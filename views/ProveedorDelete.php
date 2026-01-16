<?php

namespace PHPMaker2024\mandrake;

// Page object
$ProveedorDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { proveedor: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fproveedordelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproveedordelete")
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
<form name="fproveedordelete" id="fproveedordelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor">
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
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <th class="<?= $Page->ci_rif->headerCellClass() ?>"><span id="elh_proveedor_ci_rif" class="proveedor_ci_rif"><?= $Page->ci_rif->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_proveedor_nombre" class="proveedor_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
        <th class="<?= $Page->ciudad->headerCellClass() ?>"><span id="elh_proveedor_ciudad" class="proveedor_ciudad"><?= $Page->ciudad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta_auxiliar->Visible) { // cuenta_auxiliar ?>
        <th class="<?= $Page->cuenta_auxiliar->headerCellClass() ?>"><span id="elh_proveedor_cuenta_auxiliar" class="proveedor_cuenta_auxiliar"><?= $Page->cuenta_auxiliar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta_gasto->Visible) { // cuenta_gasto ?>
        <th class="<?= $Page->cuenta_gasto->headerCellClass() ?>"><span id="elh_proveedor_cuenta_gasto" class="proveedor_cuenta_gasto"><?= $Page->cuenta_gasto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_proveedor_activo" class="proveedor_activo"><?= $Page->activo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_proveedor_fabricante" class="proveedor_fabricante"><?= $Page->fabricante->caption() ?></span></th>
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
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <td<?= $Page->ci_rif->cellAttributes() ?>>
<span id="">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td<?= $Page->nombre->cellAttributes() ?>>
<span id="">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
        <td<?= $Page->ciudad->cellAttributes() ?>>
<span id="">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cuenta_auxiliar->Visible) { // cuenta_auxiliar ?>
        <td<?= $Page->cuenta_auxiliar->cellAttributes() ?>>
<span id="">
<span<?= $Page->cuenta_auxiliar->viewAttributes() ?>>
<?= $Page->cuenta_auxiliar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cuenta_gasto->Visible) { // cuenta_gasto ?>
        <td<?= $Page->cuenta_gasto->cellAttributes() ?>>
<span id="">
<span<?= $Page->cuenta_gasto->viewAttributes() ?>>
<?= $Page->cuenta_gasto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td<?= $Page->activo->cellAttributes() ?>>
<span id="">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td<?= $Page->fabricante->cellAttributes() ?>>
<span id="">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
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
