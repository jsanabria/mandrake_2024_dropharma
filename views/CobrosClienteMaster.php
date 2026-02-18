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
<?php if ($cobros_cliente->id_documento->Visible) { // id_documento ?>
        <tr id="r_id_documento"<?= $cobros_cliente->id_documento->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->id_documento->caption() ?></td>
            <td<?= $cobros_cliente->id_documento->cellAttributes() ?>>
<span id="el_cobros_cliente_id_documento">
<span<?= $cobros_cliente->id_documento->viewAttributes() ?>>
<?= $cobros_cliente->id_documento->getViewValue() ?></span>
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
<?php if ($cobros_cliente->pago->Visible) { // pago ?>
        <tr id="r_pago"<?= $cobros_cliente->pago->rowAttributes() ?>>
            <td class="<?= $cobros_cliente->TableLeftColumnClass ?>"><?= $cobros_cliente->pago->caption() ?></td>
            <td<?= $cobros_cliente->pago->cellAttributes() ?>>
<span id="el_cobros_cliente_pago">
<span<?= $cobros_cliente->pago->viewAttributes() ?>>
<?= $cobros_cliente->pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
