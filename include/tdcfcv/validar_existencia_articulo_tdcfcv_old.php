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

$rowDetalle = ExecuteRow("
    SELECT
        IFNULL(s.id_documento_padre, 0) AS id_documento_padre,
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

$cantidadCambio = abs($cantidad - $cantidadActual) > 0.0001;

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

$articuloInventario = strtoupper(trim((string)$row["articulo_inventario"]));
$cantidadEnMano = (float)$row["cantidad_en_mano"];

$validaExistencia = ($articuloInventario === "S");
$cantidadValida = !$validaExistencia || $cantidad <= $cantidadEnMano;

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
    "mensaje" => $cantidadValida
        ? "Cantidad disponible."
        : "La cantidad solicitada es mayor a la existencia disponible."
]);