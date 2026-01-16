<?php

namespace PHPMaker2024\mandrake;

// Page object
$ArticuloUnidadMedidaAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { articulo_unidad_medida: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var farticulo_unidad_medidaadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("farticulo_unidad_medidaadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["unidad_medida", [fields.unidad_medida.visible && fields.unidad_medida.required ? ew.Validators.required(fields.unidad_medida.caption) : null], fields.unidad_medida.isInvalid]
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
            "unidad_medida": <?= $Page->unidad_medida->toClientList($Page) ?>,
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
<form name="farticulo_unidad_medidaadd" id="farticulo_unidad_medidaadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo_unidad_medida">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "articulo") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="articulo">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->articulo->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->unidad_medida->Visible) { // unidad_medida ?>
    <div id="r_unidad_medida"<?= $Page->unidad_medida->rowAttributes() ?>>
        <label id="elh_articulo_unidad_medida_unidad_medida" for="x_unidad_medida" class="<?= $Page->LeftColumnClass ?>"><?= $Page->unidad_medida->caption() ?><?= $Page->unidad_medida->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->unidad_medida->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_unidad_medida">
    <select
        id="x_unidad_medida"
        name="x_unidad_medida"
        class="form-select ew-select<?= $Page->unidad_medida->isInvalidClass() ?>"
        <?php if (!$Page->unidad_medida->IsNativeSelect) { ?>
        data-select2-id="farticulo_unidad_medidaadd_x_unidad_medida"
        <?php } ?>
        data-table="articulo_unidad_medida"
        data-field="x_unidad_medida"
        data-value-separator="<?= $Page->unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->unidad_medida->getPlaceHolder()) ?>"
        <?= $Page->unidad_medida->editAttributes() ?>>
        <?= $Page->unidad_medida->selectOptionListHtml("x_unidad_medida") ?>
    </select>
    <?= $Page->unidad_medida->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->unidad_medida->getErrorMessage() ?></div>
<?= $Page->unidad_medida->Lookup->getParamTag($Page, "p_x_unidad_medida") ?>
<?php if (!$Page->unidad_medida->IsNativeSelect) { ?>
<script>
loadjs.ready("farticulo_unidad_medidaadd", function() {
    var options = { name: "x_unidad_medida", selectId: "farticulo_unidad_medidaadd_x_unidad_medida" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticulo_unidad_medidaadd.lists.unidad_medida?.lookupOptions.length) {
        options.data = { id: "x_unidad_medida", form: "farticulo_unidad_medidaadd" };
    } else {
        options.ajax = { id: "x_unidad_medida", form: "farticulo_unidad_medidaadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo_unidad_medida.fields.unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->articulo->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_articulo" id="x_articulo" value="<?= HtmlEncode(strval($Page->articulo->getSessionValue())) ?>">
    <?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="farticulo_unidad_medidaadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="farticulo_unidad_medidaadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("articulo_unidad_medida");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
