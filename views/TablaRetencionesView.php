<?php

namespace PHPMaker2024\mandrake;

// Page object
$TablaRetencionesView = &$Page;
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
<form name="ftabla_retencionesview" id="ftabla_retencionesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tabla_retenciones: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ftabla_retencionesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftabla_retencionesview")
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
<input type="hidden" name="t" value="tabla_retenciones">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_tabla_retenciones_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo"<?= $Page->codigo->cellAttributes() ?>>
<span id="el_tabla_retenciones_codigo">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <tr id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_tipo"><?= $Page->tipo->caption() ?></span></td>
        <td data-name="tipo"<?= $Page->tipo->cellAttributes() ?>>
<span id="el_tabla_retenciones_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->base_imponible->Visible) { // base_imponible ?>
    <tr id="r_base_imponible"<?= $Page->base_imponible->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_base_imponible"><?= $Page->base_imponible->caption() ?></span></td>
        <td data-name="base_imponible"<?= $Page->base_imponible->cellAttributes() ?>>
<span id="el_tabla_retenciones_base_imponible">
<span<?= $Page->base_imponible->viewAttributes() ?>>
<?= $Page->base_imponible->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <tr id="r_tarifa"<?= $Page->tarifa->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_tarifa"><?= $Page->tarifa->caption() ?></span></td>
        <td data-name="tarifa"<?= $Page->tarifa->cellAttributes() ?>>
<span id="el_tabla_retenciones_tarifa">
<span<?= $Page->tarifa->viewAttributes() ?>>
<?= $Page->tarifa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <tr id="r_sustraendo"<?= $Page->sustraendo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_sustraendo"><?= $Page->sustraendo->caption() ?></span></td>
        <td data-name="sustraendo"<?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_tabla_retenciones_sustraendo">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pagos_mayores->Visible) { // pagos_mayores ?>
    <tr id="r_pagos_mayores"<?= $Page->pagos_mayores->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_pagos_mayores"><?= $Page->pagos_mayores->caption() ?></span></td>
        <td data-name="pagos_mayores"<?= $Page->pagos_mayores->cellAttributes() ?>>
<span id="el_tabla_retenciones_pagos_mayores">
<span<?= $Page->pagos_mayores->viewAttributes() ?>>
<?= $Page->pagos_mayores->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_tabla_retenciones_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo"<?= $Page->activo->cellAttributes() ?>>
<span id="el_tabla_retenciones_activo">
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
