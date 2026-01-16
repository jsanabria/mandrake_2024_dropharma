<?php

namespace PHPMaker2024\mandrake;

// Table
$salidas = Container("salidas");
$salidas->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($salidas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_salidasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($salidas->tipo_documento->Visible) { // tipo_documento ?>
        <tr id="r_tipo_documento"<?= $salidas->tipo_documento->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->tipo_documento->caption() ?></td>
            <td<?= $salidas->tipo_documento->cellAttributes() ?>>
<span id="el_salidas_tipo_documento">
<span<?= $salidas->tipo_documento->viewAttributes() ?>>
<?= $salidas->tipo_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $salidas->nro_documento->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->nro_documento->caption() ?></td>
            <td<?= $salidas->nro_documento->cellAttributes() ?>>
<span id="el_salidas_nro_documento">
<span<?= $salidas->nro_documento->viewAttributes() ?>>
<?= $salidas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $salidas->fecha->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->fecha->caption() ?></td>
            <td<?= $salidas->fecha->cellAttributes() ?>>
<span id="el_salidas_fecha">
<span<?= $salidas->fecha->viewAttributes() ?>>
<?= $salidas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $salidas->cliente->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->cliente->caption() ?></td>
            <td<?= $salidas->cliente->cellAttributes() ?>>
<span id="el_salidas_cliente">
<span<?= $salidas->cliente->viewAttributes() ?>>
<?= $salidas->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->documento->Visible) { // documento ?>
        <tr id="r_documento"<?= $salidas->documento->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->documento->caption() ?></td>
            <td<?= $salidas->documento->cellAttributes() ?>>
<span id="el_salidas_documento">
<span<?= $salidas->documento->viewAttributes() ?>>
<?= $salidas->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->doc_afectado->Visible) { // doc_afectado ?>
        <tr id="r_doc_afectado"<?= $salidas->doc_afectado->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->doc_afectado->caption() ?></td>
            <td<?= $salidas->doc_afectado->cellAttributes() ?>>
<span id="el_salidas_doc_afectado">
<span<?= $salidas->doc_afectado->viewAttributes() ?>>
<?= $salidas->doc_afectado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->monto_total->Visible) { // monto_total ?>
        <tr id="r_monto_total"<?= $salidas->monto_total->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->monto_total->caption() ?></td>
            <td<?= $salidas->monto_total->cellAttributes() ?>>
<span id="el_salidas_monto_total">
<span<?= $salidas->monto_total->viewAttributes() ?>>
<?php if (!EmptyString($salidas->monto_total->getViewValue()) && $salidas->monto_total->linkAttributes() != "") { ?>
<a<?= $salidas->monto_total->linkAttributes() ?>><?= $salidas->monto_total->getViewValue() ?></a>
<?php } else { ?>
<?= $salidas->monto_total->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->alicuota_iva->Visible) { // alicuota_iva ?>
        <tr id="r_alicuota_iva"<?= $salidas->alicuota_iva->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->alicuota_iva->caption() ?></td>
            <td<?= $salidas->alicuota_iva->cellAttributes() ?>>
<span id="el_salidas_alicuota_iva">
<span<?= $salidas->alicuota_iva->viewAttributes() ?>>
<?= $salidas->alicuota_iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->iva->Visible) { // iva ?>
        <tr id="r_iva"<?= $salidas->iva->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->iva->caption() ?></td>
            <td<?= $salidas->iva->cellAttributes() ?>>
<span id="el_salidas_iva">
<span<?= $salidas->iva->viewAttributes() ?>>
<?= $salidas->iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->total->Visible) { // total ?>
        <tr id="r_total"<?= $salidas->total->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->total->caption() ?></td>
            <td<?= $salidas->total->cellAttributes() ?>>
<span id="el_salidas_total">
<span<?= $salidas->total->viewAttributes() ?>>
<?= $salidas->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->lista_pedido->Visible) { // lista_pedido ?>
        <tr id="r_lista_pedido"<?= $salidas->lista_pedido->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->lista_pedido->caption() ?></td>
            <td<?= $salidas->lista_pedido->cellAttributes() ?>>
<span id="el_salidas_lista_pedido">
<span<?= $salidas->lista_pedido->viewAttributes() ?>>
<?= $salidas->lista_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->nota->Visible) { // nota ?>
        <tr id="r_nota"<?= $salidas->nota->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->nota->caption() ?></td>
            <td<?= $salidas->nota->cellAttributes() ?>>
<span id="el_salidas_nota">
<span<?= $salidas->nota->viewAttributes() ?>>
<?= $salidas->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->_username->Visible) { // username ?>
        <tr id="r__username"<?= $salidas->_username->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->_username->caption() ?></td>
            <td<?= $salidas->_username->cellAttributes() ?>>
<span id="el_salidas__username">
<span<?= $salidas->_username->viewAttributes() ?>>
<?= $salidas->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $salidas->estatus->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->estatus->caption() ?></td>
            <td<?= $salidas->estatus->cellAttributes() ?>>
<span id="el_salidas_estatus">
<span<?= $salidas->estatus->viewAttributes() ?>>
<?= $salidas->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->asesor->Visible) { // asesor ?>
        <tr id="r_asesor"<?= $salidas->asesor->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->asesor->caption() ?></td>
            <td<?= $salidas->asesor->cellAttributes() ?>>
<span id="el_salidas_asesor">
<span<?= $salidas->asesor->viewAttributes() ?>>
<?= $salidas->asesor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->dias_credito->Visible) { // dias_credito ?>
        <tr id="r_dias_credito"<?= $salidas->dias_credito->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->dias_credito->caption() ?></td>
            <td<?= $salidas->dias_credito->cellAttributes() ?>>
<span id="el_salidas_dias_credito">
<span<?= $salidas->dias_credito->viewAttributes() ?>>
<?= $salidas->dias_credito->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->consignacion->Visible) { // consignacion ?>
        <tr id="r_consignacion"<?= $salidas->consignacion->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->consignacion->caption() ?></td>
            <td<?= $salidas->consignacion->cellAttributes() ?>>
<span id="el_salidas_consignacion">
<span<?= $salidas->consignacion->viewAttributes() ?>>
<?= $salidas->consignacion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $salidas->unidades->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->unidades->caption() ?></td>
            <td<?= $salidas->unidades->cellAttributes() ?>>
<span id="el_salidas_unidades">
<span<?= $salidas->unidades->viewAttributes() ?>>
<?= $salidas->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->factura->Visible) { // factura ?>
        <tr id="r_factura"<?= $salidas->factura->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->factura->caption() ?></td>
            <td<?= $salidas->factura->cellAttributes() ?>>
<span id="el_salidas_factura">
<span<?= $salidas->factura->viewAttributes() ?>>
<?= $salidas->factura->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $salidas->nombre->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->nombre->caption() ?></td>
            <td<?= $salidas->nombre->cellAttributes() ?>>
<span id="el_salidas_nombre">
<span<?= $salidas->nombre->viewAttributes() ?>>
<?= $salidas->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->comprobante->Visible) { // comprobante ?>
        <tr id="r_comprobante"<?= $salidas->comprobante->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->comprobante->caption() ?></td>
            <td<?= $salidas->comprobante->cellAttributes() ?>>
<span id="el_salidas_comprobante">
<span<?= $salidas->comprobante->viewAttributes() ?>>
<?php if (!EmptyString($salidas->comprobante->getViewValue()) && $salidas->comprobante->linkAttributes() != "") { ?>
<a<?= $salidas->comprobante->linkAttributes() ?>><?= $salidas->comprobante->getViewValue() ?></a>
<?php } else { ?>
<?= $salidas->comprobante->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->nro_despacho->Visible) { // nro_despacho ?>
        <tr id="r_nro_despacho"<?= $salidas->nro_despacho->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->nro_despacho->caption() ?></td>
            <td<?= $salidas->nro_despacho->cellAttributes() ?>>
<span id="el_salidas_nro_despacho">
<span<?= $salidas->nro_despacho->viewAttributes() ?>>
<?= $salidas->nro_despacho->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->asesor_asignado->Visible) { // asesor_asignado ?>
        <tr id="r_asesor_asignado"<?= $salidas->asesor_asignado->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->asesor_asignado->caption() ?></td>
            <td<?= $salidas->asesor_asignado->cellAttributes() ?>>
<span id="el_salidas_asesor_asignado">
<span<?= $salidas->asesor_asignado->viewAttributes() ?>>
<?= $salidas->asesor_asignado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->id_documento_padre->Visible) { // id_documento_padre ?>
        <tr id="r_id_documento_padre"<?= $salidas->id_documento_padre->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->id_documento_padre->caption() ?></td>
            <td<?= $salidas->id_documento_padre->cellAttributes() ?>>
<span id="el_salidas_id_documento_padre">
<span<?= $salidas->id_documento_padre->viewAttributes() ?>>
<?= $salidas->id_documento_padre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->archivo_pedido->Visible) { // archivo_pedido ?>
        <tr id="r_archivo_pedido"<?= $salidas->archivo_pedido->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->archivo_pedido->caption() ?></td>
            <td<?= $salidas->archivo_pedido->cellAttributes() ?>>
<span id="el_salidas_archivo_pedido">
<span<?= $salidas->archivo_pedido->viewAttributes() ?>>
<?= GetFileViewTag($salidas->archivo_pedido, $salidas->archivo_pedido->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->checker->Visible) { // checker ?>
        <tr id="r_checker"<?= $salidas->checker->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->checker->caption() ?></td>
            <td<?= $salidas->checker->cellAttributes() ?>>
<span id="el_salidas_checker">
<span<?= $salidas->checker->viewAttributes() ?>>
<?= $salidas->checker->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->checker_date->Visible) { // checker_date ?>
        <tr id="r_checker_date"<?= $salidas->checker_date->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->checker_date->caption() ?></td>
            <td<?= $salidas->checker_date->cellAttributes() ?>>
<span id="el_salidas_checker_date">
<span<?= $salidas->checker_date->viewAttributes() ?>>
<?= $salidas->checker_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->packer->Visible) { // packer ?>
        <tr id="r_packer"<?= $salidas->packer->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->packer->caption() ?></td>
            <td<?= $salidas->packer->cellAttributes() ?>>
<span id="el_salidas_packer">
<span<?= $salidas->packer->viewAttributes() ?>>
<?= $salidas->packer->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($salidas->packer_date->Visible) { // packer_date ?>
        <tr id="r_packer_date"<?= $salidas->packer_date->rowAttributes() ?>>
            <td class="<?= $salidas->TableLeftColumnClass ?>"><?= $salidas->packer_date->caption() ?></td>
            <td<?= $salidas->packer_date->cellAttributes() ?>>
<span id="el_salidas_packer_date">
<span<?= $salidas->packer_date->viewAttributes() ?>>
<?= $salidas->packer_date->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
