<?php
namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

header('Content-Type: application/json; charset=utf-8');

$pedido = isset($_POST["pedido"]) ? intval($_POST["pedido"]) : 0;

if ($pedido <= 0) {
    echo json_encode([
        "estatus" => 0,
        "mensaje" => "ID de pedido no válido."
    ]);
    exit;
}

try {
    $sql = "SELECT documento, nro_documento, estatus
            FROM salidas
            WHERE id = $pedido
            LIMIT 1";
    $row = ExecuteRow($sql);

    if (!$row) {
        echo json_encode([
            "estatus" => 0,
            "mensaje" => "Pedido no encontrado."
        ]);
        exit;
    }

    $documento = strtoupper(trim($row["documento"] ?? ""));

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

    echo json_encode([
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

} catch (\Exception $e) {
    echo json_encode([
        "estatus" => 0,
        "mensaje" => $e->getMessage()
    ]);
}
