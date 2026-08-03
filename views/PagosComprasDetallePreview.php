<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagosComprasDetallePreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { pagos_compras_detalle: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
    <?php if (!$Page->metodo_pago->Sortable || !$Page->sortUrl($Page->metodo_pago)) { ?>
        <th class="<?= $Page->metodo_pago->headerCellClass() ?>"><?= $Page->metodo_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->metodo_pago->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->metodo_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->metodo_pago->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->metodo_pago->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->metodo_pago->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <?php if (!$Page->referencia->Sortable || !$Page->sortUrl($Page->referencia)) { ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><?= $Page->referencia->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->referencia->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->referencia->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->referencia->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->referencia->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
    <?php if (!$Page->monto_moneda->Sortable || !$Page->sortUrl($Page->monto_moneda)) { ?>
        <th class="<?= $Page->monto_moneda->headerCellClass() ?>"><?= $Page->monto_moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_moneda->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->monto_moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_moneda->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_moneda->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_moneda->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <?php if (!$Page->moneda->Sortable || !$Page->sortUrl($Page->moneda)) { ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><?= $Page->moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->moneda->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->moneda->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->moneda->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
    <?php if (!$Page->tasa_moneda->Sortable || !$Page->sortUrl($Page->tasa_moneda)) { ?>
        <th class="<?= $Page->tasa_moneda->headerCellClass() ?>"><?= $Page->tasa_moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tasa_moneda->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->tasa_moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tasa_moneda->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tasa_moneda->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tasa_moneda->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
    <?php if (!$Page->monto_bs->Sortable || !$Page->sortUrl($Page->monto_bs)) { ?>
        <th class="<?= $Page->monto_bs->headerCellClass() ?>"><?= $Page->monto_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_bs->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->monto_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
    <?php if (!$Page->tasa_usd->Sortable || !$Page->sortUrl($Page->tasa_usd)) { ?>
        <th class="<?= $Page->tasa_usd->headerCellClass() ?>"><?= $Page->tasa_usd->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tasa_usd->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->tasa_usd->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tasa_usd->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tasa_usd->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tasa_usd->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
    <?php if (!$Page->monto_usd->Sortable || !$Page->sortUrl($Page->monto_usd)) { ?>
        <th class="<?= $Page->monto_usd->headerCellClass() ?>"><?= $Page->monto_usd->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_usd->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->monto_usd->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_usd->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_usd->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_usd->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <?php if (!$Page->banco->Sortable || !$Page->sortUrl($Page->banco)) { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><?= $Page->banco->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><div role="button" data-table="pagos_compras_detalle" data-sort="<?= HtmlEncode($Page->banco->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->banco->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->banco->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->banco->getSortIcon() ?></span>
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
<?php if ($Page->metodo_pago->Visible) { // metodo_pago ?>
        <!-- metodo_pago -->
        <td<?= $Page->metodo_pago->cellAttributes() ?>>
<span<?= $Page->metodo_pago->viewAttributes() ?>>
<?= $Page->metodo_pago->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <!-- referencia -->
        <td<?= $Page->referencia->cellAttributes() ?>>
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_moneda->Visible) { // monto_moneda ?>
        <!-- monto_moneda -->
        <td<?= $Page->monto_moneda->cellAttributes() ?>>
<span<?= $Page->monto_moneda->viewAttributes() ?>>
<?= $Page->monto_moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <!-- moneda -->
        <td<?= $Page->moneda->cellAttributes() ?>>
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tasa_moneda->Visible) { // tasa_moneda ?>
        <!-- tasa_moneda -->
        <td<?= $Page->tasa_moneda->cellAttributes() ?>>
<span<?= $Page->tasa_moneda->viewAttributes() ?>>
<?= $Page->tasa_moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_bs->Visible) { // monto_bs ?>
        <!-- monto_bs -->
        <td<?= $Page->monto_bs->cellAttributes() ?>>
<span<?= $Page->monto_bs->viewAttributes() ?>>
<?= $Page->monto_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tasa_usd->Visible) { // tasa_usd ?>
        <!-- tasa_usd -->
        <td<?= $Page->tasa_usd->cellAttributes() ?>>
<span<?= $Page->tasa_usd->viewAttributes() ?>>
<?= $Page->tasa_usd->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_usd->Visible) { // monto_usd ?>
        <!-- monto_usd -->
        <td<?= $Page->monto_usd->cellAttributes() ?>>
<span<?= $Page->monto_usd->viewAttributes() ?>>
<?= $Page->monto_usd->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <!-- banco -->
        <td<?= $Page->banco->cellAttributes() ?>>
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
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
