<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteDetalleDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cobros_cliente_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fcobros_cliente_detalledelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_cliente_detalledelete")
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
<form name="fcobros_cliente_detalledelete" id="fcobros_cliente_detalledelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente_detalle">
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <th class="<?= $Page->metodo_pago->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_metodo_pago" class="cobros_cliente_detalle_metodo_pago"><?= $Page->metodo_pago->caption() ?></span></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_referencia" class="cobros_cliente_detalle_referencia"><?= $Page->referencia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <th class="<?= $Page->monto_moneda->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_monto_moneda" class="cobros_cliente_detalle_monto_moneda"><?= $Page->monto_moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_moneda" class="cobros_cliente_detalle_moneda"><?= $Page->moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
        <th class="<?= $Page->tasa_moneda->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_tasa_moneda" class="cobros_cliente_detalle_tasa_moneda"><?= $Page->tasa_moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <th class="<?= $Page->monto_bs->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_monto_bs" class="cobros_cliente_detalle_monto_bs"><?= $Page->monto_bs->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <th class="<?= $Page->tasa_usd->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_tasa_usd" class="cobros_cliente_detalle_tasa_usd"><?= $Page->tasa_usd->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <th class="<?= $Page->monto_usd->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_monto_usd" class="cobros_cliente_detalle_monto_usd"><?= $Page->monto_usd->caption() ?></span></th>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><span id="elh_cobros_cliente_detalle_banco" class="cobros_cliente_detalle_banco"><?= $Page->banco->caption() ?></span></th>
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <td<?= $Page->metodo_pago->cellAttributes() ?>>
<span id="">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <td<?= $Page->referencia->cellAttributes() ?>>
<span id="">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <td<?= $Page->monto_moneda->cellAttributes() ?>>
<span id="">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <td<?= $Page->moneda->cellAttributes() ?>>
<span id="">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
        <td<?= $Page->tasa_moneda->cellAttributes() ?>>
<span id="">
<span<?= $Page->tasa_moneda->viewAttributes() ?>>
<?= $Page->tasa_moneda->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <td<?= $Page->monto_bs->cellAttributes() ?>>
<span id="">
<span<?= $Page->monto_bs->viewAttributes() ?>>
<?= $Page->monto_bs->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <td<?= $Page->tasa_usd->cellAttributes() ?>>
<span id="">
<span<?= $Page->tasa_usd->viewAttributes() ?>>
<?= $Page->tasa_usd->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <td<?= $Page->monto_usd->cellAttributes() ?>>
<span id="">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <td<?= $Page->banco->cellAttributes() ?>>
<span id="">
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
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
