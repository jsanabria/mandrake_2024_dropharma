<?php
session_start();
include "connect.php";

// ---------------------------------------------------------
// Helpers
// ---------------------------------------------------------
function aud($link, $user, $script, $action, $table, $keyvalue, $oldvalue, $newvalue)
{
    $user     = mysqli_real_escape_string($link, (string)$user);
    $script   = mysqli_real_escape_string($link, (string)$script);
    $action   = mysqli_real_escape_string($link, (string)$action);
    $table    = mysqli_real_escape_string($link, (string)$table);
    $keyvalue = mysqli_real_escape_string($link, (string)$keyvalue);
    $oldvalue = mysqli_real_escape_string($link, (string)$oldvalue);
    $newvalue = mysqli_real_escape_string($link, (string)$newvalue);

    $sql = "
        INSERT INTO audittrail
            (`datetime`, `script`, `user`, `action`, `table`, `field`, `keyvalue`, `oldvalue`, `newvalue`)
        VALUES
            (NOW(), '$script', '$user', '$action', '$table', NULL, '$keyvalue', '$oldvalue', '$newvalue')
    ";

    // La auditoría no debe romper la reversión.
    @mysqli_query($link, $sql);
}

function q1($link, $sql)
{
    $res = mysqli_query($link, $sql);

    if (!$res) {
        throw new Exception(mysqli_error($link));
    }

    $row = mysqli_fetch_row($res);
    return $row ? $row[0] : 0;
}

function ex($link, $sql)
{
    if (!mysqli_query($link, $sql)) {
        throw new Exception(mysqli_error($link));
    }
}

// ---------------------------------------------------------
// Entrada
// ---------------------------------------------------------
$idDocumento = isset($_POST["id"])
    ? intval($_POST["id"])
    : 0;

$tipoDocumento = isset($_POST["tipo_documento"])
    ? strtoupper(trim((string)$_POST["tipo_documento"]))
    : "";

// Alias aceptados.
if ($tipoDocumento === "GASTO") {
    $tipoDocumento = "GASTOS";
}

if ($idDocumento <= 0) {
    http_response_code(400);
    die("ID de documento inválido.");
}

if (!in_array($tipoDocumento, ["TDCFCC", "GASTOS"], true)) {
    http_response_code(400);
    die("Tipo de documento inválido.");
}

// El usuario se toma de la sesión y no del navegador.
$username = $_SESSION["username"]
    ?? $_SESSION["user"]
    ?? $_SESSION["UserName"]
    ?? "SYSTEM";

$script = "include/revertir_pagos_compras.php";
$action = "REVERT_PAGOS_COMPRA";

// ---------------------------------------------------------
// Validar documento de origen
// ---------------------------------------------------------
if ($tipoDocumento === "TDCFCC") {
    $tablaDocumento = "entradas";
    $descripcionDocumento = "Entrada/Factura de compra";
} else {
    $tablaDocumento = "compra";
    $descripcionDocumento = "Compra administrativa/Gasto";
}

$existeDocumento = q1(
    $link,
    "SELECT COUNT(*) FROM {$tablaDocumento} WHERE id = {$idDocumento}"
);

if (intval($existeDocumento) === 0) {
    http_response_code(404);
    die($descripcionDocumento . " no encontrada.");
}

// Verificar que existan pagos para revertir.
$cantidadPagos = q1(
    $link,
    "SELECT COUNT(*)
     FROM pagos_compras
     WHERE id_documento = {$idDocumento}
       AND tipo_documento = '" . mysqli_real_escape_string($link, $tipoDocumento) . "'"
);

if (intval($cantidadPagos) === 0) {
    http_response_code(404);
    die("El documento no tiene pagos registrados para revertir.");
}

// ---------------------------------------------------------
// Transacción
// ---------------------------------------------------------
mysqli_begin_transaction($link);

