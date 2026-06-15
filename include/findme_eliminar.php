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
    "redirect_to" => "",
    "error" => ""
];

try {
    $id = (int)($_REQUEST["id"] ?? 0);
    $username = trim($_REQUEST["username"] ?? "");

    if ($id <= 0) {
        throw new Exception("Registro no válido.");
    }

    $link->begin_transaction();

    $stmt = $link->prepare("SELECT es.id_documento, es.tipo_documento, s.nro_documento
        FROM entradas_salidas AS es
        LEFT JOIN salidas AS s ON s.id = es.id_documento AND s.tipo_documento = es.tipo_documento
        WHERE es.id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$resData) {
        throw new Exception("El elemento ya ha sido eliminado previamente.");
    }

    $id_documento = intval($resData["id_documento"]);
    $tipo_documento = $resData["tipo_documento"];
    $nro_documento = $resData["nro_documento"] ?? "";

    // Eliminar registro físico
    $stmtDel = $link->prepare("DELETE FROM entradas_salidas WHERE id = ?;");
    $stmtDel->bind_param("i", $id);
    $stmtDel->execute();
    $stmtDel->close();

    // Si ya no quedan renglones, eliminar también la cabecera para no dejar documentos vacíos.
    $stmtCount = $link->prepare("SELECT COUNT(*) AS total FROM entradas_salidas WHERE id_documento = ? AND tipo_documento = ?;");
    $stmtCount->bind_param("is", $id_documento, $tipo_documento);
    $stmtCount->execute();
    $rowCount = $stmtCount->get_result()->fetch_assoc();
    $stmtCount->close();

    $total_restante = intval($rowCount["total"] ?? 0);

    if ($total_restante <= 0) {
        $stmtDelCab = $link->prepare("DELETE FROM salidas WHERE id = ? AND tipo_documento = ?;");
        $stmtDelCab->bind_param("is", $id_documento, $tipo_documento);
        $stmtDelCab->execute();
        $stmtDelCab->close();

        // Trazabilidad mediante AuditTrail
        $script_name = 'findme_eliminar';
        $action_type = 'D';
        $field_name = 'id';
        $blank_val = '';
        $log_msg = "Eliminar documento vacío $tipo_documento NRO $nro_documento";
        $current_date = date("Y-m-d H:i:s");

        $sqlAudit = "INSERT INTO audittrail (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $stmtAudit = $link->prepare($sqlAudit);
        $stmtAudit->bind_param("sssssssss", $current_date, $log_msg, $username, $action_type, $script_name, $field_name, $id_documento, $blank_val, $blank_val);
        $stmtAudit->execute();
        $stmtAudit->close();

        $link->commit();

        $response["success"] = true;
        $response["message"] = "Se eliminó el último renglón y también la cabecera del documento.";
        $response["redirect_to"] = ($tipo_documento === "TDCNET") ? "ViewOutTdcnetList" : "ViewOutTdcasaList";

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Actualizar autor y estado de revisión si todavía quedan renglones.
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