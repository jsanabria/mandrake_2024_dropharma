<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContPlanctaDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_plancta: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fcont_planctadelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_planctadelete")
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
<form name="fcont_planctadelete" id="fcont_planctadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plancta">
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
<?php if ($Page->clase->Visible) { // clase ?>
        <th class="<?= $Page->clase->headerCellClass() ?>"><span id="elh_cont_plancta_clase" class="cont_plancta_clase"><?= $Page->clase->caption() ?></span></th>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
        <th class="<?= $Page->grupo->headerCellClass() ?>"><span id="elh_cont_plancta_grupo" class="cont_plancta_grupo"><?= $Page->grupo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><span id="elh_cont_plancta_cuenta" class="cont_plancta_cuenta"><?= $Page->cuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
        <th class="<?= $Page->subcuenta->headerCellClass() ?>"><span id="elh_cont_plancta_subcuenta" class="cont_plancta_subcuenta"><?= $Page->subcuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><span id="elh_cont_plancta_descripcion" class="cont_plancta_descripcion"><?= $Page->descripcion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
        <th class="<?= $Page->clasificacion->headerCellClass() ?>"><span id="elh_cont_plancta_clasificacion" class="cont_plancta_clasificacion"><?= $Page->clasificacion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><span id="elh_cont_plancta_moneda" class="cont_plancta_moneda"><?= $Page->moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
        <th class="<?= $Page->activa->headerCellClass() ?>"><span id="elh_cont_plancta_activa" class="cont_plancta_activa"><?= $Page->activa->caption() ?></span></th>
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
<?php if ($Page->clase->Visible) { // clase ?>
        <td<?= $Page->clase->cellAttributes() ?>>
<span id="">
<span<?= $Page->clase->viewAttributes() ?>>
<?= $Page->clase->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
        <td<?= $Page->grupo->cellAttributes() ?>>
<span id="">
<span<?= $Page->grupo->viewAttributes() ?>>
<?= $Page->grupo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span id="">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
        <td<?= $Page->subcuenta->cellAttributes() ?>>
<span id="">
<span<?= $Page->subcuenta->viewAttributes() ?>>
<?= $Page->subcuenta->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <td<?= $Page->descripcion->cellAttributes() ?>>
<span id="">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
        <td<?= $Page->clasificacion->cellAttributes() ?>>
<span id="">
<span<?= $Page->clasificacion->viewAttributes() ?>>
<?= $Page->clasificacion->getViewValue() ?></span>
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
<?php if ($Page->activa->Visible) { // activa ?>
        <td<?= $Page->activa->cellAttributes() ?>>
<span id="">
<span<?= $Page->activa->viewAttributes() ?>>
<?= $Page->activa->getViewValue() ?></span>
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
