<?php

namespace PHPMaker2024\mandrake;

// Page object
$CompaniaCuentaPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { compania_cuenta: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->banco->Visible) { // banco ?>
    <?php if (!$Page->banco->Sortable || !$Page->sortUrl($Page->banco)) { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><?= $Page->banco->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->banco->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->banco->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->banco->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->banco->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->banco->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
    <?php if (!$Page->titular->Sortable || !$Page->sortUrl($Page->titular)) { ?>
        <th class="<?= $Page->titular->headerCellClass() ?>"><?= $Page->titular->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->titular->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->titular->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->titular->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->titular->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->titular->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <?php if (!$Page->tipo->Sortable || !$Page->sortUrl($Page->tipo)) { ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><?= $Page->tipo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tipo->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->tipo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->tipo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->tipo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->tipo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
    <?php if (!$Page->numero->Sortable || !$Page->sortUrl($Page->numero)) { ?>
        <th class="<?= $Page->numero->headerCellClass() ?>"><?= $Page->numero->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->numero->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->numero->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->numero->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->numero->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->numero->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
    <?php if (!$Page->mostrar->Sortable || !$Page->sortUrl($Page->mostrar)) { ?>
        <th class="<?= $Page->mostrar->headerCellClass() ?>"><?= $Page->mostrar->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->mostrar->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->mostrar->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->mostrar->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->mostrar->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->mostrar->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <?php if (!$Page->cuenta->Sortable || !$Page->sortUrl($Page->cuenta)) { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><?= $Page->cuenta->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->cuenta->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cuenta->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cuenta->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cuenta->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <?php if (!$Page->activo->Sortable || !$Page->sortUrl($Page->activo)) { ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><?= $Page->activo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->activo->headerCellClass() ?>"><div role="button" data-table="compania_cuenta" data-sort="<?= HtmlEncode($Page->activo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->activo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->activo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->activo->getSortIcon() ?></span>
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
<?php if ($Page->banco->Visible) { // banco ?>
        <!-- banco -->
        <td<?= $Page->banco->cellAttributes() ?>>
<span<?= $Page->banco->viewAttributes() ?>>
<?= $Page->banco->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->titular->Visible) { // titular ?>
        <!-- titular -->
        <td<?= $Page->titular->cellAttributes() ?>>
<span<?= $Page->titular->viewAttributes() ?>>
<?= $Page->titular->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
        <!-- tipo -->
        <td<?= $Page->tipo->cellAttributes() ?>>
<span<?= $Page->tipo->viewAttributes() ?>>
<?= $Page->tipo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->numero->Visible) { // numero ?>
        <!-- numero -->
        <td<?= $Page->numero->cellAttributes() ?>>
<span<?= $Page->numero->viewAttributes() ?>>
<?= $Page->numero->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->mostrar->Visible) { // mostrar ?>
        <!-- mostrar -->
        <td<?= $Page->mostrar->cellAttributes() ?>>
<span<?= $Page->mostrar->viewAttributes() ?>>
<?= $Page->mostrar->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <!-- cuenta -->
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
        <!-- activo -->
        <td<?= $Page->activo->cellAttributes() ?>>
<span<?= $Page->activo->viewAttributes() ?>>
<?= $Page->activo->getViewValue() ?></span>
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
