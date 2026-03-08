<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContTerceroMdkView = &$Page;
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
<form name="fcont_tercero_mdkview" id="fcont_tercero_mdkview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_tercero_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcont_tercero_mdkview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_tercero_mdkview")
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
<input type="hidden" name="t" value="cont_tercero_mdk">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo"<?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->origen_tabla->Visible) { // origen_tabla ?>
    <tr id="r_origen_tabla"<?= $Page->origen_tabla->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_origen_tabla"><?= $Page->origen_tabla->caption() ?></span></td>
        <td data-name="origen_tabla"<?= $Page->origen_tabla->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_origen_tabla">
<span<?= $Page->origen_tabla->viewAttributes() ?>>
<?= $Page->origen_tabla->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->origen_id->Visible) { // origen_id ?>
    <tr id="r_origen_id"<?= $Page->origen_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_origen_id"><?= $Page->origen_id->caption() ?></span></td>
        <td data-name="origen_id"<?= $Page->origen_id->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_origen_id">
<span<?= $Page->origen_id->viewAttributes() ?>>
<?= $Page->origen_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre"<?= $Page->nombre->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rif->Visible) { // rif ?>
    <tr id="r_rif"<?= $Page->rif->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_rif"><?= $Page->rif->caption() ?></span></td>
        <td data-name="rif"<?= $Page->rif->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_rif">
<span<?= $Page->rif->viewAttributes() ?>>
<?= $Page->rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <tr id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_estado"><?= $Page->estado->caption() ?></span></td>
        <td data-name="estado"<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_estado">
<span<?= $Page->estado->viewAttributes() ?>>
<i class="fa-regular fa-square<?php if (ConvertToBool($Page->estado->CurrentValue)) { ?>-check<?php } ?> ew-icon ew-boolean"></i>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_tercero_mdk_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_cont_tercero_mdk_created_at">
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
