<?php

namespace PHPMaker2024\mandrake;

// Page object
$ViewCuentasPorCobrarPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { view_cuentas_por_cobrar: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->cliente->Visible) { // cliente ?>
    <?php if (!$Page->cliente->Sortable || !$Page->sortUrl($Page->cliente)) { ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><?= $Page->cliente->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cliente->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->cliente->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cliente->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cliente->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cliente->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cliente_rif->Visible) { // cliente_rif ?>
    <?php if (!$Page->cliente_rif->Sortable || !$Page->sortUrl($Page->cliente_rif)) { ?>
        <th class="<?= $Page->cliente_rif->headerCellClass() ?>"><?= $Page->cliente_rif->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cliente_rif->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->cliente_rif->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cliente_rif->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cliente_rif->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cliente_rif->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cliente_nombre->Visible) { // cliente_nombre ?>
    <?php if (!$Page->cliente_nombre->Sortable || !$Page->sortUrl($Page->cliente_nombre)) { ?>
        <th class="<?= $Page->cliente_nombre->headerCellClass() ?>"><?= $Page->cliente_nombre->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cliente_nombre->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->cliente_nombre->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cliente_nombre->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cliente_nombre->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cliente_nombre->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tipo_documento_fiscal->Visible) { // tipo_documento_fiscal ?>
    <?php if (!$Page->tipo_documento_fiscal->Sortable || !$Page->sortUrl($Page->tipo_documento_fiscal)) { ?>
        <th class="<?= $Page->tipo_documento_fiscal->headerCellClass() ?>"><?= $Page->tipo_documento_fiscal->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo_documento_fiscal->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->tipo_documento_fiscal->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo_documento_fiscal->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tipo_documento_fiscal->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tipo_documento_fiscal->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <?php if (!$Page->nro_documento->Sortable || !$Page->sortUrl($Page->nro_documento)) { ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><?= $Page->nro_documento->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->nro_documento->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->nro_documento->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->nro_documento->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->nro_documento->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->nro_documento->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <?php if (!$Page->fecha->Sortable || !$Page->sortUrl($Page->fecha)) { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><?= $Page->fecha->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->fecha->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->fecha->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->fecha->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->fecha->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->fecha->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
    <?php if (!$Page->monto_documento_bs->Sortable || !$Page->sortUrl($Page->monto_documento_bs)) { ?>
        <th class="<?= $Page->monto_documento_bs->headerCellClass() ?>"><?= $Page->monto_documento_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->monto_documento_bs->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->monto_documento_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->monto_documento_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->monto_documento_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->monto_documento_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->total_cobrado_bs->Visible) { // total_cobrado_bs ?>
    <?php if (!$Page->total_cobrado_bs->Sortable || !$Page->sortUrl($Page->total_cobrado_bs)) { ?>
        <th class="<?= $Page->total_cobrado_bs->headerCellClass() ?>"><?= $Page->total_cobrado_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->total_cobrado_bs->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->total_cobrado_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->total_cobrado_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->total_cobrado_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->total_cobrado_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
    <?php if (!$Page->saldo_bs->Sortable || !$Page->sortUrl($Page->saldo_bs)) { ?>
        <th class="<?= $Page->saldo_bs->headerCellClass() ?>"><?= $Page->saldo_bs->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->saldo_bs->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->saldo_bs->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->saldo_bs->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->saldo_bs->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->saldo_bs->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->dias_vencido->Visible) { // dias_vencido ?>
    <?php if (!$Page->dias_vencido->Sortable || !$Page->sortUrl($Page->dias_vencido)) { ?>
        <th class="<?= $Page->dias_vencido->headerCellClass() ?>"><?= $Page->dias_vencido->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->dias_vencido->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->dias_vencido->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->dias_vencido->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->dias_vencido->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->dias_vencido->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->antiguedad->Visible) { // antiguedad ?>
    <?php if (!$Page->antiguedad->Sortable || !$Page->sortUrl($Page->antiguedad)) { ?>
        <th class="<?= $Page->antiguedad->headerCellClass() ?>"><?= $Page->antiguedad->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->antiguedad->headerCellClass() ?>"><div role="button" data-table="view_cuentas_por_cobrar" data-sort="<?= HtmlEncode($Page->antiguedad->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->antiguedad->getNextSort() ?>">
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
<?php if ($Page->cliente->Visible) { // cliente ?>
        <!-- cliente -->
        <td<?= $Page->cliente->cellAttributes() ?>>
<span<?= $Page->cliente->viewAttributes() ?>>
<?= $Page->cliente->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cliente_rif->Visible) { // cliente_rif ?>
        <!-- cliente_rif -->
        <td<?= $Page->cliente_rif->cellAttributes() ?>>
<span<?= $Page->cliente_rif->viewAttributes() ?>>
<?= $Page->cliente_rif->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cliente_nombre->Visible) { // cliente_nombre ?>
        <!-- cliente_nombre -->
        <td<?= $Page->cliente_nombre->cellAttributes() ?>>
<span<?= $Page->cliente_nombre->viewAttributes() ?>>
<?= $Page->cliente_nombre->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tipo_documento_fiscal->Visible) { // tipo_documento_fiscal ?>
        <!-- tipo_documento_fiscal -->
        <td<?= $Page->tipo_documento_fiscal->cellAttributes() ?>>
<span<?= $Page->tipo_documento_fiscal->viewAttributes() ?>>
<?= $Page->tipo_documento_fiscal->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
        <!-- nro_documento -->
        <td<?= $Page->nro_documento->cellAttributes() ?>>
<span<?= $Page->nro_documento->viewAttributes() ?>>
<?= $Page->nro_documento->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
        <!-- fecha -->
        <td<?= $Page->fecha->cellAttributes() ?>>
<span<?= $Page->fecha->viewAttributes() ?>>
<?= $Page->fecha->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->monto_documento_bs->Visible) { // monto_documento_bs ?>
        <!-- monto_documento_bs -->
        <td<?= $Page->monto_documento_bs->cellAttributes() ?>>
<span<?= $Page->monto_documento_bs->viewAttributes() ?>>
<?= $Page->monto_documento_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->total_cobrado_bs->Visible) { // total_cobrado_bs ?>
        <!-- total_cobrado_bs -->
        <td<?= $Page->total_cobrado_bs->cellAttributes() ?>>
<span<?= $Page->total_cobrado_bs->viewAttributes() ?>>
<?= $Page->total_cobrado_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->saldo_bs->Visible) { // saldo_bs ?>
        <!-- saldo_bs -->
        <td<?= $Page->saldo_bs->cellAttributes() ?>>
<span<?= $Page->saldo_bs->viewAttributes() ?>>
<?= $Page->saldo_bs->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->dias_vencido->Visible) { // dias_vencido ?>
        <!-- dias_vencido -->
        <td<?= $Page->dias_vencido->cellAttributes() ?>>
<span<?= $Page->dias_vencido->viewAttributes() ?>>
<?= $Page->dias_vencido->getViewValue() ?></span>
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
