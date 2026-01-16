<?php

namespace PHPMaker2024\mandrake;

// Table
$view_out_tdcnet = Container("view_out_tdcnet");
$view_out_tdcnet->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_out_tdcnet->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_out_tdcnetmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_out_tdcnet->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_out_tdcnet->_username->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->_username->caption() ?></td>
            <td<?= $view_out_tdcnet->_username->cellAttributes() ?>>
<span id="el_view_out_tdcnet__username">
<span<?= $view_out_tdcnet->_username->viewAttributes() ?>>
<?= $view_out_tdcnet->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_out_tdcnet->fecha->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->fecha->caption() ?></td>
            <td<?= $view_out_tdcnet->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcnet_fecha">
<span<?= $view_out_tdcnet->fecha->viewAttributes() ?>>
<?= $view_out_tdcnet->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $view_out_tdcnet->cliente->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->cliente->caption() ?></td>
            <td<?= $view_out_tdcnet->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcnet_cliente">
<span<?= $view_out_tdcnet->cliente->viewAttributes() ?>>
<?= $view_out_tdcnet->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_out_tdcnet->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->nro_documento->caption() ?></td>
            <td<?= $view_out_tdcnet->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcnet_nro_documento">
<span<?= $view_out_tdcnet->nro_documento->viewAttributes() ?>>
<?= $view_out_tdcnet->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_out_tdcnet->total->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->total->caption() ?></td>
            <td<?= $view_out_tdcnet->total->cellAttributes() ?>>
<span id="el_view_out_tdcnet_total">
<span<?= $view_out_tdcnet->total->viewAttributes() ?>>
<?= $view_out_tdcnet->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->lista_pedido->Visible) { // lista_pedido ?>
        <tr id="r_lista_pedido"<?= $view_out_tdcnet->lista_pedido->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->lista_pedido->caption() ?></td>
            <td<?= $view_out_tdcnet->lista_pedido->cellAttributes() ?>>
<span id="el_view_out_tdcnet_lista_pedido">
<span<?= $view_out_tdcnet->lista_pedido->viewAttributes() ?>>
<?= $view_out_tdcnet->lista_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_out_tdcnet->unidades->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->unidades->caption() ?></td>
            <td<?= $view_out_tdcnet->unidades->cellAttributes() ?>>
<span id="el_view_out_tdcnet_unidades">
<span<?= $view_out_tdcnet->unidades->viewAttributes() ?>>
<?= $view_out_tdcnet->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->id_documento_padre->Visible) { // id_documento_padre ?>
        <tr id="r_id_documento_padre"<?= $view_out_tdcnet->id_documento_padre->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->id_documento_padre->caption() ?></td>
            <td<?= $view_out_tdcnet->id_documento_padre->cellAttributes() ?>>
<span id="el_view_out_tdcnet_id_documento_padre">
<span<?= $view_out_tdcnet->id_documento_padre->viewAttributes() ?>>
<?= $view_out_tdcnet->id_documento_padre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->dias_credito->Visible) { // dias_credito ?>
        <tr id="r_dias_credito"<?= $view_out_tdcnet->dias_credito->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->dias_credito->caption() ?></td>
            <td<?= $view_out_tdcnet->dias_credito->cellAttributes() ?>>
<span id="el_view_out_tdcnet_dias_credito">
<span<?= $view_out_tdcnet->dias_credito->viewAttributes() ?>>
<?= $view_out_tdcnet->dias_credito->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->bultos->Visible) { // bultos ?>
        <tr id="r_bultos"<?= $view_out_tdcnet->bultos->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->bultos->caption() ?></td>
            <td<?= $view_out_tdcnet->bultos->cellAttributes() ?>>
<span id="el_view_out_tdcnet_bultos">
<span<?= $view_out_tdcnet->bultos->viewAttributes() ?>>
<?= $view_out_tdcnet->bultos->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_out_tdcnet->descuento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->descuento->caption() ?></td>
            <td<?= $view_out_tdcnet->descuento->cellAttributes() ?>>
<span id="el_view_out_tdcnet_descuento">
<span<?= $view_out_tdcnet->descuento->viewAttributes() ?>>
<?= $view_out_tdcnet->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->descuento2->Visible) { // descuento2 ?>
        <tr id="r_descuento2"<?= $view_out_tdcnet->descuento2->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->descuento2->caption() ?></td>
            <td<?= $view_out_tdcnet->descuento2->cellAttributes() ?>>
<span id="el_view_out_tdcnet_descuento2">
<span<?= $view_out_tdcnet->descuento2->viewAttributes() ?>>
<?= $view_out_tdcnet->descuento2->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->factura->Visible) { // factura ?>
        <tr id="r_factura"<?= $view_out_tdcnet->factura->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->factura->caption() ?></td>
            <td<?= $view_out_tdcnet->factura->cellAttributes() ?>>
<span id="el_view_out_tdcnet_factura">
<span<?= $view_out_tdcnet->factura->viewAttributes() ?>>
<?= $view_out_tdcnet->factura->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif"<?= $view_out_tdcnet->ci_rif->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->ci_rif->caption() ?></td>
            <td<?= $view_out_tdcnet->ci_rif->cellAttributes() ?>>
<span id="el_view_out_tdcnet_ci_rif">
<span<?= $view_out_tdcnet->ci_rif->viewAttributes() ?>>
<?= $view_out_tdcnet->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcnet->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $view_out_tdcnet->nombre->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->nombre->caption() ?></td>
            <td<?= $view_out_tdcnet->nombre->cellAttributes() ?>>
<span id="el_view_out_tdcnet_nombre">
<span<?= $view_out_tdcnet->nombre->viewAttributes() ?>>
<?= $view_out_tdcnet->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
