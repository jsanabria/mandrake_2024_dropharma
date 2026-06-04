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
<?php if ($view_out_tdcnet->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_out_tdcnet->moneda->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->moneda->caption() ?></td>
            <td<?= $view_out_tdcnet->moneda->cellAttributes() ?>>
<span id="el_view_out_tdcnet_moneda">
<span<?= $view_out_tdcnet->moneda->viewAttributes() ?>>
<?= $view_out_tdcnet->moneda->getViewValue() ?></span>
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
<?php if ($view_out_tdcnet->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $view_out_tdcnet->estatus->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->estatus->caption() ?></td>
            <td<?= $view_out_tdcnet->estatus->cellAttributes() ?>>
<span id="el_view_out_tdcnet_estatus">
<span<?= $view_out_tdcnet->estatus->viewAttributes() ?>>
<?= $view_out_tdcnet->estatus->getViewValue() ?></span>
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
<?php if ($view_out_tdcnet->entregado->Visible) { // entregado ?>
        <tr id="r_entregado"<?= $view_out_tdcnet->entregado->rowAttributes() ?>>
            <td class="<?= $view_out_tdcnet->TableLeftColumnClass ?>"><?= $view_out_tdcnet->entregado->caption() ?></td>
            <td<?= $view_out_tdcnet->entregado->cellAttributes() ?>>
<span id="el_view_out_tdcnet_entregado">
<span<?= $view_out_tdcnet->entregado->viewAttributes() ?>>
<?= $view_out_tdcnet->entregado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
