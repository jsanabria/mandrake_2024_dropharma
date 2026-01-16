<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewOutTdcnetEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fview_out_tdcnetedit" id="fview_out_tdcnetedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_out_tdcnet: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fview_out_tdcnetedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_out_tdcnetedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["estatus", [fields.estatus.visible && fields.estatus.required ? ew.Validators.required(fields.estatus.caption) : null], fields.estatus.isInvalid],
            ["bultos", [fields.bultos.visible && fields.bultos.required ? ew.Validators.required(fields.bultos.caption) : null, ew.Validators.integer], fields.bultos.isInvalid],
            ["consignacion", [fields.consignacion.visible && fields.consignacion.required ? ew.Validators.required(fields.consignacion.caption) : null], fields.consignacion.isInvalid],
            ["factura", [fields.factura.visible && fields.factura.required ? ew.Validators.required(fields.factura.caption) : null], fields.factura.isInvalid],
            ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
            ["telefono", [fields.telefono.visible && fields.telefono.required ? ew.Validators.required(fields.telefono.caption) : null], fields.telefono.isInvalid]
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
            "consignacion": <?= $Page->consignacion->toClientList($Page) ?>,
            "factura": <?= $Page->factura->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="view_out_tdcnet">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcnet_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcnet" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcnet_cliente">
<span<?= $Page->cliente->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->cliente->getDisplayValue($Page->cliente->EditValue) ?></span></span>
<input type="hidden" data-table="view_out_tdcnet" data-field="x_cliente" data-hidden="1" name="x_cliente" id="x_cliente" value="<?= HtmlEncode($Page->cliente->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcnet_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
<input type="hidden" data-table="view_out_tdcnet" data-field="x_nro_documento" data-hidden="1" name="x_nro_documento" id="x_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_view_out_tdcnet_nota">
<textarea data-table="view_out_tdcnet" data-field="x_nota" name="x_nota" id="x_nota" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estatus->Visible) { // estatus ?>
    <div id="r_estatus"<?= $Page->estatus->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_estatus" for="x_estatus" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estatus->caption() ?><?= $Page->estatus->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estatus->cellAttributes() ?>>
<span id="el_view_out_tdcnet_estatus">
    <select
        id="x_estatus"
        name="x_estatus"
        class="form-select ew-select<?= $Page->estatus->isInvalidClass() ?>"
        <?php if (!$Page->estatus->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcnetedit_x_estatus"
        <?php } ?>
        data-table="view_out_tdcnet"
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
loadjs.ready("fview_out_tdcnetedit", function() {
    var options = { name: "x_estatus", selectId: "fview_out_tdcnetedit_x_estatus" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcnetedit.lists.estatus?.lookupOptions.length) {
        options.data = { id: "x_estatus", form: "fview_out_tdcnetedit" };
    } else {
        options.ajax = { id: "x_estatus", form: "fview_out_tdcnetedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcnet.fields.estatus.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->bultos->Visible) { // bultos ?>
    <div id="r_bultos"<?= $Page->bultos->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_bultos" for="x_bultos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->bultos->caption() ?><?= $Page->bultos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->bultos->cellAttributes() ?>>
<span id="el_view_out_tdcnet_bultos">
<input type="<?= $Page->bultos->getInputTextType() ?>" name="x_bultos" id="x_bultos" data-table="view_out_tdcnet" data-field="x_bultos" value="<?= $Page->bultos->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->bultos->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->bultos->formatPattern()) ?>"<?= $Page->bultos->editAttributes() ?> aria-describedby="x_bultos_help">
<?= $Page->bultos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->bultos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->consignacion->Visible) { // consignacion ?>
    <div id="r_consignacion"<?= $Page->consignacion->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_consignacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->consignacion->caption() ?><?= $Page->consignacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->consignacion->cellAttributes() ?>>
<span id="el_view_out_tdcnet_consignacion">
<template id="tp_x_consignacion">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="view_out_tdcnet" data-field="x_consignacion" name="x_consignacion" id="x_consignacion"<?= $Page->consignacion->editAttributes() ?>>
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
    data-table="view_out_tdcnet"
    data-field="x_consignacion"
    data-value-separator="<?= $Page->consignacion->displayValueSeparatorAttribute() ?>"
    <?= $Page->consignacion->editAttributes() ?>></selection-list>
<?= $Page->consignacion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->consignacion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->factura->Visible) { // factura ?>
    <div id="r_factura"<?= $Page->factura->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_factura" for="x_factura" class="<?= $Page->LeftColumnClass ?>"><?= $Page->factura->caption() ?><?= $Page->factura->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->factura->cellAttributes() ?>>
<span id="el_view_out_tdcnet_factura">
    <select
        id="x_factura"
        name="x_factura"
        class="form-select ew-select<?= $Page->factura->isInvalidClass() ?>"
        <?php if (!$Page->factura->IsNativeSelect) { ?>
        data-select2-id="fview_out_tdcnetedit_x_factura"
        <?php } ?>
        data-table="view_out_tdcnet"
        data-field="x_factura"
        data-value-separator="<?= $Page->factura->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->factura->getPlaceHolder()) ?>"
        <?= $Page->factura->editAttributes() ?>>
        <?= $Page->factura->selectOptionListHtml("x_factura") ?>
    </select>
    <?= $Page->factura->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->factura->getErrorMessage() ?></div>
<?php if (!$Page->factura->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_out_tdcnetedit", function() {
    var options = { name: "x_factura", selectId: "fview_out_tdcnetedit_x_factura" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_out_tdcnetedit.lists.factura?.lookupOptions.length) {
        options.data = { id: "x_factura", form: "fview_out_tdcnetedit" };
    } else {
        options.ajax = { id: "x_factura", form: "fview_out_tdcnetedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_out_tdcnet.fields.factura.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif"<?= $Page->ci_rif->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_ci_rif" for="x_ci_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_view_out_tdcnet_ci_rif">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" name="x_ci_rif" id="x_ci_rif" data-table="view_out_tdcnet" data-field="x_ci_rif" value="<?= $Page->ci_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ci_rif->formatPattern()) ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_view_out_tdcnet_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="view_out_tdcnet" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion"<?= $Page->direccion->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_direccion" for="x_direccion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->direccion->cellAttributes() ?>>
<span id="el_view_out_tdcnet_direccion">
<input type="<?= $Page->direccion->getInputTextType() ?>" name="x_direccion" id="x_direccion" data-table="view_out_tdcnet" data-field="x_direccion" value="<?= $Page->direccion->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->direccion->formatPattern()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help">
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono->Visible) { // telefono ?>
    <div id="r_telefono"<?= $Page->telefono->rowAttributes() ?>>
        <label id="elh_view_out_tdcnet_telefono" for="x_telefono" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono->caption() ?><?= $Page->telefono->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->telefono->cellAttributes() ?>>
<span id="el_view_out_tdcnet_telefono">
<input type="<?= $Page->telefono->getInputTextType() ?>" name="x_telefono" id="x_telefono" data-table="view_out_tdcnet" data-field="x_telefono" value="<?= $Page->telefono->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono->formatPattern()) ?>"<?= $Page->telefono->editAttributes() ?> aria-describedby="x_telefono_help">
<?= $Page->telefono->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="view_out_tdcnet" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
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
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fview_out_tdcnetedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fview_out_tdcnetedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("view_out_tdcnet");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
