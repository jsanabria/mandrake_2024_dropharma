<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";

$response = [
    "success" => false,
    "message" => "",
    "error" => ""
];

try {
    $id = intval($_POST["id"] ?? 0);
    $username = trim($_POST["username"] ?? "");

    if ($id <= 0) {
        throw new Exception("ID de documento inválido.");
    }

    $stmt = $link->prepare("
        UPDATE salidas
        SET estatus = 'PROCESADO',
            username = ?
        WHERE id = ?
          AND tipo_documento = 'TDCASA'
    ");
    $stmt->bind_param("si", $username, $id);
    $stmt->execute();

    if ($stmt->affected_rows <= 0) {
        throw new Exception("No se pudo procesar el ajuste de salida.");
    }

    $stmt->close();

    $response["success"] = true;
    $response["message"] = "Ajuste de salida procesado correctamente.";

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;