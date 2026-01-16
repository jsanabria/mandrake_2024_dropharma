<?php

namespace PHPMaker2024\mandrake;

// Page object
$SalidasView = &$Page;
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
<form name="fsalidasview" id="fsalidasview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fsalidasview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fsalidasview")
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
    // Client script
    // Write your table-specific client script here, no need to add script tags.
    window.addImages = function (id) {
    	$.ajax({
    	  url : "include/fotos_subir_ne.php",
    	  type: "GET",
    	  data : {id: id},
    	  beforeSend: function(){
    	  }
    	})
    	.done(function(data) {
    		// $("#r_fotos").html(data);
    		$("#xFoto").html(data);
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	});
    };
});
</script>
<?php } ?>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="salidas">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_SalidasView"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_salidas1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_salidas2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_salidas3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(4) ?>" data-bs-target="#tab_salidas4" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas4" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(4)) ?>"><?= $Page->pageCaption(4) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(5) ?>" data-bs-target="#tab_salidas5" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_salidas5" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(5)) ?>"><?= $Page->pageCaption(5) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_salidas1" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento" data-page="1">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_salidas_nro_documento" data-page="1">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control"<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_salidas_nro_control" data-page="1">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_salidas_fecha" data-page="1">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente"<?= $Page->cliente->cellAttributes() ?>>
<span id="el_salidas_cliente" data-page="1">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el_salidas_documento" data-page="1">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_salidas_doc_afectado" data-page="1">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <tr id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nota"><?= $Page->nota->caption() ?></span></td>
        <td data-name="nota"<?= $Page->nota->cellAttributes() ?>>
<span id="el_salidas_nota" data-page="1">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <tr id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_estatus"><?= $Page->estatus->caption() ?></span></td>
        <td data-name="estatus"<?= $Page->estatus->cellAttributes() ?>>
<span id="el_salidas_estatus" data-page="1">
<span<?= $Page->estatus->viewAttributes() ?>>
<?= $Page->estatus->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
    <tr id="r_factura"<?= $Page->factura->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_factura"><?= $Page->factura->caption() ?></span></td>
        <td data-name="factura"<?= $Page->factura->cellAttributes() ?>>
<span id="el_salidas_factura" data-page="1">
<span<?= $Page->factura->viewAttributes() ?>>
<?= $Page->factura->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <tr id="r_comprobante"<?= $Page->comprobante->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_comprobante"><?= $Page->comprobante->caption() ?></span></td>
        <td data-name="comprobante"<?= $Page->comprobante->cellAttributes() ?>>
