<?php

namespace PHPMaker2024\mandrake;

// Page object
$ClienteDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cliente: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fclientedelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fclientedelete")
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
<form name="fclientedelete" id="fclientedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cliente">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_cliente_id" class="cliente_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
        <th class="<?= $Page->ci_rif->headerCellClass() ?>"><span id="elh_cliente_ci_rif" class="cliente_ci_rif"><?= $Page->ci_rif->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_cliente_nombre" class="cliente_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sucursal->Visible) { // sucursal ?>
        <th class="<?= $Page->sucursal->headerCellClass() ?>"><span id="elh_cliente_sucursal" class="cliente_sucursal"><?= $Page->sucursal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->contacto->Visible) { // contacto ?>
        <th class="<?= $Page->contacto->headerCellClass() ?>"><span id="elh_cliente_contacto" class="cliente_contacto"><?= $Page->contacto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
        <th class="<?= $Page->ciudad->headerCellClass() ?>"><span id="elh_cliente_ciudad" class="cliente_ciudad"><?= $Page->ciudad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->zona->Visible) { // zona ?>
        <th class="<?= $Page->zona->headerCellClass() ?>"><span id="elh_cliente_zona" class="cliente_zona"><?= $Page->zona->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo_cliente->Visible) { // tipo_cliente ?>
        <th class="<?= $Page->tipo_cliente->headerCellClass() ?>"><span id="elh_cliente_tipo_cliente" class="cliente_tipo_cliente"><?= $Page->tipo_cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
        <th class="<?= $Page->tarifa->headerCellClass() ?>"><span id="elh_cliente_tarifa" class="cliente_tarifa"><?= $Page->tarifa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><span id="elh_cliente_cuenta" class="cliente_cuenta"><?= $Page->cuenta->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_cliente_activo" class="cliente_activo"><?= $Page->activo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <th class="<?= $Page->dias_credito->headerCellClass() ?>"><span id="elh_cliente_dias_credito" class="cliente_dias_credito"><?= $Page->dias_credito->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_cliente_descuento" class="cliente_descuento"><?= $Page->descuento->caption() ?></span></th>
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
<?php if ($Page->sucursal->Visible) { // sucursal ?>
        <td<?= $Page->sucursal->cellAttributes() ?>>
<span id="">
<span<?= $Page->sucursal->viewAttributes() ?>>
<?= $Page->sucursal->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->contacto->Visible) { // contacto ?>
        <td<?= $Page->contacto->cellAttributes() ?>>
<span id="">
<span<?= $Page->contacto->viewAttributes() ?>>
<?= $Page->contacto->getViewValue() ?></span>
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
<?php if ($Page->zona->Visible) { // zona ?>
        <td<?= $Page->zona->cellAttributes() ?>>
<span id="">
<span<?= $Page->zona->viewAttributes() ?>>
<?= $Page->zona->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo_cliente->Visible) { // tipo_cliente ?>
        <td<?= $Page->tipo_cliente->cellAttributes() ?>>
<span id="">
<span<?= $Page->tipo_cliente->viewAttributes() ?>>
<?= $Page->tipo_cliente->getViewValue() ?></span>
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
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span id="">
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
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
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <td<?= $Page->dias_credito->cellAttributes() ?>>
<span id="">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <td<?= $Page->descuento->cellAttributes() ?>>
<span id="">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
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
