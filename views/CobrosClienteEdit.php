<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcobros_clienteedit" id="fcobros_clienteedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cobros_cliente: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcobros_clienteedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_clienteedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["id_documento", [fields.id_documento.visible && fields.id_documento.required ? ew.Validators.required(fields.id_documento.caption) : null, ew.Validators.integer], fields.id_documento.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
            ["monto_recibido", [fields.monto_recibido.visible && fields.monto_recibido.required ? ew.Validators.required(fields.monto_recibido.caption) : null, ew.Validators.float], fields.monto_recibido.isInvalid],
            ["tasa_cambio", [fields.tasa_cambio.visible && fields.tasa_cambio.required ? ew.Validators.required(fields.tasa_cambio.caption) : null, ew.Validators.float], fields.tasa_cambio.isInvalid],
            ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
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
            "cliente": <?= $Page->cliente->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "banco": <?= $Page->banco->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="cobros_cliente">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_cobros_cliente_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
    <select
        id="x_cliente"
        name="x_cliente"
        class="form-control ew-select<?= $Page->cliente->isInvalidClass() ?>"
        data-select2-id="fcobros_clienteedit_x_cliente"
        data-table="cobros_cliente"
        data-field="x_cliente"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cliente->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"
        <?= $Page->cliente->editAttributes() ?>>
        <?= $Page->cliente->selectOptionListHtml("x_cliente") ?>
    </select>
    <?= $Page->cliente->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<script>
loadjs.ready("fcobros_clienteedit", function() {
    var options = { name: "x_cliente", selectId: "fcobros_clienteedit_x_cliente" };
    if (fcobros_clienteedit.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fcobros_clienteedit" };
    } else {
        options.ajax = { id: "x_cliente", form: "fcobros_clienteedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cobros_cliente.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_documento->Visible) { // id_documento ?>
    <div id="r_id_documento"<?= $Page->id_documento->rowAttributes() ?>>
        <label id="elh_cobros_cliente_id_documento" for="x_id_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_documento->caption() ?><?= $Page->id_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_id_documento">
<input type="<?= $Page->id_documento->getInputTextType() ?>" name="x_id_documento" id="x_id_documento" data-table="cobros_cliente" data-field="x_id_documento" value="<?= $Page->id_documento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->id_documento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id_documento->formatPattern()) ?>"<?= $Page->id_documento->editAttributes() ?> aria-describedby="x_id_documento_help">
<?= $Page->id_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_cobros_cliente_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="cobros_cliente" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcobros_clienteedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fcobros_clienteedit", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_cobros_cliente_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fcobros_clienteedit_x_moneda"
        <?php } ?>
        data-table="cobros_cliente"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<?php if (!$Page->moneda->IsNativeSelect) { ?>
<script>
loadjs.ready("fcobros_clienteedit", function() {
    var options = { name: "x_moneda", selectId: "fcobros_clienteedit_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcobros_clienteedit.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fcobros_clienteedit" };
    } else {
        options.ajax = { id: "x_moneda", form: "fcobros_clienteedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cobros_cliente.fields.moneda.selectOptions);
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
        <label id="elh_cobros_cliente_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_cobros_cliente_nota">
<textarea data-table="cobros_cliente" data-field="x_nota" name="x_nota" id="x_nota" cols="30" rows="3" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help"><?= $Page->nota->EditValue ?></textarea>
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <label id="elh_cobros_cliente_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->referencia->cellAttributes() ?>>
<span id="el_cobros_cliente_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" name="x_referencia" id="x_referencia" data-table="cobros_cliente" data-field="x_referencia" value="<?= $Page->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->referencia->formatPattern()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco"<?= $Page->banco->rowAttributes() ?>>
        <label id="elh_cobros_cliente_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco->cellAttributes() ?>>
<span id="el_cobros_cliente_banco">
    <select
        id="x_banco"
        name="x_banco"
        class="form-select ew-select<?= $Page->banco->isInvalidClass() ?>"
        <?php if (!$Page->banco->IsNativeSelect) { ?>
        data-select2-id="fcobros_clienteedit_x_banco"
        <?php } ?>
        data-table="cobros_cliente"
        data-field="x_banco"
        data-value-separator="<?= $Page->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>"
        <?= $Page->banco->editAttributes() ?>>
        <?= $Page->banco->selectOptionListHtml("x_banco") ?>
    </select>
    <?= $Page->banco->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
<?= $Page->banco->Lookup->getParamTag($Page, "p_x_banco") ?>
<?php if (!$Page->banco->IsNativeSelect) { ?>
<script>
loadjs.ready("fcobros_clienteedit", function() {
    var options = { name: "x_banco", selectId: "fcobros_clienteedit_x_banco" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcobros_clienteedit.lists.banco?.lookupOptions.length) {
        options.data = { id: "x_banco", form: "fcobros_clienteedit" };
    } else {
        options.ajax = { id: "x_banco", form: "fcobros_clienteedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cobros_cliente.fields.banco.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto_recibido->Visible) { // monto_recibido ?>
    <div id="r_monto_recibido"<?= $Page->monto_recibido->rowAttributes() ?>>
        <label id="elh_cobros_cliente_monto_recibido" for="x_monto_recibido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto_recibido->caption() ?><?= $Page->monto_recibido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto_recibido->cellAttributes() ?>>
<span id="el_cobros_cliente_monto_recibido">
<input type="<?= $Page->monto_recibido->getInputTextType() ?>" name="x_monto_recibido" id="x_monto_recibido" data-table="cobros_cliente" data-field="x_monto_recibido" value="<?= $Page->monto_recibido->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto_recibido->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto_recibido->formatPattern()) ?>"<?= $Page->monto_recibido->editAttributes() ?> aria-describedby="x_monto_recibido_help">
<?= $Page->monto_recibido->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto_recibido->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tasa_cambio->Visible) { // tasa_cambio ?>
    <div id="r_tasa_cambio"<?= $Page->tasa_cambio->rowAttributes() ?>>
        <label id="elh_cobros_cliente_tasa_cambio" for="x_tasa_cambio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tasa_cambio->caption() ?><?= $Page->tasa_cambio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tasa_cambio->cellAttributes() ?>>
<span id="el_cobros_cliente_tasa_cambio">
<input type="<?= $Page->tasa_cambio->getInputTextType() ?>" name="x_tasa_cambio" id="x_tasa_cambio" data-table="cobros_cliente" data-field="x_tasa_cambio" value="<?= $Page->tasa_cambio->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->tasa_cambio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->tasa_cambio->formatPattern()) ?>"<?= $Page->tasa_cambio->editAttributes() ?> aria-describedby="x_tasa_cambio_help">
<?= $Page->tasa_cambio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tasa_cambio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote2->Visible) { // pivote2 ?>
    <div id="r_pivote2"<?= $Page->pivote2->rowAttributes() ?>>
        <label id="elh_cobros_cliente_pivote2" for="x_pivote2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote2->caption() ?><?= $Page->pivote2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pivote2->cellAttributes() ?>>
<span id="el_cobros_cliente_pivote2">
<input type="<?= $Page->pivote2->getInputTextType() ?>" name="x_pivote2" id="x_pivote2" data-table="cobros_cliente" data-field="x_pivote2" value="<?= $Page->pivote2->EditValue ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pivote2->formatPattern()) ?>"<?= $Page->pivote2->editAttributes() ?> aria-describedby="x_pivote2_help">
<?= $Page->pivote2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cobros_cliente" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("cobros_cliente_factura", explode(",", $Page->getCurrentDetailTable())) && $cobros_cliente_factura->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cobros_cliente_factura", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CobrosClienteFacturaGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcobros_clienteedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcobros_clienteedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cobros_cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
