<?php
session_start();
require('rcs/fpdf.php');
require("../include/connect.php");

$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
$username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : "";
$test_fiscal = isset($_REQUEST["test_fiscal"]) ? intval($_REQUEST["test_fiscal"]) : 0;
$auto_return = isset($_REQUEST["auto_return"]) ? intval($_REQUEST["auto_return"]) : 0;
$generar_ne = strtoupper(trim($_REQUEST["generar_ne"] ?? "N"));

// ---------------------------------------------------------------------
// Configuración del Motor Fiscal
// ---------------------------------------------------------------------
$FISCAL_DIR = "C:\\laragon\\www\\mandrake_2024_dropharma\\MandrakeFiscal";
$FISCAL_EXE = $FISCAL_DIR . "\\FiscalPrinterV2.exe";
$FISCAL_WORK_DIR = $FISCAL_DIR . "\\Temp";
$FISCAL_LOG_DIR = $FISCAL_DIR . "\\Logs";

// Modo seguro para pruebas: genera .dat, llama al motor, muestra respuesta,
// pero NO actualiza salidas como fiscal real. Cámbialo a false al pasar a producción.
$MODO_PRUEBA_FISCAL = false;

// Debug visual del comando ejecutado
$DEBUG_FISCAL = false;
$debug_cmd = "";
$debug_cwd = "";
$debug_raw = "";
$debug_json_error = "";
$debug_param040 = "";

if (!file_exists($FISCAL_EXE)) {
    die("No se encontró el Motor Fiscal en: " . $FISCAL_EXE);
}

if (!is_dir($FISCAL_WORK_DIR)) {
    mkdir($FISCAL_WORK_DIR, 0777, true);
}

if (!is_dir($FISCAL_LOG_DIR)) {
    mkdir($FISCAL_LOG_DIR, 0777, true);
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function safe_utf8($str) {
    $str = (string)$str;
    if ($str === "") return $str;

    // Si ya es UTF-8 válido, no tocar nada.
    if (mb_check_encoding($str, 'UTF-8')) {
        return $str;
    }

    // El .exe probablemente escribe acentos en el codepage de consola de
    // Windows (CP1252 / CP850 / CP437), no en UTF-8. Probamos en ese orden.
    foreach (array('Windows-1252', 'CP850', 'CP437', 'ISO-8859-1') as $enc) {
        $try = @iconv($enc, 'UTF-8//IGNORE', $str);
        if ($try !== false && $try !== "" && mb_check_encoding($try, 'UTF-8')) {
            return $try;
        }
    }

    // Último recurso: forzar limpieza de bytes inválidos para que al menos
    // se pueda mostrar algo en pantalla sin que h() se trague todo.
    return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
}

function dat_value($v) {
    $v = trim((string)$v);
    $v = str_replace(array("\r", "\n", "|"), array(" ", " ", "/"), $v);
    return $v;
}

function money_dot($v, $dec = 2) {
    return number_format((float)$v, $dec, ".", "");
}

function execute_scalar($link, $sql) {
    $rs = mysqli_query($link, $sql);
    if (!$rs) return "";
    $row = mysqli_fetch_row($rs);
    return $row ? $row[0] : "";
}

function run_fiscal_cmd($cmd, $cwd = "") {
    // Mantenida por compatibilidad. Usa ejecución simple tipo script original.
    return run_cmd_capture($cmd);
}

function run_cmd_capture($cmd) {
    global $debug_cmd, $debug_cwd, $debug_raw, $FISCAL_DIR;

    $debug_cmd = $cmd;
    $debug_cwd = $FISCAL_DIR;

    $descriptors = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w"),
    );

    // OJO: forzamos el cwd real del motor fiscal, aquí estaba el problema.
    $proc = proc_open($cmd, $descriptors, $pipes, $FISCAL_DIR);

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

        // Si el comando ya tenía "2>&1", stderr suele venir vacío o mezclado en stdout.
        // Igual lo dejamos disponible para depurar por separado.
        if ($salida === "" && $err !== "") {
            $salida = $err;
        }
    }

    // DEBUG CRUDO: longitud real en bytes y hexdump de los primeros bytes.
    // Esto nos permite ver BOM, UTF-16, o basura invisible que rompe json_decode
    // aunque en pantalla "se vea" como JSON normal.
    $len = strlen($salida);
    $hex_preview = bin2hex(substr($salida, 0, 80));

    // Normalizamos a UTF-8 válido SOLO para mostrar en pantalla; de lo
    // contrario htmlspecialchars() se traga el bloque completo si hay
    // bytes inválidos (por eso antes no veías esta sección).
    $salida_debug = safe_utf8($salida);
    $err_debug = safe_utf8($err);

    $debug_raw =
        "EXIT CODE: " . $ret . "\n" .
        "BYTES TOTALES: " . $len . "\n" .
        "HEX (primeros 80 bytes): " . $hex_preview . "\n" .
        "STDERR:\n" . $err_debug . "\n" .
        "OUTPUT:\n" . $salida_debug;

    return $salida;
}

