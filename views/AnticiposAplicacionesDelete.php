<?php

namespace PHPMaker2024\mandrake;

// Page object
$AnticiposAplicacionesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { anticipos_aplicaciones: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fanticipos_aplicacionesdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fanticipos_aplicacionesdelete")
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
<form name="fanticipos_aplicacionesdelete" id="fanticipos_aplicacionesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="anticipos_aplicaciones">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_id" class="anticipos_aplicaciones_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->anticipo_cobro_id->Visible) { // anticipo_cobro_id ?>
        <th class="<?= $Page->anticipo_cobro_id->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_anticipo_cobro_id" class="anticipos_aplicaciones_anticipo_cobro_id"><?= $Page->anticipo_cobro_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cobro_factura_id->Visible) { // cobro_factura_id ?>
        <th class="<?= $Page->cobro_factura_id->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_cobro_factura_id" class="anticipos_aplicaciones_cobro_factura_id"><?= $Page->cobro_factura_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->salida_id->Visible) { // salida_id ?>
        <th class="<?= $Page->salida_id->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_salida_id" class="anticipos_aplicaciones_salida_id"><?= $Page->salida_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_fecha" class="anticipos_aplicaciones_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones__username" class="anticipos_aplicaciones__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <th class="<?= $Page->monto_moneda->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_monto_moneda" class="anticipos_aplicaciones_monto_moneda"><?= $Page->monto_moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_moneda" class="anticipos_aplicaciones_moneda"><?= $Page->moneda->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tasa_factura->Visible) { // tasa_factura ?>
        <th class="<?= $Page->tasa_factura->headerCellClass() ?>"><span id="elh_anticipos_aplicaciones_tasa_factura" class="anticipos_aplicaciones_tasa_factura"><?= $Page->tasa_factura->caption() ?></span></th>
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
<?php if ($Page->anticipo_cobro_id->Visible) { // anticipo_cobro_id ?>
        <td<?= $Page->anticipo_cobro_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->anticipo_cobro_id->viewAttributes() ?>>
<?= $Page->anticipo_cobro_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cobro_factura_id->Visible) { // cobro_factura_id ?>
        <td<?= $Page->cobro_factura_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->cobro_factura_id->viewAttributes() ?>>
<?= $Page->cobro_factura_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->salida_id->Visible) { // salida_id ?>
        <td<?= $Page->salida_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->salida_id->viewAttributes() ?>>
<?= $Page->salida_id->getViewValue() ?></span>
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
<?php if ($Page->_username->Visible) { // username ?>
        <td<?= $Page->_username->cellAttributes() ?>>
<span id="">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
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
<?php if ($Page->tasa_factura->Visible) { // tasa_factura ?>
        <td<?= $Page->tasa_factura->cellAttributes() ?>>
<span id="">
<span<?= $Page->tasa_factura->viewAttributes() ?>>
<?= $Page->tasa_factura->getViewValue() ?></span>
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
