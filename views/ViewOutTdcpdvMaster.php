<?php

namespace PHPMaker2024\mandrake;

// Table
$view_out_tdcpdv = Container("view_out_tdcpdv");
$view_out_tdcpdv->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_out_tdcpdv->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_out_tdcpdvmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_out_tdcpdv->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_out_tdcpdv->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->nro_documento->caption() ?></td>
            <td<?= $view_out_tdcpdv->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_nro_documento">
<span<?= $view_out_tdcpdv->nro_documento->viewAttributes() ?>>
<?= $view_out_tdcpdv->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_out_tdcpdv->fecha->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->fecha->caption() ?></td>
            <td<?= $view_out_tdcpdv->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_fecha">
<span<?= $view_out_tdcpdv->fecha->viewAttributes() ?>>
<?= $view_out_tdcpdv->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $view_out_tdcpdv->cliente->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->cliente->caption() ?></td>
            <td<?= $view_out_tdcpdv->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_cliente">
<span<?= $view_out_tdcpdv->cliente->viewAttributes() ?>>
<?= $view_out_tdcpdv->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_out_tdcpdv->total->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->total->caption() ?></td>
            <td<?= $view_out_tdcpdv->total->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_total">
<span<?= $view_out_tdcpdv->total->viewAttributes() ?>>
<?= $view_out_tdcpdv->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_out_tdcpdv->moneda->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->moneda->caption() ?></td>
            <td<?= $view_out_tdcpdv->moneda->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_moneda">
<span<?= $view_out_tdcpdv->moneda->viewAttributes() ?>>
<?= $view_out_tdcpdv->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_out_tdcpdv->descuento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->descuento->caption() ?></td>
            <td<?= $view_out_tdcpdv->descuento->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_descuento">
<span<?= $view_out_tdcpdv->descuento->viewAttributes() ?>>
<?= $view_out_tdcpdv->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->descuento2->Visible) { // descuento2 ?>
        <tr id="r_descuento2"<?= $view_out_tdcpdv->descuento2->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->descuento2->caption() ?></td>
            <td<?= $view_out_tdcpdv->descuento2->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_descuento2">
<span<?= $view_out_tdcpdv->descuento2->viewAttributes() ?>>
<?= $view_out_tdcpdv->descuento2->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->lista_pedido->Visible) { // lista_pedido ?>
        <tr id="r_lista_pedido"<?= $view_out_tdcpdv->lista_pedido->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->lista_pedido->caption() ?></td>
            <td<?= $view_out_tdcpdv->lista_pedido->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_lista_pedido">
<span<?= $view_out_tdcpdv->lista_pedido->viewAttributes() ?>>
<?= $view_out_tdcpdv->lista_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_out_tdcpdv->unidades->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->unidades->caption() ?></td>
            <td<?= $view_out_tdcpdv->unidades->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_unidades">
<span<?= $view_out_tdcpdv->unidades->viewAttributes() ?>>
<?= $view_out_tdcpdv->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_out_tdcpdv->_username->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->_username->caption() ?></td>
            <td<?= $view_out_tdcpdv->_username->cellAttributes() ?>>
<span id="el_view_out_tdcpdv__username">
<span<?= $view_out_tdcpdv->_username->viewAttributes() ?>>
<?= $view_out_tdcpdv->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcpdv->asesor_asignado->Visible) { // asesor_asignado ?>
        <tr id="r_asesor_asignado"<?= $view_out_tdcpdv->asesor_asignado->rowAttributes() ?>>
            <td class="<?= $view_out_tdcpdv->TableLeftColumnClass ?>"><?= $view_out_tdcpdv->asesor_asignado->caption() ?></td>
            <td<?= $view_out_tdcpdv->asesor_asignado->cellAttributes() ?>>
<span id="el_view_out_tdcpdv_asesor_asignado">
<span<?= $view_out_tdcpdv->asesor_asignado->viewAttributes() ?>>
<?= $view_out_tdcpdv->asesor_asignado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
