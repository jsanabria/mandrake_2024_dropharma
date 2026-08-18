<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$articulo = TdcfcvRequestInt("articulo");
$cantidad = (float)($_POST["cantidad"] ?? $_GET["cantidad"] ?? 0);
$pedido = TdcfcvRequestInt("pedido");
$idItem = TdcfcvRequestInt("id_item");

if ($idItem <= 0) {
    // TdcfcvJsonError("Ítem no válido.");
}

if ($pedido <= 0) {
    // TdcfcvJsonError("Pedido no válido.");
}

if ($articulo <= 0) {
    TdcfcvJsonError("Artículo no válido.");
}

if ($cantidad <= 0) {
    TdcfcvJsonError("La cantidad debe ser mayor a cero.");
}


// =========================================================
// Obtener información del documento y del renglón
// =========================================================
$rowDetalle = ExecuteRow("
    SELECT
        IFNULL(s.id_documento_padre, 0) AS id_documento_padre,
        IFNULL(s.doc_afe, 0) AS doc_afe,
        IFNULL(s.documento, '') AS documento,
        IFNULL(es.cantidad_articulo, 0) AS cantidad_actual
    FROM salidas AS s
    INNER JOIN entradas_salidas AS es
        ON es.id_documento = s.id
       AND es.tipo_documento = s.tipo_documento
    WHERE s.id = {$pedido}
      AND s.tipo_documento = 'TDCFCV'
      AND es.id = {$idItem}
      AND es.articulo = {$articulo}
    LIMIT 1
");

if (!$rowDetalle) {
    // TdcfcvJsonError("No se encontró el ítem de la factura.");
}


$idDocumentoPadre = intval(
    $rowDetalle["id_documento_padre"] ?? 0
);

$cantidadActual = (float)(
    $rowDetalle["cantidad_actual"] ?? 0
);

$documento = strtoupper(trim(
    (string)($rowDetalle["documento"] ?? "")
));

$docAfe = intval(
    $rowDetalle["doc_afe"] ?? 0
);


$cantidadCambio =
    abs($cantidad - $cantidadActual) > 0.0001;


// =========================================================
// Documento proveniente de Orden de Entrega
// =========================================================
if ($idDocumentoPadre > 0 && $cantidadCambio) {

    TdcfcvJsonOk([
        "pedido" => (string)$pedido,
        "id_item" => (string)$idItem,
        "id_documento_padre" => $idDocumentoPadre,
        "cantidad_actual" => $cantidadActual,
        "cantidad_solicitada" => $cantidad,
        "cantidad_modificable" => false,
        "cantidad_valida" => false,
        "mensaje" =>
            "La factura se origina de una Orden de Entrega y no permite modificar la cantidad."
    ]);

    exit;
}


// =========================================================
// NOTA DE CRÉDITO
//
// No validar contra existencia física actual.
//
// La cantidad máxima permitida es:
//
//   Cantidad facturada originalmente
//   - Cantidad ya devuelta mediante NC anteriores
//
// La NC actual ($pedido) se excluye del acumulado para
// evitar que se descuente a sí misma durante una edición.
// =========================================================
if ($documento === "NC") {

    if ($docAfe <= 0) {
        TdcfcvJsonError(
            "No se pudo identificar la factura afectada por la Nota de Crédito."
        );
    }

    // -----------------------------------------------------
    // 1. Cantidad originalmente facturada del artículo
    // -----------------------------------------------------
    $cantidadFacturada = (float)ExecuteScalar("
        SELECT IFNULL(SUM(es.cantidad_articulo), 0)
        FROM entradas_salidas AS es
        WHERE es.tipo_documento = 'TDCFCV'
          AND es.id_documento = {$docAfe}
          AND es.articulo = {$articulo}
    ");


    // -----------------------------------------------------
    // 2. Cantidad ya devuelta mediante NC anteriores
    //
    // Solo se consideran documentos PROCESADOS.
    // Se excluye la NC que actualmente se está editando.
    // -----------------------------------------------------
    $cantidadDevueltaAnterior = (float)ExecuteScalar("
        SELECT IFNULL(SUM(es.cantidad_articulo), 0)
        FROM salidas AS s
        INNER JOIN entradas_salidas AS es
            ON es.id_documento = s.id
           AND es.tipo_documento = s.tipo_documento
        WHERE s.tipo_documento = 'TDCFCV'
          AND s.documento = 'NC'
          AND IFNULL(s.doc_afe, 0) = {$docAfe}
          AND s.id <> {$pedido}
          AND s.estatus = 'PROCESADO'
          AND es.articulo = {$articulo}
    ");


    // -----------------------------------------------------
    // 3. Saldo todavía disponible para devolver
    // -----------------------------------------------------
    $cantidadDisponible =
        $cantidadFacturada -
        $cantidadDevueltaAnterior;

    // Por seguridad nunca dejamos un disponible negativo.
    if ($cantidadDisponible < 0) {
        $cantidadDisponible = 0;
    }


    // -----------------------------------------------------
    // 4. Validar la cantidad solicitada
    // -----------------------------------------------------
    $cantidadValida =
        $cantidad <= ($cantidadDisponible + 0.0001);


    TdcfcvJsonOk([
        "pedido" => (string)$pedido,
        "articulo" => (string)$articulo,

        "documento" => "NC",
        "doc_afe" => $docAfe,

        // Se mantiene para compatibilidad con el JS actual.
        "valida_existencia" => true,

        "cantidad_solicitada" => $cantidad,

        // Para el JS, este es ahora el saldo realmente
        // disponible para devolver.
        "cantidad_disponible" => $cantidadDisponible,

        // Datos adicionales útiles para diagnóstico.
        "cantidad_facturada" => $cantidadFacturada,
        "cantidad_devuelta_anterior" => $cantidadDevueltaAnterior,

        "cantidad_valida" => $cantidadValida,
        "cantidad_modificable" => true,

        "id_documento_padre" => $idDocumentoPadre,

        "tipo_validacion" => "FACTURA_AFECTADA",

        "mensaje" => $cantidadValida
            ? "Cantidad válida según el saldo disponible de la factura afectada."
            : "La cantidad solicitada supera el saldo disponible del artículo en la factura afectada."
    ]);

    exit;
}


// =========================================================
// FACTURA / NOTA DE DÉBITO
//
// Mantener exactamente la validación actual contra
// existencia física.
// =========================================================
$row = ExecuteRow("
    SELECT
        IFNULL(articulo_inventario, 'N') AS articulo_inventario,
        IFNULL(cantidad_en_mano, 0) AS cantidad_en_mano
    FROM articulo
    WHERE id = {$articulo}
    LIMIT 1
");

if (!$row) {
    TdcfcvJsonError("No se encontró el artículo.");
}

$articuloInventario =
    strtoupper(trim((string)$row["articulo_inventario"]));

$cantidadEnMano =
    (float)$row["cantidad_en_mano"];

$validaExistencia =
    ($articuloInventario === "S");

$cantidadValida =
    !$validaExistencia ||
    $cantidad <= $cantidadEnMano;


TdcfcvJsonOk([
    "pedido" => (string)$pedido,
    "articulo" => (string)$articulo,
    "articulo_inventario" => $articuloInventario,
    "valida_existencia" => $validaExistencia,
    "cantidad_solicitada" => $cantidad,
    "cantidad_disponible" => $cantidadEnMano,
    "cantidad_valida" => $cantidadValida,
    "cantidad_modificable" => true,
    "id_documento_padre" => 0,
    "tipo_validacion" => "INVENTARIO",

    "mensaje" => $cantidadValida
        ? "Cantidad disponible."
        : "La cantidad solicitada es mayor a la existencia disponible."
]);