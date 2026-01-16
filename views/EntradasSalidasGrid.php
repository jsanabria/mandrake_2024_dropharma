<?php

namespace PHPMaker2024\mandrake;

// Set up and run Grid object
$Grid = Container("EntradasSalidasGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fentradas_salidasgrid;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { entradas_salidas: currentTable } });
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fentradas_salidasgrid")
        .setPageId("grid")
        .setFormKeyCountName("<?= $Grid->FormKeyCountName ?>")

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
            "almacen": <?= $Grid->almacen->toClientList($Grid) ?>,
            "check_ne": <?= $Grid->check_ne->toClientList($Grid) ?>,
            "newdata": <?= $Grid->newdata->toClientList($Grid) ?>,
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
<div id="fentradas_salidasgrid" class="ew-form ew-list-form">
<div id="gmp_entradas_salidas" class="card-body ew-grid-middle-panel <?= $Grid->TableContainerClass ?>" style="<?= $Grid->TableContainerStyle ?>">
<table id="tbl_entradas_salidasgrid" class="<?= $Grid->TableClass ?>"><!-- .ew-table -->
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
<?php if ($Grid->articulo->Visible) { // articulo ?>
        <th data-name="articulo" class="<?= $Grid->articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_articulo" class="entradas_salidas_articulo"><?= $Grid->renderFieldHeader($Grid->articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->lote->Visible) { // lote ?>
        <th data-name="lote" class="<?= $Grid->lote->headerCellClass() ?>"><div id="elh_entradas_salidas_lote" class="entradas_salidas_lote"><?= $Grid->renderFieldHeader($Grid->lote) ?></div></th>
<?php } ?>
<?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <th data-name="fecha_vencimiento" class="<?= $Grid->fecha_vencimiento->headerCellClass() ?>"><div id="elh_entradas_salidas_fecha_vencimiento" class="entradas_salidas_fecha_vencimiento"><?= $Grid->renderFieldHeader($Grid->fecha_vencimiento) ?></div></th>
<?php } ?>
<?php if ($Grid->almacen->Visible) { // almacen ?>
        <th data-name="almacen" class="<?= $Grid->almacen->headerCellClass() ?>"><div id="elh_entradas_salidas_almacen" class="entradas_salidas_almacen"><?= $Grid->renderFieldHeader($Grid->almacen) ?></div></th>
<?php } ?>
<?php if ($Grid->id_compra->Visible) { // id_compra ?>
        <th data-name="id_compra" class="<?= $Grid->id_compra->headerCellClass() ?>"><div id="elh_entradas_salidas_id_compra" class="entradas_salidas_id_compra"><?= $Grid->renderFieldHeader($Grid->id_compra) ?></div></th>
<?php } ?>
<?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <th data-name="cantidad_articulo" class="<?= $Grid->cantidad_articulo->headerCellClass() ?>"><div id="elh_entradas_salidas_cantidad_articulo" class="entradas_salidas_cantidad_articulo"><?= $Grid->renderFieldHeader($Grid->cantidad_articulo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <th data-name="precio_unidad_sin_desc" class="<?= $Grid->precio_unidad_sin_desc->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad_sin_desc" class="entradas_salidas_precio_unidad_sin_desc"><?= $Grid->renderFieldHeader($Grid->precio_unidad_sin_desc) ?></div></th>
<?php } ?>
<?php if ($Grid->descuento->Visible) { // descuento ?>
        <th data-name="descuento" class="<?= $Grid->descuento->headerCellClass() ?>"><div id="elh_entradas_salidas_descuento" class="entradas_salidas_descuento"><?= $Grid->renderFieldHeader($Grid->descuento) ?></div></th>
<?php } ?>
<?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <th data-name="costo_unidad" class="<?= $Grid->costo_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_costo_unidad" class="entradas_salidas_costo_unidad"><?= $Grid->renderFieldHeader($Grid->costo_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->costo->Visible) { // costo ?>
        <th data-name="costo" class="<?= $Grid->costo->headerCellClass() ?>"><div id="elh_entradas_salidas_costo" class="entradas_salidas_costo"><?= $Grid->renderFieldHeader($Grid->costo) ?></div></th>
<?php } ?>
<?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <th data-name="precio_unidad" class="<?= $Grid->precio_unidad->headerCellClass() ?>"><div id="elh_entradas_salidas_precio_unidad" class="entradas_salidas_precio_unidad"><?= $Grid->renderFieldHeader($Grid->precio_unidad) ?></div></th>
<?php } ?>
<?php if ($Grid->precio->Visible) { // precio ?>
        <th data-name="precio" class="<?= $Grid->precio->headerCellClass() ?>"><div id="elh_entradas_salidas_precio" class="entradas_salidas_precio"><?= $Grid->renderFieldHeader($Grid->precio) ?></div></th>
<?php } ?>
<?php if ($Grid->check_ne->Visible) { // check_ne ?>
        <th data-name="check_ne" class="<?= $Grid->check_ne->headerCellClass() ?>"><div id="elh_entradas_salidas_check_ne" class="entradas_salidas_check_ne"><?= $Grid->renderFieldHeader($Grid->check_ne) ?></div></th>
<?php } ?>
<?php if ($Grid->newdata->Visible) { // newdata ?>
        <th data-name="newdata" class="<?= $Grid->newdata->headerCellClass() ?>"><div id="elh_entradas_salidas_newdata" class="entradas_salidas_newdata"><?= $Grid->renderFieldHeader($Grid->newdata) ?></div></th>
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
    <?php if ($Grid->articulo->Visible) { // articulo ?>
        <td data-name="articulo"<?= $Grid->articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_articulo" class="el_entradas_salidas_articulo">
<input type="<?= $Grid->articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" data-table="entradas_salidas" data-field="x_articulo" value="<?= $Grid->articulo->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo->formatPattern()) ?>"<?= $Grid->articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_articulo" id="o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_articulo" class="el_entradas_salidas_articulo">
<input type="<?= $Grid->articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_articulo" id="x<?= $Grid->RowIndex ?>_articulo" data-table="entradas_salidas" data-field="x_articulo" value="<?= $Grid->articulo->EditValue ?>" size="20" placeholder="<?= HtmlEncode($Grid->articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->articulo->formatPattern()) ?>"<?= $Grid->articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_articulo" class="el_entradas_salidas_articulo">
<span<?= $Grid->articulo->viewAttributes() ?>>
<?= $Grid->articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_articulo" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_articulo" value="<?= HtmlEncode($Grid->articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->lote->Visible) { // lote ?>
        <td data-name="lote"<?= $Grid->lote->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_lote" class="el_entradas_salidas_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="entradas_salidas" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_lote" id="o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_lote" class="el_entradas_salidas_lote">
<input type="<?= $Grid->lote->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_lote" id="x<?= $Grid->RowIndex ?>_lote" data-table="entradas_salidas" data-field="x_lote" value="<?= $Grid->lote->EditValue ?>" size="10" maxlength="20" placeholder="<?= HtmlEncode($Grid->lote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->lote->formatPattern()) ?>"<?= $Grid->lote->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->lote->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_lote" class="el_entradas_salidas_lote">
<span<?= $Grid->lote->viewAttributes() ?>>
<?= $Grid->lote->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_lote" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_lote" value="<?= HtmlEncode($Grid->lote->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <td data-name="fecha_vencimiento"<?= $Grid->fecha_vencimiento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_fecha_vencimiento" class="el_entradas_salidas_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_vencimiento->ReadOnly && !$Grid->fecha_vencimiento->Disabled && !isset($Grid->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Grid->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fentradas_salidasgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fentradas_salidasgrid", "x<?= $Grid->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_fecha_vencimiento" class="el_entradas_salidas_fecha_vencimiento">
<input type="<?= $Grid->fecha_vencimiento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="x<?= $Grid->RowIndex ?>_fecha_vencimiento" data-table="entradas_salidas" data-field="x_fecha_vencimiento" value="<?= $Grid->fecha_vencimiento->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->fecha_vencimiento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->fecha_vencimiento->formatPattern()) ?>"<?= $Grid->fecha_vencimiento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Grid->fecha_vencimiento->ReadOnly && !$Grid->fecha_vencimiento->Disabled && !isset($Grid->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Grid->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fentradas_salidasgrid", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fentradas_salidasgrid", "x<?= $Grid->RowIndex ?>_fecha_vencimiento", ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_fecha_vencimiento" class="el_entradas_salidas_fecha_vencimiento">
<span<?= $Grid->fecha_vencimiento->viewAttributes() ?>>
<?= $Grid->fecha_vencimiento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_fecha_vencimiento" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_fecha_vencimiento" value="<?= HtmlEncode($Grid->fecha_vencimiento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->almacen->Visible) { // almacen ?>
        <td data-name="almacen"<?= $Grid->almacen->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_almacen" class="el_entradas_salidas_almacen">
    <select
        id="x<?= $Grid->RowIndex ?>_almacen"
        name="x<?= $Grid->RowIndex ?>_almacen"
        class="form-select ew-select<?= $Grid->almacen->isInvalidClass() ?>"
        <?php if (!$Grid->almacen->IsNativeSelect) { ?>
        data-select2-id="fentradas_salidasgrid_x<?= $Grid->RowIndex ?>_almacen"
        <?php } ?>
        data-table="entradas_salidas"
        data-field="x_almacen"
        data-value-separator="<?= $Grid->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>"
        <?= $Grid->almacen->editAttributes() ?>>
        <?= $Grid->almacen->selectOptionListHtml("x{$Grid->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
<?= $Grid->almacen->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_almacen") ?>
<?php if (!$Grid->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradas_salidasgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_almacen", selectId: "fentradas_salidasgrid_x<?= $Grid->RowIndex ?>_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradas_salidasgrid.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fentradas_salidasgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fentradas_salidasgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas_salidas.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_almacen" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_almacen" id="o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_almacen" class="el_entradas_salidas_almacen">
    <select
        id="x<?= $Grid->RowIndex ?>_almacen"
        name="x<?= $Grid->RowIndex ?>_almacen"
        class="form-select ew-select<?= $Grid->almacen->isInvalidClass() ?>"
        <?php if (!$Grid->almacen->IsNativeSelect) { ?>
        data-select2-id="fentradas_salidasgrid_x<?= $Grid->RowIndex ?>_almacen"
        <?php } ?>
        data-table="entradas_salidas"
        data-field="x_almacen"
        data-value-separator="<?= $Grid->almacen->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->almacen->getPlaceHolder()) ?>"
        <?= $Grid->almacen->editAttributes() ?>>
        <?= $Grid->almacen->selectOptionListHtml("x{$Grid->RowIndex}_almacen") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->almacen->getErrorMessage() ?></div>
<?= $Grid->almacen->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_almacen") ?>
<?php if (!$Grid->almacen->IsNativeSelect) { ?>
<script>
loadjs.ready("fentradas_salidasgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_almacen", selectId: "fentradas_salidasgrid_x<?= $Grid->RowIndex ?>_almacen" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fentradas_salidasgrid.lists.almacen?.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fentradas_salidasgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_almacen", form: "fentradas_salidasgrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.entradas_salidas.fields.almacen.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_almacen" class="el_entradas_salidas_almacen">
<span<?= $Grid->almacen->viewAttributes() ?>>
<?= $Grid->almacen->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_almacen" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_almacen" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_almacen" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_almacen" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_almacen" value="<?= HtmlEncode($Grid->almacen->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_compra->Visible) { // id_compra ?>
        <td data-name="id_compra"<?= $Grid->id_compra->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_id_compra" class="el_entradas_salidas_id_compra">
<input type="<?= $Grid->id_compra->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_compra" id="x<?= $Grid->RowIndex ?>_id_compra" data-table="entradas_salidas" data-field="x_id_compra" value="<?= $Grid->id_compra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_compra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_compra->formatPattern()) ?>"<?= $Grid->id_compra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_compra->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_id_compra" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_id_compra" id="o<?= $Grid->RowIndex ?>_id_compra" value="<?= HtmlEncode($Grid->id_compra->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_id_compra" class="el_entradas_salidas_id_compra">
<input type="<?= $Grid->id_compra->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_id_compra" id="x<?= $Grid->RowIndex ?>_id_compra" data-table="entradas_salidas" data-field="x_id_compra" value="<?= $Grid->id_compra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->id_compra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->id_compra->formatPattern()) ?>"<?= $Grid->id_compra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_compra->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_id_compra" class="el_entradas_salidas_id_compra">
<span<?= $Grid->id_compra->viewAttributes() ?>>
<?= $Grid->id_compra->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_id_compra" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_id_compra" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_id_compra" value="<?= HtmlEncode($Grid->id_compra->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_id_compra" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_id_compra" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_id_compra" value="<?= HtmlEncode($Grid->id_compra->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <td data-name="cantidad_articulo"<?= $Grid->cantidad_articulo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_cantidad_articulo" class="el_entradas_salidas_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_cantidad_articulo" id="o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_cantidad_articulo" class="el_entradas_salidas_cantidad_articulo">
<input type="<?= $Grid->cantidad_articulo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_cantidad_articulo" id="x<?= $Grid->RowIndex ?>_cantidad_articulo" data-table="entradas_salidas" data-field="x_cantidad_articulo" value="<?= $Grid->cantidad_articulo->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Grid->cantidad_articulo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->cantidad_articulo->formatPattern()) ?>"<?= $Grid->cantidad_articulo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->cantidad_articulo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_cantidad_articulo" class="el_entradas_salidas_cantidad_articulo">
<span<?= $Grid->cantidad_articulo->viewAttributes() ?>>
<?= $Grid->cantidad_articulo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_cantidad_articulo" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_cantidad_articulo" value="<?= HtmlEncode($Grid->cantidad_articulo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad_sin_desc->Visible) { // precio_unidad_sin_desc ?>
        <td data-name="precio_unidad_sin_desc"<?= $Grid->precio_unidad_sin_desc->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="el_entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad_sin_desc->formatPattern()) ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="el_entradas_salidas_precio_unidad_sin_desc">
<input type="<?= $Grid->precio_unidad_sin_desc->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" value="<?= $Grid->precio_unidad_sin_desc->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio_unidad_sin_desc->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad_sin_desc->formatPattern()) ?>"<?= $Grid->precio_unidad_sin_desc->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad_sin_desc->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio_unidad_sin_desc" class="el_entradas_salidas_precio_unidad_sin_desc">
<span<?= $Grid->precio_unidad_sin_desc->viewAttributes() ?>>
<?= $Grid->precio_unidad_sin_desc->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad_sin_desc" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad_sin_desc" value="<?= HtmlEncode($Grid->precio_unidad_sin_desc->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->descuento->Visible) { // descuento ?>
        <td data-name="descuento"<?= $Grid->descuento->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_descuento" class="el_entradas_salidas_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="entradas_salidas" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_descuento" id="o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_descuento" class="el_entradas_salidas_descuento">
<input type="<?= $Grid->descuento->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_descuento" id="x<?= $Grid->RowIndex ?>_descuento" data-table="entradas_salidas" data-field="x_descuento" value="<?= $Grid->descuento->EditValue ?>" size="6" placeholder="<?= HtmlEncode($Grid->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->descuento->formatPattern()) ?>"<?= $Grid->descuento->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->descuento->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_descuento" class="el_entradas_salidas_descuento">
<span<?= $Grid->descuento->viewAttributes() ?>>
<?= $Grid->descuento->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_descuento" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_descuento" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_descuento" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_descuento" value="<?= HtmlEncode($Grid->descuento->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->costo_unidad->Visible) { // costo_unidad ?>
        <td data-name="costo_unidad"<?= $Grid->costo_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_costo_unidad" class="el_entradas_salidas_costo_unidad">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" data-table="entradas_salidas" data-field="x_costo_unidad" value="<?= $Grid->costo_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo_unidad->formatPattern()) ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_costo_unidad" id="o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_costo_unidad" class="el_entradas_salidas_costo_unidad">
<input type="<?= $Grid->costo_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo_unidad" id="x<?= $Grid->RowIndex ?>_costo_unidad" data-table="entradas_salidas" data-field="x_costo_unidad" value="<?= $Grid->costo_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->costo_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo_unidad->formatPattern()) ?>"<?= $Grid->costo_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_costo_unidad" class="el_entradas_salidas_costo_unidad">
<span<?= $Grid->costo_unidad->viewAttributes() ?>>
<?= $Grid->costo_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo_unidad" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_costo_unidad" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo_unidad" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo_unidad" value="<?= HtmlEncode($Grid->costo_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->costo->Visible) { // costo ?>
        <td data-name="costo"<?= $Grid->costo->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_costo" class="el_entradas_salidas_costo">
<input type="<?= $Grid->costo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" data-table="entradas_salidas" data-field="x_costo" value="<?= $Grid->costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo->formatPattern()) ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_costo" id="o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_costo" class="el_entradas_salidas_costo">
<input type="<?= $Grid->costo->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_costo" id="x<?= $Grid->RowIndex ?>_costo" data-table="entradas_salidas" data-field="x_costo" value="<?= $Grid->costo->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->costo->formatPattern()) ?>"<?= $Grid->costo->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->costo->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_costo" class="el_entradas_salidas_costo">
<span<?= $Grid->costo->viewAttributes() ?>>
<?= $Grid->costo->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_costo" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_costo" value="<?= HtmlEncode($Grid->costo->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio_unidad->Visible) { // precio_unidad ?>
        <td data-name="precio_unidad"<?= $Grid->precio_unidad->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio_unidad" class="el_entradas_salidas_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" data-table="entradas_salidas" data-field="x_precio_unidad" value="<?= $Grid->precio_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad->formatPattern()) ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio_unidad" id="o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio_unidad" class="el_entradas_salidas_precio_unidad">
<input type="<?= $Grid->precio_unidad->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio_unidad" id="x<?= $Grid->RowIndex ?>_precio_unidad" data-table="entradas_salidas" data-field="x_precio_unidad" value="<?= $Grid->precio_unidad->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio_unidad->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio_unidad->formatPattern()) ?>"<?= $Grid->precio_unidad->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio_unidad->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio_unidad" class="el_entradas_salidas_precio_unidad">
<span<?= $Grid->precio_unidad->viewAttributes() ?>>
<?= $Grid->precio_unidad->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_precio_unidad" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio_unidad" value="<?= HtmlEncode($Grid->precio_unidad->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->precio->Visible) { // precio ?>
        <td data-name="precio"<?= $Grid->precio->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio" class="el_entradas_salidas_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="entradas_salidas" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_precio" id="o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio" class="el_entradas_salidas_precio">
<input type="<?= $Grid->precio->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_precio" id="x<?= $Grid->RowIndex ?>_precio" data-table="entradas_salidas" data-field="x_precio" value="<?= $Grid->precio->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->precio->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Grid->precio->formatPattern()) ?>"<?= $Grid->precio->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->precio->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_precio" class="el_entradas_salidas_precio">
<span<?= $Grid->precio->viewAttributes() ?>>
<?= $Grid->precio->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_precio" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_precio" value="<?= HtmlEncode($Grid->precio->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->check_ne->Visible) { // check_ne ?>
        <td data-name="check_ne"<?= $Grid->check_ne->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_check_ne" class="el_entradas_salidas_check_ne">
<template id="tp_x<?= $Grid->RowIndex ?>_check_ne">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" data-table="entradas_salidas" data-field="x_check_ne" name="x<?= $Grid->RowIndex ?>_check_ne" id="x<?= $Grid->RowIndex ?>_check_ne"<?= $Grid->check_ne->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_check_ne" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_check_ne[]"
    name="x<?= $Grid->RowIndex ?>_check_ne[]"
    value="<?= HtmlEncode($Grid->check_ne->CurrentValue) ?>"
    data-type="select-multiple"
    data-template="tp_x<?= $Grid->RowIndex ?>_check_ne"
    data-target="dsl_x<?= $Grid->RowIndex ?>_check_ne"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->check_ne->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_check_ne"
    data-value-separator="<?= $Grid->check_ne->displayValueSeparatorAttribute() ?>"
    <?= $Grid->check_ne->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->check_ne->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_check_ne[]" id="o<?= $Grid->RowIndex ?>_check_ne[]" value="<?= HtmlEncode($Grid->check_ne->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_check_ne" class="el_entradas_salidas_check_ne">
<span<?= $Grid->check_ne->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->check_ne->getDisplayValue($Grid->check_ne->EditValue) ?></span></span>
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" name="x<?= $Grid->RowIndex ?>_check_ne" id="x<?= $Grid->RowIndex ?>_check_ne" value="<?= HtmlEncode($Grid->check_ne->CurrentValue) ?>">
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_check_ne" class="el_entradas_salidas_check_ne">
<span<?= $Grid->check_ne->viewAttributes() ?>>
<?= $Grid->check_ne->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_check_ne" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_check_ne" value="<?= HtmlEncode($Grid->check_ne->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_check_ne" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_check_ne[]" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_check_ne[]" value="<?= HtmlEncode($Grid->check_ne->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->newdata->Visible) { // newdata ?>
        <td data-name="newdata"<?= $Grid->newdata->cellAttributes() ?>>
<?php if ($Grid->RowType == RowType::ADD) { // Add record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_newdata" class="el_entradas_salidas_newdata">
<template id="tp_x<?= $Grid->RowIndex ?>_newdata">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="entradas_salidas" data-field="x_newdata" name="x<?= $Grid->RowIndex ?>_newdata" id="x<?= $Grid->RowIndex ?>_newdata"<?= $Grid->newdata->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_newdata" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_newdata"
    name="x<?= $Grid->RowIndex ?>_newdata"
    value="<?= HtmlEncode($Grid->newdata->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_newdata"
    data-target="dsl_x<?= $Grid->RowIndex ?>_newdata"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->newdata->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_newdata"
    data-value-separator="<?= $Grid->newdata->displayValueSeparatorAttribute() ?>"
    <?= $Grid->newdata->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->newdata->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="entradas_salidas" data-field="x_newdata" data-hidden="1" data-old name="o<?= $Grid->RowIndex ?>_newdata" id="o<?= $Grid->RowIndex ?>_newdata" value="<?= HtmlEncode($Grid->newdata->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == RowType::EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_newdata" class="el_entradas_salidas_newdata">
<template id="tp_x<?= $Grid->RowIndex ?>_newdata">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="entradas_salidas" data-field="x_newdata" name="x<?= $Grid->RowIndex ?>_newdata" id="x<?= $Grid->RowIndex ?>_newdata"<?= $Grid->newdata->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_newdata" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_newdata"
    name="x<?= $Grid->RowIndex ?>_newdata"
    value="<?= HtmlEncode($Grid->newdata->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_newdata"
    data-target="dsl_x<?= $Grid->RowIndex ?>_newdata"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->newdata->isInvalidClass() ?>"
    data-table="entradas_salidas"
    data-field="x_newdata"
    data-value-separator="<?= $Grid->newdata->displayValueSeparatorAttribute() ?>"
    <?= $Grid->newdata->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->newdata->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == RowType::VIEW) { // View record ?>
<span id="el<?= $Grid->RowIndex == '$rowindex$' ? '$rowindex$' : $Grid->RowCount ?>_entradas_salidas_newdata" class="el_entradas_salidas_newdata">
<span<?= $Grid->newdata->viewAttributes() ?>>
<?= $Grid->newdata->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="entradas_salidas" data-field="x_newdata" data-hidden="1" name="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_newdata" id="fentradas_salidasgrid$x<?= $Grid->RowIndex ?>_newdata" value="<?= HtmlEncode($Grid->newdata->FormValue) ?>">
<input type="hidden" data-table="entradas_salidas" data-field="x_newdata" data-hidden="1" data-old name="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_newdata" id="fentradas_salidasgrid$o<?= $Grid->RowIndex ?>_newdata" value="<?= HtmlEncode($Grid->newdata->OldValue) ?>">
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
loadjs.ready(["fentradas_salidasgrid","load"], () => fentradas_salidasgrid.updateLists(<?= $Grid->RowIndex ?><?= $Grid->isAdd() || $Grid->isEdit() || $Grid->isCopy() || $Grid->RowIndex === '$rowindex$' ? ", true" : "" ?>));
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
<input type="hidden" name="detailpage" value="fentradas_salidasgrid">
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
    ew.addEventHandlers("entradas_salidas");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