try {
    $tipoSql = mysqli_real_escape_string($link, $tipoDocumento);

    // Conteos y montos previos para auditoría.
    $cantidadCabecerasAntes = intval(q1(
        $link,
        "SELECT COUNT(*)
         FROM pagos_compras
         WHERE id_documento = {$idDocumento}
           AND tipo_documento = '{$tipoSql}'"
    ));

    $cantidadDetallesAntes = intval(q1(
        $link,
        "SELECT COUNT(*)
         FROM pagos_compras_detalle d
         INNER JOIN pagos_compras p
                 ON p.id = d.pagos_compras
         WHERE p.id_documento = {$idDocumento}
           AND p.tipo_documento = '{$tipoSql}'"
    ));

    $montoPagadoAntes = floatval(q1(
        $link,
        "SELECT COALESCE(SUM(pago), 0)
         FROM pagos_compras
         WHERE id_documento = {$idDocumento}
           AND tipo_documento = '{$tipoSql}'"
    ));

    $key = "id_documento={$idDocumento};tipo_documento={$tipoDocumento}";

    $oldHeader = "cabeceras={$cantidadCabecerasAntes};"
               . "detalles={$cantidadDetallesAntes};"
               . "total_pagado={$montoPagadoAntes}";

    aud(
        $link,
        $username,
        $script,
        $action,
        "pagos_compras",
        $key,
        $oldHeader,
        "reversion=INICIO"
    );

    // 1. Eliminar detalles de todos los pagos del documento y tipo indicados.
    ex(
        $link,
        "DELETE d
         FROM pagos_compras_detalle d
         INNER JOIN pagos_compras p
                 ON p.id = d.pagos_compras
         WHERE p.id_documento = {$idDocumento}
           AND p.tipo_documento = '{$tipoSql}'"
    );

    $cantidadDetallesDespues = intval(q1(
        $link,
        "SELECT COUNT(*)
         FROM pagos_compras_detalle d
         INNER JOIN pagos_compras p
                 ON p.id = d.pagos_compras
         WHERE p.id_documento = {$idDocumento}
           AND p.tipo_documento = '{$tipoSql}'"
    ));

    aud(
        $link,
        $username,
        $script,
        $action,
        "pagos_compras_detalle",
        $key,
        "rows_before={$cantidadDetallesAntes}",
        "rows_after={$cantidadDetallesDespues}"
    );

    // 2. Eliminar todas las cabeceras de pagos del documento y tipo indicados.
    ex(
        $link,
        "DELETE FROM pagos_compras
         WHERE id_documento = {$idDocumento}
           AND tipo_documento = '{$tipoSql}'"
    );

    $cantidadCabecerasDespues = intval(q1(
        $link,
        "SELECT COUNT(*)
         FROM pagos_compras
         WHERE id_documento = {$idDocumento}
           AND tipo_documento = '{$tipoSql}'"
    ));

    aud(
        $link,
        $username,
        $script,
        $action,
        "pagos_compras",
        $key,
        "rows_before={$cantidadCabecerasAntes}",
        "rows_after={$cantidadCabecerasDespues}"
    );

    // 3. Solo las compras administrativas tienen el campo pagado.
    if ($tipoDocumento === "GASTOS") {
        $pagadoAntes = (string)q1(
            $link,
            "SELECT COALESCE(pagado, '') FROM compra WHERE id = {$idDocumento}"
        );

        ex(
            $link,
            "UPDATE compra
             SET pagado = 'N'
             WHERE id = {$idDocumento}"
        );

        $pagadoDespues = (string)q1(
            $link,
            "SELECT COALESCE(pagado, '') FROM compra WHERE id = {$idDocumento}"
        );

        aud(
            $link,
            $username,
            $script,
            $action,
            "compra",
            "compra.id={$idDocumento}",
            "pagado_before={$pagadoAntes}",
            "pagado_after={$pagadoDespues}"
        );
    }

    aud(
        $link,
        $username,
        $script,
        $action,
        "pagos_compras",
        $key,
        $oldHeader,
        "reversion=OK;"
        . "cabeceras_eliminadas={$cantidadCabecerasAntes};"
        . "detalles_eliminados={$cantidadDetallesAntes};"
        . "total_revertido={$montoPagadoAntes}"
    );

    mysqli_commit($link);

    echo "Pagos revertidos correctamente.";

} catch (Throwable $e) {
    mysqli_rollback($link);

    $mensaje = "ERROR: " . $e->getMessage();

    aud(
        $link,
        $username,
        $script,
        $action,
        "pagos_compras",
        "id_documento={$idDocumento};tipo_documento={$tipoDocumento}",
        "reversion=FALLA",
        $mensaje
    );

    http_response_code(500);
    echo $mensaje;
}
?>