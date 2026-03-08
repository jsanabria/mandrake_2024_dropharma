<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContPlanCuentasMdkAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_plan_cuentas_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcont_plan_cuentas_mdkadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_plan_cuentas_mdkadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["naturaleza", [fields.naturaleza.visible && fields.naturaleza.required ? ew.Validators.required(fields.naturaleza.caption) : null], fields.naturaleza.isInvalid],
            ["acepta_movimiento", [fields.acepta_movimiento.visible && fields.acepta_movimiento.required ? ew.Validators.required(fields.acepta_movimiento.caption) : null], fields.acepta_movimiento.isInvalid],
            ["cuenta_padre_id", [fields.cuenta_padre_id.visible && fields.cuenta_padre_id.required ? ew.Validators.required(fields.cuenta_padre_id.caption) : null], fields.cuenta_padre_id.isInvalid],
            ["nivel", [fields.nivel.visible && fields.nivel.required ? ew.Validators.required(fields.nivel.caption) : null, ew.Validators.integer], fields.nivel.isInvalid],
            ["estado", [fields.estado.visible && fields.estado.required ? ew.Validators.required(fields.estado.caption) : null], fields.estado.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
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
            "tipo": <?= $Page->tipo->toClientList($Page) ?>,
            "naturaleza": <?= $Page->naturaleza->toClientList($Page) ?>,
            "acepta_movimiento": <?= $Page->acepta_movimiento->toClientList($Page) ?>,
            "cuenta_padre_id": <?= $Page->cuenta_padre_id->toClientList($Page) ?>,
            "estado": <?= $Page->estado->toClientList($Page) ?>,
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
<form name="fcont_plan_cuentas_mdkadd" id="fcont_plan_cuentas_mdkadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_plan_cuentas_mdk">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" name="x_codigo" id="x_codigo" data-table="cont_plan_cuentas_mdk" data-field="x_codigo" value="<?= $Page->codigo->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo->formatPattern()) ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="cont_plan_cuentas_mdk" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_tipo">
    <select
        id="x_tipo"
        name="x_tipo"
        class="form-select ew-select<?= $Page->tipo->isInvalidClass() ?>"
        <?php if (!$Page->tipo->IsNativeSelect) { ?>
        data-select2-id="fcont_plan_cuentas_mdkadd_x_tipo"
        <?php } ?>
        data-table="cont_plan_cuentas_mdk"
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
loadjs.ready("fcont_plan_cuentas_mdkadd", function() {
    var options = { name: "x_tipo", selectId: "fcont_plan_cuentas_mdkadd_x_tipo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_plan_cuentas_mdkadd.lists.tipo?.lookupOptions.length) {
        options.data = { id: "x_tipo", form: "fcont_plan_cuentas_mdkadd" };
    } else {
        options.ajax = { id: "x_tipo", form: "fcont_plan_cuentas_mdkadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_plan_cuentas_mdk.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->naturaleza->Visible) { // naturaleza ?>
    <div id="r_naturaleza"<?= $Page->naturaleza->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_naturaleza" for="x_naturaleza" class="<?= $Page->LeftColumnClass ?>"><?= $Page->naturaleza->caption() ?><?= $Page->naturaleza->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->naturaleza->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_naturaleza">
    <select
        id="x_naturaleza"
        name="x_naturaleza"
        class="form-select ew-select<?= $Page->naturaleza->isInvalidClass() ?>"
        <?php if (!$Page->naturaleza->IsNativeSelect) { ?>
        data-select2-id="fcont_plan_cuentas_mdkadd_x_naturaleza"
        <?php } ?>
        data-table="cont_plan_cuentas_mdk"
        data-field="x_naturaleza"
        data-value-separator="<?= $Page->naturaleza->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->naturaleza->getPlaceHolder()) ?>"
        <?= $Page->naturaleza->editAttributes() ?>>
        <?= $Page->naturaleza->selectOptionListHtml("x_naturaleza") ?>
    </select>
    <?= $Page->naturaleza->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->naturaleza->getErrorMessage() ?></div>
<?php if (!$Page->naturaleza->IsNativeSelect) { ?>
<script>
loadjs.ready("fcont_plan_cuentas_mdkadd", function() {
    var options = { name: "x_naturaleza", selectId: "fcont_plan_cuentas_mdkadd_x_naturaleza" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_plan_cuentas_mdkadd.lists.naturaleza?.lookupOptions.length) {
        options.data = { id: "x_naturaleza", form: "fcont_plan_cuentas_mdkadd" };
    } else {
        options.ajax = { id: "x_naturaleza", form: "fcont_plan_cuentas_mdkadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_plan_cuentas_mdk.fields.naturaleza.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->acepta_movimiento->Visible) { // acepta_movimiento ?>
    <div id="r_acepta_movimiento"<?= $Page->acepta_movimiento->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_acepta_movimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->acepta_movimiento->caption() ?><?= $Page->acepta_movimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->acepta_movimiento->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_acepta_movimiento">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->acepta_movimiento->isInvalidClass() ?>" data-table="cont_plan_cuentas_mdk" data-field="x_acepta_movimiento" data-boolean name="x_acepta_movimiento" id="x_acepta_movimiento" value="1"<?= ConvertToBool($Page->acepta_movimiento->CurrentValue) ? " checked" : "" ?><?= $Page->acepta_movimiento->editAttributes() ?> aria-describedby="x_acepta_movimiento_help">
    <div class="invalid-feedback"><?= $Page->acepta_movimiento->getErrorMessage() ?></div>
</div>
<?= $Page->acepta_movimiento->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta_padre_id->Visible) { // cuenta_padre_id ?>
    <div id="r_cuenta_padre_id"<?= $Page->cuenta_padre_id->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_cuenta_padre_id" for="x_cuenta_padre_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta_padre_id->caption() ?><?= $Page->cuenta_padre_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta_padre_id->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_cuenta_padre_id">
    <select
        id="x_cuenta_padre_id"
        name="x_cuenta_padre_id"
        class="form-control ew-select<?= $Page->cuenta_padre_id->isInvalidClass() ?>"
        data-select2-id="fcont_plan_cuentas_mdkadd_x_cuenta_padre_id"
        data-table="cont_plan_cuentas_mdk"
        data-field="x_cuenta_padre_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cuenta_padre_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cuenta_padre_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cuenta_padre_id->getPlaceHolder()) ?>"
        <?= $Page->cuenta_padre_id->editAttributes() ?>>
        <?= $Page->cuenta_padre_id->selectOptionListHtml("x_cuenta_padre_id") ?>
    </select>
    <?= $Page->cuenta_padre_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cuenta_padre_id->getErrorMessage() ?></div>
<?= $Page->cuenta_padre_id->Lookup->getParamTag($Page, "p_x_cuenta_padre_id") ?>
<script>
loadjs.ready("fcont_plan_cuentas_mdkadd", function() {
    var options = { name: "x_cuenta_padre_id", selectId: "fcont_plan_cuentas_mdkadd_x_cuenta_padre_id" };
    if (fcont_plan_cuentas_mdkadd.lists.cuenta_padre_id?.lookupOptions.length) {
        options.data = { id: "x_cuenta_padre_id", form: "fcont_plan_cuentas_mdkadd" };
    } else {
        options.ajax = { id: "x_cuenta_padre_id", form: "fcont_plan_cuentas_mdkadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_plan_cuentas_mdk.fields.cuenta_padre_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nivel->Visible) { // nivel ?>
    <div id="r_nivel"<?= $Page->nivel->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_nivel" for="x_nivel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nivel->caption() ?><?= $Page->nivel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nivel->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_nivel">
<input type="<?= $Page->nivel->getInputTextType() ?>" name="x_nivel" id="x_nivel" data-table="cont_plan_cuentas_mdk" data-field="x_nivel" value="<?= $Page->nivel->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->nivel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nivel->formatPattern()) ?>"<?= $Page->nivel->editAttributes() ?> aria-describedby="x_nivel_help">
<?= $Page->nivel->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nivel->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <div id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_estado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estado->caption() ?><?= $Page->estado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_estado">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->estado->isInvalidClass() ?>" data-table="cont_plan_cuentas_mdk" data-field="x_estado" data-boolean name="x_estado" id="x_estado" value="1"<?= ConvertToBool($Page->estado->CurrentValue) ? " checked" : "" ?><?= $Page->estado->editAttributes() ?> aria-describedby="x_estado_help">
    <div class="invalid-feedback"><?= $Page->estado->getErrorMessage() ?></div>
</div>
<?= $Page->estado->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_cont_plan_cuentas_mdk_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_cont_plan_cuentas_mdk_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="cont_plan_cuentas_mdk" data-field="x_created_at" value="<?= $Page->created_at->EditValue ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fcont_plan_cuentas_mdkadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
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
    ew.createDateTimePicker("fcont_plan_cuentas_mdkadd", "x_created_at", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_plan_cuentas_mdkadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_plan_cuentas_mdkadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_plan_cuentas_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
