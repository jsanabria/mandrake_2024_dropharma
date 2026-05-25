<?php

namespace PHPMaker2024\mandrake;

// Page object
$ReporteSeniatNeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { reporte_seniat_ne: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var freporte_seniat_nedelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("freporte_seniat_nedelete")
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
<form name="freporte_seniat_nedelete" id="freporte_seniat_nedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="reporte_seniat_ne">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_id" class="reporte_seniat_ne_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->periodo->Visible) { // periodo ?>
        <th class="<?= $Page->periodo->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_periodo" class="reporte_seniat_ne_periodo"><?= $Page->periodo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <th class="<?= $Page->cantidad->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_cantidad" class="reporte_seniat_ne_cantidad"><?= $Page->cantidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th class="<?= $Page->monto_total->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_monto_total" class="reporte_seniat_ne_monto_total"><?= $Page->monto_total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->email_destino->Visible) { // email_destino ?>
        <th class="<?= $Page->email_destino->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_email_destino" class="reporte_seniat_ne_email_destino"><?= $Page->email_destino->caption() ?></span></th>
<?php } ?>
<?php if ($Page->enviado_en->Visible) { // enviado_en ?>
        <th class="<?= $Page->enviado_en->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_enviado_en" class="reporte_seniat_ne_enviado_en"><?= $Page->enviado_en->caption() ?></span></th>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <th class="<?= $Page->usuario->headerCellClass() ?>"><span id="elh_reporte_seniat_ne_usuario" class="reporte_seniat_ne_usuario"><?= $Page->usuario->caption() ?></span></th>
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
<?php if ($Page->periodo->Visible) { // periodo ?>
        <td<?= $Page->periodo->cellAttributes() ?>>
<span id="">
<span<?= $Page->periodo->viewAttributes() ?>>
<?= $Page->periodo->getViewValue() ?></span>
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
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td<?= $Page->monto_total->cellAttributes() ?>>
<span id="">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->email_destino->Visible) { // email_destino ?>
        <td<?= $Page->email_destino->cellAttributes() ?>>
<span id="">
<span<?= $Page->email_destino->viewAttributes() ?>>
<?= $Page->email_destino->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->enviado_en->Visible) { // enviado_en ?>
        <td<?= $Page->enviado_en->cellAttributes() ?>>
<span id="">
<span<?= $Page->enviado_en->viewAttributes() ?>>
<?= $Page->enviado_en->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <td<?= $Page->usuario->cellAttributes() ?>>
<span id="">
<span<?= $Page->usuario->viewAttributes() ?>>
<?= $Page->usuario->getViewValue() ?></span>
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
