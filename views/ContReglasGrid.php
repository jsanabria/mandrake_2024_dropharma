<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ContReglasGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fcont_reglasgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { cont_reglas: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_reglasgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
            ["cargo", [fields.cargo.visible && fields.cargo.required ? ew.Validators.required(fields.cargo.caption) : null], fields.cargo.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["codigo",false],["descripcion",false],["cuenta",false],["cargo",false]];
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
            "cuenta": <?= $Grid->cuenta->toClientList($Grid) ?>,
            "cargo": <?= $Grid->cargo->toClientList($Grid) ?>,
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
<div id="fcont_reglasgrid" class="ew-form ew-list-form">
<div id="gmp_cont_reglas" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_cont_reglasgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->codigo->Visible) { // codigo ?>
        <th data-name="codigo" class="<?= $Grid->codigo->headerCellClass() ?>"><div id="elh_cont_reglas_codigo" class="cont_reglas_codigo"><?= $Grid->renderFieldHeader($Grid->codigo) ?></div></th>
<?php } ?>
<?php if ($Grid->descripcion->Visible) { // descripcion ?>
        <th data-name="descripcion" class="<?= $Grid->descripcion->headerCellClass() ?>"><div id="elh_cont_reglas_descripcion" class="cont_reglas_descripcion"><?= $Grid->renderFieldHeader($Grid->descripcion) ?></div></th>
<?php } ?>
<?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Grid->cuenta->headerCellClass() ?>"><div id="elh_cont_reglas_cuenta" class="cont_reglas_cuenta"><?= $Grid->renderFieldHeader($Grid->cuenta) ?></div></th>
<?php } ?>
<?php if ($Grid->cargo->Visible) { // cargo ?>
        <th data-name="cargo" class="<?= $Grid->cargo->headerCellClass() ?>"><div id="elh_cont_reglas_cargo" class="cont_reglas_cargo"><?= $Grid->renderFieldHeader($Grid->cargo) ?></div></th>
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
    <?php if ($Grid->codigo->Visible) { // codigo ?>
        <td data-name="codigo"<?= $Grid->codigo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_codigo" class="el_cont_reglas_codigo">
<input type="<?= $Grid->codigo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_codigo" id="x<?= $Grid->RowIndex ?>_codigo" data-table="cont_reglas" data-field="x_codigo" value="<?= $Grid->codigo->EditValue ?>" size="30" maxlength="4" placeholder="<?= HtmlEncode($Grid->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->codigo->formatPattern()) ?>"<?= $Grid->codigo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->codigo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_codigo" id="o<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_codigo" class="el_cont_reglas_codigo">
<span<?= $Grid->codigo->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->codigo->getDisplayValue($Grid->codigo->EditValue))) ?>"></span>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="x<?= $Grid->RowIndex ?>_codigo" id="x<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_codigo" class="el_cont_reglas_codigo">
<span<?= $Grid->codigo->viewAttributes() ?>>
<?= $Grid->codigo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_codigo" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_codigo" data-hidden="1" data-old name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_codigo" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_codigo" value="<?= HtmlEncode($Grid->codigo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descripcion->Visible) { // descripcion ?>
        <td data-name="descripcion"<?= $Grid->descripcion->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_descripcion" class="el_cont_reglas_descripcion">
<input type="<?= $Grid->descripcion->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descripcion" id="x<?= $Grid->RowIndex ?>_descripcion" data-table="cont_reglas" data-field="x_descripcion" value="<?= $Grid->descripcion->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descripcion->formatPattern()) ?>"<?= $Grid->descripcion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descripcion->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_descripcion" id="o<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_descripcion" class="el_cont_reglas_descripcion">
<input type="<?= $Grid->descripcion->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descripcion" id="x<?= $Grid->RowIndex ?>_descripcion" data-table="cont_reglas" data-field="x_descripcion" value="<?= $Grid->descripcion->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Grid->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descripcion->formatPattern()) ?>"<?= $Grid->descripcion->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descripcion->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_descripcion" class="el_cont_reglas_descripcion">
<span<?= $Grid->descripcion->viewAttributes() ?>>
<?= $Grid->descripcion->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_descripcion" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_descripcion" data-hidden="1" data-old name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_descripcion" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_descripcion" value="<?= HtmlEncode($Grid->descripcion->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta"<?= $Grid->cuenta->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_cuenta" class="el_cont_reglas_cuenta">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta"
        name="x<?= $Grid->RowIndex ?>_cuenta"
        class="form-control ew-select<?= $Grid->cuenta->isInvalidClass() ?>"
        data-select2-id="fcont_reglasgrid_x<?= $Grid->RowIndex ?>_cuenta"
        data-table="cont_reglas"
        data-field="x_cuenta"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cuenta->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cuenta->getPlaceHolder()) ?>"
        <?= $Grid->cuenta->editAttributes() ?>>
        <?= $Grid->cuenta->selectOptionListHtml("x{$Grid->RowIndex}_cuenta") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<script>
