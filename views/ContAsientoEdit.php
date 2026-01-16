<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContAsientoEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcont_asientoedit" id="fcont_asientoedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_asiento: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcont_asientoedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_asientoedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["debe", [fields.debe.visible && fields.debe.required ? ew.Validators.required(fields.debe.caption) : null, ew.Validators.float], fields.debe.isInvalid],
            ["haber", [fields.haber.visible && fields.haber.required ? ew.Validators.required(fields.haber.caption) : null, ew.Validators.float], fields.haber.isInvalid]
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
            "cuenta": <?= $Page->cuenta->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="cont_asiento">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "cont_comprobante") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="cont_comprobante">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->comprobante->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <div id="r_cuenta"<?= $Page->cuenta->rowAttributes() ?>>
        <label id="elh_cont_asiento_cuenta" for="x_cuenta" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cuenta->caption() ?><?= $Page->cuenta->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cuenta->cellAttributes() ?>>
<span id="el_cont_asiento_cuenta">
    <select
        id="x_cuenta"
        name="x_cuenta"
        class="form-control ew-select<?= $Page->cuenta->isInvalidClass() ?>"
        data-select2-id="fcont_asientoedit_x_cuenta"
        data-table="cont_asiento"
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
loadjs.ready("fcont_asientoedit", function() {
    var options = { name: "x_cuenta", selectId: "fcont_asientoedit_x_cuenta" };
    if (fcont_asientoedit.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x_cuenta", form: "fcont_asientoedit" };
    } else {
        options.ajax = { id: "x_cuenta", form: "fcont_asientoedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nota->Visible) { // nota ?>
    <div id="r_nota"<?= $Page->nota->rowAttributes() ?>>
        <label id="elh_cont_asiento_nota" for="x_nota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nota->caption() ?><?= $Page->nota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nota->cellAttributes() ?>>
<span id="el_cont_asiento_nota">
<input type="<?= $Page->nota->getInputTextType() ?>" name="x_nota" id="x_nota" data-table="cont_asiento" data-field="x_nota" value="<?= $Page->nota->EditValue ?>" size="10" maxlength="60" placeholder="<?= HtmlEncode($Page->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nota->formatPattern()) ?>"<?= $Page->nota->editAttributes() ?> aria-describedby="x_nota_help">
<?= $Page->nota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <div id="r_referencia"<?= $Page->referencia->rowAttributes() ?>>
        <label id="elh_cont_asiento_referencia" for="x_referencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->referencia->caption() ?><?= $Page->referencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->referencia->cellAttributes() ?>>
<span id="el_cont_asiento_referencia">
<input type="<?= $Page->referencia->getInputTextType() ?>" name="x_referencia" id="x_referencia" data-table="cont_asiento" data-field="x_referencia" value="<?= $Page->referencia->EditValue ?>" size="10" maxlength="25" placeholder="<?= HtmlEncode($Page->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->referencia->formatPattern()) ?>"<?= $Page->referencia->editAttributes() ?> aria-describedby="x_referencia_help">
<?= $Page->referencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->referencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->debe->Visible) { // debe ?>
    <div id="r_debe"<?= $Page->debe->rowAttributes() ?>>
        <label id="elh_cont_asiento_debe" for="x_debe" class="<?= $Page->LeftColumnClass ?>"><?= $Page->debe->caption() ?><?= $Page->debe->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->debe->cellAttributes() ?>>
<span id="el_cont_asiento_debe">
<input type="<?= $Page->debe->getInputTextType() ?>" name="x_debe" id="x_debe" data-table="cont_asiento" data-field="x_debe" value="<?= $Page->debe->EditValue ?>" size="12" placeholder="<?= HtmlEncode($Page->debe->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->debe->formatPattern()) ?>"<?= $Page->debe->editAttributes() ?> aria-describedby="x_debe_help">
<?= $Page->debe->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->debe->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->haber->Visible) { // haber ?>
    <div id="r_haber"<?= $Page->haber->rowAttributes() ?>>
        <label id="elh_cont_asiento_haber" for="x_haber" class="<?= $Page->LeftColumnClass ?>"><?= $Page->haber->caption() ?><?= $Page->haber->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->haber->cellAttributes() ?>>
<span id="el_cont_asiento_haber">
<input type="<?= $Page->haber->getInputTextType() ?>" name="x_haber" id="x_haber" data-table="cont_asiento" data-field="x_haber" value="<?= $Page->haber->EditValue ?>" size="12" placeholder="<?= HtmlEncode($Page->haber->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->haber->formatPattern()) ?>"<?= $Page->haber->editAttributes() ?> aria-describedby="x_haber_help">
<?= $Page->haber->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->haber->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="cont_asiento" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_asientoedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_asientoedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_asiento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
