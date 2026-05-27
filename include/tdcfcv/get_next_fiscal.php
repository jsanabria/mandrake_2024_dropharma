<?php
namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

// Asegúrate de incluir los headers necesarios para responder JSON y validar sesión si es requerido por PHPMaker
header('Content-Type: application/json');

$pedido = isset($_POST["pedido"]) ? intval($_POST["pedido"]) : 0;

if ($pedido <= 0) {
    echo json_encode(["estatus" => 0, "mensaje" => "ID de pedido no válido."]);
    exit;
}

try {
    $sql = "SELECT documento, nro_documento, estatus FROM salidas WHERE id = $pedido;";
    $row = ExecuteRow($sql);
    
    if (!$row) {
        echo json_encode(["estatus" => 0, "mensaje" => "Pedido no encontrado."]);
        exit;
    }

    $documento = $row["documento"];
    $docu = "003";
    
    switch($documento) {
        case "FC": $docu = "003"; break;
        case "NC": $docu = "010"; break;
        case "ND": $docu = "011"; break;
    }

    $sql = "SELECT valor1 FROM parametro WHERE codigo = '035';";
    if(ExecuteScalar($sql) == "S") {
        $crtl = "030";
    } else {
        switch($documento) {
            case "FC": $crtl = "030"; break;
            case "NC": $crtl = "031"; break;
            case "ND": $crtl = "032"; break;
        }
    }

    // Nro Factura / Nota
    $sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$docu';";
    $rowFact = ExecuteRow($sql);
    $numero = intval($rowFact["valor1"]) + 1;
    $prefijo = trim($rowFact["valor2"]);
    $padeo = intval($rowFact["valor3"]);
    $factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 

    // Nro Control
    $sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '$crtl';";
    $rowCtrl = ExecuteRow($sql);
    $numeroCtrl = intval($rowCtrl["valor1"]) + 1;
    $prefijoCtrl = trim($rowCtrl["valor2"]);
    $padeoCtrl = intval($rowCtrl["valor3"]);
    $facturaCTRL = $prefijoCtrl . str_pad($numeroCtrl, $padeoCtrl, "0", STR_PAD_LEFT); 

    // Fecha actual del servidor
    $fechaActual = date("Y-m-d H:i:s");

    echo json_encode([
        "estatus" => 1,
        "factura" => $factura,
        "control" => $facturaCTRL,
        "fecha" => $fechaActual,
        "tipo_doc" => $documento
    ]);

} catch (\Exception $e) {
    echo json_encode(["estatus" => 0, "mensaje" => $e->getMessage()]);
}