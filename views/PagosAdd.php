<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagosAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pagos: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpagosadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpagosadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["tipo_pago", [fields.tipo_pago.visible && fields.tipo_pago.required ? ew.Validators.required(fields.tipo_pago.caption) : null], fields.tipo_pago.isInvalid],
            ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(fields.fecha.clientFormatPattern)], fields.fecha.isInvalid],
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
            ["banco_destino", [fields.banco_destino.visible && fields.banco_destino.required ? ew.Validators.required(fields.banco_destino.caption) : null], fields.banco_destino.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["monto", [fields.monto.visible && fields.monto.required ? ew.Validators.required(fields.monto.caption) : null, ew.Validators.float], fields.monto.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["comprobante_pago", [fields.comprobante_pago.visible && fields.comprobante_pago.required ? ew.Validators.fileRequired(fields.comprobante_pago.caption) : null], fields.comprobante_pago.isInvalid]
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
            "tipo_pago": <?= $Page->tipo_pago->toClientList($Page) ?>,
            "banco": <?= $Page->banco->toClientList($Page) ?>,
            "banco_destino": <?= $Page->banco_destino->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
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
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpagosadd" id="fpagosadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pagos">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "salidas") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="salidas">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "view_facturas_cobranza") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="view_facturas_cobranza">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <div id="r_tipo_pago"<?= $Page->tipo_pago->rowAttributes() ?>>
        <label id="elh_pagos_tipo_pago" for="x_tipo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_pago->caption() ?><?= $Page->tipo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_pago->cellAttributes() ?>>
<span id="el_pagos_tipo_pago">
    <select
        id="x_tipo_pago"
        name="x_tipo_pago"
        class="form-select ew-select<?= $Page->tipo_pago->isInvalidClass() ?>"
        <?php if (!$Page->tipo_pago->IsNativeSelect) { ?>
        data-select2-id="fpagosadd_x_tipo_pago"
        <?php } ?>
        data-table="pagos"
        data-field="x_tipo_pago"
        data-value-separator="<?= $Page->tipo_pago->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_pago->getPlaceHolder()) ?>"
        <?= $Page->tipo_pago->editAttributes() ?>>
        <?= $Page->tipo_pago->selectOptionListHtml("x_tipo_pago") ?>
    </select>
    <?= $Page->tipo_pago->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_pago->getErrorMessage() ?></div>
<?= $Page->tipo_pago->Lookup->getParamTag($Page, "p_x_tipo_pago") ?>
<?php if (!$Page->tipo_pago->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosadd", function() {
    var options = { name: "x_tipo_pago", selectId: "fpagosadd_x_tipo_pago" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosadd.lists.tipo_pago?.lookupOptions.length) {
        options.data = { id: "x_tipo_pago", form: "fpagosadd" };
    } else {
        options.ajax = { id: "x_tipo_pago", form: "fpagosadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.tipo_pago.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha"<?= $Page->fecha->rowAttributes() ?>>
        <label id="elh_pagos_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha->cellAttributes() ?>>
<span id="el_pagos_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" name="x_fecha" id="x_fecha" data-table="pagos" data-field="x_fecha" value="<?= $Page->fecha->EditValue ?>" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha->formatPattern()) ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpagosadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpagosadd", "x_fecha", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco"<?= $Page->banco->rowAttributes() ?>>
        <label id="elh_pagos_banco" for="x_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco->cellAttributes() ?>>
<span id="el_pagos_banco">
    <select
        id="x_banco"
        name="x_banco"
        class="form-select ew-select<?= $Page->banco->isInvalidClass() ?>"
        <?php if (!$Page->banco->IsNativeSelect) { ?>
        data-select2-id="fpagosadd_x_banco"
        <?php } ?>
        data-table="pagos"
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
loadjs.ready("fpagosadd", function() {
    var options = { name: "x_banco", selectId: "fpagosadd_x_banco" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosadd.lists.banco?.lookupOptions.length) {
        options.data = { id: "x_banco", form: "fpagosadd" };
    } else {
        options.ajax = { id: "x_banco", form: "fpagosadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.banco.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->banco_destino->Visible) { // banco_destino ?>
    <div id="r_banco_destino"<?= $Page->banco_destino->rowAttributes() ?>>
        <label id="elh_pagos_banco_destino" for="x_banco_destino" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco_destino->caption() ?><?= $Page->banco_destino->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco_destino->cellAttributes() ?>>
<span id="el_pagos_banco_destino">
    <select
        id="x_banco_destino"
        name="x_banco_destino"
        class="form-select ew-select<?= $Page->banco_destino->isInvalidClass() ?>"
        <?php if (!$Page->banco_destino->IsNativeSelect) { ?>
        data-select2-id="fpagosadd_x_banco_destino"
        <?php } ?>
        data-table="pagos"
        data-field="x_banco_destino"
        data-value-separator="<?= $Page->banco_destino->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->banco_destino->getPlaceHolder()) ?>"
        <?= $Page->banco_destino->editAttributes() ?>>
        <?= $Page->banco_destino->selectOptionListHtml("x_banco_destino") ?>
    </select>
    <?= $Page->banco_destino->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->banco_destino->getErrorMessage() ?></div>
<?= $Page->banco_destino->Lookup->getParamTag($Page, "p_x_banco_destino") ?>
<?php if (!$Page->banco_destino->IsNativeSelect) { ?>
<script>
loadjs.ready("fpagosadd", function() {
    var options = { name: "x_banco_destino", selectId: "fpagosadd_x_banco_destino" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosadd.lists.banco_destino?.lookupOptions.length) {
        options.data = { id: "x_banco_destino", form: "fpagosadd" };
    } else {
        options.ajax = { id: "x_banco_destino", form: "fpagosadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.banco_destino.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <label id="elh_pagos_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->referencia->cellAttributes() ?>>
<span id="el_pagos_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" name="x_referencia" id="x_referencia" data-table="pagos" data-field="x_referencia" value="<?= $Page->referencia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->referencia->formatPattern()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_pagos_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_pagos_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fpagosadd_x_moneda"
        <?php } ?>
        data-table="pagos"
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
loadjs.ready("fpagosadd", function() {
    var options = { name: "x_moneda", selectId: "fpagosadd_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpagosadd.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fpagosadd" };
    } else {
        options.ajax = { id: "x_moneda", form: "fpagosadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pagos.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <div id="r_monto"<?= $Page->monto->rowAttributes() ?>>
        <label id="elh_pagos_monto" for="x_monto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->monto->caption() ?><?= $Page->monto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->monto->cellAttributes() ?>>
<span id="el_pagos_monto">
<input type="<?= $Page->monto->getInputTextType() ?>" name="x_monto" id="x_monto" data-table="pagos" data-field="x_monto" value="<?= $Page->monto->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->monto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->monto->formatPattern()) ?>"<?= $Page->monto->editAttributes() ?> aria-describedby="x_monto_help">
<?= $Page->monto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->monto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_pagos_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_pagos_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" name="x_nota" id="x_nota" data-table="pagos" data-field="x_nota" value="<?= $Page->nota->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nota->formatPattern()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
    <div id="r_comprobante_pago"<?= $Page->comprobante_pago->rowAttributes() ?>>
        <label id="elh_pagos_comprobante_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->comprobante_pago->caption() ?><?= $Page->comprobante_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->comprobante_pago->cellAttributes() ?>>
<span id="el_pagos_comprobante_pago">
<div id="fd_x_comprobante_pago" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_comprobante_pago"
        name="x_comprobante_pago"
        class="form-control ew-file-input"
        title="<?= $Page->comprobante_pago->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="pagos"
        data-field="x_comprobante_pago"
        data-size="255"
        data-accept-file-types="<?= $Page->comprobante_pago->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->comprobante_pago->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->comprobante_pago->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_comprobante_pago_help"
        <?= ($Page->comprobante_pago->ReadOnly || $Page->comprobante_pago->Disabled) ? " disabled" : "" ?>
        <?= $Page->comprobante_pago->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->comprobante_pago->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->comprobante_pago->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_comprobante_pago" id= "fn_x_comprobante_pago" value="<?= $Page->comprobante_pago->Upload->FileName ?>">
<input type="hidden" name="fa_x_comprobante_pago" id= "fa_x_comprobante_pago" value="0">
<table id="ft_x_comprobante_pago" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->id_documento->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_id_documento" id="x_id_documento" value="<?= HtmlEncode(strval($Page->id_documento->getSessionValue())) ?>">
    <?php } ?>
    <?php if (strval($Page->tipo_documento->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_tipo_documento" id="x_tipo_documento" value="<?= HtmlEncode(strval($Page->tipo_documento->getSessionValue())) ?>">
    <?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpagosadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpagosadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("pagos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
