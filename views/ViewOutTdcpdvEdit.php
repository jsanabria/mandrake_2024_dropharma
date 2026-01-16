<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewOutTdcpdvEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fview_out_tdcpdvedit" id="fview_out_tdcpdvedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_out_tdcpdv: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fview_out_tdcpdvedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_out_tdcpdvedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid],
            ["consignacion", [fields.consignacion.visible && fields.consignacion.required ? ew.Validators.required(fields.consignacion.caption) : null], fields.consignacion.isInvalid],
            ["asesor_asignado", [fields.asesor_asignado.visible && fields.asesor_asignado.required ? ew.Validators.required(fields.asesor_asignado.caption) : null], fields.asesor_asignado.isInvalid]
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
            "estatus": <?= $Page->estatus->toClientList($Page) ?>,
            "dias_credito": <?= $Page->dias_credito->toClientList($Page) ?>,
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
            "asesor_asignado": <?= $Page->asesor_asignado->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="view_out_tdcpdv">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcpdv" data-field="x_nro_documento" data-hidden="1" name="x_nro_documento" id="x_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcpdv" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->cliente->getDisplayValue($Page->cliente->EditValue) ?></span></span>
<input type="hidden" data-table="view_out_tdcpdv" data-field="x_cliente" data-hidden="1" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estatus->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-select ew-select<?= $Page->estatus->isInvalidClass() ?>"
        <?php if (!$Page->estatus->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcpdvedit_x_estatus"
        <?php } ?>
        data-table="view_out_tdcpdv"
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
loadjs.ready("fview_out_tdcpdvedit", function() {
    var options = { name: "x_estatus", selectId: "fview_out_tdcpdvedit_x_estatus" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcpdvedit.lists.estatus?.lookupOptions.length) {
        options.data = { id: "x_estatus", form: "fview_out_tdcpdvedit" };
    } else {
        options.ajax = { id: "x_estatus", form: "fview_out_tdcpdvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcpdv.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_nota">
<textarea data-table="view_out_tdcpdv" data-field="x_nota" name="x_nota" id="x_nota" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_dias_credito" for="x_dias_credito" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_dias_credito">
    <select
        id="x_dias_credito"
        name="x_dias_credito"
        class="form-select ew-select<?= $Page->dias_credito->isInvalidClass() ?>"
        <?php if (!$Page->dias_credito->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcpdvedit_x_dias_credito"
        <?php } ?>
        data-table="view_out_tdcpdv"
        data-field="x_dias_credito"
        data-value-separator="<?= $Page->dias_credito->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->dias_credito->getPlaceHolder()) ?>"
        <?= $Page->dias_credito->editAttributes() ?>>
        <?= $Page->dias_credito->selectOptionListHtml("x_dias_credito") ?>
    </select>
    <?= $Page->dias_credito->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->dias_credito->getErrorMessage() ?></div>
<?= $Page->dias_credito->Lookup->getParamTag($Page, "p_x_dias_credito") ?>
<?php if (!$Page->dias_credito->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_out_tdcpdvedit", function() {
    var options = { name: "x_dias_credito", selectId: "fview_out_tdcpdvedit_x_dias_credito" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcpdvedit.lists.dias_credito?.lookupOptions.length) {
        options.data = { id: "x_dias_credito", form: "fview_out_tdcpdvedit" };
    } else {
        options.ajax = { id: "x_dias_credito", form: "fview_out_tdcpdvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcpdv.fields.dias_credito.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <div id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_consignacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->consignacion->caption() ?><?= $Page->consignacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_consignacion">
<template id="tp_x_consignacion">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="view_out_tdcpdv" data-field="x_consignacion" name="x_consignacion" id="x_consignacion"<?= $Page->consignacion->editAttributes() ?>>
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
    data-table="view_out_tdcpdv"
    data-field="x_consignacion"
    data-value-separator="<?= $Page->consignacion->displayValueSeparatorAttribute() ?>"
    <?= $Page->consignacion->editAttributes() ?>></selection-list>
<?= $Page->consignacion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->consignacion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->asesor_asignado->Visible) { // asesor_asignado ?>
    <div id="r_asesor_asignado"<?= $Page->asesor_asignado->rowAttributes() ?>>
        <label id="elh_view_out_tdcpdv_asesor_asignado" for="x_asesor_asignado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->asesor_asignado->caption() ?><?= $Page->asesor_asignado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->asesor_asignado->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_asesor_asignado">
    <select
        id="x_asesor_asignado"
        name="x_asesor_asignado"
        class="form-control ew-select<?= $Page->asesor_asignado->isInvalidClass() ?>"
        data-select2-id="fview_out_tdcpdvedit_x_asesor_asignado"
        data-table="view_out_tdcpdv"
        data-field="x_asesor_asignado"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->asesor_asignado->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->asesor_asignado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->asesor_asignado->getPlaceHolder()) ?>"
        <?= $Page->asesor_asignado->editAttributes() ?>>
        <?= $Page->asesor_asignado->selectOptionListHtml("x_asesor_asignado") ?>
    </select>
    <?= $Page->asesor_asignado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->asesor_asignado->getErrorMessage() ?></div>
<?= $Page->asesor_asignado->Lookup->getParamTag($Page, "p_x_asesor_asignado") ?>
<script>
loadjs.ready("fview_out_tdcpdvedit", function() {
    var options = { name: "x_asesor_asignado", selectId: "fview_out_tdcpdvedit_x_asesor_asignado" };
    if (fview_out_tdcpdvedit.lists.asesor_asignado?.lookupOptions.length) {
        options.data = { id: "x_asesor_asignado", form: "fview_out_tdcpdvedit" };
    } else {
        options.ajax = { id: "x_asesor_asignado", form: "fview_out_tdcpdvedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.view_out_tdcpdv.fields.asesor_asignado.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="view_out_tdcpdv" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("view_out", explode(",", $Page->getCurrentDetailTable())) && $view_out->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("view_out", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ViewOutGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fview_out_tdcpdvedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fview_out_tdcpdvedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("view_out_tdcpdv");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
