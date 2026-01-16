<?php

namespace PHPMaker2024\mandrake;

// Page object
$TempConsignacionView = &$Page;
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
<form name="ftemp_consignacionview" id="ftemp_consignacionview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { temp_consignacion: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var ftemp_consignacionview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftemp_consignacionview")
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
<input type="hidden" name="t" value="temp_consignacion">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_temp_consignacion_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_temp_consignacion__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <tr id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_nro_documento"><?= $Page->nro_documento->caption() ?></span></td>
        <td data-name="nro_documento"<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <tr id="r_id_documento"<?= $Page->id_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_id_documento"><?= $Page->id_documento->caption() ?></span></td>
        <td data-name="id_documento"<?= $Page->id_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <tr id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_tipo_documento"><?= $Page->tipo_documento->caption() ?></span></td>
        <td data-name="tipo_documento"<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_temp_consignacion_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante"<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_temp_consignacion_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo"<?= $Page->articulo->cellAttributes() ?>>
<span id="el_temp_consignacion_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_movimiento->Visible) { // cantidad_movimiento ?>
    <tr id="r_cantidad_movimiento"<?= $Page->cantidad_movimiento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_cantidad_movimiento"><?= $Page->cantidad_movimiento->caption() ?></span></td>
        <td data-name="cantidad_movimiento"<?= $Page->cantidad_movimiento->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_movimiento">
<span<?= $Page->cantidad_movimiento->viewAttributes() ?>>
<?= $Page->cantidad_movimiento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_entre_fechas->Visible) { // cantidad_entre_fechas ?>
    <tr id="r_cantidad_entre_fechas"<?= $Page->cantidad_entre_fechas->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_cantidad_entre_fechas"><?= $Page->cantidad_entre_fechas->caption() ?></span></td>
        <td data-name="cantidad_entre_fechas"<?= $Page->cantidad_entre_fechas->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_entre_fechas">
<span<?= $Page->cantidad_entre_fechas->viewAttributes() ?>>
<?= $Page->cantidad_entre_fechas->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_acumulada->Visible) { // cantidad_acumulada ?>
    <tr id="r_cantidad_acumulada"<?= $Page->cantidad_acumulada->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_cantidad_acumulada"><?= $Page->cantidad_acumulada->caption() ?></span></td>
        <td data-name="cantidad_acumulada"<?= $Page->cantidad_acumulada->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_acumulada">
<span<?= $Page->cantidad_acumulada->viewAttributes() ?>>
<?= $Page->cantidad_acumulada->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_ajuste->Visible) { // cantidad_ajuste ?>
    <tr id="r_cantidad_ajuste"<?= $Page->cantidad_ajuste->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_temp_consignacion_cantidad_ajuste"><?= $Page->cantidad_ajuste->caption() ?></span></td>
        <td data-name="cantidad_ajuste"<?= $Page->cantidad_ajuste->cellAttributes() ?>>
<span id="el_temp_consignacion_cantidad_ajuste">
<span<?= $Page->cantidad_ajuste->viewAttributes() ?>>
<?= $Page->cantidad_ajuste->getViewValue() ?></span>
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
