<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContMesContableView = &$Page;
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
<form name="fcont_mes_contableview" id="fcont_mes_contableview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_mes_contable: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcont_mes_contableview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_mes_contableview")
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
<input type="hidden" name="t" value="cont_mes_contable">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_mes_contable_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
    <tr id="r_M01"<?= $Page->M01->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M01"><?= $Page->M01->caption() ?></span></td>
        <td data-name="M01"<?= $Page->M01->cellAttributes() ?>>
<span id="el_cont_mes_contable_M01">
<span<?= $Page->M01->viewAttributes() ?>>
<?= $Page->M01->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
    <tr id="r_M02"<?= $Page->M02->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M02"><?= $Page->M02->caption() ?></span></td>
        <td data-name="M02"<?= $Page->M02->cellAttributes() ?>>
<span id="el_cont_mes_contable_M02">
<span<?= $Page->M02->viewAttributes() ?>>
<?= $Page->M02->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
    <tr id="r_M03"<?= $Page->M03->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M03"><?= $Page->M03->caption() ?></span></td>
        <td data-name="M03"<?= $Page->M03->cellAttributes() ?>>
<span id="el_cont_mes_contable_M03">
<span<?= $Page->M03->viewAttributes() ?>>
<?= $Page->M03->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
    <tr id="r_M04"<?= $Page->M04->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M04"><?= $Page->M04->caption() ?></span></td>
        <td data-name="M04"<?= $Page->M04->cellAttributes() ?>>
<span id="el_cont_mes_contable_M04">
<span<?= $Page->M04->viewAttributes() ?>>
<?= $Page->M04->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
    <tr id="r_M05"<?= $Page->M05->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M05"><?= $Page->M05->caption() ?></span></td>
        <td data-name="M05"<?= $Page->M05->cellAttributes() ?>>
<span id="el_cont_mes_contable_M05">
<span<?= $Page->M05->viewAttributes() ?>>
<?= $Page->M05->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
    <tr id="r_M06"<?= $Page->M06->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M06"><?= $Page->M06->caption() ?></span></td>
        <td data-name="M06"<?= $Page->M06->cellAttributes() ?>>
<span id="el_cont_mes_contable_M06">
<span<?= $Page->M06->viewAttributes() ?>>
<?= $Page->M06->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
    <tr id="r_M07"<?= $Page->M07->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M07"><?= $Page->M07->caption() ?></span></td>
        <td data-name="M07"<?= $Page->M07->cellAttributes() ?>>
<span id="el_cont_mes_contable_M07">
<span<?= $Page->M07->viewAttributes() ?>>
<?= $Page->M07->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
    <tr id="r_M08"<?= $Page->M08->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M08"><?= $Page->M08->caption() ?></span></td>
        <td data-name="M08"<?= $Page->M08->cellAttributes() ?>>
<span id="el_cont_mes_contable_M08">
<span<?= $Page->M08->viewAttributes() ?>>
<?= $Page->M08->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
    <tr id="r_M09"<?= $Page->M09->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M09"><?= $Page->M09->caption() ?></span></td>
        <td data-name="M09"<?= $Page->M09->cellAttributes() ?>>
<span id="el_cont_mes_contable_M09">
<span<?= $Page->M09->viewAttributes() ?>>
<?= $Page->M09->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
    <tr id="r_M10"<?= $Page->M10->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M10"><?= $Page->M10->caption() ?></span></td>
        <td data-name="M10"<?= $Page->M10->cellAttributes() ?>>
<span id="el_cont_mes_contable_M10">
<span<?= $Page->M10->viewAttributes() ?>>
<?= $Page->M10->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
    <tr id="r_M11"<?= $Page->M11->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M11"><?= $Page->M11->caption() ?></span></td>
        <td data-name="M11"<?= $Page->M11->cellAttributes() ?>>
<span id="el_cont_mes_contable_M11">
<span<?= $Page->M11->viewAttributes() ?>>
<?= $Page->M11->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
    <tr id="r_M12"<?= $Page->M12->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_M12"><?= $Page->M12->caption() ?></span></td>
        <td data-name="M12"<?= $Page->M12->cellAttributes() ?>>
<span id="el_cont_mes_contable_M12">
<span<?= $Page->M12->viewAttributes() ?>>
<?= $Page->M12->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_mes_contable_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo"<?= $Page->activo->cellAttributes() ?>>
<span id="el_cont_mes_contable_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
