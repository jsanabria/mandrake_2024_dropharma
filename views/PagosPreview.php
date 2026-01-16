<?php

namespace PHPMaker2024\mandrake;

// Page object
$PagosPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { pagos: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
    <?php if (!$Page->tipo_pago->Sortable || !$Page->sortUrl($Page->tipo_pago)) { ?>
        <th class="<?= $Page->tipo_pago->headerCellClass() ?>"><?= $Page->tipo_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo_pago->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->tipo_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo_pago->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tipo_pago->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tipo_pago->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <?php if (!$Page->fecha->Sortable || !$Page->sortUrl($Page->fecha)) { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><?= $Page->fecha->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->fecha->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fecha->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fecha->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
    <?php if (!$Page->banco->Sortable || !$Page->sortUrl($Page->banco)) { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><?= $Page->banco->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->banco->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->banco->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->banco->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->banco->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->banco_destino->Visible) { // banco_destino ?>
    <?php if (!$Page->banco_destino->Sortable || !$Page->sortUrl($Page->banco_destino)) { ?>
        <th class="<?= $Page->banco_destino->headerCellClass() ?>"><?= $Page->banco_destino->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->banco_destino->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->banco_destino->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->banco_destino->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->banco_destino->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->banco_destino->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
    <?php if (!$Page->referencia->Sortable || !$Page->sortUrl($Page->referencia)) { ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><?= $Page->referencia->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->referencia->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->referencia->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->referencia->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->referencia->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->referencia->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <?php if (!$Page->moneda->Sortable || !$Page->sortUrl($Page->moneda)) { ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><?= $Page->moneda->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->moneda->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->moneda->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->moneda->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->moneda->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->moneda->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
    <?php if (!$Page->monto->Sortable || !$Page->sortUrl($Page->monto)) { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><?= $Page->monto->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->monto->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
    <?php if (!$Page->comprobante_pago->Sortable || !$Page->sortUrl($Page->comprobante_pago)) { ?>
        <th class="<?= $Page->comprobante_pago->headerCellClass() ?>"><?= $Page->comprobante_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->comprobante_pago->headerCellClass() ?>"><div role="button" data-table="pagos" data-sort="<?= HtmlEncode($Page->comprobante_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->comprobante_pago->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->comprobante_pago->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->comprobante_pago->getSortIcon() ?></span>
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
<?php if ($Page->tipo_pago->Visible) { // tipo_pago ?>
        <!-- tipo_pago -->
        <td<?= $Page->tipo_pago->cellAttributes() ?>>
<span<?= $Page->tipo_pago->viewAttributes() ?>>
<?= $Page->tipo_pago->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <!-- fecha -->
        <td<?= $Page->fecha->cellAttributes() ?>>
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->banco->Visible) { // banco ?>
        <!-- banco -->
        <td<?= $Page->banco->cellAttributes() ?>>
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->banco_destino->Visible) { // banco_destino ?>
        <!-- banco_destino -->
        <td<?= $Page->banco_destino->cellAttributes() ?>>
<span<?= $Page->banco_destino->viewAttributes() ?>>
<?= $Page->banco_destino->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->referencia->Visible) { // referencia ?>
        <!-- referencia -->
        <td<?= $Page->referencia->cellAttributes() ?>>
<span<?= $Page->referencia->viewAttributes() ?>>
<?= $Page->referencia->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
        <!-- moneda -->
        <td<?= $Page->moneda->cellAttributes() ?>>
<span<?= $Page->moneda->viewAttributes() ?>>
<?= $Page->moneda->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto->Visible) { // monto ?>
        <!-- monto -->
        <td<?= $Page->monto->cellAttributes() ?>>
<span<?= $Page->monto->viewAttributes() ?>>
<?= $Page->monto->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->comprobante_pago->Visible) { // comprobante_pago ?>
        <!-- comprobante_pago -->
        <td<?= $Page->comprobante_pago->cellAttributes() ?>>
<span>
<?= GetFileViewTag($Page->comprobante_pago, $Page->comprobante_pago->getViewValue(), false) ?>
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
