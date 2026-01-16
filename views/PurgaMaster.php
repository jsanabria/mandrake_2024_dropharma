<?php

namespace PHPMaker2024\mandrake;

// Table
$purga = Container("purga");
$purga->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($purga->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_purgamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($purga->id->Visible) { // id ?>
        <tr id="r_id"<?= $purga->id->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->id->caption() ?></td>
            <td<?= $purga->id->cellAttributes() ?>>
<span id="el_purga_id">
<span<?= $purga->id->viewAttributes() ?>>
<?= $purga->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($purga->_username->Visible) { // username ?>
        <tr id="r__username"<?= $purga->_username->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->_username->caption() ?></td>
            <td<?= $purga->_username->cellAttributes() ?>>
<span id="el_purga__username">
<span<?= $purga->_username->viewAttributes() ?>>
<?= $purga->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($purga->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $purga->fecha->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->fecha->caption() ?></td>
            <td<?= $purga->fecha->cellAttributes() ?>>
<span id="el_purga_fecha">
<span<?= $purga->fecha->viewAttributes() ?>>
<?= $purga->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($purga->procesado->Visible) { // procesado ?>
        <tr id="r_procesado"<?= $purga->procesado->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->procesado->caption() ?></td>
            <td<?= $purga->procesado->cellAttributes() ?>>
<span id="el_purga_procesado">
<span<?= $purga->procesado->viewAttributes() ?>>
<?= $purga->procesado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($purga->username_procesa->Visible) { // username_procesa ?>
        <tr id="r_username_procesa"<?= $purga->username_procesa->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->username_procesa->caption() ?></td>
            <td<?= $purga->username_procesa->cellAttributes() ?>>
<span id="el_purga_username_procesa">
<span<?= $purga->username_procesa->viewAttributes() ?>>
<?= $purga->username_procesa->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($purga->salidas->Visible) { // salidas ?>
        <tr id="r_salidas"<?= $purga->salidas->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->salidas->caption() ?></td>
            <td<?= $purga->salidas->cellAttributes() ?>>
<span id="el_purga_salidas">
<span<?= $purga->salidas->viewAttributes() ?>>
<?php if (!EmptyString($purga->salidas->getViewValue()) && $purga->salidas->linkAttributes() != "") { ?>
<a<?= $purga->salidas->linkAttributes() ?>><?= $purga->salidas->getViewValue() ?></a>
<?php } else { ?>
<?= $purga->salidas->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($purga->entradas->Visible) { // entradas ?>
        <tr id="r_entradas"<?= $purga->entradas->rowAttributes() ?>>
            <td class="<?= $purga->TableLeftColumnClass ?>"><?= $purga->entradas->caption() ?></td>
            <td<?= $purga->entradas->cellAttributes() ?>>
<span id="el_purga_entradas">
<span<?= $purga->entradas->viewAttributes() ?>>
<?php if (!EmptyString($purga->entradas->getViewValue()) && $purga->entradas->linkAttributes() != "") { ?>
<a<?= $purga->entradas->linkAttributes() ?>><?= $purga->entradas->getViewValue() ?></a>
<?php } else { ?>
<?= $purga->entradas->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
