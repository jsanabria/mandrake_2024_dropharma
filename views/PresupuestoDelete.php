<?php

namespace PHPMaker2024\mandrake;

// Page object
$PresupuestoDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { presupuesto: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fpresupuestodelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpresupuestodelete")
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
<form name="fpresupuestodelete" id="fpresupuestodelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="presupuesto">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_presupuesto_id" class="presupuesto_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_presupuesto_fecha" class="presupuesto_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente_potencial->Visible) { // cliente_potencial ?>
        <th class="<?= $Page->cliente_potencial->headerCellClass() ?>"><span id="elh_presupuesto_cliente_potencial" class="presupuesto_cliente_potencial"><?= $Page->cliente_potencial->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rif->Visible) { // rif ?>
        <th class="<?= $Page->rif->headerCellClass() ?>"><span id="elh_presupuesto_rif" class="presupuesto_rif"><?= $Page->rif->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_presupuesto_cliente" class="presupuesto_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->proyecto->Visible) { // proyecto ?>
        <th class="<?= $Page->proyecto->headerCellClass() ?>"><span id="elh_presupuesto_proyecto" class="presupuesto_proyecto"><?= $Page->proyecto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th class="<?= $Page->estatus->headerCellClass() ?>"><span id="elh_presupuesto_estatus" class="presupuesto_estatus"><?= $Page->estatus->caption() ?></span></th>
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
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td<?= $Page->fecha->cellAttributes() ?>>
<span id="">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente_potencial->Visible) { // cliente_potencial ?>
        <td<?= $Page->cliente_potencial->cellAttributes() ?>>
<span id="">
<span<?= $Page->cliente_potencial->viewAttributes() ?>>
<?= $Page->cliente_potencial->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->rif->Visible) { // rif ?>
        <td<?= $Page->rif->cellAttributes() ?>>
<span id="">
<span<?= $Page->rif->viewAttributes() ?>>
<?= $Page->rif->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td<?= $Page->cliente->cellAttributes() ?>>
<span id="">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->proyecto->Visible) { // proyecto ?>
        <td<?= $Page->proyecto->cellAttributes() ?>>
<span id="">
<span<?= $Page->proyecto->viewAttributes() ?>>
<?= $Page->proyecto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <td<?= $Page->estatus->cellAttributes() ?>>
<span id="">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
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
