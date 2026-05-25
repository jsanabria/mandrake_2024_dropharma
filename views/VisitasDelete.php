<?php

namespace PHPMaker2024\mandrake;

// Page object
$VisitasDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { visitas: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fvisitasdelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fvisitasdelete")
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
<form name="fvisitasdelete" id="fvisitasdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="visitas">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_visitas_id" class="visitas_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_visitas_nombre" class="visitas_nombre"><?= $Page->nombre->caption() ?></span></th>
<?php } ?>
<?php if ($Page->apellido->Visible) { // apellido ?>
        <th class="<?= $Page->apellido->headerCellClass() ?>"><span id="elh_visitas_apellido" class="visitas_apellido"><?= $Page->apellido->caption() ?></span></th>
<?php } ?>
<?php if ($Page->correo->Visible) { // correo ?>
        <th class="<?= $Page->correo->headerCellClass() ?>"><span id="elh_visitas_correo" class="visitas_correo"><?= $Page->correo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->telefono->Visible) { // telefono ?>
        <th class="<?= $Page->telefono->headerCellClass() ?>"><span id="elh_visitas_telefono" class="visitas_telefono"><?= $Page->telefono->caption() ?></span></th>
<?php } ?>
<?php if ($Page->producto->Visible) { // producto ?>
        <th class="<?= $Page->producto->headerCellClass() ?>"><span id="elh_visitas_producto" class="visitas_producto"><?= $Page->producto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><span id="elh_visitas_referencia" class="visitas_referencia"><?= $Page->referencia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->comentario->Visible) { // comentario ?>
        <th class="<?= $Page->comentario->headerCellClass() ?>"><span id="elh_visitas_comentario" class="visitas_comentario"><?= $Page->comentario->caption() ?></span></th>
<?php } ?>
<?php if ($Page->seguimiento->Visible) { // seguimiento ?>
        <th class="<?= $Page->seguimiento->headerCellClass() ?>"><span id="elh_visitas_seguimiento" class="visitas_seguimiento"><?= $Page->seguimiento->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_visitas_fecha" class="visitas_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
        <th class="<?= $Page->fecha_registro->headerCellClass() ?>"><span id="elh_visitas_fecha_registro" class="visitas_fecha_registro"><?= $Page->fecha_registro->caption() ?></span></th>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <th class="<?= $Page->usuario->headerCellClass() ?>"><span id="elh_visitas_usuario" class="visitas_usuario"><?= $Page->usuario->caption() ?></span></th>
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
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td<?= $Page->nombre->cellAttributes() ?>>
<span id="">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->apellido->Visible) { // apellido ?>
        <td<?= $Page->apellido->cellAttributes() ?>>
<span id="">
<span<?= $Page->apellido->viewAttributes() ?>>
<?= $Page->apellido->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->correo->Visible) { // correo ?>
        <td<?= $Page->correo->cellAttributes() ?>>
<span id="">
<span<?= $Page->correo->viewAttributes() ?>>
<?= $Page->correo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->telefono->Visible) { // telefono ?>
        <td<?= $Page->telefono->cellAttributes() ?>>
<span id="">
<span<?= $Page->telefono->viewAttributes() ?>>
<?= $Page->telefono->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->producto->Visible) { // producto ?>
        <td<?= $Page->producto->cellAttributes() ?>>
<span id="">
<span<?= $Page->producto->viewAttributes() ?>>
<?= $Page->producto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <td<?= $Page->referencia->cellAttributes() ?>>
<span id="">
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->comentario->Visible) { // comentario ?>
        <td<?= $Page->comentario->cellAttributes() ?>>
<span id="">
<span<?= $Page->comentario->viewAttributes() ?>>
<?= $Page->comentario->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->seguimiento->Visible) { // seguimiento ?>
        <td<?= $Page->seguimiento->cellAttributes() ?>>
<span id="">
<span<?= $Page->seguimiento->viewAttributes() ?>>
<?= $Page->seguimiento->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <td<?= $Page->fecha->cellAttributes() ?>>
<span id="">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->fecha_registro->Visible) { // fecha_registro ?>
        <td<?= $Page->fecha_registro->cellAttributes() ?>>
<span id="">
<span<?= $Page->fecha_registro->viewAttributes() ?>>
<?= $Page->fecha_registro->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <td<?= $Page->usuario->cellAttributes() ?>>
<span id="">
<span<?= $Page->usuario->viewAttributes() ?>>
<?= $Page->usuario->getViewValue() ?></span>
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
