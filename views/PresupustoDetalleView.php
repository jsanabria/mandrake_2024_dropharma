<?php

namespace PHPMaker2024\mandrake;

// Page object
$PresupustoDetalleView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fpresupusto_detalleview" id="fpresupusto_detalleview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { presupusto_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fpresupusto_detalleview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpresupusto_detalleview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="presupusto_detalle">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_presupusto_detalle_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->presupuesto->Visible) { // presupuesto ?>
    <tr id="r_presupuesto"<?= $Page->presupuesto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_presupuesto"><?= $Page->presupuesto->caption() ?></span></td>
        <td data-name="presupuesto"<?= $Page->presupuesto->cellAttributes() ?>>
<span id="el_presupusto_detalle_presupuesto">
<span<?= $Page->presupuesto->viewAttributes() ?>>
<?= $Page->presupuesto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->grupo1->Visible) { // grupo1 ?>
    <tr id="r_grupo1"<?= $Page->grupo1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_grupo1"><?= $Page->grupo1->caption() ?></span></td>
        <td data-name="grupo1"<?= $Page->grupo1->cellAttributes() ?>>
<span id="el_presupusto_detalle_grupo1">
<span<?= $Page->grupo1->viewAttributes() ?>>
<?= $Page->grupo1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->grupo2->Visible) { // grupo2 ?>
    <tr id="r_grupo2"<?= $Page->grupo2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_grupo2"><?= $Page->grupo2->caption() ?></span></td>
        <td data-name="grupo2"<?= $Page->grupo2->cellAttributes() ?>>
<span id="el_presupusto_detalle_grupo2">
<span<?= $Page->grupo2->viewAttributes() ?>>
<?= $Page->grupo2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <tr id="r_numero"<?= $Page->numero->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_numero"><?= $Page->numero->caption() ?></span></td>
        <td data-name="numero"<?= $Page->numero->cellAttributes() ?>>
<span id="el_presupusto_detalle_numero">
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo"<?= $Page->articulo->cellAttributes() ?>>
<span id="el_presupusto_detalle_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->linea->Visible) { // linea ?>
    <tr id="r_linea"<?= $Page->linea->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_linea"><?= $Page->linea->caption() ?></span></td>
        <td data-name="linea"<?= $Page->linea->cellAttributes() ?>>
<span id="el_presupusto_detalle_linea">
<span<?= $Page->linea->viewAttributes() ?>>
<?= $Page->linea->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->imagen->Visible) { // imagen ?>
    <tr id="r_imagen"<?= $Page->imagen->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_imagen"><?= $Page->imagen->caption() ?></span></td>
        <td data-name="imagen"<?= $Page->imagen->cellAttributes() ?>>
<span id="el_presupusto_detalle_imagen">
<span<?= $Page->imagen->viewAttributes() ?>>
<?= $Page->imagen->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_presupusto_detalle_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <tr id="r_cantidad"<?= $Page->cantidad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_cantidad"><?= $Page->cantidad->caption() ?></span></td>
        <td data-name="cantidad"<?= $Page->cantidad->cellAttributes() ?>>
<span id="el_presupusto_detalle_cantidad">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <tr id="r_precio"<?= $Page->precio->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_precio"><?= $Page->precio->caption() ?></span></td>
        <td data-name="precio"<?= $Page->precio->cellAttributes() ?>>
<span id="el_presupusto_detalle_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total"<?= $Page->total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_presupusto_detalle_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el_presupusto_detalle_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
