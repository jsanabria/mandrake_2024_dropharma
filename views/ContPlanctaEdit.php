<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContPlanctaEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcont_planctaedit" id="fcont_planctaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_plancta: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcont_planctaedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_planctaedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["clase", [fields.clase.visible && fields.clase.required ? ew.Validators.required(fields.clase.caption) : null], fields.clase.isInvalid],
            ["grupo", [fields.grupo.visible && fields.grupo.required ? ew.Validators.required(fields.grupo.caption) : null], fields.grupo.isInvalid],
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
            ["subcuenta", [fields.subcuenta.visible && fields.subcuenta.required ? ew.Validators.required(fields.subcuenta.caption) : null], fields.subcuenta.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["clasificacion", [fields.clasificacion.visible && fields.clasificacion.required ? ew.Validators.required(fields.clasificacion.caption) : null], fields.clasificacion.isInvalid],
            ["naturaleza", [fields.naturaleza.visible && fields.naturaleza.required ? ew.Validators.required(fields.naturaleza.caption) : null], fields.naturaleza.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["activa", [fields.activa.visible && fields.activa.required ? ew.Validators.required(fields.activa.caption) : null], fields.activa.isInvalid]
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
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
            "activa": <?= $Page->activa->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="cont_plancta">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->clase->Visible) { // clase ?>
    <div id="r_clase"<?= $Page->clase->rowAttributes() ?>>
        <label id="elh_cont_plancta_clase" for="x_clase" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clase->caption() ?><?= $Page->clase->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->clase->cellAttributes() ?>>
<span id="el_cont_plancta_clase">
<span<?= $Page->clase->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->clase->getDisplayValue($Page->clase->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_plancta" data-field="x_clase" data-hidden="1" name="x_clase" id="x_clase" value="<?= HtmlEncode($Page->clase->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->grupo->Visible) { // grupo ?>
    <div id="r_grupo"<?= $Page->grupo->rowAttributes() ?>>
        <label id="elh_cont_plancta_grupo" for="x_grupo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->grupo->caption() ?><?= $Page->grupo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->grupo->cellAttributes() ?>>
<span id="el_cont_plancta_grupo">
<span<?= $Page->grupo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->grupo->getDisplayValue($Page->grupo->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_plancta" data-field="x_grupo" data-hidden="1" name="x_grupo" id="x_grupo" value="<?= HtmlEncode($Page->grupo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta"<?= $Page->cuenta->rowAttributes() ?>>
        <label id="elh_cont_plancta_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_plancta_cuenta">
<span<?= $Page->cuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->cuenta->getDisplayValue($Page->cuenta->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_plancta" data-field="x_cuenta" data-hidden="1" name="x_cuenta" id="x_cuenta" value="<?= HtmlEncode($Page->cuenta->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->subcuenta->Visible) { // subcuenta ?>
    <div id="r_subcuenta"<?= $Page->subcuenta->rowAttributes() ?>>
        <label id="elh_cont_plancta_subcuenta" for="x_subcuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->subcuenta->caption() ?><?= $Page->subcuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->subcuenta->cellAttributes() ?>>
<span id="el_cont_plancta_subcuenta">
<span<?= $Page->subcuenta->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->subcuenta->getDisplayValue($Page->subcuenta->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_plancta" data-field="x_subcuenta" data-hidden="1" name="x_subcuenta" id="x_subcuenta" value="<?= HtmlEncode($Page->subcuenta->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_cont_plancta_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_plancta_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="cont_plancta" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->clasificacion->Visible) { // clasificacion ?>
    <div id="r_clasificacion"<?= $Page->clasificacion->rowAttributes() ?>>
        <label id="elh_cont_plancta_clasificacion" for="x_clasificacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clasificacion->caption() ?><?= $Page->clasificacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->clasificacion->cellAttributes() ?>>
<span id="el_cont_plancta_clasificacion">
<span<?= $Page->clasificacion->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->clasificacion->getDisplayValue($Page->clasificacion->EditValue) ?></span></span>
<input type="hidden" data-table="cont_plancta" data-field="x_clasificacion" data-hidden="1" name="x_clasificacion" id="x_clasificacion" value="<?= HtmlEncode($Page->clasificacion->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->naturaleza->Visible) { // naturaleza ?>
    <div id="r_naturaleza"<?= $Page->naturaleza->rowAttributes() ?>>
        <label id="elh_cont_plancta_naturaleza" class="<?= $Page->LeftColumnClass ?>"><?= $Page->naturaleza->caption() ?><?= $Page->naturaleza->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->naturaleza->cellAttributes() ?>>
<span id="el_cont_plancta_naturaleza">
<span<?= $Page->naturaleza->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->naturaleza->getDisplayValue($Page->naturaleza->EditValue) ?></span></span>
<input type="hidden" data-table="cont_plancta" data-field="x_naturaleza" data-hidden="1" name="x_naturaleza" id="x_naturaleza" value="<?= HtmlEncode($Page->naturaleza->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_cont_plancta_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_cont_plancta_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->tipo->getDisplayValue($Page->tipo->EditValue) ?></span></span>
<input type="hidden" data-table="cont_plancta" data-field="x_tipo" data-hidden="1" name="x_tipo" id="x_tipo" value="<?= HtmlEncode($Page->tipo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_cont_plancta_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_cont_plancta_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fcont_planctaedit_x_moneda"
        <?php } ?>
        data-table="cont_plancta"
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
loadjs.ready("fcont_planctaedit", function() {
    var options = { name: "x_moneda", selectId: "fcont_planctaedit_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_planctaedit.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fcont_planctaedit" };
    } else {
        options.ajax = { id: "x_moneda", form: "fcont_planctaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_plancta.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activa->Visible) { // activa ?>
    <div id="r_activa"<?= $Page->activa->rowAttributes() ?>>
        <label id="elh_cont_plancta_activa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activa->caption() ?><?= $Page->activa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activa->cellAttributes() ?>>
<span id="el_cont_plancta_activa">
<template id="tp_x_activa">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_plancta" data-field="x_activa" name="x_activa" id="x_activa"<?= $Page->activa->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_activa" class="ew-item-list"></div>
<selection-list hidden
    id="x_activa"
    name="x_activa"
    value="<?= HtmlEncode($Page->activa->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_activa"
    data-target="dsl_x_activa"
    data-repeatcolumn="5"
    class="form-control<?= $Page->activa->isInvalidClass() ?>"
    data-table="cont_plancta"
    data-field="x_activa"
    data-value-separator="<?= $Page->activa->displayValueSeparatorAttribute() ?>"
    <?= $Page->activa->editAttributes() ?>></selection-list>
<?= $Page->activa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->activa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_plancta" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_planctaedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_planctaedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_plancta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
