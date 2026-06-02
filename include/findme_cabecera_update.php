<?php
/**
 * findme_cabecera_update.php
 */
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";
require_once "findme_procesador_calculos.php";

$response = [
    "success" => false,
    "message" => "",
    "data" => null,
    "error" => ""
];

try {
    $id = (int)($_POST["id"] ?? 0);
    $nota = trim($_POST["nota"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $factura = trim($_POST["factura"] ?? "");
    $ci_rif = trim($_POST["ci_rif"] ?? "");
    $nombre = trim($_POST["nombre"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $descuento = isset($_POST["descuento"]) ? floatval($_POST["descuento"]) : 0;
    $descuento2 = isset($_POST["descuento2"]) ? floatval($_POST["descuento2"]) : 0;
    $tasa_dia = floatval($_POST["tasa"] ?? 0);

    if ($id <= 0) {
        throw new Exception("ID de documento inválido.");
    }

    if ($tasa_dia == 0) {
        $resTasa = $link->query("SELECT tasa FROM tasa_usd ORDER BY id DESC LIMIT 0, 1;");
        if ($resTasa && $rowT = $resTasa->fetch_assoc()) {
            $tasa_dia = floatval($rowT["tasa"]);
        }
    }

    $stmt = $link->prepare("SELECT tipo_documento FROM salidas WHERE id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resDoc = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$resDoc) throw new Exception("Documento inexistente.");
    $tipo_documento = $resDoc["tipo_documento"];

    $link->begin_transaction();

    $sqlUpd = "UPDATE salidas 
               SET descuento = ?, 
                   descuento2 = ?, 
                   tasa_dia = ?, 
                   estatus = 'NUEVO', 
                   factura = ?, 
                   ci_rif = ?, 
                   nombre = ?, 
                   direccion = ?, 
                   nota = ?, 
                   telefono = ?, 
                   username = ?  
               WHERE tipo_documento = ? 
                 AND id = ?;";

    $stmtUpd = $link->prepare($sqlUpd);

    $stmtUpd->bind_param("dddssssssssi", 
        $descuento,
        $descuento2,
        $tasa_dia,
        $factura,
        $ci_rif,
        $nombre,
        $direccion,
        $nota,
        $telefono,
        $username,
        $tipo_documento,
        $id
    );

    $stmtUpd->execute();
    $stmtUpd->close();

    $dataActualizada = calcularYObtenerDetalleJSON($link, $tipo_documento, $id);

    $link->commit();

    $response["success"] = true;
    $response["message"] = "Cabecera actualizada con éxito.";
    $response["data"] = $dataActualizada;

} catch (Exception $e) {
    $link->rollback();
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;