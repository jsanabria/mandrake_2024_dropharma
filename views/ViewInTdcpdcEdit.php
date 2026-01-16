<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewInTdcpdcEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fview_in_tdcpdcedit" id="fview_in_tdcpdcedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_in_tdcpdc: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fview_in_tdcpdcedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_in_tdcpdcedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
            ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["consignacion", [fields.consignacion.visible && fields.consignacion.required ? ew.Validators.required(fields.consignacion.caption) : null], fields.consignacion.isInvalid],
            ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
            ["archivo_pedido", [fields.archivo_pedido.visible && fields.archivo_pedido.required ? ew.Validators.fileRequired(fields.archivo_pedido.caption) : null], fields.archivo_pedido.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
            "estatus": <?= $Page->estatus->toClientList($Page) ?>,
        })
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="view_in_tdcpdc">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
<input type="hidden" data-table="view_in_tdcpdc" data-field="x_nro_documento" data-hidden="1" name="x_nro_documento" id="x_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
<input type="hidden" data-table="view_in_tdcpdc" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor"<?= $Page->proveedor->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->proveedor->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_proveedor">
<span<?= $Page->proveedor->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->proveedor->getDisplayValue($Page->proveedor->EditValue) ?></span></span>
<input type="hidden" data-table="view_in_tdcpdc" data-field="x_proveedor" data-hidden="1" name="x_proveedor" id="x_proveedor" value="<?= HtmlEncode($Page->proveedor->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_nota">
<textarea data-table="view_in_tdcpdc" data-field="x_nota" name="x_nota" id="x_nota" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <div id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_consignacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->consignacion->caption() ?><?= $Page->consignacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_consignacion">
<template id="tp_x_consignacion">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="view_in_tdcpdc" data-field="x_consignacion" name="x_consignacion" id="x_consignacion"<?= $Page->consignacion->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_consignacion" class="ew-item-list"></div>
<selection-list hidden
    id="x_consignacion"
    name="x_consignacion"
    value="<?= HtmlEncode($Page->consignacion->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_consignacion"
    data-target="dsl_x_consignacion"
    data-repeatcolumn="5"
    class="form-control<?= $Page->consignacion->isInvalidClass() ?>"
    data-table="view_in_tdcpdc"
    data-field="x_consignacion"
    data-value-separator="<?= $Page->consignacion->displayValueSeparatorAttribute() ?>"
    <?= $Page->consignacion->editAttributes() ?>></selection-list>
<?= $Page->consignacion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->consignacion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estatus->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-select ew-select<?= $Page->estatus->isInvalidClass() ?>"
        <?php if (!$Page->estatus->IsNativeSelect) { ?>
        data-select2-id="fview_in_tdcpdcedit_x_estatus"
        <?php } ?>
        data-table="view_in_tdcpdc"
        data-field="x_estatus"
        data-value-separator="<?= $Page->estatus->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->estatus->getPlaceHolder()) ?>"
        <?= $Page->estatus->editAttributes() ?>>
        <?= $Page->estatus->selectOptionListHtml("x_estatus") ?>
    </select>
    <?= $Page->estatus->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->estatus->getErrorMessage() ?></div>
<?php if (!$Page->estatus->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_in_tdcpdcedit", function() {
    var options = { name: "x_estatus", selectId: "fview_in_tdcpdcedit_x_estatus" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_in_tdcpdcedit.lists.estatus?.lookupOptions.length) {
        options.data = { id: "x_estatus", form: "fview_in_tdcpdcedit" };
    } else {
        options.ajax = { id: "x_estatus", form: "fview_in_tdcpdcedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_in_tdcpdc.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->archivo_pedido->Visible) { // archivo_pedido ?>
    <div id="r_archivo_pedido"<?= $Page->archivo_pedido->rowAttributes() ?>>
        <label id="elh_view_in_tdcpdc_archivo_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->archivo_pedido->caption() ?><?= $Page->archivo_pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->archivo_pedido->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_archivo_pedido">
<div id="fd_x_archivo_pedido" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_archivo_pedido"
        name="x_archivo_pedido"
        class="form-control ew-file-input"
        title="<?= $Page->archivo_pedido->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="view_in_tdcpdc"
        data-field="x_archivo_pedido"
        data-size="255"
        data-accept-file-types="<?= $Page->archivo_pedido->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->archivo_pedido->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->archivo_pedido->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_archivo_pedido_help"
        <?= ($Page->archivo_pedido->ReadOnly || $Page->archivo_pedido->Disabled) ? " disabled" : "" ?>
        <?= $Page->archivo_pedido->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->archivo_pedido->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->archivo_pedido->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_archivo_pedido" id= "fn_x_archivo_pedido" value="<?= $Page->archivo_pedido->Upload->FileName ?>">
<input type="hidden" name="fa_x_archivo_pedido" id= "fa_x_archivo_pedido" value="<?= (Post("fa_x_archivo_pedido") == "0") ? "0" : "1" ?>">
<table id="ft_x_archivo_pedido" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="view_in_tdcpdc" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("view_in", explode(",", $Page->getCurrentDetailTable())) && $view_in->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("view_in", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ViewInGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fview_in_tdcpdcedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fview_in_tdcpdcedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("view_in_tdcpdc");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
