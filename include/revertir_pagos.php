<?php
session_start();
include "connect.php";

// ------------------- helpers -------------------
function aud($link, $user, $script, $action, $table, $keyvalue, $oldvalue, $newvalue) {
    $user = mysqli_real_escape_string($link, (string)$user);
    $script = mysqli_real_escape_string($link, (string)$script);
    $action = mysqli_real_escape_string($link, (string)$action);
    $table = mysqli_real_escape_string($link, (string)$table);
    $keyvalue = mysqli_real_escape_string($link, (string)$keyvalue);
    $oldvalue = mysqli_real_escape_string($link, (string)$oldvalue);
    $newvalue = mysqli_real_escape_string($link, (string)$newvalue);

    $sql = "
        INSERT INTO audittrail
            (`datetime`, `script`, `user`, `action`, `table`, `field`, `keyvalue`, `oldvalue`, `newvalue`)
        VALUES
            (NOW(), '$script', '$user', '$action', '$table', NULL, '$keyvalue', '$oldvalue', '$newvalue')
    ";
    @mysqli_query($link, $sql); // no rompas el revert por falla de auditoría
}

function q1($link, $sql) {
    $res = mysqli_query($link, $sql);
    if (!$res) throw new Exception(mysqli_error($link));
    $row = mysqli_fetch_row($res);
    return $row ? $row[0] : 0;
}

function ex($link, $sql) {
    if (!mysqli_query($link, $sql)) {
        throw new Exception(mysqli_error($link));
    }
}

// ------------------- input -------------------
$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
if ($id <= 0) { http_response_code(400); die("ID inválido"); }

// usuario (ajusta a tu sesión real)
$username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : "SYSTEM";
$script = "include/revertir_pagos.php";
$action = "REVERT_PAGO_FACTURA";

// Validar que exista la salida
$exists = q1($link, "SELECT COUNT(*) FROM salidas WHERE id = $id");
if (intval($exists) === 0) { http_response_code(404); die("Factura/Salida no encontrada"); }

// ------------------- transaction -------------------
mysqli_begin_transaction($link);

try {

    // ---- Preconteos para auditoría ----
    $cnt_apps_before = q1($link, "SELECT COUNT(*) FROM anticipos_aplicaciones WHERE salida_id = $id");

    $cnt_det_before = q1($link, "
        SELECT COUNT(*)
        FROM cobros_cliente_detalle d
        JOIN cobros_cliente c ON c.id = d.cobros_cliente
        WHERE c.id_documento = $id
    ");

    $cnt_cab_before = q1($link, "SELECT COUNT(*) FROM cobros_cliente WHERE id_documento = $id");

    $pagado_before = q1($link, "SELECT IFNULL(pagado,'') FROM salidas WHERE id = $id");
    // ojo: q1 devuelve row[0] pero en string; ok.

    // ---- Auditoría header (intención) ----
    $key = "salidas.id={$id}";
    $old_header = "apps=$cnt_apps_before; det=$cnt_det_before; cab=$cnt_cab_before; pagado=$pagado_before";
    $new_header = "revert=INICIO";
    aud($link, $username, $script, $action, "SALIDAS", $key, $old_header, $new_header);

    // 1) Revertir SOLO aplicaciones de anticipos hechas a ESTA factura
    ex($link, "DELETE FROM anticipos_aplicaciones WHERE salida_id = $id");
    $cnt_apps_after = q1($link, "SELECT COUNT(*) FROM anticipos_aplicaciones WHERE salida_id = $id");
    aud(
        $link, $username, $script, $action,
        "anticipos_aplicaciones",
        $key,
        "rows_before=$cnt_apps_before",
        "rows_after=$cnt_apps_after"
    );

    // 2) Borrar detalles de cobros de ESTA factura
    ex($link, "
        DELETE d
        FROM cobros_cliente_detalle d
        JOIN cobros_cliente c ON c.id = d.cobros_cliente
        WHERE c.id_documento = $id
    ");
    $cnt_det_after = q1($link, "
        SELECT COUNT(*)
        FROM cobros_cliente_detalle d
        JOIN cobros_cliente c ON c.id = d.cobros_cliente
        WHERE c.id_documento = $id
    ");
    aud(
        $link, $username, $script, $action,
        "cobros_cliente_detalle",
        $key,
        "rows_before=$cnt_det_before",
        "rows_after=$cnt_det_after"
    );

    // 3) Borrar cabeceras de cobros de ESTA factura
    ex($link, "DELETE FROM cobros_cliente WHERE id_documento = $id");
    $cnt_cab_after = q1($link, "SELECT COUNT(*) FROM cobros_cliente WHERE id_documento = $id");
    aud(
        $link, $username, $script, $action,
        "cobros_cliente",
        $key,
        "rows_before=$cnt_cab_before",
        "rows_after=$cnt_cab_after"
    );

    // 4) Marcar la salida como no pagada
    ex($link, "UPDATE salidas SET pagado = 'N', igtf = 'N', monto_base_igtf = 0, monto_igtf = 0 WHERE id = $id");
    $pagado_after = q1($link, "SELECT IFNULL(pagado,'') FROM salidas WHERE id = $id");
    aud(
        $link, $username, $script, $action,
        "salidas",
        $key,
        "pagado_before=$pagado_before",
        "pagado_after=$pagado_after"
    );

    // ---- Auditoría final (resumen) ----
    $new_header2 = "revert=OK; apps_deleted=$cnt_apps_before; det_deleted=$cnt_det_before; cab_deleted=$cnt_cab_before; pagado_now=$pagado_after";
    aud($link, $username, $script, $action, "SALIDAS", $key, $old_header, $new_header2);

    mysqli_commit($link);

    echo "Proceso exitoso...";

} catch (Throwable $e) {
    mysqli_rollback($link);

    // auditar fallo (best effort)
    $msg = "ERROR: " . $e->getMessage();
    aud($link, $username, $script, $action, "SALIDAS", "salidas.id={$id}", "revert=FALLA", $msg);

    http_response_code(500);
    echo $msg;
}
?>