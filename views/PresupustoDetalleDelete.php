<?php

namespace PHPMaker2024\mandrake;

// Page object
$PresupustoDetalleDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { presupusto_detalle: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fpresupusto_detalledelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpresupusto_detalledelete")
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
<form name="fpresupusto_detalledelete" id="fpresupusto_detalledelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="presupusto_detalle">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_presupusto_detalle_id" class="presupusto_detalle_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->presupuesto->Visible) { // presupuesto ?>
        <th class="<?= $Page->presupuesto->headerCellClass() ?>"><span id="elh_presupusto_detalle_presupuesto" class="presupusto_detalle_presupuesto"><?= $Page->presupuesto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->grupo1->Visible) { // grupo1 ?>
        <th class="<?= $Page->grupo1->headerCellClass() ?>"><span id="elh_presupusto_detalle_grupo1" class="presupusto_detalle_grupo1"><?= $Page->grupo1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->grupo2->Visible) { // grupo2 ?>
        <th class="<?= $Page->grupo2->headerCellClass() ?>"><span id="elh_presupusto_detalle_grupo2" class="presupusto_detalle_grupo2"><?= $Page->grupo2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <th class="<?= $Page->numero->headerCellClass() ?>"><span id="elh_presupusto_detalle_numero" class="presupusto_detalle_numero"><?= $Page->numero->caption() ?></span></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_presupusto_detalle_articulo" class="presupusto_detalle_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->linea->Visible) { // linea ?>
        <th class="<?= $Page->linea->headerCellClass() ?>"><span id="elh_presupusto_detalle_linea" class="presupusto_detalle_linea"><?= $Page->linea->caption() ?></span></th>
<?php } ?>
<?php if ($Page->imagen->Visible) { // imagen ?>
        <th class="<?= $Page->imagen->headerCellClass() ?>"><span id="elh_presupusto_detalle_imagen" class="presupusto_detalle_imagen"><?= $Page->imagen->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><span id="elh_presupusto_detalle_descripcion" class="presupusto_detalle_descripcion"><?= $Page->descripcion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <th class="<?= $Page->cantidad->headerCellClass() ?>"><span id="elh_presupusto_detalle_cantidad" class="presupusto_detalle_cantidad"><?= $Page->cantidad->caption() ?></span></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><span id="elh_presupusto_detalle_precio" class="presupusto_detalle_precio"><?= $Page->precio->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_presupusto_detalle_total" class="presupusto_detalle_total"><?= $Page->total->caption() ?></span></th>
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
<?php if ($Page->presupuesto->Visible) { // presupuesto ?>
        <td<?= $Page->presupuesto->cellAttributes() ?>>
<span id="">
<span<?= $Page->presupuesto->viewAttributes() ?>>
<?= $Page->presupuesto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->grupo1->Visible) { // grupo1 ?>
        <td<?= $Page->grupo1->cellAttributes() ?>>
<span id="">
<span<?= $Page->grupo1->viewAttributes() ?>>
<?= $Page->grupo1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->grupo2->Visible) { // grupo2 ?>
        <td<?= $Page->grupo2->cellAttributes() ?>>
<span id="">
<span<?= $Page->grupo2->viewAttributes() ?>>
<?= $Page->grupo2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <td<?= $Page->numero->cellAttributes() ?>>
<span id="">
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
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
<?php if ($Page->linea->Visible) { // linea ?>
        <td<?= $Page->linea->cellAttributes() ?>>
<span id="">
<span<?= $Page->linea->viewAttributes() ?>>
<?= $Page->linea->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->imagen->Visible) { // imagen ?>
        <td<?= $Page->imagen->cellAttributes() ?>>
<span id="">
<span<?= $Page->imagen->viewAttributes() ?>>
<?= $Page->imagen->getViewValue() ?></span>
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
<?php if ($Page->cantidad->Visible) { // cantidad ?>
        <td<?= $Page->cantidad->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
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
<?php if ($Page->total->Visible) { // total ?>
        <td<?= $Page->total->cellAttributes() ?>>
<span id="">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
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
