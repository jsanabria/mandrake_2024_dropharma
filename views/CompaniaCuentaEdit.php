<?php

namespace PHPMaker2024\mandrake;

// Page object
$CompaniaCuentaEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcompania_cuentaedit" id="fcompania_cuentaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { compania_cuenta: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcompania_cuentaedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcompania_cuentaedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["banco", [fields.banco.visible && fields.banco.required ? ew.Validators.required(fields.banco.caption) : null], fields.banco.isInvalid],
            ["titular", [fields.titular.visible && fields.titular.required ? ew.Validators.required(fields.titular.caption) : null], fields.titular.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["numero", [fields.numero.visible && fields.numero.required ? ew.Validators.required(fields.numero.caption) : null], fields.numero.isInvalid],
            ["mostrar", [fields.mostrar.visible && fields.mostrar.required ? ew.Validators.required(fields.mostrar.caption) : null], fields.mostrar.isInvalid],
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
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
            "banco": <?= $Page->banco->toClientList($Page) ?>,
            "tipo": <?= $Page->tipo->toClientList($Page) ?>,
            "mostrar": <?= $Page->mostrar->toClientList($Page) ?>,
            "cuenta": <?= $Page->cuenta->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="compania_cuenta">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "compania") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="compania">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->compania->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->banco->Visible) { // banco ?>
    <div id="r_banco"<?= $Page->banco->rowAttributes() ?>>
        <label id="elh_compania_cuenta_banco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->banco->caption() ?><?= $Page->banco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->banco->cellAttributes() ?>>
<span id="el_compania_cuenta_banco">
    <select
        id="x_banco"
        name="x_banco"
        class="form-control ew-select<?= $Page->banco->isInvalidClass() ?>"
        data-select2-id="fcompania_cuentaedit_x_banco"
        data-table="compania_cuenta"
        data-field="x_banco"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->banco->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->banco->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->banco->getPlaceHolder()) ?>"
        <?= $Page->banco->editAttributes() ?>>
        <?= $Page->banco->selectOptionListHtml("x_banco") ?>
    </select>
    <?= $Page->banco->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->banco->getErrorMessage() ?></div>
