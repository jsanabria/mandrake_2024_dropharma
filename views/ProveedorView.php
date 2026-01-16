<?php

namespace PHPMaker2024\mandrake;

// Page object
$ProveedorView = &$Page;
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
<form name="fproveedorview" id="fproveedorview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { proveedor: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fproveedorview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproveedorview")
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
<input type="hidden" name="t" value="proveedor">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <tr id="r_ci_rif"<?= $Page->ci_rif->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_ci_rif"><?= $Page->ci_rif->caption() ?></span></td>
        <td data-name="ci_rif"<?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_proveedor_ci_rif">
<span<?= $Page->ci_rif->viewAttributes() ?>>
<?= $Page->ci_rif->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <tr id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_nombre"><?= $Page->nombre->caption() ?></span></td>
        <td data-name="nombre"<?= $Page->nombre->cellAttributes() ?>>
<span id="el_proveedor_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <tr id="r_ciudad"<?= $Page->ciudad->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_ciudad"><?= $Page->ciudad->caption() ?></span></td>
        <td data-name="ciudad"<?= $Page->ciudad->cellAttributes() ?>>
<span id="el_proveedor_ciudad">
<span<?= $Page->ciudad->viewAttributes() ?>>
<?= $Page->ciudad->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <tr id="r_direccion"<?= $Page->direccion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_direccion"><?= $Page->direccion->caption() ?></span></td>
        <td data-name="direccion"<?= $Page->direccion->cellAttributes() ?>>
<span id="el_proveedor_direccion">
<span<?= $Page->direccion->viewAttributes() ?>>
<?= $Page->direccion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <tr id="r_telefono1"<?= $Page->telefono1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_telefono1"><?= $Page->telefono1->caption() ?></span></td>
        <td data-name="telefono1"<?= $Page->telefono1->cellAttributes() ?>>
<span id="el_proveedor_telefono1">
<span<?= $Page->telefono1->viewAttributes() ?>>
<?= $Page->telefono1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <tr id="r_telefono2"<?= $Page->telefono2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_telefono2"><?= $Page->telefono2->caption() ?></span></td>
        <td data-name="telefono2"<?= $Page->telefono2->cellAttributes() ?>>
<span id="el_proveedor_telefono2">
<span<?= $Page->telefono2->viewAttributes() ?>>
<?= $Page->telefono2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <tr id="r_email1"<?= $Page->email1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_email1"><?= $Page->email1->caption() ?></span></td>
        <td data-name="email1"<?= $Page->email1->cellAttributes() ?>>
<span id="el_proveedor_email1">
<span<?= $Page->email1->viewAttributes() ?>>
<?= $Page->email1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <tr id="r_email2"<?= $Page->email2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_email2"><?= $Page->email2->caption() ?></span></td>
        <td data-name="email2"<?= $Page->email2->cellAttributes() ?>>
<span id="el_proveedor_email2">
<span<?= $Page->email2->viewAttributes() ?>>
<?= $Page->email2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta_auxiliar->Visible) { // cuenta_auxiliar ?>
    <tr id="r_cuenta_auxiliar"<?= $Page->cuenta_auxiliar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_cuenta_auxiliar"><?= $Page->cuenta_auxiliar->caption() ?></span></td>
        <td data-name="cuenta_auxiliar"<?= $Page->cuenta_auxiliar->cellAttributes() ?>>
<span id="el_proveedor_cuenta_auxiliar">
<span<?= $Page->cuenta_auxiliar->viewAttributes() ?>>
<?= $Page->cuenta_auxiliar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cuenta_gasto->Visible) { // cuenta_gasto ?>
    <tr id="r_cuenta_gasto"<?= $Page->cuenta_gasto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_cuenta_gasto"><?= $Page->cuenta_gasto->caption() ?></span></td>
        <td data-name="cuenta_gasto"<?= $Page->cuenta_gasto->cellAttributes() ?>>
<span id="el_proveedor_cuenta_gasto">
<span<?= $Page->cuenta_gasto->viewAttributes() ?>>
<?= $Page->cuenta_gasto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_iva->Visible) { // tipo_iva ?>
    <tr id="r_tipo_iva"<?= $Page->tipo_iva->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_tipo_iva"><?= $Page->tipo_iva->caption() ?></span></td>
        <td data-name="tipo_iva"<?= $Page->tipo_iva->cellAttributes() ?>>
<span id="el_proveedor_tipo_iva">
<span<?= $Page->tipo_iva->viewAttributes() ?>>
<?= $Page->tipo_iva->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_islr->Visible) { // tipo_islr ?>
    <tr id="r_tipo_islr"<?= $Page->tipo_islr->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_tipo_islr"><?= $Page->tipo_islr->caption() ?></span></td>
        <td data-name="tipo_islr"<?= $Page->tipo_islr->cellAttributes() ?>>
<span id="el_proveedor_tipo_islr">
<span<?= $Page->tipo_islr->viewAttributes() ?>>
<?= $Page->tipo_islr->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <tr id="r_sustraendo"<?= $Page->sustraendo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_sustraendo"><?= $Page->sustraendo->caption() ?></span></td>
        <td data-name="sustraendo"<?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_proveedor_sustraendo">
<span<?= $Page->sustraendo->viewAttributes() ?>>
<?= $Page->sustraendo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_impmun->Visible) { // tipo_impmun ?>
    <tr id="r_tipo_impmun"<?= $Page->tipo_impmun->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_tipo_impmun"><?= $Page->tipo_impmun->caption() ?></span></td>
        <td data-name="tipo_impmun"<?= $Page->tipo_impmun->cellAttributes() ?>>
<span id="el_proveedor_tipo_impmun">
<span<?= $Page->tipo_impmun->viewAttributes() ?>>
<?= $Page->tipo_impmun->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <tr id="r_cta_bco"<?= $Page->cta_bco->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_cta_bco"><?= $Page->cta_bco->caption() ?></span></td>
        <td data-name="cta_bco"<?= $Page->cta_bco->cellAttributes() ?>>
<span id="el_proveedor_cta_bco">
<span<?= $Page->cta_bco->viewAttributes() ?>>
<?= $Page->cta_bco->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo"<?= $Page->activo->cellAttributes() ?>>
<span id="el_proveedor_activo">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_proveedor_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante"<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_proveedor_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("proveedor_articulo", explode(",", $Page->getCurrentDetailTable())) && $proveedor_articulo->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("proveedor_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProveedorArticuloGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
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
