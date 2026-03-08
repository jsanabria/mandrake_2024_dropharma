<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContCentroCostoMdkView = &$Page;
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
<form name="fcont_centro_costo_mdkview" id="fcont_centro_costo_mdkview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_centro_costo_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fcont_centro_costo_mdkview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_centro_costo_mdkview")
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
<input type="hidden" name="t" value="cont_centro_costo_mdk">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_centro_costo_mdk_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo"<?= $Page->codigo->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_centro_costo_mdk_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre"<?= $Page->nombre->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <tr id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_centro_costo_mdk_descripcion"><?= $Page->descripcion->caption() ?></span></td>
        <td data-name="descripcion"<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->centro_padre_id->Visible) { // centro_padre_id ?>
    <tr id="r_centro_padre_id"<?= $Page->centro_padre_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_centro_costo_mdk_centro_padre_id"><?= $Page->centro_padre_id->caption() ?></span></td>
        <td data-name="centro_padre_id"<?= $Page->centro_padre_id->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_centro_padre_id">
<span<?= $Page->centro_padre_id->viewAttributes() ?>>
<?= $Page->centro_padre_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nivel->Visible) { // nivel ?>
    <tr id="r_nivel"<?= $Page->nivel->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_centro_costo_mdk_nivel"><?= $Page->nivel->caption() ?></span></td>
        <td data-name="nivel"<?= $Page->nivel->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_nivel">
<span<?= $Page->nivel->viewAttributes() ?>>
<?= $Page->nivel->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <tr id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_cont_centro_costo_mdk_estado"><?= $Page->estado->caption() ?></span></td>
        <td data-name="estado"<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_estado">
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
