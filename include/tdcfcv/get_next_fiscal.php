<?php
namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

header('Content-Type: application/json; charset=utf-8');
ob_start();


function ResponderJsonFiscalNext($datos)
{
    if (ob_get_length()) {
        ob_clean();
    }

    $json = json_encode(
        $datos,
        JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
    );

    if ($json === false) {
        $json = '{"estatus":0,"mensaje":"No fue posible codificar la respuesta JSON."}';
    }

    echo $json;
    exit;
}


function IncrementarCorrelativoFiscal($valor)
{
    $valor = trim((string)$valor);

    if ($valor === "" || !preg_match('/^(.*?)(\\d+)$/', $valor, $m)) {
        return "";
    }

    return $m[1] . str_pad(
        (string)(intval($m[2]) + 1),
        strlen($m[2]),
        "0",
        STR_PAD_LEFT
    );
}

/**
 * La consola de Windows suele devolver la salida del .exe en CP1252 /
 * ISO-8859-1 (por acentos como "ó" en los mensajes). json_decode()
 * exige UTF-8 válido y devuelve null si no lo es, así que aquí
 * normalizamos la cadena antes de intentar decodificarla.
 */
function SanearUtf8FiscalNext($text)
{
    if ($text === "" || mb_check_encoding($text, 'UTF-8')) {
        return $text;
    }

    $convertido = @iconv('CP1252', 'UTF-8//IGNORE', $text);

    if ($convertido !== false && $convertido !== "") {
        return $convertido;
    }

    return mb_convert_encoding($text, 'UTF-8', 'UTF-8');
}

function ParseJsonFiscalNext($text)
{
    $text = SanearUtf8FiscalNext(trim((string)$text));
    $json = json_decode($text, true);

    if (is_array($json)) {
        return $json;
    }

    $lines = preg_split('/\\r\\n|\\r|\\n/', $text);

    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $json = json_decode(trim($lines[$i]), true);

        if (is_array($json)) {
            return $json;
        }
    }

    return null;
}

/**
 * Ejecuta INFOJSON contra el motor fiscal y devuelve el último número
 * de documento (factura / nota de crédito / nota de débito) según
 * el tipo solicitado. El número de control se deriva del mismo valor,
 * ya que en la impresora fiscal ambos correlativos coinciden.
 */
function ObtenerUltimoDocumentoFiscal($documento)
{
    $fiscalDir = "C:\\laragon\\www\\mandrake_2024_dropharma\\MandrakeFiscal";
    $fiscalExe = $fiscalDir . "\\FiscalPrinterV2.exe";

    if (!file_exists($fiscalExe)) {
        throw new \Exception("No existe el motor fiscal en: " . $fiscalExe);
    }

    $claves = [
        "FC" => ["numeroFactura", "ultimaFactura", "nroFactura"],
        "NC" => ["numeroNotaCredito", "ultimaNotaCredito", "nroNotaCredito"],
        "ND" => ["numeroNotaDebito", "ultimaNotaDebito", "nroNotaDebito"],
    ];

    if (!isset($claves[$documento])) {
        throw new \Exception("Tipo de documento fiscal no válido: " . $documento);
    }

    $puerto = trim((string)ExecuteScalar(
        "SELECT valor1 FROM parametro WHERE codigo = '040' LIMIT 1"
    ));

    $cmd = '"' . $fiscalExe . '" INFOJSON';
    if ($puerto !== "") {
        $cmd .= ' "' . $puerto . '"';
    }

    $descriptors = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"],
    ];

    $proc = proc_open('cmd.exe /S /C "' . $cmd . '"', $descriptors, $pipes, $fiscalDir);

    if (!is_resource($proc)) {
        throw new \Exception("No fue posible ejecutar el motor fiscal.");
    }

    fclose($pipes[0]);
    $salida = trim(stream_get_contents($pipes[1]));
    $error = trim(stream_get_contents($pipes[2]));
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($proc);

    $json = ParseJsonFiscalNext($salida !== "" ? $salida : $error);

    if (!is_array($json) || empty($json["success"])) {
        $mensaje = is_array($json) && !empty($json["message"]) ? $json["message"] : $salida;
        throw new \Exception("No se pudo consultar INFOJSON de la impresora fiscal. " . trim((string)$mensaje));
    }

    foreach ($claves[$documento] as $clave) {
        if (isset($json[$clave]) && trim((string)$json[$clave]) !== "") {
            return trim((string)$json[$clave]);
        }
    }

    throw new \Exception("INFOJSON no devolvió el correlativo actual para " . $documento . ".");
}


$pedido = isset($_POST["pedido"]) ? intval($_POST["pedido"]) : 0;

if ($pedido <= 0) {
    ResponderJsonFiscalNext([
        "estatus" => 0,
        "mensaje" => "ID de pedido no válido."
    ]);
}

