<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteFacturaPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { cobros_cliente_factura: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <?php if (!$Page->tipo_documento->Sortable || !$Page->sortUrl($Page->tipo_documento)) { ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><?= $Page->tipo_documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->tipo_documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo_documento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tipo_documento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tipo_documento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
    <?php if (!$Page->abono->Sortable || !$Page->sortUrl($Page->abono)) { ?>
        <th class="<?= $Page->abono->headerCellClass() ?>"><?= $Page->abono->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->abono->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->abono->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->abono->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->abono->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->abono->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <?php if (!$Page->monto->Sortable || !$Page->sortUrl($Page->monto)) { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><?= $Page->monto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->monto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
    <?php if (!$Page->retivamonto->Sortable || !$Page->sortUrl($Page->retivamonto)) { ?>
        <th class="<?= $Page->retivamonto->headerCellClass() ?>"><?= $Page->retivamonto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retivamonto->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->retivamonto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retivamonto->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->retivamonto->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->retivamonto->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
    <?php if (!$Page->retiva->Sortable || !$Page->sortUrl($Page->retiva)) { ?>
        <th class="<?= $Page->retiva->headerCellClass() ?>"><?= $Page->retiva->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retiva->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->retiva->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retiva->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->retiva->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->retiva->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
    <?php if (!$Page->retislrmonto->Sortable || !$Page->sortUrl($Page->retislrmonto)) { ?>
        <th class="<?= $Page->retislrmonto->headerCellClass() ?>"><?= $Page->retislrmonto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retislrmonto->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->retislrmonto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retislrmonto->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->retislrmonto->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->retislrmonto->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
    <?php if (!$Page->retislr->Sortable || !$Page->sortUrl($Page->retislr)) { ?>
        <th class="<?= $Page->retislr->headerCellClass() ?>"><?= $Page->retislr->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->retislr->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->retislr->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->retislr->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->retislr->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->retislr->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <?php if (!$Page->comprobante->Sortable || !$Page->sortUrl($Page->comprobante)) { ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><?= $Page->comprobante->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><div role="button" data-table="cobros_cliente_factura" data-sort="<?= HtmlEncode($Page->comprobante->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->comprobante->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->comprobante->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->comprobante->getSortIcon() ?></span>
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
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <!-- tipo_documento -->
        <td<?= $Page->tipo_documento->cellAttributes() ?>>
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->abono->Visible) { // abono ?>
        <!-- abono -->
        <td<?= $Page->abono->cellAttributes() ?>>
<span<?= $Page->abono->viewAttributes() ?>>
<?= $Page->abono->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <!-- monto -->
        <td<?= $Page->monto->cellAttributes() ?>>
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retivamonto->Visible) { // retivamonto ?>
        <!-- retivamonto -->
        <td<?= $Page->retivamonto->cellAttributes() ?>>
<span<?= $Page->retivamonto->viewAttributes() ?>>
<?= $Page->retivamonto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retiva->Visible) { // retiva ?>
        <!-- retiva -->
        <td<?= $Page->retiva->cellAttributes() ?>>
<span<?= $Page->retiva->viewAttributes() ?>>
<?= $Page->retiva->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retislrmonto->Visible) { // retislrmonto ?>
        <!-- retislrmonto -->
        <td<?= $Page->retislrmonto->cellAttributes() ?>>
<span<?= $Page->retislrmonto->viewAttributes() ?>>
<?= $Page->retislrmonto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->retislr->Visible) { // retislr ?>
        <!-- retislr -->
        <td<?= $Page->retislr->cellAttributes() ?>>
<span<?= $Page->retislr->viewAttributes() ?>>
<?= $Page->retislr->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <!-- comprobante -->
        <td<?= $Page->comprobante->cellAttributes() ?>>
<span<?= $Page->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($Page->comprobante->getViewValue()) && $Page->comprobante->linkAttributes() != "") { ?>
<a<?= $Page->comprobante->linkAttributes() ?>><?= $Page->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->comprobante->getViewValue() ?>
<?php } ?>
</span>
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
