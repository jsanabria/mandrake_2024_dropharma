<?php

namespace PHPMaker2024\mandrake;

// Page object
$TablaEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="ftablaedit" id="ftablaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tabla: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ftablaedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftablaedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["tabla", [fields.tabla.visible && fields.tabla.required ? ew.Validators.required(fields.tabla.caption) : null], fields.tabla.isInvalid],
            ["campo_codigo", [fields.campo_codigo.visible && fields.campo_codigo.required ? ew.Validators.required(fields.campo_codigo.caption) : null], fields.campo_codigo.isInvalid],
            ["campo_descripcion", [fields.campo_descripcion.visible && fields.campo_descripcion.required ? ew.Validators.required(fields.campo_descripcion.caption) : null], fields.campo_descripcion.isInvalid],
            ["campo_dato", [fields.campo_dato.visible && fields.campo_dato.required ? ew.Validators.required(fields.campo_dato.caption) : null], fields.campo_dato.isInvalid]
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
            "campo_dato": <?= $Page->campo_dato->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="tabla">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->tabla->Visible) { // tabla ?>
    <div id="r_tabla"<?= $Page->tabla->rowAttributes() ?>>
        <label id="elh_tabla_tabla" for="x_tabla" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tabla->caption() ?><?= $Page->tabla->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tabla->cellAttributes() ?>>
<span id="el_tabla_tabla">
<span<?= $Page->tabla->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->tabla->getDisplayValue($Page->tabla->EditValue) ?></span></span>
<input type="hidden" data-table="tabla" data-field="x_tabla" data-hidden="1" name="x_tabla" id="x_tabla" value="<?= HtmlEncode($Page->tabla->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->campo_codigo->Visible) { // campo_codigo ?>
    <div id="r_campo_codigo"<?= $Page->campo_codigo->rowAttributes() ?>>
        <label id="elh_tabla_campo_codigo" for="x_campo_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->campo_codigo->caption() ?><?= $Page->campo_codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->campo_codigo->cellAttributes() ?>>
<span id="el_tabla_campo_codigo">
<span<?= $Page->campo_codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->campo_codigo->getDisplayValue($Page->campo_codigo->EditValue))) ?>"></span>
<input type="hidden" data-table="tabla" data-field="x_campo_codigo" data-hidden="1" name="x_campo_codigo" id="x_campo_codigo" value="<?= HtmlEncode($Page->campo_codigo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->campo_descripcion->Visible) { // campo_descripcion ?>
    <div id="r_campo_descripcion"<?= $Page->campo_descripcion->rowAttributes() ?>>
        <label id="elh_tabla_campo_descripcion" for="x_campo_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->campo_descripcion->caption() ?><?= $Page->campo_descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->campo_descripcion->cellAttributes() ?>>
<span id="el_tabla_campo_descripcion">
<input type="<?= $Page->campo_descripcion->getInputTextType() ?>" name="x_campo_descripcion" id="x_campo_descripcion" data-table="tabla" data-field="x_campo_descripcion" value="<?= $Page->campo_descripcion->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->campo_descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->campo_descripcion->formatPattern()) ?>"<?= $Page->campo_descripcion->editAttributes() ?> aria-describedby="x_campo_descripcion_help">
<?= $Page->campo_descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->campo_descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->campo_dato->Visible) { // campo_dato ?>
    <div id="r_campo_dato"<?= $Page->campo_dato->rowAttributes() ?>>
        <label id="elh_tabla_campo_dato" for="x_campo_dato" class="<?= $Page->LeftColumnClass ?>"><?= $Page->campo_dato->caption() ?><?= $Page->campo_dato->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->campo_dato->cellAttributes() ?>>
<span id="el_tabla_campo_dato">
    <select
        id="x_campo_dato"
        name="x_campo_dato"
        class="form-select ew-select<?= $Page->campo_dato->isInvalidClass() ?>"
        <?php if (!$Page->campo_dato->IsNativeSelect) { ?>
        data-select2-id="ftablaedit_x_campo_dato"
        <?php } ?>
        data-table="tabla"
        data-field="x_campo_dato"
        data-value-separator="<?= $Page->campo_dato->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->campo_dato->getPlaceHolder()) ?>"
        <?= $Page->campo_dato->editAttributes() ?>>
        <?= $Page->campo_dato->selectOptionListHtml("x_campo_dato") ?>
    </select>
    <?= $Page->campo_dato->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->campo_dato->getErrorMessage() ?></div>
<?= $Page->campo_dato->Lookup->getParamTag($Page, "p_x_campo_dato") ?>
<?php if (!$Page->campo_dato->IsNativeSelect) { ?>
<script>
loadjs.ready("ftablaedit", function() {
    var options = { name: "x_campo_dato", selectId: "ftablaedit_x_campo_dato" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (ftablaedit.lists.campo_dato?.lookupOptions.length) {
        options.data = { id: "x_campo_dato", form: "ftablaedit" };
    } else {
        options.ajax = { id: "x_campo_dato", form: "ftablaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.tabla.fields.campo_dato.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="tabla" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftablaedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftablaedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("tabla");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
