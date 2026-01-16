<?php

namespace PHPMaker2024\mandrake;

// Table
$usuario = Container("usuario");
$usuario->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($usuario->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_usuariomaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($usuario->_username->Visible) { // username ?>
        <tr id="r__username"<?= $usuario->_username->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->_username->caption() ?></td>
            <td<?= $usuario->_username->cellAttributes() ?>>
<span id="el_usuario__username">
<span<?= $usuario->_username->viewAttributes() ?>>
<?= $usuario->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->nombre->Visible) { // nombre ?>
        <tr id="r_nombre"<?= $usuario->nombre->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->nombre->caption() ?></td>
            <td<?= $usuario->nombre->cellAttributes() ?>>
<span id="el_usuario_nombre">
<span<?= $usuario->nombre->viewAttributes() ?>>
<?= $usuario->nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->userlevelid->Visible) { // userlevelid ?>
        <tr id="r_userlevelid"<?= $usuario->userlevelid->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->userlevelid->caption() ?></td>
            <td<?= $usuario->userlevelid->cellAttributes() ?>>
<span id="el_usuario_userlevelid">
<span<?= $usuario->userlevelid->viewAttributes() ?>>
<?= $usuario->userlevelid->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->asesor->Visible) { // asesor ?>
        <tr id="r_asesor"<?= $usuario->asesor->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->asesor->caption() ?></td>
            <td<?= $usuario->asesor->cellAttributes() ?>>
<span id="el_usuario_asesor">
<span<?= $usuario->asesor->viewAttributes() ?>>
<?= $usuario->asesor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $usuario->cliente->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->cliente->caption() ?></td>
            <td<?= $usuario->cliente->cellAttributes() ?>>
<span id="el_usuario_cliente">
<span<?= $usuario->cliente->viewAttributes() ?>>
<?= $usuario->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $usuario->proveedor->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->proveedor->caption() ?></td>
            <td<?= $usuario->proveedor->cellAttributes() ?>>
<span id="el_usuario_proveedor">
<span<?= $usuario->proveedor->viewAttributes() ?>>
<?= $usuario->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->foto->Visible) { // foto ?>
        <tr id="r_foto"<?= $usuario->foto->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->foto->caption() ?></td>
            <td<?= $usuario->foto->cellAttributes() ?>>
<span id="el_usuario_foto">
<span>
<?= GetFileViewTag($usuario->foto, $usuario->foto->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($usuario->activo->Visible) { // activo ?>
        <tr id="r_activo"<?= $usuario->activo->rowAttributes() ?>>
            <td class="<?= $usuario->TableLeftColumnClass ?>"><?= $usuario->activo->caption() ?></td>
            <td<?= $usuario->activo->cellAttributes() ?>>
<span id="el_usuario_activo">
<span<?= $usuario->activo->viewAttributes() ?>>
<?= $usuario->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
