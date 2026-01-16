<?php

namespace PHPMaker2024\mandrake;

// Page object
$ContReglasPreview = &$Page;
?>
<script>
ew.deepAssign(ew.vars, { tables: { cont_reglas: <?= JsonEncode($Page->toClientVar()) ?> } });
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
<?php if ($Page->codigo->Visible) { // codigo ?>
    <?php if (!$Page->codigo->Sortable || !$Page->sortUrl($Page->codigo)) { ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><?= $Page->codigo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->codigo->headerCellClass() ?>"><div role="button" data-table="cont_reglas" data-sort="<?= HtmlEncode($Page->codigo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->codigo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->codigo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->codigo->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <?php if (!$Page->descripcion->Sortable || !$Page->sortUrl($Page->descripcion)) { ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><?= $Page->descripcion->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->descripcion->headerCellClass() ?>"><div role="button" data-table="cont_reglas" data-sort="<?= HtmlEncode($Page->descripcion->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->descripcion->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->descripcion->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->descripcion->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
    <?php if (!$Page->cuenta->Sortable || !$Page->sortUrl($Page->cuenta)) { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><?= $Page->cuenta->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cuenta->headerCellClass() ?>"><div role="button" data-table="cont_reglas" data-sort="<?= HtmlEncode($Page->cuenta->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cuenta->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cuenta->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cuenta->getSortIcon() ?></span>
            </div>
        </th>
    <?php } ?>
<?php } ?>
<?php if ($Page->cargo->Visible) { // cargo ?>
    <?php if (!$Page->cargo->Sortable || !$Page->sortUrl($Page->cargo)) { ?>
        <th class="<?= $Page->cargo->headerCellClass() ?>"><?= $Page->cargo->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cargo->headerCellClass() ?>"><div role="button" data-table="cont_reglas" data-sort="<?= HtmlEncode($Page->cargo->Name) ?>" data-sort-type="1" data-sort-order="<?= $Page->cargo->getNextSort() ?>">
            <div class="ew-table-header-btn">
                <span class="ew-table-header-caption"><?= $Page->cargo->caption() ?></span>
                <span class="ew-table-header-sort"><?= $Page->cargo->getSortIcon() ?></span>
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
<?php if ($Page->codigo->Visible) { // codigo ?>
        <!-- codigo -->
        <td<?= $Page->codigo->cellAttributes() ?>>
<span<?= $Page->codigo->viewAttributes() ?>>
<?= $Page->codigo->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
        <!-- descripcion -->
        <td<?= $Page->descripcion->cellAttributes() ?>>
<span<?= $Page->descripcion->viewAttributes() ?>>
<?= $Page->descripcion->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cuenta->Visible) { // cuenta ?>
        <!-- cuenta -->
        <td<?= $Page->cuenta->cellAttributes() ?>>
<span<?= $Page->cuenta->viewAttributes() ?>>
<?= $Page->cuenta->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->cargo->Visible) { // cargo ?>
        <!-- cargo -->
        <td<?= $Page->cargo->cellAttributes() ?>>
<span<?= $Page->cargo->viewAttributes() ?>>
<?= $Page->cargo->getViewValue() ?></span>
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
