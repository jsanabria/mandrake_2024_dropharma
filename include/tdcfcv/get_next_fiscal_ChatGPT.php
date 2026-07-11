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

function NormalizarUtf8FiscalNext($texto)
{
    $texto = (string)$texto;

    if ($texto === "") {
        return "";
    }

    if (substr($texto, 0, 3) === "\xEF\xBB\xBF") {
        $texto = substr($texto, 3);
    }

    if (mb_check_encoding($texto, "UTF-8")) {
        return $texto;
    }

    foreach (["Windows-1252", "CP850", "CP437", "ISO-8859-1"] as $encoding) {
        $convertido = @iconv(
            $encoding,
            "UTF-8//IGNORE",
            $texto
        );

        if (
            $convertido !== false &&
            mb_check_encoding($convertido, "UTF-8")
        ) {
            return $convertido;
        }
    }

    return mb_convert_encoding(
        $texto,
        "UTF-8",
        "UTF-8"
    );
}

function ParseJsonFiscalNext($text)
{
    $text = trim(NormalizarUtf8FiscalNext($text));

    $json = json_decode($text, true);

    if (is_array($json)) {
        return $json;
    }

    $lines = preg_split('/\r\n|\r|\n/', $text);

    for ($i = count($lines) - 1; $i >= 0; $i--) {
        $linea = trim($lines[$i]);

        if ($linea === "") {
            continue;
        }

        $json = json_decode($linea, true);

        if (is_array($json)) {
            return $json;
        }
    }

    return null;
}

function EjecutarInfoJsonFiscalNext($exe, $directorio, $com = "")
{
    $cmd = '"' . $exe . '" INFOJSON';

    if (trim($com) !== "") {
        $cmd .= ' "' . trim($com) . '"';
    }

    $cmdWin = 'cmd.exe /S /C "' . $cmd . '"';

    $descriptors = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];

    $proc = proc_open($cmdWin, $descriptors, $pipes, $directorio);

    if (!is_resource($proc)) {
        return [null, ""];
    }

    fclose($pipes[0]);
    $salida = trim(stream_get_contents($pipes[1]));
    $error = trim(stream_get_contents($pipes[2]));
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($proc);

    if ($salida === "" && $error !== "") {
        $salida = $error;
    }

    return [ParseJsonFiscalNext($salida), $salida];
}

function ObtenerInfoFiscalImpresoraNext()
{
    $fiscalDir = "C:\\laragon\\www\\mandrake_2024_dropharma\\MandrakeFiscal";
    $fiscalExe = $fiscalDir . "\\FiscalPrinterV2.exe";

    if (!file_exists($fiscalExe)) {
        throw new \Exception("No existe el motor fiscal en: " . $fiscalExe);
    }

    // Este endpoint usa el bootstrap nativo de PHPMaker/Doctrine.
    // No dependemos de una variable mysqli $link que aquí puede no existir.
    $rowPuerto = ExecuteRow(
        "SELECT valor1 FROM parametro WHERE codigo = '040' LIMIT 1"
    );

    $puerto = $rowPuerto
        ? trim((string)($rowPuerto["valor1"] ?? ""))
        : "";

    if ($puerto !== "") {
        [$json, $raw] = EjecutarInfoJsonFiscalNext(
            $fiscalExe,
            $fiscalDir,
            $puerto
        );

        if (is_array($json) && !empty($json["success"])) {
            return $json;
        }
    }

    [$json, $raw] = EjecutarInfoJsonFiscalNext(
        $fiscalExe,
        $fiscalDir,
        ""
    );

    if (!is_array($json) || empty($json["success"])) {
        $mensaje = is_array($json) && !empty($json["message"])
            ? $json["message"]
            : $raw;

        throw new \Exception(
            "No se pudo consultar INFOJSON de la impresora fiscal. " .
            trim((string)$mensaje)
        );
    }

    return $json;
}

function ObtenerValorJsonFiscal($json, $claves)
{
    foreach ($claves as $clave) {
        if (isset($json[$clave]) && trim((string)$json[$clave]) !== "") {
            return trim((string)$json[$clave]);
        }
    }

    return "";
}

$pedido = isset($_POST["pedido"]) ? intval($_POST["pedido"]) : 0;

if ($pedido <= 0) {
    ResponderJsonFiscalNext([
        "estatus" => 0,
        "mensaje" => "ID de pedido no válido."
    ]);
}

try {
    $sql = "SELECT documento, nro_documento, estatus
            FROM salidas
            WHERE id = $pedido
            LIMIT 1";
    $row = ExecuteRow($sql);

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

    if ($usaImpresoraFiscal) {
        $infoFiscal = ObtenerInfoFiscalImpresoraNext();

        switch ($documento) {
            case "FC":
                $actualDocumento = ObtenerValorJsonFiscal(
                    $infoFiscal,
                    ["numeroFactura", "ultimaFactura", "nroFactura"]
                );
                break;

            case "NC":
                $actualDocumento = ObtenerValorJsonFiscal(
                    $infoFiscal,
                    ["numeroNotaCredito", "ultimaNotaCredito", "nroNotaCredito"]
                );
                break;

            case "ND":
                $actualDocumento = ObtenerValorJsonFiscal(
                    $infoFiscal,
                    ["numeroNotaDebito", "ultimaNotaDebito", "nroNotaDebito"]
                );
                break;

            default:
                throw new \Exception(
                    "Tipo de documento fiscal no válido: " . $documento
                );
        }

        $actualControl = ObtenerValorJsonFiscal(
            $infoFiscal,
            [
                "numeroControl",
                "numeroControlFiscal",
                "ultimoNumeroControl",
                "nroControl"
            ]
        );

        if ($actualDocumento === "") {
            throw new \Exception(
                "INFOJSON no devolvió el correlativo actual para " . $documento . "."
            );
        }

        if ($actualControl === "") {
            $actualControl = $actualDocumento;
        }

        $factura = IncrementarCorrelativoFiscal($actualDocumento);
        $facturaCTRL = IncrementarCorrelativoFiscal($actualControl);

        if ($factura === "" || $facturaCTRL === "") {
            throw new \Exception(
                "No fue posible calcular los próximos correlativos desde INFOJSON."
            );
        }

        ResponderJsonFiscalNext([
            "estatus" => 1,
            "factura" => $factura,
            "control" => $facturaCTRL,
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

    // Parámetro que define el formato/prefijo del número de documento.
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
        // Todos usan la configuración de control de factura y la serie común DC_CTRL.
        $crtl = "030";
        $serie_ctrl = "DC_CTRL";
    } else {
        // Cada tipo de documento usa su propia configuración y su propia serie.
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

    // El consecutivo del documento siempre permanece independiente.
    $serie_doc = $documento . "_DOC";

    // Configuración y próximo estimado del documento.
    $rowFact = ExecuteRow(
        "SELECT valor2, valor3
         FROM parametro
         WHERE codigo = '$docu'
         LIMIT 1"
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

    // Configuración y próximo estimado del número de control.
    $rowCtrl = ExecuteRow(
        "SELECT valor2, valor3
         FROM parametro
         WHERE codigo = '$crtl'
         LIMIT 1"
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