try {
    $row = ExecuteRow(
        "SELECT documento, nro_documento, estatus
         FROM salidas
         WHERE id = $pedido
         LIMIT 1"
    );

    if (!$row) {
        ResponderJsonFiscalNext([
            "estatus" => 0,
            "mensaje" => "Pedido no encontrado."
        ]);
    }

    $documento = strtoupper(trim($row["documento"] ?? ""));

    $usaImpresoraFiscal = strtoupper(trim((string)ExecuteScalar(
        "SELECT valor1 FROM parametro WHERE codigo = '112' LIMIT 1"
    ))) === "S";

    // ---------------------------------------------------------------
    // CASO 1: Impresora fiscal. Solo necesitamos el último número de
    // documento; el control usa el mismo correlativo (factura = control).
    // ---------------------------------------------------------------
    if ($usaImpresoraFiscal) {
        $actualDocumento = ObtenerUltimoDocumentoFiscal($documento);
        $factura = IncrementarCorrelativoFiscal($actualDocumento);

        if ($factura === "") {
            throw new \Exception("No fue posible calcular el próximo correlativo desde INFOJSON.");
        }

        ResponderJsonFiscalNext([
            "estatus" => 1,
            "factura" => $factura,
            "control" => $factura,
            "fecha" => date("Y-m-d H:i:s"),
            "tipo_doc" => $documento,
            "origen_correlativo" => "IMPRESORA_FISCAL",
            "impresora_fiscal" => true,
            "estimado" => true,
            "mensaje_estimado" =>
                "Número estimado consultado en la impresora fiscal. " .
                "La asignación definitiva se hará al emitir el documento."
        ]);
    }

    // ---------------------------------------------------------------
    // CASO 2: Correlativo interno (base de datos). Sin cambios respecto
    // a la lógica original, que ya funciona correctamente.
    // ---------------------------------------------------------------
    switch ($documento) {
        case "FC":
            $docu = "003";
            break;
        case "NC":
            $docu = "010";
            break;
        case "ND":
            $docu = "011";
            break;
        default:
            throw new \Exception("Tipo de documento fiscal no válido: " . $documento);
    }

    // Si parametro 035 = S, FC, NC y ND comparten una sola serie de control.
    $controlUnificado = strtoupper(trim(
        ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '035'")
    )) === "S";

    if ($controlUnificado) {
        $crtl = "030";
        $serie_ctrl = "DC_CTRL";
    } else {
        switch ($documento) {
            case "FC":
                $crtl = "030";
                break;
            case "NC":
                $crtl = "031";
                break;
            case "ND":
                $crtl = "032";
                break;
        }
        $serie_ctrl = $documento . "_CTRL";
    }

    $serie_doc = $documento . "_DOC";

    $rowFact = ExecuteRow(
        "SELECT valor2, valor3 FROM parametro WHERE codigo = '$docu' LIMIT 1"
    );

    if (!$rowFact) {
        throw new \Exception("No existe la configuración del parámetro $docu.");
    }

    $numero = intval(ObtenerConsecutivoActual("TDCFCV", $serie_doc)) + 1;
    $prefijo = trim($rowFact["valor2"] ?? "");
    $padeo = intval($rowFact["valor3"] ?? 0);

    if ($padeo <= 0) {
        throw new \Exception("El padding configurado en el parámetro $docu no es válido.");
    }

    $factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT);

    $rowCtrl = ExecuteRow(
        "SELECT valor2, valor3 FROM parametro WHERE codigo = '$crtl' LIMIT 1"
    );

    if (!$rowCtrl) {
        throw new \Exception("No existe la configuración del parámetro $crtl.");
    }

    $numeroCtrl = intval(ObtenerConsecutivoActual("TDCFCV", $serie_ctrl)) + 1;
    $prefijoCtrl = trim($rowCtrl["valor2"] ?? "");
    $padeoCtrl = intval($rowCtrl["valor3"] ?? 0);

    if ($padeoCtrl <= 0) {
        throw new \Exception("El padding configurado en el parámetro $crtl no es válido.");
    }

    $facturaCTRL = $prefijoCtrl . str_pad($numeroCtrl, $padeoCtrl, "0", STR_PAD_LEFT);

    ResponderJsonFiscalNext([
        "estatus" => 1,
        "factura" => $factura,
        "control" => $facturaCTRL,
        "fecha" => date("Y-m-d H:i:s"),
        "tipo_doc" => $documento,
        "serie_documento" => $serie_doc,
        "serie_control" => $serie_ctrl,
        "control_unificado" => $controlUnificado,
        "estimado" => true,
        "mensaje_estimado" => "Número estimado. La asignación definitiva se hará al procesar."
    ]);

} catch (\Throwable $e) {
    ResponderJsonFiscalNext([
        "estatus" => 0,
        "mensaje" => $e->getMessage()
    ]);
}