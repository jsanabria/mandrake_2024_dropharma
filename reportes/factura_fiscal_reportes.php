<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once dirname(__DIR__) . "/include/connect.php";

// ---------------------------------------------------------------------
// Configuración del Motor Fiscal
// ---------------------------------------------------------------------
$FISCAL_DIR = "C:\\laragon\\www\\mandrake_novedades\\MandrakeFiscal";
$FISCAL_EXE = $FISCAL_DIR . "\\FiscalPrinterV2.exe";

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function safe_utf8($str) {
    $str = (string)$str;
    if ($str === "") return $str;

    if (mb_check_encoding($str, 'UTF-8')) {
        return $str;
    }

    // El .exe puede escribir acentos en el codepage de consola de Windows
    // (CP1252 / CP850) en vez de UTF-8; sin esto, htmlspecialchars() se
    // traga el bloque completo si hay bytes inválidos.
    foreach (array('Windows-1252', 'CP850', 'CP437', 'ISO-8859-1') as $enc) {
        $try = @iconv($enc, 'UTF-8//IGNORE', $str);
        if ($try !== false && $try !== "" && mb_check_encoding($try, 'UTF-8')) {
            return $try;
        }
    }

    return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
}

function run_fiscal_cmd($cmd) {
    global $FISCAL_DIR;

    $descriptors = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w"),
    );

    // En Windows, cuando el ejecutable va entre comillas y se ejecuta desde
    // Apache/PHP, conviene envolverlo con cmd.exe /S /C "...".
    // Ejemplo final:
    // cmd.exe /S /C ""C:\ruta\FiscalPrinterV2.exe" INFOJSON"
    //
    // Importante: $cmd debe venir con comillas manuales, NO usar escapeshellarg()
    // para evitar triples comillas.
    $cmd_win = 'cmd.exe /S /C "' . $cmd . '"';

    // Forzamos el cwd real del motor fiscal, la carpeta donde vive el .exe.
    $proc = proc_open($cmd_win, $descriptors, $pipes, $FISCAL_DIR);

    $salida = "";
    $err = "";
    $ret = -1;

    if (is_resource($proc)) {
        fclose($pipes[0]);
        $salida = trim(stream_get_contents($pipes[1]));
        $err = trim(stream_get_contents($pipes[2]));
        fclose($pipes[1]);
        fclose($pipes[2]);
        $ret = proc_close($proc);

        if ($salida === "" && $err !== "") {
            $salida = $err;
        }
    }

    return array(
        "last" => $salida,
        "raw" => $salida,
        "exit_code" => $ret
    );
}

function parse_json_response($text) {
    $text = safe_utf8($text);
    $text = trim($text);
    $json = json_decode($text, true);
    if (is_array($json)) return $json;

    $lines = preg_split('/\r\n|\r|\n/', $text);
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $line = trim($lines[$i]);
        if ($line === "") continue;
        $json = json_decode($line, true);
        if (is_array($json)) return $json;
    }

    return null;
}


function execute_scalar($link, $sql) {
    $rs = mysqli_query($link, $sql);
    if (!$rs) return "";
    $row = mysqli_fetch_row($rs);
    return $row ? $row[0] : "";
}

function get_parametro_040($link) {
    $sql = "SELECT valor1, valor2 FROM parametro WHERE codigo = '040' LIMIT 1";
    $rs = mysqli_query($link, $sql);

    if (!$rs) return array("", "");
    $row = mysqli_fetch_assoc($rs);
    if (!$row) return array("", "");

    return array(trim($row["valor1"]), trim($row["valor2"]));
}

