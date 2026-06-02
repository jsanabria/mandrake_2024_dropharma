<?php
/**
 * findme_eliminar_all.php
 */
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";

$response = [
    "success" => false,
    "redirect_to" => "",
    "message" => "",
    "error" => ""
];

try {
    $pedido = (int)($_REQUEST["id"] ?? 0);
    $username = trim($_REQUEST["username"] ?? "");

    if ($pedido <= 0) {
        throw new Exception("ID de documento inválido.");
    }

    $link->begin_transaction();

    $stmt = $link->prepare("SELECT tipo_documento, nro_documento FROM salidas WHERE id = ?;");
    $stmt->bind_param("i", $pedido);
    $stmt->execute();
    $rowSalida = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$rowSalida) {
        throw new Exception("El documento solicitado no existe en el sistema.");
    }

    $tipo_documento = $rowSalida["tipo_documento"];
    $nro_documento = $rowSalida["nro_documento"];

    // Borrado total de renglones dependientes
    $stmtDelDet = $link->prepare("DELETE FROM entradas_salidas WHERE id_documento = ? AND tipo_documento = ?;");
    $stmtDelDet->bind_param("is", $pedido, $tipo_documento);
    $stmtDelDet->execute();
    $stmtDelDet->close();

    // Borrado de cabecera
    $stmtDelCab = $link->prepare("DELETE FROM salidas WHERE id = ? AND tipo_documento = ?;");
    $stmtDelCab->bind_param("is", $pedido, $tipo_documento);
    $stmtDelCab->execute();
    $stmtDelCab->close();

    // Trazabilidad mediante AuditTrail
    $script_name = 'findme_eliminar_all';
    $action_type = 'D';
    $field_name = 'id';
    $blank_val = '';
    $log_msg = "Eliminar documento $tipo_documento NRO $nro_documento";
    $current_date = date("Y-m-d H:i:s");

    $sqlAudit = "INSERT INTO audittrail (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmtAudit = $link->prepare($sqlAudit);
    $stmtAudit->bind_param("sssssssss", $current_date, $log_msg, $username, $action_type, $script_name, $field_name, $pedido, $blank_val, $blank_val);
    $stmtAudit->execute();
    $stmtAudit->close();

    $link->commit();

    $response["success"] = true;
    $response["message"] = "Documento eliminado permanentemente.";
    $response["redirect_to"] = ($tipo_documento === "TDCNET") ? "../ViewOutTdcnetList" : "../ViewOutTdcasaList";

} catch (Exception $e) {
    $link->rollback();
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;