<span id="el_salidas_comprobante" data-page="1">
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
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
    <tr id="r_archivo_pedido"<?= $Page->archivo_pedido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_archivo_pedido"><?= $Page->archivo_pedido->caption() ?></span></td>
        <td data-name="archivo_pedido"<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="el_salidas_archivo_pedido" data-page="1">
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
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_salidas2" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_salidas_moneda" data-page="2">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total"<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_salidas_monto_total" data-page="2">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?php if (!EmptyString($Page->monto_total->getViewValue()) && $Page->monto_total->linkAttributes() != "") { ?>
<a<?= $Page->monto_total->linkAttributes() ?>><?= $Page->monto_total->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->monto_total->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <tr id="r_alicuota_iva"<?= $Page->alicuota_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></td>
        <td data-name="alicuota_iva"<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_salidas_alicuota_iva" data-page="2">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <tr id="r_iva"<?= $Page->iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_iva"><?= $Page->iva->caption() ?></span></td>
        <td data-name="iva"<?= $Page->iva->cellAttributes() ?>>
<span id="el_salidas_iva" data-page="2">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total"<?= $Page->total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el_salidas_total" data-page="2">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia"<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_salidas_tasa_dia" data-page="2">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd"<?= $Page->monto_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd"<?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_salidas_monto_usd" data-page="2">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<span id="el_salidas_descuento" data-page="2">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_salidas3" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <tr id="r_lista_pedido"<?= $Page->lista_pedido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></td>
        <td data-name="lista_pedido"<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_salidas_lista_pedido" data-page="3">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_salidas__username" data-page="3">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asesor->Visible) { // asesor ?>
    <tr id="r_asesor"<?= $Page->asesor->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_asesor"><?= $Page->asesor->caption() ?></span></td>
        <td data-name="asesor"<?= $Page->asesor->cellAttributes() ?>>
<span id="el_salidas_asesor" data-page="3">
<span<?= $Page->asesor->viewAttributes() ?>>
<?= $Page->asesor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <tr id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_dias_credito"><?= $Page->dias_credito->caption() ?></span></td>
        <td data-name="dias_credito"<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_salidas_dias_credito" data-page="3">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_despacho->Visible) { // nro_despacho ?>
    <tr id="r_nro_despacho"<?= $Page->nro_despacho->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_nro_despacho"><?= $Page->nro_despacho->caption() ?></span></td>
        <td data-name="nro_despacho"<?= $Page->nro_despacho->cellAttributes() ?>>
<span id="el_salidas_nro_despacho" data-page="3">
<span<?= $Page->nro_despacho->viewAttributes() ?>>
<?= $Page->nro_despacho->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
    <tr id="r_asesor_asignado"<?= $Page->asesor_asignado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_asesor_asignado"><?= $Page->asesor_asignado->caption() ?></span></td>
        <td data-name="asesor_asignado"<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el_salidas_asesor_asignado" data-page="3">
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
        <div class="<?= $Page->MultiPages->tabPaneClasses(4) ?>" id="tab_salidas4" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->entregado->Visible) { // entregado ?>
    <tr id="r_entregado"<?= $Page->entregado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_entregado"><?= $Page->entregado->caption() ?></span></td>
        <td data-name="entregado"<?= $Page->entregado->cellAttributes() ?>>
<span id="el_salidas_entregado" data-page="4">
<span<?= $Page->entregado->viewAttributes() ?>>
<?= $Page->entregado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
    <tr id="r_fecha_entrega"<?= $Page->fecha_entrega->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fecha_entrega"><?= $Page->fecha_entrega->caption() ?></span></td>
        <td data-name="fecha_entrega"<?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el_salidas_fecha_entrega" data-page="4">
<span<?= $Page->fecha_entrega->viewAttributes() ?>>
<?= $Page->fecha_entrega->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <tr id="r_pagado"<?= $Page->pagado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_pagado"><?= $Page->pagado->caption() ?></span></td>
        <td data-name="pagado"<?= $Page->pagado->cellAttributes() ?>>
<span id="el_salidas_pagado" data-page="4">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bultos->Visible) { // bultos ?>
    <tr id="r_bultos"<?= $Page->bultos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_bultos"><?= $Page->bultos->caption() ?></span></td>
        <td data-name="bultos"<?= $Page->bultos->cellAttributes() ?>>
<span id="el_salidas_bultos" data-page="4">
<span<?= $Page->bultos->viewAttributes() ?>>
<?= $Page->bultos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_bultos->Visible) { // fecha_bultos ?>
    <tr id="r_fecha_bultos"<?= $Page->fecha_bultos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fecha_bultos"><?= $Page->fecha_bultos->caption() ?></span></td>
        <td data-name="fecha_bultos"<?= $Page->fecha_bultos->cellAttributes() ?>>
<span id="el_salidas_fecha_bultos" data-page="4">
<span<?= $Page->fecha_bultos->viewAttributes() ?>>
<?= $Page->fecha_bultos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_bultos->Visible) { // user_bultos ?>
    <tr id="r_user_bultos"<?= $Page->user_bultos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_user_bultos"><?= $Page->user_bultos->caption() ?></span></td>
        <td data-name="user_bultos"<?= $Page->user_bultos->cellAttributes() ?>>
<span id="el_salidas_user_bultos" data-page="4">
<span<?= $Page->user_bultos->viewAttributes() ?>>
<?= $Page->user_bultos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_despacho->Visible) { // fecha_despacho ?>
    <tr id="r_fecha_despacho"<?= $Page->fecha_despacho->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fecha_despacho"><?= $Page->fecha_despacho->caption() ?></span></td>
        <td data-name="fecha_despacho"<?= $Page->fecha_despacho->cellAttributes() ?>>
<span id="el_salidas_fecha_despacho" data-page="4">
<span<?= $Page->fecha_despacho->viewAttributes() ?>>
<?= $Page->fecha_despacho->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_despacho->Visible) { // user_despacho ?>
    <tr id="r_user_despacho"<?= $Page->user_despacho->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_user_despacho"><?= $Page->user_despacho->caption() ?></span></td>
        <td data-name="user_despacho"<?= $Page->user_despacho->cellAttributes() ?>>
<span id="el_salidas_user_despacho" data-page="4">
<span<?= $Page->user_despacho->viewAttributes() ?>>
<?= $Page->user_despacho->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <tr id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_consignacion"><?= $Page->consignacion->caption() ?></span></td>
        <td data-name="consignacion"<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_salidas_consignacion" data-page="4">
<span<?= $Page->consignacion->viewAttributes() ?>>
<?= $Page->consignacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->checker->Visible) { // checker ?>
    <tr id="r_checker"<?= $Page->checker->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_checker"><?= $Page->checker->caption() ?></span></td>
        <td data-name="checker"<?= $Page->checker->cellAttributes() ?>>
<span id="el_salidas_checker" data-page="4">
<span<?= $Page->checker->viewAttributes() ?>>
<?= $Page->checker->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->checker_date->Visible) { // checker_date ?>
    <tr id="r_checker_date"<?= $Page->checker_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_checker_date"><?= $Page->checker_date->caption() ?></span></td>
        <td data-name="checker_date"<?= $Page->checker_date->cellAttributes() ?>>
<span id="el_salidas_checker_date" data-page="4">
<span<?= $Page->checker_date->viewAttributes() ?>>
<?= $Page->checker_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->packer->Visible) { // packer ?>
    <tr id="r_packer"<?= $Page->packer->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_packer"><?= $Page->packer->caption() ?></span></td>
        <td data-name="packer"<?= $Page->packer->cellAttributes() ?>>
<span id="el_salidas_packer" data-page="4">
<span<?= $Page->packer->viewAttributes() ?>>
<?= $Page->packer->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->packer_date->Visible) { // packer_date ?>
    <tr id="r_packer_date"<?= $Page->packer_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_packer_date"><?= $Page->packer_date->caption() ?></span></td>
        <td data-name="packer_date"<?= $Page->packer_date->cellAttributes() ?>>
<span id="el_salidas_packer_date" data-page="4">
<span<?= $Page->packer_date->viewAttributes() ?>>
<?= $Page->packer_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(5) ?>" id="tab_salidas5" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->fotos->Visible) { // fotos ?>
    <tr id="r_fotos"<?= $Page->fotos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_salidas_fotos"><?= $Page->fotos->caption() ?></span></td>
        <td data-name="fotos"<?= $Page->fotos->cellAttributes() ?>>
<span id="el_salidas_fotos" data-page="5">
<span>
<?= GetFileViewTag($Page->fotos, $Page->fotos->getViewValue(), false) ?>
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
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
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
    	$("#elh_salidas_fotos").html('<a onclick="js:addImages(<?php echo CurrentPage()->id->CurrentValue; ?>);">Click aqu&iacute; + Fotos</a>');
    });
    $("#codbar").change(function() {
    	var id = <?php echo CurrentPage()->id->CurrentValue; ?>;
    	var tipo_documento = "<?php echo CurrentPage()->tipo_documento->CurrentValue; ?>";
    	var codbar = $("#codbar").val();
    	$.ajax({
    	  url : "include/check_nota_entrega.php",
    	  type: "GET",
    	  data : {id: id, tipo_documento: tipo_documento, codbar: codbar},
    	  beforeSend: function(){
    	    //$("#result").html("Espere. . . ");
    	  }
    	})
    	.done(function(data) {
    		var resp = data;
    		if(resp == "N") {
    			alert("ARTICULO NO EXISTE EN LA NOTA DE ENTREGA");
    		}
    		else {
    			location.reload();
    		}
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    		//alert( "complete" );
    		//$("#result").html("Espere. . . ");
    	});
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
    		  data : {id: id, tipo_documento: tipo_documento, regla: 4, username: username},
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
    $("#btnCerrar").click(function() {
    });
    $("#btnAceptar").click(function() {
    	var xpass = $("#xpassword").val();
    	var xtipo = $("#xtipo").val();
    	$.ajax({
    	  url : "include/Validar_Checker_Packer.php",
    	  type: "GET",
    	  data : {password: xpass, tipo: xtipo},
    	  beforeSend: function(){
    	  }
    	})
    	.done(function(MyResult) {
    		if(MyResult == "N") {
    			alert("!!! NO AUTORIZADO !!!");
    		}
    		$('#ventanaModal').modal('hide');
    		$("#xpassword").val("");
    		if(MyResult == "S") {
    			location.reload();
    		}
    	})
    	.fail(function(data) {
    		alert( "error" + data );
    	})
    	.always(function(data) {
    	});
    });
    $("#btnCerrarChecker").click(function() {
    	var id = <?php echo $_REQUEST["id"]; ?>;
    	if(confirm("Está seguro de cerrar el chequeo de las unidades?")) {
    		$.ajax({
    		  url : "include/Cerrar_Checker_Packer.php",
    		  type: "GET",
    		  data : {id: id, tipo: 'CHECKER'},
    		  beforeSend: function(){
    		  }
    		})
    		.done(function(MyResult) {
    			location.reload();
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    		});
    	}
    });
    $("#btnCerrarPacker").click(function() {
    	var id = <?php echo $_REQUEST["id"]; ?>;
    	if(confirm("Está seguro de cerrar el conteo de las unidades?")) {
    		$.ajax({
    		  url : "include/Cerrar_Checker_Packer.php",
    		  type: "GET",
    		  data : {id: id, tipo: 'PACKER'},
    		  beforeSend: function(){
    		  }
    		})
    		.done(function(MyResult) {
    			location.reload();
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    		});
    	}
    });
    $(document).ready(function() {
    	var input = document.getElementById("xpassword");
    	input.addEventListener("keypress", function(event) {
    	  if (event.key === "Enter") {
    	    event.preventDefault();
    	    document.getElementById("btnAceptar").click();
    	  }
    	});
    });
});
</script>
<?php } ?>
