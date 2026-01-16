<?php

namespace PHPMaker2024\mandrake;

// Page object
$EntradasSalidasList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas_salidas: currentTable } });
var currentPageID = ew.PAGE_ID = "list";
var currentForm;
var <?= $Page->FormName ?>;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("<?= $Page->FormName ?>")
        .setPageId("list")
        .setSubmitWithFetch(<?= $Page->UseAjaxActions ? "true" : "false" ?>)
        .setFormKeyCountName("<?= $Page->FormKeyCountName ?>")

        // Add fields
        .setFields([
            ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null, ew.Validators.integer], fields.articulo.isInvalid],
            ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
            ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(fields.fecha_vencimiento.clientFormatPattern)], fields.fecha_vencimiento.isInvalid],
            ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
            ["id_compra", [fields.id_compra.visible && fields.id_compra.required ? ew.Validators.required(fields.id_compra.caption) : null, ew.Validators.integer], fields.id_compra.isInvalid],
            ["cantidad_articulo", [fields.cantidad_articulo.visible && fields.cantidad_articulo.required ? ew.Validators.required(fields.cantidad_articulo.caption) : null, ew.Validators.float], fields.cantidad_articulo.isInvalid],
            ["precio_unidad_sin_desc", [fields.precio_unidad_sin_desc.visible && fields.precio_unidad_sin_desc.required ? ew.Validators.required(fields.precio_unidad_sin_desc.caption) : null, ew.Validators.float], fields.precio_unidad_sin_desc.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["costo_unidad", [fields.costo_unidad.visible && fields.costo_unidad.required ? ew.Validators.required(fields.costo_unidad.caption) : null, ew.Validators.float], fields.costo_unidad.isInvalid],
            ["costo", [fields.costo.visible && fields.costo.required ? ew.Validators.required(fields.costo.caption) : null, ew.Validators.float], fields.costo.isInvalid],
            ["precio_unidad", [fields.precio_unidad.visible && fields.precio_unidad.required ? ew.Validators.required(fields.precio_unidad.caption) : null, ew.Validators.float], fields.precio_unidad.isInvalid],
            ["precio", [fields.precio.visible && fields.precio.required ? ew.Validators.required(fields.precio.caption) : null, ew.Validators.float], fields.precio.isInvalid],
            ["check_ne", [fields.check_ne.visible && fields.check_ne.required ? ew.Validators.required(fields.check_ne.caption) : null], fields.check_ne.isInvalid],
            ["newdata", [fields.newdata.visible && fields.newdata.required ? ew.Validators.required(fields.newdata.caption) : null], fields.newdata.isInvalid]
        ])

        // Check empty row
        .setEmptyRow(
            function (rowIndex) {
                let fobj = this.getForm(),
                    fields = [["articulo",false],["lote",false],["fecha_vencimiento",false],["almacen",false],["id_compra",false],["cantidad_articulo",false],["precio_unidad_sin_desc",false],["descuento",false],["costo_unidad",false],["costo",false],["precio_unidad",false],["precio",false],["check_ne[]",false],["newdata",false]];
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
            "almacen": <?= $Page->almacen->toClientList($Page) ?>,
            "check_ne": <?= $Page->check_ne->toClientList($Page) ?>,
            "newdata": <?= $Page->newdata->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
ew.PREVIEW_SELECTOR ??= ".ew-preview-btn";
ew.PREVIEW_TYPE ??= "row";
ew.PREVIEW_NAV_STYLE ??= "tabs"; // tabs/pills/underline
ew.PREVIEW_MODAL_CLASS ??= "modal modal-fullscreen-sm-down";
ew.PREVIEW_ROW ??= true;
ew.PREVIEW_SINGLE_ROW ??= false;
ew.PREVIEW || ew.ready("head", ew.PATH_BASE + "js/preview.js?v=24.16.0", "preview");
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "entradas") {
    if ($Page->MasterRecordExists) {
        include_once "views/EntradasMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "salidas") {
    if ($Page->MasterRecordExists) {
        include_once "views/SalidasMaster.php";
    }
}
?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<form name="fentradas_salidassrch" id="fentradas_salidassrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>" autocomplete="off">
<div id="fentradas_salidassrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { entradas_salidas: currentTable } });
var currentForm;
var fentradas_salidassrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery,
        fields = currentTable.fields;

    // Form object for search
    let form = new ew.FormBuilder()
        .setId("fentradas_salidassrch")
        .setPageId("list")
<?php if ($Page->UseAjaxActions) { ?>
        .setSubmitWithFetch(true)
<?php } ?>

        // Dynamic selection lists
        .setLists({
        })

        // Filters
        .setFilterList(<?= $Page->getFilterList() ?>)

        // Init search panel as collapsed
        .setInitSearchPanel(true)
        .build();
    window[form.id] = form;
    currentSearchForm = form;
    loadjs.done(form.id);
});
</script>
<input type="hidden" name="cmd" value="search">
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !($Page->CurrentAction && $Page->CurrentAction != "search") && $Page->hasSearchFields()) { ?>
<div class="ew-extended-search container-fluid ps-2">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fentradas_salidassrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fentradas_salidassrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fentradas_salidassrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fentradas_salidassrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
<?php } ?>
<?php } ?>
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="list<?= ($Page->TotalRecords == 0 && !$Page->isAdd()) ? " ew-no-record" : "" ?>">
<div id="ew-header-options">
<?php $Page->HeaderOptions?->render("body") ?>
</div>
<div id="ew-list">
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?= $Page->isAddOrEdit() ? " ew-grid-add-edit" : "" ?> <?= $Page->TableGridClass ?>">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="<?= $Page->FormName ?>" id="<?= $Page->FormName ?>" class="ew-form ew-list-form" action="<?= $Page->PageAction ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="entradas_salidas">
<?php if ($Page->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "entradas" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="entradas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "salidas" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="salidas">
<input type="hidden" name="fk_tipo_documento" value="<?= HtmlEncode($Page->tipo_documento->getSessionValue()) ?>">
<input type="hidden" name="fk_id" value="<?= HtmlEncode($Page->id_documento->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_entradas_salidas" class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<table id="tbl_entradas_salidaslist" class="<?= $Page->TableClass ?>"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = RowType::HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Page->articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_articulo" class="entradas_salidas_articulo"><?= $Page->renderFieldHeader($Page->articulo) ?></div></th>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Page->lote->headerCellClass() ?>"><div id="elh_entradas_salidas_lote" class="entradas_salidas_lote"><?= $Page->renderFieldHeader($Page->lote) ?></div></th>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><div id="elh_entradas_salidas_fecha_vencimiento" class="entradas_salidas_fecha_vencimiento"><?= $Page->renderFieldHeader($Page->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
        <th data-name="almacen" class="<?= $Page->almacen->headerCellClass() ?>"><div id="elh_entradas_salidas_almacen" class="entradas_salidas_almacen"><?= $Page->renderFieldHeader($Page->almacen) ?></div></th>
<?php } ?>
<?php if ($Page->id_compra->Visible) { // id_compra ?>
        <th data-name="id_compra" class="<?= $Page->id_compra->headerCellClass() ?>"><div id="elh_entradas_salidas_id_compra" class="entradas_salidas_id_compra"><?= $Page->renderFieldHeader($Page->id_compra) ?></div></th>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo"><?= $Page->renderFieldHeader($Page->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th data-name="precio_unidad_sin_desc" class="<?= $Page->precio_unidad_sin_desc->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc"><?= $Page->renderFieldHeader($Page->precio_unidad_sin_desc) ?></div></th>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Page->descuento->headerCellClass() ?>"><div id="elh_entradas_salidas_descuento" class="entradas_salidas_descuento"><?= $Page->renderFieldHeader($Page->descuento) ?></div></th>
<?php } ?>
<?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <th data-name="costo_unidad" class="<?= $Page->costo_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad"><?= $Page->renderFieldHeader($Page->costo_unidad) ?></div></th>
<?php } ?>
<?php if ($Page->costo->Visible) { // costo ?>
        <th data-name="costo" class="<?= $Page->costo->headerCellClass() ?>"><div id="elh_entradas_salidas_costo" class="entradas_salidas_costo"><?= $Page->renderFieldHeader($Page->costo) ?></div></th>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <th data-name="precio_unidad" class="<?= $Page->precio_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad"><?= $Page->renderFieldHeader($Page->precio_unidad) ?></div></th>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Page->precio->headerCellClass() ?>"><div id="elh_entradas_salidas_precio" class="entradas_salidas_precio"><?= $Page->renderFieldHeader($Page->precio) ?></div></th>
<?php } ?>
<?php if ($Page->check_ne->Visible) { // check_ne ?>
        <th data-name="check_ne" class="<?= $Page->check_ne->headerCellClass() ?>"><div id="elh_entradas_salidas_check_ne" class="entradas_salidas_check_ne"><?= $Page->renderFieldHeader($Page->check_ne) ?></div></th>
<?php } ?>
<?php if ($Page->newdata->Visible) { // newdata ?>
        <th data-name="newdata" class="<?= $Page->newdata->headerCellClass() ?>"><div id="elh_entradas_salidas_newdata" class="entradas_salidas_newdata"><?= $Page->renderFieldHeader($Page->newdata) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody data-page="<?= $Page->getPageNumber() ?>">
<?php
$Page->setupGrid();
$isInlineAddOrCopy = ($Page->isCopy() || $Page->isAdd());
while ($Page->RecordCount < $Page->StopRecord || $Page->RowIndex === '$rowindex$' || $isInlineAddOrCopy && $Page->RowIndex == 0) {
    if (
        $Page->CurrentRow !== false &&
        $Page->RowIndex !== '$rowindex$' &&
        (!$Page->isGridAdd() || $Page->CurrentMode == "copy") &&
        !($isInlineAddOrCopy && $Page->RowIndex == 0)
    ) {
        $Page->fetch();
    }
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->setupRow();

        // Skip 1) delete row / empty row for confirm page, 2) hidden row
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow()) &&
            $Page->RowAction != "hide"
        ) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Page->articulo->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_articulo" class="el_entradas_salidas_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" data-table="entradas_salidas" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_articulo" id="o<?= $Page->RowIndex ?>_articulo" value="<?= HtmlEncode($Page->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_articulo" class="el_entradas_salidas_articulo">
