<?php

namespace PHPMaker2024\mandrake;

// Table
$asesor = Container("asesor");
$asesor->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($asesor->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_asesormaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($asesor->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif"<?= $asesor->ci_rif->rowAttributes() ?>>
            <td class="<?= $asesor->TableLeftColumnClass ?>"><?= $asesor->ci_rif->caption() ?></td>
            <td<?= $asesor->ci_rif->cellAttributes() ?>>
<span id="el_asesor_ci_rif">
<span<?= $asesor->ci_rif->viewAttributes() ?>>
<?= $asesor->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($asesor->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $asesor->nombre->rowAttributes() ?>>
            <td class="<?= $asesor->TableLeftColumnClass ?>"><?= $asesor->nombre->caption() ?></td>
            <td<?= $asesor->nombre->cellAttributes() ?>>
<span id="el_asesor_nombre">
<span<?= $asesor->nombre->viewAttributes() ?>>
<?= $asesor->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($asesor->ciudad->Visible) { // ciudad ?>
        <tr id="r_ciudad"<?= $asesor->ciudad->rowAttributes() ?>>
            <td class="<?= $asesor->TableLeftColumnClass ?>"><?= $asesor->ciudad->caption() ?></td>
            <td<?= $asesor->ciudad->cellAttributes() ?>>
<span id="el_asesor_ciudad">
<span<?= $asesor->ciudad->viewAttributes() ?>>
<?= $asesor->ciudad->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($asesor->activo->Visible) { // activo ?>
        <tr id="r_activo"<?= $asesor->activo->rowAttributes() ?>>
            <td class="<?= $asesor->TableLeftColumnClass ?>"><?= $asesor->activo->caption() ?></td>
            <td<?= $asesor->activo->cellAttributes() ?>>
<span id="el_asesor_activo">
<span<?= $asesor->activo->viewAttributes() ?>>
<?= $asesor->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
