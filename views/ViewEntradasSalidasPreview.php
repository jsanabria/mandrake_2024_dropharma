<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewEntradasSalidasPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { view_entradas_salidas: <?= JsonEncode($Page->toClientVar()) ?> } });
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
        <th class="<?= $Page->fabricante->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->fabricante->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fabricante->getNextSort() ?>">
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
        <th class="<?= $Page->articulo->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->articulo->getNextSort() ?>">
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
        <th class="<?= $Page->lote->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->lote->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->lote->getNextSort() ?>">
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
        <th class="<?= $Page->fecha_vencimiento->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->fecha_vencimiento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha_vencimiento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fecha_vencimiento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fecha_vencimiento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
    <?php if (!$Page->cantidad_articulo->Sortable || !$Page->sortUrl($Page->cantidad_articulo)) { ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><?= $Page->cantidad_articulo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cantidad_articulo->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->cantidad_articulo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cantidad_articulo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cantidad_articulo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cantidad_articulo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
    <?php if (!$Page->articulo_unidad_medida->Sortable || !$Page->sortUrl($Page->articulo_unidad_medida)) { ?>
        <th class="<?= $Page->articulo_unidad_medida->headerCellClass() ?>"><?= $Page->articulo_unidad_medida->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->articulo_unidad_medida->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->articulo_unidad_medida->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->articulo_unidad_medida->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->articulo_unidad_medida->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->articulo_unidad_medida->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->packer_cantidad->Visible) { // packer_cantidad ?>
    <?php if (!$Page->packer_cantidad->Sortable || !$Page->sortUrl($Page->packer_cantidad)) { ?>
        <th class="<?= $Page->packer_cantidad->headerCellClass() ?>"><?= $Page->packer_cantidad->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->packer_cantidad->headerCellClass() ?>"><div role="button" data-table="view_entradas_salidas" data-sort="<?= HtmlEncode($Page->packer_cantidad->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->packer_cantidad->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->packer_cantidad->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->packer_cantidad->getSortIcon() ?></span>
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
<?php if ($Page->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <!-- cantidad_articulo -->
        <td<?= $Page->cantidad_articulo->cellAttributes() ?>>
<span<?= $Page->cantidad_articulo->viewAttributes() ?>>
<?= $Page->cantidad_articulo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->articulo_unidad_medida->Visible) { // articulo_unidad_medida ?>
        <!-- articulo_unidad_medida -->
        <td<?= $Page->articulo_unidad_medida->cellAttributes() ?>>
<span<?= $Page->articulo_unidad_medida->viewAttributes() ?>>
<?= $Page->articulo_unidad_medida->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->packer_cantidad->Visible) { // packer_cantidad ?>
        <!-- packer_cantidad -->
        <td<?= $Page->packer_cantidad->cellAttributes() ?>>
<span<?= $Page->packer_cantidad->viewAttributes() ?>>
<?= $Page->packer_cantidad->getViewValue() ?></span>
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
