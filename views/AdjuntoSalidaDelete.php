<?php

namespace PHPMaker2024\mandrake;

// Page object
$AdjuntoSalidaDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { adjunto_salida: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fadjunto_salidadelete;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fadjunto_salidadelete")
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
<form name="fadjunto_salidadelete" id="fadjunto_salidadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="adjunto_salida">
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
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_adjunto_salida_id" class="adjunto_salida_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->salida->Visible) { // salida ?>
        <th class="<?= $Page->salida->headerCellClass() ?>"><span id="elh_adjunto_salida_salida" class="adjunto_salida_salida"><?= $Page->salida->caption() ?></span></th>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
        <th class="<?= $Page->foto->headerCellClass() ?>"><span id="elh_adjunto_salida_foto" class="adjunto_salida_foto"><?= $Page->foto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <th class="<?= $Page->nota->headerCellClass() ?>"><span id="elh_adjunto_salida_nota" class="adjunto_salida_nota"><?= $Page->nota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><span id="elh_adjunto_salida_fecha" class="adjunto_salida_fecha"><?= $Page->fecha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
        <th class="<?= $Page->usuario->headerCellClass() ?>"><span id="elh_adjunto_salida_usuario" class="adjunto_salida_usuario"><?= $Page->usuario->caption() ?></span></th>
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
<?php if ($Page->salida->Visible) { // salida ?>
        <td<?= $Page->salida->cellAttributes() ?>>
<span id="">
<span<?= $Page->salida->viewAttributes() ?>>
<?= $Page->salida->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->foto->Visible) { // foto ?>
        <td<?= $Page->foto->cellAttributes() ?>>
<span id="">
<span<?= $Page->foto->viewAttributes() ?>>
<?= $Page->foto->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
        <td<?= $Page->nota->cellAttributes() ?>>
<span id="">
<span<?= $Page->nota->viewAttributes() ?>>
<?= $Page->nota->getViewValue() ?></span>
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
