<?php

namespace PHPMaker2024\mandrake;

// Page object
$ProveedorAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { proveedor: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fproveedoradd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fproveedoradd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["ciudad", [fields.ciudad.visible && fields.ciudad.required ? ew.Validators.required(fields.ciudad.caption) : null], fields.ciudad.isInvalid],
            ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
            ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
            ["telefono2", [fields.telefono2.visible && fields.telefono2.required ? ew.Validators.required(fields.telefono2.caption) : null], fields.telefono2.isInvalid],
            ["email1", [fields.email1.visible && fields.email1.required ? ew.Validators.required(fields.email1.caption) : null, ew.Validators.email], fields.email1.isInvalid],
            ["email2", [fields.email2.visible && fields.email2.required ? ew.Validators.required(fields.email2.caption) : null, ew.Validators.email], fields.email2.isInvalid],
            ["cuenta_gasto", [fields.cuenta_gasto.visible && fields.cuenta_gasto.required ? ew.Validators.required(fields.cuenta_gasto.caption) : null], fields.cuenta_gasto.isInvalid],
            ["tipo_iva", [fields.tipo_iva.visible && fields.tipo_iva.required ? ew.Validators.required(fields.tipo_iva.caption) : null], fields.tipo_iva.isInvalid],
            ["tipo_islr", [fields.tipo_islr.visible && fields.tipo_islr.required ? ew.Validators.required(fields.tipo_islr.caption) : null], fields.tipo_islr.isInvalid],
            ["sustraendo", [fields.sustraendo.visible && fields.sustraendo.required ? ew.Validators.required(fields.sustraendo.caption) : null], fields.sustraendo.isInvalid],
            ["tipo_impmun", [fields.tipo_impmun.visible && fields.tipo_impmun.required ? ew.Validators.required(fields.tipo_impmun.caption) : null], fields.tipo_impmun.isInvalid],
            ["cta_bco", [fields.cta_bco.visible && fields.cta_bco.required ? ew.Validators.required(fields.cta_bco.caption) : null], fields.cta_bco.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null, ew.Validators.integer], fields.fabricante.isInvalid]
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
            "ciudad": <?= $Page->ciudad->toClientList($Page) ?>,
            "cuenta_gasto": <?= $Page->cuenta_gasto->toClientList($Page) ?>,
            "tipo_iva": <?= $Page->tipo_iva->toClientList($Page) ?>,
            "tipo_islr": <?= $Page->tipo_islr->toClientList($Page) ?>,
            "sustraendo": <?= $Page->sustraendo->toClientList($Page) ?>,
            "tipo_impmun": <?= $Page->tipo_impmun->toClientList($Page) ?>,
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
<form name="fproveedoradd" id="fproveedoradd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="proveedor">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif"<?= $Page->ci_rif->rowAttributes() ?>>
        <label id="elh_proveedor_ci_rif" for="x_ci_rif" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ci_rif->cellAttributes() ?>>
<span id="el_proveedor_ci_rif">
<input type="<?= $Page->ci_rif->getInputTextType() ?>" name="x_ci_rif" id="x_ci_rif" data-table="proveedor" data-field="x_ci_rif" value="<?= $Page->ci_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ci_rif->formatPattern()) ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_proveedor_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_proveedor_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="proveedor" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <div id="r_ciudad"<?= $Page->ciudad->rowAttributes() ?>>
        <label id="elh_proveedor_ciudad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ciudad->caption() ?><?= $Page->ciudad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ciudad->cellAttributes() ?>>
<span id="el_proveedor_ciudad">
    <select
        id="x_ciudad"
        name="x_ciudad"
        class="form-control ew-select<?= $Page->ciudad->isInvalidClass() ?>"
        data-select2-id="fproveedoradd_x_ciudad"
        data-table="proveedor"
        data-field="x_ciudad"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->ciudad->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->ciudad->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ciudad->getPlaceHolder()) ?>"
        <?= $Page->ciudad->editAttributes() ?>>
        <?= $Page->ciudad->selectOptionListHtml("x_ciudad") ?>
    </select>
    <?= $Page->ciudad->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ciudad->getErrorMessage() ?></div>
