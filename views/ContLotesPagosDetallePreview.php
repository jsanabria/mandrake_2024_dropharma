<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContLotesPagosDetallePreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { cont_lotes_pagos_detalle: <?= JsonEncode($Page->toClientVar()) ?> } });
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
        <th class="<?= $Page->proveedor->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->proveedor->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->proveedor->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->proveedor->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->proveedor->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
    <?php if (!$Page->tipodoc->Sortable || !$Page->sortUrl($Page->tipodoc)) { ?>
        <th class="<?= $Page->tipodoc->headerCellClass() ?>"><?= $Page->tipodoc->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipodoc->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->tipodoc->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipodoc->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tipodoc->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tipodoc->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <?php if (!$Page->nro_documento->Sortable || !$Page->sortUrl($Page->nro_documento)) { ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><?= $Page->nro_documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->nro_documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->nro_documento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->nro_documento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->nro_documento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
    <?php if (!$Page->monto_a_pagar->Sortable || !$Page->sortUrl($Page->monto_a_pagar)) { ?>
        <th class="<?= $Page->monto_a_pagar->headerCellClass() ?>"><?= $Page->monto_a_pagar->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_a_pagar->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->monto_a_pagar->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_a_pagar->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_a_pagar->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_a_pagar->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_pagdo->Visible) { // monto_pagdo ?>
    <?php if (!$Page->monto_pagdo->Sortable || !$Page->sortUrl($Page->monto_pagdo)) { ?>
        <th class="<?= $Page->monto_pagdo->headerCellClass() ?>"><?= $Page->monto_pagdo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_pagdo->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->monto_pagdo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_pagdo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_pagdo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_pagdo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
    <?php if (!$Page->saldo->Sortable || !$Page->sortUrl($Page->saldo)) { ?>
        <th class="<?= $Page->saldo->headerCellClass() ?>"><?= $Page->saldo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->saldo->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->saldo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->saldo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->saldo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->saldo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
    <?php if (!$Page->comprobante->Sortable || !$Page->sortUrl($Page->comprobante)) { ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><?= $Page->comprobante->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->comprobante->headerCellClass() ?>"><div role="button" data-table="cont_lotes_pagos_detalle" data-sort="<?= HtmlEncode($Page->comprobante->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->comprobante->getNextSort() ?>">
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
<?php if ($Page->proveedor->Visible) { // proveedor ?>
        <!-- proveedor -->
        <td<?= $Page->proveedor->cellAttributes() ?>>
<span<?= $Page->proveedor->viewAttributes() ?>>
<?= $Page->proveedor->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tipodoc->Visible) { // tipodoc ?>
        <!-- tipodoc -->
        <td<?= $Page->tipodoc->cellAttributes() ?>>
<span<?= $Page->tipodoc->viewAttributes() ?>>
<?= $Page->tipodoc->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <!-- nro_documento -->
        <td<?= $Page->nro_documento->cellAttributes() ?>>
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_a_pagar->Visible) { // monto_a_pagar ?>
        <!-- monto_a_pagar -->
        <td<?= $Page->monto_a_pagar->cellAttributes() ?>>
<span<?= $Page->monto_a_pagar->viewAttributes() ?>>
<?= $Page->monto_a_pagar->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_pagdo->Visible) { // monto_pagdo ?>
        <!-- monto_pagdo -->
        <td<?= $Page->monto_pagdo->cellAttributes() ?>>
<span<?= $Page->monto_pagdo->viewAttributes() ?>>
<?= $Page->monto_pagdo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->saldo->Visible) { // saldo ?>
        <!-- saldo -->
        <td<?= $Page->saldo->cellAttributes() ?>>
<span<?= $Page->saldo->viewAttributes() ?>>
<?= $Page->saldo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->comprobante->Visible) { // comprobante ?>
        <!-- comprobante -->
        <td<?= $Page->comprobante->cellAttributes() ?>>
<span<?= $Page->comprobante->viewAttributes() ?>>
<?= $Page->comprobante->getViewValue() ?></span>
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
