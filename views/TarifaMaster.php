<?php

namespace PHPMaker2024\mandrake;

// Table
$tarifa = Container("tarifa");
$tarifa->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($tarifa->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_tarifamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($tarifa->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $tarifa->nombre->rowAttributes() ?>>
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->nombre->caption() ?></td>
            <td<?= $tarifa->nombre->cellAttributes() ?>>
<span id="el_tarifa_nombre">
<span<?= $tarifa->nombre->viewAttributes() ?>>
<?= $tarifa->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($tarifa->patron->Visible) { // patron ?>
        <tr id="r_patron"<?= $tarifa->patron->rowAttributes() ?>>
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->patron->caption() ?></td>
            <td<?= $tarifa->patron->cellAttributes() ?>>
<span id="el_tarifa_patron">
<span<?= $tarifa->patron->viewAttributes() ?>>
<?= $tarifa->patron->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($tarifa->activo->Visible) { // activo ?>
        <tr id="r_activo"<?= $tarifa->activo->rowAttributes() ?>>
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->activo->caption() ?></td>
            <td<?= $tarifa->activo->cellAttributes() ?>>
<span id="el_tarifa_activo">
<span<?= $tarifa->activo->viewAttributes() ?>>
<?= $tarifa->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($tarifa->porcentaje->Visible) { // porcentaje ?>
        <tr id="r_porcentaje"<?= $tarifa->porcentaje->rowAttributes() ?>>
            <td class="<?= $tarifa->TableLeftColumnClass ?>"><?= $tarifa->porcentaje->caption() ?></td>
            <td<?= $tarifa->porcentaje->cellAttributes() ?>>
<span id="el_tarifa_porcentaje">
<span<?= $tarifa->porcentaje->viewAttributes() ?>>
<?= $tarifa->porcentaje->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
