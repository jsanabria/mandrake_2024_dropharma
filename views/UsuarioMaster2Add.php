<?php

namespace PHPMaker2024\mandrake;

// Page object
$UsuarioMaster2Add = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { usuario_master2: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fusuario_master2add;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fusuario_master2add")
        .setPageId("add")

        // Add fields
        .setFields([
            ["username2", [fields.username2.visible && fields.username2.required ? ew.Validators.required(fields.username2.caption) : null], fields.username2.isInvalid]
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
            "username2": <?= $Page->username2->toClientList($Page) ?>,
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
<form name="fusuario_master2add" id="fusuario_master2add" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="usuario_master2">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "usuario") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="usuario">
<input type="hidden" name="fk__username" value="<?= HtmlEncode($Page->_username->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->username2->Visible) { // username2 ?>
    <div id="r_username2"<?= $Page->username2->rowAttributes() ?>>
        <label id="elh_usuario_master2_username2" for="x_username2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->username2->caption() ?><?= $Page->username2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->username2->cellAttributes() ?>>
<span id="el_usuario_master2_username2">
    <select
        id="x_username2"
        name="x_username2"
        class="form-control ew-select<?= $Page->username2->isInvalidClass() ?>"
        data-select2-id="fusuario_master2add_x_username2"
        data-table="usuario_master2"
        data-field="x_username2"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->username2->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->username2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->username2->getPlaceHolder()) ?>"
        <?= $Page->username2->editAttributes() ?>>
        <?= $Page->username2->selectOptionListHtml("x_username2") ?>
    </select>
    <?= $Page->username2->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->username2->getErrorMessage() ?></div>
<?= $Page->username2->Lookup->getParamTag($Page, "p_x_username2") ?>
<script>
loadjs.ready("fusuario_master2add", function() {
    var options = { name: "x_username2", selectId: "fusuario_master2add_x_username2" };
    if (fusuario_master2add.lists.username2?.lookupOptions.length) {
        options.data = { id: "x_username2", form: "fusuario_master2add" };
    } else {
        options.ajax = { id: "x_username2", form: "fusuario_master2add", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.usuario_master2.fields.username2.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <?php if (strval($Page->_username->getSessionValue()) != "") { ?>
    <input type="hidden" name="x__username" id="x__username" value="<?= HtmlEncode(strval($Page->_username->getSessionValue())) ?>">
    <?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fusuario_master2add"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fusuario_master2add" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("usuario_master2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
