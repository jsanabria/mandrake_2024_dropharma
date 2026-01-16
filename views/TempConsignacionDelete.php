<?php

namespace PHPMaker2024\mandrake;

// Page object
$TempConsignacionDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { temp_consignacion: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var ftemp_consignaciondelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftemp_consignaciondelete")
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
<form name="ftemp_consignaciondelete" id="ftemp_consignaciondelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="temp_consignacion">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_temp_consignacion_id" class="temp_consignacion_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_temp_consignacion__username" class="temp_consignacion__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_temp_consignacion_nro_documento" class="temp_consignacion_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <th class="<?= $Page->id_documento->headerCellClass() ?>"><span id="elh_temp_consignacion_id_documento" class="temp_consignacion_id_documento"><?= $Page->id_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_temp_consignacion_tipo_documento" class="temp_consignacion_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_temp_consignacion_fabricante" class="temp_consignacion_fabricante"><?= $Page->fabricante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_temp_consignacion_articulo" class="temp_consignacion_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
        <th class="<?= $Page->cantidad_movimiento->headerCellClass() ?>"><span id="elh_temp_consignacion_cantidad_movimiento" class="temp_consignacion_cantidad_movimiento"><?= $Page->cantidad_movimiento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_entre_fechas->Visible) { // cantidad_entre_fechas ?>
        <th class="<?= $Page->cantidad_entre_fechas->headerCellClass() ?>"><span id="elh_temp_consignacion_cantidad_entre_fechas" class="temp_consignacion_cantidad_entre_fechas"><?= $Page->cantidad_entre_fechas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_acumulada->Visible) { // cantidad_acumulada ?>
        <th class="<?= $Page->cantidad_acumulada->headerCellClass() ?>"><span id="elh_temp_consignacion_cantidad_acumulada" class="temp_consignacion_cantidad_acumulada"><?= $Page->cantidad_acumulada->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_ajuste->Visible) { // cantidad_ajuste ?>
        <th class="<?= $Page->cantidad_ajuste->headerCellClass() ?>"><span id="elh_temp_consignacion_cantidad_ajuste" class="temp_consignacion_cantidad_ajuste"><?= $Page->cantidad_ajuste->caption() ?></span></th>
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
<?php if ($Page->_username->Visible) { // username ?>
        <td<?= $Page->_username->cellAttributes() ?>>
<span id="">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td<?= $Page->nro_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <td<?= $Page->id_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td<?= $Page->articulo->cellAttributes() ?>>
<span id="">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
        <td<?= $Page->cantidad_movimiento->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_movimiento->viewAttributes() ?>>
<?= $Page->cantidad_movimiento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_entre_fechas->Visible) { // cantidad_entre_fechas ?>
        <td<?= $Page->cantidad_entre_fechas->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_entre_fechas->viewAttributes() ?>>
<?= $Page->cantidad_entre_fechas->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_acumulada->Visible) { // cantidad_acumulada ?>
        <td<?= $Page->cantidad_acumulada->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_acumulada->viewAttributes() ?>>
<?= $Page->cantidad_acumulada->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_ajuste->Visible) { // cantidad_ajuste ?>
        <td<?= $Page->cantidad_ajuste->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_ajuste->viewAttributes() ?>>
<?= $Page->cantidad_ajuste->getViewValue() ?></span>
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