loadjs.ready("fcont_reglasgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta", selectId: "fcont_reglasgrid_x<?= $Grid->RowIndex ?>_cuenta" };
    if (fcont_reglasgrid.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_reglasgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_reglasgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_reglas.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_cuenta" class="el_cont_reglas_cuenta">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta"
        name="x<?= $Grid->RowIndex ?>_cuenta"
        class="form-control ew-select<?= $Grid->cuenta->isInvalidClass() ?>"
        data-select2-id="fcont_reglasgrid_x<?= $Grid->RowIndex ?>_cuenta"
        data-table="cont_reglas"
        data-field="x_cuenta"
        data-caption="<?= HtmlEncode(RemoveHtml($Grid->cuenta->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Grid->cuenta->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->cuenta->getPlaceHolder()) ?>"
        <?= $Grid->cuenta->editAttributes() ?>>
        <?= $Grid->cuenta->selectOptionListHtml("x{$Grid->RowIndex}_cuenta") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->cuenta->getErrorMessage() ?></div>
<?= $Grid->cuenta->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_cuenta") ?>
<script>
loadjs.ready("fcont_reglasgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta", selectId: "fcont_reglasgrid_x<?= $Grid->RowIndex ?>_cuenta" };
    if (fcont_reglasgrid.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_reglasgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_reglasgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_reglas.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_cuenta" class="el_cont_reglas_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<?= $Grid->cuenta->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cuenta" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_cuenta" data-hidden="1" data-old name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cuenta" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cargo->Visible) { // cargo ?>
        <td data-name="cargo"<?= $Grid->cargo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_cargo" class="el_cont_reglas_cargo">
<template id="tp_x<?= $Grid->RowIndex ?>_cargo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_reglas" data-field="x_cargo" name="x<?= $Grid->RowIndex ?>_cargo" id="x<?= $Grid->RowIndex ?>_cargo"<?= $Grid->cargo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_cargo" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_cargo"
    name="x<?= $Grid->RowIndex ?>_cargo"
    value="<?= HtmlEncode($Grid->cargo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_cargo"
    data-target="dsl_x<?= $Grid->RowIndex ?>_cargo"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->cargo->isInvalidClass() ?>"
    data-table="cont_reglas"
    data-field="x_cargo"
    data-value-separator="<?= $Grid->cargo->displayValueSeparatorAttribute() ?>"
    <?= $Grid->cargo->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->cargo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cargo" id="o<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_cargo" class="el_cont_reglas_cargo">
<template id="tp_x<?= $Grid->RowIndex ?>_cargo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="cont_reglas" data-field="x_cargo" name="x<?= $Grid->RowIndex ?>_cargo" id="x<?= $Grid->RowIndex ?>_cargo"<?= $Grid->cargo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_cargo" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_cargo"
    name="x<?= $Grid->RowIndex ?>_cargo"
    value="<?= HtmlEncode($Grid->cargo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_cargo"
    data-target="dsl_x<?= $Grid->RowIndex ?>_cargo"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->cargo->isInvalidClass() ?>"
    data-table="cont_reglas"
    data-field="x_cargo"
    data-value-separator="<?= $Grid->cargo->displayValueSeparatorAttribute() ?>"
    <?= $Grid->cargo->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->cargo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_reglas_cargo" class="el_cont_reglas_cargo">
<span<?= $Grid->cargo->viewAttributes() ?>>
<?= $Grid->cargo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" name="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cargo" id="fcont_reglasgrid$x<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->FormValue) ?>">
<input type="hidden" data-table="cont_reglas" data-field="x_cargo" data-hidden="1" data-old name="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cargo" id="fcont_reglasgrid$o<?= $Grid->RowIndex ?>_cargo" value="<?= HtmlEncode($Grid->cargo->OldValue) ?>">
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
loadjs.ready(["fcont_reglasgrid","load"], () => fcont_reglasgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fcont_reglasgrid">
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
    ew.addEventHandlers("cont_reglas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
