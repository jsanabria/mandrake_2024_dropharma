<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContAsientoDetalleMdkPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { cont_asiento_detalle_mdk: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->id->Visible) { // id ?>
    <?php if (!$Page->id->Sortable || !$Page->sortUrl($Page->id)) { ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><?= $Page->id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->id->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->id->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->id->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->id->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->asiento_id->Visible) { // asiento_id ?>
    <?php if (!$Page->asiento_id->Sortable || !$Page->sortUrl($Page->asiento_id)) { ?>
        <th class="<?= $Page->asiento_id->headerCellClass() ?>"><?= $Page->asiento_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->asiento_id->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->asiento_id->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->asiento_id->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->asiento_id->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->asiento_id->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
    <?php if (!$Page->cuenta_id->Sortable || !$Page->sortUrl($Page->cuenta_id)) { ?>
        <th class="<?= $Page->cuenta_id->headerCellClass() ?>"><?= $Page->cuenta_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cuenta_id->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->cuenta_id->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cuenta_id->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cuenta_id->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cuenta_id->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->centro_costo_id->Visible) { // centro_costo_id ?>
    <?php if (!$Page->centro_costo_id->Sortable || !$Page->sortUrl($Page->centro_costo_id)) { ?>
        <th class="<?= $Page->centro_costo_id->headerCellClass() ?>"><?= $Page->centro_costo_id->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->centro_costo_id->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->centro_costo_id->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->centro_costo_id->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->centro_costo_id->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->centro_costo_id->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->concepto->Visible) { // concepto ?>
    <?php if (!$Page->concepto->Sortable || !$Page->sortUrl($Page->concepto)) { ?>
        <th class="<?= $Page->concepto->headerCellClass() ?>"><?= $Page->concepto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->concepto->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->concepto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->concepto->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->concepto->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->concepto->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->moneda_trx->Visible) { // moneda_trx ?>
    <?php if (!$Page->moneda_trx->Sortable || !$Page->sortUrl($Page->moneda_trx)) { ?>
        <th class="<?= $Page->moneda_trx->headerCellClass() ?>"><?= $Page->moneda_trx->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->moneda_trx->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->moneda_trx->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->moneda_trx->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->moneda_trx->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->moneda_trx->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tasa_trx->Visible) { // tasa_trx ?>
    <?php if (!$Page->tasa_trx->Sortable || !$Page->sortUrl($Page->tasa_trx)) { ?>
        <th class="<?= $Page->tasa_trx->headerCellClass() ?>"><?= $Page->tasa_trx->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tasa_trx->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->tasa_trx->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tasa_trx->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tasa_trx->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tasa_trx->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
    <?php if (!$Page->monto_moneda_trx->Sortable || !$Page->sortUrl($Page->monto_moneda_trx)) { ?>
        <th class="<?= $Page->monto_moneda_trx->headerCellClass() ?>"><?= $Page->monto_moneda_trx->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_moneda_trx->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->monto_moneda_trx->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_moneda_trx->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_moneda_trx->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_moneda_trx->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->debe_bs->Visible) { // debe_bs ?>
    <?php if (!$Page->debe_bs->Sortable || !$Page->sortUrl($Page->debe_bs)) { ?>
        <th class="<?= $Page->debe_bs->headerCellClass() ?>"><?= $Page->debe_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->debe_bs->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->debe_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->debe_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->debe_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->debe_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->haber_bs->Visible) { // haber_bs ?>
    <?php if (!$Page->haber_bs->Sortable || !$Page->sortUrl($Page->haber_bs)) { ?>
        <th class="<?= $Page->haber_bs->headerCellClass() ?>"><?= $Page->haber_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->haber_bs->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->haber_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->haber_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->haber_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->haber_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <?php if (!$Page->created_at->Sortable || !$Page->sortUrl($Page->created_at)) { ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><?= $Page->created_at->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><div role="button" data-table="cont_asiento_detalle_mdk" data-sort="<?= HtmlEncode($Page->created_at->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->created_at->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->created_at->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->created_at->getSortIcon() ?></span>
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
    $Page->aggregateListRowValues(); // Aggregate row values

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
<?php if ($Page->id->Visible) { // id ?>
        <!-- id -->
        <td<?= $Page->id->cellAttributes() ?>>
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->asiento_id->Visible) { // asiento_id ?>
        <!-- asiento_id -->
        <td<?= $Page->asiento_id->cellAttributes() ?>>
<span<?= $Page->asiento_id->viewAttributes() ?>>
<?= $Page->asiento_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
        <!-- cuenta_id -->
        <td<?= $Page->cuenta_id->cellAttributes() ?>>
<span<?= $Page->cuenta_id->viewAttributes() ?>>
<?= $Page->cuenta_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->centro_costo_id->Visible) { // centro_costo_id ?>
        <!-- centro_costo_id -->
        <td<?= $Page->centro_costo_id->cellAttributes() ?>>
<span<?= $Page->centro_costo_id->viewAttributes() ?>>
<?= $Page->centro_costo_id->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->concepto->Visible) { // concepto ?>
        <!-- concepto -->
        <td<?= $Page->concepto->cellAttributes() ?>>
<span<?= $Page->concepto->viewAttributes() ?>>
<?= $Page->concepto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->moneda_trx->Visible) { // moneda_trx ?>
        <!-- moneda_trx -->
        <td<?= $Page->moneda_trx->cellAttributes() ?>>
<span<?= $Page->moneda_trx->viewAttributes() ?>>
<?= $Page->moneda_trx->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tasa_trx->Visible) { // tasa_trx ?>
        <!-- tasa_trx -->
        <td<?= $Page->tasa_trx->cellAttributes() ?>>
<span<?= $Page->tasa_trx->viewAttributes() ?>>
<?= $Page->tasa_trx->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
        <!-- monto_moneda_trx -->
        <td<?= $Page->monto_moneda_trx->cellAttributes() ?>>
<span<?= $Page->monto_moneda_trx->viewAttributes() ?>>
<?= $Page->monto_moneda_trx->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->debe_bs->Visible) { // debe_bs ?>
        <!-- debe_bs -->
        <td<?= $Page->debe_bs->cellAttributes() ?>>
<span<?= $Page->debe_bs->viewAttributes() ?>>
<?= $Page->debe_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->haber_bs->Visible) { // haber_bs ?>
        <!-- haber_bs -->
        <td<?= $Page->haber_bs->cellAttributes() ?>>
<span<?= $Page->haber_bs->viewAttributes() ?>>
<?= $Page->haber_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <!-- created_at -->
        <td<?= $Page->created_at->cellAttributes() ?>>
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
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
<?php
    // Render aggregate row
    $Page->RowType = RowType::AGGREGATE; // Aggregate
    $Page->aggregateListRow(); // Prepare aggregate row

    // Render list options
    $Page->renderListOptions();
?>
    <tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options (footer, left)
$Page->ListOptions->render("footer", "left");
?>
<?php if ($Page->id->Visible) { // id ?>
        <!-- id -->
        <td class="<?= $Page->id->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->asiento_id->Visible) { // asiento_id ?>
        <!-- asiento_id -->
        <td class="<?= $Page->asiento_id->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->cuenta_id->Visible) { // cuenta_id ?>
        <!-- cuenta_id -->
        <td class="<?= $Page->cuenta_id->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->centro_costo_id->Visible) { // centro_costo_id ?>
        <!-- centro_costo_id -->
        <td class="<?= $Page->centro_costo_id->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->concepto->Visible) { // concepto ?>
        <!-- concepto -->
        <td class="<?= $Page->concepto->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->moneda_trx->Visible) { // moneda_trx ?>
        <!-- moneda_trx -->
        <td class="<?= $Page->moneda_trx->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->tasa_trx->Visible) { // tasa_trx ?>
        <!-- tasa_trx -->
        <td class="<?= $Page->tasa_trx->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->monto_moneda_trx->Visible) { // monto_moneda_trx ?>
        <!-- monto_moneda_trx -->
        <td class="<?= $Page->monto_moneda_trx->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php if ($Page->debe_bs->Visible) { // debe_bs ?>
        <!-- debe_bs -->
        <td class="<?= $Page->debe_bs->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->debe_bs->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->haber_bs->Visible) { // haber_bs ?>
        <!-- haber_bs -->
        <td class="<?= $Page->haber_bs->footerCellClass() ?>">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->haber_bs->ViewValue ?></span>
        </td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <!-- created_at -->
        <td class="<?= $Page->created_at->footerCellClass() ?>">
        &nbsp;
        </td>
<?php } ?>
<?php
// Render list options (footer, right)
$Page->ListOptions->render("footer", "right");
?>
    </tr>
    </tfoot>
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
