<?php

namespace PHPMaker2024\mandrake;

// Page object
$GrupoFuncionesAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { grupo_funciones: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fgrupo_funcionesadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fgrupo_funcionesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["funcion", [fields.funcion.visible && fields.funcion.required ? ew.Validators.required(fields.funcion.caption) : null], fields.funcion.isInvalid]
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
            "funcion": <?= $Page->funcion->toClientList($Page) ?>,
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
<form name="fgrupo_funcionesadd" id="fgrupo_funcionesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="grupo_funciones">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "userlevels") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="userlevels">
<input type="hidden" name="fk_userlevelid" value="<?= HtmlEncode($Page->grupo->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->funcion->Visible) { // funcion ?>
    <div id="r_funcion"<?= $Page->funcion->rowAttributes() ?>>
        <label id="elh_grupo_funciones_funcion" for="x_funcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->funcion->caption() ?><?= $Page->funcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->funcion->cellAttributes() ?>>
<span id="el_grupo_funciones_funcion">
    <select
        id="x_funcion"
        name="x_funcion"
        class="form-select ew-select<?= $Page->funcion->isInvalidClass() ?>"
        <?php if (!$Page->funcion->IsNativeSelect) { ?>
        data-select2-id="fgrupo_funcionesadd_x_funcion"
        <?php } ?>
        data-table="grupo_funciones"
        data-field="x_funcion"
        data-value-separator="<?= $Page->funcion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->funcion->getPlaceHolder()) ?>"
        <?= $Page->funcion->editAttributes() ?>>
        <?= $Page->funcion->selectOptionListHtml("x_funcion") ?>
    </select>
    <?= $Page->funcion->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->funcion->getErrorMessage() ?></div>
<?= $Page->funcion->Lookup->getParamTag($Page, "p_x_funcion") ?>
<?php if (!$Page->funcion->IsNativeSelect) { ?>
<script>
loadjs.ready("fgrupo_funcionesadd", function() {
    var options = { name: "x_funcion", selectId: "fgrupo_funcionesadd_x_funcion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fgrupo_funcionesadd.lists.funcion?.lookupOptions.length) {
        options.data = { id: "x_funcion", form: "fgrupo_funcionesadd" };
    } else {
        options.ajax = { id: "x_funcion", form: "fgrupo_funcionesadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.grupo_funciones.fields.funcion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->grupo->getSessionValue()) != "") { ?>
    <input type="hidden" name="x_grupo" id="x_grupo" value="<?= HtmlEncode(strval($Page->grupo->getSessionValue())) ?>">
    <?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fgrupo_funcionesadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fgrupo_funcionesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("grupo_funciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
