<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("UsuarioMaster2Grid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fusuario_master2grid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { usuario_master2: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fusuario_master2grid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["username2", [fields.username2.visible && fields.username2.required ? ew.Validators.required(fields.username2.caption) : null], fields.username2.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["username2",false]];
                if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
                    return false;
                return true;
            }
        )

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
            "username2": <?= $Grid->username2->toClientList($Grid) ?>,
        })
        .build();
    window[form.id] = form;
    loadjs.done(form.id);
});
</script>
<?php } ?>
<main class="list">
<div id="ew-header-options">
<?php $Grid->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Grid->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Grid->TableGridClass ?>">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fusuario_master2grid" class="ew-form ew-list-form">
<div id="gmp_usuario_master2" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_usuario_master2grid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = RowType::HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->username2->Visible) { // username2 ?>
        <th data-name="username2" class="<?= $Grid->username2->headerCellClass() ?>"><div id="elh_usuario_master2_username2" class="usuario_master2_username2"><?= $Grid->renderFieldHeader($Grid->username2) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Grid->getPageNumber() ?>">
<?php
$Grid->setupGrid();
$isInlineAddOrCopy = ($Grid->isCopy() || $Grid->isAdd());
while ($Grid->RecordCount < $Grid->StopRecord || $Grid->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Grid->RowIndex == 0) {
    if (
        $Grid->CurrentRow !== false &&
        $Grid->RowIndex !== '$rowindex$' &&
        (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Grid->RowIndex == 0)
    ) {
        $Grid->fetch();
    }
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Grid->RowAction != "delete" &&
            $Grid->RowAction != "insertdelete" &&
            !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow()) &&
            $Grid->RowAction != "hide"
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->username2->Visible) { // username2 ?>
        <td data-name="username2"<?= $Grid->username2->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_usuario_master2_username2" class="el_usuario_master2_username2">
    <select
        id="x<?= $Grid->RowIndex ?>_username2"
        name="x<?= $Grid->RowIndex ?>_username2"
        class="form-control ew-select<?= $Grid->username2->isInvalidClass() ?>"
        data-select2-id="fusuario_master2grid_x<?= $Grid->RowIndex ?>_username2"
        data-table="usuario_master2"
        data-field="x_username2"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->username2->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->username2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->username2->getPlaceHolder()) ?>"
        <?= $Grid->username2->editAttributes() ?>>
        <?= $Grid->username2->selectOptionListHtml("x{$Grid->RowIndex}_username2") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->username2->getErrorMessage() ?></div>
<?= $Grid->username2->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_username2") ?>
<script>
loadjs.ready("fusuario_master2grid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_username2", selectId: "fusuario_master2grid_x<?= $Grid->RowIndex ?>_username2" };
    if (fusuario_master2grid.lists.username2?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_username2", form: "fusuario_master2grid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_username2", form: "fusuario_master2grid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.usuario_master2.fields.username2.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="usuario_master2" data-field="x_username2" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_username2" id="o<?= $Grid->RowIndex ?>_username2" value="<?= HtmlEncode($Grid->username2->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_usuario_master2_username2" class="el_usuario_master2_username2">
    <select
        id="x<?= $Grid->RowIndex ?>_username2"
        name="x<?= $Grid->RowIndex ?>_username2"
        class="form-control ew-select<?= $Grid->username2->isInvalidClass() ?>"
        data-select2-id="fusuario_master2grid_x<?= $Grid->RowIndex ?>_username2"
        data-table="usuario_master2"
        data-field="x_username2"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->username2->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->username2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->username2->getPlaceHolder()) ?>"
        <?= $Grid->username2->editAttributes() ?>>
        <?= $Grid->username2->selectOptionListHtml("x{$Grid->RowIndex}_username2") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->username2->getErrorMessage() ?></div>
<?= $Grid->username2->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_username2") ?>
<script>
loadjs.ready("fusuario_master2grid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_username2", selectId: "fusuario_master2grid_x<?= $Grid->RowIndex ?>_username2" };
    if (fusuario_master2grid.lists.username2?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_username2", form: "fusuario_master2grid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_username2", form: "fusuario_master2grid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.usuario_master2.fields.username2.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_usuario_master2_username2" class="el_usuario_master2_username2">
<span<?= $Grid->username2->viewAttributes() ?>>
<?= $Grid->username2->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="usuario_master2" data-field="x_username2" data-hidden="1" name="fusuario_master2grid$x<?= $Grid->RowIndex ?>_username2" id="fusuario_master2grid$x<?= $Grid->RowIndex ?>_username2" value="<?= HtmlEncode($Grid->username2->FormValue) ?>">
<input type="hidden" data-table="usuario_master2" data-field="x_username2" data-hidden="1" data-old name="fusuario_master2grid$o<?= $Grid->RowIndex ?>_username2" id="fusuario_master2grid$o<?= $Grid->RowIndex ?>_username2" value="<?= HtmlEncode($Grid->username2->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == RowType::ADD || $Grid->RowType == RowType::EDIT) { ?>
<script data-rowindex="<?= $Grid->RowIndex ?>">
loadjs.ready(["fusuario_master2grid","load"], () => fusuario_master2grid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking

    // Reset for template row
    if ($Grid->RowIndex === '$rowindex$') {
        $Grid->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Grid->isCopy() || $Grid->isAdd()) && $Grid->RowIndex == 0) {
        $Grid->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fusuario_master2grid">
</div><!-- /.ew-list-form -->
<?php
// Close result set
$Grid->Recordset?->free();
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Grid->FooterOptions?->render("body") ?>
</div>
</main>
<?php if (!$Grid->isExport()) { ?>
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
<?php } ?>
