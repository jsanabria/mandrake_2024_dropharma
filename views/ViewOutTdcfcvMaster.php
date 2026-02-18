<?php

namespace PHPMaker2024\mandrake;

// Table
$view_out_tdcfcv = Container("view_out_tdcfcv");
$view_out_tdcfcv->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_out_tdcfcv->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_out_tdcfcvmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_out_tdcfcv->documento->Visible) { // documento ?>
        <tr id="r_documento"<?= $view_out_tdcfcv->documento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->documento->caption() ?></td>
            <td<?= $view_out_tdcfcv->documento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_documento">
<span<?= $view_out_tdcfcv->documento->viewAttributes() ?>>
<?= $view_out_tdcfcv->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_out_tdcfcv->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->nro_documento->caption() ?></td>
            <td<?= $view_out_tdcfcv->nro_documento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_documento">
<span<?= $view_out_tdcfcv->nro_documento->viewAttributes() ?>>
<?= $view_out_tdcfcv->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_out_tdcfcv->fecha->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->fecha->caption() ?></td>
            <td<?= $view_out_tdcfcv->fecha->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_fecha">
<span<?= $view_out_tdcfcv->fecha->viewAttributes() ?>>
<?= $view_out_tdcfcv->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->nro_control->Visible) { // nro_control ?>
        <tr id="r_nro_control"<?= $view_out_tdcfcv->nro_control->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->nro_control->caption() ?></td>
            <td<?= $view_out_tdcfcv->nro_control->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_nro_control">
<span<?= $view_out_tdcfcv->nro_control->viewAttributes() ?>>
<?= $view_out_tdcfcv->nro_control->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $view_out_tdcfcv->cliente->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->cliente->caption() ?></td>
            <td<?= $view_out_tdcfcv->cliente->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_cliente">
<span<?= $view_out_tdcfcv->cliente->viewAttributes() ?>>
<?= $view_out_tdcfcv->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->doc_afectado->Visible) { // doc_afectado ?>
        <tr id="r_doc_afectado"<?= $view_out_tdcfcv->doc_afectado->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->doc_afectado->caption() ?></td>
            <td<?= $view_out_tdcfcv->doc_afectado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_doc_afectado">
<span<?= $view_out_tdcfcv->doc_afectado->viewAttributes() ?>>
<?= $view_out_tdcfcv->doc_afectado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_out_tdcfcv->total->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->total->caption() ?></td>
            <td<?= $view_out_tdcfcv->total->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_total">
<span<?= $view_out_tdcfcv->total->viewAttributes() ?>>
<?= $view_out_tdcfcv->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->igtf->Visible) { // igtf ?>
        <tr id="r_igtf"<?= $view_out_tdcfcv->igtf->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->igtf->caption() ?></td>
            <td<?= $view_out_tdcfcv->igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_igtf">
<span<?= $view_out_tdcfcv->igtf->viewAttributes() ?>>
<?= $view_out_tdcfcv->igtf->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->monto_base_igtf->Visible) { // monto_base_igtf ?>
        <tr id="r_monto_base_igtf"<?= $view_out_tdcfcv->monto_base_igtf->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->monto_base_igtf->caption() ?></td>
            <td<?= $view_out_tdcfcv->monto_base_igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_base_igtf">
<span<?= $view_out_tdcfcv->monto_base_igtf->viewAttributes() ?>>
<?= $view_out_tdcfcv->monto_base_igtf->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->monto_igtf->Visible) { // monto_igtf ?>
        <tr id="r_monto_igtf"<?= $view_out_tdcfcv->monto_igtf->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->monto_igtf->caption() ?></td>
            <td<?= $view_out_tdcfcv->monto_igtf->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_monto_igtf">
<span<?= $view_out_tdcfcv->monto_igtf->viewAttributes() ?>>
<?= $view_out_tdcfcv->monto_igtf->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_out_tdcfcv->moneda->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->moneda->caption() ?></td>
            <td<?= $view_out_tdcfcv->moneda->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_moneda">
<span<?= $view_out_tdcfcv->moneda->viewAttributes() ?>>
<?= $view_out_tdcfcv->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_out_tdcfcv->unidades->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->unidades->caption() ?></td>
            <td<?= $view_out_tdcfcv->unidades->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_unidades">
<span<?= $view_out_tdcfcv->unidades->viewAttributes() ?>>
<?= $view_out_tdcfcv->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $view_out_tdcfcv->estatus->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->estatus->caption() ?></td>
            <td<?= $view_out_tdcfcv->estatus->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_estatus">
<span<?= $view_out_tdcfcv->estatus->viewAttributes() ?>>
<?= $view_out_tdcfcv->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->id_documento_padre->Visible) { // id_documento_padre ?>
        <tr id="r_id_documento_padre"<?= $view_out_tdcfcv->id_documento_padre->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->id_documento_padre->caption() ?></td>
            <td<?= $view_out_tdcfcv->id_documento_padre->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_id_documento_padre">
<span<?= $view_out_tdcfcv->id_documento_padre->viewAttributes() ?>>
<?= $view_out_tdcfcv->id_documento_padre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $view_out_tdcfcv->descuento->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->descuento->caption() ?></td>
            <td<?= $view_out_tdcfcv->descuento->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_descuento">
<span<?= $view_out_tdcfcv->descuento->viewAttributes() ?>>
<?= $view_out_tdcfcv->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->descuento2->Visible) { // descuento2 ?>
        <tr id="r_descuento2"<?= $view_out_tdcfcv->descuento2->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->descuento2->caption() ?></td>
            <td<?= $view_out_tdcfcv->descuento2->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_descuento2">
<span<?= $view_out_tdcfcv->descuento2->viewAttributes() ?>>
<?= $view_out_tdcfcv->descuento2->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_out_tdcfcv->asesor_asignado->Visible) { // asesor_asignado ?>
        <tr id="r_asesor_asignado"<?= $view_out_tdcfcv->asesor_asignado->rowAttributes() ?>>
            <td class="<?= $view_out_tdcfcv->TableLeftColumnClass ?>"><?= $view_out_tdcfcv->asesor_asignado->caption() ?></td>
            <td<?= $view_out_tdcfcv->asesor_asignado->cellAttributes() ?>>
<span id="el_view_out_tdcfcv_asesor_asignado">
<span<?= $view_out_tdcfcv->asesor_asignado->viewAttributes() ?>>
<?= $view_out_tdcfcv->asesor_asignado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
