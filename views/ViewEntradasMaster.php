<?php

namespace PHPMaker2024\mandrake;

// Table
$view_entradas = Container("view_entradas");
$view_entradas->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_entradas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_entradasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_entradas->nombre_documento->Visible) { // nombre_documento ?>
        <tr id="r_nombre_documento"<?= $view_entradas->nombre_documento->rowAttributes() ?>>
            <td class="<?= $view_entradas->TableLeftColumnClass ?>"><?= $view_entradas->nombre_documento->caption() ?></td>
            <td<?= $view_entradas->nombre_documento->cellAttributes() ?>>
<span id="el_view_entradas_nombre_documento">
<span<?= $view_entradas->nombre_documento->viewAttributes() ?>>
<?= $view_entradas->nombre_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_entradas->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $view_entradas->proveedor->rowAttributes() ?>>
            <td class="<?= $view_entradas->TableLeftColumnClass ?>"><?= $view_entradas->proveedor->caption() ?></td>
            <td<?= $view_entradas->proveedor->cellAttributes() ?>>
<span id="el_view_entradas_proveedor">
<span<?= $view_entradas->proveedor->viewAttributes() ?>>
<?= $view_entradas->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_entradas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_entradas->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_entradas->TableLeftColumnClass ?>"><?= $view_entradas->nro_documento->caption() ?></td>
            <td<?= $view_entradas->nro_documento->cellAttributes() ?>>
<span id="el_view_entradas_nro_documento">
<span<?= $view_entradas->nro_documento->viewAttributes() ?>>
<?= $view_entradas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_entradas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_entradas->fecha->rowAttributes() ?>>
            <td class="<?= $view_entradas->TableLeftColumnClass ?>"><?= $view_entradas->fecha->caption() ?></td>
            <td<?= $view_entradas->fecha->cellAttributes() ?>>
<span id="el_view_entradas_fecha">
<span<?= $view_entradas->fecha->viewAttributes() ?>>
<?= $view_entradas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_entradas->nota->Visible) { // nota ?>
        <tr id="r_nota"<?= $view_entradas->nota->rowAttributes() ?>>
            <td class="<?= $view_entradas->TableLeftColumnClass ?>"><?= $view_entradas->nota->caption() ?></td>
            <td<?= $view_entradas->nota->cellAttributes() ?>>
<span id="el_view_entradas_nota">
<span<?= $view_entradas->nota->viewAttributes() ?>>
<?= $view_entradas->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
