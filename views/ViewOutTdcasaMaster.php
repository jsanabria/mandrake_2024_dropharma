<?php

namespace PHPMaker2024\mandrake;

// Table
$view_out_tdcasa = Container("view_out_tdcasa");
$view_out_tdcasa->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_out_tdcasa->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_out_tdcasamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_out_tdcasa->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_out_tdcasa->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->nro_documento->caption() ?></td>
            <td<?= $view_out_tdcasa->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcasa_nro_documento">
<span<?= $view_out_tdcasa->nro_documento->viewAttributes() ?>>
<?= $view_out_tdcasa->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_out_tdcasa->fecha->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->fecha->caption() ?></td>
            <td<?= $view_out_tdcasa->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcasa_fecha">
<span<?= $view_out_tdcasa->fecha->viewAttributes() ?>>
<?= $view_out_tdcasa->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $view_out_tdcasa->cliente->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->cliente->caption() ?></td>
            <td<?= $view_out_tdcasa->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcasa_cliente">
<span<?= $view_out_tdcasa->cliente->viewAttributes() ?>>
<?= $view_out_tdcasa->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_out_tdcasa->total->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->total->caption() ?></td>
            <td<?= $view_out_tdcasa->total->cellAttributes() ?>>
<span id="el_view_out_tdcasa_total">
<span<?= $view_out_tdcasa->total->viewAttributes() ?>>
<?= $view_out_tdcasa->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_out_tdcasa->unidades->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->unidades->caption() ?></td>
            <td<?= $view_out_tdcasa->unidades->cellAttributes() ?>>
<span id="el_view_out_tdcasa_unidades">
<span<?= $view_out_tdcasa->unidades->viewAttributes() ?>>
<?= $view_out_tdcasa->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_out_tdcasa->_username->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->_username->caption() ?></td>
            <td<?= $view_out_tdcasa->_username->cellAttributes() ?>>
<span id="el_view_out_tdcasa__username">
<span<?= $view_out_tdcasa->_username->viewAttributes() ?>>
<?= $view_out_tdcasa->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_out_tdcasa->descuento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->descuento->caption() ?></td>
            <td<?= $view_out_tdcasa->descuento->cellAttributes() ?>>
<span id="el_view_out_tdcasa_descuento">
<span<?= $view_out_tdcasa->descuento->viewAttributes() ?>>
<?= $view_out_tdcasa->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->factura->Visible) { // factura ?>
        <tr id="r_factura"<?= $view_out_tdcasa->factura->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->factura->caption() ?></td>
            <td<?= $view_out_tdcasa->factura->cellAttributes() ?>>
<span id="el_view_out_tdcasa_factura">
<span<?= $view_out_tdcasa->factura->viewAttributes() ?>>
<?= $view_out_tdcasa->factura->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif"<?= $view_out_tdcasa->ci_rif->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->ci_rif->caption() ?></td>
            <td<?= $view_out_tdcasa->ci_rif->cellAttributes() ?>>
<span id="el_view_out_tdcasa_ci_rif">
<span<?= $view_out_tdcasa->ci_rif->viewAttributes() ?>>
<?= $view_out_tdcasa->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcasa->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $view_out_tdcasa->nombre->rowAttributes() ?>>
            <td class="<?= $view_out_tdcasa->TableLeftColumnClass ?>"><?= $view_out_tdcasa->nombre->caption() ?></td>
            <td<?= $view_out_tdcasa->nombre->cellAttributes() ?>>
<span id="el_view_out_tdcasa_nombre">
<span<?= $view_out_tdcasa->nombre->viewAttributes() ?>>
<?= $view_out_tdcasa->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
