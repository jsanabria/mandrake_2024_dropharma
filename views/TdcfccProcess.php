<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcfccProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$pedido = intval($_REQUEST["pedido"] ?? 0);

if ($pedido <= 0) {
    die("Documento no válido.");
}

/*
|--------------------------------------------------------------------------
| Procesar compra
|--------------------------------------------------------------------------
*/
$sql = "
    UPDATE entradas
    SET
        estatus = 'PROCESADO',
        aplica_retencion = 'N',
        username = '" . AdjustSql(CurrentUserName()) . "'
    WHERE id = {$pedido}
      AND tipo_documento = 'TDCFCC'
    LIMIT 1
";

Execute($sql);

/*
|--------------------------------------------------------------------------
| Las retenciones se gestionan exclusivamente desde Compras Administrativas
|--------------------------------------------------------------------------
| En TDCFCC solo se registran artículos, costos y movimientos de inventario.
| El Libro de Compras, las retenciones y sus comprobantes se generan desde
| el módulo de Compras Administrativas.
|--------------------------------------------------------------------------
*/

/*
$resultadoRetenciones =
    CalcularRetencionesCompraInventario($pedido);

if ($resultadoRetenciones === false) {
    die(
        "No fue posible calcular las retenciones " .
        "de la compra de inventario."
    );
}
*/

/*
|--------------------------------------------------------------------------
| Costo promedio ponderado
|--------------------------------------------------------------------------
*/
$sql = "
    SELECT
        articulo,
        AVG(costo_unidad) AS costo,
        SUM(cantidad_movimiento) AS cantidad
    FROM entradas_salidas
    WHERE id_documento = {$pedido}
      AND tipo_documento = 'TDCNRP'
    GROUP BY articulo
";

$rows = ExecuteRows($sql);

foreach ($rows as $value) {
    CalcularCostoPromedioPonderado(
        intval($value["articulo"]),
        floatval($value["costo"]),
        intval($value["cantidad"]),
        $pedido
    );
}

header(
    "Location: ViewInTdcfccEdit/" . $pedido . "?showdetail="
);

?>
<?= GetDebugMessage() ?>