<?= $Page->banco->Lookup->getParamTag($Page, "p_x_banco") ?>
<script>
loadjs.ready("fcompania_cuentaedit", function() {
    var options = { name: "x_banco", selectId: "fcompania_cuentaedit_x_banco" };
    if (fcompania_cuentaedit.lists.banco?.lookupOptions.length) {
        options.data = { id: "x_banco", form: "fcompania_cuentaedit" };
    } else {
        options.ajax = { id: "x_banco", form: "fcompania_cuentaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compania_cuenta.fields.banco.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
    <div id="r_titular"<?= $Page->titular->rowAttributes() ?>>
        <label id="elh_compania_cuenta_titular" for="x_titular" class="<?= $Page->LeftColumnClass ?>"><?= $Page->titular->caption() ?><?= $Page->titular->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->titular->cellAttributes() ?>>
<span id="el_compania_cuenta_titular">
<input type="<?= $Page->titular->getInputTextType() ?>" name="x_titular" id="x_titular" data-table="compania_cuenta" data-field="x_titular" value="<?= $Page->titular->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->titular->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->titular->formatPattern()) ?>"<?= $Page->titular->editAttributes() ?> aria-describedby="x_titular_help">
<?= $Page->titular->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->titular->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_compania_cuenta_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_compania_cuenta_tipo">
    <select
        id="x_tipo"
        name="x_tipo"
        class="form-select ew-select<?= $Page->tipo->isInvalidClass() ?>"
        <?php if (!$Page->tipo->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentaedit_x_tipo"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_tipo"
        data-value-separator="<?= $Page->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo->getPlaceHolder()) ?>"
        <?= $Page->tipo->editAttributes() ?>>
        <?= $Page->tipo->selectOptionListHtml("x_tipo") ?>
    </select>
    <?= $Page->tipo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo->getErrorMessage() ?></div>
<?php if (!$Page->tipo->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentaedit", function() {
    var options = { name: "x_tipo", selectId: "fcompania_cuentaedit_x_tipo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentaedit.lists.tipo?.lookupOptions.length) {
        options.data = { id: "x_tipo", form: "fcompania_cuentaedit" };
    } else {
        options.ajax = { id: "x_tipo", form: "fcompania_cuentaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <div id="r_numero"<?= $Page->numero->rowAttributes() ?>>
        <label id="elh_compania_cuenta_numero" for="x_numero" class="<?= $Page->LeftColumnClass ?>"><?= $Page->numero->caption() ?><?= $Page->numero->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->numero->cellAttributes() ?>>
<span id="el_compania_cuenta_numero">
<input type="<?= $Page->numero->getInputTextType() ?>" name="x_numero" id="x_numero" data-table="compania_cuenta" data-field="x_numero" value="<?= $Page->numero->EditValue ?>" size="30" maxlength="40" placeholder="<?= HtmlEncode($Page->numero->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->numero->formatPattern()) ?>"<?= $Page->numero->editAttributes() ?> aria-describedby="x_numero_help">
<?= $Page->numero->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->numero->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
    <div id="r_mostrar"<?= $Page->mostrar->rowAttributes() ?>>
        <label id="elh_compania_cuenta_mostrar" for="x_mostrar" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mostrar->caption() ?><?= $Page->mostrar->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mostrar->cellAttributes() ?>>
<span id="el_compania_cuenta_mostrar">
    <select
        id="x_mostrar"
        name="x_mostrar"
        class="form-select ew-select<?= $Page->mostrar->isInvalidClass() ?>"
        <?php if (!$Page->mostrar->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentaedit_x_mostrar"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_mostrar"
        data-value-separator="<?= $Page->mostrar->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mostrar->getPlaceHolder()) ?>"
        <?= $Page->mostrar->editAttributes() ?>>
        <?= $Page->mostrar->selectOptionListHtml("x_mostrar") ?>
    </select>
    <?= $Page->mostrar->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mostrar->getErrorMessage() ?></div>
<?php if (!$Page->mostrar->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentaedit", function() {
    var options = { name: "x_mostrar", selectId: "fcompania_cuentaedit_x_mostrar" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentaedit.lists.mostrar?.lookupOptions.length) {
        options.data = { id: "x_mostrar", form: "fcompania_cuentaedit" };
    } else {
        options.ajax = { id: "x_mostrar", form: "fcompania_cuentaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.mostrar.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta"<?= $Page->cuenta->rowAttributes() ?>>
        <label id="elh_compania_cuenta_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta->cellAttributes() ?>>
<span id="el_compania_cuenta_cuenta">
    <select
        id="x_cuenta"
        name="x_cuenta"
        class="form-control ew-select<?= $Page->cuenta->isInvalidClass() ?>"
        data-select2-id="fcompania_cuentaedit_x_cuenta"
        data-table="compania_cuenta"
        data-field="x_cuenta"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cuenta->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cuenta->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cuenta->getPlaceHolder()) ?>"
        <?= $Page->cuenta->editAttributes() ?>>
        <?= $Page->cuenta->selectOptionListHtml("x_cuenta") ?>
    </select>
    <?= $Page->cuenta->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cuenta->getErrorMessage() ?></div>
<?= $Page->cuenta->Lookup->getParamTag($Page, "p_x_cuenta") ?>
<script>
loadjs.ready("fcompania_cuentaedit", function() {
    var options = { name: "x_cuenta", selectId: "fcompania_cuentaedit_x_cuenta" };
    if (fcompania_cuentaedit.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x_cuenta", form: "fcompania_cuentaedit" };
    } else {
        options.ajax = { id: "x_cuenta", form: "fcompania_cuentaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.compania_cuenta.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_compania_cuenta_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_compania_cuenta_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="fcompania_cuentaedit_x_activo"
        <?php } ?>
        data-table="compania_cuenta"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<?php if (!$Page->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("fcompania_cuentaedit", function() {
    var options = { name: "x_activo", selectId: "fcompania_cuentaedit_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcompania_cuentaedit.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "fcompania_cuentaedit" };
    } else {
        options.ajax = { id: "x_activo", form: "fcompania_cuentaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.compania_cuenta.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="compania_cuenta" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcompania_cuentaedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcompania_cuentaedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("compania_cuenta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
