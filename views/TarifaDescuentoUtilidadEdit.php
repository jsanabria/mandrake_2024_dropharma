<?php

namespace PHPMaker2024\mandrake;

// Page object
$TarifaDescuentoUtilidadEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="ftarifa_descuento_utilidadedit" id="ftarifa_descuento_utilidadedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tarifa_descuento_utilidad: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ftarifa_descuento_utilidadedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftarifa_descuento_utilidadedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["utilidad", [fields.utilidad.visible && fields.utilidad.required ? ew.Validators.required(fields.utilidad.caption) : null, ew.Validators.float], fields.utilidad.isInvalid]
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
            "fabricante": <?= $Page->fabricante->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="tarifa_descuento_utilidad">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "tarifa") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="tarifa">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->tarifa->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_tarifa_descuento_utilidad_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_tarifa_descuento_utilidad_fabricante">
    <select
        id="x_fabricante"
        name="x_fabricante"
        class="form-control ew-select<?= $Page->fabricante->isInvalidClass() ?>"
        data-select2-id="ftarifa_descuento_utilidadedit_x_fabricante"
        data-table="tarifa_descuento_utilidad"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>"
        <?= $Page->fabricante->editAttributes() ?>>
        <?= $Page->fabricante->selectOptionListHtml("x_fabricante") ?>
    </select>
    <?= $Page->fabricante->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<script>
loadjs.ready("ftarifa_descuento_utilidadedit", function() {
    var options = { name: "x_fabricante", selectId: "ftarifa_descuento_utilidadedit_x_fabricante" };
    if (ftarifa_descuento_utilidadedit.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x_fabricante", form: "ftarifa_descuento_utilidadedit" };
    } else {
        options.ajax = { id: "x_fabricante", form: "ftarifa_descuento_utilidadedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.tarifa_descuento_utilidad.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <label id="elh_tarifa_descuento_utilidad_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descuento->cellAttributes() ?>>
<span id="el_tarifa_descuento_utilidad_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x_descuento" id="x_descuento" data-table="tarifa_descuento_utilidad" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->utilidad->Visible) { // utilidad ?>
    <div id="r_utilidad"<?= $Page->utilidad->rowAttributes() ?>>
        <label id="elh_tarifa_descuento_utilidad_utilidad" for="x_utilidad" class="<?= $Page->LeftColumnClass ?>"><?= $Page->utilidad->caption() ?><?= $Page->utilidad->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->utilidad->cellAttributes() ?>>
<span id="el_tarifa_descuento_utilidad_utilidad">
<input type="<?= $Page->utilidad->getInputTextType() ?>" name="x_utilidad" id="x_utilidad" data-table="tarifa_descuento_utilidad" data-field="x_utilidad" value="<?= $Page->utilidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->utilidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->utilidad->formatPattern()) ?>"<?= $Page->utilidad->editAttributes() ?> aria-describedby="x_utilidad_help">
<?= $Page->utilidad->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->utilidad->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="tarifa_descuento_utilidad" data-field="x_Id" data-hidden="1" name="x_Id" id="x_Id" value="<?= HtmlEncode($Page->Id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftarifa_descuento_utilidadedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftarifa_descuento_utilidadedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("tarifa_descuento_utilidad");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
