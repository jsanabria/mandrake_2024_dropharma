<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewCuentasPorCobrarView = &$Page;
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
<form name="fview_cuentas_por_cobrarview" id="fview_cuentas_por_cobrarview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_cobrar: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fview_cuentas_por_cobrarview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_cuentas_por_cobrarview")
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
<input type="hidden" name="t" value="view_cuentas_por_cobrar">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->cliente->Visible) { // cliente ?>
    <tr id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_cliente"><?= $Page->cliente->caption() ?></span></td>
        <td data-name="cliente"<?= $Page->cliente->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente_rif->Visible) { // cliente_rif ?>
    <tr id="r_cliente_rif"<?= $Page->cliente_rif->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_cliente_rif"><?= $Page->cliente_rif->caption() ?></span></td>
        <td data-name="cliente_rif"<?= $Page->cliente_rif->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_cliente_rif">
<span<?= $Page->cliente_rif->viewAttributes() ?>>
<?= $Page->cliente_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cliente_nombre->Visible) { // cliente_nombre ?>
    <tr id="r_cliente_nombre"<?= $Page->cliente_nombre->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_cliente_nombre"><?= $Page->cliente_nombre->caption() ?></span></td>
        <td data-name="cliente_nombre"<?= $Page->cliente_nombre->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_cliente_nombre">
<span<?= $Page->cliente_nombre->viewAttributes() ?>>
<?= $Page->cliente_nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_documento_fiscal->Visible) { // tipo_documento_fiscal ?>
    <tr id="r_tipo_documento_fiscal"<?= $Page->tipo_documento_fiscal->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_tipo_documento_fiscal"><?= $Page->tipo_documento_fiscal->caption() ?></span></td>
        <td data-name="tipo_documento_fiscal"<?= $Page->tipo_documento_fiscal->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_tipo_documento_fiscal">
<span<?= $Page->tipo_documento_fiscal->viewAttributes() ?>>
<?= $Page->tipo_documento_fiscal->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control"<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_nro_control">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_documento->Visible) { // fecha_documento ?>
    <tr id="r_fecha_documento"<?= $Page->fecha_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_fecha_documento"><?= $Page->fecha_documento->caption() ?></span></td>
        <td data-name="fecha_documento"<?= $Page->fecha_documento->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_fecha_documento">
<span<?= $Page->fecha_documento->viewAttributes() ?>>
<?= $Page->fecha_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
    <tr id="r_fecha_vencimiento"<?= $Page->fecha_vencimiento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_fecha_vencimiento"><?= $Page->fecha_vencimiento->caption() ?></span></td>
        <td data-name="fecha_vencimiento"<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_fecha_vencimiento">
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia"<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_tasa_dia">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <tr id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_dias_credito"><?= $Page->dias_credito->caption() ?></span></td>
        <td data-name="dias_credito"<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<?= $Page->dias_credito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
    <tr id="r_entregado"<?= $Page->entregado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_entregado"><?= $Page->entregado->caption() ?></span></td>
        <td data-name="entregado"<?= $Page->entregado->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_entregado">
<span<?= $Page->entregado->viewAttributes() ?>>
<?= $Page->entregado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <tr id="r_pagado"<?= $Page->pagado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_pagado"><?= $Page->pagado->caption() ?></span></td>
        <td data-name="pagado"<?= $Page->pagado->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_pagado">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afe->Visible) { // doc_afe ?>
    <tr id="r_doc_afe"<?= $Page->doc_afe->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_doc_afe"><?= $Page->doc_afe->caption() ?></span></td>
        <td data-name="doc_afe"<?= $Page->doc_afe->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_doc_afe">
<span<?= $Page->doc_afe->viewAttributes() ?>>
<?= $Page->doc_afe->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->igtf->Visible) { // igtf ?>
    <tr id="r_igtf"<?= $Page->igtf->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_igtf"><?= $Page->igtf->caption() ?></span></td>
        <td data-name="igtf"<?= $Page->igtf->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_igtf">
<span<?= $Page->igtf->viewAttributes() ?>>
<?= $Page->igtf->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_igtf_bs->Visible) { // monto_igtf_bs ?>
    <tr id="r_monto_igtf_bs"<?= $Page->monto_igtf_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_monto_igtf_bs"><?= $Page->monto_igtf_bs->caption() ?></span></td>
        <td data-name="monto_igtf_bs"<?= $Page->monto_igtf_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_monto_igtf_bs">
<span<?= $Page->monto_igtf_bs->viewAttributes() ?>>
<?= $Page->monto_igtf_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->signo_documento->Visible) { // signo_documento ?>
    <tr id="r_signo_documento"<?= $Page->signo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_signo_documento"><?= $Page->signo_documento->caption() ?></span></td>
        <td data-name="signo_documento"<?= $Page->signo_documento->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_signo_documento">
<span<?= $Page->signo_documento->viewAttributes() ?>>
<?= $Page->signo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_documento_moneda->Visible) { // monto_documento_moneda ?>
    <tr id="r_monto_documento_moneda"<?= $Page->monto_documento_moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_monto_documento_moneda"><?= $Page->monto_documento_moneda->caption() ?></span></td>
        <td data-name="monto_documento_moneda"<?= $Page->monto_documento_moneda->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_monto_documento_moneda">
<span<?= $Page->monto_documento_moneda->viewAttributes() ?>>
<?= $Page->monto_documento_moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
    <tr id="r_monto_documento_bs"<?= $Page->monto_documento_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_monto_documento_bs"><?= $Page->monto_documento_bs->caption() ?></span></td>
        <td data-name="monto_documento_bs"<?= $Page->monto_documento_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_monto_documento_bs">
<span<?= $Page->monto_documento_bs->viewAttributes() ?>>
<?= $Page->monto_documento_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_documento_usd->Visible) { // monto_documento_usd ?>
    <tr id="r_monto_documento_usd"<?= $Page->monto_documento_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_monto_documento_usd"><?= $Page->monto_documento_usd->caption() ?></span></td>
        <td data-name="monto_documento_usd"<?= $Page->monto_documento_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_monto_documento_usd">
<span<?= $Page->monto_documento_usd->viewAttributes() ?>>
<?= $Page->monto_documento_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_aplicado_bs->Visible) { // monto_aplicado_bs ?>
    <tr id="r_monto_aplicado_bs"<?= $Page->monto_aplicado_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_monto_aplicado_bs"><?= $Page->monto_aplicado_bs->caption() ?></span></td>
        <td data-name="monto_aplicado_bs"<?= $Page->monto_aplicado_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_monto_aplicado_bs">
<span<?= $Page->monto_aplicado_bs->viewAttributes() ?>>
<?= $Page->monto_aplicado_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_aplicado_usd->Visible) { // monto_aplicado_usd ?>
    <tr id="r_monto_aplicado_usd"<?= $Page->monto_aplicado_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_monto_aplicado_usd"><?= $Page->monto_aplicado_usd->caption() ?></span></td>
        <td data-name="monto_aplicado_usd"<?= $Page->monto_aplicado_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_monto_aplicado_usd">
<span<?= $Page->monto_aplicado_usd->viewAttributes() ?>>
<?= $Page->monto_aplicado_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_cobrado_bs->Visible) { // total_cobrado_bs ?>
    <tr id="r_total_cobrado_bs"<?= $Page->total_cobrado_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_total_cobrado_bs"><?= $Page->total_cobrado_bs->caption() ?></span></td>
        <td data-name="total_cobrado_bs"<?= $Page->total_cobrado_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_total_cobrado_bs">
<span<?= $Page->total_cobrado_bs->viewAttributes() ?>>
<?= $Page->total_cobrado_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_cobrado_usd->Visible) { // total_cobrado_usd ?>
    <tr id="r_total_cobrado_usd"<?= $Page->total_cobrado_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_total_cobrado_usd"><?= $Page->total_cobrado_usd->caption() ?></span></td>
        <td data-name="total_cobrado_usd"<?= $Page->total_cobrado_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_total_cobrado_usd">
<span<?= $Page->total_cobrado_usd->viewAttributes() ?>>
<?= $Page->total_cobrado_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_cobros->Visible) { // cantidad_cobros ?>
    <tr id="r_cantidad_cobros"<?= $Page->cantidad_cobros->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_cantidad_cobros"><?= $Page->cantidad_cobros->caption() ?></span></td>
        <td data-name="cantidad_cobros"<?= $Page->cantidad_cobros->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_cantidad_cobros">
<span<?= $Page->cantidad_cobros->viewAttributes() ?>>
<?= $Page->cantidad_cobros->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_ultimo_cobro->Visible) { // fecha_ultimo_cobro ?>
    <tr id="r_fecha_ultimo_cobro"<?= $Page->fecha_ultimo_cobro->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_fecha_ultimo_cobro"><?= $Page->fecha_ultimo_cobro->caption() ?></span></td>
        <td data-name="fecha_ultimo_cobro"<?= $Page->fecha_ultimo_cobro->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_fecha_ultimo_cobro">
<span<?= $Page->fecha_ultimo_cobro->viewAttributes() ?>>
<?= $Page->fecha_ultimo_cobro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
    <tr id="r_saldo_bs"<?= $Page->saldo_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_saldo_bs"><?= $Page->saldo_bs->caption() ?></span></td>
        <td data-name="saldo_bs"<?= $Page->saldo_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_saldo_bs">
<span<?= $Page->saldo_bs->viewAttributes() ?>>
<?= $Page->saldo_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo_usd->Visible) { // saldo_usd ?>
    <tr id="r_saldo_usd"<?= $Page->saldo_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_saldo_usd"><?= $Page->saldo_usd->caption() ?></span></td>
        <td data-name="saldo_usd"<?= $Page->saldo_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_saldo_usd">
<span<?= $Page->saldo_usd->viewAttributes() ?>>
<?= $Page->saldo_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estado_cuenta->Visible) { // estado_cuenta ?>
    <tr id="r_estado_cuenta"<?= $Page->estado_cuenta->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_estado_cuenta"><?= $Page->estado_cuenta->caption() ?></span></td>
        <td data-name="estado_cuenta"<?= $Page->estado_cuenta->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_estado_cuenta">
<span<?= $Page->estado_cuenta->viewAttributes() ?>>
<?= $Page->estado_cuenta->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_vencido->Visible) { // dias_vencido ?>
    <tr id="r_dias_vencido"<?= $Page->dias_vencido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_dias_vencido"><?= $Page->dias_vencido->caption() ?></span></td>
        <td data-name="dias_vencido"<?= $Page->dias_vencido->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_dias_vencido">
<span<?= $Page->dias_vencido->viewAttributes() ?>>
<?= $Page->dias_vencido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->antiguedad->Visible) { // antiguedad ?>
    <tr id="r_antiguedad"<?= $Page->antiguedad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_cobrar_antiguedad"><?= $Page->antiguedad->caption() ?></span></td>
        <td data-name="antiguedad"<?= $Page->antiguedad->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_antiguedad">
<span<?= $Page->antiguedad->viewAttributes() ?>>
<?= $Page->antiguedad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
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
