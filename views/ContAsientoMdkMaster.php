<?php

namespace PHPMaker2024\mandrake;

// Table
$cont_asiento_mdk = Container("cont_asiento_mdk");
$cont_asiento_mdk->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($cont_asiento_mdk->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cont_asiento_mdkmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cont_asiento_mdk->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $cont_asiento_mdk->fecha->rowAttributes() ?>>
            <td class="<?= $cont_asiento_mdk->TableLeftColumnClass ?>"><?= $cont_asiento_mdk->fecha->caption() ?></td>
            <td<?= $cont_asiento_mdk->fecha->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_fecha">
<span<?= $cont_asiento_mdk->fecha->viewAttributes() ?>>
<?= $cont_asiento_mdk->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_asiento_mdk->referencia->Visible) { // referencia ?>
        <tr id="r_referencia"<?= $cont_asiento_mdk->referencia->rowAttributes() ?>>
            <td class="<?= $cont_asiento_mdk->TableLeftColumnClass ?>"><?= $cont_asiento_mdk->referencia->caption() ?></td>
            <td<?= $cont_asiento_mdk->referencia->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_referencia">
<span<?= $cont_asiento_mdk->referencia->viewAttributes() ?>>
<?= $cont_asiento_mdk->referencia->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_asiento_mdk->modulo_origen->Visible) { // modulo_origen ?>
        <tr id="r_modulo_origen"<?= $cont_asiento_mdk->modulo_origen->rowAttributes() ?>>
            <td class="<?= $cont_asiento_mdk->TableLeftColumnClass ?>"><?= $cont_asiento_mdk->modulo_origen->caption() ?></td>
            <td<?= $cont_asiento_mdk->modulo_origen->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_modulo_origen">
<span<?= $cont_asiento_mdk->modulo_origen->viewAttributes() ?>>
<?= $cont_asiento_mdk->modulo_origen->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_asiento_mdk->total_bs->Visible) { // total_bs ?>
        <tr id="r_total_bs"<?= $cont_asiento_mdk->total_bs->rowAttributes() ?>>
            <td class="<?= $cont_asiento_mdk->TableLeftColumnClass ?>"><?= $cont_asiento_mdk->total_bs->caption() ?></td>
            <td<?= $cont_asiento_mdk->total_bs->cellAttributes() ?>>
<span id="el_cont_asiento_mdk_total_bs">
<span<?= $cont_asiento_mdk->total_bs->viewAttributes() ?>>
<?= $cont_asiento_mdk->total_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