function parse_json_response($text) {
    global $debug_json_error;
    $debug_json_error = "";

    // 1) Quitar BOM UTF-8 (EF BB BF) si viene
    if (substr($text, 0, 3) === "\xEF\xBB\xBF") {
        $text = substr($text, 3);
    }

    // 2) Detectar UTF-16LE/BE (típico en exe de consola .NET/Windows) y convertir
    if (substr($text, 0, 2) === "\xFF\xFE") {
        $text = mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16LE');
    } elseif (substr($text, 0, 2) === "\xFE\xFF") {
        $text = mb_convert_encoding(substr($text, 2), 'UTF-8', 'UTF-16BE');
    } elseif (preg_match('/\x00/', substr($text, 0, 20))) {
        // No hay BOM pero hay bytes nulos intercalados: casi seguro es UTF-16LE sin BOM
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-16LE');
    }

    // 3) Si sigue sin ser UTF-8 válido, probablemente viene en el codepage
    // de consola de Windows (CP1252/CP850) por los acentos. Normalizamos.
    $text = safe_utf8($text);

    $text = trim($text);
    $json = json_decode($text, true);
    if (is_array($json)) return $json;
    $debug_json_error = "json_decode falló en texto completo: " . json_last_error_msg();

    $lines = preg_split('/\r\n|\r|\n/', $text);
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $line = trim($lines[$i]);
        if ($line === "") continue;
        $json = json_decode($line, true);
        if (is_array($json)) return $json;
    }

    return null;
}

function get_parametro_040($link) {
    $sql = "SELECT valor1, valor2 FROM parametro WHERE codigo = '040' LIMIT 1";
    $rs = mysqli_query($link, $sql);

    if (!$rs) return array("", "");

    $row = mysqli_fetch_assoc($rs);
    if (!$row) return array("", "");

    return array(trim($row["valor1"]), trim($row["valor2"]));
}

function get_serial_fiscal_param($link) {
    list($puerto, $serial) = get_parametro_040($link);
    return $serial;
}

