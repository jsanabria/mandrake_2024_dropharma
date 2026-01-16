<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteFacturaDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cobros_cliente_factura: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fcobros_cliente_facturadelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_cliente_facturadelete")
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
<form name="fcobros_cliente_facturadelete" id="fcobros_cliente_facturadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_factura">
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_tipo_documento" class="cobros_cliente_factura_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <th class="<?= $Page->abono->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_abono" class="cobros_cliente_factura_abono"><?= $Page->abono->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_monto" class="cobros_cliente_factura_monto"><?= $Page->monto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
        <th class="<?= $Page->retivamonto->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_retivamonto" class="cobros_cliente_factura_retivamonto"><?= $Page->retivamonto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
        <th class="<?= $Page->retiva->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_retiva" class="cobros_cliente_factura_retiva"><?= $Page->retiva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
        <th class="<?= $Page->retislrmonto->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_retislrmonto" class="cobros_cliente_factura_retislrmonto"><?= $Page->retislrmonto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
        <th class="<?= $Page->retislr->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_retislr" class="cobros_cliente_factura_retislr"><?= $Page->retislr->caption() ?></span></th>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><span id="elh_cobros_cliente_factura_comprobante" class="cobros_cliente_factura_comprobante"><?= $Page->comprobante->caption() ?></span></th>
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <td<?= $Page->abono->cellAttributes() ?>>
<span id="">
<span<?= $Page->abono->viewAttributes() ?>>
<?= $Page->abono->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <td<?= $Page->monto->cellAttributes() ?>>
<span id="">
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
        <td<?= $Page->retivamonto->cellAttributes() ?>>
<span id="">
<span<?= $Page->retivamonto->viewAttributes() ?>>
<?= $Page->retivamonto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
        <td<?= $Page->retiva->cellAttributes() ?>>
<span id="">
<span<?= $Page->retiva->viewAttributes() ?>>
<?= $Page->retiva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
        <td<?= $Page->retislrmonto->cellAttributes() ?>>
<span id="">
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<?= $Page->retislrmonto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
        <td<?= $Page->retislr->cellAttributes() ?>>
<span id="">
<span<?= $Page->retislr->viewAttributes() ?>>
<?= $Page->retislr->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <td<?= $Page->comprobante->cellAttributes() ?>>
<span id="">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
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
