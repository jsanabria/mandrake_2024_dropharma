<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagoDestinoReglaAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pago_destino_regla: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fpago_destino_reglaadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fpago_destino_reglaadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["compania", [fields.compania.visible && fields.compania.required ? ew.Validators.required(fields.compania.caption) : null], fields.compania.isInvalid],
            ["metodo", [fields.metodo.visible && fields.metodo.required ? ew.Validators.required(fields.metodo.caption) : null], fields.metodo.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["cuenta_destino_id", [fields.cuenta_destino_id.visible && fields.cuenta_destino_id.required ? ew.Validators.required(fields.cuenta_destino_id.caption) : null], fields.cuenta_destino_id.isInvalid],
            ["prioridad", [fields.prioridad.visible && fields.prioridad.required ? ew.Validators.required(fields.prioridad.caption) : null], fields.prioridad.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
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
            "compania": <?= $Page->compania->toClientList($Page) ?>,
            "metodo": <?= $Page->metodo->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "cuenta_destino_id": <?= $Page->cuenta_destino_id->toClientList($Page) ?>,
            "prioridad": <?= $Page->prioridad->toClientList($Page) ?>,
            "activo": <?= $Page->activo->toClientList($Page) ?>,
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
<form name="fpago_destino_reglaadd" id="fpago_destino_reglaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pago_destino_regla">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->compania->Visible) { // compania ?>
    <div id="r_compania"<?= $Page->compania->rowAttributes() ?>>
        <label id="elh_pago_destino_regla_compania" for="x_compania" class="<?= $Page->LeftColumnClass ?>"><?= $Page->compania->caption() ?><?= $Page->compania->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->compania->cellAttributes() ?>>
<span id="el_pago_destino_regla_compania">
    <select
        id="x_compania"
        name="x_compania"
        class="form-select ew-select<?= $Page->compania->isInvalidClass() ?>"
        <?php if (!$Page->compania->IsNativeSelect) { ?>
        data-select2-id="fpago_destino_reglaadd_x_compania"
        <?php } ?>
        data-table="pago_destino_regla"
        data-field="x_compania"
        data-value-separator="<?= $Page->compania->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->compania->getPlaceHolder()) ?>"
        <?= $Page->compania->editAttributes() ?>>
        <?= $Page->compania->selectOptionListHtml("x_compania") ?>
    </select>
    <?= $Page->compania->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->compania->getErrorMessage() ?></div>
<?= $Page->compania->Lookup->getParamTag($Page, "p_x_compania") ?>
<?php if (!$Page->compania->IsNativeSelect) { ?>
<script>
loadjs.ready("fpago_destino_reglaadd", function() {
    var options = { name: "x_compania", selectId: "fpago_destino_reglaadd_x_compania" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpago_destino_reglaadd.lists.compania?.lookupOptions.length) {
        options.data = { id: "x_compania", form: "fpago_destino_reglaadd" };
    } else {
        options.ajax = { id: "x_compania", form: "fpago_destino_reglaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pago_destino_regla.fields.compania.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->metodo->Visible) { // metodo ?>
    <div id="r_metodo"<?= $Page->metodo->rowAttributes() ?>>
        <label id="elh_pago_destino_regla_metodo" for="x_metodo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo->caption() ?><?= $Page->metodo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->metodo->cellAttributes() ?>>
<span id="el_pago_destino_regla_metodo">
    <select
        id="x_metodo"
        name="x_metodo"
        class="form-select ew-select<?= $Page->metodo->isInvalidClass() ?>"
        <?php if (!$Page->metodo->IsNativeSelect) { ?>
        data-select2-id="fpago_destino_reglaadd_x_metodo"
        <?php } ?>
        data-table="pago_destino_regla"
        data-field="x_metodo"
        data-value-separator="<?= $Page->metodo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->metodo->getPlaceHolder()) ?>"
        <?= $Page->metodo->editAttributes() ?>>
        <?= $Page->metodo->selectOptionListHtml("x_metodo") ?>
    </select>
    <?= $Page->metodo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->metodo->getErrorMessage() ?></div>
<?= $Page->metodo->Lookup->getParamTag($Page, "p_x_metodo") ?>
<?php if (!$Page->metodo->IsNativeSelect) { ?>
<script>
loadjs.ready("fpago_destino_reglaadd", function() {
    var options = { name: "x_metodo", selectId: "fpago_destino_reglaadd_x_metodo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpago_destino_reglaadd.lists.metodo?.lookupOptions.length) {
        options.data = { id: "x_metodo", form: "fpago_destino_reglaadd" };
    } else {
        options.ajax = { id: "x_metodo", form: "fpago_destino_reglaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pago_destino_regla.fields.metodo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_pago_destino_regla_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_pago_destino_regla_moneda">
<template id="tp_x_moneda">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="pago_destino_regla" data-field="x_moneda" name="x_moneda" id="x_moneda"<?= $Page->moneda->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_moneda" class="ew-item-list"></div>
<selection-list hidden
    id="x_moneda"
    name="x_moneda"
    value="<?= HtmlEncode($Page->moneda->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_moneda"
    data-target="dsl_x_moneda"
    data-repeatcolumn="5"
    class="form-control<?= $Page->moneda->isInvalidClass() ?>"
    data-table="pago_destino_regla"
    data-field="x_moneda"
    data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
    <?= $Page->moneda->editAttributes() ?>></selection-list>
<?= $Page->moneda->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta_destino_id->Visible) { // cuenta_destino_id ?>
    <div id="r_cuenta_destino_id"<?= $Page->cuenta_destino_id->rowAttributes() ?>>
        <label id="elh_pago_destino_regla_cuenta_destino_id" for="x_cuenta_destino_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta_destino_id->caption() ?><?= $Page->cuenta_destino_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta_destino_id->cellAttributes() ?>>
<span id="el_pago_destino_regla_cuenta_destino_id">
    <select
        id="x_cuenta_destino_id"
        name="x_cuenta_destino_id"
        class="form-select ew-select<?= $Page->cuenta_destino_id->isInvalidClass() ?>"
        <?php if (!$Page->cuenta_destino_id->IsNativeSelect) { ?>
        data-select2-id="fpago_destino_reglaadd_x_cuenta_destino_id"
        <?php } ?>
        data-table="pago_destino_regla"
        data-field="x_cuenta_destino_id"
        data-value-separator="<?= $Page->cuenta_destino_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cuenta_destino_id->getPlaceHolder()) ?>"
        <?= $Page->cuenta_destino_id->editAttributes() ?>>
        <?= $Page->cuenta_destino_id->selectOptionListHtml("x_cuenta_destino_id") ?>
    </select>
    <?= $Page->cuenta_destino_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cuenta_destino_id->getErrorMessage() ?></div>
<?= $Page->cuenta_destino_id->Lookup->getParamTag($Page, "p_x_cuenta_destino_id") ?>
<?php if (!$Page->cuenta_destino_id->IsNativeSelect) { ?>
<script>
loadjs.ready("fpago_destino_reglaadd", function() {
    var options = { name: "x_cuenta_destino_id", selectId: "fpago_destino_reglaadd_x_cuenta_destino_id" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpago_destino_reglaadd.lists.cuenta_destino_id?.lookupOptions.length) {
        options.data = { id: "x_cuenta_destino_id", form: "fpago_destino_reglaadd" };
    } else {
        options.ajax = { id: "x_cuenta_destino_id", form: "fpago_destino_reglaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pago_destino_regla.fields.cuenta_destino_id.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->prioridad->Visible) { // prioridad ?>
    <div id="r_prioridad"<?= $Page->prioridad->rowAttributes() ?>>
        <label id="elh_pago_destino_regla_prioridad" for="x_prioridad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->prioridad->caption() ?><?= $Page->prioridad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->prioridad->cellAttributes() ?>>
<span id="el_pago_destino_regla_prioridad">
    <select
        id="x_prioridad"
        name="x_prioridad"
        class="form-select ew-select<?= $Page->prioridad->isInvalidClass() ?>"
        <?php if (!$Page->prioridad->IsNativeSelect) { ?>
        data-select2-id="fpago_destino_reglaadd_x_prioridad"
        <?php } ?>
        data-table="pago_destino_regla"
        data-field="x_prioridad"
        data-value-separator="<?= $Page->prioridad->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->prioridad->getPlaceHolder()) ?>"
        <?= $Page->prioridad->editAttributes() ?>>
        <?= $Page->prioridad->selectOptionListHtml("x_prioridad") ?>
    </select>
    <?= $Page->prioridad->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->prioridad->getErrorMessage() ?></div>
<?php if (!$Page->prioridad->IsNativeSelect) { ?>
<script>
loadjs.ready("fpago_destino_reglaadd", function() {
    var options = { name: "x_prioridad", selectId: "fpago_destino_reglaadd_x_prioridad" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpago_destino_reglaadd.lists.prioridad?.lookupOptions.length) {
        options.data = { id: "x_prioridad", form: "fpago_destino_reglaadd" };
    } else {
        options.ajax = { id: "x_prioridad", form: "fpago_destino_reglaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.pago_destino_regla.fields.prioridad.selectOptions);
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
        <label id="elh_pago_destino_regla_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_pago_destino_regla_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" name="x_nota" id="x_nota" data-table="pago_destino_regla" data-field="x_nota" value="<?= $Page->nota->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nota->formatPattern()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_pago_destino_regla_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_pago_destino_regla_activo">
<template id="tp_x_activo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="pago_destino_regla" data-field="x_activo" name="x_activo" id="x_activo"<?= $Page->activo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_activo" class="ew-item-list"></div>
<selection-list hidden
    id="x_activo"
    name="x_activo"
    value="<?= HtmlEncode($Page->activo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activo"
    data-target="dsl_x_activo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activo->isInvalidClass() ?>"
    data-table="pago_destino_regla"
    data-field="x_activo"
    data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
    <?= $Page->activo->editAttributes() ?>></selection-list>
<?= $Page->activo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fpago_destino_reglaadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fpago_destino_reglaadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("pago_destino_regla");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
