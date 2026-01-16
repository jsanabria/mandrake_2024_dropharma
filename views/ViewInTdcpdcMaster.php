<?php

namespace PHPMaker2024\mandrake;

// Table
$view_in_tdcpdc = Container("view_in_tdcpdc");
$view_in_tdcpdc->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_in_tdcpdc->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_in_tdcpdcmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_in_tdcpdc->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_in_tdcpdc->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->nro_documento->caption() ?></td>
            <td<?= $view_in_tdcpdc->nro_documento->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_nro_documento">
<span<?= $view_in_tdcpdc->nro_documento->viewAttributes() ?>>
<?= $view_in_tdcpdc->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_in_tdcpdc->fecha->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->fecha->caption() ?></td>
            <td<?= $view_in_tdcpdc->fecha->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_fecha">
<span<?= $view_in_tdcpdc->fecha->viewAttributes() ?>>
<?= $view_in_tdcpdc->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $view_in_tdcpdc->proveedor->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->proveedor->caption() ?></td>
            <td<?= $view_in_tdcpdc->proveedor->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_proveedor">
<span<?= $view_in_tdcpdc->proveedor->viewAttributes() ?>>
<?= $view_in_tdcpdc->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->almacen->Visible) { // almacen ?>
        <tr id="r_almacen"<?= $view_in_tdcpdc->almacen->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->almacen->caption() ?></td>
            <td<?= $view_in_tdcpdc->almacen->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_almacen">
<span<?= $view_in_tdcpdc->almacen->viewAttributes() ?>>
<?= $view_in_tdcpdc->almacen->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_in_tdcpdc->total->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->total->caption() ?></td>
            <td<?= $view_in_tdcpdc->total->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_total">
<span<?= $view_in_tdcpdc->total->viewAttributes() ?>>
<?= $view_in_tdcpdc->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_in_tdcpdc->moneda->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->moneda->caption() ?></td>
            <td<?= $view_in_tdcpdc->moneda->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_moneda">
<span<?= $view_in_tdcpdc->moneda->viewAttributes() ?>>
<?= $view_in_tdcpdc->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_in_tdcpdc->unidades->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->unidades->caption() ?></td>
            <td<?= $view_in_tdcpdc->unidades->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_unidades">
<span<?= $view_in_tdcpdc->unidades->viewAttributes() ?>>
<?= $view_in_tdcpdc->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $view_in_tdcpdc->estatus->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->estatus->caption() ?></td>
            <td<?= $view_in_tdcpdc->estatus->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_estatus">
<span<?= $view_in_tdcpdc->estatus->viewAttributes() ?>>
<?= $view_in_tdcpdc->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_in_tdcpdc->_username->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->_username->caption() ?></td>
            <td<?= $view_in_tdcpdc->_username->cellAttributes() ?>>
<span id="el_view_in_tdcpdc__username">
<span<?= $view_in_tdcpdc->_username->viewAttributes() ?>>
<?= $view_in_tdcpdc->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcpdc->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_in_tdcpdc->descuento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcpdc->TableLeftColumnClass ?>"><?= $view_in_tdcpdc->descuento->caption() ?></td>
            <td<?= $view_in_tdcpdc->descuento->cellAttributes() ?>>
<span id="el_view_in_tdcpdc_descuento">
<span<?= $view_in_tdcpdc->descuento->viewAttributes() ?>>
<?= $view_in_tdcpdc->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
