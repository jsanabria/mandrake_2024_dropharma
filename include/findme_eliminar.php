<?php
/**
 * findme_eliminar.php
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
    $id = (int)($_REQUEST["id"] ?? 0);
    $username = trim($_REQUEST["username"] ?? "");

    if ($id <= 0) {
        throw new Exception("Registro no válido.");
    }

    $link->begin_transaction();

    $stmt = $link->prepare("SELECT id_documento, tipo_documento FROM entradas_salidas WHERE id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$resData) {
        throw new Exception("El elemento ya ha sido eliminado previamente.");
    }

    $id_documento = $resData["id_documento"];
    $tipo_documento = $resData["tipo_documento"];

    // Eliminar registro físico
    $stmtDel = $link->prepare("DELETE FROM entradas_salidas WHERE id = ?;");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();
    $stmtDel->close();

    // Actualizar autor y estado de revisión
    $stmtUpd = $link->prepare("UPDATE salidas SET estatus = 'PROCESADO', username = ? WHERE id = ?;");
    $stmtUpd->bind_param("si", $username, $id_documento);
    $stmtUpd->execute();
    $stmtUpd->close();

    $dataActualizada = calcularYObtenerDetalleJSON($link, $tipo_documento, $id_documento);

    $link->commit();

    $response["success"] = true;
    $response["message"] = "Renglón removido del documento.";
    $response["data"] = $dataActualizada;

} catch (Exception $e) {
    $link->rollback();
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;