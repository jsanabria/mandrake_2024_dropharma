<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewFacturasAEntregarEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fview_facturas_a_entregaredit" id="fview_facturas_a_entregaredit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { view_facturas_a_entregar: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fview_facturas_a_entregaredit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fview_facturas_a_entregaredit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
            ["codcli", [fields.codcli.visible && fields.codcli.required ? ew.Validators.required(fields.codcli.caption) : null], fields.codcli.isInvalid],
            ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null], fields.fecha.isInvalid],
            ["total", [fields.total.visible && fields.total.required ? ew.Validators.required(fields.total.caption) : null], fields.total.isInvalid],
            ["entregado", [fields.entregado.visible && fields.entregado.required ? ew.Validators.required(fields.entregado.caption) : null], fields.entregado.isInvalid],
            ["fecha_entrega", [fields.fecha_entrega.visible && fields.fecha_entrega.required ? ew.Validators.required(fields.fecha_entrega.caption) : null, ew.Validators.datetime(fields.fecha_entrega.clientFormatPattern)], fields.fecha_entrega.isInvalid],
            ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid]
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
            "entregado": <?= $Page->entregado->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="view_facturas_a_entregar">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento"<?= $Page->tipo_documento->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_tipo_documento">
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->tipo_documento->getDisplayValue($Page->tipo_documento->EditValue) ?></span></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_tipo_documento" data-hidden="1" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codcli->Visible) { // codcli ?>
    <div id="r_codcli"<?= $Page->codcli->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_codcli" for="x_codcli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codcli->caption() ?><?= $Page->codcli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codcli->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_codcli">
<span<?= $Page->codcli->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->codcli->getDisplayValue($Page->codcli->EditValue) ?></span></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_codcli" data-hidden="1" name="x_codcli" id="x_codcli" value="<?= HtmlEncode($Page->codcli->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento"<?= $Page->nro_documento->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_nro_documento">
<span<?= $Page->nro_documento->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->nro_documento->getDisplayValue($Page->nro_documento->EditValue))) ?>"></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_nro_documento" data-hidden="1" name="x_nro_documento" id="x_nro_documento" value="<?= HtmlEncode($Page->nro_documento->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_fecha">
<span<?= $Page->fecha->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha->getDisplayValue($Page->fecha->EditValue))) ?>"></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_fecha" data-hidden="1" name="x_fecha" id="x_fecha" value="<?= HtmlEncode($Page->fecha->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->total->Visible) { // total ?>
    <div id="r_total"<?= $Page->total->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_total" for="x_total" class="<?= $Page->LeftColumnClass ?>"><?= $Page->total->caption() ?><?= $Page->total->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->total->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_total">
<span<?= $Page->total->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->total->getDisplayValue($Page->total->EditValue))) ?>"></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_total" data-hidden="1" name="x_total" id="x_total" value="<?= HtmlEncode($Page->total->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->entregado->Visible) { // entregado ?>
    <div id="r_entregado"<?= $Page->entregado->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_entregado" for="x_entregado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->entregado->caption() ?><?= $Page->entregado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->entregado->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_entregado">
    <select
        id="x_entregado"
        name="x_entregado"
        class="form-select ew-select<?= $Page->entregado->isInvalidClass() ?>"
        <?php if (!$Page->entregado->IsNativeSelect) { ?>
        data-select2-id="fview_facturas_a_entregaredit_x_entregado"
        <?php } ?>
        data-table="view_facturas_a_entregar"
        data-field="x_entregado"
        data-value-separator="<?= $Page->entregado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->entregado->getPlaceHolder()) ?>"
        <?= $Page->entregado->editAttributes() ?>>
        <?= $Page->entregado->selectOptionListHtml("x_entregado") ?>
    </select>
    <?= $Page->entregado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->entregado->getErrorMessage() ?></div>
<?php if (!$Page->entregado->IsNativeSelect) { ?>
<script>
loadjs.ready("fview_facturas_a_entregaredit", function() {
    var options = { name: "x_entregado", selectId: "fview_facturas_a_entregaredit_x_entregado" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fview_facturas_a_entregaredit.lists.entregado?.lookupOptions.length) {
        options.data = { id: "x_entregado", form: "fview_facturas_a_entregaredit" };
    } else {
        options.ajax = { id: "x_entregado", form: "fview_facturas_a_entregaredit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.view_facturas_a_entregar.fields.entregado.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_entrega->Visible) { // fecha_entrega ?>
    <div id="r_fecha_entrega"<?= $Page->fecha_entrega->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_fecha_entrega" for="x_fecha_entrega" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_entrega->caption() ?><?= $Page->fecha_entrega->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_entrega->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_fecha_entrega">
<input type="<?= $Page->fecha_entrega->getInputTextType() ?>" name="x_fecha_entrega" id="x_fecha_entrega" data-table="view_facturas_a_entregar" data-field="x_fecha_entrega" value="<?= $Page->fecha_entrega->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha_entrega->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_entrega->formatPattern()) ?>"<?= $Page->fecha_entrega->editAttributes() ?> aria-describedby="x_fecha_entrega_help">
<?= $Page->fecha_entrega->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_entrega->getErrorMessage() ?></div>
<?php if (!$Page->fecha_entrega->ReadOnly && !$Page->fecha_entrega->Disabled && !isset($Page->fecha_entrega->EditAttrs["readonly"]) && !isset($Page->fecha_entrega->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fview_facturas_a_entregaredit", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("fview_facturas_a_entregaredit", "x_fecha_entrega", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <label id="elh_view_facturas_a_entregar_dias_credito" for="x_dias_credito" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->dias_credito->cellAttributes() ?>>
<span id="el_view_facturas_a_entregar_dias_credito">
<span<?= $Page->dias_credito->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->dias_credito->getDisplayValue($Page->dias_credito->EditValue))) ?>"></span>
<input type="hidden" data-table="view_facturas_a_entregar" data-field="x_dias_credito" data-hidden="1" name="x_dias_credito" id="x_dias_credito" value="<?= HtmlEncode($Page->dias_credito->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fview_facturas_a_entregaredit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fview_facturas_a_entregaredit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("view_facturas_a_entregar");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
