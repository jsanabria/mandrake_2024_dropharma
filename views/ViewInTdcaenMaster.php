<?php

namespace PHPMaker2024\mandrake;

// Table
$view_in_tdcaen = Container("view_in_tdcaen");
$view_in_tdcaen->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_in_tdcaen->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_in_tdcaenmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_in_tdcaen->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_in_tdcaen->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->nro_documento->caption() ?></td>
            <td<?= $view_in_tdcaen->nro_documento->cellAttributes() ?>>
<span id="el_view_in_tdcaen_nro_documento">
<span<?= $view_in_tdcaen->nro_documento->viewAttributes() ?>>
<?= $view_in_tdcaen->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcaen->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_in_tdcaen->fecha->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->fecha->caption() ?></td>
            <td<?= $view_in_tdcaen->fecha->cellAttributes() ?>>
<span id="el_view_in_tdcaen_fecha">
<span<?= $view_in_tdcaen->fecha->viewAttributes() ?>>
<?= $view_in_tdcaen->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcaen->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $view_in_tdcaen->proveedor->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->proveedor->caption() ?></td>
            <td<?= $view_in_tdcaen->proveedor->cellAttributes() ?>>
<span id="el_view_in_tdcaen_proveedor">
<span<?= $view_in_tdcaen->proveedor->viewAttributes() ?>>
<?= $view_in_tdcaen->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcaen->almacen->Visible) { // almacen ?>
        <tr id="r_almacen"<?= $view_in_tdcaen->almacen->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->almacen->caption() ?></td>
            <td<?= $view_in_tdcaen->almacen->cellAttributes() ?>>
<span id="el_view_in_tdcaen_almacen">
<span<?= $view_in_tdcaen->almacen->viewAttributes() ?>>
<?= $view_in_tdcaen->almacen->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcaen->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_in_tdcaen->unidades->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->unidades->caption() ?></td>
            <td<?= $view_in_tdcaen->unidades->cellAttributes() ?>>
<span id="el_view_in_tdcaen_unidades">
<span<?= $view_in_tdcaen->unidades->viewAttributes() ?>>
<?= $view_in_tdcaen->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcaen->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $view_in_tdcaen->estatus->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->estatus->caption() ?></td>
            <td<?= $view_in_tdcaen->estatus->cellAttributes() ?>>
<span id="el_view_in_tdcaen_estatus">
<span<?= $view_in_tdcaen->estatus->viewAttributes() ?>>
<?= $view_in_tdcaen->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcaen->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_in_tdcaen->_username->rowAttributes() ?>>
            <td class="<?= $view_in_tdcaen->TableLeftColumnClass ?>"><?= $view_in_tdcaen->_username->caption() ?></td>
            <td<?= $view_in_tdcaen->_username->cellAttributes() ?>>
<span id="el_view_in_tdcaen__username">
<span<?= $view_in_tdcaen->_username->viewAttributes() ?>>
<?= $view_in_tdcaen->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
