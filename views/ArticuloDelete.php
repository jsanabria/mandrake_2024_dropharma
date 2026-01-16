<?php

namespace PHPMaker2024\mandrake;

// Page object
$ArticuloDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { articulo: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var farticulodelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("farticulodelete")
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
<form name="farticulodelete" id="farticulodelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo">
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
<?php if ($Page->foto->Visible) { // foto ?>
        <th class="<?= $Page->foto->headerCellClass() ?>"><span id="elh_articulo_foto" class="articulo_foto"><?= $Page->foto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><span id="elh_articulo_codigo" class="articulo_codigo"><?= $Page->codigo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <th class="<?= $Page->nombre_comercial->headerCellClass() ?>"><span id="elh_articulo_nombre_comercial" class="articulo_nombre_comercial"><?= $Page->nombre_comercial->caption() ?></span></th>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <th class="<?= $Page->principio_activo->headerCellClass() ?>"><span id="elh_articulo_principio_activo" class="articulo_principio_activo"><?= $Page->principio_activo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
        <th class="<?= $Page->presentacion->headerCellClass() ?>"><span id="elh_articulo_presentacion" class="articulo_presentacion"><?= $Page->presentacion->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><span id="elh_articulo_fabricante" class="articulo_fabricante"><?= $Page->fabricante->caption() ?></span></th>
<?php } ?>
<?php if ($Page->categoria->Visible) { // categoria ?>
        <th class="<?= $Page->categoria->headerCellClass() ?>"><span id="elh_articulo_categoria" class="articulo_categoria"><?= $Page->categoria->caption() ?></span></th>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <th class="<?= $Page->lista_pedido->headerCellClass() ?>"><span id="elh_articulo_lista_pedido" class="articulo_lista_pedido"><?= $Page->lista_pedido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <th class="<?= $Page->cantidad_en_mano->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_mano" class="articulo_cantidad_en_mano"><?= $Page->cantidad_en_mano->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_almacenes->Visible) { // cantidad_en_almacenes ?>
        <th class="<?= $Page->cantidad_en_almacenes->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_almacenes" class="articulo_cantidad_en_almacenes"><?= $Page->cantidad_en_almacenes->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <th class="<?= $Page->cantidad_en_pedido->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_pedido" class="articulo_cantidad_en_pedido"><?= $Page->cantidad_en_pedido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <th class="<?= $Page->cantidad_en_transito->headerCellClass() ?>"><span id="elh_articulo_cantidad_en_transito" class="articulo_cantidad_en_transito"><?= $Page->cantidad_en_transito->caption() ?></span></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><span id="elh_articulo_descuento" class="articulo_descuento"><?= $Page->descuento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><span id="elh_articulo_activo" class="articulo_activo"><?= $Page->activo->caption() ?></span></th>
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
<?php if ($Page->foto->Visible) { // foto ?>
        <td<?= $Page->foto->cellAttributes() ?>>
<span id="">
<span>
<?= GetFileViewTag($Page->foto, $Page->foto->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->codigo->Visible) { // codigo ?>
        <td<?= $Page->codigo->cellAttributes() ?>>
<span id="">
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
        <td<?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="">
<span<?= $Page->nombre_comercial->viewAttributes() ?>>
<?= $Page->nombre_comercial->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
        <td<?= $Page->principio_activo->cellAttributes() ?>>
<span id="">
<span<?= $Page->principio_activo->viewAttributes() ?>>
<?= $Page->principio_activo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
        <td<?= $Page->presentacion->cellAttributes() ?>>
<span id="">
<span<?= $Page->presentacion->viewAttributes() ?>>
<?= $Page->presentacion->getViewValue() ?></span>
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
<?php if ($Page->categoria->Visible) { // categoria ?>
        <td<?= $Page->categoria->cellAttributes() ?>>
<span id="">
<span<?= $Page->categoria->viewAttributes() ?>>
<?= $Page->categoria->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
        <td<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="">
<span<?= $Page->lista_pedido->viewAttributes() ?>>
<?= $Page->lista_pedido->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <td<?= $Page->cantidad_en_mano->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_en_mano->viewAttributes() ?>>
<?= $Page->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_almacenes->Visible) { // cantidad_en_almacenes ?>
        <td<?= $Page->cantidad_en_almacenes->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_en_almacenes->viewAttributes() ?>>
<?= $Page->cantidad_en_almacenes->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <td<?= $Page->cantidad_en_pedido->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_en_pedido->viewAttributes() ?>>
<?= $Page->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <td<?= $Page->cantidad_en_transito->cellAttributes() ?>>
<span id="">
<span<?= $Page->cantidad_en_transito->viewAttributes() ?>>
<?= $Page->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <td<?= $Page->descuento->cellAttributes() ?>>
<span id="">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <td<?= $Page->activo->cellAttributes() ?>>
<span id="">
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
