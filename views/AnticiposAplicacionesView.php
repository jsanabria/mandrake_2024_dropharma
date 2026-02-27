<?php

namespace PHPMaker2024\mandrake;

// Page object
$AnticiposAplicacionesView = &$Page;
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
<form name="fanticipos_aplicacionesview" id="fanticipos_aplicacionesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { anticipos_aplicaciones: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fanticipos_aplicacionesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fanticipos_aplicacionesview")
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
<input type="hidden" name="t" value="anticipos_aplicaciones">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->anticipo_cobro_id->Visible) { // anticipo_cobro_id ?>
    <tr id="r_anticipo_cobro_id"<?= $Page->anticipo_cobro_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_anticipo_cobro_id"><?= $Page->anticipo_cobro_id->caption() ?></span></td>
        <td data-name="anticipo_cobro_id"<?= $Page->anticipo_cobro_id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_anticipo_cobro_id">
<span<?= $Page->anticipo_cobro_id->viewAttributes() ?>>
<?= $Page->anticipo_cobro_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cobro_factura_id->Visible) { // cobro_factura_id ?>
    <tr id="r_cobro_factura_id"<?= $Page->cobro_factura_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_cobro_factura_id"><?= $Page->cobro_factura_id->caption() ?></span></td>
        <td data-name="cobro_factura_id"<?= $Page->cobro_factura_id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_cobro_factura_id">
<span<?= $Page->cobro_factura_id->viewAttributes() ?>>
<?= $Page->cobro_factura_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->salida_id->Visible) { // salida_id ?>
    <tr id="r_salida_id"<?= $Page->salida_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_salida_id"><?= $Page->salida_id->caption() ?></span></td>
        <td data-name="salida_id"<?= $Page->salida_id->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_salida_id">
<span<?= $Page->salida_id->viewAttributes() ?>>
<?= $Page->salida_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <tr id="r_monto_moneda"<?= $Page->monto_moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_monto_moneda"><?= $Page->monto_moneda->caption() ?></span></td>
        <td data-name="monto_moneda"<?= $Page->monto_moneda->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_monto_moneda">
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <tr id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_moneda"><?= $Page->moneda->caption() ?></span></td>
        <td data-name="moneda"<?= $Page->moneda->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_moneda">
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tasa_factura->Visible) { // tasa_factura ?>
    <tr id="r_tasa_factura"<?= $Page->tasa_factura->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anticipos_aplicaciones_tasa_factura"><?= $Page->tasa_factura->caption() ?></span></td>
        <td data-name="tasa_factura"<?= $Page->tasa_factura->cellAttributes() ?>>
<span id="el_anticipos_aplicaciones_tasa_factura">
<span<?= $Page->tasa_factura->viewAttributes() ?>>
<?= $Page->tasa_factura->getViewValue() ?></span>
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
