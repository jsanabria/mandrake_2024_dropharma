<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewCuentasPorPagarView = &$Page;
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
<form name="fview_cuentas_por_pagarview" id="fview_cuentas_por_pagarview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fview_cuentas_por_pagarview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_cuentas_por_pagarview")
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
<input type="hidden" name="t" value="view_cuentas_por_pagar">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->proveedor_rif->Visible) { // proveedor_rif ?>
    <tr id="r_proveedor_rif"<?= $Page->proveedor_rif->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_proveedor_rif"><?= $Page->proveedor_rif->caption() ?></span></td>
        <td data-name="proveedor_rif"<?= $Page->proveedor_rif->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_proveedor_rif">
<span<?= $Page->proveedor_rif->viewAttributes() ?>>
<?= $Page->proveedor_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->proveedor_nombre->Visible) { // proveedor_nombre ?>
    <tr id="r_proveedor_nombre"<?= $Page->proveedor_nombre->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_proveedor_nombre"><?= $Page->proveedor_nombre->caption() ?></span></td>
        <td data-name="proveedor_nombre"<?= $Page->proveedor_nombre->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_proveedor_nombre">
<span<?= $Page->proveedor_nombre->viewAttributes() ?>>
<?= $Page->proveedor_nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <tr id="r_documento"<?= $Page->documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_documento"><?= $Page->documento->caption() ?></span></td>
        <td data-name="documento"<?= $Page->documento->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_documento">
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_control->Visible) { // nro_control ?>
    <tr id="r_nro_control"<?= $Page->nro_control->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_nro_control"><?= $Page->nro_control->caption() ?></span></td>
        <td data-name="nro_control"<?= $Page->nro_control->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_nro_control">
<span<?= $Page->nro_control->viewAttributes() ?>>
<?= $Page->nro_control->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
    <tr id="r_fecha_ultimo_pago"<?= $Page->fecha_ultimo_pago->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_fecha_ultimo_pago"><?= $Page->fecha_ultimo_pago->caption() ?></span></td>
        <td data-name="fecha_ultimo_pago"<?= $Page->fecha_ultimo_pago->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_fecha_ultimo_pago">
<span<?= $Page->fecha_ultimo_pago->viewAttributes() ?>>
<?= $Page->fecha_ultimo_pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
    <tr id="r_fecha_registro"<?= $Page->fecha_registro->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></td>
        <td data-name="fecha_registro"<?= $Page->fecha_registro->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_fecha_registro">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->doc_afectado->Visible) { // doc_afectado ?>
    <tr id="r_doc_afectado"<?= $Page->doc_afectado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_doc_afectado"><?= $Page->doc_afectado->caption() ?></span></td>
        <td data-name="doc_afectado"<?= $Page->doc_afectado->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_doc_afectado">
<span<?= $Page->doc_afectado->viewAttributes() ?>>
<?= $Page->doc_afectado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->anulado->Visible) { // anulado ?>
    <tr id="r_anulado"<?= $Page->anulado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_anulado"><?= $Page->anulado->caption() ?></span></td>
        <td data-name="anulado"<?= $Page->anulado->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_anulado">
<span<?= $Page->anulado->viewAttributes() ?>>
<?= $Page->anulado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagado->Visible) { // pagado ?>
    <tr id="r_pagado"<?= $Page->pagado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_pagado"><?= $Page->pagado->caption() ?></span></td>
        <td data-name="pagado"<?= $Page->pagado->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_pagado">
<span<?= $Page->pagado->viewAttributes() ?>>
<?= $Page->pagado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_dia->Visible) { // tasa_dia ?>
    <tr id="r_tasa_dia"<?= $Page->tasa_dia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_tasa_dia"><?= $Page->tasa_dia->caption() ?></span></td>
        <td data-name="tasa_dia"<?= $Page->tasa_dia->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_tasa_dia">
<span<?= $Page->tasa_dia->viewAttributes() ?>>
<?= $Page->tasa_dia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->signo_documento->Visible) { // signo_documento ?>
    <tr id="r_signo_documento"<?= $Page->signo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_signo_documento"><?= $Page->signo_documento->caption() ?></span></td>
        <td data-name="signo_documento"<?= $Page->signo_documento->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_signo_documento">
<span<?= $Page->signo_documento->viewAttributes() ?>>
<?= $Page->signo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_documento_moneda->Visible) { // monto_documento_moneda ?>
    <tr id="r_monto_documento_moneda"<?= $Page->monto_documento_moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_monto_documento_moneda"><?= $Page->monto_documento_moneda->caption() ?></span></td>
        <td data-name="monto_documento_moneda"<?= $Page->monto_documento_moneda->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_monto_documento_moneda">
<span<?= $Page->monto_documento_moneda->viewAttributes() ?>>
<?= $Page->monto_documento_moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
    <tr id="r_monto_documento_bs"<?= $Page->monto_documento_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_monto_documento_bs"><?= $Page->monto_documento_bs->caption() ?></span></td>
        <td data-name="monto_documento_bs"<?= $Page->monto_documento_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_monto_documento_bs">
<span<?= $Page->monto_documento_bs->viewAttributes() ?>>
<?= $Page->monto_documento_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_documento_usd->Visible) { // monto_documento_usd ?>
    <tr id="r_monto_documento_usd"<?= $Page->monto_documento_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_monto_documento_usd"><?= $Page->monto_documento_usd->caption() ?></span></td>
        <td data-name="monto_documento_usd"<?= $Page->monto_documento_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_monto_documento_usd">
<span<?= $Page->monto_documento_usd->viewAttributes() ?>>
<?= $Page->monto_documento_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_aplicado_bs->Visible) { // monto_aplicado_bs ?>
    <tr id="r_monto_aplicado_bs"<?= $Page->monto_aplicado_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_monto_aplicado_bs"><?= $Page->monto_aplicado_bs->caption() ?></span></td>
        <td data-name="monto_aplicado_bs"<?= $Page->monto_aplicado_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_monto_aplicado_bs">
<span<?= $Page->monto_aplicado_bs->viewAttributes() ?>>
<?= $Page->monto_aplicado_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_aplicado_usd->Visible) { // monto_aplicado_usd ?>
    <tr id="r_monto_aplicado_usd"<?= $Page->monto_aplicado_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_monto_aplicado_usd"><?= $Page->monto_aplicado_usd->caption() ?></span></td>
        <td data-name="monto_aplicado_usd"<?= $Page->monto_aplicado_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_monto_aplicado_usd">
<span<?= $Page->monto_aplicado_usd->viewAttributes() ?>>
<?= $Page->monto_aplicado_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
    <tr id="r_total_pagado_bs"<?= $Page->total_pagado_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_total_pagado_bs"><?= $Page->total_pagado_bs->caption() ?></span></td>
        <td data-name="total_pagado_bs"<?= $Page->total_pagado_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_total_pagado_bs">
<span<?= $Page->total_pagado_bs->viewAttributes() ?>>
<?= $Page->total_pagado_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_pagado_usd->Visible) { // total_pagado_usd ?>
    <tr id="r_total_pagado_usd"<?= $Page->total_pagado_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_total_pagado_usd"><?= $Page->total_pagado_usd->caption() ?></span></td>
        <td data-name="total_pagado_usd"<?= $Page->total_pagado_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_total_pagado_usd">
<span<?= $Page->total_pagado_usd->viewAttributes() ?>>
<?= $Page->total_pagado_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_pagos->Visible) { // cantidad_pagos ?>
    <tr id="r_cantidad_pagos"<?= $Page->cantidad_pagos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_cantidad_pagos"><?= $Page->cantidad_pagos->caption() ?></span></td>
        <td data-name="cantidad_pagos"<?= $Page->cantidad_pagos->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_cantidad_pagos">
<span<?= $Page->cantidad_pagos->viewAttributes() ?>>
<?= $Page->cantidad_pagos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
    <tr id="r_saldo_bs"<?= $Page->saldo_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_saldo_bs"><?= $Page->saldo_bs->caption() ?></span></td>
        <td data-name="saldo_bs"<?= $Page->saldo_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_saldo_bs">
<span<?= $Page->saldo_bs->viewAttributes() ?>>
<?= $Page->saldo_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->saldo_usd->Visible) { // saldo_usd ?>
    <tr id="r_saldo_usd"<?= $Page->saldo_usd->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_saldo_usd"><?= $Page->saldo_usd->caption() ?></span></td>
        <td data-name="saldo_usd"<?= $Page->saldo_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_saldo_usd">
<span<?= $Page->saldo_usd->viewAttributes() ?>>
<?= $Page->saldo_usd->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estado_cuenta->Visible) { // estado_cuenta ?>
    <tr id="r_estado_cuenta"<?= $Page->estado_cuenta->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_estado_cuenta"><?= $Page->estado_cuenta->caption() ?></span></td>
        <td data-name="estado_cuenta"<?= $Page->estado_cuenta->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_estado_cuenta">
<span<?= $Page->estado_cuenta->viewAttributes() ?>>
<?= $Page->estado_cuenta->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->dias_transcurridos->Visible) { // dias_transcurridos ?>
    <tr id="r_dias_transcurridos"<?= $Page->dias_transcurridos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_dias_transcurridos"><?= $Page->dias_transcurridos->caption() ?></span></td>
        <td data-name="dias_transcurridos"<?= $Page->dias_transcurridos->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_dias_transcurridos">
<span<?= $Page->dias_transcurridos->viewAttributes() ?>>
<?= $Page->dias_transcurridos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->antiguedad->Visible) { // antiguedad ?>
    <tr id="r_antiguedad"<?= $Page->antiguedad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_view_cuentas_por_pagar_antiguedad"><?= $Page->antiguedad->caption() ?></span></td>
        <td data-name="antiguedad"<?= $Page->antiguedad->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_antiguedad">
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
