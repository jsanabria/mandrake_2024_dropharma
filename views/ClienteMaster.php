<?php

namespace PHPMaker2024\mandrake;

// Table
$cliente = Container("cliente");
$cliente->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($cliente->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_clientemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cliente->id->Visible) { // id ?>
        <tr id="r_id"<?= $cliente->id->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->id->caption() ?></td>
            <td<?= $cliente->id->cellAttributes() ?>>
<span id="el_cliente_id">
<span<?= $cliente->id->viewAttributes() ?>>
<?= $cliente->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->ci_rif->Visible) { // ci_rif ?>
        <tr id="r_ci_rif"<?= $cliente->ci_rif->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->ci_rif->caption() ?></td>
            <td<?= $cliente->ci_rif->cellAttributes() ?>>
<span id="el_cliente_ci_rif">
<span<?= $cliente->ci_rif->viewAttributes() ?>>
<?= $cliente->ci_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $cliente->nombre->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->nombre->caption() ?></td>
            <td<?= $cliente->nombre->cellAttributes() ?>>
<span id="el_cliente_nombre">
<span<?= $cliente->nombre->viewAttributes() ?>>
<?= $cliente->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->sucursal->Visible) { // sucursal ?>
        <tr id="r_sucursal"<?= $cliente->sucursal->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->sucursal->caption() ?></td>
            <td<?= $cliente->sucursal->cellAttributes() ?>>
<span id="el_cliente_sucursal">
<span<?= $cliente->sucursal->viewAttributes() ?>>
<?= $cliente->sucursal->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->contacto->Visible) { // contacto ?>
        <tr id="r_contacto"<?= $cliente->contacto->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->contacto->caption() ?></td>
            <td<?= $cliente->contacto->cellAttributes() ?>>
<span id="el_cliente_contacto">
<span<?= $cliente->contacto->viewAttributes() ?>>
<?= $cliente->contacto->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->ciudad->Visible) { // ciudad ?>
        <tr id="r_ciudad"<?= $cliente->ciudad->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->ciudad->caption() ?></td>
            <td<?= $cliente->ciudad->cellAttributes() ?>>
<span id="el_cliente_ciudad">
<span<?= $cliente->ciudad->viewAttributes() ?>>
<?= $cliente->ciudad->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->zona->Visible) { // zona ?>
        <tr id="r_zona"<?= $cliente->zona->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->zona->caption() ?></td>
            <td<?= $cliente->zona->cellAttributes() ?>>
<span id="el_cliente_zona">
<span<?= $cliente->zona->viewAttributes() ?>>
<?= $cliente->zona->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->tipo_cliente->Visible) { // tipo_cliente ?>
        <tr id="r_tipo_cliente"<?= $cliente->tipo_cliente->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->tipo_cliente->caption() ?></td>
            <td<?= $cliente->tipo_cliente->cellAttributes() ?>>
<span id="el_cliente_tipo_cliente">
<span<?= $cliente->tipo_cliente->viewAttributes() ?>>
<?= $cliente->tipo_cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->tarifa->Visible) { // tarifa ?>
        <tr id="r_tarifa"<?= $cliente->tarifa->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->tarifa->caption() ?></td>
            <td<?= $cliente->tarifa->cellAttributes() ?>>
<span id="el_cliente_tarifa">
<span<?= $cliente->tarifa->viewAttributes() ?>>
<?= $cliente->tarifa->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->cuenta->Visible) { // cuenta ?>
        <tr id="r_cuenta"<?= $cliente->cuenta->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->cuenta->caption() ?></td>
            <td<?= $cliente->cuenta->cellAttributes() ?>>
<span id="el_cliente_cuenta">
<span<?= $cliente->cuenta->viewAttributes() ?>>
<?= $cliente->cuenta->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->activo->Visible) { // activo ?>
        <tr id="r_activo"<?= $cliente->activo->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->activo->caption() ?></td>
            <td<?= $cliente->activo->cellAttributes() ?>>
<span id="el_cliente_activo">
<span<?= $cliente->activo->viewAttributes() ?>>
<?= $cliente->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->dias_credito->Visible) { // dias_credito ?>
        <tr id="r_dias_credito"<?= $cliente->dias_credito->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->dias_credito->caption() ?></td>
            <td<?= $cliente->dias_credito->cellAttributes() ?>>
<span id="el_cliente_dias_credito">
<span<?= $cliente->dias_credito->viewAttributes() ?>>
<?= $cliente->dias_credito->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cliente->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $cliente->descuento->rowAttributes() ?>>
            <td class="<?= $cliente->TableLeftColumnClass ?>"><?= $cliente->descuento->caption() ?></td>
            <td<?= $cliente->descuento->cellAttributes() ?>>
<span id="el_cliente_descuento">
<span<?= $cliente->descuento->viewAttributes() ?>>
<?= $cliente->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
