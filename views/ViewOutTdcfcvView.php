<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewOutTdcfcvView = &$Page;
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
<form name="fview_out_tdcfcvview" id="fview_out_tdcfcvview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_out_tdcfcv: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fview_out_tdcfcvview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_out_tdcfcvview")
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
<input type="hidden" name="t" value="view_out_tdcfcv">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_ViewOutTdcfcvView"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_view_out_tdcfcv1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_view_out_tdcfcv2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_view_out_tdcfcv3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(4) ?>" data-bs-target="#tab_view_out_tdcfcv4" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_view_out_tdcfcv4" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>"><?= $Page->pageCaption(4) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_view_out_tdcfcv1" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_documento" data-page="1">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_documento" data-page="1">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_fecha" data-page="1">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control"<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_control" data-page="1">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente"<?= $Page->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_cliente" data-page="1">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_doc_afectado" data-page="1">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota"<?= $Page->nota->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nota" data-page="1">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <tr id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_estatus"><?= $Page->estatus->caption() ?></span></td>
        <td data-name="estatus"<?= $Page->estatus->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_estatus" data-page="1">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_view_out_tdcfcv__username" data-page="1">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_view_out_tdcfcv2" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total"<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_total" data-page="2">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <tr id="r_alicuota_iva"<?= $Page->alicuota_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></td>
        <td data-name="alicuota_iva"<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_alicuota_iva" data-page="2">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <tr id="r_iva"<?= $Page->iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_iva"><?= $Page->iva->caption() ?></span></td>
        <td data-name="iva"<?= $Page->iva->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_iva" data-page="2">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total"<?= $Page->total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_total" data-page="2">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->igtf->Visible) { // igtf ?>
    <tr id="r_igtf"<?= $Page->igtf->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_igtf"><?= $Page->igtf->caption() ?></span></td>
        <td data-name="igtf"<?= $Page->igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_igtf" data-page="2">
<span<?= $Page->igtf->viewAttributes() ?>>
<?= $Page->igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_base_igtf->Visible) { // monto_base_igtf ?>
    <tr id="r_monto_base_igtf"<?= $Page->monto_base_igtf->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_monto_base_igtf"><?= $Page->monto_base_igtf->caption() ?></span></td>
        <td data-name="monto_base_igtf"<?= $Page->monto_base_igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_base_igtf" data-page="2">
<span<?= $Page->monto_base_igtf->viewAttributes() ?>>
<?= $Page->monto_base_igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_igtf->Visible) { // monto_igtf ?>
    <tr id="r_monto_igtf"<?= $Page->monto_igtf->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_monto_igtf"><?= $Page->monto_igtf->caption() ?></span></td>
        <td data-name="monto_igtf"<?= $Page->monto_igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_igtf" data-page="2">
<span<?= $Page->monto_igtf->viewAttributes() ?>>
<?= $Page->monto_igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_moneda" data-page="2">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia"<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_tasa_dia" data-page="2">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd"<?= $Page->monto_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd"<?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_usd" data-page="2">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_descuento" data-page="2">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento2->Visible) { // descuento2 ?>
    <tr id="r_descuento2"<?= $Page->descuento2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_descuento2"><?= $Page->descuento2->caption() ?></span></td>
        <td data-name="descuento2"<?= $Page->descuento2->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_descuento2" data-page="2">
<span<?= $Page->descuento2->viewAttributes() ?>>
<?= $Page->descuento2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_view_out_tdcfcv3" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <tr id="r_lista_pedido"<?= $Page->lista_pedido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></td>
        <td data-name="lista_pedido"<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_lista_pedido" data-page="3">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <tr id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_dias_credito"><?= $Page->dias_credito->caption() ?></span></td>
        <td data-name="dias_credito"<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_dias_credito" data-page="3">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
    <tr id="r_nro_despacho"<?= $Page->nro_despacho->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_nro_despacho"><?= $Page->nro_despacho->caption() ?></span></td>
        <td data-name="nro_despacho"<?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_despacho" data-page="3">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
    <tr id="r_asesor_asignado"<?= $Page->asesor_asignado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_asesor_asignado"><?= $Page->asesor_asignado->caption() ?></span></td>
        <td data-name="asesor_asignado"<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_asesor_asignado" data-page="3">
<span<?= $Page->asesor_asignado->viewAttributes() ?>>
<?= $Page->asesor_asignado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(4) ?>" id="tab_view_out_tdcfcv4" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->pagado->Visible) { // pagado ?>
    <tr id="r_pagado"<?= $Page->pagado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_out_tdcfcv_pagado"><?= $Page->pagado->caption() ?></span></td>
        <td data-name="pagado"<?= $Page->pagado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_pagado" data-page="4">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
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
    if (in_array("view_out", explode(",", $Page->getCurrentDetailTable())) && $view_out->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("view_out", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ViewOutGrid.php" ?>
<?php } ?>
</form>
<?php if (!$Page->IsModal) { ?>
<?php if (!$Page->isExport()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
