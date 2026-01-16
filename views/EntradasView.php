<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="view">
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
<form name="fentradasview" id="fentradasview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fentradasview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradasview")
        .setPageId("view")

        // Multi-Page
        .setMultiPage(true)
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
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_EntradasView"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_entradas1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_entradas2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_entradas3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(4) ?>" data-bs-target="#tab_entradas4" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_entradas4" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>"><?= $Page->pageCaption(4) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_entradas1" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_entradas_tipo_documento" data-page="1">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_entradas_nro_documento" data-page="1">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_entradas_doc_afectado" data-page="1">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control"<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_entradas_nro_control" data-page="1">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <tr id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_proveedor"><?= $Page->proveedor->caption() ?></span></td>
        <td data-name="proveedor"<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_entradas_proveedor" data-page="1">
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el_entradas_documento" data-page="1">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante"<?= $Page->comprobante->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante"<?= $Page->comprobante->cellAttributes() ?>>
<span id="el_entradas_comprobante" data-page="1">
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_libro_compra->Visible) { // fecha_libro_compra ?>
    <tr id="r_fecha_libro_compra"<?= $Page->fecha_libro_compra->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_fecha_libro_compra"><?= $Page->fecha_libro_compra->caption() ?></span></td>
        <td data-name="fecha_libro_compra"<?= $Page->fecha_libro_compra->cellAttributes() ?>>
<span id="el_entradas_fecha_libro_compra" data-page="1">
<span<?= $Page->fecha_libro_compra->viewAttributes() ?>>
<?= $Page->fecha_libro_compra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<span id="el_entradas_descuento" data-page="1">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
    <tr id="r_archivo_pedido"<?= $Page->archivo_pedido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_archivo_pedido"><?= $Page->archivo_pedido->caption() ?></span></td>
        <td data-name="archivo_pedido"<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="el_entradas_archivo_pedido" data-page="1">
<span<?= $Page->archivo_pedido->viewAttributes() ?>>
<?= GetFileViewTag($Page->archivo_pedido, $Page->archivo_pedido->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_entradas2" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total"<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_entradas_monto_total" data-page="2">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <tr id="r_alicuota_iva"<?= $Page->alicuota_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></td>
        <td data-name="alicuota_iva"<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_entradas_alicuota_iva" data-page="2">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <tr id="r_iva"<?= $Page->iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_iva"><?= $Page->iva->caption() ?></span></td>
        <td data-name="iva"<?= $Page->iva->cellAttributes() ?>>
<span id="el_entradas_iva" data-page="2">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total"<?= $Page->total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el_entradas_total" data-page="2">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_entradas_moneda" data-page="2">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_pagar->Visible) { // monto_pagar ?>
    <tr id="r_monto_pagar"<?= $Page->monto_pagar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_monto_pagar"><?= $Page->monto_pagar->caption() ?></span></td>
        <td data-name="monto_pagar"<?= $Page->monto_pagar->cellAttributes() ?>>
<span id="el_entradas_monto_pagar" data-page="2">
<span<?= $Page->monto_pagar->viewAttributes() ?>>
<?= $Page->monto_pagar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia"<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_entradas_tasa_dia" data-page="2">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd"<?= $Page->monto_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd"<?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_entradas_monto_usd" data-page="2">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_entradas3" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota"<?= $Page->nota->cellAttributes() ?>>
<span id="el_entradas_nota" data-page="3">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <tr id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_estatus"><?= $Page->estatus->caption() ?></span></td>
        <td data-name="estatus"<?= $Page->estatus->cellAttributes() ?>>
<span id="el_entradas_estatus" data-page="3">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_entradas__username" data-page="3">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <tr id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_consignacion"><?= $Page->consignacion->caption() ?></span></td>
        <td data-name="consignacion"<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_entradas_consignacion" data-page="3">
<span<?= $Page->consignacion->viewAttributes() ?>>
<?= $Page->consignacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->consignacion_reportada->Visible) { // consignacion_reportada ?>
    <tr id="r_consignacion_reportada"<?= $Page->consignacion_reportada->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_consignacion_reportada"><?= $Page->consignacion_reportada->caption() ?></span></td>
        <td data-name="consignacion_reportada"<?= $Page->consignacion_reportada->cellAttributes() ?>>
<span id="el_entradas_consignacion_reportada" data-page="3">
<span<?= $Page->consignacion_reportada->viewAttributes() ?>>
<?= $Page->consignacion_reportada->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(4) ?>" id="tab_entradas4" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->aplica_retencion->Visible) { // aplica_retencion ?>
    <tr id="r_aplica_retencion"<?= $Page->aplica_retencion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_aplica_retencion"><?= $Page->aplica_retencion->caption() ?></span></td>
        <td data-name="aplica_retencion"<?= $Page->aplica_retencion->cellAttributes() ?>>
<span id="el_entradas_aplica_retencion" data-page="4">
<span<?= $Page->aplica_retencion->viewAttributes() ?>>
<?= $Page->aplica_retencion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ret_iva->Visible) { // ret_iva ?>
    <tr id="r_ret_iva"<?= $Page->ret_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ret_iva"><?= $Page->ret_iva->caption() ?></span></td>
        <td data-name="ret_iva"<?= $Page->ret_iva->cellAttributes() ?>>
<span id="el_entradas_ret_iva" data-page="4">
<span<?= $Page->ret_iva->viewAttributes() ?>>
<?= $Page->ret_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ref_iva->Visible) { // ref_iva ?>
    <tr id="r_ref_iva"<?= $Page->ref_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ref_iva"><?= $Page->ref_iva->caption() ?></span></td>
        <td data-name="ref_iva"<?= $Page->ref_iva->cellAttributes() ?>>
<span id="el_entradas_ref_iva" data-page="4">
<span<?= $Page->ref_iva->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_iva->getViewValue()) && $Page->ref_iva->linkAttributes() != "") { ?>
<a<?= $Page->ref_iva->linkAttributes() ?>><?= $Page->ref_iva->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_iva->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ret_islr->Visible) { // ret_islr ?>
    <tr id="r_ret_islr"<?= $Page->ret_islr->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ret_islr"><?= $Page->ret_islr->caption() ?></span></td>
        <td data-name="ret_islr"<?= $Page->ret_islr->cellAttributes() ?>>
<span id="el_entradas_ret_islr" data-page="4">
<span<?= $Page->ret_islr->viewAttributes() ?>>
<?= $Page->ret_islr->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ref_islr->Visible) { // ref_islr ?>
    <tr id="r_ref_islr"<?= $Page->ref_islr->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ref_islr"><?= $Page->ref_islr->caption() ?></span></td>
        <td data-name="ref_islr"<?= $Page->ref_islr->cellAttributes() ?>>
<span id="el_entradas_ref_islr" data-page="4">
<span<?= $Page->ref_islr->viewAttributes() ?>>
<?php if (!EmptyString($Page->ref_islr->getViewValue()) && $Page->ref_islr->linkAttributes() != "") { ?>
<a<?= $Page->ref_islr->linkAttributes() ?>><?= $Page->ref_islr->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->ref_islr->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ret_municipal->Visible) { // ret_municipal ?>
    <tr id="r_ret_municipal"<?= $Page->ret_municipal->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ret_municipal"><?= $Page->ret_municipal->caption() ?></span></td>
        <td data-name="ret_municipal"<?= $Page->ret_municipal->cellAttributes() ?>>
<span id="el_entradas_ret_municipal" data-page="4">
<span<?= $Page->ret_municipal->viewAttributes() ?>>
<?= $Page->ret_municipal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ref_municipal->Visible) { // ref_municipal ?>
    <tr id="r_ref_municipal"<?= $Page->ref_municipal->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_entradas_ref_municipal"><?= $Page->ref_municipal->caption() ?></span></td>
        <td data-name="ref_municipal"<?= $Page->ref_municipal->cellAttributes() ?>>
<span id="el_entradas_ref_municipal" data-page="4">
<span<?= $Page->ref_municipal->viewAttributes() ?>>
<?= $Page->ref_municipal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    </div>
</div>
</div>
<?php } ?>
<?php
    if (in_array("entradas_salidas", explode(",", $Page->getCurrentDetailTable())) && $entradas_salidas->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("entradas_salidas", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "EntradasSalidasGrid.php" ?>
<?php } ?>
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() {
    	$(".ewActionOption").hide(); 
    });
    $("#cmbContab").click(function(){
        var id = <?php echo $_REQUEST["id"]; ?>;
    	// 23-02-2022 - Junior Sanabria
    	// Agrego tipo_documento para poder contabilizar.
    	//Se le agregó ese parámetro a la clase Generar_Comprobante_Contable.php
    	// en la carpeta include
    	var tipo_documento = "<?php echo CurrentPage()->tipo_documento->CurrentValue; ?>";
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "include/Generar_Comprobante_Contable.php",
    		  type: "GET",
    		  data : {id: id, tipo_documento: tipo_documento, regla: 2, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = '';
    			if(data == "0")
    				rs = '<div class="alert alert-danger" role="alert">No se Gener&oacute; Comprobante Contable. Periodo contable cerrado o no hay reglas de contabilizaci&oacute;n definidas.</div>';
    			else 
    				rs = '<div class="alert alert-success" role="alert">Se Gener&oacute; Comprobante Contable # ' + data + ' Exitosamente.</div>';
    			$("#result").html(rs);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    });
});
</script>
<?php } ?>
