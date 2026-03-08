<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContMetodoCuentaMdkView = &$Page;
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
<form name="fcont_metodo_cuenta_mdkview" id="fcont_metodo_cuenta_mdkview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_metodo_cuenta_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcont_metodo_cuenta_mdkview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_metodo_cuenta_mdkview")
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
<input type="hidden" name="t" value="cont_metodo_cuenta_mdk">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <tr id="r_metodo_pago"<?= $Page->metodo_pago->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_metodo_pago"><?= $Page->metodo_pago->caption() ?></span></td>
        <td data-name="metodo_pago"<?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_metodo_pago">
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_destino->Visible) { // tipo_destino ?>
    <tr id="r_tipo_destino"<?= $Page->tipo_destino->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_tipo_destino"><?= $Page->tipo_destino->caption() ?></span></td>
        <td data-name="tipo_destino"<?= $Page->tipo_destino->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_tipo_destino">
<span<?= $Page->tipo_destino->viewAttributes() ?>>
<?= $Page->tipo_destino->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
    <tr id="r_cuenta_id"<?= $Page->cuenta_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_cuenta_id"><?= $Page->cuenta_id->caption() ?></span></td>
        <td data-name="cuenta_id"<?= $Page->cuenta_id->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_cuenta_id">
<span<?= $Page->cuenta_id->viewAttributes() ?>>
<?= $Page->cuenta_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->forzar_destino->Visible) { // forzar_destino ?>
    <tr id="r_forzar_destino"<?= $Page->forzar_destino->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_forzar_destino"><?= $Page->forzar_destino->caption() ?></span></td>
        <td data-name="forzar_destino"<?= $Page->forzar_destino->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_forzar_destino">
<span<?= $Page->forzar_destino->viewAttributes() ?>>
<?= $Page->forzar_destino->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <tr id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_metodo_cuenta_mdk_estado"><?= $Page->estado->caption() ?></span></td>
        <td data-name="estado"<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_estado">
<span<?= $Page->estado->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->estado->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
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
