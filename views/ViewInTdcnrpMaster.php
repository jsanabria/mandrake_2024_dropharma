<?php

namespace PHPMaker2024\mandrake;

// Table
$view_in_tdcnrp = Container("view_in_tdcnrp");
$view_in_tdcnrp->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_in_tdcnrp->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_in_tdcnrpmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_in_tdcnrp->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_in_tdcnrp->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->nro_documento->caption() ?></td>
            <td<?= $view_in_tdcnrp->nro_documento->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_nro_documento">
<span<?= $view_in_tdcnrp->nro_documento->viewAttributes() ?>>
<?= $view_in_tdcnrp->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_in_tdcnrp->fecha->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->fecha->caption() ?></td>
            <td<?= $view_in_tdcnrp->fecha->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_fecha">
<span<?= $view_in_tdcnrp->fecha->viewAttributes() ?>>
<?= $view_in_tdcnrp->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $view_in_tdcnrp->proveedor->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->proveedor->caption() ?></td>
            <td<?= $view_in_tdcnrp->proveedor->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_proveedor">
<span<?= $view_in_tdcnrp->proveedor->viewAttributes() ?>>
<?= $view_in_tdcnrp->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->almacen->Visible) { // almacen ?>
        <tr id="r_almacen"<?= $view_in_tdcnrp->almacen->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->almacen->caption() ?></td>
            <td<?= $view_in_tdcnrp->almacen->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_almacen">
<span<?= $view_in_tdcnrp->almacen->viewAttributes() ?>>
<?= $view_in_tdcnrp->almacen->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_in_tdcnrp->total->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->total->caption() ?></td>
            <td<?= $view_in_tdcnrp->total->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_total">
<span<?= $view_in_tdcnrp->total->viewAttributes() ?>>
<?= $view_in_tdcnrp->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_in_tdcnrp->moneda->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->moneda->caption() ?></td>
            <td<?= $view_in_tdcnrp->moneda->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_moneda">
<span<?= $view_in_tdcnrp->moneda->viewAttributes() ?>>
<?= $view_in_tdcnrp->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_in_tdcnrp->unidades->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->unidades->caption() ?></td>
            <td<?= $view_in_tdcnrp->unidades->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_unidades">
<span<?= $view_in_tdcnrp->unidades->viewAttributes() ?>>
<?= $view_in_tdcnrp->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $view_in_tdcnrp->estatus->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->estatus->caption() ?></td>
            <td<?= $view_in_tdcnrp->estatus->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_estatus">
<span<?= $view_in_tdcnrp->estatus->viewAttributes() ?>>
<?= $view_in_tdcnrp->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_in_tdcnrp->_username->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->_username->caption() ?></td>
            <td<?= $view_in_tdcnrp->_username->cellAttributes() ?>>
<span id="el_view_in_tdcnrp__username">
<span<?= $view_in_tdcnrp->_username->viewAttributes() ?>>
<?= $view_in_tdcnrp->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcnrp->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_in_tdcnrp->descuento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcnrp->TableLeftColumnClass ?>"><?= $view_in_tdcnrp->descuento->caption() ?></td>
            <td<?= $view_in_tdcnrp->descuento->cellAttributes() ?>>
<span id="el_view_in_tdcnrp_descuento">
<span<?= $view_in_tdcnrp->descuento->viewAttributes() ?>>
<?= $view_in_tdcnrp->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
