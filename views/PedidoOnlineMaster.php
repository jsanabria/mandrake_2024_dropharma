<?php

namespace PHPMaker2024\mandrake;

// Table
$pedido_online = Container("pedido_online");
$pedido_online->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($pedido_online->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_pedido_onlinemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($pedido_online->id->Visible) { // id ?>
        <tr id="r_id"<?= $pedido_online->id->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->id->caption() ?></td>
            <td<?= $pedido_online->id->cellAttributes() ?>>
<span id="el_pedido_online_id">
<span<?= $pedido_online->id->viewAttributes() ?>>
<?= $pedido_online->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->tipo_documento->Visible) { // tipo_documento ?>
        <tr id="r_tipo_documento"<?= $pedido_online->tipo_documento->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->tipo_documento->caption() ?></td>
            <td<?= $pedido_online->tipo_documento->cellAttributes() ?>>
<span id="el_pedido_online_tipo_documento">
<span<?= $pedido_online->tipo_documento->viewAttributes() ?>>
<?= $pedido_online->tipo_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->asesor->Visible) { // asesor ?>
        <tr id="r_asesor"<?= $pedido_online->asesor->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->asesor->caption() ?></td>
            <td<?= $pedido_online->asesor->cellAttributes() ?>>
<span id="el_pedido_online_asesor">
<span<?= $pedido_online->asesor->viewAttributes() ?>>
<?= $pedido_online->asesor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $pedido_online->cliente->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->cliente->caption() ?></td>
            <td<?= $pedido_online->cliente->cellAttributes() ?>>
<span id="el_pedido_online_cliente">
<span<?= $pedido_online->cliente->viewAttributes() ?>>
<?= $pedido_online->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $pedido_online->fecha->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->fecha->caption() ?></td>
            <td<?= $pedido_online->fecha->cellAttributes() ?>>
<span id="el_pedido_online_fecha">
<span<?= $pedido_online->fecha->viewAttributes() ?>>
<?= $pedido_online->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->monto_total->Visible) { // monto_total ?>
        <tr id="r_monto_total"<?= $pedido_online->monto_total->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->monto_total->caption() ?></td>
            <td<?= $pedido_online->monto_total->cellAttributes() ?>>
<span id="el_pedido_online_monto_total">
<span<?= $pedido_online->monto_total->viewAttributes() ?>>
<?= $pedido_online->monto_total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->iva->Visible) { // iva ?>
        <tr id="r_iva"<?= $pedido_online->iva->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->iva->caption() ?></td>
            <td<?= $pedido_online->iva->cellAttributes() ?>>
<span id="el_pedido_online_iva">
<span<?= $pedido_online->iva->viewAttributes() ?>>
<?= $pedido_online->iva->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->total->Visible) { // total ?>
        <tr id="r_total"<?= $pedido_online->total->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->total->caption() ?></td>
            <td<?= $pedido_online->total->cellAttributes() ?>>
<span id="el_pedido_online_total">
<span<?= $pedido_online->total->viewAttributes() ?>>
<?= $pedido_online->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->nota->Visible) { // nota ?>
        <tr id="r_nota"<?= $pedido_online->nota->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->nota->caption() ?></td>
            <td<?= $pedido_online->nota->cellAttributes() ?>>
<span id="el_pedido_online_nota">
<span<?= $pedido_online->nota->viewAttributes() ?>>
<?= $pedido_online->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pedido_online->estatus->Visible) { // estatus ?>
        <tr id="r_estatus"<?= $pedido_online->estatus->rowAttributes() ?>>
            <td class="<?= $pedido_online->TableLeftColumnClass ?>"><?= $pedido_online->estatus->caption() ?></td>
            <td<?= $pedido_online->estatus->cellAttributes() ?>>
<span id="el_pedido_online_estatus">
<span<?= $pedido_online->estatus->viewAttributes() ?>>
<?= $pedido_online->estatus->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
