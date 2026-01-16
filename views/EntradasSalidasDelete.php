<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasSalidasDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas_salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fentradas_salidasdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradas_salidasdelete")
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
<form name="fentradas_salidasdelete" id="fentradas_salidasdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas_salidas">
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_entradas_salidas_articulo" class="entradas_salidas_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><span id="elh_entradas_salidas_lote" class="entradas_salidas_lote"><?= $Page->lote->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><span id="elh_entradas_salidas_fecha_vencimiento" class="entradas_salidas_fecha_vencimiento"><?= $Page->fecha_vencimiento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
        <th class="<?= $Page->almacen->headerCellClass() ?>"><span id="elh_entradas_salidas_almacen" class="entradas_salidas_almacen"><?= $Page->almacen->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_compra->Visible) { // id_compra ?>
        <th class="<?= $Page->id_compra->headerCellClass() ?>"><span id="elh_entradas_salidas_id_compra" class="entradas_salidas_id_compra"><?= $Page->id_compra->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><span id="elh_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo"><?= $Page->cantidad_articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th class="<?= $Page->precio_unidad_sin_desc->headerCellClass() ?>"><span id="elh_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc"><?= $Page->precio_unidad_sin_desc->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_entradas_salidas_descuento" class="entradas_salidas_descuento"><?= $Page->descuento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <th class="<?= $Page->costo_unidad->headerCellClass() ?>"><span id="elh_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad"><?= $Page->costo_unidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <th class="<?= $Page->costo->headerCellClass() ?>"><span id="elh_entradas_salidas_costo" class="entradas_salidas_costo"><?= $Page->costo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <th class="<?= $Page->precio_unidad->headerCellClass() ?>"><span id="elh_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad"><?= $Page->precio_unidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><span id="elh_entradas_salidas_precio" class="entradas_salidas_precio"><?= $Page->precio->caption() ?></span></th>
<?php } ?>
<?php if ($Page->check_ne->Visible) { // check_ne ?>
        <th class="<?= $Page->check_ne->headerCellClass() ?>"><span id="elh_entradas_salidas_check_ne" class="entradas_salidas_check_ne"><?= $Page->check_ne->caption() ?></span></th>
<?php } ?>
<?php if ($Page->newdata->Visible) { // newdata ?>
        <th class="<?= $Page->newdata->headerCellClass() ?>"><span id="elh_entradas_salidas_newdata" class="entradas_salidas_newdata"><?= $Page->newdata->caption() ?></span></th>
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
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td<?= $Page->articulo->cellAttributes() ?>>
<span id="">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <td<?= $Page->lote->cellAttributes() ?>>
<span id="">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="">
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
        <td<?= $Page->almacen->cellAttributes() ?>>
<span id="">
<span<?= $Page->almacen->viewAttributes() ?>>
<?= $Page->almacen->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_compra->Visible) { // id_compra ?>
        <td<?= $Page->id_compra->cellAttributes() ?>>
<span id="">
<span<?= $Page->id_compra->viewAttributes() ?>>
<?= $Page->id_compra->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td<?= $Page->precio_unidad_sin_desc->cellAttributes() ?>>
<span id="">
<span<?= $Page->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Page->precio_unidad_sin_desc->getViewValue() ?></span>
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
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <td<?= $Page->costo_unidad->cellAttributes() ?>>
<span id="">
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <td<?= $Page->costo->cellAttributes() ?>>
<span id="">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <td<?= $Page->precio_unidad->cellAttributes() ?>>
<span id="">
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <td<?= $Page->precio->cellAttributes() ?>>
<span id="">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->check_ne->Visible) { // check_ne ?>
        <td<?= $Page->check_ne->cellAttributes() ?>>
<span id="">
<span<?= $Page->check_ne->viewAttributes() ?>>
<?= $Page->check_ne->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->newdata->Visible) { // newdata ?>
        <td<?= $Page->newdata->cellAttributes() ?>>
<span id="">
<span<?= $Page->newdata->viewAttributes() ?>>
<?= $Page->newdata->getViewValue() ?></span>
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
