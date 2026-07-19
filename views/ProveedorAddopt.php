<?php

namespace PHPMaker2024\mandrake;

// Page object
$ProveedorAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { proveedor: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fproveedoraddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproveedoraddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
            ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
            ["tipo_ret_iva", [fields.tipo_ret_iva.visible && fields.tipo_ret_iva.required ? ew.Validators.required(fields.tipo_ret_iva.caption) : null], fields.tipo_ret_iva.isInvalid],
            ["tipo_ret_islr_concepto", [fields.tipo_ret_islr_concepto.visible && fields.tipo_ret_islr_concepto.required ? ew.Validators.required(fields.tipo_ret_islr_concepto.caption) : null], fields.tipo_ret_islr_concepto.isInvalid],
            ["tipo_ret_islr", [fields.tipo_ret_islr.visible && fields.tipo_ret_islr.required ? ew.Validators.required(fields.tipo_ret_islr.caption) : null], fields.tipo_ret_islr.isInvalid],
            ["tipo_ret_mun", [fields.tipo_ret_mun.visible && fields.tipo_ret_mun.required ? ew.Validators.required(fields.tipo_ret_mun.caption) : null], fields.tipo_ret_mun.isInvalid],
            ["cta_bco", [fields.cta_bco.visible && fields.cta_bco.required ? ew.Validators.required(fields.cta_bco.caption) : null], fields.cta_bco.isInvalid]
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
            "tipo_ret_iva": <?= $Page->tipo_ret_iva->toClientList($Page) ?>,
            "tipo_ret_islr_concepto": <?= $Page->tipo_ret_islr_concepto->toClientList($Page) ?>,
            "tipo_ret_islr": <?= $Page->tipo_ret_islr->toClientList($Page) ?>,
            "tipo_ret_mun": <?= $Page->tipo_ret_mun->toClientList($Page) ?>,
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
<form name="fproveedoraddopt" id="fproveedoraddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="proveedor">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif"<?= $Page->ci_rif->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ci_rif"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ci_rif->cellAttributes() ?>>
<input type="<?= $Page->ci_rif->getInputTextType() ?>" name="x_ci_rif" id="x_ci_rif" data-table="proveedor" data-field="x_ci_rif" value="<?= $Page->ci_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ci_rif->formatPattern()) ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_nombre"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->nombre->cellAttributes() ?>>
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="proveedor" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion"<?= $Page->direccion->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_direccion"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->direccion->cellAttributes() ?>>
<textarea data-table="proveedor" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help"><?= $Page->direccion->EditValue ?></textarea>
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div id="r_telefono1"<?= $Page->telefono1->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_telefono1"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->telefono1->cellAttributes() ?>>
<input type="<?= $Page->telefono1->getInputTextType() ?>" name="x_telefono1" id="x_telefono1" data-table="proveedor" data-field="x_telefono1" value="<?= $Page->telefono1->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono1->formatPattern()) ?>"<?= $Page->telefono1->editAttributes() ?> aria-describedby="x_telefono1_help">
<?= $Page->telefono1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_ret_iva->Visible) { // tipo_ret_iva ?>
    <div id="r_tipo_ret_iva"<?= $Page->tipo_ret_iva->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_tipo_ret_iva"><?= $Page->tipo_ret_iva->caption() ?><?= $Page->tipo_ret_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->tipo_ret_iva->cellAttributes() ?>>
    <select
        id="x_tipo_ret_iva"
        name="x_tipo_ret_iva"
        class="form-select ew-select<?= $Page->tipo_ret_iva->isInvalidClass() ?>"
        <?php if (!$Page->tipo_ret_iva->IsNativeSelect) { ?>
        data-select2-id="fproveedoraddopt_x_tipo_ret_iva"
        <?php } ?>
        data-table="proveedor"
        data-field="x_tipo_ret_iva"
        data-value-separator="<?= $Page->tipo_ret_iva->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_ret_iva->getPlaceHolder()) ?>"
        <?= $Page->tipo_ret_iva->editAttributes() ?>>
        <?= $Page->tipo_ret_iva->selectOptionListHtml("x_tipo_ret_iva") ?>
    </select>
    <?= $Page->tipo_ret_iva->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_ret_iva->getErrorMessage() ?></div>
<?= $Page->tipo_ret_iva->Lookup->getParamTag($Page, "p_x_tipo_ret_iva") ?>
<?php if (!$Page->tipo_ret_iva->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoraddopt", function() {
    var options = { name: "x_tipo_ret_iva", selectId: "fproveedoraddopt_x_tipo_ret_iva" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoraddopt.lists.tipo_ret_iva?.lookupOptions.length) {
        options.data = { id: "x_tipo_ret_iva", form: "fproveedoraddopt" };
    } else {
        options.ajax = { id: "x_tipo_ret_iva", form: "fproveedoraddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.tipo_ret_iva.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_ret_islr_concepto->Visible) { // tipo_ret_islr_concepto ?>
    <div id="r_tipo_ret_islr_concepto"<?= $Page->tipo_ret_islr_concepto->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_tipo_ret_islr_concepto"><?= $Page->tipo_ret_islr_concepto->caption() ?><?= $Page->tipo_ret_islr_concepto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->tipo_ret_islr_concepto->cellAttributes() ?>>
    <select
        id="x_tipo_ret_islr_concepto"
        name="x_tipo_ret_islr_concepto"
        class="form-control ew-select<?= $Page->tipo_ret_islr_concepto->isInvalidClass() ?>"
        data-select2-id="fproveedoraddopt_x_tipo_ret_islr_concepto"
        data-table="proveedor"
        data-field="x_tipo_ret_islr_concepto"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->tipo_ret_islr_concepto->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->tipo_ret_islr_concepto->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_ret_islr_concepto->getPlaceHolder()) ?>"
        data-ew-action="update-options"
        <?= $Page->tipo_ret_islr_concepto->editAttributes() ?>>
        <?= $Page->tipo_ret_islr_concepto->selectOptionListHtml("x_tipo_ret_islr_concepto") ?>
    </select>
    <?= $Page->tipo_ret_islr_concepto->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_ret_islr_concepto->getErrorMessage() ?></div>
<?= $Page->tipo_ret_islr_concepto->Lookup->getParamTag($Page, "p_x_tipo_ret_islr_concepto") ?>
<script>
loadjs.ready("fproveedoraddopt", function() {
    var options = { name: "x_tipo_ret_islr_concepto", selectId: "fproveedoraddopt_x_tipo_ret_islr_concepto" };
    if (fproveedoraddopt.lists.tipo_ret_islr_concepto?.lookupOptions.length) {
        options.data = { id: "x_tipo_ret_islr_concepto", form: "fproveedoraddopt" };
    } else {
        options.ajax = { id: "x_tipo_ret_islr_concepto", form: "fproveedoraddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor.fields.tipo_ret_islr_concepto.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_ret_islr->Visible) { // tipo_ret_islr ?>
    <div id="r_tipo_ret_islr"<?= $Page->tipo_ret_islr->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_tipo_ret_islr"><?= $Page->tipo_ret_islr->caption() ?><?= $Page->tipo_ret_islr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->tipo_ret_islr->cellAttributes() ?>>
    <select
        id="x_tipo_ret_islr"
        name="x_tipo_ret_islr"
        class="form-select ew-select<?= $Page->tipo_ret_islr->isInvalidClass() ?>"
        <?php if (!$Page->tipo_ret_islr->IsNativeSelect) { ?>
        data-select2-id="fproveedoraddopt_x_tipo_ret_islr"
        <?php } ?>
        data-table="proveedor"
        data-field="x_tipo_ret_islr"
        data-value-separator="<?= $Page->tipo_ret_islr->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_ret_islr->getPlaceHolder()) ?>"
        <?= $Page->tipo_ret_islr->editAttributes() ?>>
        <?= $Page->tipo_ret_islr->selectOptionListHtml("x_tipo_ret_islr") ?>
    </select>
    <?= $Page->tipo_ret_islr->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_ret_islr->getErrorMessage() ?></div>
<?= $Page->tipo_ret_islr->Lookup->getParamTag($Page, "p_x_tipo_ret_islr") ?>
<?php if (!$Page->tipo_ret_islr->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoraddopt", function() {
    var options = { name: "x_tipo_ret_islr", selectId: "fproveedoraddopt_x_tipo_ret_islr" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoraddopt.lists.tipo_ret_islr?.lookupOptions.length) {
        options.data = { id: "x_tipo_ret_islr", form: "fproveedoraddopt" };
    } else {
        options.ajax = { id: "x_tipo_ret_islr", form: "fproveedoraddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.tipo_ret_islr.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_ret_mun->Visible) { // tipo_ret_mun ?>
    <div id="r_tipo_ret_mun"<?= $Page->tipo_ret_mun->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_tipo_ret_mun"><?= $Page->tipo_ret_mun->caption() ?><?= $Page->tipo_ret_mun->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->tipo_ret_mun->cellAttributes() ?>>
    <select
        id="x_tipo_ret_mun"
        name="x_tipo_ret_mun"
        class="form-select ew-select<?= $Page->tipo_ret_mun->isInvalidClass() ?>"
        <?php if (!$Page->tipo_ret_mun->IsNativeSelect) { ?>
        data-select2-id="fproveedoraddopt_x_tipo_ret_mun"
        <?php } ?>
        data-table="proveedor"
        data-field="x_tipo_ret_mun"
        data-value-separator="<?= $Page->tipo_ret_mun->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_ret_mun->getPlaceHolder()) ?>"
        <?= $Page->tipo_ret_mun->editAttributes() ?>>
        <?= $Page->tipo_ret_mun->selectOptionListHtml("x_tipo_ret_mun") ?>
    </select>
    <?= $Page->tipo_ret_mun->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_ret_mun->getErrorMessage() ?></div>
<?= $Page->tipo_ret_mun->Lookup->getParamTag($Page, "p_x_tipo_ret_mun") ?>
<?php if (!$Page->tipo_ret_mun->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoraddopt", function() {
    var options = { name: "x_tipo_ret_mun", selectId: "fproveedoraddopt_x_tipo_ret_mun" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoraddopt.lists.tipo_ret_mun?.lookupOptions.length) {
        options.data = { id: "x_tipo_ret_mun", form: "fproveedoraddopt" };
    } else {
        options.ajax = { id: "x_tipo_ret_mun", form: "fproveedoraddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.tipo_ret_mun.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <div id="r_cta_bco"<?= $Page->cta_bco->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_cta_bco"><?= $Page->cta_bco->caption() ?><?= $Page->cta_bco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->cta_bco->cellAttributes() ?>>
<input type="<?= $Page->cta_bco->getInputTextType() ?>" name="x_cta_bco" id="x_cta_bco" data-table="proveedor" data-field="x_cta_bco" value="<?= $Page->cta_bco->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->cta_bco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cta_bco->formatPattern()) ?>"<?= $Page->cta_bco->editAttributes() ?> aria-describedby="x_cta_bco_help">
<?= $Page->cta_bco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cta_bco->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
