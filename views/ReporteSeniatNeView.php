<?php

namespace PHPMaker2024\mandrake;

// Page object
$ReporteSeniatNeView = &$Page;
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
<form name="freporte_seniat_neview" id="freporte_seniat_neview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { reporte_seniat_ne: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var freporte_seniat_neview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("freporte_seniat_neview")
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
<input type="hidden" name="t" value="reporte_seniat_ne">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->periodo->Visible) { // periodo ?>
    <tr id="r_periodo"<?= $Page->periodo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_periodo"><?= $Page->periodo->caption() ?></span></td>
        <td data-name="periodo"<?= $Page->periodo->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_periodo">
<span<?= $Page->periodo->viewAttributes() ?>>
<?= $Page->periodo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad->Visible) { // cantidad ?>
    <tr id="r_cantidad"<?= $Page->cantidad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_cantidad"><?= $Page->cantidad->caption() ?></span></td>
        <td data-name="cantidad"<?= $Page->cantidad->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_cantidad">
<span<?= $Page->cantidad->viewAttributes() ?>>
<?= $Page->cantidad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_total->Visible) { // monto_total ?>
    <tr id="r_monto_total"<?= $Page->monto_total->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_monto_total"><?= $Page->monto_total->caption() ?></span></td>
        <td data-name="monto_total"<?= $Page->monto_total->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_monto_total">
<span<?= $Page->monto_total->viewAttributes() ?>>
<?= $Page->monto_total->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email_destino->Visible) { // email_destino ?>
    <tr id="r_email_destino"<?= $Page->email_destino->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_email_destino"><?= $Page->email_destino->caption() ?></span></td>
        <td data-name="email_destino"<?= $Page->email_destino->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_email_destino">
<span<?= $Page->email_destino->viewAttributes() ?>>
<?= $Page->email_destino->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->enviado_en->Visible) { // enviado_en ?>
    <tr id="r_enviado_en"<?= $Page->enviado_en->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_enviado_en"><?= $Page->enviado_en->caption() ?></span></td>
        <td data-name="enviado_en"<?= $Page->enviado_en->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_enviado_en">
<span<?= $Page->enviado_en->viewAttributes() ?>>
<?= $Page->enviado_en->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <tr id="r_usuario"<?= $Page->usuario->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_reporte_seniat_ne_usuario"><?= $Page->usuario->caption() ?></span></td>
        <td data-name="usuario"<?= $Page->usuario->cellAttributes() ?>>
<span id="el_reporte_seniat_ne_usuario">
<span<?= $Page->usuario->viewAttributes() ?>>
<?= $Page->usuario->getViewValue() ?></span>
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
