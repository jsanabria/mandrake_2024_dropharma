<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewFacturasCobranzaView = &$Page;
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
<form name="fview_facturas_cobranzaview" id="fview_facturas_cobranzaview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_facturas_cobranza: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fview_facturas_cobranzaview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_facturas_cobranzaview")
        .setPageId("view")
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
<input type="hidden" name="t" value="view_facturas_cobranza">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->codcli->Visible) { // codcli ?>
    <tr id="r_codcli"<?= $Page->codcli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_codcli"><?= $Page->codcli->caption() ?></span></td>
        <td data-name="codcli"<?= $Page->codcli->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_codcli">
<span<?= $Page->codcli->viewAttributes() ?>>
<?= $Page->codcli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_nota->Visible) { // nro_nota ?>
    <tr id="r_nro_nota"<?= $Page->nro_nota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_nro_nota"><?= $Page->nro_nota->caption() ?></span></td>
        <td data-name="nro_nota"<?= $Page->nro_nota->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_nro_nota">
<span<?= $Page->nro_nota->viewAttributes() ?>>
<?= $Page->nro_nota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <tr id="r_ciudad"<?= $Page->ciudad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_ciudad"><?= $Page->ciudad->caption() ?></span></td>
        <td data-name="ciudad"<?= $Page->ciudad->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
    <tr id="r_base_imponible"<?= $Page->base_imponible->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_base_imponible"><?= $Page->base_imponible->caption() ?></span></td>
        <td data-name="base_imponible"<?= $Page->base_imponible->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_base_imponible">
<span<?= $Page->base_imponible->viewAttributes() ?>>
<?= $Page->base_imponible->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota_iva->Visible) { // alicuota_iva ?>
    <tr id="r_alicuota_iva"<?= $Page->alicuota_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_alicuota_iva"><?= $Page->alicuota_iva->caption() ?></span></td>
        <td data-name="alicuota_iva"<?= $Page->alicuota_iva->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_alicuota_iva">
<span<?= $Page->alicuota_iva->viewAttributes() ?>>
<?= $Page->alicuota_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iva->Visible) { // iva ?>
    <tr id="r_iva"<?= $Page->iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_iva"><?= $Page->iva->caption() ?></span></td>
        <td data-name="iva"<?= $Page->iva->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_iva">
<span<?= $Page->iva->viewAttributes() ?>>
<?= $Page->iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <tr id="r_total"<?= $Page->total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_total"><?= $Page->total->caption() ?></span></td>
        <td data-name="total"<?= $Page->total->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_total">
<span<?= $Page->total->viewAttributes() ?>>
<?= $Page->total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_pagado->Visible) { // monto_pagado ?>
    <tr id="r_monto_pagado"<?= $Page->monto_pagado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_monto_pagado"><?= $Page->monto_pagado->caption() ?></span></td>
        <td data-name="monto_pagado"<?= $Page->monto_pagado->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_monto_pagado">
<span<?= $Page->monto_pagado->viewAttributes() ?>>
<?= $Page->monto_pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pendiente->Visible) { // pendiente ?>
    <tr id="r_pendiente"<?= $Page->pendiente->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_pendiente"><?= $Page->pendiente->caption() ?></span></td>
        <td data-name="pendiente"<?= $Page->pendiente->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pendiente">
<span<?= $Page->pendiente->viewAttributes() ?>>
<?= $Page->pendiente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pendiente2->Visible) { // pendiente2 ?>
    <tr id="r_pendiente2"<?= $Page->pendiente2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_pendiente2"><?= $Page->pendiente2->caption() ?></span></td>
        <td data-name="pendiente2"<?= $Page->pendiente2->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pendiente2">
<span<?= $Page->pendiente2->viewAttributes() ?>>
<?= $Page->pendiente2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pendiente3->Visible) { // pendiente3 ?>
    <tr id="r_pendiente3"<?= $Page->pendiente3->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_pendiente3"><?= $Page->pendiente3->caption() ?></span></td>
        <td data-name="pendiente3"<?= $Page->pendiente3->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pendiente3">
<span<?= $Page->pendiente3->viewAttributes() ?>>
<?= $Page->pendiente3->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia"<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_tasa_dia">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <tr id="r_monto_usd"<?= $Page->monto_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_monto_usd"><?= $Page->monto_usd->caption() ?></span></td>
        <td data-name="monto_usd"<?= $Page->monto_usd->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_monto_usd">
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_despacho->Visible) { // fecha_despacho ?>
    <tr id="r_fecha_despacho"<?= $Page->fecha_despacho->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_fecha_despacho"><?= $Page->fecha_despacho->caption() ?></span></td>
        <td data-name="fecha_despacho"<?= $Page->fecha_despacho->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_fecha_despacho">
<span<?= $Page->fecha_despacho->viewAttributes() ?>>
<?= $Page->fecha_despacho->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
    <tr id="r_fecha_entrega"<?= $Page->fecha_entrega->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_fecha_entrega"><?= $Page->fecha_entrega->caption() ?></span></td>
        <td data-name="fecha_entrega"<?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_fecha_entrega">
<span<?= $Page->fecha_entrega->viewAttributes() ?>>
<?= $Page->fecha_entrega->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <tr id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_dias_credito"><?= $Page->dias_credito->caption() ?></span></td>
        <td data-name="dias_credito"<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_transcurridos->Visible) { // dias_transcurridos ?>
    <tr id="r_dias_transcurridos"<?= $Page->dias_transcurridos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_dias_transcurridos"><?= $Page->dias_transcurridos->caption() ?></span></td>
        <td data-name="dias_transcurridos"<?= $Page->dias_transcurridos->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_dias_transcurridos">
<span<?= $Page->dias_transcurridos->viewAttributes() ?>>
<?= $Page->dias_transcurridos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_vencidos->Visible) { // dias_vencidos ?>
    <tr id="r_dias_vencidos"<?= $Page->dias_vencidos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_dias_vencidos"><?= $Page->dias_vencidos->caption() ?></span></td>
        <td data-name="dias_vencidos"<?= $Page->dias_vencidos->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_dias_vencidos">
<span<?= $Page->dias_vencidos->viewAttributes() ?>>
<?= $Page->dias_vencidos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <tr id="r_pagado"<?= $Page->pagado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_pagado"><?= $Page->pagado->caption() ?></span></td>
        <td data-name="pagado"<?= $Page->pagado->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pagado">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->bultos->Visible) { // bultos ?>
    <tr id="r_bultos"<?= $Page->bultos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_bultos"><?= $Page->bultos->caption() ?></span></td>
        <td data-name="bultos"<?= $Page->bultos->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_bultos">
<span<?= $Page->bultos->viewAttributes() ?>>
<?= $Page->bultos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
    <tr id="r_asesor_asignado"<?= $Page->asesor_asignado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_facturas_cobranza_asesor_asignado"><?= $Page->asesor_asignado->caption() ?></span></td>
        <td data-name="asesor_asignado"<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_asesor_asignado">
<span<?= $Page->asesor_asignado->viewAttributes() ?>>
<?= $Page->asesor_asignado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("pagos", explode(",", $Page->getCurrentDetailTable())) && $pagos->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("pagos", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "PagosGrid.php" ?>
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
