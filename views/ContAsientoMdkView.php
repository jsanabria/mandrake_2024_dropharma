<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContAsientoMdkView = &$Page;
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
<form name="fcont_asiento_mdkview" id="fcont_asiento_mdkview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_asiento_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcont_asiento_mdkview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_asiento_mdkview")
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
<input type="hidden" name="t" value="cont_asiento_mdk">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <tr id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_referencia"><?= $Page->referencia->caption() ?></span></td>
        <td data-name="referencia"<?= $Page->referencia->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_referencia">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->modulo_origen->Visible) { // modulo_origen ?>
    <tr id="r_modulo_origen"<?= $Page->modulo_origen->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_modulo_origen"><?= $Page->modulo_origen->caption() ?></span></td>
        <td data-name="modulo_origen"<?= $Page->modulo_origen->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_modulo_origen">
<span<?= $Page->modulo_origen->viewAttributes() ?>>
<?= $Page->modulo_origen->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->origen_tabla->Visible) { // origen_tabla ?>
    <tr id="r_origen_tabla"<?= $Page->origen_tabla->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_origen_tabla"><?= $Page->origen_tabla->caption() ?></span></td>
        <td data-name="origen_tabla"<?= $Page->origen_tabla->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_origen_tabla">
<span<?= $Page->origen_tabla->viewAttributes() ?>>
<?= $Page->origen_tabla->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->origen_id->Visible) { // origen_id ?>
    <tr id="r_origen_id"<?= $Page->origen_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_origen_id"><?= $Page->origen_id->caption() ?></span></td>
        <td data-name="origen_id"<?= $Page->origen_id->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_origen_id">
<span<?= $Page->origen_id->viewAttributes() ?>>
<?= $Page->origen_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tercero_id->Visible) { // tercero_id ?>
    <tr id="r_tercero_id"<?= $Page->tercero_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_tercero_id"><?= $Page->tercero_id->caption() ?></span></td>
        <td data-name="tercero_id"<?= $Page->tercero_id->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_tercero_id">
<span<?= $Page->tercero_id->viewAttributes() ?>>
<?= $Page->tercero_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda_trx->Visible) { // moneda_trx ?>
    <tr id="r_moneda_trx"<?= $Page->moneda_trx->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_moneda_trx"><?= $Page->moneda_trx->caption() ?></span></td>
        <td data-name="moneda_trx"<?= $Page->moneda_trx->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_moneda_trx">
<span<?= $Page->moneda_trx->viewAttributes() ?>>
<?= $Page->moneda_trx->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_trx->Visible) { // tasa_trx ?>
    <tr id="r_tasa_trx"<?= $Page->tasa_trx->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_tasa_trx"><?= $Page->tasa_trx->caption() ?></span></td>
        <td data-name="tasa_trx"<?= $Page->tasa_trx->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_tasa_trx">
<span<?= $Page->tasa_trx->viewAttributes() ?>>
<?= $Page->tasa_trx->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_moneda_trx->Visible) { // total_moneda_trx ?>
    <tr id="r_total_moneda_trx"<?= $Page->total_moneda_trx->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_total_moneda_trx"><?= $Page->total_moneda_trx->caption() ?></span></td>
        <td data-name="total_moneda_trx"<?= $Page->total_moneda_trx->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_total_moneda_trx">
<span<?= $Page->total_moneda_trx->viewAttributes() ?>>
<?= $Page->total_moneda_trx->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->total_bs->Visible) { // total_bs ?>
    <tr id="r_total_bs"<?= $Page->total_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_total_bs"><?= $Page->total_bs->caption() ?></span></td>
        <td data-name="total_bs"<?= $Page->total_bs->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_total_bs">
<span<?= $Page->total_bs->viewAttributes() ?>>
<?= $Page->total_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->centro_costo_id->Visible) { // centro_costo_id ?>
    <tr id="r_centro_costo_id"<?= $Page->centro_costo_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_centro_costo_id"><?= $Page->centro_costo_id->caption() ?></span></td>
        <td data-name="centro_costo_id"<?= $Page->centro_costo_id->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_centro_costo_id">
<span<?= $Page->centro_costo_id->viewAttributes() ?>>
<?= $Page->centro_costo_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_cont_asiento_mdk__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->anulado->Visible) { // anulado ?>
    <tr id="r_anulado"<?= $Page->anulado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_anulado"><?= $Page->anulado->caption() ?></span></td>
        <td data-name="anulado"<?= $Page->anulado->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_anulado">
<span<?= $Page->anulado->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->anulado->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->anulado_at->Visible) { // anulado_at ?>
    <tr id="r_anulado_at"<?= $Page->anulado_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_anulado_at"><?= $Page->anulado_at->caption() ?></span></td>
        <td data-name="anulado_at"<?= $Page->anulado_at->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_anulado_at">
<span<?= $Page->anulado_at->viewAttributes() ?>>
<?= $Page->anulado_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->anulado_by->Visible) { // anulado_by ?>
    <tr id="r_anulado_by"<?= $Page->anulado_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_anulado_by"><?= $Page->anulado_by->caption() ?></span></td>
        <td data-name="anulado_by"<?= $Page->anulado_by->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_anulado_by">
<span<?= $Page->anulado_by->viewAttributes() ?>>
<?= $Page->anulado_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->anulado_motivo->Visible) { // anulado_motivo ?>
    <tr id="r_anulado_motivo"<?= $Page->anulado_motivo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_mdk_anulado_motivo"><?= $Page->anulado_motivo->caption() ?></span></td>
        <td data-name="anulado_motivo"<?= $Page->anulado_motivo->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_anulado_motivo">
<span<?= $Page->anulado_motivo->viewAttributes() ?>>
<?= $Page->anulado_motivo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("cont_asiento_detalle_mdk", explode(",", $Page->getCurrentDetailTable())) && $cont_asiento_detalle_mdk->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cont_asiento_detalle_mdk", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ContAsientoDetalleMdkGrid.php" ?>
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
