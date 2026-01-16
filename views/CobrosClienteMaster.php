<?php

namespace PHPMaker2024\mandrake;

// Table
$cobros_cliente = Container("cobros_cliente");
$cobros_cliente->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($cobros_cliente->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cobros_clientemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cobros_cliente->id->Visible) { // id ?>
        <tr id="r_id"<?= $cobros_cliente->id->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->id->caption() ?></td>
            <td<?= $cobros_cliente->id->cellAttributes() ?>>
<span id="el_cobros_cliente_id">
<span<?= $cobros_cliente->id->viewAttributes() ?>>
<?= $cobros_cliente->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $cobros_cliente->cliente->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->cliente->caption() ?></td>
            <td<?= $cobros_cliente->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
<span<?= $cobros_cliente->cliente->viewAttributes() ?>>
<?= $cobros_cliente->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->tipo_pago->Visible) { // tipo_pago ?>
        <tr id="r_tipo_pago"<?= $cobros_cliente->tipo_pago->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->tipo_pago->caption() ?></td>
            <td<?= $cobros_cliente->tipo_pago->cellAttributes() ?>>
<span id="el_cobros_cliente_tipo_pago">
<span<?= $cobros_cliente->tipo_pago->viewAttributes() ?>>
<?= $cobros_cliente->tipo_pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->referencia->Visible) { // referencia ?>
        <tr id="r_referencia"<?= $cobros_cliente->referencia->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->referencia->caption() ?></td>
            <td<?= $cobros_cliente->referencia->cellAttributes() ?>>
<span id="el_cobros_cliente_referencia">
<span<?= $cobros_cliente->referencia->viewAttributes() ?>>
<?= $cobros_cliente->referencia->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->banco->Visible) { // banco ?>
        <tr id="r_banco"<?= $cobros_cliente->banco->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->banco->caption() ?></td>
            <td<?= $cobros_cliente->banco->cellAttributes() ?>>
<span id="el_cobros_cliente_banco">
<span<?= $cobros_cliente->banco->viewAttributes() ?>>
<?= $cobros_cliente->banco->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $cobros_cliente->fecha->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->fecha->caption() ?></td>
            <td<?= $cobros_cliente->fecha->cellAttributes() ?>>
<span id="el_cobros_cliente_fecha">
<span<?= $cobros_cliente->fecha->viewAttributes() ?>>
<?= $cobros_cliente->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $cobros_cliente->moneda->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->moneda->caption() ?></td>
            <td<?= $cobros_cliente->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
<span<?= $cobros_cliente->moneda->viewAttributes() ?>>
<?= $cobros_cliente->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->monto_recibido->Visible) { // monto_recibido ?>
        <tr id="r_monto_recibido"<?= $cobros_cliente->monto_recibido->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->monto_recibido->caption() ?></td>
            <td<?= $cobros_cliente->monto_recibido->cellAttributes() ?>>
<span id="el_cobros_cliente_monto_recibido">
<span<?= $cobros_cliente->monto_recibido->viewAttributes() ?>>
<?= $cobros_cliente->monto_recibido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cobros_cliente->monto->Visible) { // monto ?>
        <tr id="r_monto"<?= $cobros_cliente->monto->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->monto->caption() ?></td>
            <td<?= $cobros_cliente->monto->cellAttributes() ?>>
<span id="el_cobros_cliente_monto">
<span<?= $cobros_cliente->monto->viewAttributes() ?>>
<?= $cobros_cliente->monto->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
