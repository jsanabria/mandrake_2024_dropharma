<?php

namespace PHPMaker2024\mandrake;

// Page object
$TarifaEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="ftarifaedit" id="ftarifaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tarifa: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ftarifaedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftarifaedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid],
            ["patron", [fields.patron.visible && fields.patron.required ? ew.Validators.required(fields.patron.caption) : null], fields.patron.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid],
            ["porcentaje", [fields.porcentaje.visible && fields.porcentaje.required ? ew.Validators.required(fields.porcentaje.caption) : null, ew.Validators.float], fields.porcentaje.isInvalid]
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
            "patron": <?= $Page->patron->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="tarifa">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_tarifa_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_tarifa_nombre">
<input type="<?= $Page->nombre->getInputTextType() ?>" name="x_nombre" id="x_nombre" data-table="tarifa" data-field="x_nombre" value="<?= $Page->nombre->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre->formatPattern()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help">
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->patron->Visible) { // patron ?>
    <div id="r_patron"<?= $Page->patron->rowAttributes() ?>>
        <label id="elh_tarifa_patron" for="x_patron" class="<?= $Page->LeftColumnClass ?>"><?= $Page->patron->caption() ?><?= $Page->patron->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->patron->cellAttributes() ?>>
<span id="el_tarifa_patron">
    <select
        id="x_patron"
        name="x_patron"
        class="form-select ew-select<?= $Page->patron->isInvalidClass() ?>"
        <?php if (!$Page->patron->IsNativeSelect) { ?>
        data-select2-id="ftarifaedit_x_patron"
        <?php } ?>
        data-table="tarifa"
        data-field="x_patron"
        data-value-separator="<?= $Page->patron->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->patron->getPlaceHolder()) ?>"
        <?= $Page->patron->editAttributes() ?>>
        <?= $Page->patron->selectOptionListHtml("x_patron") ?>
    </select>
    <?= $Page->patron->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->patron->getErrorMessage() ?></div>
<?php if (!$Page->patron->IsNativeSelect) { ?>
<script>
loadjs.ready("ftarifaedit", function() {
    var options = { name: "x_patron", selectId: "ftarifaedit_x_patron" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftarifaedit.lists.patron?.lookupOptions.length) {
        options.data = { id: "x_patron", form: "ftarifaedit" };
    } else {
        options.ajax = { id: "x_patron", form: "ftarifaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.tarifa.fields.patron.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_tarifa_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_tarifa_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="ftarifaedit_x_activo"
        <?php } ?>
        data-table="tarifa"
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
loadjs.ready("ftarifaedit", function() {
    var options = { name: "x_activo", selectId: "ftarifaedit_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftarifaedit.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "ftarifaedit" };
    } else {
        options.ajax = { id: "x_activo", form: "ftarifaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.tarifa.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->porcentaje->Visible) { // porcentaje ?>
    <div id="r_porcentaje"<?= $Page->porcentaje->rowAttributes() ?>>
        <label id="elh_tarifa_porcentaje" for="x_porcentaje" class="<?= $Page->LeftColumnClass ?>"><?= $Page->porcentaje->caption() ?><?= $Page->porcentaje->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->porcentaje->cellAttributes() ?>>
<span id="el_tarifa_porcentaje">
<input type="<?= $Page->porcentaje->getInputTextType() ?>" name="x_porcentaje" id="x_porcentaje" data-table="tarifa" data-field="x_porcentaje" value="<?= $Page->porcentaje->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->porcentaje->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->porcentaje->formatPattern()) ?>"<?= $Page->porcentaje->editAttributes() ?> aria-describedby="x_porcentaje_help">
<?= $Page->porcentaje->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->porcentaje->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="tarifa" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("tarifa_descuento_utilidad", explode(",", $Page->getCurrentDetailTable())) && $tarifa_descuento_utilidad->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("tarifa_descuento_utilidad", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "TarifaDescuentoUtilidadGrid.php" ?>
<?php } ?>
<?php
    if (in_array("tarifa_articulo", explode(",", $Page->getCurrentDetailTable())) && $tarifa_articulo->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("tarifa_articulo", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "TarifaArticuloGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftarifaedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftarifaedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("tarifa");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
