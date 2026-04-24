<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidosDetallesOnlineBitacoraView = &$Page;
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
<form name="fpedidos_detalles_online_bitacoraview" id="fpedidos_detalles_online_bitacoraview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pedidos_detalles_online_bitacora: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fpedidos_detalles_online_bitacoraview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpedidos_detalles_online_bitacoraview")
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
<input type="hidden" name="t" value="pedidos_detalles_online_bitacora">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->id->Visible) { // id ?>
    <tr id="r_id"<?= $Page->id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidos_detalles_online_bitacora_id"><?= $Page->id->caption() ?></span></td>
        <td data-name="id"<?= $Page->id->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_id">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <tr id="r_id_documento"<?= $Page->id_documento->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidos_detalles_online_bitacora_id_documento"><?= $Page->id_documento->caption() ?></span></td>
        <td data-name="id_documento"<?= $Page->id_documento->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_id_documento">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <tr id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidos_detalles_online_bitacora_fabricante"><?= $Page->fabricante->caption() ?></span></td>
        <td data-name="fabricante"<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_fabricante">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <tr id="r_articulo"<?= $Page->articulo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidos_detalles_online_bitacora_articulo"><?= $Page->articulo->caption() ?></span></td>
        <td data-name="articulo"<?= $Page->articulo->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidad_solicitada->Visible) { // cantidad_solicitada ?>
    <tr id="r_cantidad_solicitada"<?= $Page->cantidad_solicitada->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidos_detalles_online_bitacora_cantidad_solicitada"><?= $Page->cantidad_solicitada->caption() ?></span></td>
        <td data-name="cantidad_solicitada"<?= $Page->cantidad_solicitada->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_cantidad_solicitada">
<span<?= $Page->cantidad_solicitada->viewAttributes() ?>>
<?= $Page->cantidad_solicitada->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->cantidadasignada->Visible) { // cantidadasignada ?>
    <tr id="r_cantidadasignada"<?= $Page->cantidadasignada->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pedidos_detalles_online_bitacora_cantidadasignada"><?= $Page->cantidadasignada->caption() ?></span></td>
        <td data-name="cantidadasignada"<?= $Page->cantidadasignada->cellAttributes() ?>>
<span id="el_pedidos_detalles_online_bitacora_cantidadasignada">
<span<?= $Page->cantidadasignada->viewAttributes() ?>>
<?= $Page->cantidadasignada->getViewValue() ?></span>
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
