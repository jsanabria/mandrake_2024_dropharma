<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContAsientoDetalleMdkView = &$Page;
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
<form name="fcont_asiento_detalle_mdkview" id="fcont_asiento_detalle_mdkview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_asiento_detalle_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcont_asiento_detalle_mdkview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_asiento_detalle_mdkview")
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
<input type="hidden" name="t" value="cont_asiento_detalle_mdk">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asiento_id->Visible) { // asiento_id ?>
    <tr id="r_asiento_id"<?= $Page->asiento_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_asiento_id"><?= $Page->asiento_id->caption() ?></span></td>
        <td data-name="asiento_id"<?= $Page->asiento_id->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_asiento_id">
<span<?= $Page->asiento_id->viewAttributes() ?>>
<?= $Page->asiento_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
    <tr id="r_cuenta_id"<?= $Page->cuenta_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_cuenta_id"><?= $Page->cuenta_id->caption() ?></span></td>
        <td data-name="cuenta_id"<?= $Page->cuenta_id->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_cuenta_id">
<span<?= $Page->cuenta_id->viewAttributes() ?>>
<?= $Page->cuenta_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->centro_costo_id->Visible) { // centro_costo_id ?>
    <tr id="r_centro_costo_id"<?= $Page->centro_costo_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_centro_costo_id"><?= $Page->centro_costo_id->caption() ?></span></td>
        <td data-name="centro_costo_id"<?= $Page->centro_costo_id->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_centro_costo_id">
<span<?= $Page->centro_costo_id->viewAttributes() ?>>
<?= $Page->centro_costo_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->concepto->Visible) { // concepto ?>
    <tr id="r_concepto"<?= $Page->concepto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_concepto"><?= $Page->concepto->caption() ?></span></td>
        <td data-name="concepto"<?= $Page->concepto->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_concepto">
<span<?= $Page->concepto->viewAttributes() ?>>
<?= $Page->concepto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda_trx->Visible) { // moneda_trx ?>
    <tr id="r_moneda_trx"<?= $Page->moneda_trx->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_moneda_trx"><?= $Page->moneda_trx->caption() ?></span></td>
        <td data-name="moneda_trx"<?= $Page->moneda_trx->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_moneda_trx">
<span<?= $Page->moneda_trx->viewAttributes() ?>>
<?= $Page->moneda_trx->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_trx->Visible) { // tasa_trx ?>
    <tr id="r_tasa_trx"<?= $Page->tasa_trx->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_tasa_trx"><?= $Page->tasa_trx->caption() ?></span></td>
        <td data-name="tasa_trx"<?= $Page->tasa_trx->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_tasa_trx">
<span<?= $Page->tasa_trx->viewAttributes() ?>>
<?= $Page->tasa_trx->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
    <tr id="r_monto_moneda_trx"<?= $Page->monto_moneda_trx->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_monto_moneda_trx"><?= $Page->monto_moneda_trx->caption() ?></span></td>
        <td data-name="monto_moneda_trx"<?= $Page->monto_moneda_trx->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_monto_moneda_trx">
<span<?= $Page->monto_moneda_trx->viewAttributes() ?>>
<?= $Page->monto_moneda_trx->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->debe_bs->Visible) { // debe_bs ?>
    <tr id="r_debe_bs"<?= $Page->debe_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_debe_bs"><?= $Page->debe_bs->caption() ?></span></td>
        <td data-name="debe_bs"<?= $Page->debe_bs->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_debe_bs">
<span<?= $Page->debe_bs->viewAttributes() ?>>
<?= $Page->debe_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->haber_bs->Visible) { // haber_bs ?>
    <tr id="r_haber_bs"<?= $Page->haber_bs->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_haber_bs"><?= $Page->haber_bs->caption() ?></span></td>
        <td data-name="haber_bs"<?= $Page->haber_bs->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_haber_bs">
<span<?= $Page->haber_bs->viewAttributes() ?>>
<?= $Page->haber_bs->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_asiento_detalle_mdk_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_cont_asiento_detalle_mdk_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
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
