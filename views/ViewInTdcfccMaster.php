<?php

namespace PHPMaker2024\mandrake;

// Table
$view_in_tdcfcc = Container("view_in_tdcfcc");
$view_in_tdcfcc->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_in_tdcfcc->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_in_tdcfccmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_in_tdcfcc->documento->Visible) { // documento ?>
        <tr id="r_documento"<?= $view_in_tdcfcc->documento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->documento->caption() ?></td>
            <td<?= $view_in_tdcfcc->documento->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_documento">
<span<?= $view_in_tdcfcc->documento->viewAttributes() ?>>
<?= $view_in_tdcfcc->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_in_tdcfcc->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->nro_documento->caption() ?></td>
            <td<?= $view_in_tdcfcc->nro_documento->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_nro_documento">
<span<?= $view_in_tdcfcc->nro_documento->viewAttributes() ?>>
<?= $view_in_tdcfcc->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_in_tdcfcc->fecha->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->fecha->caption() ?></td>
            <td<?= $view_in_tdcfcc->fecha->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_fecha">
<span<?= $view_in_tdcfcc->fecha->viewAttributes() ?>>
<?= $view_in_tdcfcc->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->nro_control->Visible) { // nro_control ?>
        <tr id="r_nro_control"<?= $view_in_tdcfcc->nro_control->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->nro_control->caption() ?></td>
            <td<?= $view_in_tdcfcc->nro_control->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_nro_control">
<span<?= $view_in_tdcfcc->nro_control->viewAttributes() ?>>
<?= $view_in_tdcfcc->nro_control->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $view_in_tdcfcc->proveedor->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->proveedor->caption() ?></td>
            <td<?= $view_in_tdcfcc->proveedor->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_proveedor">
<span<?= $view_in_tdcfcc->proveedor->viewAttributes() ?>>
<?= $view_in_tdcfcc->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->doc_afectado->Visible) { // doc_afectado ?>
        <tr id="r_doc_afectado"<?= $view_in_tdcfcc->doc_afectado->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->doc_afectado->caption() ?></td>
            <td<?= $view_in_tdcfcc->doc_afectado->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_doc_afectado">
<span<?= $view_in_tdcfcc->doc_afectado->viewAttributes() ?>>
<?= $view_in_tdcfcc->doc_afectado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_in_tdcfcc->total->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->total->caption() ?></td>
            <td<?= $view_in_tdcfcc->total->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_total">
<span<?= $view_in_tdcfcc->total->viewAttributes() ?>>
<?= $view_in_tdcfcc->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_in_tdcfcc->moneda->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->moneda->caption() ?></td>
            <td<?= $view_in_tdcfcc->moneda->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_moneda">
<span<?= $view_in_tdcfcc->moneda->viewAttributes() ?>>
<?= $view_in_tdcfcc->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_in_tdcfcc->unidades->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->unidades->caption() ?></td>
            <td<?= $view_in_tdcfcc->unidades->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_unidades">
<span<?= $view_in_tdcfcc->unidades->viewAttributes() ?>>
<?= $view_in_tdcfcc->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $view_in_tdcfcc->estatus->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->estatus->caption() ?></td>
            <td<?= $view_in_tdcfcc->estatus->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_estatus">
<span<?= $view_in_tdcfcc->estatus->viewAttributes() ?>>
<?= $view_in_tdcfcc->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->_username->Visible) { // username ?>
        <tr id="r__username"<?= $view_in_tdcfcc->_username->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->_username->caption() ?></td>
            <td<?= $view_in_tdcfcc->_username->cellAttributes() ?>>
<span id="el_view_in_tdcfcc__username">
<span<?= $view_in_tdcfcc->_username->viewAttributes() ?>>
<?= $view_in_tdcfcc->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_in_tdcfcc->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_in_tdcfcc->descuento->rowAttributes() ?>>
            <td class="<?= $view_in_tdcfcc->TableLeftColumnClass ?>"><?= $view_in_tdcfcc->descuento->caption() ?></td>
            <td<?= $view_in_tdcfcc->descuento->cellAttributes() ?>>
<span id="el_view_in_tdcfcc_descuento">
<span<?= $view_in_tdcfcc->descuento->viewAttributes() ?>>
<?= $view_in_tdcfcc->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
