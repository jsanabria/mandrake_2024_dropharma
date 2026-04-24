<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidosDetallesOnlineBitacoraDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pedidos_detalles_online_bitacora: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fpedidos_detalles_online_bitacoradelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpedidos_detalles_online_bitacoradelete")
        .setPageId("delete")
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpedidos_detalles_online_bitacoradelete" id="fpedidos_detalles_online_bitacoradelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pedidos_detalles_online_bitacora">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->id->Visible) { // id ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_pedidos_detalles_online_bitacora_id" class="pedidos_detalles_online_bitacora_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <th class="<?= $Page->id_documento->headerCellClass() ?>"><span id="elh_pedidos_detalles_online_bitacora_id_documento" class="pedidos_detalles_online_bitacora_id_documento"><?= $Page->id_documento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_pedidos_detalles_online_bitacora_fabricante" class="pedidos_detalles_online_bitacora_fabricante"><?= $Page->fabricante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><span id="elh_pedidos_detalles_online_bitacora_articulo" class="pedidos_detalles_online_bitacora_articulo"><?= $Page->articulo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_solicitada->Visible) { // cantidad_solicitada ?>
        <th class="<?= $Page->cantidad_solicitada->headerCellClass() ?>"><span id="elh_pedidos_detalles_online_bitacora_cantidad_solicitada" class="pedidos_detalles_online_bitacora_cantidad_solicitada"><?= $Page->cantidad_solicitada->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidadasignada->Visible) { // cantidadasignada ?>
        <th class="<?= $Page->cantidadasignada->headerCellClass() ?>"><span id="elh_pedidos_detalles_online_bitacora_cantidadasignada" class="pedidos_detalles_online_bitacora_cantidadasignada"><?= $Page->cantidadasignada->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while ($Page->fetch()) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = RowType::VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->CurrentRow);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->id->Visible) { // id ?>
        <td<?= $Page->id->cellAttributes() ?>>
<span id="">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
        <td<?= $Page->id_documento->cellAttributes() ?>>
<span id="">
<span<?= $Page->id_documento->viewAttributes() ?>>
<?= $Page->id_documento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <td<?= $Page->fabricante->cellAttributes() ?>>
<span id="">
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <td<?= $Page->articulo->cellAttributes() ?>>
<span id="">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_solicitada->Visible) { // cantidad_solicitada ?>
        <td<?= $Page->cantidad_solicitada->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_solicitada->viewAttributes() ?>>
<?= $Page->cantidad_solicitada->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidadasignada->Visible) { // cantidadasignada ?>
        <td<?= $Page->cantidadasignada->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidadasignada->viewAttributes() ?>>
<?= $Page->cantidadasignada->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
$Page->Recordset?->free();
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
