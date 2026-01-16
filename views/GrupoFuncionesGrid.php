<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("GrupoFuncionesGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fgrupo_funcionesgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { grupo_funciones: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fgrupo_funcionesgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["funcion", [fields.funcion.visible && fields.funcion.required ? ew.Validators.required(fields.funcion.caption) : null], fields.funcion.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["funcion",false]];
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
            "funcion": <?= $Grid->funcion->toClientList($Grid) ?>,
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
<div id="fgrupo_funcionesgrid" class="ew-form ew-list-form">
<div id="gmp_grupo_funciones" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_grupo_funcionesgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->funcion->Visible) { // funcion ?>
        <th data-name="funcion" class="<?= $Grid->funcion->headerCellClass() ?>"><div id="elh_grupo_funciones_funcion" class="grupo_funciones_funcion"><?= $Grid->renderFieldHeader($Grid->funcion) ?></div></th>
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
    <?php if ($Grid->funcion->Visible) { // funcion ?>
        <td data-name="funcion"<?= $Grid->funcion->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_grupo_funciones_funcion" class="el_grupo_funciones_funcion">
    <select
        id="x<?= $Grid->RowIndex ?>_funcion"
        name="x<?= $Grid->RowIndex ?>_funcion"
        class="form-select ew-select<?= $Grid->funcion->isInvalidClass() ?>"
        <?php if (!$Grid->funcion->IsNativeSelect) { ?>
        data-select2-id="fgrupo_funcionesgrid_x<?= $Grid->RowIndex ?>_funcion"
        <?php } ?>
        data-table="grupo_funciones"
        data-field="x_funcion"
        data-value-separator="<?= $Grid->funcion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->funcion->getPlaceHolder()) ?>"
        <?= $Grid->funcion->editAttributes() ?>>
        <?= $Grid->funcion->selectOptionListHtml("x{$Grid->RowIndex}_funcion") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->funcion->getErrorMessage() ?></div>
<?= $Grid->funcion->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_funcion") ?>
<?php if (!$Grid->funcion->IsNativeSelect) { ?>
<script>
loadjs.ready("fgrupo_funcionesgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_funcion", selectId: "fgrupo_funcionesgrid_x<?= $Grid->RowIndex ?>_funcion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fgrupo_funcionesgrid.lists.funcion?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_funcion", form: "fgrupo_funcionesgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_funcion", form: "fgrupo_funcionesgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.grupo_funciones.fields.funcion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="grupo_funciones" data-field="x_funcion" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_funcion" id="o<?= $Grid->RowIndex ?>_funcion" value="<?= HtmlEncode($Grid->funcion->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_grupo_funciones_funcion" class="el_grupo_funciones_funcion">
    <select
        id="x<?= $Grid->RowIndex ?>_funcion"
        name="x<?= $Grid->RowIndex ?>_funcion"
        class="form-select ew-select<?= $Grid->funcion->isInvalidClass() ?>"
        <?php if (!$Grid->funcion->IsNativeSelect) { ?>
        data-select2-id="fgrupo_funcionesgrid_x<?= $Grid->RowIndex ?>_funcion"
        <?php } ?>
        data-table="grupo_funciones"
        data-field="x_funcion"
        data-value-separator="<?= $Grid->funcion->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->funcion->getPlaceHolder()) ?>"
        <?= $Grid->funcion->editAttributes() ?>>
        <?= $Grid->funcion->selectOptionListHtml("x{$Grid->RowIndex}_funcion") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->funcion->getErrorMessage() ?></div>
<?= $Grid->funcion->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_funcion") ?>
<?php if (!$Grid->funcion->IsNativeSelect) { ?>
<script>
loadjs.ready("fgrupo_funcionesgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_funcion", selectId: "fgrupo_funcionesgrid_x<?= $Grid->RowIndex ?>_funcion" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fgrupo_funcionesgrid.lists.funcion?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_funcion", form: "fgrupo_funcionesgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_funcion", form: "fgrupo_funcionesgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.grupo_funciones.fields.funcion.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_grupo_funciones_funcion" class="el_grupo_funciones_funcion">
<span<?= $Grid->funcion->viewAttributes() ?>>
<?= $Grid->funcion->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="grupo_funciones" data-field="x_funcion" data-hidden="1" name="fgrupo_funcionesgrid$x<?= $Grid->RowIndex ?>_funcion" id="fgrupo_funcionesgrid$x<?= $Grid->RowIndex ?>_funcion" value="<?= HtmlEncode($Grid->funcion->FormValue) ?>">
<input type="hidden" data-table="grupo_funciones" data-field="x_funcion" data-hidden="1" data-old name="fgrupo_funcionesgrid$o<?= $Grid->RowIndex ?>_funcion" id="fgrupo_funcionesgrid$o<?= $Grid->RowIndex ?>_funcion" value="<?= HtmlEncode($Grid->funcion->OldValue) ?>">
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
loadjs.ready(["fgrupo_funcionesgrid","load"], () => fgrupo_funcionesgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fgrupo_funcionesgrid">
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
    ew.addEventHandlers("grupo_funciones");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
