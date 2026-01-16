<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasSalidasView = &$Page;
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
<form name="fentradas_salidasview" id="fentradas_salidasview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas_salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fentradas_salidasview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradas_salidasview")
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
<input type="hidden" name="t" value="entradas_salidas">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo"<?= $Page->articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <tr id="r_lote"<?= $Page->lote->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_lote"><?= $Page->lote->caption() ?></span></td>
        <td data-name="lote"<?= $Page->lote->cellAttributes() ?>>
<span id="el_entradas_salidas_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
    <tr id="r_fecha_vencimiento"<?= $Page->fecha_vencimiento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_fecha_vencimiento"><?= $Page->fecha_vencimiento->caption() ?></span></td>
        <td data-name="fecha_vencimiento"<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="el_entradas_salidas_fecha_vencimiento">
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <tr id="r_cantidad_articulo"<?= $Page->cantidad_articulo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_cantidad_articulo"><?= $Page->cantidad_articulo->caption() ?></span></td>
        <td data-name="cantidad_articulo"<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="el_entradas_salidas_cantidad_articulo">
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
    <tr id="r_articulo_unidad_medida"<?= $Page->articulo_unidad_medida->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_articulo_unidad_medida"><?= $Page->articulo_unidad_medida->caption() ?></span></td>
        <td data-name="articulo_unidad_medida"<?= $Page->articulo_unidad_medida->cellAttributes() ?>>
<span id="el_entradas_salidas_articulo_unidad_medida">
<span<?= $Page->articulo_unidad_medida->viewAttributes() ?>>
<?= $Page->articulo_unidad_medida->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
    <tr id="r_costo_unidad"<?= $Page->costo_unidad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_costo_unidad"><?= $Page->costo_unidad->caption() ?></span></td>
        <td data-name="costo_unidad"<?= $Page->costo_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_costo_unidad">
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
    <tr id="r_costo"<?= $Page->costo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_costo"><?= $Page->costo->caption() ?></span></td>
        <td data-name="costo"<?= $Page->costo->cellAttributes() ?>>
<span id="el_entradas_salidas_costo">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
    <tr id="r_precio_unidad"<?= $Page->precio_unidad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_precio_unidad"><?= $Page->precio_unidad->caption() ?></span></td>
        <td data-name="precio_unidad"<?= $Page->precio_unidad->cellAttributes() ?>>
<span id="el_entradas_salidas_precio_unidad">
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <tr id="r_precio"<?= $Page->precio->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_precio"><?= $Page->precio->caption() ?></span></td>
        <td data-name="precio"<?= $Page->precio->cellAttributes() ?>>
<span id="el_entradas_salidas_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->check_ne->Visible) { // check_ne ?>
    <tr id="r_check_ne"<?= $Page->check_ne->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_check_ne"><?= $Page->check_ne->caption() ?></span></td>
        <td data-name="check_ne"<?= $Page->check_ne->cellAttributes() ?>>
<span id="el_entradas_salidas_check_ne">
<span<?= $Page->check_ne->viewAttributes() ?>>
<?= $Page->check_ne->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->newdata->Visible) { // newdata ?>
    <tr id="r_newdata"<?= $Page->newdata->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_salidas_newdata"><?= $Page->newdata->caption() ?></span></td>
        <td data-name="newdata"<?= $Page->newdata->cellAttributes() ?>>
<span id="el_entradas_salidas_newdata">
<span<?= $Page->newdata->viewAttributes() ?>>
<?= $Page->newdata->getViewValue() ?></span>
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
