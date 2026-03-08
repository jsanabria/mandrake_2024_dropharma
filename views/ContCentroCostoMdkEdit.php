<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContCentroCostoMdkEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcont_centro_costo_mdkedit" id="fcont_centro_costo_mdkedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_centro_costo_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcont_centro_costo_mdkedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_centro_costo_mdkedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["centro_padre_id", [fields.centro_padre_id.visible && fields.centro_padre_id.required ? ew.Validators.required(fields.centro_padre_id.caption) : null], fields.centro_padre_id.isInvalid],
            ["nivel", [fields.nivel.visible && fields.nivel.required ? ew.Validators.required(fields.nivel.caption) : null, ew.Validators.integer], fields.nivel.isInvalid],
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
            "centro_padre_id": <?= $Page->centro_padre_id->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cont_centro_costo_mdk">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <label id="elh_cont_centro_costo_mdk_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" name="x_codigo" id="x_codigo" data-table="cont_centro_costo_mdk" data-field="x_codigo" value="<?= $Page->codigo->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo->formatPattern()) ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_cont_centro_costo_mdk_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="cont_centro_costo_mdk" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="120" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_cont_centro_costo_mdk_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="cont_centro_costo_mdk" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->centro_padre_id->Visible) { // centro_padre_id ?>
    <div id="r_centro_padre_id"<?= $Page->centro_padre_id->rowAttributes() ?>>
        <label id="elh_cont_centro_costo_mdk_centro_padre_id" for="x_centro_padre_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->centro_padre_id->caption() ?><?= $Page->centro_padre_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->centro_padre_id->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_centro_padre_id">
    <select
        id="x_centro_padre_id"
        name="x_centro_padre_id"
        class="form-control ew-select<?= $Page->centro_padre_id->isInvalidClass() ?>"
        data-select2-id="fcont_centro_costo_mdkedit_x_centro_padre_id"
        data-table="cont_centro_costo_mdk"
        data-field="x_centro_padre_id"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->centro_padre_id->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->centro_padre_id->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->centro_padre_id->getPlaceHolder()) ?>"
        <?= $Page->centro_padre_id->editAttributes() ?>>
        <?= $Page->centro_padre_id->selectOptionListHtml("x_centro_padre_id") ?>
    </select>
    <?= $Page->centro_padre_id->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->centro_padre_id->getErrorMessage() ?></div>
<?= $Page->centro_padre_id->Lookup->getParamTag($Page, "p_x_centro_padre_id") ?>
<script>
loadjs.ready("fcont_centro_costo_mdkedit", function() {
    var options = { name: "x_centro_padre_id", selectId: "fcont_centro_costo_mdkedit_x_centro_padre_id" };
    if (fcont_centro_costo_mdkedit.lists.centro_padre_id?.lookupOptions.length) {
        options.data = { id: "x_centro_padre_id", form: "fcont_centro_costo_mdkedit" };
    } else {
        options.ajax = { id: "x_centro_padre_id", form: "fcont_centro_costo_mdkedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_centro_costo_mdk.fields.centro_padre_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nivel->Visible) { // nivel ?>
    <div id="r_nivel"<?= $Page->nivel->rowAttributes() ?>>
        <label id="elh_cont_centro_costo_mdk_nivel" for="x_nivel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nivel->caption() ?><?= $Page->nivel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nivel->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_nivel">
<input type="<?= $Page->nivel->getInputTextType() ?>" name="x_nivel" id="x_nivel" data-table="cont_centro_costo_mdk" data-field="x_nivel" value="<?= $Page->nivel->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->nivel->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nivel->formatPattern()) ?>"<?= $Page->nivel->editAttributes() ?> aria-describedby="x_nivel_help">
<?= $Page->nivel->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nivel->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <div id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <label id="elh_cont_centro_costo_mdk_estado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estado->caption() ?><?= $Page->estado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_centro_costo_mdk_estado">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->estado->isInvalidClass() ?>" data-table="cont_centro_costo_mdk" data-field="x_estado" data-boolean name="x_estado" id="x_estado" value="1"<?= ConvertToBool($Page->estado->CurrentValue) ? " checked" : "" ?><?= $Page->estado->editAttributes() ?> aria-describedby="x_estado_help">
    <div class="invalid-feedback"><?= $Page->estado->getErrorMessage() ?></div>
</div>
<?= $Page->estado->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_centro_costo_mdk" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_centro_costo_mdkedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_centro_costo_mdkedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_centro_costo_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
