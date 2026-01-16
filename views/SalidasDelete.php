<?php

namespace PHPMaker2024\mandrake;

// Page object
$SalidasDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fsalidasdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsalidasdelete")
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
<form name="fsalidasdelete" id="fsalidasdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><span id="elh_salidas_tipo_documento" class="salidas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><span id="elh_salidas_nro_documento" class="salidas_nro_documento"><?= $Page->nro_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_salidas_fecha" class="salidas_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><span id="elh_salidas_cliente" class="salidas_cliente"><?= $Page->cliente->caption() ?></span></th>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><span id="elh_salidas_documento" class="salidas_documento"><?= $Page->documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <th class="<?= $Page->doc_afectado->headerCellClass() ?>"><span id="elh_salidas_doc_afectado" class="salidas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <th class="<?= $Page->monto_total->headerCellClass() ?>"><span id="elh_salidas_monto_total" class="salidas_monto_total"><?= $Page->monto_total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <th class="<?= $Page->alicuota_iva->headerCellClass() ?>"><span id="elh_salidas_alicuota_iva" class="salidas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <th class="<?= $Page->iva->headerCellClass() ?>"><span id="elh_salidas_iva" class="salidas_iva"><?= $Page->iva->caption() ?></span></th>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
        <th class="<?= $Page->total->headerCellClass() ?>"><span id="elh_salidas_total" class="salidas_total"><?= $Page->total->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <th class="<?= $Page->lista_pedido->headerCellClass() ?>"><span id="elh_salidas_lista_pedido" class="salidas_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th class="<?= $Page->nota->headerCellClass() ?>"><span id="elh_salidas_nota" class="salidas_nota"><?= $Page->nota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_salidas__username" class="salidas__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
        <th class="<?= $Page->estatus->headerCellClass() ?>"><span id="elh_salidas_estatus" class="salidas_estatus"><?= $Page->estatus->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <th class="<?= $Page->asesor->headerCellClass() ?>"><span id="elh_salidas_asesor" class="salidas_asesor"><?= $Page->asesor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
        <th class="<?= $Page->dias_credito->headerCellClass() ?>"><span id="elh_salidas_dias_credito" class="salidas_dias_credito"><?= $Page->dias_credito->caption() ?></span></th>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
        <th class="<?= $Page->consignacion->headerCellClass() ?>"><span id="elh_salidas_consignacion" class="salidas_consignacion"><?= $Page->consignacion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <th class="<?= $Page->unidades->headerCellClass() ?>"><span id="elh_salidas_unidades" class="salidas_unidades"><?= $Page->unidades->caption() ?></span></th>
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
        <th class="<?= $Page->factura->headerCellClass() ?>"><span id="elh_salidas_factura" class="salidas_factura"><?= $Page->factura->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_salidas_nombre" class="salidas_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><span id="elh_salidas_comprobante" class="salidas_comprobante"><?= $Page->comprobante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <th class="<?= $Page->nro_despacho->headerCellClass() ?>"><span id="elh_salidas_nro_despacho" class="salidas_nro_despacho"><?= $Page->nro_despacho->caption() ?></span></th>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <th class="<?= $Page->asesor_asignado->headerCellClass() ?>"><span id="elh_salidas_asesor_asignado" class="salidas_asesor_asignado"><?= $Page->asesor_asignado->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_documento_padre->Visible) { // id_documento_padre ?>
        <th class="<?= $Page->id_documento_padre->headerCellClass() ?>"><span id="elh_salidas_id_documento_padre" class="salidas_id_documento_padre"><?= $Page->id_documento_padre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
        <th class="<?= $Page->archivo_pedido->headerCellClass() ?>"><span id="elh_salidas_archivo_pedido" class="salidas_archivo_pedido"><?= $Page->archivo_pedido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->checker->Visible) { // checker ?>
        <th class="<?= $Page->checker->headerCellClass() ?>"><span id="elh_salidas_checker" class="salidas_checker"><?= $Page->checker->caption() ?></span></th>
<?php } ?>
<?php if ($Page->checker_date->Visible) { // checker_date ?>
        <th class="<?= $Page->checker_date->headerCellClass() ?>"><span id="elh_salidas_checker_date" class="salidas_checker_date"><?= $Page->checker_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->packer->Visible) { // packer ?>
        <th class="<?= $Page->packer->headerCellClass() ?>"><span id="elh_salidas_packer" class="salidas_packer"><?= $Page->packer->caption() ?></span></th>
<?php } ?>
<?php if ($Page->packer_date->Visible) { // packer_date ?>
        <th class="<?= $Page->packer_date->headerCellClass() ?>"><span id="elh_salidas_packer_date" class="salidas_packer_date"><?= $Page->packer_date->caption() ?></span></th>
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <td<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
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
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td<?= $Page->fecha->cellAttributes() ?>>
<span id="">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
        <td<?= $Page->cliente->cellAttributes() ?>>
<span id="">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
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
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
        <td<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
        <td<?= $Page->monto_total->cellAttributes() ?>>
<span id="">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?php if (!EmptyString($Page->monto_total->getViewValue()) && $Page->monto_total->linkAttributes() != "") { ?>
<a<?= $Page->monto_total->linkAttributes() ?>><?= $Page->monto_total->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->monto_total->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
        <td<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
        <td<?= $Page->iva->cellAttributes() ?>>
<span id="">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
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
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <td<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <td<?= $Page->nota->cellAttributes() ?>>
<span id="">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
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
<?php if ($Page->estatus->Visible) { // estatus ?>
        <td<?= $Page->estatus->cellAttributes() ?>>
<span id="">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
        <td<?= $Page->asesor->cellAttributes() ?>>
<span id="">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
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
<?php if ($Page->consignacion->Visible) { // consignacion ?>
        <td<?= $Page->consignacion->cellAttributes() ?>>
<span id="">
<span<?= $Page->consignacion->viewAttributes() ?>>
<?= $Page->consignacion->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->unidades->Visible) { // unidades ?>
        <td<?= $Page->unidades->cellAttributes() ?>>
<span id="">
<span<?= $Page->unidades->viewAttributes() ?>>
<?= $Page->unidades->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
        <td<?= $Page->factura->cellAttributes() ?>>
<span id="">
<span<?= $Page->factura->viewAttributes() ?>>
<?= $Page->factura->getViewValue() ?></span>
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
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <td<?= $Page->comprobante->cellAttributes() ?>>
<span id="">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
        <td<?= $Page->nro_despacho->cellAttributes() ?>>
<span id="">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
        <td<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="">
<span<?= $Page->asesor_asignado->viewAttributes() ?>>
<?= $Page->asesor_asignado->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_documento_padre->Visible) { // id_documento_padre ?>
        <td<?= $Page->id_documento_padre->cellAttributes() ?>>
<span id="">
<span<?= $Page->id_documento_padre->viewAttributes() ?>>
<?= $Page->id_documento_padre->getViewValue() ?></span>
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
<?php if ($Page->checker->Visible) { // checker ?>
        <td<?= $Page->checker->cellAttributes() ?>>
<span id="">
<span<?= $Page->checker->viewAttributes() ?>>
<?= $Page->checker->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->checker_date->Visible) { // checker_date ?>
        <td<?= $Page->checker_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->checker_date->viewAttributes() ?>>
<?= $Page->checker_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->packer->Visible) { // packer ?>
        <td<?= $Page->packer->cellAttributes() ?>>
<span id="">
<span<?= $Page->packer->viewAttributes() ?>>
<?= $Page->packer->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->packer_date->Visible) { // packer_date ?>
        <td<?= $Page->packer_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->packer_date->viewAttributes() ?>>
<?= $Page->packer_date->getViewValue() ?></span>
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
