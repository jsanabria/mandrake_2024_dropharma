<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fentradasdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradasdelete")
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
<form name="fentradasdelete" id="fentradasdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
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
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_entradas_nro_documento" class="entradas_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <th class="<?= $Page->doc_afectado->headerCellClass() ?>"><span id="elh_entradas_doc_afectado" class="entradas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_entradas_fecha" class="entradas_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><span id="elh_entradas_proveedor" class="entradas_proveedor"><?= $Page->proveedor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_entradas_total" class="entradas_total"><?= $Page->total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><span id="elh_entradas_documento" class="entradas_documento"><?= $Page->documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th class="<?= $Page->estatus->headerCellClass() ?>"><span id="elh_entradas_estatus" class="entradas_estatus"><?= $Page->estatus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_entradas__username" class="entradas__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
        <th class="<?= $Page->consignacion->headerCellClass() ?>"><span id="elh_entradas_consignacion" class="entradas_consignacion"><?= $Page->consignacion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
        <th class="<?= $Page->ref_iva->headerCellClass() ?>"><span id="elh_entradas_ref_iva" class="entradas_ref_iva"><?= $Page->ref_iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
        <th class="<?= $Page->ref_islr->headerCellClass() ?>"><span id="elh_entradas_ref_islr" class="entradas_ref_islr"><?= $Page->ref_islr->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
        <th class="<?= $Page->ref_municipal->headerCellClass() ?>"><span id="elh_entradas_ref_municipal" class="entradas_ref_municipal"><?= $Page->ref_municipal->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_entradas_descuento" class="entradas_descuento"><?= $Page->descuento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
        <th class="<?= $Page->archivo_pedido->headerCellClass() ?>"><span id="elh_entradas_archivo_pedido" class="entradas_archivo_pedido"><?= $Page->archivo_pedido->caption() ?></span></th>
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
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <td<?= $Page->nro_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <td<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <td<?= $Page->proveedor->cellAttributes() ?>>
<span id="">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
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
<?php if ($Page->documento->Visible) { // documento ?>
        <td<?= $Page->documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <td<?= $Page->estatus->cellAttributes() ?>>
<span id="">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
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
<?php if ($Page->consignacion->Visible) { // consignacion ?>
        <td<?= $Page->consignacion->cellAttributes() ?>>
<span id="">
<span<?= $Page->consignacion->viewAttributes() ?>>
<?= $Page->consignacion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
        <td<?= $Page->ref_iva->cellAttributes() ?>>
<span id="">
<span<?= $Page->ref_iva->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_iva->getViewValue()) && $Page->ref_iva->linkAttributes() != "") { ?>
<a<?= $Page->ref_iva->linkAttributes() ?>><?= $Page->ref_iva->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_iva->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
        <td<?= $Page->ref_islr->cellAttributes() ?>>
<span id="">
<span<?= $Page->ref_islr->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_islr->getViewValue()) && $Page->ref_islr->linkAttributes() != "") { ?>
<a<?= $Page->ref_islr->linkAttributes() ?>><?= $Page->ref_islr->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_islr->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
        <td<?= $Page->ref_municipal->cellAttributes() ?>>
<span id="">
<span<?= $Page->ref_municipal->viewAttributes() ?>>
<?= $Page->ref_municipal->getViewValue() ?></span>
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
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
        <td<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="">
<span<?= $Page->archivo_pedido->viewAttributes() ?>>
<?= GetFileViewTag($Page->archivo_pedido, $Page->archivo_pedido->getViewValue(), false) ?>
</span>
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
