<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewCuentasPorPagarPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_pagar: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <?php if (!$Page->proveedor->Sortable || !$Page->sortUrl($Page->proveedor)) { ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><?= $Page->proveedor->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->proveedor->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->proveedor->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->proveedor->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->proveedor->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <?php if (!$Page->tipo_documento->Sortable || !$Page->sortUrl($Page->tipo_documento)) { ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><?= $Page->tipo_documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo_documento->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->tipo_documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo_documento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tipo_documento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tipo_documento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
    <?php if (!$Page->documento->Sortable || !$Page->sortUrl($Page->documento)) { ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><?= $Page->documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->documento->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->documento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->documento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->documento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <?php if (!$Page->fecha->Sortable || !$Page->sortUrl($Page->fecha)) { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><?= $Page->fecha->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->fecha->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fecha->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fecha->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
    <?php if (!$Page->fecha_ultimo_pago->Sortable || !$Page->sortUrl($Page->fecha_ultimo_pago)) { ?>
        <th class="<?= $Page->fecha_ultimo_pago->headerCellClass() ?>"><?= $Page->fecha_ultimo_pago->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha_ultimo_pago->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->fecha_ultimo_pago->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha_ultimo_pago->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fecha_ultimo_pago->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fecha_ultimo_pago->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
    <?php if (!$Page->monto_documento_bs->Sortable || !$Page->sortUrl($Page->monto_documento_bs)) { ?>
        <th class="<?= $Page->monto_documento_bs->headerCellClass() ?>"><?= $Page->monto_documento_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_documento_bs->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->monto_documento_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_documento_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_documento_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_documento_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
    <?php if (!$Page->total_pagado_bs->Sortable || !$Page->sortUrl($Page->total_pagado_bs)) { ?>
        <th class="<?= $Page->total_pagado_bs->headerCellClass() ?>"><?= $Page->total_pagado_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->total_pagado_bs->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->total_pagado_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->total_pagado_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->total_pagado_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->total_pagado_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
    <?php if (!$Page->saldo_bs->Sortable || !$Page->sortUrl($Page->saldo_bs)) { ?>
        <th class="<?= $Page->saldo_bs->headerCellClass() ?>"><?= $Page->saldo_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->saldo_bs->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->saldo_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->saldo_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->saldo_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->saldo_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->antiguedad->Visible) { // antiguedad ?>
    <?php if (!$Page->antiguedad->Sortable || !$Page->sortUrl($Page->antiguedad)) { ?>
        <th class="<?= $Page->antiguedad->headerCellClass() ?>"><?= $Page->antiguedad->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->antiguedad->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_pagar" data-sort="<?= HtmlEncode($Page->antiguedad->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->antiguedad->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->antiguedad->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->antiguedad->getSortIcon() ?></span>
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <!-- proveedor -->
        <td<?= $Page->proveedor->cellAttributes() ?>>
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
        <!-- tipo_documento -->
        <td<?= $Page->tipo_documento->cellAttributes() ?>>
<span<?= $Page->tipo_documento->viewAttributes() ?>>
<?= $Page->tipo_documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->documento->Visible) { // documento ?>
        <!-- documento -->
        <td<?= $Page->documento->cellAttributes() ?>>
<span<?= $Page->documento->viewAttributes() ?>>
<?= $Page->documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <!-- fecha -->
        <td<?= $Page->fecha->cellAttributes() ?>>
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha_ultimo_pago->Visible) { // fecha_ultimo_pago ?>
        <!-- fecha_ultimo_pago -->
        <td<?= $Page->fecha_ultimo_pago->cellAttributes() ?>>
<span<?= $Page->fecha_ultimo_pago->viewAttributes() ?>>
<?= $Page->fecha_ultimo_pago->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <!-- monto_documento_bs -->
        <td<?= $Page->monto_documento_bs->cellAttributes() ?>>
<span<?= $Page->monto_documento_bs->viewAttributes() ?>>
<?= $Page->monto_documento_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <!-- total_pagado_bs -->
        <td<?= $Page->total_pagado_bs->cellAttributes() ?>>
<span<?= $Page->total_pagado_bs->viewAttributes() ?>>
<?= $Page->total_pagado_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
        <!-- saldo_bs -->
        <td<?= $Page->saldo_bs->cellAttributes() ?>>
<span<?= $Page->saldo_bs->viewAttributes() ?>>
<?= $Page->saldo_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->antiguedad->Visible) { // antiguedad ?>
        <!-- antiguedad -->
        <td<?= $Page->antiguedad->cellAttributes() ?>>
<span<?= $Page->antiguedad->viewAttributes() ?>>
<?= $Page->antiguedad->getViewValue() ?></span>
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