function guardar_parametro_040($link, $puerto, $serial = "") {
    $puerto = mysqli_real_escape_string($link, trim($puerto));
    $serial = mysqli_real_escape_string($link, trim($serial));

    $existe = execute_scalar($link, "SELECT COUNT(*) FROM parametro WHERE codigo = '040'");

    if ((int)$existe > 0) {
        mysqli_query($link, "
            UPDATE parametro
               SET valor1 = '$puerto',
                   valor2 = '$serial',
                   descripcion = 'PUERTO COM Y SERIAL IMPRESORA FISCAL'
             WHERE codigo = '040'
        ");
    } else {
        mysqli_query($link, "
            INSERT INTO parametro (codigo, descripcion, valor1, valor2)
            VALUES ('040', 'PUERTO COM Y SERIAL IMPRESORA FISCAL', '$puerto', '$serial')
        ");
    }
}

function obtener_info_impresora_json($exe, $com = "") {
    $cmd = '"' . $exe . '" INFOJSON';
    if (trim($com) != "") {
        $cmd .= ' "' . trim($com) . '"';
    }

    $result = run_fiscal_cmd($cmd);
    $raw = $result["raw"];
    $json = parse_json_response($raw);

    return array($json, $raw, $cmd);
}

function detectar_impresora_fiscal($link, $exe) {
    list($puerto_guardado, $serial_guardado) = get_parametro_040($link);

    // 1) Si existe puerto guardado, probar primero ese puerto.
    if ($puerto_guardado != "") {
        list($json, $raw, $cmd) = obtener_info_impresora_json($exe, $puerto_guardado);

        if (is_array($json) && !empty($json["success"])) {
            $puerto = isset($json["puerto"]) ? trim($json["puerto"]) : $puerto_guardado;
            $serial = isset($json["serialFiscal"]) ? trim($json["serialFiscal"]) : $serial_guardado;
            guardar_parametro_040($link, $puerto, $serial);
            return array($puerto, $serial, $json, $raw, $cmd);
        }
    }

    // 2) Si no existe o falló, pedir autodetección al motor fiscal.
    list($json, $raw, $cmd) = obtener_info_impresora_json($exe, "");

    if (is_array($json) && !empty($json["success"])) {
        $puerto = isset($json["puerto"]) ? trim($json["puerto"]) : "";
        $serial = isset($json["serialFiscal"]) ? trim($json["serialFiscal"]) : "";

        if ($puerto != "") {
            guardar_parametro_040($link, $puerto, $serial);
            return array($puerto, $serial, $json, $raw, $cmd);
        }
    }

    return array("", "", $json, $raw, $cmd);
}

function comando_permitido($documento) {
    $documento = strtoupper(trim($documento));

    switch ($documento) {
        case "X":
        case "RX":
        case "REPX":
            return "X";
        case "Z":
        case "RZ":
        case "REPZ":
            return "Z";
        case "INFO":
            return "INFO";
        case "INFOJSON":
            return "INFOJSON";
        case "CLOSE":
            return "CLOSE";
        default:
            return "";
    }
}

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
$username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : "NA.NA";
$documento = isset($_REQUEST["documento"]) ? strtoupper(trim($_REQUEST["documento"])) : "INFOJSON";
$com = isset($_REQUEST["com"]) ? strtoupper(trim($_REQUEST["com"])) : "";

$tipo = comando_permitido($documento);
$ok = false;
$title = "Impresora Fiscal";
$message = "";
$raw = "";
$json = null;
$cmd = "";

$serialFiscal = "";
$com_detectado = "";
$cmd_info = "";

if (!file_exists($FISCAL_EXE)) {
    $title = "No se encontró el motor fiscal";
    $message = "No existe FiscalPrinterV2.exe en: " . $FISCAL_EXE;
} elseif ($tipo == "") {
    $title = "Comando inválido";
    $message = "Comando no permitido. Use X, Z, INFO, INFOJSON o CLOSE.";
} else {
    // Primero detectamos/probamos la impresora y sincronizamos parametro 040:
    // valor1 = COM activo, valor2 = serial fiscal.
    list($com_detectado, $serialFiscal, $info_json, $info_raw, $cmd_info) = detectar_impresora_fiscal($link, $FISCAL_EXE);

    if ($com_detectado == "") {
        $raw = $info_raw;
        $json = $info_json;
        $cmd = $cmd_info;
        $ok = false;
        $title = "No hay comunicación con la impresora fiscal";
        $message = "No se pudo detectar el puerto COM activo ni consultar el serial de la impresora.";
    } else {
        // Para INFOJSON usamos directamente la respuesta de detección.
        // Para los demás comandos, ejecutamos con el COM detectado.
        if ($tipo == "INFOJSON") {
            $raw = $info_raw;
            $json = $info_json;
            $cmd = $cmd_info;
        } else {
            $cmd = '"' . $FISCAL_EXE . '" ' . $tipo . ' "' . $com_detectado . '"';
            $result = run_fiscal_cmd($cmd);
            $raw = $result["raw"];
            $json = parse_json_response($raw);
        }

        if (is_array($json)) {
            $ok = !empty($json["success"]);
            $message = isset($json["message"]) ? $json["message"] : ($ok ? "Proceso ejecutado correctamente." : "El motor fiscal respondió error.");
        } else {
            // Reportes X/Z pueden devolver texto simple o JSON según versión del EXE.
            $ok = (stripos($raw, "error") === false && stripos($raw, "no conecta") === false && trim($raw) != "");
            $message = $ok ? "Comando enviado al motor fiscal." : "No se pudo confirmar la ejecución del comando.";
        }

        if ($ok) {
            if ($tipo == "X") $title = "Reporte X enviado correctamente";
            elseif ($tipo == "Z") $title = "Reporte Z enviado correctamente";
            elseif ($tipo == "CLOSE") $title = "Cierre/recuperación enviado";
            else $title = "Comunicación fiscal correcta";
        } else {
            $title = "Error ejecutando comando fiscal";
        }

        $com = $com_detectado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Impresora Fiscal</title>
<style>
    body { margin:0; padding:25px; font-family:Arial, Helvetica, sans-serif; background:#f4f6f9; color:#263238; }
    .card { max-width:920px; margin:25px auto; background:#fff; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,.12); overflow:hidden; border:1px solid #e6e9ef; }
    .header { padding:18px 24px; color:#fff; background:<?php echo $ok ? '#198754' : '#dc3545'; ?>; }
    .header h2 { margin:0; font-size:22px; }
    .body { padding:22px 24px; }
    .badge { display:inline-block; padding:6px 10px; border-radius:20px; font-size:12px; font-weight:bold; color:#fff; background:<?php echo $ok ? '#198754' : '#dc3545'; ?>; margin-bottom:12px; }
    .grid { display:grid; grid-template-columns:190px 1fr; gap:8px 14px; margin-top:12px; }
    .label { color:#607d8b; font-weight:bold; }
    pre { background:#101820; color:#e8f5e9; padding:14px; border-radius:8px; overflow:auto; font-size:13px; }
    .actions { margin-top:22px; display:flex; gap:10px; flex-wrap:wrap; }
    .btn { display:inline-block; text-decoration:none; background:#0d6efd; color:#fff; padding:9px 14px; border-radius:6px; font-weight:bold; }
    .btn-secondary { background:#6c757d; }
    .btn-warning { background:#fd7e14; color:#111; }
    .btn-danger { background:#dc3545; }
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h2><?php echo h($title); ?></h2>
    </div>
    <div class="body">
        <span class="badge"><?php echo $ok ? "OK" : "ERROR"; ?></span>

        <div class="grid">
            <div class="label">Documento ID</div><div><?php echo h($id); ?></div>
            <div class="label">Comando</div><div><?php echo h($tipo); ?></div>
            <div class="label">Puerto COM</div><div><?php echo h($com); ?></div>
            <div class="label">Serial consultado</div><div><?php echo h($serialFiscal); ?></div>
            <div class="label">Mensaje</div><div><?php echo h($message); ?></div>
            <?php if (is_array($json)) { ?>
                <div class="label">Puerto detectado</div><div><?php echo h(isset($json["puerto"]) ? $json["puerto"] : ""); ?></div>
                <div class="label">Serial fiscal</div><div><?php echo h(isset($json["serialFiscal"]) ? $json["serialFiscal"] : ""); ?></div>
                <div class="label">Factura</div><div><?php echo h(isset($json["numeroFactura"]) ? $json["numeroFactura"] : ""); ?></div>
                <div class="label">Nota crédito</div><div><?php echo h(isset($json["numeroNotaCredito"]) ? $json["numeroNotaCredito"] : ""); ?></div>
                <div class="label">Nota débito</div><div><?php echo h(isset($json["numeroNotaDebito"]) ? $json["numeroNotaDebito"] : ""); ?></div>
                <div class="label">Estado impresora</div><div><?php echo h(isset($json["estadoImpresora"]) ? $json["estadoImpresora"] : ""); ?></div>
                <div class="label">Error impresora</div><div><?php echo h(isset($json["errorImpresora"]) ? $json["errorImpresora"] : ""); ?></div>
            <?php } ?>
        </div>

        <h3>Respuesta del motor fiscal</h3>
        <pre><?php echo h(safe_utf8($raw)); ?></pre>

        <h3>Comando ejecutado</h3>
        <pre><?php echo h($cmd); ?></pre>

        <div class="actions">
            <a class="btn" href="?id=INFOJSON">INFOJSON</a>
            <a class="btn" href="?id=INFO">INFO</a>
            <a class="btn btn-warning" href="?id=REPX">Reporte X</a>
            <a class="btn btn-danger" href="?id=REPZ" onclick="return confirm('El Reporte Z cierra el día fiscal. ¿Seguro que desea continuar?');">Reporte Z</a>
            <a class="btn btn-secondary" href="?id=CLOSE" onclick="return confirm('CLOSE intenta cerrar una transacción abierta. Úselo solo para recuperar la impresora. ¿Continuar?');">CLOSE</a>
            <a class="btn btn-secondary" href="javascript:history.back()">Volver</a>
        </div>
    </div>
</div>
</body>
</html>