<input type="<?= $Page->articulo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_articulo" id="x<?= $Page->RowIndex ?>_articulo" data-table="entradas_salidas" data-field="x_articulo" value="<?= $Page->articulo->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Page->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->articulo->formatPattern()) ?>"<?= $Page->articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_articulo" class="el_entradas_salidas_articulo">
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Page->lote->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_lote" class="el_entradas_salidas_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_lote" id="x<?= $Page->RowIndex ?>_lote" data-table="entradas_salidas" data-field="x_lote" value="<?= $Page->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->lote->formatPattern()) ?>"<?= $Page->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_lote" id="o<?= $Page->RowIndex ?>_lote" value="<?= HtmlEncode($Page->lote->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_lote" class="el_entradas_salidas_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_lote" id="x<?= $Page->RowIndex ?>_lote" data-table="entradas_salidas" data-field="x_lote" value="<?= $Page->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->lote->formatPattern()) ?>"<?= $Page->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_lote" class="el_entradas_salidas_lote">
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento"<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_fecha_vencimiento" class="el_entradas_salidas_fecha_vencimiento">
<input type="<?= $Page->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_fecha_vencimiento" id="x<?= $Page->RowIndex ?>_fecha_vencimiento" data-table="entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Page->fecha_vencimiento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_vencimiento->formatPattern()) ?>"<?= $Page->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Page->fecha_vencimiento->ReadOnly && !$Page->fecha_vencimiento->Disabled && !isset($Page->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Page->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["<?= $Page->FormName ?>", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("<?= $Page->FormName ?>", "x<?= $Page->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_fecha_vencimiento" id="o<?= $Page->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Page->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_fecha_vencimiento" class="el_entradas_salidas_fecha_vencimiento">
<input type="<?= $Page->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_fecha_vencimiento" id="x<?= $Page->RowIndex ?>_fecha_vencimiento" data-table="entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Page->fecha_vencimiento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->fecha_vencimiento->formatPattern()) ?>"<?= $Page->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Page->fecha_vencimiento->ReadOnly && !$Page->fecha_vencimiento->Disabled && !isset($Page->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Page->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["<?= $Page->FormName ?>", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker("<?= $Page->FormName ?>", "x<?= $Page->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_fecha_vencimiento" class="el_entradas_salidas_fecha_vencimiento">
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->almacen->Visible) { // almacen ?>
        <td data-name="almacen"<?= $Page->almacen->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_almacen" class="el_entradas_salidas_almacen">
    <select
        id="x<?= $Page->RowIndex ?>_almacen"
        name="x<?= $Page->RowIndex ?>_almacen"
        class="form-select ew-select<?= $Page->almacen->isInvalidClass() ?>"
        <?php if (!$Page->almacen->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_almacen"
        <?php } ?>
        data-table="entradas_salidas"
        data-field="x_almacen"
        data-value-separator="<?= $Page->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->almacen->getPlaceHolder()) ?>"
        <?= $Page->almacen->editAttributes() ?>>
        <?= $Page->almacen->selectOptionListHtml("x{$Page->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->almacen->getErrorMessage() ?></div>
<?= $Page->almacen->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_almacen") ?>
<?php if (!$Page->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_almacen", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_almacen", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_almacen", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas_salidas.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_almacen" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_almacen" id="o<?= $Page->RowIndex ?>_almacen" value="<?= HtmlEncode($Page->almacen->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_almacen" class="el_entradas_salidas_almacen">
    <select
        id="x<?= $Page->RowIndex ?>_almacen"
        name="x<?= $Page->RowIndex ?>_almacen"
        class="form-select ew-select<?= $Page->almacen->isInvalidClass() ?>"
        <?php if (!$Page->almacen->IsNativeSelect) { ?>
        data-select2-id="<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_almacen"
        <?php } ?>
        data-table="entradas_salidas"
        data-field="x_almacen"
        data-value-separator="<?= $Page->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->almacen->getPlaceHolder()) ?>"
        <?= $Page->almacen->editAttributes() ?>>
        <?= $Page->almacen->selectOptionListHtml("x{$Page->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->almacen->getErrorMessage() ?></div>
<?= $Page->almacen->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_almacen") ?>
<?php if (!$Page->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("<?= $Page->FormName ?>", function() {
    var options = { name: "x<?= $Page->RowIndex ?>_almacen", selectId: "<?= $Page->FormName ?>_x<?= $Page->RowIndex ?>_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (<?= $Page->FormName ?>.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Page->RowIndex ?>_almacen", form: "<?= $Page->FormName ?>" };
    } else {
        options.ajax = { id: "x<?= $Page->RowIndex ?>_almacen", form: "<?= $Page->FormName ?>", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas_salidas.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_almacen" class="el_entradas_salidas_almacen">
<span<?= $Page->almacen->viewAttributes() ?>>
<?= $Page->almacen->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->id_compra->Visible) { // id_compra ?>
        <td data-name="id_compra"<?= $Page->id_compra->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_id_compra" class="el_entradas_salidas_id_compra">
<input type="<?= $Page->id_compra->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_id_compra" id="x<?= $Page->RowIndex ?>_id_compra" data-table="entradas_salidas" data-field="x_id_compra" value="<?= $Page->id_compra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->id_compra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id_compra->formatPattern()) ?>"<?= $Page->id_compra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->id_compra->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_id_compra" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_id_compra" id="o<?= $Page->RowIndex ?>_id_compra" value="<?= HtmlEncode($Page->id_compra->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_id_compra" class="el_entradas_salidas_id_compra">
<input type="<?= $Page->id_compra->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_id_compra" id="x<?= $Page->RowIndex ?>_id_compra" data-table="entradas_salidas" data-field="x_id_compra" value="<?= $Page->id_compra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->id_compra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->id_compra->formatPattern()) ?>"<?= $Page->id_compra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->id_compra->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_id_compra" class="el_entradas_salidas_id_compra">
<span<?= $Page->id_compra->viewAttributes() ?>>
<?= $Page->id_compra->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo"<?= $Page->cantidad_articulo->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="el_entradas_salidas_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_cantidad_articulo" id="x<?= $Page->RowIndex ?>_cantidad_articulo" data-table="entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Page->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_articulo->formatPattern()) ?>"<?= $Page->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_cantidad_articulo" id="o<?= $Page->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Page->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="el_entradas_salidas_cantidad_articulo">
<input type="<?= $Page->cantidad_articulo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_cantidad_articulo" id="x<?= $Page->RowIndex ?>_cantidad_articulo" data-table="entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Page->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_articulo->formatPattern()) ?>"<?= $Page->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_cantidad_articulo" class="el_entradas_salidas_cantidad_articulo">
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc"<?= $Page->precio_unidad_sin_desc->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="el_entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Page->precio_unidad_sin_desc->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" value="<?= $Page->precio_unidad_sin_desc->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio_unidad_sin_desc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio_unidad_sin_desc->formatPattern()) ?>"<?= $Page->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Page->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Page->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="el_entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Page->precio_unidad_sin_desc->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Page->RowIndex ?>_precio_unidad_sin_desc" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" value="<?= $Page->precio_unidad_sin_desc->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio_unidad_sin_desc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio_unidad_sin_desc->formatPattern()) ?>"<?= $Page->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="el_entradas_salidas_precio_unidad_sin_desc">
<span<?= $Page->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Page->precio_unidad_sin_desc->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Page->descuento->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_descuento" class="el_entradas_salidas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_descuento" id="x<?= $Page->RowIndex ?>_descuento" data-table="entradas_salidas" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_descuento" id="o<?= $Page->RowIndex ?>_descuento" value="<?= HtmlEncode($Page->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_descuento" class="el_entradas_salidas_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_descuento" id="x<?= $Page->RowIndex ?>_descuento" data-table="entradas_salidas" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_descuento" class="el_entradas_salidas_descuento">
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad"<?= $Page->costo_unidad->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_costo_unidad" class="el_entradas_salidas_costo_unidad">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_costo_unidad" id="x<?= $Page->RowIndex ?>_costo_unidad" data-table="entradas_salidas" data-field="x_costo_unidad" value="<?= $Page->costo_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->costo_unidad->formatPattern()) ?>"<?= $Page->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_costo_unidad" id="o<?= $Page->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Page->costo_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_costo_unidad" class="el_entradas_salidas_costo_unidad">
<input type="<?= $Page->costo_unidad->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_costo_unidad" id="x<?= $Page->RowIndex ?>_costo_unidad" data-table="entradas_salidas" data-field="x_costo_unidad" value="<?= $Page->costo_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->costo_unidad->formatPattern()) ?>"<?= $Page->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_costo_unidad" class="el_entradas_salidas_costo_unidad">
<span<?= $Page->costo_unidad->viewAttributes() ?>>
<?= $Page->costo_unidad->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->costo->Visible) { // costo ?>
        <td data-name="costo"<?= $Page->costo->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_costo" class="el_entradas_salidas_costo">
<input type="<?= $Page->costo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_costo" id="x<?= $Page->RowIndex ?>_costo" data-table="entradas_salidas" data-field="x_costo" value="<?= $Page->costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->costo->formatPattern()) ?>"<?= $Page->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_costo" id="o<?= $Page->RowIndex ?>_costo" value="<?= HtmlEncode($Page->costo->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_costo" class="el_entradas_salidas_costo">
<input type="<?= $Page->costo->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_costo" id="x<?= $Page->RowIndex ?>_costo" data-table="entradas_salidas" data-field="x_costo" value="<?= $Page->costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->costo->formatPattern()) ?>"<?= $Page->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_costo" class="el_entradas_salidas_costo">
<span<?= $Page->costo->viewAttributes() ?>>
<?= $Page->costo->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad"<?= $Page->precio_unidad->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio_unidad" class="el_entradas_salidas_precio_unidad">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_precio_unidad" id="x<?= $Page->RowIndex ?>_precio_unidad" data-table="entradas_salidas" data-field="x_precio_unidad" value="<?= $Page->precio_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio_unidad->formatPattern()) ?>"<?= $Page->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_precio_unidad" id="o<?= $Page->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Page->precio_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio_unidad" class="el_entradas_salidas_precio_unidad">
<input type="<?= $Page->precio_unidad->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_precio_unidad" id="x<?= $Page->RowIndex ?>_precio_unidad" data-table="entradas_salidas" data-field="x_precio_unidad" value="<?= $Page->precio_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio_unidad->formatPattern()) ?>"<?= $Page->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio_unidad" class="el_entradas_salidas_precio_unidad">
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->precio->Visible) { // precio ?>
        <td data-name="precio"<?= $Page->precio->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio" class="el_entradas_salidas_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" data-table="entradas_salidas" data-field="x_precio" value="<?= $Page->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio->formatPattern()) ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_precio" id="o<?= $Page->RowIndex ?>_precio" value="<?= HtmlEncode($Page->precio->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio" class="el_entradas_salidas_precio">
<input type="<?= $Page->precio->getInputTextType() ?>" name="x<?= $Page->RowIndex ?>_precio" id="x<?= $Page->RowIndex ?>_precio" data-table="entradas_salidas" data-field="x_precio" value="<?= $Page->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->precio->formatPattern()) ?>"<?= $Page->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_precio" class="el_entradas_salidas_precio">
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->check_ne->Visible) { // check_ne ?>
        <td data-name="check_ne"<?= $Page->check_ne->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_check_ne" class="el_entradas_salidas_check_ne">
<template id="tp_x<?= $Page->RowIndex ?>_check_ne">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="entradas_salidas" data-field="x_check_ne" name="x<?= $Page->RowIndex ?>_check_ne" id="x<?= $Page->RowIndex ?>_check_ne"<?= $Page->check_ne->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Page->RowIndex ?>_check_ne" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Page->RowIndex ?>_check_ne[]"
    name="x<?= $Page->RowIndex ?>_check_ne[]"
    value="<?= HtmlEncode($Page->check_ne->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x<?= $Page->RowIndex ?>_check_ne"
    data-target="dsl_x<?= $Page->RowIndex ?>_check_ne"
    data-repeatcolumn="5"
    class="form-control<?= $Page->check_ne->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_check_ne"
    data-value-separator="<?= $Page->check_ne->displayValueSeparatorAttribute() ?>"
    <?= $Page->check_ne->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->check_ne->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_check_ne[]" id="o<?= $Page->RowIndex ?>_check_ne[]" value="<?= HtmlEncode($Page->check_ne->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_check_ne" class="el_entradas_salidas_check_ne">
<span<?= $Page->check_ne->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->check_ne->getDisplayValue($Page->check_ne->EditValue) ?></span></span>
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" name="x<?= $Page->RowIndex ?>_check_ne" id="x<?= $Page->RowIndex ?>_check_ne" value="<?= HtmlEncode($Page->check_ne->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_check_ne" class="el_entradas_salidas_check_ne">
<span<?= $Page->check_ne->viewAttributes() ?>>
<?= $Page->check_ne->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Page->newdata->Visible) { // newdata ?>
        <td data-name="newdata"<?= $Page->newdata->cellAttributes() ?>>
<?php if ($Page->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_newdata" class="el_entradas_salidas_newdata">
<template id="tp_x<?= $Page->RowIndex ?>_newdata">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="entradas_salidas" data-field="x_newdata" name="x<?= $Page->RowIndex ?>_newdata" id="x<?= $Page->RowIndex ?>_newdata"<?= $Page->newdata->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Page->RowIndex ?>_newdata" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Page->RowIndex ?>_newdata"
    name="x<?= $Page->RowIndex ?>_newdata"
    value="<?= HtmlEncode($Page->newdata->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Page->RowIndex ?>_newdata"
    data-target="dsl_x<?= $Page->RowIndex ?>_newdata"
    data-repeatcolumn="5"
    class="form-control<?= $Page->newdata->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_newdata"
    data-value-separator="<?= $Page->newdata->displayValueSeparatorAttribute() ?>"
    <?= $Page->newdata->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->newdata->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_newdata" data-hidden="1" data-old name="o<?= $Page->RowIndex ?>_newdata" id="o<?= $Page->RowIndex ?>_newdata" value="<?= HtmlEncode($Page->newdata->OldValue) ?>">
<?php } ?>
<?php if ($Page->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_newdata" class="el_entradas_salidas_newdata">
<template id="tp_x<?= $Page->RowIndex ?>_newdata">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="entradas_salidas" data-field="x_newdata" name="x<?= $Page->RowIndex ?>_newdata" id="x<?= $Page->RowIndex ?>_newdata"<?= $Page->newdata->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Page->RowIndex ?>_newdata" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Page->RowIndex ?>_newdata"
    name="x<?= $Page->RowIndex ?>_newdata"
    value="<?= HtmlEncode($Page->newdata->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Page->RowIndex ?>_newdata"
    data-target="dsl_x<?= $Page->RowIndex ?>_newdata"
    data-repeatcolumn="5"
    class="form-control<?= $Page->newdata->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_newdata"
    data-value-separator="<?= $Page->newdata->displayValueSeparatorAttribute() ?>"
    <?= $Page->newdata->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->newdata->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Page->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Page->RowIndex == '$rowindex$' ? '$rowindex$' : $Page->RowCount ?>_entradas_salidas_newdata" class="el_entradas_salidas_newdata">
<span<?= $Page->newdata->viewAttributes() ?>>
<?= $Page->newdata->getViewValue() ?></span>
</span>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php if ($Page->RowType == RowType::ADD || $Page->RowType == RowType::EDIT) { ?>
<script data-rowindex="<?= $Page->RowIndex ?>">
loadjs.ready(["<?= $Page->FormName ?>","load"], () => <?= $Page->FormName ?>.updateLists(<?= $Page->RowIndex ?><?= $Page->isAdd() || $Page->isEdit() || $Page->isCopy() || $Page->RowIndex === '$rowindex$' ? ", true" : "" ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking

    // Reset for template row
    if ($Page->RowIndex === '$rowindex$') {
        $Page->RowIndex = 0;
    }
    // Reset inline add/copy row
    if (($Page->isCopy() || $Page->isAdd()) && $Page->RowIndex == 0) {
        $Page->RowIndex = 1;
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
<?php if ($Page->isGridAdd()) { ?>
<input type="hidden" name="action" id="action" value="gridinsert">
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
<?php if ($Page->isGridEdit() || $Page->isMultiEdit()) { ?>
<?php if ($Page->isGridEdit()) { ?>
<input type="hidden" name="action" id="action" value="gridupdate">
<?php } elseif ($Page->isMultiEdit()) { ?>
<input type="hidden" name="action" id="action" value="multiupdate">
<?php } ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<?= $Page->MultiSelectKey ?>
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction && !$Page->UseAjaxActions) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close result set
$Page->Recordset?->free();
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd() && !($Page->isGridEdit() && $Page->ModalGridEdit) && !$Page->isMultiEdit()) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
</div>
<div id="ew-footer-options">
<?php $Page->FooterOptions?->render("body") ?>
</div>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
