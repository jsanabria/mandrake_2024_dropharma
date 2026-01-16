<?php

namespace PHPMaker2024\mandrake;

// Page object
$ParametroView = &$Page;
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
<form name="fparametroview" id="fparametroview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { parametro: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fparametroview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fparametroview")
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
<input type="hidden" name="t" value="parametro">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_parametro_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo"<?= $Page->codigo->cellAttributes() ?>>
<span id="el_parametro_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->valor1->Visible) { // valor1 ?>
    <tr id="r_valor1"<?= $Page->valor1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_parametro_valor1"><?= $Page->valor1->caption() ?></span></td>
        <td data-name="valor1"<?= $Page->valor1->cellAttributes() ?>>
<span id="el_parametro_valor1">
<span<?= $Page->valor1->viewAttributes() ?>>
<?= $Page->valor1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->valor2->Visible) { // valor2 ?>
    <tr id="r_valor2"<?= $Page->valor2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_parametro_valor2"><?= $Page->valor2->caption() ?></span></td>
        <td data-name="valor2"<?= $Page->valor2->cellAttributes() ?>>
<span id="el_parametro_valor2">
<span<?= $Page->valor2->viewAttributes() ?>>
<?= $Page->valor2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->valor3->Visible) { // valor3 ?>
    <tr id="r_valor3"<?= $Page->valor3->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_parametro_valor3"><?= $Page->valor3->caption() ?></span></td>
        <td data-name="valor3"<?= $Page->valor3->cellAttributes() ?>>
<span id="el_parametro_valor3">
<span<?= $Page->valor3->viewAttributes() ?>>
<?= $Page->valor3->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->valor4->Visible) { // valor4 ?>
    <tr id="r_valor4"<?= $Page->valor4->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_parametro_valor4"><?= $Page->valor4->caption() ?></span></td>
        <td data-name="valor4"<?= $Page->valor4->cellAttributes() ?>>
<span id="el_parametro_valor4">
<span<?= $Page->valor4->viewAttributes() ?>>
<?= $Page->valor4->getViewValue() ?></span>
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
