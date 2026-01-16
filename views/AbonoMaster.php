<?php

namespace PHPMaker2024\mandrake;

// Table
$abono = Container("abono");
$abono->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($abono->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_abonomaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($abono->nro_recibo->Visible) { // nro_recibo ?>
        <tr id="r_nro_recibo"<?= $abono->nro_recibo->rowAttributes() ?>>
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->nro_recibo->caption() ?></td>
            <td<?= $abono->nro_recibo->cellAttributes() ?>>
<span id="el_abono_nro_recibo">
<span<?= $abono->nro_recibo->viewAttributes() ?>>
<?= $abono->nro_recibo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $abono->cliente->rowAttributes() ?>>
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->cliente->caption() ?></td>
            <td<?= $abono->cliente->cellAttributes() ?>>
<span id="el_abono_cliente">
<span<?= $abono->cliente->viewAttributes() ?>>
<?= $abono->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $abono->fecha->rowAttributes() ?>>
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->fecha->caption() ?></td>
            <td<?= $abono->fecha->cellAttributes() ?>>
<span id="el_abono_fecha">
<span<?= $abono->fecha->viewAttributes() ?>>
<?= $abono->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->pago->Visible) { // pago ?>
        <tr id="r_pago"<?= $abono->pago->rowAttributes() ?>>
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->pago->caption() ?></td>
            <td<?= $abono->pago->cellAttributes() ?>>
<span id="el_abono_pago">
<span<?= $abono->pago->viewAttributes() ?>>
<?= $abono->pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->tasa_usd->Visible) { // tasa_usd ?>
        <tr id="r_tasa_usd"<?= $abono->tasa_usd->rowAttributes() ?>>
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->tasa_usd->caption() ?></td>
            <td<?= $abono->tasa_usd->cellAttributes() ?>>
<span id="el_abono_tasa_usd">
<span<?= $abono->tasa_usd->viewAttributes() ?>>
<?= $abono->tasa_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($abono->_username->Visible) { // username ?>
        <tr id="r__username"<?= $abono->_username->rowAttributes() ?>>
            <td class="<?= $abono->TableLeftColumnClass ?>"><?= $abono->_username->caption() ?></td>
            <td<?= $abono->_username->cellAttributes() ?>>
<span id="el_abono__username">
<span<?= $abono->_username->viewAttributes() ?>>
<?= $abono->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
