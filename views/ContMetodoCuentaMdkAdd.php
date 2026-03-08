<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContMetodoCuentaMdkAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_metodo_cuenta_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcont_metodo_cuenta_mdkadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_metodo_cuenta_mdkadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["metodo_pago", [fields.metodo_pago.visible && fields.metodo_pago.required ? ew.Validators.required(fields.metodo_pago.caption) : null], fields.metodo_pago.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["tipo_destino", [fields.tipo_destino.visible && fields.tipo_destino.required ? ew.Validators.required(fields.tipo_destino.caption) : null], fields.tipo_destino.isInvalid],
            ["cuenta_id", [fields.cuenta_id.visible && fields.cuenta_id.required ? ew.Validators.required(fields.cuenta_id.caption) : null], fields.cuenta_id.isInvalid],
            ["forzar_destino", [fields.forzar_destino.visible && fields.forzar_destino.required ? ew.Validators.required(fields.forzar_destino.caption) : null], fields.forzar_destino.isInvalid],
            ["estado", [fields.estado.visible && fields.estado.required ? ew.Validators.required(fields.estado.caption) : null], fields.estado.isInvalid]
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
            "tipo_destino": <?= $Page->tipo_destino->toClientList($Page) ?>,
            "cuenta_id": <?= $Page->cuenta_id->toClientList($Page) ?>,
            "forzar_destino": <?= $Page->forzar_destino->toClientList($Page) ?>,
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
<form name="fcont_metodo_cuenta_mdkadd" id="fcont_metodo_cuenta_mdkadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_metodo_cuenta_mdk">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <div id="r_metodo_pago"<?= $Page->metodo_pago->rowAttributes() ?>>
        <label id="elh_cont_metodo_cuenta_mdk_metodo_pago" for="x_metodo_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->metodo_pago->caption() ?><?= $Page->metodo_pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->metodo_pago->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_metodo_pago">
<input type="<?= $Page->metodo_pago->getInputTextType() ?>" name="x_metodo_pago" id="x_metodo_pago" data-table="cont_metodo_cuenta_mdk" data-field="x_metodo_pago" value="<?= $Page->metodo_pago->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->metodo_pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->metodo_pago->formatPattern()) ?>"<?= $Page->metodo_pago->editAttributes() ?> aria-describedby="x_metodo_pago_help">
<?= $Page->metodo_pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->metodo_pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_cont_metodo_cuenta_mdk_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="cont_metodo_cuenta_mdk" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_destino->Visible) { // tipo_destino ?>
    <div id="r_tipo_destino"<?= $Page->tipo_destino->rowAttributes() ?>>
        <label id="elh_cont_metodo_cuenta_mdk_tipo_destino" for="x_tipo_destino" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_destino->caption() ?><?= $Page->tipo_destino->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo_destino->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_tipo_destino">
    <select
        id="x_tipo_destino"
        name="x_tipo_destino"
        class="form-select ew-select<?= $Page->tipo_destino->isInvalidClass() ?>"
        <?php if (!$Page->tipo_destino->IsNativeSelect) { ?>
        data-select2-id="fcont_metodo_cuenta_mdkadd_x_tipo_destino"
        <?php } ?>
        data-table="cont_metodo_cuenta_mdk"
        data-field="x_tipo_destino"
        data-value-separator="<?= $Page->tipo_destino->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_destino->getPlaceHolder()) ?>"
        <?= $Page->tipo_destino->editAttributes() ?>>
        <?= $Page->tipo_destino->selectOptionListHtml("x_tipo_destino") ?>
    </select>
    <?= $Page->tipo_destino->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_destino->getErrorMessage() ?></div>
<?php if (!$Page->tipo_destino->IsNativeSelect) { ?>
<script>
loadjs.ready("fcont_metodo_cuenta_mdkadd", function() {
    var options = { name: "x_tipo_destino", selectId: "fcont_metodo_cuenta_mdkadd_x_tipo_destino" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_metodo_cuenta_mdkadd.lists.tipo_destino?.lookupOptions.length) {
        options.data = { id: "x_tipo_destino", form: "fcont_metodo_cuenta_mdkadd" };
    } else {
        options.ajax = { id: "x_tipo_destino", form: "fcont_metodo_cuenta_mdkadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_metodo_cuenta_mdk.fields.tipo_destino.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
    <div id="r_cuenta_id"<?= $Page->cuenta_id->rowAttributes() ?>>
        <label id="elh_cont_metodo_cuenta_mdk_cuenta_id" for="x_cuenta_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta_id->caption() ?><?= $Page->cuenta_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta_id->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_cuenta_id">
    <select
        id="x_cuenta_id"
        name="x_cuenta_id"
        class="form-control ew-select<?= $Page->cuenta_id->isInvalidClass() ?>"
        data-select2-id="fcont_metodo_cuenta_mdkadd_x_cuenta_id"
        data-table="cont_metodo_cuenta_mdk"
        data-field="x_cuenta_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cuenta_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cuenta_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cuenta_id->getPlaceHolder()) ?>"
        <?= $Page->cuenta_id->editAttributes() ?>>
        <?= $Page->cuenta_id->selectOptionListHtml("x_cuenta_id") ?>
    </select>
    <?= $Page->cuenta_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cuenta_id->getErrorMessage() ?></div>
<?= $Page->cuenta_id->Lookup->getParamTag($Page, "p_x_cuenta_id") ?>
<script>
loadjs.ready("fcont_metodo_cuenta_mdkadd", function() {
    var options = { name: "x_cuenta_id", selectId: "fcont_metodo_cuenta_mdkadd_x_cuenta_id" };
    if (fcont_metodo_cuenta_mdkadd.lists.cuenta_id?.lookupOptions.length) {
        options.data = { id: "x_cuenta_id", form: "fcont_metodo_cuenta_mdkadd" };
    } else {
        options.ajax = { id: "x_cuenta_id", form: "fcont_metodo_cuenta_mdkadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_metodo_cuenta_mdk.fields.cuenta_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->forzar_destino->Visible) { // forzar_destino ?>
    <div id="r_forzar_destino"<?= $Page->forzar_destino->rowAttributes() ?>>
        <label id="elh_cont_metodo_cuenta_mdk_forzar_destino" class="<?= $Page->LeftColumnClass ?>"><?= $Page->forzar_destino->caption() ?><?= $Page->forzar_destino->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->forzar_destino->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_forzar_destino">
<template id="tp_x_forzar_destino">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_metodo_cuenta_mdk" data-field="x_forzar_destino" name="x_forzar_destino" id="x_forzar_destino"<?= $Page->forzar_destino->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_forzar_destino" class="ew-item-list"></div>
<selection-list hidden
    id="x_forzar_destino"
    name="x_forzar_destino"
    value="<?= HtmlEncode($Page->forzar_destino->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_forzar_destino"
    data-target="dsl_x_forzar_destino"
    data-repeatcolumn="5"
    class="form-control<?= $Page->forzar_destino->isInvalidClass() ?>"
    data-table="cont_metodo_cuenta_mdk"
    data-field="x_forzar_destino"
    data-value-separator="<?= $Page->forzar_destino->displayValueSeparatorAttribute() ?>"
    <?= $Page->forzar_destino->editAttributes() ?>></selection-list>
<?= $Page->forzar_destino->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->forzar_destino->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <div id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <label id="elh_cont_metodo_cuenta_mdk_estado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estado->caption() ?><?= $Page->estado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_metodo_cuenta_mdk_estado">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->estado->isInvalidClass() ?>" data-table="cont_metodo_cuenta_mdk" data-field="x_estado" data-boolean name="x_estado" id="x_estado" value="1"<?= ConvertToBool($Page->estado->CurrentValue) ? " checked" : "" ?><?= $Page->estado->editAttributes() ?> aria-describedby="x_estado_help">
    <div class="invalid-feedback"><?= $Page->estado->getErrorMessage() ?></div>
</div>
<?= $Page->estado->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_metodo_cuenta_mdkadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_metodo_cuenta_mdkadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_metodo_cuenta_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
