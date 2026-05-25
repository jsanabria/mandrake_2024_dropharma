<?php

namespace PHPMaker2024\mandrake;

// Page object
$ConteoDetalleView = &$Page;
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
<form name="fconteo_detalleview" id="fconteo_detalleview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { conteo_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fconteo_detalleview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fconteo_detalleview")
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
<input type="hidden" name="t" value="conteo_detalle">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_conteo_detalle_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_conteo_detalle_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->conteo->Visible) { // conteo ?>
    <tr id="r_conteo"<?= $Page->conteo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_conteo_detalle_conteo"><?= $Page->conteo->caption() ?></span></td>
        <td data-name="conteo"<?= $Page->conteo->cellAttributes() ?>>
<span id="el_conteo_detalle_conteo">
<span<?= $Page->conteo->viewAttributes() ?>>
<?= $Page->conteo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_conteo_detalle_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo"<?= $Page->articulo->cellAttributes() ?>>
<span id="el_conteo_detalle_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <tr id="r_cantidad"<?= $Page->cantidad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_conteo_detalle_cantidad"><?= $Page->cantidad->caption() ?></span></td>
        <td data-name="cantidad"<?= $Page->cantidad->cellAttributes() ?>>
<span id="el_conteo_detalle_cantidad">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_switch->Visible) { // switch ?>
    <tr id="r__switch"<?= $Page->_switch->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_conteo_detalle__switch"><?= $Page->_switch->caption() ?></span></td>
        <td data-name="_switch"<?= $Page->_switch->cellAttributes() ?>>
<span id="el_conteo_detalle__switch">
<span<?= $Page->_switch->viewAttributes() ?>>
<?= $Page->_switch->getViewValue() ?></span>
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
