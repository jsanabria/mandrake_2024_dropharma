<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("ContAsientoGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fcont_asientogrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { cont_asiento: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcont_asientogrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["cuenta", [fields.cuenta.visible && fields.cuenta.required ? ew.Validators.required(fields.cuenta.caption) : null], fields.cuenta.isInvalid],
            ["nota", [fields.nota.visible && fields.nota.required ? ew.Validators.required(fields.nota.caption) : null], fields.nota.isInvalid],
            ["referencia", [fields.referencia.visible && fields.referencia.required ? ew.Validators.required(fields.referencia.caption) : null], fields.referencia.isInvalid],
            ["debe", [fields.debe.visible && fields.debe.required ? ew.Validators.required(fields.debe.caption) : null, ew.Validators.float], fields.debe.isInvalid],
            ["haber", [fields.haber.visible && fields.haber.required ? ew.Validators.required(fields.haber.caption) : null, ew.Validators.float], fields.haber.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["cuenta",false],["nota",false],["referencia",false],["debe",false],["haber",false]];
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
<div id="fcont_asientogrid" class="ew-form ew-list-form">
<div id="gmp_cont_asiento" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_cont_asientogrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <th data-name="cuenta" class="<?= $Grid->cuenta->headerCellClass() ?>"><div id="elh_cont_asiento_cuenta" class="cont_asiento_cuenta"><?= $Grid->renderFieldHeader($Grid->cuenta) ?></div></th>
<?php } ?>
<?php if ($Grid->nota->Visible) { // nota ?>
        <th data-name="nota" class="<?= $Grid->nota->headerCellClass() ?>"><div id="elh_cont_asiento_nota" class="cont_asiento_nota"><?= $Grid->renderFieldHeader($Grid->nota) ?></div></th>
<?php } ?>
<?php if ($Grid->referencia->Visible) { // referencia ?>
        <th data-name="referencia" class="<?= $Grid->referencia->headerCellClass() ?>"><div id="elh_cont_asiento_referencia" class="cont_asiento_referencia"><?= $Grid->renderFieldHeader($Grid->referencia) ?></div></th>
<?php } ?>
<?php if ($Grid->debe->Visible) { // debe ?>
        <th data-name="debe" class="<?= $Grid->debe->headerCellClass() ?>"><div id="elh_cont_asiento_debe" class="cont_asiento_debe"><?= $Grid->renderFieldHeader($Grid->debe) ?></div></th>
<?php } ?>
<?php if ($Grid->haber->Visible) { // haber ?>
        <th data-name="haber" class="<?= $Grid->haber->headerCellClass() ?>"><div id="elh_cont_asiento_haber" class="cont_asiento_haber"><?= $Grid->renderFieldHeader($Grid->haber) ?></div></th>
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
    <?php if ($Grid->cuenta->Visible) { // cuenta ?>
        <td data-name="cuenta"<?= $Grid->cuenta->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_cuenta" class="el_cont_asiento_cuenta">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta"
        name="x<?= $Grid->RowIndex ?>_cuenta"
        class="form-control ew-select<?= $Grid->cuenta->isInvalidClass() ?>"
        data-select2-id="fcont_asientogrid_x<?= $Grid->RowIndex ?>_cuenta"
        data-table="cont_asiento"
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
loadjs.ready("fcont_asientogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta", selectId: "fcont_asientogrid_x<?= $Grid->RowIndex ?>_cuenta" };
    if (fcont_asientogrid.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_asientogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_asientogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cuenta" id="o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_cuenta" class="el_cont_asiento_cuenta">
    <select
        id="x<?= $Grid->RowIndex ?>_cuenta"
        name="x<?= $Grid->RowIndex ?>_cuenta"
        class="form-control ew-select<?= $Grid->cuenta->isInvalidClass() ?>"
        data-select2-id="fcont_asientogrid_x<?= $Grid->RowIndex ?>_cuenta"
        data-table="cont_asiento"
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
loadjs.ready("fcont_asientogrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_cuenta", selectId: "fcont_asientogrid_x<?= $Grid->RowIndex ?>_cuenta" };
    if (fcont_asientogrid.lists.cuenta?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_asientogrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_cuenta", form: "fcont_asientogrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cont_asiento.fields.cuenta.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_cuenta" class="el_cont_asiento_cuenta">
<span<?= $Grid->cuenta->viewAttributes() ?>>
<?= $Grid->cuenta->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_cuenta" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_cuenta" data-hidden="1" data-old name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_cuenta" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_cuenta" value="<?= HtmlEncode($Grid->cuenta->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nota->Visible) { // nota ?>
        <td data-name="nota"<?= $Grid->nota->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_nota" class="el_cont_asiento_nota">
<input type="<?= $Grid->nota->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_nota" id="x<?= $Grid->RowIndex ?>_nota" data-table="cont_asiento" data-field="x_nota" value="<?= $Grid->nota->EditValue ?>" size="10" maxlength="60" placeholder="<?= HtmlEncode($Grid->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->nota->formatPattern()) ?>"<?= $Grid->nota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nota->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_nota" id="o<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_nota" class="el_cont_asiento_nota">
<input type="<?= $Grid->nota->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_nota" id="x<?= $Grid->RowIndex ?>_nota" data-table="cont_asiento" data-field="x_nota" value="<?= $Grid->nota->EditValue ?>" size="10" maxlength="60" placeholder="<?= HtmlEncode($Grid->nota->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->nota->formatPattern()) ?>"<?= $Grid->nota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nota->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_nota" class="el_cont_asiento_nota">
<span<?= $Grid->nota->viewAttributes() ?>>
<?= $Grid->nota->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_nota" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_nota" data-hidden="1" data-old name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_nota" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_nota" value="<?= HtmlEncode($Grid->nota->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->referencia->Visible) { // referencia ?>
        <td data-name="referencia"<?= $Grid->referencia->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_referencia" class="el_cont_asiento_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="cont_asiento" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="10" maxlength="25" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_referencia" id="o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_referencia" class="el_cont_asiento_referencia">
<input type="<?= $Grid->referencia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_referencia" id="x<?= $Grid->RowIndex ?>_referencia" data-table="cont_asiento" data-field="x_referencia" value="<?= $Grid->referencia->EditValue ?>" size="10" maxlength="25" placeholder="<?= HtmlEncode($Grid->referencia->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->referencia->formatPattern()) ?>"<?= $Grid->referencia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->referencia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_referencia" class="el_cont_asiento_referencia">
<span<?= $Grid->referencia->viewAttributes() ?>>
<?= $Grid->referencia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_referencia" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_referencia" data-hidden="1" data-old name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_referencia" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_referencia" value="<?= HtmlEncode($Grid->referencia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->debe->Visible) { // debe ?>
        <td data-name="debe"<?= $Grid->debe->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_debe" class="el_cont_asiento_debe">
<input type="<?= $Grid->debe->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_debe" id="x<?= $Grid->RowIndex ?>_debe" data-table="cont_asiento" data-field="x_debe" value="<?= $Grid->debe->EditValue ?>" size="12" placeholder="<?= HtmlEncode($Grid->debe->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->debe->formatPattern()) ?>"<?= $Grid->debe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_debe" id="o<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_debe" class="el_cont_asiento_debe">
<input type="<?= $Grid->debe->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_debe" id="x<?= $Grid->RowIndex ?>_debe" data-table="cont_asiento" data-field="x_debe" value="<?= $Grid->debe->EditValue ?>" size="12" placeholder="<?= HtmlEncode($Grid->debe->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->debe->formatPattern()) ?>"<?= $Grid->debe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->debe->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_debe" class="el_cont_asiento_debe">
<span<?= $Grid->debe->viewAttributes() ?>>
<?= $Grid->debe->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_debe" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_debe" data-hidden="1" data-old name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_debe" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_debe" value="<?= HtmlEncode($Grid->debe->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->haber->Visible) { // haber ?>
        <td data-name="haber"<?= $Grid->haber->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_haber" class="el_cont_asiento_haber">
<input type="<?= $Grid->haber->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_haber" id="x<?= $Grid->RowIndex ?>_haber" data-table="cont_asiento" data-field="x_haber" value="<?= $Grid->haber->EditValue ?>" size="12" placeholder="<?= HtmlEncode($Grid->haber->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->haber->formatPattern()) ?>"<?= $Grid->haber->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_haber" id="o<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_haber" class="el_cont_asiento_haber">
<input type="<?= $Grid->haber->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_haber" id="x<?= $Grid->RowIndex ?>_haber" data-table="cont_asiento" data-field="x_haber" value="<?= $Grid->haber->EditValue ?>" size="12" placeholder="<?= HtmlEncode($Grid->haber->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->haber->formatPattern()) ?>"<?= $Grid->haber->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->haber->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_cont_asiento_haber" class="el_cont_asiento_haber">
<span<?= $Grid->haber->viewAttributes() ?>>
<?= $Grid->haber->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" name="fcont_asientogrid$x<?= $Grid->RowIndex ?>_haber" id="fcont_asientogrid$x<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->FormValue) ?>">
<input type="hidden" data-table="cont_asiento" data-field="x_haber" data-hidden="1" data-old name="fcont_asientogrid$o<?= $Grid->RowIndex ?>_haber" id="fcont_asientogrid$o<?= $Grid->RowIndex ?>_haber" value="<?= HtmlEncode($Grid->haber->OldValue) ?>">
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
loadjs.ready(["fcont_asientogrid","load"], () => fcont_asientogrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fcont_asientogrid">
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
    ew.addEventHandlers("cont_asiento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $(document).ready(function() { 
    	var ButtonGroup = $('.ewButtonGroup'); 
    	ButtonGroup.hide(); 
    });
    $("#cmbContab").click(function(){
    	var id = <?php echo $_REQUEST["fk_id"]; ?>;
    	var username = "<?php echo CurrentUserName(); ?>";
    	if(confirm("Seguro de contabilizar este comprobante?")) {
    		$.ajax({
    		  url : "include/Contabilizar_Procesar.php",
    		  type: "GET",
    		  data : {id: id, username: username},
    		  beforeSend: function(){
    		    $("#result").html("Por Favor Espere. . .");
    		  }
    		})
    		.done(function(data) {
    			//alert(data);
    			var rs = '<div class="alert alert-success" role="alert">Este Comprobante se Contabiliz&oacute; Exitosamente.</div>';
    			$("#result").html(rs);
    		})
    		.fail(function(data) {
    			alert( "error" + data );
    		})
    		.always(function(data) {
    			//alert( "complete" );
    			//$("#result").html("Espere. . . ");
    		});
    	}
    });
});
</script>
<?php } ?>
