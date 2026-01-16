<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContPeriodoContableEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="fcont_periodo_contableedit" id="fcont_periodo_contableedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cont_periodo_contable: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fcont_periodo_contableedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_periodo_contableedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["fecha_inicio", [fields.fecha_inicio.visible && fields.fecha_inicio.required ? ew.Validators.required(fields.fecha_inicio.caption) : null], fields.fecha_inicio.isInvalid],
            ["fecha_fin", [fields.fecha_fin.visible && fields.fecha_fin.required ? ew.Validators.required(fields.fecha_fin.caption) : null], fields.fecha_fin.isInvalid],
            ["cerrado", [fields.cerrado.visible && fields.cerrado.required ? ew.Validators.required(fields.cerrado.caption) : null], fields.cerrado.isInvalid]
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
            "cerrado": <?= $Page->cerrado->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="cont_periodo_contable">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_cont_periodo_contable_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_cont_periodo_contable_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id->getDisplayValue($Page->id->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_periodo_contable" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_inicio->Visible) { // fecha_inicio ?>
    <div id="r_fecha_inicio"<?= $Page->fecha_inicio->rowAttributes() ?>>
        <label id="elh_cont_periodo_contable_fecha_inicio" for="x_fecha_inicio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_inicio->caption() ?><?= $Page->fecha_inicio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_inicio->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_inicio">
<span<?= $Page->fecha_inicio->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha_inicio->getDisplayValue($Page->fecha_inicio->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_periodo_contable" data-field="x_fecha_inicio" data-hidden="1" name="x_fecha_inicio" id="x_fecha_inicio" value="<?= HtmlEncode($Page->fecha_inicio->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_fin->Visible) { // fecha_fin ?>
    <div id="r_fecha_fin"<?= $Page->fecha_fin->rowAttributes() ?>>
        <label id="elh_cont_periodo_contable_fecha_fin" for="x_fecha_fin" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_fin->caption() ?><?= $Page->fecha_fin->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fecha_fin->cellAttributes() ?>>
<span id="el_cont_periodo_contable_fecha_fin">
<span<?= $Page->fecha_fin->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->fecha_fin->getDisplayValue($Page->fecha_fin->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_periodo_contable" data-field="x_fecha_fin" data-hidden="1" name="x_fecha_fin" id="x_fecha_fin" value="<?= HtmlEncode($Page->fecha_fin->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cerrado->Visible) { // cerrado ?>
    <div id="r_cerrado"<?= $Page->cerrado->rowAttributes() ?>>
        <label id="elh_cont_periodo_contable_cerrado" for="x_cerrado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cerrado->caption() ?><?= $Page->cerrado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cerrado->cellAttributes() ?>>
<span id="el_cont_periodo_contable_cerrado">
    <select
        id="x_cerrado"
        name="x_cerrado"
        class="form-select ew-select<?= $Page->cerrado->isInvalidClass() ?>"
        <?php if (!$Page->cerrado->IsNativeSelect) { ?>
        data-select2-id="fcont_periodo_contableedit_x_cerrado"
        <?php } ?>
        data-table="cont_periodo_contable"
        data-field="x_cerrado"
        data-value-separator="<?= $Page->cerrado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cerrado->getPlaceHolder()) ?>"
        <?= $Page->cerrado->editAttributes() ?>>
        <?= $Page->cerrado->selectOptionListHtml("x_cerrado") ?>
    </select>
    <?= $Page->cerrado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cerrado->getErrorMessage() ?></div>
<?php if (!$Page->cerrado->IsNativeSelect) { ?>
<script>
loadjs.ready("fcont_periodo_contableedit", function() {
    var options = { name: "x_cerrado", selectId: "fcont_periodo_contableedit_x_cerrado" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcont_periodo_contableedit.lists.cerrado?.lookupOptions.length) {
        options.data = { id: "x_cerrado", form: "fcont_periodo_contableedit" };
    } else {
        options.ajax = { id: "x_cerrado", form: "fcont_periodo_contableedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cont_periodo_contable.fields.cerrado.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcont_periodo_contableedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcont_periodo_contableedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("cont_periodo_contable");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
