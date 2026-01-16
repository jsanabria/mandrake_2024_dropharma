<?php

namespace PHPMaker2021\mandrake;

// Page object
$HistoriaArticuloDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fhistoria_articulodelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fhistoria_articulodelete = currentForm = new ew.Form("fhistoria_articulodelete", "delete");
    loadjs.done("fhistoria_articulodelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.historia_articulo) ew.vars.tables.historia_articulo = <?= JsonEncode(GetClientVar("tables", "historia_articulo")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fhistoria_articulodelete" id="fhistoria_articulodelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="historia_articulo">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_historia_articulo_fabricante" class="historia_articulo_fabricante"><?= $Page->fabricante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_historia_articulo_articulo" class="historia_articulo_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><span id="elh_historia_articulo_proveedor" class="historia_articulo_proveedor"><?= $Page->proveedor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
        <th class="<?= $Page->almacen->headerCellClass() ?>"><span id="elh_historia_articulo_almacen" class="historia_articulo_almacen"><?= $Page->almacen->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_historia_articulo_tipo_documento" class="historia_articulo_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_historia_articulo_nro_documento" class="historia_articulo_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_historia_articulo_fecha" class="historia_articulo_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><span id="elh_historia_articulo_lote" class="historia_articulo_lote"><?= $Page->lote->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><span id="elh_historia_articulo_fecha_vencimiento" class="historia_articulo_fecha_vencimiento"><?= $Page->fecha_vencimiento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <th class="<?= $Page->usuario->headerCellClass() ?>"><span id="elh_historia_articulo_usuario" class="historia_articulo_usuario"><?= $Page->usuario->caption() ?></span></th>
<?php } ?>
<?php if ($Page->entradas->Visible) { // entradas ?>
        <th class="<?= $Page->entradas->headerCellClass() ?>"><span id="elh_historia_articulo_entradas" class="historia_articulo_entradas"><?= $Page->entradas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->salidas->Visible) { // salidas ?>
        <th class="<?= $Page->salidas->headerCellClass() ?>"><span id="elh_historia_articulo_salidas" class="historia_articulo_salidas"><?= $Page->salidas->caption() ?></span></th>
<?php } ?>
<?php if ($Page->existencia->Visible) { // existencia ?>
        <th class="<?= $Page->existencia->headerCellClass() ?>"><span id="elh_historia_articulo_existencia" class="historia_articulo_existencia"><?= $Page->existencia->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td <?= $Page->fabricante->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_fabricante" class="historia_articulo_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td <?= $Page->articulo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_articulo" class="historia_articulo_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td <?= $Page->proveedor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_proveedor" class="historia_articulo_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
        <td <?= $Page->almacen->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_almacen" class="historia_articulo_almacen">
<span<?= $Page->almacen->viewAttributes() ?>>
<?= $Page->almacen->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_tipo_documento" class="historia_articulo_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_nro_documento" class="historia_articulo_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td <?= $Page->fecha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_fecha" class="historia_articulo_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <td <?= $Page->lote->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_lote" class="historia_articulo_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td <?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_fecha_vencimiento" class="historia_articulo_fecha_vencimiento">
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <td <?= $Page->usuario->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_usuario" class="historia_articulo_usuario">
<span<?= $Page->usuario->viewAttributes() ?>>
<?= $Page->usuario->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->entradas->Visible) { // entradas ?>
        <td <?= $Page->entradas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_entradas" class="historia_articulo_entradas">
<span<?= $Page->entradas->viewAttributes() ?>>
<?= $Page->entradas->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->salidas->Visible) { // salidas ?>
        <td <?= $Page->salidas->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_salidas" class="historia_articulo_salidas">
<span<?= $Page->salidas->viewAttributes() ?>>
<?= $Page->salidas->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->existencia->Visible) { // existencia ?>
        <td <?= $Page->existencia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_historia_articulo_existencia" class="historia_articulo_existencia">
<span<?= $Page->existencia->viewAttributes() ?>>
<?= $Page->existencia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
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
