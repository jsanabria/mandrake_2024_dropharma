<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotificacionesView = &$Page;
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
<form name="fnotificacionesview" id="fnotificacionesview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { notificaciones: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fnotificacionesview;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fnotificacionesview")
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
<input type="hidden" name="t" value="notificaciones">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->Nnotificaciones->Visible) { // Nnotificaciones ?>
    <tr id="r_Nnotificaciones"<?= $Page->Nnotificaciones->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_Nnotificaciones"><?= $Page->Nnotificaciones->caption() ?></span></td>
        <td data-name="Nnotificaciones"<?= $Page->Nnotificaciones->cellAttributes() ?>>
<span id="el_notificaciones_Nnotificaciones">
<span<?= $Page->Nnotificaciones->viewAttributes() ?>>
<?= $Page->Nnotificaciones->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notificar->Visible) { // notificar ?>
    <tr id="r_notificar"<?= $Page->notificar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_notificar"><?= $Page->notificar->caption() ?></span></td>
        <td data-name="notificar"<?= $Page->notificar->cellAttributes() ?>>
<span id="el_notificaciones_notificar">
<span<?= $Page->notificar->viewAttributes() ?>>
<?= $Page->notificar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->asunto->Visible) { // asunto ?>
    <tr id="r_asunto"<?= $Page->asunto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_asunto"><?= $Page->asunto->caption() ?></span></td>
        <td data-name="asunto"<?= $Page->asunto->cellAttributes() ?>>
<span id="el_notificaciones_asunto">
<span<?= $Page->asunto->viewAttributes() ?>>
<?= $Page->asunto->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notificacion->Visible) { // notificacion ?>
    <tr id="r_notificacion"<?= $Page->notificacion->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_notificacion"><?= $Page->notificacion->caption() ?></span></td>
        <td data-name="notificacion"<?= $Page->notificacion->cellAttributes() ?>>
<span id="el_notificaciones_notificacion">
<span<?= $Page->notificacion->viewAttributes() ?>>
<?= $Page->notificacion->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notificados->Visible) { // notificados ?>
    <tr id="r_notificados"<?= $Page->notificados->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_notificados"><?= $Page->notificados->caption() ?></span></td>
        <td data-name="notificados"<?= $Page->notificados->cellAttributes() ?>>
<span id="el_notificaciones_notificados">
<span<?= $Page->notificados->viewAttributes() ?>>
<?= $Page->notificados->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->notificados_efectivos->Visible) { // notificados_efectivos ?>
    <tr id="r_notificados_efectivos"<?= $Page->notificados_efectivos->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_notificados_efectivos"><?= $Page->notificados_efectivos->caption() ?></span></td>
        <td data-name="notificados_efectivos"<?= $Page->notificados_efectivos->cellAttributes() ?>>
<span id="el_notificaciones_notificados_efectivos">
<span<?= $Page->notificados_efectivos->viewAttributes() ?>>
<?= $Page->notificados_efectivos->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username"<?= $Page->_username->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username"<?= $Page->_username->cellAttributes() ?>>
<span id="el_notificaciones__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <tr id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_fecha"><?= $Page->fecha->caption() ?></span></td>
        <td data-name="fecha"<?= $Page->fecha->cellAttributes() ?>>
<span id="el_notificaciones_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->enviado->Visible) { // enviado ?>
    <tr id="r_enviado"<?= $Page->enviado->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_enviado"><?= $Page->enviado->caption() ?></span></td>
        <td data-name="enviado"<?= $Page->enviado->cellAttributes() ?>>
<span id="el_notificaciones_enviado">
<span<?= $Page->enviado->viewAttributes() ?>>
<?= $Page->enviado->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->adjunto->Visible) { // adjunto ?>
    <tr id="r_adjunto"<?= $Page->adjunto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_notificaciones_adjunto"><?= $Page->adjunto->caption() ?></span></td>
        <td data-name="adjunto"<?= $Page->adjunto->cellAttributes() ?>>
<span id="el_notificaciones_adjunto">
<span>
<?= GetFileViewTag($Page->adjunto, $Page->adjunto->getViewValue(), false) ?>
</span>
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