<?= $Page->ciudad->Lookup->getParamTag($Page, "p_x_ciudad") ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_ciudad", selectId: "fproveedoradd_x_ciudad" };
    if (fproveedoradd.lists.ciudad?.lookupOptions.length) {
        options.data = { id: "x_ciudad", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_ciudad", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor.fields.ciudad.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion"<?= $Page->direccion->rowAttributes() ?>>
        <label id="elh_proveedor_direccion" for="x_direccion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->direccion->cellAttributes() ?>>
<span id="el_proveedor_direccion">
<textarea data-table="proveedor" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help"><?= $Page->direccion->EditValue ?></textarea>
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div id="r_telefono1"<?= $Page->telefono1->rowAttributes() ?>>
        <label id="elh_proveedor_telefono1" for="x_telefono1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->telefono1->cellAttributes() ?>>
<span id="el_proveedor_telefono1">
<input type="<?= $Page->telefono1->getInputTextType() ?>" name="x_telefono1" id="x_telefono1" data-table="proveedor" data-field="x_telefono1" value="<?= $Page->telefono1->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono1->formatPattern()) ?>"<?= $Page->telefono1->editAttributes() ?> aria-describedby="x_telefono1_help">
<?= $Page->telefono1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono2->Visible) { // telefono2 ?>
    <div id="r_telefono2"<?= $Page->telefono2->rowAttributes() ?>>
        <label id="elh_proveedor_telefono2" for="x_telefono2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->telefono2->caption() ?><?= $Page->telefono2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->telefono2->cellAttributes() ?>>
<span id="el_proveedor_telefono2">
<input type="<?= $Page->telefono2->getInputTextType() ?>" name="x_telefono2" id="x_telefono2" data-table="proveedor" data-field="x_telefono2" value="<?= $Page->telefono2->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono2->formatPattern()) ?>"<?= $Page->telefono2->editAttributes() ?> aria-describedby="x_telefono2_help">
<?= $Page->telefono2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <div id="r_email1"<?= $Page->email1->rowAttributes() ?>>
        <label id="elh_proveedor_email1" for="x_email1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email1->caption() ?><?= $Page->email1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->email1->cellAttributes() ?>>
<span id="el_proveedor_email1">
<input type="<?= $Page->email1->getInputTextType() ?>" name="x_email1" id="x_email1" data-table="proveedor" data-field="x_email1" value="<?= $Page->email1->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->email1->formatPattern()) ?>"<?= $Page->email1->editAttributes() ?> aria-describedby="x_email1_help">
<?= $Page->email1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email2->Visible) { // email2 ?>
    <div id="r_email2"<?= $Page->email2->rowAttributes() ?>>
        <label id="elh_proveedor_email2" for="x_email2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->email2->caption() ?><?= $Page->email2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->email2->cellAttributes() ?>>
<span id="el_proveedor_email2">
<input type="<?= $Page->email2->getInputTextType() ?>" name="x_email2" id="x_email2" data-table="proveedor" data-field="x_email2" value="<?= $Page->email2->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->email2->formatPattern()) ?>"<?= $Page->email2->editAttributes() ?> aria-describedby="x_email2_help">
<?= $Page->email2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta_gasto->Visible) { // cuenta_gasto ?>
    <div id="r_cuenta_gasto"<?= $Page->cuenta_gasto->rowAttributes() ?>>
        <label id="elh_proveedor_cuenta_gasto" for="x_cuenta_gasto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta_gasto->caption() ?><?= $Page->cuenta_gasto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta_gasto->cellAttributes() ?>>
<span id="el_proveedor_cuenta_gasto">
    <select
        id="x_cuenta_gasto"
        name="x_cuenta_gasto"
        class="form-control ew-select<?= $Page->cuenta_gasto->isInvalidClass() ?>"
        data-select2-id="fproveedoradd_x_cuenta_gasto"
        data-table="proveedor"
        data-field="x_cuenta_gasto"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cuenta_gasto->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cuenta_gasto->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cuenta_gasto->getPlaceHolder()) ?>"
        <?= $Page->cuenta_gasto->editAttributes() ?>>
        <?= $Page->cuenta_gasto->selectOptionListHtml("x_cuenta_gasto") ?>
    </select>
    <?= $Page->cuenta_gasto->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cuenta_gasto->getErrorMessage() ?></div>
<?= $Page->cuenta_gasto->Lookup->getParamTag($Page, "p_x_cuenta_gasto") ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_cuenta_gasto", selectId: "fproveedoradd_x_cuenta_gasto" };
    if (fproveedoradd.lists.cuenta_gasto?.lookupOptions.length) {
        options.data = { id: "x_cuenta_gasto", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_cuenta_gasto", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.proveedor.fields.cuenta_gasto.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_iva->Visible) { // tipo_iva ?>
    <div id="r_tipo_iva"<?= $Page->tipo_iva->rowAttributes() ?>>
        <label id="elh_proveedor_tipo_iva" for="x_tipo_iva" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_iva->caption() ?><?= $Page->tipo_iva->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_iva->cellAttributes() ?>>
<span id="el_proveedor_tipo_iva">
    <select
        id="x_tipo_iva"
        name="x_tipo_iva"
        class="form-select ew-select<?= $Page->tipo_iva->isInvalidClass() ?>"
        <?php if (!$Page->tipo_iva->IsNativeSelect) { ?>
        data-select2-id="fproveedoradd_x_tipo_iva"
        <?php } ?>
        data-table="proveedor"
        data-field="x_tipo_iva"
        data-value-separator="<?= $Page->tipo_iva->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_iva->getPlaceHolder()) ?>"
        <?= $Page->tipo_iva->editAttributes() ?>>
        <?= $Page->tipo_iva->selectOptionListHtml("x_tipo_iva") ?>
    </select>
    <?= $Page->tipo_iva->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_iva->getErrorMessage() ?></div>
<?= $Page->tipo_iva->Lookup->getParamTag($Page, "p_x_tipo_iva") ?>
<?php if (!$Page->tipo_iva->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_tipo_iva", selectId: "fproveedoradd_x_tipo_iva" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoradd.lists.tipo_iva?.lookupOptions.length) {
        options.data = { id: "x_tipo_iva", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_tipo_iva", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.tipo_iva.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_islr->Visible) { // tipo_islr ?>
    <div id="r_tipo_islr"<?= $Page->tipo_islr->rowAttributes() ?>>
        <label id="elh_proveedor_tipo_islr" for="x_tipo_islr" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_islr->caption() ?><?= $Page->tipo_islr->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_islr->cellAttributes() ?>>
<span id="el_proveedor_tipo_islr">
    <select
        id="x_tipo_islr"
        name="x_tipo_islr"
        class="form-select ew-select<?= $Page->tipo_islr->isInvalidClass() ?>"
        <?php if (!$Page->tipo_islr->IsNativeSelect) { ?>
        data-select2-id="fproveedoradd_x_tipo_islr"
        <?php } ?>
        data-table="proveedor"
        data-field="x_tipo_islr"
        data-value-separator="<?= $Page->tipo_islr->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_islr->getPlaceHolder()) ?>"
        <?= $Page->tipo_islr->editAttributes() ?>>
        <?= $Page->tipo_islr->selectOptionListHtml("x_tipo_islr") ?>
    </select>
    <?= $Page->tipo_islr->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_islr->getErrorMessage() ?></div>
<?= $Page->tipo_islr->Lookup->getParamTag($Page, "p_x_tipo_islr") ?>
<?php if (!$Page->tipo_islr->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_tipo_islr", selectId: "fproveedoradd_x_tipo_islr" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoradd.lists.tipo_islr?.lookupOptions.length) {
        options.data = { id: "x_tipo_islr", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_tipo_islr", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.tipo_islr.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sustraendo->Visible) { // sustraendo ?>
    <div id="r_sustraendo"<?= $Page->sustraendo->rowAttributes() ?>>
        <label id="elh_proveedor_sustraendo" for="x_sustraendo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sustraendo->caption() ?><?= $Page->sustraendo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sustraendo->cellAttributes() ?>>
<span id="el_proveedor_sustraendo">
    <select
        id="x_sustraendo"
        name="x_sustraendo"
        class="form-select ew-select<?= $Page->sustraendo->isInvalidClass() ?>"
        <?php if (!$Page->sustraendo->IsNativeSelect) { ?>
        data-select2-id="fproveedoradd_x_sustraendo"
        <?php } ?>
        data-table="proveedor"
        data-field="x_sustraendo"
        data-value-separator="<?= $Page->sustraendo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->sustraendo->getPlaceHolder()) ?>"
        <?= $Page->sustraendo->editAttributes() ?>>
        <?= $Page->sustraendo->selectOptionListHtml("x_sustraendo") ?>
    </select>
    <?= $Page->sustraendo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->sustraendo->getErrorMessage() ?></div>
<?= $Page->sustraendo->Lookup->getParamTag($Page, "p_x_sustraendo") ?>
<?php if (!$Page->sustraendo->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_sustraendo", selectId: "fproveedoradd_x_sustraendo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoradd.lists.sustraendo?.lookupOptions.length) {
        options.data = { id: "x_sustraendo", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_sustraendo", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.sustraendo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_impmun->Visible) { // tipo_impmun ?>
    <div id="r_tipo_impmun"<?= $Page->tipo_impmun->rowAttributes() ?>>
        <label id="elh_proveedor_tipo_impmun" for="x_tipo_impmun" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_impmun->caption() ?><?= $Page->tipo_impmun->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_impmun->cellAttributes() ?>>
<span id="el_proveedor_tipo_impmun">
    <select
        id="x_tipo_impmun"
        name="x_tipo_impmun"
        class="form-select ew-select<?= $Page->tipo_impmun->isInvalidClass() ?>"
        <?php if (!$Page->tipo_impmun->IsNativeSelect) { ?>
        data-select2-id="fproveedoradd_x_tipo_impmun"
        <?php } ?>
        data-table="proveedor"
        data-field="x_tipo_impmun"
        data-value-separator="<?= $Page->tipo_impmun->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_impmun->getPlaceHolder()) ?>"
        <?= $Page->tipo_impmun->editAttributes() ?>>
        <?= $Page->tipo_impmun->selectOptionListHtml("x_tipo_impmun") ?>
    </select>
    <?= $Page->tipo_impmun->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_impmun->getErrorMessage() ?></div>
<?= $Page->tipo_impmun->Lookup->getParamTag($Page, "p_x_tipo_impmun") ?>
<?php if (!$Page->tipo_impmun->IsNativeSelect) { ?>
<script>
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_tipo_impmun", selectId: "fproveedoradd_x_tipo_impmun" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoradd.lists.tipo_impmun?.lookupOptions.length) {
        options.data = { id: "x_tipo_impmun", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_tipo_impmun", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.tipo_impmun.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cta_bco->Visible) { // cta_bco ?>
    <div id="r_cta_bco"<?= $Page->cta_bco->rowAttributes() ?>>
        <label id="elh_proveedor_cta_bco" for="x_cta_bco" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cta_bco->caption() ?><?= $Page->cta_bco->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cta_bco->cellAttributes() ?>>
<span id="el_proveedor_cta_bco">
<input type="<?= $Page->cta_bco->getInputTextType() ?>" name="x_cta_bco" id="x_cta_bco" data-table="proveedor" data-field="x_cta_bco" value="<?= $Page->cta_bco->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->cta_bco->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cta_bco->formatPattern()) ?>"<?= $Page->cta_bco->editAttributes() ?> aria-describedby="x_cta_bco_help">
<?= $Page->cta_bco->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cta_bco->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_proveedor_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_proveedor_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="fproveedoradd_x_activo"
        <?php } ?>
        data-table="proveedor"
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
loadjs.ready("fproveedoradd", function() {
    var options = { name: "x_activo", selectId: "fproveedoradd_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fproveedoradd.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "fproveedoradd" };
    } else {
        options.ajax = { id: "x_activo", form: "fproveedoradd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.proveedor.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_proveedor_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_proveedor_fabricante">
<input type="<?= $Page->fabricante->getInputTextType() ?>" name="x_fabricante" id="x_fabricante" data-table="proveedor" data-field="x_fabricante" value="<?= $Page->fabricante->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fabricante->formatPattern()) ?>"<?= $Page->fabricante->editAttributes() ?> aria-describedby="x_fabricante_help">
<?= $Page->fabricante->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("proveedor_articulo", explode(",", $Page->getCurrentDetailTable())) && $proveedor_articulo->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("proveedor_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ProveedorArticuloGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fproveedoradd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fproveedoradd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("proveedor");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $("#x_telefono1").mask("(9999) 999-99-99");
    $("#x_telefono2").mask("(9999) 999-99-99");
    $("#x_ci_rif").change(function(){
    	if($("#x_ci_rif").val().trim() == '') {
    		return true;
    	}
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"ci_rif": $("#x_ci_rif").val(),
    		"tipo": 'PROVEEDOR',
    		"accion": 'I'
    	};
    	var url = "rif_buscar.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = $(response).find('#outtext').text();
    			if(content == "1") {
    				alert("RIF / CI \"" + $("#x_ci_rif").val() + "\" ya existe.");
    				$("#x_ci_rif").val("");
    				$("#x_ci_rif").focus();
    				return false;
    			}
    			else {
    				return true;
    			}
    		}
    	});
    });
});
</script>
