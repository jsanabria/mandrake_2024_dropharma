<?php

namespace PHPMaker2024\mandrake;

// Page object
$ArticuloView = &$Page;
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
<form name="farticuloview" id="farticuloview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { articulo: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var farticuloview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("farticuloview")
        .setPageId("view")

        // Multi-Page
        .setMultiPage(true)
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
<input type="hidden" name="t" value="articulo">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (!$Page->isExport()) { ?>
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_ArticuloView"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_articulo1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_articulo1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_articulo2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_articulo2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_articulo3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_articulo3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>">
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_articulo1" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->codigo->Visible) { // codigo ?>
    <tr id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_codigo"><?= $Page->codigo->caption() ?></span></td>
        <td data-name="codigo"<?= $Page->codigo->cellAttributes() ?>>
<span id="el_articulo_codigo" data-page="1">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
    <tr id="r_nombre_comercial"<?= $Page->nombre_comercial->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_nombre_comercial"><?= $Page->nombre_comercial->caption() ?></span></td>
        <td data-name="nombre_comercial"<?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="el_articulo_nombre_comercial" data-page="1">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<?= $Page->nombre_comercial->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
    <tr id="r_principio_activo"<?= $Page->principio_activo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_principio_activo"><?= $Page->principio_activo->caption() ?></span></td>
        <td data-name="principio_activo"<?= $Page->principio_activo->cellAttributes() ?>>
<span id="el_articulo_principio_activo" data-page="1">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
    <tr id="r_presentacion"<?= $Page->presentacion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_presentacion"><?= $Page->presentacion->caption() ?></span></td>
        <td data-name="presentacion"<?= $Page->presentacion->cellAttributes() ?>>
<span id="el_articulo_presentacion" data-page="1">
<span<?= $Page->presentacion->viewAttributes() ?>>
<?= $Page->presentacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante"<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_articulo_fabricante" data-page="1">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->codigo_de_barra->Visible) { // codigo_de_barra ?>
    <tr id="r_codigo_de_barra"<?= $Page->codigo_de_barra->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_codigo_de_barra"><?= $Page->codigo_de_barra->caption() ?></span></td>
        <td data-name="codigo_de_barra"<?= $Page->codigo_de_barra->cellAttributes() ?>>
<span id="el_articulo_codigo_de_barra" data-page="1">
<span<?= $Page->codigo_de_barra->viewAttributes() ?>>
<?= $Page->codigo_de_barra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo_inventario->Visible) { // articulo_inventario ?>
    <tr id="r_articulo_inventario"<?= $Page->articulo_inventario->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_articulo_inventario"><?= $Page->articulo_inventario->caption() ?></span></td>
        <td data-name="articulo_inventario"<?= $Page->articulo_inventario->cellAttributes() ?>>
<span id="el_articulo_articulo_inventario" data-page="1">
<span<?= $Page->articulo_inventario->viewAttributes() ?>>
<?= $Page->articulo_inventario->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <tr id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_activo"><?= $Page->activo->caption() ?></span></td>
        <td data-name="activo"<?= $Page->activo->cellAttributes() ?>>
<span id="el_articulo_activo" data-page="1">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_articulo2" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->cantidad_minima->Visible) { // cantidad_minima ?>
    <tr id="r_cantidad_minima"<?= $Page->cantidad_minima->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_minima"><?= $Page->cantidad_minima->caption() ?></span></td>
        <td data-name="cantidad_minima"<?= $Page->cantidad_minima->cellAttributes() ?>>
<span id="el_articulo_cantidad_minima" data-page="2">
<span<?= $Page->cantidad_minima->viewAttributes() ?>>
<?= $Page->cantidad_minima->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_maxima->Visible) { // cantidad_maxima ?>
    <tr id="r_cantidad_maxima"<?= $Page->cantidad_maxima->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_maxima"><?= $Page->cantidad_maxima->caption() ?></span></td>
        <td data-name="cantidad_maxima"<?= $Page->cantidad_maxima->cellAttributes() ?>>
<span id="el_articulo_cantidad_maxima" data-page="2">
<span<?= $Page->cantidad_maxima->viewAttributes() ?>>
<?= $Page->cantidad_maxima->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
    <tr id="r_cantidad_en_mano"<?= $Page->cantidad_en_mano->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_mano"><?= $Page->cantidad_en_mano->caption() ?></span></td>
        <td data-name="cantidad_en_mano"<?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_mano" data-page="2">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_almacenes->Visible) { // cantidad_en_almacenes ?>
    <tr id="r_cantidad_en_almacenes"<?= $Page->cantidad_en_almacenes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_almacenes"><?= $Page->cantidad_en_almacenes->caption() ?></span></td>
        <td data-name="cantidad_en_almacenes"<?= $Page->cantidad_en_almacenes->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_almacenes" data-page="2">
<span<?= $Page->cantidad_en_almacenes->viewAttributes() ?>>
<?= $Page->cantidad_en_almacenes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
    <tr id="r_cantidad_en_pedido"<?= $Page->cantidad_en_pedido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_pedido"><?= $Page->cantidad_en_pedido->caption() ?></span></td>
        <td data-name="cantidad_en_pedido"<?= $Page->cantidad_en_pedido->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_pedido" data-page="2">
<span<?= $Page->cantidad_en_pedido->viewAttributes() ?>>
<?= $Page->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
    <tr id="r_cantidad_en_transito"<?= $Page->cantidad_en_transito->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_en_transito"><?= $Page->cantidad_en_transito->caption() ?></span></td>
        <td data-name="cantidad_en_transito"<?= $Page->cantidad_en_transito->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_transito" data-page="2">
<span<?= $Page->cantidad_en_transito->viewAttributes() ?>>
<?= $Page->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
    <tr id="r_ultimo_costo"<?= $Page->ultimo_costo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_ultimo_costo"><?= $Page->ultimo_costo->caption() ?></span></td>
        <td data-name="ultimo_costo"<?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el_articulo_ultimo_costo" data-page="2">
<span<?= $Page->ultimo_costo->viewAttributes() ?>>
<?= $Page->ultimo_costo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <tr id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_descuento"><?= $Page->descuento->caption() ?></span></td>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<span id="el_articulo_descuento" data-page="2">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <tr id="r_precio"<?= $Page->precio->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_precio"><?= $Page->precio->caption() ?></span></td>
        <td data-name="precio"<?= $Page->precio->cellAttributes() ?>>
<span id="el_articulo_precio" data-page="2">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <tr id="r_alicuota"<?= $Page->alicuota->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_alicuota"><?= $Page->alicuota->caption() ?></span></td>
        <td data-name="alicuota"<?= $Page->alicuota->cellAttributes() ?>>
<span id="el_articulo_alicuota" data-page="2">
<span<?= $Page->alicuota->viewAttributes() ?>>
<?= $Page->alicuota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_articulo3" role="tabpanel"><!-- multi-page .tab-pane -->
<?php } ?>
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->foto->Visible) { // foto ?>
    <tr id="r_foto"<?= $Page->foto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_foto"><?= $Page->foto->caption() ?></span></td>
        <td data-name="foto"<?= $Page->foto->cellAttributes() ?>>
<span id="el_articulo_foto" data-page="3">
<span>
<?= GetFileViewTag($Page->foto, $Page->foto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->categoria->Visible) { // categoria ?>
    <tr id="r_categoria"<?= $Page->categoria->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_categoria"><?= $Page->categoria->caption() ?></span></td>
        <td data-name="categoria"<?= $Page->categoria->cellAttributes() ?>>
<span id="el_articulo_categoria" data-page="3">
<span<?= $Page->categoria->viewAttributes() ?>>
<?= $Page->categoria->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <tr id="r_lista_pedido"<?= $Page->lista_pedido->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></td>
        <td data-name="lista_pedido"<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_articulo_lista_pedido" data-page="3">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->unidad_medida_defecto->Visible) { // unidad_medida_defecto ?>
    <tr id="r_unidad_medida_defecto"<?= $Page->unidad_medida_defecto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_unidad_medida_defecto"><?= $Page->unidad_medida_defecto->caption() ?></span></td>
        <td data-name="unidad_medida_defecto"<?= $Page->unidad_medida_defecto->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_defecto" data-page="3">
<span<?= $Page->unidad_medida_defecto->viewAttributes() ?>>
<?= $Page->unidad_medida_defecto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_por_unidad_medida->Visible) { // cantidad_por_unidad_medida ?>
    <tr id="r_cantidad_por_unidad_medida"<?= $Page->cantidad_por_unidad_medida->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_articulo_cantidad_por_unidad_medida"><?= $Page->cantidad_por_unidad_medida->caption() ?></span></td>
        <td data-name="cantidad_por_unidad_medida"<?= $Page->cantidad_por_unidad_medida->cellAttributes() ?>>
<span id="el_articulo_cantidad_por_unidad_medida" data-page="3">
<span<?= $Page->cantidad_por_unidad_medida->viewAttributes() ?>>
<?= $Page->cantidad_por_unidad_medida->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php if (!$Page->isExport()) { ?>
        </div><!-- /multi-page .tab-pane -->
<?php } ?>
<?php if (!$Page->isExport()) { ?>
    </div>
</div>
</div>
<?php } ?>
<?php
    if (in_array("articulo_unidad_medida", explode(",", $Page->getCurrentDetailTable())) && $articulo_unidad_medida->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("articulo_unidad_medida", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ArticuloUnidadMedidaGrid.php" ?>
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
