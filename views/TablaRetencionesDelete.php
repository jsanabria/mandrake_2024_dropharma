<?php

namespace PHPMaker2024\mandrake;

// Page object
$TablaRetencionesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tabla_retenciones: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ftabla_retencionesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftabla_retencionesdelete")
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
<form name="ftabla_retencionesdelete" id="ftabla_retencionesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tabla_retenciones">
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><span id="elh_tabla_retenciones_codigo" class="tabla_retenciones_codigo"><?= $Page->codigo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><span id="elh_tabla_retenciones_tipo" class="tabla_retenciones_tipo"><?= $Page->tipo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
        <th class="<?= $Page->base_imponible->headerCellClass() ?>"><span id="elh_tabla_retenciones_base_imponible" class="tabla_retenciones_base_imponible"><?= $Page->base_imponible->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <th class="<?= $Page->tarifa->headerCellClass() ?>"><span id="elh_tabla_retenciones_tarifa" class="tabla_retenciones_tarifa"><?= $Page->tarifa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
        <th class="<?= $Page->sustraendo->headerCellClass() ?>"><span id="elh_tabla_retenciones_sustraendo" class="tabla_retenciones_sustraendo"><?= $Page->sustraendo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
        <th class="<?= $Page->pagos_mayores->headerCellClass() ?>"><span id="elh_tabla_retenciones_pagos_mayores" class="tabla_retenciones_pagos_mayores"><?= $Page->pagos_mayores->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_tabla_retenciones_activo" class="tabla_retenciones_activo"><?= $Page->activo->caption() ?></span></th>
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <td<?= $Page->codigo->cellAttributes() ?>>
<span id="">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <td<?= $Page->tipo->cellAttributes() ?>>
<span id="">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
        <td<?= $Page->base_imponible->cellAttributes() ?>>
<span id="">
<span<?= $Page->base_imponible->viewAttributes() ?>>
<?= $Page->base_imponible->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <td<?= $Page->tarifa->cellAttributes() ?>>
<span id="">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
        <td<?= $Page->sustraendo->cellAttributes() ?>>
<span id="">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
        <td<?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="">
<span<?= $Page->pagos_mayores->viewAttributes() ?>>
<?= $Page->pagos_mayores->getViewValue() ?></span>
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
