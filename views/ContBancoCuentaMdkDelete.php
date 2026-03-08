<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContBancoCuentaMdkDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_banco_cuenta_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fcont_banco_cuenta_mdkdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_banco_cuenta_mdkdelete")
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
<form name="fcont_banco_cuenta_mdkdelete" id="fcont_banco_cuenta_mdkdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_banco_cuenta_mdk">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_cont_banco_cuenta_mdk_id" class="cont_banco_cuenta_mdk_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->banco_id->Visible) { // banco_id ?>
        <th class="<?= $Page->banco_id->headerCellClass() ?>"><span id="elh_cont_banco_cuenta_mdk_banco_id" class="cont_banco_cuenta_mdk_banco_id"><?= $Page->banco_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
        <th class="<?= $Page->cuenta_id->headerCellClass() ?>"><span id="elh_cont_banco_cuenta_mdk_cuenta_id" class="cont_banco_cuenta_mdk_cuenta_id"><?= $Page->cuenta_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
        <th class="<?= $Page->estado->headerCellClass() ?>"><span id="elh_cont_banco_cuenta_mdk_estado" class="cont_banco_cuenta_mdk_estado"><?= $Page->estado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_cont_banco_cuenta_mdk_created_at" class="cont_banco_cuenta_mdk_created_at"><?= $Page->created_at->caption() ?></span></th>
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
<?php if ($Page->banco_id->Visible) { // banco_id ?>
        <td<?= $Page->banco_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->banco_id->viewAttributes() ?>>
<?= $Page->banco_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
        <td<?= $Page->cuenta_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->cuenta_id->viewAttributes() ?>>
<?= $Page->cuenta_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
        <td<?= $Page->estado->cellAttributes() ?>>
<span id="">
<span<?= $Page->estado->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->estado->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <td<?= $Page->created_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
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
