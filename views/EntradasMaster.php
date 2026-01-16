<?php

namespace PHPMaker2024\mandrake;

// Table
$entradas = Container("entradas");
$entradas->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($entradas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_entradasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($entradas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $entradas->nro_documento->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->nro_documento->caption() ?></td>
            <td<?= $entradas->nro_documento->cellAttributes() ?>>
<span id="el_entradas_nro_documento">
<span<?= $entradas->nro_documento->viewAttributes() ?>>
<?= $entradas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->doc_afectado->Visible) { // doc_afectado ?>
        <tr id="r_doc_afectado"<?= $entradas->doc_afectado->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->doc_afectado->caption() ?></td>
            <td<?= $entradas->doc_afectado->cellAttributes() ?>>
<span id="el_entradas_doc_afectado">
<span<?= $entradas->doc_afectado->viewAttributes() ?>>
<?= $entradas->doc_afectado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $entradas->fecha->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->fecha->caption() ?></td>
            <td<?= $entradas->fecha->cellAttributes() ?>>
<span id="el_entradas_fecha">
<span<?= $entradas->fecha->viewAttributes() ?>>
<?= $entradas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $entradas->proveedor->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->proveedor->caption() ?></td>
            <td<?= $entradas->proveedor->cellAttributes() ?>>
<span id="el_entradas_proveedor">
<span<?= $entradas->proveedor->viewAttributes() ?>>
<?= $entradas->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->total->Visible) { // total ?>
        <tr id="r_total"<?= $entradas->total->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->total->caption() ?></td>
            <td<?= $entradas->total->cellAttributes() ?>>
<span id="el_entradas_total">
<span<?= $entradas->total->viewAttributes() ?>>
<?= $entradas->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->documento->Visible) { // documento ?>
        <tr id="r_documento"<?= $entradas->documento->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->documento->caption() ?></td>
            <td<?= $entradas->documento->cellAttributes() ?>>
<span id="el_entradas_documento">
<span<?= $entradas->documento->viewAttributes() ?>>
<?= $entradas->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $entradas->estatus->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->estatus->caption() ?></td>
            <td<?= $entradas->estatus->cellAttributes() ?>>
<span id="el_entradas_estatus">
<span<?= $entradas->estatus->viewAttributes() ?>>
<?= $entradas->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->_username->Visible) { // username ?>
        <tr id="r__username"<?= $entradas->_username->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->_username->caption() ?></td>
            <td<?= $entradas->_username->cellAttributes() ?>>
<span id="el_entradas__username">
<span<?= $entradas->_username->viewAttributes() ?>>
<?= $entradas->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->consignacion->Visible) { // consignacion ?>
        <tr id="r_consignacion"<?= $entradas->consignacion->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->consignacion->caption() ?></td>
            <td<?= $entradas->consignacion->cellAttributes() ?>>
<span id="el_entradas_consignacion">
<span<?= $entradas->consignacion->viewAttributes() ?>>
<?= $entradas->consignacion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->ref_iva->Visible) { // ref_iva ?>
        <tr id="r_ref_iva"<?= $entradas->ref_iva->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->ref_iva->caption() ?></td>
            <td<?= $entradas->ref_iva->cellAttributes() ?>>
<span id="el_entradas_ref_iva">
<span<?= $entradas->ref_iva->viewAttributes() ?>>
<?php if (!EmptyString($entradas->ref_iva->getViewValue()) && $entradas->ref_iva->linkAttributes() != "") { ?>
<a<?= $entradas->ref_iva->linkAttributes() ?>><?= $entradas->ref_iva->getViewValue() ?></a>
<?php } else { ?>
<?= $entradas->ref_iva->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->ref_islr->Visible) { // ref_islr ?>
        <tr id="r_ref_islr"<?= $entradas->ref_islr->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->ref_islr->caption() ?></td>
            <td<?= $entradas->ref_islr->cellAttributes() ?>>
<span id="el_entradas_ref_islr">
<span<?= $entradas->ref_islr->viewAttributes() ?>>
<?php if (!EmptyString($entradas->ref_islr->getViewValue()) && $entradas->ref_islr->linkAttributes() != "") { ?>
<a<?= $entradas->ref_islr->linkAttributes() ?>><?= $entradas->ref_islr->getViewValue() ?></a>
<?php } else { ?>
<?= $entradas->ref_islr->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->ref_municipal->Visible) { // ref_municipal ?>
        <tr id="r_ref_municipal"<?= $entradas->ref_municipal->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->ref_municipal->caption() ?></td>
            <td<?= $entradas->ref_municipal->cellAttributes() ?>>
<span id="el_entradas_ref_municipal">
<span<?= $entradas->ref_municipal->viewAttributes() ?>>
<?= $entradas->ref_municipal->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $entradas->descuento->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->descuento->caption() ?></td>
            <td<?= $entradas->descuento->cellAttributes() ?>>
<span id="el_entradas_descuento">
<span<?= $entradas->descuento->viewAttributes() ?>>
<?= $entradas->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($entradas->archivo_pedido->Visible) { // archivo_pedido ?>
        <tr id="r_archivo_pedido"<?= $entradas->archivo_pedido->rowAttributes() ?>>
            <td class="<?= $entradas->TableLeftColumnClass ?>"><?= $entradas->archivo_pedido->caption() ?></td>
            <td<?= $entradas->archivo_pedido->cellAttributes() ?>>
<span id="el_entradas_archivo_pedido">
<span<?= $entradas->archivo_pedido->viewAttributes() ?>>
<?= GetFileViewTag($entradas->archivo_pedido, $entradas->archivo_pedido->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
