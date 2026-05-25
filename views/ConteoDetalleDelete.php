<?php

namespace PHPMaker2024\mandrake;

// Page object
$ConteoDetalleDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { conteo_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fconteo_detalledelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fconteo_detalledelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fconteo_detalledelete" id="fconteo_detalledelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="conteo_detalle">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->id->Visible) { // id ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_conteo_detalle_id" class="conteo_detalle_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->conteo->Visible) { // conteo ?>
        <th class="<?= $Page->conteo->headerCellClass() ?>"><span id="elh_conteo_detalle_conteo" class="conteo_detalle_conteo"><?= $Page->conteo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_conteo_detalle_articulo" class="conteo_detalle_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <th class="<?= $Page->cantidad->headerCellClass() ?>"><span id="elh_conteo_detalle_cantidad" class="conteo_detalle_cantidad"><?= $Page->cantidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_switch->Visible) { // switch ?>
        <th class="<?= $Page->_switch->headerCellClass() ?>"><span id="elh_conteo_detalle__switch" class="conteo_detalle__switch"><?= $Page->_switch->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->id->Visible) { // id ?>
        <td<?= $Page->id->cellAttributes() ?>>
<span id="">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->conteo->Visible) { // conteo ?>
        <td<?= $Page->conteo->cellAttributes() ?>>
<span id="">
<span<?= $Page->conteo->viewAttributes() ?>>
<?= $Page->conteo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td<?= $Page->articulo->cellAttributes() ?>>
<span id="">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <td<?= $Page->cantidad->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_switch->Visible) { // switch ?>
        <td<?= $Page->_switch->cellAttributes() ?>>
<span id="">
<span<?= $Page->_switch->viewAttributes() ?>>
<?= $Page->_switch->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Recordset?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
