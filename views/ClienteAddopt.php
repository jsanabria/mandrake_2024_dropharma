<?php

namespace PHPMaker2024\mandrake;

// Page object
$ClienteAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cliente: currentTable } });
var currentPageID = ew.PAGE_ID = "addopt";
var currentForm;
var fclienteaddopt;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fclienteaddopt")
        .setPageId("addopt")

        // Add fields
        .setFields([
            ["ci_rif", [fields.ci_rif.visible && fields.ci_rif.required ? ew.Validators.required(fields.ci_rif.caption) : null], fields.ci_rif.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["sucursal", [fields.sucursal.visible && fields.sucursal.required ? ew.Validators.required(fields.sucursal.caption) : null], fields.sucursal.isInvalid],
            ["contacto", [fields.contacto.visible && fields.contacto.required ? ew.Validators.required(fields.contacto.caption) : null], fields.contacto.isInvalid],
            ["ciudad", [fields.ciudad.visible && fields.ciudad.required ? ew.Validators.required(fields.ciudad.caption) : null], fields.ciudad.isInvalid],
            ["zona", [fields.zona.visible && fields.zona.required ? ew.Validators.required(fields.zona.caption) : null], fields.zona.isInvalid],
            ["direccion", [fields.direccion.visible && fields.direccion.required ? ew.Validators.required(fields.direccion.caption) : null], fields.direccion.isInvalid],
            ["telefono1", [fields.telefono1.visible && fields.telefono1.required ? ew.Validators.required(fields.telefono1.caption) : null], fields.telefono1.isInvalid],
            ["email1", [fields.email1.visible && fields.email1.required ? ew.Validators.required(fields.email1.caption) : null, ew.Validators.email], fields.email1.isInvalid],
            ["tipo_cliente", [fields.tipo_cliente.visible && fields.tipo_cliente.required ? ew.Validators.required(fields.tipo_cliente.caption) : null], fields.tipo_cliente.isInvalid],
            ["tarifa", [fields.tarifa.visible && fields.tarifa.required ? ew.Validators.required(fields.tarifa.caption) : null], fields.tarifa.isInvalid],
            ["limite_credito", [fields.limite_credito.visible && fields.limite_credito.required ? ew.Validators.required(fields.limite_credito.caption) : null, ew.Validators.float], fields.limite_credito.isInvalid],
            ["condicion", [fields.condicion.visible && fields.condicion.required ? ew.Validators.required(fields.condicion.caption) : null], fields.condicion.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid],
            ["foto1", [fields.foto1.visible && fields.foto1.required ? ew.Validators.fileRequired(fields.foto1.caption) : null], fields.foto1.isInvalid],
            ["foto2", [fields.foto2.visible && fields.foto2.required ? ew.Validators.fileRequired(fields.foto2.caption) : null], fields.foto2.isInvalid],
            ["dias_credito", [fields.dias_credito.visible && fields.dias_credito.required ? ew.Validators.required(fields.dias_credito.caption) : null], fields.dias_credito.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.integer], fields.descuento.isInvalid]
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
            "tipo_cliente": <?= $Page->tipo_cliente->toClientList($Page) ?>,
            "tarifa": <?= $Page->tarifa->toClientList($Page) ?>,
            "condicion": <?= $Page->condicion->toClientList($Page) ?>,
            "activo": <?= $Page->activo->toClientList($Page) ?>,
            "dias_credito": <?= $Page->dias_credito->toClientList($Page) ?>,
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
<form name="fclienteaddopt" id="fclienteaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="cliente">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->ci_rif->Visible) { // ci_rif ?>
    <div id="r_ci_rif"<?= $Page->ci_rif->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_ci_rif"><?= $Page->ci_rif->caption() ?><?= $Page->ci_rif->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ci_rif->cellAttributes() ?>>
<input type="<?= $Page->ci_rif->getInputTextType() ?>" name="x_ci_rif" id="x_ci_rif" data-table="cliente" data-field="x_ci_rif" value="<?= $Page->ci_rif->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->ci_rif->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ci_rif->formatPattern()) ?>"<?= $Page->ci_rif->editAttributes() ?> aria-describedby="x_ci_rif_help">
<?= $Page->ci_rif->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ci_rif->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_nombre"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->nombre->cellAttributes() ?>>
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="cliente" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sucursal->Visible) { // sucursal ?>
    <div id="r_sucursal"<?= $Page->sucursal->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_sucursal"><?= $Page->sucursal->caption() ?><?= $Page->sucursal->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->sucursal->cellAttributes() ?>>
<input type="<?= $Page->sucursal->getInputTextType() ?>" name="x_sucursal" id="x_sucursal" data-table="cliente" data-field="x_sucursal" value="<?= $Page->sucursal->EditValue ?>" size="30" maxlength="80" placeholder="<?= HtmlEncode($Page->sucursal->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->sucursal->formatPattern()) ?>"<?= $Page->sucursal->editAttributes() ?> aria-describedby="x_sucursal_help">
<?= $Page->sucursal->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sucursal->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->contacto->Visible) { // contacto ?>
    <div id="r_contacto"<?= $Page->contacto->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_contacto"><?= $Page->contacto->caption() ?><?= $Page->contacto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->contacto->cellAttributes() ?>>
<input type="<?= $Page->contacto->getInputTextType() ?>" name="x_contacto" id="x_contacto" data-table="cliente" data-field="x_contacto" value="<?= $Page->contacto->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->contacto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->contacto->formatPattern()) ?>"<?= $Page->contacto->editAttributes() ?> aria-describedby="x_contacto_help">
<?= $Page->contacto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->contacto->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ciudad->Visible) { // ciudad ?>
    <div id="r_ciudad"<?= $Page->ciudad->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->ciudad->caption() ?><?= $Page->ciudad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->ciudad->cellAttributes() ?>>
    <select
        id="x_ciudad"
        name="x_ciudad"
        class="form-control ew-select<?= $Page->ciudad->isInvalidClass() ?>"
        data-select2-id="fclienteaddopt_x_ciudad"
        data-table="cliente"
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
loadjs.ready("fclienteaddopt", function() {
    var options = { name: "x_ciudad", selectId: "fclienteaddopt_x_ciudad" };
    if (fclienteaddopt.lists.ciudad?.lookupOptions.length) {
        options.data = { id: "x_ciudad", form: "fclienteaddopt" };
    } else {
        options.ajax = { id: "x_ciudad", form: "fclienteaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cliente.fields.ciudad.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->zona->Visible) { // zona ?>
    <div id="r_zona"<?= $Page->zona->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_zona"><?= $Page->zona->caption() ?><?= $Page->zona->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->zona->cellAttributes() ?>>
<input type="<?= $Page->zona->getInputTextType() ?>" name="x_zona" id="x_zona" data-table="cliente" data-field="x_zona" value="<?= $Page->zona->EditValue ?>" size="30" maxlength="6" placeholder="<?= HtmlEncode($Page->zona->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->zona->formatPattern()) ?>"<?= $Page->zona->editAttributes() ?> aria-describedby="x_zona_help">
<?= $Page->zona->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->zona->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->direccion->Visible) { // direccion ?>
    <div id="r_direccion"<?= $Page->direccion->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_direccion"><?= $Page->direccion->caption() ?><?= $Page->direccion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->direccion->cellAttributes() ?>>
<textarea data-table="cliente" data-field="x_direccion" name="x_direccion" id="x_direccion" cols="35" rows="3" placeholder="<?= HtmlEncode($Page->direccion->getPlaceHolder()) ?>"<?= $Page->direccion->editAttributes() ?> aria-describedby="x_direccion_help"><?= $Page->direccion->EditValue ?></textarea>
<?= $Page->direccion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->direccion->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->telefono1->Visible) { // telefono1 ?>
    <div id="r_telefono1"<?= $Page->telefono1->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_telefono1"><?= $Page->telefono1->caption() ?><?= $Page->telefono1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->telefono1->cellAttributes() ?>>
<input type="<?= $Page->telefono1->getInputTextType() ?>" name="x_telefono1" id="x_telefono1" data-table="cliente" data-field="x_telefono1" value="<?= $Page->telefono1->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->telefono1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->telefono1->formatPattern()) ?>"<?= $Page->telefono1->editAttributes() ?> aria-describedby="x_telefono1_help">
<?= $Page->telefono1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->telefono1->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->email1->Visible) { // email1 ?>
    <div id="r_email1"<?= $Page->email1->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_email1"><?= $Page->email1->caption() ?><?= $Page->email1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->email1->cellAttributes() ?>>
<input type="<?= $Page->email1->getInputTextType() ?>" name="x_email1" id="x_email1" data-table="cliente" data-field="x_email1" value="<?= $Page->email1->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->email1->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->email1->formatPattern()) ?>"<?= $Page->email1->editAttributes() ?> aria-describedby="x_email1_help">
<?= $Page->email1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->email1->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_cliente->Visible) { // tipo_cliente ?>
    <div id="r_tipo_cliente"<?= $Page->tipo_cliente->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_tipo_cliente"><?= $Page->tipo_cliente->caption() ?><?= $Page->tipo_cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->tipo_cliente->cellAttributes() ?>>
    <select
        id="x_tipo_cliente"
        name="x_tipo_cliente"
        class="form-select ew-select<?= $Page->tipo_cliente->isInvalidClass() ?>"
        <?php if (!$Page->tipo_cliente->IsNativeSelect) { ?>
        data-select2-id="fclienteaddopt_x_tipo_cliente"
        <?php } ?>
        data-table="cliente"
        data-field="x_tipo_cliente"
        data-value-separator="<?= $Page->tipo_cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_cliente->getPlaceHolder()) ?>"
        <?= $Page->tipo_cliente->editAttributes() ?>>
        <?= $Page->tipo_cliente->selectOptionListHtml("x_tipo_cliente") ?>
    </select>
    <?= $Page->tipo_cliente->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_cliente->getErrorMessage() ?></div>
<?= $Page->tipo_cliente->Lookup->getParamTag($Page, "p_x_tipo_cliente") ?>
<?php if (!$Page->tipo_cliente->IsNativeSelect) { ?>
<script>
loadjs.ready("fclienteaddopt", function() {
    var options = { name: "x_tipo_cliente", selectId: "fclienteaddopt_x_tipo_cliente" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fclienteaddopt.lists.tipo_cliente?.lookupOptions.length) {
        options.data = { id: "x_tipo_cliente", form: "fclienteaddopt" };
    } else {
        options.ajax = { id: "x_tipo_cliente", form: "fclienteaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cliente.fields.tipo_cliente.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tarifa->Visible) { // tarifa ?>
    <div id="r_tarifa"<?= $Page->tarifa->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_tarifa"><?= $Page->tarifa->caption() ?><?= $Page->tarifa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->tarifa->cellAttributes() ?>>
    <select
        id="x_tarifa"
        name="x_tarifa"
        class="form-select ew-select<?= $Page->tarifa->isInvalidClass() ?>"
        <?php if (!$Page->tarifa->IsNativeSelect) { ?>
        data-select2-id="fclienteaddopt_x_tarifa"
        <?php } ?>
        data-table="cliente"
        data-field="x_tarifa"
        data-value-separator="<?= $Page->tarifa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tarifa->getPlaceHolder()) ?>"
        <?= $Page->tarifa->editAttributes() ?>>
        <?= $Page->tarifa->selectOptionListHtml("x_tarifa") ?>
    </select>
    <?= $Page->tarifa->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tarifa->getErrorMessage() ?></div>
<?= $Page->tarifa->Lookup->getParamTag($Page, "p_x_tarifa") ?>
<?php if (!$Page->tarifa->IsNativeSelect) { ?>
<script>
loadjs.ready("fclienteaddopt", function() {
    var options = { name: "x_tarifa", selectId: "fclienteaddopt_x_tarifa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fclienteaddopt.lists.tarifa?.lookupOptions.length) {
        options.data = { id: "x_tarifa", form: "fclienteaddopt" };
    } else {
        options.ajax = { id: "x_tarifa", form: "fclienteaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cliente.fields.tarifa.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->limite_credito->Visible) { // limite_credito ?>
    <div id="r_limite_credito"<?= $Page->limite_credito->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_limite_credito"><?= $Page->limite_credito->caption() ?><?= $Page->limite_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->limite_credito->cellAttributes() ?>>
<input type="<?= $Page->limite_credito->getInputTextType() ?>" name="x_limite_credito" id="x_limite_credito" data-table="cliente" data-field="x_limite_credito" value="<?= $Page->limite_credito->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->limite_credito->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->limite_credito->formatPattern()) ?>"<?= $Page->limite_credito->editAttributes() ?> aria-describedby="x_limite_credito_help">
<?= $Page->limite_credito->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->limite_credito->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->condicion->Visible) { // condicion ?>
    <div id="r_condicion"<?= $Page->condicion->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->condicion->caption() ?><?= $Page->condicion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->condicion->cellAttributes() ?>>
<template id="tp_x_condicion">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cliente" data-field="x_condicion" name="x_condicion" id="x_condicion"<?= $Page->condicion->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_condicion" class="ew-item-list"></div>
<selection-list hidden
    id="x_condicion"
    name="x_condicion"
    value="<?= HtmlEncode($Page->condicion->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_condicion"
    data-target="dsl_x_condicion"
    data-repeatcolumn="5"
    class="form-control<?= $Page->condicion->isInvalidClass() ?>"
    data-table="cliente"
    data-field="x_condicion"
    data-value-separator="<?= $Page->condicion->displayValueSeparatorAttribute() ?>"
    <?= $Page->condicion->editAttributes() ?>></selection-list>
<?= $Page->condicion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->condicion->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_activo"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->activo->cellAttributes() ?>>
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="fclienteaddopt_x_activo"
        <?php } ?>
        data-table="cliente"
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
loadjs.ready("fclienteaddopt", function() {
    var options = { name: "x_activo", selectId: "fclienteaddopt_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fclienteaddopt.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "fclienteaddopt" };
    } else {
        options.ajax = { id: "x_activo", form: "fclienteaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cliente.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->foto1->Visible) { // foto1 ?>
    <div id="r_foto1"<?= $Page->foto1->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->foto1->caption() ?><?= $Page->foto1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->foto1->cellAttributes() ?>>
<div id="fd_x_foto1" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_foto1"
        name="x_foto1"
        class="form-control ew-file-input"
        title="<?= $Page->foto1->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="cliente"
        data-field="x_foto1"
        data-size="254"
        data-accept-file-types="<?= $Page->foto1->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->foto1->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->foto1->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_foto1_help"
        <?= ($Page->foto1->ReadOnly || $Page->foto1->Disabled) ? " disabled" : "" ?>
        <?= $Page->foto1->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->foto1->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->foto1->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_foto1" id= "fn_x_foto1" value="<?= $Page->foto1->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto1" id= "fa_x_foto1" value="0">
<table id="ft_x_foto1" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->foto2->Visible) { // foto2 ?>
    <div id="r_foto2"<?= $Page->foto2->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->foto2->caption() ?><?= $Page->foto2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->foto2->cellAttributes() ?>>
<div id="fd_x_foto2" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_foto2"
        name="x_foto2"
        class="form-control ew-file-input"
        title="<?= $Page->foto2->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="cliente"
        data-field="x_foto2"
        data-size="254"
        data-accept-file-types="<?= $Page->foto2->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->foto2->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->foto2->ImageCropper ? 0 : 1 ?>"
        aria-describedby="x_foto2_help"
        <?= ($Page->foto2->ReadOnly || $Page->foto2->Disabled) ? " disabled" : "" ?>
        <?= $Page->foto2->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->foto2->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->foto2->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_foto2" id= "fn_x_foto2" value="<?= $Page->foto2->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto2" id= "fa_x_foto2" value="0">
<table id="ft_x_foto2" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dias_credito->Visible) { // dias_credito ?>
    <div id="r_dias_credito"<?= $Page->dias_credito->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_dias_credito"><?= $Page->dias_credito->caption() ?><?= $Page->dias_credito->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->dias_credito->cellAttributes() ?>>
    <select
        id="x_dias_credito"
        name="x_dias_credito"
        class="form-select ew-select<?= $Page->dias_credito->isInvalidClass() ?>"
        <?php if (!$Page->dias_credito->IsNativeSelect) { ?>
        data-select2-id="fclienteaddopt_x_dias_credito"
        <?php } ?>
        data-table="cliente"
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
loadjs.ready("fclienteaddopt", function() {
    var options = { name: "x_dias_credito", selectId: "fclienteaddopt_x_dias_credito" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fclienteaddopt.lists.dias_credito?.lookupOptions.length) {
        options.data = { id: "x_dias_credito", form: "fclienteaddopt" };
    } else {
        options.ajax = { id: "x_dias_credito", form: "fclienteaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cliente.fields.dias_credito.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_descuento"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->descuento->cellAttributes() ?>>
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x_descuento" id="x_descuento" data-table="cliente" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
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
    ew.addEventHandlers("cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
