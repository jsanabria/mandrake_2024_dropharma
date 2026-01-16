<?php

namespace PHPMaker2024\mandrake;

// Table
$compania = Container("compania");
$compania->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($compania->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_companiamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($compania->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif"<?= $compania->ci_rif->rowAttributes() ?>>
            <td class="<?= $compania->TableLeftColumnClass ?>"><?= $compania->ci_rif->caption() ?></td>
            <td<?= $compania->ci_rif->cellAttributes() ?>>
<span id="el_compania_ci_rif">
<span<?= $compania->ci_rif->viewAttributes() ?>>
<?= $compania->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($compania->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $compania->nombre->rowAttributes() ?>>
            <td class="<?= $compania->TableLeftColumnClass ?>"><?= $compania->nombre->caption() ?></td>
            <td<?= $compania->nombre->cellAttributes() ?>>
<span id="el_compania_nombre">
<span<?= $compania->nombre->viewAttributes() ?>>
<?= $compania->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($compania->ciudad->Visible) { // ciudad ?>
        <tr id="r_ciudad"<?= $compania->ciudad->rowAttributes() ?>>
            <td class="<?= $compania->TableLeftColumnClass ?>"><?= $compania->ciudad->caption() ?></td>
            <td<?= $compania->ciudad->cellAttributes() ?>>
<span id="el_compania_ciudad">
<span<?= $compania->ciudad->viewAttributes() ?>>
<?= $compania->ciudad->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($compania->email1->Visible) { // email1 ?>
        <tr id="r_email1"<?= $compania->email1->rowAttributes() ?>>
            <td class="<?= $compania->TableLeftColumnClass ?>"><?= $compania->email1->caption() ?></td>
            <td<?= $compania->email1->cellAttributes() ?>>
<span id="el_compania_email1">
<span<?= $compania->email1->viewAttributes() ?>>
<?= $compania->email1->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($compania->agente_retencion->Visible) { // agente_retencion ?>
        <tr id="r_agente_retencion"<?= $compania->agente_retencion->rowAttributes() ?>>
            <td class="<?= $compania->TableLeftColumnClass ?>"><?= $compania->agente_retencion->caption() ?></td>
            <td<?= $compania->agente_retencion->cellAttributes() ?>>
<span id="el_compania_agente_retencion">
<span<?= $compania->agente_retencion->viewAttributes() ?>>
<?= $compania->agente_retencion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($compania->logo->Visible) { // logo ?>
        <tr id="r_logo"<?= $compania->logo->rowAttributes() ?>>
            <td class="<?= $compania->TableLeftColumnClass ?>"><?= $compania->logo->caption() ?></td>
            <td<?= $compania->logo->cellAttributes() ?>>
<span id="el_compania_logo">
<span>
<?= GetFileViewTag($compania->logo, $compania->logo->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
