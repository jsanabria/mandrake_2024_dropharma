<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewOutPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { view_out: <?= JsonEncode($Page->toClientVar()) ?> } });
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid <?= $Page->TableGridClass ?>"><!-- .card -->
<div class="card-header ew-grid-upper-panel ew-preview-upper-panel"><!-- .card-header -->
<?= $Page->Pager->render() ?>
<?php if ($Page->OtherOptions->visible()) { ?>
<div class="ew-preview-other-options">
<?php
    foreach ($Page->OtherOptions as $option) {
        $option->render("body");
    }
?>
</div>
<?php } ?>
</div><!-- /.card-header -->
<div class="card-body ew-preview-middle-panel ew-grid-middle-panel <?= $Page->TableContainerClass ?>" style="<?= $Page->TableContainerStyle ?>"><!-- .card-body -->
<table class="<?= $Page->TableClass ?>"><!-- .table -->
    <thead><!-- Table header -->
        <tr class="ew-table-header">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <?php if (!$Page->fabricante->Sortable || !$Page->sortUrl($Page->fabricante)) { ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><?= $Page->fabricante->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->fabricante->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fabricante->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fabricante->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fabricante->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <?php if (!$Page->articulo->Sortable || !$Page->sortUrl($Page->articulo)) { ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><?= $Page->articulo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->articulo->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->articulo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->articulo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->articulo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <?php if (!$Page->lote->Sortable || !$Page->sortUrl($Page->lote)) { ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><?= $Page->lote->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->lote->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->lote->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->lote->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->lote->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->lote->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
    <?php if (!$Page->fecha_vencimiento->Sortable || !$Page->sortUrl($Page->fecha_vencimiento)) { ?>
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><?= $Page->fecha_vencimiento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->fecha_vencimiento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha_vencimiento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fecha_vencimiento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fecha_vencimiento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
    <?php if (!$Page->almacen->Sortable || !$Page->sortUrl($Page->almacen)) { ?>
        <th class="<?= $Page->almacen->headerCellClass() ?>"><?= $Page->almacen->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->almacen->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->almacen->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->almacen->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->almacen->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->almacen->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <?php if (!$Page->cantidad_articulo->Sortable || !$Page->sortUrl($Page->cantidad_articulo)) { ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><?= $Page->cantidad_articulo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->cantidad_articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cantidad_articulo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cantidad_articulo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cantidad_articulo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
    <?php if (!$Page->precio_unidad->Sortable || !$Page->sortUrl($Page->precio_unidad)) { ?>
        <th class="<?= $Page->precio_unidad->headerCellClass() ?>"><?= $Page->precio_unidad->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->precio_unidad->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->precio_unidad->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->precio_unidad->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->precio_unidad->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->precio_unidad->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
    <?php if (!$Page->precio->Sortable || !$Page->sortUrl($Page->precio)) { ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><?= $Page->precio->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->precio->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->precio->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->precio->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->precio->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->precio->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <?php if (!$Page->alicuota->Sortable || !$Page->sortUrl($Page->alicuota)) { ?>
        <th class="<?= $Page->alicuota->headerCellClass() ?>"><?= $Page->alicuota->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->alicuota->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->alicuota->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->alicuota->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->alicuota->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->alicuota->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <?php if (!$Page->descuento->Sortable || !$Page->sortUrl($Page->descuento)) { ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><?= $Page->descuento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->descuento->headerCellClass() ?>"><div role="button" data-table="view_out" data-sort="<?= HtmlEncode($Page->descuento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->descuento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->descuento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->descuento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
        </tr>
    </thead>
    <tbody><!-- Table body -->
<?php
$Page->RecordCount = 0;
$Page->RowCount = 0;
while ($Page->fetch()) {
    // Init row class and style
    $Page->RecordCount++;
    $Page->RowCount++;
    $Page->CssStyle = "";
    $Page->loadListRowValues($Page->CurrentRow);

    // Render row
    $Page->RowType = RowType::PREVIEW; // Preview record
    $Page->resetAttributes();
    $Page->renderListRow();

    // Set up row attributes
    $Page->RowAttrs->merge([
        "data-rowindex" => $Page->RowCount,
        "class" => ($Page->RowCount % 2 != 1) ? "ew-table-alt-row" : "",

        // Add row attributes for expandable row
        "data-widget" => "expandable-table",
        "aria-expanded" => "false",
    ]);

    // Render list options
    $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
        <!-- fabricante -->
        <td<?= $Page->fabricante->cellAttributes() ?>>
<span<?= $Page->fabricante->viewAttributes() ?>>
<?= $Page->fabricante->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
        <!-- articulo -->
        <td<?= $Page->articulo->cellAttributes() ?>>
<span<?= $Page->articulo->viewAttributes() ?>>
<?= $Page->articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
        <!-- lote -->
        <td<?= $Page->lote->cellAttributes() ?>>
<span<?= $Page->lote->viewAttributes() ?>>
<?= $Page->lote->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <!-- fecha_vencimiento -->
        <td<?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span<?= $Page->fecha_vencimiento->viewAttributes() ?>>
<?= $Page->fecha_vencimiento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
        <!-- almacen -->
        <td<?= $Page->almacen->cellAttributes() ?>>
<span<?= $Page->almacen->viewAttributes() ?>>
<?= $Page->almacen->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <!-- cantidad_articulo -->
        <td<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->precio_unidad->Visible) { // precio_unidad ?>
        <!-- precio_unidad -->
        <td<?= $Page->precio_unidad->cellAttributes() ?>>
<span<?= $Page->precio_unidad->viewAttributes() ?>>
<?= $Page->precio_unidad->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->precio->Visible) { // precio ?>
        <!-- precio -->
        <td<?= $Page->precio->cellAttributes() ?>>
<span<?= $Page->precio->viewAttributes() ?>>
<?= $Page->precio->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
        <!-- alicuota -->
        <td<?= $Page->alicuota->cellAttributes() ?>>
<span<?= $Page->alicuota->viewAttributes() ?>>
<?= $Page->alicuota->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
        <!-- descuento -->
        <td<?= $Page->descuento->cellAttributes() ?>>
<span<?= $Page->descuento->viewAttributes() ?>>
<?= $Page->descuento->getViewValue() ?></span>
</td>
<?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
} // while
?>
    </tbody>
</table><!-- /.table -->
</div><!-- /.card-body -->
<div class="card-footer ew-grid-lower-panel ew-preview-lower-panel"><!-- .card-footer -->
<?= $Page->Pager->render() ?>
<?php if ($Page->OtherOptions->visible()) { ?>
<div class="ew-preview-other-options">
<?php
    foreach ($Page->OtherOptions as $option) {
        $option->render("body");
    }
?>
</div>
<?php } ?>
</div><!-- /.card-footer -->
</div><!-- /.card -->
<?php } else { // No record ?>
<div class="card border-0"><!-- .card -->
<div class="ew-detail-count"><?= $Language->phrase("NoRecord") ?></div>
<?php if ($Page->OtherOptions->visible()) { ?>
<div class="ew-preview-other-options">
<?php
    foreach ($Page->OtherOptions as $option) {
        $option->render("body");
    }
?>
</div>
<?php } ?>
</div><!-- /.card -->
<?php } ?>
<?php
foreach ($Page->DetailCounts as $detailTblVar => $detailCount) {
?>
<div class="ew-detail-count d-none" data-table="<?= $detailTblVar ?>" data-count="<?= $detailCount ?>"><?= FormatInteger($detailCount) ?></div>
<?php
}
?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php
$Page->Recordset?->free();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