function guardar_parametro_040($link, $puerto, $serial = "") {
    global $debug_param040;
    $debug_param040 = "";

    $puerto = mysqli_real_escape_string($link, trim($puerto));
    $serial = mysqli_real_escape_string($link, trim($serial));

    $existe = execute_scalar($link, "SELECT COUNT(*) FROM parametro WHERE codigo = '040'");

    if ((int)$existe > 0) {
        $res = mysqli_query($link, "
            UPDATE parametro
               SET valor1 = '$puerto',
                   valor2 = '$serial',
                   descripcion = 'PUERTO COM Y SERIAL IMPRESORA FISCAL'
             WHERE codigo = '040'
        ");
        if ($res === false) {
            $debug_param040 = "UPDATE falló: " . mysqli_error($link);
        } else {
            $debug_param040 = "UPDATE ejecutado. Filas afectadas: " . mysqli_affected_rows($link)
                . " (valor1='$puerto', valor2='$serial')";
        }
    } else {
        $res = mysqli_query($link, "
            INSERT INTO parametro (codigo, descripcion, valor1, valor2)
            VALUES (
                '040',
                'PUERTO COM Y SERIAL IMPRESORA FISCAL',
                '$puerto',
                '$serial'
            )
        ");
        if ($res === false) {
            $debug_param040 = "INSERT falló: " . mysqli_error($link);
        } else {
            $debug_param040 = "INSERT ejecutado. Filas afectadas: " . mysqli_affected_rows($link)
                . " (valor1='$puerto', valor2='$serial')";
        }
    }
}

function obtener_info_impresora_json($exe, $com) {
    // Ejecución simple, igual al script original:
    // C:\...\FiscalPrinterV2.exe INFOJSON [COMx]
    if (trim($com) != "") {
        $cmd = $exe . ' INFOJSON "' . trim($com) . '" 2>&1';
    } else {
        $cmd = $exe . ' INFOJSON 2>&1';
    }

    $raw = run_cmd_capture($cmd);
    $json = parse_json_response($raw);

    if (!is_array($json)) {
        return array(null, $raw);
    }

    return array($json, $raw);
}

function detectar_puerto_fiscal($link, $exe) {
    list($puerto_guardado, $serial_guardado) = get_parametro_040($link);

    // 1) Si existe puerto guardado, probar SOLO ese puerto.
    if ($puerto_guardado != "") {
        list($json, $raw) = obtener_info_impresora_json($exe, $puerto_guardado);

        if (is_array($json) && !empty($json["success"])) {
            $puerto = isset($json["puerto"]) ? trim($json["puerto"]) : $puerto_guardado;
            $serial = isset($json["serialFiscal"]) ? trim($json["serialFiscal"]) : $serial_guardado;

            guardar_parametro_040($link, $puerto, $serial);
            return array($puerto, $serial, $json, $raw);
        }
    }

    // 2) Si falló o no existe, pedir autodetección al motor fiscal. NO escanear COM1-COM20 en PHP.
    list($json, $raw) = obtener_info_impresora_json($exe, "");

    if (is_array($json) && !empty($json["success"])) {
        $puerto = isset($json["puerto"]) ? trim($json["puerto"]) : "";
        $serial = isset($json["serialFiscal"]) ? trim($json["serialFiscal"]) : "";

        if ($puerto != "") {
            guardar_parametro_040($link, $puerto, $serial);
            return array($puerto, $serial, $json, $raw);
        }
    }

    return array("", "", $json, $raw);
}

function map_tipo_fiscal($documento, $tipo_documento) {
    $documento = strtoupper(trim($documento));
    $tipo_documento = strtoupper(trim($tipo_documento));

    if (in_array($documento, array("FC", "NC", "ND"))) return $documento;

    if ($tipo_documento == "TDCFCV") return "FC";
    if ($tipo_documento == "TDCNCR" || $tipo_documento == "TDCNCV" || $tipo_documento == "TDCNCC") return "NC";
    if ($tipo_documento == "TDCNDB" || $tipo_documento == "TDCNDV") return "ND";

    return $documento;
}

function generar_dat_fiscal($link, $id, $username, $puerto, $work_dir, $serial_fiscal = "") {
    global $MODO_PRUEBA_FISCAL;
    $id = intval($id);

    $sql = "
        SELECT
            s.id,
            DATE_FORMAT(s.fecha, '%d/%m/%Y') AS fecha_afectada,
            s.fecha,
            s.cliente,
            s.nro_documento,
            s.nro_control,
            s.tipo_documento,
            s.estatus,
            s.username,
            s.documento,
            IFNULL(s.moneda, 'Bs.') AS moneda,
            IFNULL(s.tasa_dia, 1) AS tasa_dia,
            IFNULL(s.monto_base_igtf, 0) AS monto_base_igtf,
            IFNULL(s.igtf_alicuota, 0) AS igtf_alicuota,
            IFNULL(s.doc_afectado, '') AS doc_afectado,
            c.ci_rif,
            c.nombre,
            c.direccion,
            c.telefono1
        FROM salidas s
            LEFT JOIN cliente c ON c.id = s.cliente
        WHERE s.id = $id
        LIMIT 1";

    $rs = mysqli_query($link, $sql);
    if (!$rs) return array(false, "", "Error consultando cabecera: " . mysqli_error($link));

    $cab = mysqli_fetch_assoc($rs);
    if (!$cab) return array(false, "", "No existe el documento ID $id.");

    $tipo = map_tipo_fiscal($cab["documento"], $cab["tipo_documento"]);

    $lines = array();
    $lines[] = "COM=" . dat_value($puerto);
    $lines[] = "TIPO=" . dat_value($tipo);
    $lines[] = "ID=" . $id;
    $lines[] = "USUARIO=" . dat_value($username != "" ? $username : $cab["username"]);
    $lines[] = "";

    if ($tipo == "NC" || $tipo == "ND") {
        $doc_afectado = trim($cab["doc_afectado"]);
        $fecha_afectada = "";

        if ($doc_afectado != "") {
            $doc_afectado_sql = mysqli_real_escape_string($link, $doc_afectado);
            $fecha_afectada = execute_scalar($link, "
                SELECT DATE_FORMAT(fecha, '%d/%m/%Y')
                FROM salidas
                WHERE nro_documento = '$doc_afectado_sql'
                LIMIT 1
            ");
        }

        $serial = trim($serial_fiscal);
        if ($serial == "") $serial = get_serial_fiscal_param($link);

        $lines[] = "FACTURA_AFECTADA=" . dat_value(str_pad($doc_afectado, 8, "0", STR_PAD_LEFT));
        $lines[] = "FECHA_AFECTADA=" . dat_value($fecha_afectada);
        $lines[] = "SERIAL_AFECTADA=" . dat_value($serial);
        $lines[] = "";
    }

    $lines[] = "RIF=" . dat_value($cab["ci_rif"]);
    $lines[] = "CLIENTE=" . dat_value($cab["nombre"]);
    $lines[] = "DIRECCION=" . dat_value($cab["direccion"]);
    $lines[] = "TELEFONO=" . dat_value($cab["telefono1"]);
    $lines[] = "";

    $moneda = trim($cab["moneda"]);
    if ($moneda == "") $moneda = "Bs.";

    $lines[] = "MONEDA=" . dat_value($moneda);
    $lines[] = "TASA=" . money_dot($cab["tasa_dia"], 4);
    $lines[] = "";

    // NOTA: el .exe ya NO usa este flag para decidir si cierra con el comando
    // 199 — ahora decide mirando la moneda de cada PAGO en el .dat (más
    // confiable que un flag calculado aparte). Como más abajo todavía armamos
    // un único pago fijo en Bs. (ver bloque "PAGOS="), este flag se deja en
    // "N" para reflejar la realidad de lo que se envía a imprimir, y no
    // confundir con lo que diga monto_base_igtf/igtf_alicuota en salidas.
    // El día que se conecten pagos reales multi-moneda, esto debe volver a
    // calcularse (o mejor, eliminarse del todo y dejar que decida el .exe).
    $igtf_aplica = "N";
    $lines[] = "IGTF_APLICA=" . $igtf_aplica;
    $lines[] = "IGTF_ALICUOTA=" . money_dot($cab["igtf_alicuota"], 2);
    // Le indicamos al motor fiscal (.exe) si debe imprimir como documento
    // NO fiscal (PrintTest) o como documento fiscal real (PrintFiscal),
    // usando el mismo flag que ya controla el resto del flujo en PHP.
    $lines[] = "MODO_PRUEBA=" . ($MODO_PRUEBA_FISCAL ? "S" : "N");
    $lines[] = "";

    $sql_det = "
        SELECT
            IFNULL(
                NULLIF(
                    CONCAT_WS(' ',
                        NULLIF(TRIM(a.nombre_comercial), ''),
                        NULLIF(TRIM(a.principio_activo), ''),
                        NULLIF(TRIM(a.presentacion), '')
                    ),
                    ''
                ),
                CONCAT('ARTICULO ', es.articulo)
            ) AS descripcion,
            IFNULL(es.cantidad_articulo, 0) AS cantidad,
            IFNULL(es.precio_unidad, 0) AS precio,
            IFNULL(es.alicuota, 0) AS alicuota
        FROM entradas_salidas es
            LEFT JOIN articulo a ON a.id = es.articulo
        WHERE es.id_documento = $id
            AND es.tipo_documento = '" . mysqli_real_escape_string($link, $cab["tipo_documento"]) . "'
        ORDER BY es.id";

    $rs_det = mysqli_query($link, $sql_det);
    if (!$rs_det) return array(false, "", "Error consultando detalle: " . mysqli_error($link));

    $items = array();
    while ($d = mysqli_fetch_assoc($rs_det)) {
        $items[] = dat_value($d["descripcion"]) . "|" .
                   money_dot($d["cantidad"], 3) . "|" .
                   money_dot($d["precio"], 2) . "|" .
                   money_dot($d["alicuota"], 2);
    }

    if (count($items) == 0) return array(false, "", "El documento no tiene detalle.");

    $lines[] = "ITEMS=" . count($items);
    foreach ($items as $i => $line) {
        $lines[] = "ITEM" . ($i + 1) . "=" . $line;
    }
    $lines[] = "";

    // Pagos reales del documento. Cualquier medio de pago que no esté en la
    // lista conocida (EF, TD, TC, TR, PM, CH) se manda tal cual al .dat con
    // su código original — el .exe (GetPaymentCode) ya sabe tratar cualquier
    // código desconocido como Efectivo en la moneda que traiga cada pago
    // (nacional -> "01", divisa -> "20"), así que RC/CS/RD (abonos, CASHEA,
    // etc.) quedan cubiertos sin necesidad de mapearlos aquí uno por uno.
    $sql_pagos = "
        SELECT
            b.metodo_pago,
            b.moneda,
            b.monto_moneda
        FROM cobros_cliente AS a
            JOIN cobros_cliente_detalle AS b ON b.cobros_cliente = a.id
        WHERE a.id_documento = $id
        ORDER BY b.id";

    $rs_pagos = mysqli_query($link, $sql_pagos);
    if (!$rs_pagos) return array(false, "", "Error consultando pagos: " . mysqli_error($link));

    $descripciones_pago = array(
        "EF" => "EFECTIVO",
        "TD" => "TARJETA DEBITO",
        "TC" => "TARJETA CREDITO",
        "TR" => "TRANSFERENCIA",
        "PM" => "PAGO MOVIL",
        "CH" => "CHEQUE",
    );

    $pagos = array();
    while ($p = mysqli_fetch_assoc($rs_pagos)) {
        $codigo = trim($p["metodo_pago"]);
        $moneda_pago = trim($p["moneda"]);
        if ($moneda_pago == "") $moneda_pago = "Bs.";

        // Medios de pago conocidos usan su nombre real; cualquier otro
        // (RC = abono/anticipo, CS = CASHEA, etc.) se manda con su propio
        // código como descripción — el .exe igual lo va a tratar como
        // Efectivo en la moneda que traiga, así que el nombre exacto aquí
        // es solo informativo para lo que se imprime en el ticket.
        $descripcion = isset($descripciones_pago[$codigo]) ? $descripciones_pago[$codigo] : $codigo;

        $pagos[] = dat_value($codigo) . "|" .
                   dat_value($descripcion) . "|" .
                   money_dot($p["monto_moneda"], 2) . "|" .
                   dat_value($moneda_pago);
    }

    // Si por algún motivo el documento no tiene pagos registrados en
    // cobros_cliente_detalle (dato faltante, no que el cliente no haya
    // pagado), caemos al total en efectivo/Bs. como red de seguridad para
    // no bloquear la impresión.
    if (count($pagos) == 0) {
        $total_bs = execute_scalar($link, "SELECT IFNULL(total, 0) FROM salidas WHERE id = $id");
        $pagos[] = "EF|EFECTIVO|" . money_dot($total_bs, 2) . "|Bs.";
    }

    $lines[] = "PAGOS=" . count($pagos);
    foreach ($pagos as $i => $line) {
        $lines[] = "PAGO" . ($i + 1) . "=" . $line;
    }

    $file = rtrim($work_dir, "\\/") . "\\doc_" . $id . "_" . $tipo . ".dat";
    $content = implode("\r\n", $lines) . "\r\n";

    if (file_put_contents($file, $content) === false) {
        return array(false, "", "No se pudo crear el archivo DAT: $file");
    }

    return array(true, $file, "");
}


function solo_numero_fiscal($valor) {
    $valor = preg_replace('/\D+/', '', (string)$valor);
    return intval($valor);
}

function sincronizar_documento_consecutivo_fiscal($link, $tipo, $numero_doc, $numero_ctrl) {
    $tipo = strtoupper(trim($tipo));
    if (!in_array($tipo, array("FC", "NC", "ND"))) {
        return true;
    }

    $tipo_documento = mysqli_real_escape_string($link, "TDCFCV");
    $serie_doc = mysqli_real_escape_string($link, $tipo . "_DOC");
    $serie_ctrl = mysqli_real_escape_string($link, $tipo . "_CTRL");

    $num_doc = solo_numero_fiscal($numero_doc);
    $num_ctrl = solo_numero_fiscal($numero_ctrl);

    if ($num_doc > 0) {
        mysqli_query($link, "
            INSERT IGNORE INTO documento_consecutivo
                (tipo_documento, serie, ultimo_numero, updated_at)
            VALUES
                ('$tipo_documento', '$serie_doc', 0, NOW())
        ");

        mysqli_query($link, "
            UPDATE documento_consecutivo
               SET ultimo_numero = GREATEST(IFNULL(ultimo_numero, 0), $num_doc),
                   updated_at = NOW()
             WHERE tipo_documento = '$tipo_documento'
               AND serie = '$serie_doc'
        ");
    }

    if ($num_ctrl > 0) {
        mysqli_query($link, "
            INSERT IGNORE INTO documento_consecutivo
                (tipo_documento, serie, ultimo_numero, updated_at)
            VALUES
                ('$tipo_documento', '$serie_ctrl', 0, NOW())
        ");

        mysqli_query($link, "
            UPDATE documento_consecutivo
               SET ultimo_numero = GREATEST(IFNULL(ultimo_numero, 0), $num_ctrl),
                   updated_at = NOW()
             WHERE tipo_documento = '$tipo_documento'
               AND serie = '$serie_ctrl'
        ");
    }

    return true;
}

function registrar_auditoria_fiscal($link, $id, $tipo, $numero, $control, $username) {
    $id = intval($id);
    $tipo = strtoupper(trim($tipo));

    $nombre = "Documento";
    if ($tipo == "FC") $nombre = "Factura";
    elseif ($tipo == "NC") $nombre = "Nota de Crédito";
    elseif ($tipo == "ND") $nombre = "Nota de Débito";

    $usuario = mysqli_real_escape_string($link, trim($username) != "" ? trim($username) : "NA.NA");
    $numero_sql = mysqli_real_escape_string($link, $numero);
    $control_sql = mysqli_real_escape_string($link, $control);
    $fecha_txt = date("d/m/Y H:i:s");
    $script = mysqli_real_escape_string($link, "Emitió documento $nombre # $numero con # de control $control de fecha $fecha_txt (IMPRESORA FISCAL)");

    mysqli_query($link, "
        INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (NULL, '" . date("Y-m-d H:i:s") . "',
             '$script', '$usuario', 'SENIAT: U', 'view_out_tdcfcv', 'id', '$id', '', '$numero_sql')
    ");
}

function actualizar_salida_post_fiscal($link, $id, $json) {
    global $MODO_PRUEBA_FISCAL, $username;

    if ($MODO_PRUEBA_FISCAL) {
        return true;
    }

    $id = intval($id);
    $tipo = isset($json["tipo"]) ? strtoupper(trim($json["tipo"])) : "";

    $numero = "";
    if ($tipo == "FC") {
        $numero = isset($json["numeroFactura"]) ? $json["numeroFactura"] : "";
    } elseif ($tipo == "NC") {
        $numero = isset($json["numeroNotaCredito"]) ? $json["numeroNotaCredito"] : "";
    } elseif ($tipo == "ND") {
        $numero = isset($json["numeroNotaDebito"]) ? $json["numeroNotaDebito"] : "";
    }

    $control = isset($json["numeroControl"]) && trim($json["numeroControl"]) != ""
        ? $json["numeroControl"]
        : $numero;

    $numero_sql = mysqli_real_escape_string($link, $numero);
    $control_sql = mysqli_real_escape_string($link, $control);
    $usuario_sql = mysqli_real_escape_string($link, trim($username) != "" ? trim($username) : "NA.NA");

    $sql = "
        UPDATE salidas
        SET
            fecha = '" . date("Y-m-d H:i:s") . "',
            nro_documento = '$numero_sql',
            nro_control = '$control_sql',
            estatus = 'PROCESADO',
            impreso = 'S',
            username = '$usuario_sql'
        WHERE id = $id
          AND (nro_documento IS NULL OR nro_documento = '')";

    $ok_update = mysqli_query($link, $sql);
    if (!$ok_update) {
        return false;
    }

    sincronizar_documento_consecutivo_fiscal($link, $tipo, $numero, $control);
    registrar_auditoria_fiscal($link, $id, $tipo, $numero, $control, $username);

    return true;
}

$ok = false;
$ya_impreso = false;
$title = "Impresora Fiscal";
$detail = "";
$dat_file = "";
$raw_response = "";
$json = null;
$puerto = "";
$serialFiscal = "";
$info_json = null;

if ($test_fiscal == 1) {
    list($puerto, $serialFiscal, $json, $raw_response) = detectar_puerto_fiscal($link, $FISCAL_EXE);

    if ($puerto != "" && is_array($json) && !empty($json["success"])) {
        $ok = true;
        $title = "Comunicación fiscal correcta";
        $detail = "Impresora fiscal detectada correctamente.";
    } else {
        $title = "No hay comunicación con la impresora fiscal";
        $detail = "No se pudo comunicar con la impresora fiscal usando el puerto guardado ni autodetección.";
    }
} elseif ($id <= 0) {
    $detail = "Debe indicar un ID de documento válido.";
} elseif (!file_exists($FISCAL_EXE)) {
    $detail = "No se encontró FiscalPrinterV2.exe en: " . $FISCAL_EXE;
} else {
    // -----------------------------------------------------------------
    // Guardia anti-doble-impresión: si el documento YA quedó marcado
    // como impreso fiscalmente (fuera de modo prueba), no se vuelve a
    // ejecutar el motor fiscal aunque recarguen (F5) o vuelvan atrás.
    // -----------------------------------------------------------------
    $doc_previo = null;
    $rs_doc_previo = mysqli_query($link, "SELECT nro_documento, nro_control, impreso FROM salidas WHERE id = $id LIMIT 1");
    if ($rs_doc_previo) $doc_previo = mysqli_fetch_assoc($rs_doc_previo);

    if (!$MODO_PRUEBA_FISCAL && $doc_previo && $doc_previo["impreso"] == "S") {
        $ok = true;
        $ya_impreso = true;
        $title = "Documento fiscal ya emitido";
        $detail = "Este documento ya fue procesado fiscalmente anteriormente (Nro. Documento: "
            . $doc_previo["nro_documento"] . ", Nro. Control: " . $doc_previo["nro_control"]
            . "). No se puede volver a imprimir desde aquí.";
    } else {
    list($puerto, $serialFiscal, $info_json, $info_raw) = detectar_puerto_fiscal($link, $FISCAL_EXE);

    if ($puerto == "") {
        $detail = "No se pudo detectar el puerto COM de la impresora fiscal. Verifique conexión y driver USB/Serial.";
        $raw_response = $info_raw;
    } else {
        list($gen_ok, $dat_file, $gen_error) = generar_dat_fiscal($link, $id, $username, $puerto, $FISCAL_WORK_DIR, $serialFiscal);

        if (!$gen_ok) {
            $detail = $gen_error;
        } else {
            // DEBUG TEMPORAL: comparar cwd real de PHP vs carpeta del motor fiscal
            error_log("CWD antes de exec (esperado: $FISCAL_DIR) => " . getcwd());

            // Ejecución simple, igual al script original.
            $cmd = $FISCAL_EXE . ' "' . $dat_file . '" 2>&1';
            $raw_response = run_cmd_capture($cmd);
            $json = parse_json_response($raw_response);

            if (is_array($json) && !empty($json["success"])) {
                if (actualizar_salida_post_fiscal($link, $id, $json)) {
                    $ok = true;
                    $title = $MODO_PRUEBA_FISCAL ? "Prueba fiscal ejecutada correctamente" : "Documento fiscal emitido correctamente";
                    $detail = isset($json["message"]) ? $json["message"] : "Proceso exitoso.";

                    if ($MODO_PRUEBA_FISCAL) {
                        $detail .= " MODO PRUEBA: no se actualizó salidas como fiscal real.";
                    } else {
                        // Impresión real exitosa: ya no se debe poder reimprimir.
                        $ya_impreso = true;
                    }
                } else {
                    $detail = "La impresora respondió OK, pero falló el UPDATE de salidas: " . mysqli_error($link);
                }
            } else {
                $title = "No se pudo emitir el documento fiscal";
                $detail = is_array($json) && isset($json["message"]) ? $json["message"] : $raw_response;
            }
        }
    }
    }
}

if ($auto_return == 1 && $ok && !$MODO_PRUEBA_FISCAL) {
    if ($generar_ne == "S") {
        header("Location: CrearNotaEntregaAutomaticaWait?id=$id&return=ViewOutTdcfcvList");
        die();
    }

    header("Location: ../ViewOutTdcfcvView/" . $id . "?showdetail=");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Impresora Fiscal</title>
<style>
    body { margin: 0; padding: 25px; font-family: Arial, Helvetica, sans-serif; background: #f4f6f9; color: #263238; }
    .fiscal-card { max-width: 920px; margin: 30px auto; background: #fff; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,.12); overflow: hidden; border: 1px solid #e6e9ef; }
    .fiscal-header { padding: 18px 24px; color: #fff; background: <?php echo $ok ? "#198754" : "#dc3545"; ?>; }
    .fiscal-header h2 { margin: 0; font-size: 22px; }
    .fiscal-body { padding: 22px 24px; }
    .badge { display: inline-block; padding: 6px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; color: #fff; background: <?php echo $ok ? "#198754" : "#dc3545"; ?>; margin-bottom: 12px; }
    .badge-test { background: #fd7e14; }
    .grid { display: grid; grid-template-columns: 190px 1fr; gap: 8px 14px; margin-top: 12px; }
    .label { color: #607d8b; font-weight: bold; }
    pre { background: #101820; color: #e8f5e9; padding: 14px; border-radius: 8px; overflow: auto; font-size: 13px; }
    .actions { margin-top: 22px; display: flex; gap: 10px; flex-wrap: wrap; }
    .btn { display: inline-block; text-decoration: none; background: #0d6efd; color: #fff; padding: 9px 14px; border-radius: 6px; font-weight: bold; }
    .btn-secondary { background: #6c757d; }
    .btn-warning { background: #fd7e14; color: #111; }
</style>
</head>
<body>
<div class="fiscal-card">
    <div class="fiscal-header">
        <h2><?php echo h($title); ?></h2>
    </div>
    <div class="fiscal-body">
        <span class="badge"><?php echo $ok ? "OK" : "ERROR"; ?></span>
        <?php if ($MODO_PRUEBA_FISCAL) { ?>
            <span class="badge badge-test">MODO PRUEBA</span>
        <?php } ?>

        <div class="grid">
            <div class="label">Documento ID</div><div><?php echo h($id); ?></div>
            <div class="label">Puerto COM</div><div><?php echo h($puerto); ?></div>
            <div class="label">Serial consultado</div><div><?php echo h($serialFiscal); ?></div>
            <div class="label">Archivo DAT</div><div><?php echo h($dat_file); ?></div>
            <div class="label">Mensaje</div><div><?php echo h($detail); ?></div>

            <?php if (is_array($json)) { ?>
                <div class="label">Tipo</div><div><?php echo h(isset($json["tipo"]) ? $json["tipo"] : ""); ?></div>
                <div class="label">Factura</div><div><?php echo h(isset($json["numeroFactura"]) ? $json["numeroFactura"] : ""); ?></div>
                <div class="label">Nota Crédito</div><div><?php echo h(isset($json["numeroNotaCredito"]) ? $json["numeroNotaCredito"] : ""); ?></div>
                <div class="label">Nota Débito</div><div><?php echo h(isset($json["numeroNotaDebito"]) ? $json["numeroNotaDebito"] : ""); ?></div>
                <div class="label">Serial fiscal</div><div><?php echo h(isset($json["serialFiscal"]) ? $json["serialFiscal"] : ""); ?></div>
                <div class="label">Estado impresora</div><div><?php echo h(isset($json["estadoImpresora"]) ? $json["estadoImpresora"] : ""); ?></div>
                <div class="label">Error impresora</div><div><?php echo h(isset($json["errorImpresora"]) ? $json["errorImpresora"] : ""); ?></div>
            <?php } ?>
        </div>

        <?php if ($raw_response != "") { ?>
            <h3>Respuesta del motor fiscal</h3>
            <pre><?php echo h($raw_response); ?></pre>
        <?php } ?>

        <?php if (!empty($DEBUG_FISCAL)) { ?>
            <h3>Debug del comando fiscal</h3>
            <pre><?php
                echo h("CMD:\n" . $debug_cmd . "\n\n");
                echo h("CWD:\n" . $debug_cwd . "\n\n");
                echo h("RAW:\n" . $debug_raw . "\n\n");
                echo h("JSON DECODE:\n" . ($debug_json_error !== "" ? $debug_json_error : "OK, se interpretó correctamente") . "\n\n");
                echo h("PARAMETRO 040 (guardar puerto/serial):\n" . ($debug_param040 !== "" ? $debug_param040 : "(no se intentó guardar en esta ejecución)"));
            ?></pre>
        <?php } ?>

        <div class="actions">
            <?php if ($ya_impreso) { ?>
                <a class="btn btn-secondary" href="javascript:void(0);" onclick="window.close(); return false;">Cerrar</a>
            <?php } else { ?>
                <a class="btn btn-warning" href="?test_fiscal=1&id=<?php echo intval($id); ?>&username=<?php echo urlencode($username); ?>">Probar comunicación fiscal</a>
                <a class="btn btn-secondary" href="../ViewOutTdcfcvList">Listar Facturas</a>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>