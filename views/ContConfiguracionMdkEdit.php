<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContConfiguracionMdkEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcont_configuracion_mdkedit" id="fcont_configuracion_mdkedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_configuracion_mdk: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcont_configuracion_mdkedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_configuracion_mdkedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["clave", [fields.clave.visible && fields.clave.required ? ew.Validators.required(fields.clave.caption) : null], fields.clave.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["cuenta_id", [fields.cuenta_id.visible && fields.cuenta_id.required ? ew.Validators.required(fields.cuenta_id.caption) : null], fields.cuenta_id.isInvalid],
            ["valor_texto", [fields.valor_texto.visible && fields.valor_texto.required ? ew.Validators.required(fields.valor_texto.caption) : null], fields.valor_texto.isInvalid],
            ["valor_numero", [fields.valor_numero.visible && fields.valor_numero.required ? ew.Validators.required(fields.valor_numero.caption) : null, ew.Validators.float], fields.valor_numero.isInvalid],
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
            "cuenta_id": <?= $Page->cuenta_id->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="cont_configuracion_mdk">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->clave->Visible) { // clave ?>
    <div id="r_clave"<?= $Page->clave->rowAttributes() ?>>
        <label id="elh_cont_configuracion_mdk_clave" for="x_clave" class="<?= $Page->LeftColumnClass ?>"><?= $Page->clave->caption() ?><?= $Page->clave->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->clave->cellAttributes() ?>>
<span id="el_cont_configuracion_mdk_clave">
<input type="<?= $Page->clave->getInputTextType() ?>" name="x_clave" id="x_clave" data-table="cont_configuracion_mdk" data-field="x_clave" value="<?= $Page->clave->EditValue ?>" size="30" maxlength="60" placeholder="<?= HtmlEncode($Page->clave->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->clave->formatPattern()) ?>"<?= $Page->clave->editAttributes() ?> aria-describedby="x_clave_help">
<?= $Page->clave->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->clave->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_cont_configuracion_mdk_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_cont_configuracion_mdk_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="cont_configuracion_mdk" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
    <div id="r_cuenta_id"<?= $Page->cuenta_id->rowAttributes() ?>>
        <label id="elh_cont_configuracion_mdk_cuenta_id" for="x_cuenta_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta_id->caption() ?><?= $Page->cuenta_id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta_id->cellAttributes() ?>>
<span id="el_cont_configuracion_mdk_cuenta_id">
    <select
        id="x_cuenta_id"
        name="x_cuenta_id"
        class="form-control ew-select<?= $Page->cuenta_id->isInvalidClass() ?>"
        data-select2-id="fcont_configuracion_mdkedit_x_cuenta_id"
        data-table="cont_configuracion_mdk"
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
loadjs.ready("fcont_configuracion_mdkedit", function() {
    var options = { name: "x_cuenta_id", selectId: "fcont_configuracion_mdkedit_x_cuenta_id" };
    if (fcont_configuracion_mdkedit.lists.cuenta_id?.lookupOptions.length) {
        options.data = { id: "x_cuenta_id", form: "fcont_configuracion_mdkedit" };
    } else {
        options.ajax = { id: "x_cuenta_id", form: "fcont_configuracion_mdkedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_configuracion_mdk.fields.cuenta_id.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor_texto->Visible) { // valor_texto ?>
    <div id="r_valor_texto"<?= $Page->valor_texto->rowAttributes() ?>>
        <label id="elh_cont_configuracion_mdk_valor_texto" for="x_valor_texto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor_texto->caption() ?><?= $Page->valor_texto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->valor_texto->cellAttributes() ?>>
<span id="el_cont_configuracion_mdk_valor_texto">
<input type="<?= $Page->valor_texto->getInputTextType() ?>" name="x_valor_texto" id="x_valor_texto" data-table="cont_configuracion_mdk" data-field="x_valor_texto" value="<?= $Page->valor_texto->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->valor_texto->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->valor_texto->formatPattern()) ?>"<?= $Page->valor_texto->editAttributes() ?> aria-describedby="x_valor_texto_help">
<?= $Page->valor_texto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor_texto->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->valor_numero->Visible) { // valor_numero ?>
    <div id="r_valor_numero"<?= $Page->valor_numero->rowAttributes() ?>>
        <label id="elh_cont_configuracion_mdk_valor_numero" for="x_valor_numero" class="<?= $Page->LeftColumnClass ?>"><?= $Page->valor_numero->caption() ?><?= $Page->valor_numero->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->valor_numero->cellAttributes() ?>>
<span id="el_cont_configuracion_mdk_valor_numero">
<input type="<?= $Page->valor_numero->getInputTextType() ?>" name="x_valor_numero" id="x_valor_numero" data-table="cont_configuracion_mdk" data-field="x_valor_numero" value="<?= $Page->valor_numero->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->valor_numero->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->valor_numero->formatPattern()) ?>"<?= $Page->valor_numero->editAttributes() ?> aria-describedby="x_valor_numero_help">
<?= $Page->valor_numero->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->valor_numero->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->estado->Visible) { // estado ?>
    <div id="r_estado"<?= $Page->estado->rowAttributes() ?>>
        <label id="elh_cont_configuracion_mdk_estado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->estado->caption() ?><?= $Page->estado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->estado->cellAttributes() ?>>
<span id="el_cont_configuracion_mdk_estado">
<div class="form-check d-inline-block">
    <input type="checkbox" class="form-check-input<?= $Page->estado->isInvalidClass() ?>" data-table="cont_configuracion_mdk" data-field="x_estado" data-boolean name="x_estado" id="x_estado" value="1"<?= ConvertToBool($Page->estado->CurrentValue) ? " checked" : "" ?><?= $Page->estado->editAttributes() ?> aria-describedby="x_estado_help">
    <div class="invalid-feedback"><?= $Page->estado->getErrorMessage() ?></div>
</div>
<?= $Page->estado->getCustomMessage() ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_configuracion_mdk" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_configuracion_mdkedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_configuracion_mdkedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_configuracion_mdk");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
