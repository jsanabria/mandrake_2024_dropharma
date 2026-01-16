<?php

namespace PHPMaker2024\mandrake;

// Table
$articulo = Container("articulo");
$articulo->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($articulo->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_articulomaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($articulo->foto->Visible) { // foto ?>
        <tr id="r_foto"<?= $articulo->foto->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->foto->caption() ?></td>
            <td<?= $articulo->foto->cellAttributes() ?>>
<span id="el_articulo_foto">
<span>
<?= GetFileViewTag($articulo->foto, $articulo->foto->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->codigo->Visible) { // codigo ?>
        <tr id="r_codigo"<?= $articulo->codigo->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->codigo->caption() ?></td>
            <td<?= $articulo->codigo->cellAttributes() ?>>
<span id="el_articulo_codigo">
<span<?= $articulo->codigo->viewAttributes() ?>>
<?= $articulo->codigo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->nombre_comercial->Visible) { // nombre_comercial ?>
        <tr id="r_nombre_comercial"<?= $articulo->nombre_comercial->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->nombre_comercial->caption() ?></td>
            <td<?= $articulo->nombre_comercial->cellAttributes() ?>>
<span id="el_articulo_nombre_comercial">
<span<?= $articulo->nombre_comercial->viewAttributes() ?>>
<?= $articulo->nombre_comercial->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->principio_activo->Visible) { // principio_activo ?>
        <tr id="r_principio_activo"<?= $articulo->principio_activo->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->principio_activo->caption() ?></td>
            <td<?= $articulo->principio_activo->cellAttributes() ?>>
<span id="el_articulo_principio_activo">
<span<?= $articulo->principio_activo->viewAttributes() ?>>
<?= $articulo->principio_activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->presentacion->Visible) { // presentacion ?>
        <tr id="r_presentacion"<?= $articulo->presentacion->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->presentacion->caption() ?></td>
            <td<?= $articulo->presentacion->cellAttributes() ?>>
<span id="el_articulo_presentacion">
<span<?= $articulo->presentacion->viewAttributes() ?>>
<?= $articulo->presentacion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->fabricante->Visible) { // fabricante ?>
        <tr id="r_fabricante"<?= $articulo->fabricante->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->fabricante->caption() ?></td>
            <td<?= $articulo->fabricante->cellAttributes() ?>>
<span id="el_articulo_fabricante">
<span<?= $articulo->fabricante->viewAttributes() ?>>
<?= $articulo->fabricante->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->categoria->Visible) { // categoria ?>
        <tr id="r_categoria"<?= $articulo->categoria->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->categoria->caption() ?></td>
            <td<?= $articulo->categoria->cellAttributes() ?>>
<span id="el_articulo_categoria">
<span<?= $articulo->categoria->viewAttributes() ?>>
<?= $articulo->categoria->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->lista_pedido->Visible) { // lista_pedido ?>
        <tr id="r_lista_pedido"<?= $articulo->lista_pedido->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->lista_pedido->caption() ?></td>
            <td<?= $articulo->lista_pedido->cellAttributes() ?>>
<span id="el_articulo_lista_pedido">
<span<?= $articulo->lista_pedido->viewAttributes() ?>>
<?= $articulo->lista_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_mano->Visible) { // cantidad_en_mano ?>
        <tr id="r_cantidad_en_mano"<?= $articulo->cantidad_en_mano->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_mano->caption() ?></td>
            <td<?= $articulo->cantidad_en_mano->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_mano">
<span<?= $articulo->cantidad_en_mano->viewAttributes() ?>>
<?= $articulo->cantidad_en_mano->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_almacenes->Visible) { // cantidad_en_almacenes ?>
        <tr id="r_cantidad_en_almacenes"<?= $articulo->cantidad_en_almacenes->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_almacenes->caption() ?></td>
            <td<?= $articulo->cantidad_en_almacenes->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_almacenes">
<span<?= $articulo->cantidad_en_almacenes->viewAttributes() ?>>
<?= $articulo->cantidad_en_almacenes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_pedido->Visible) { // cantidad_en_pedido ?>
        <tr id="r_cantidad_en_pedido"<?= $articulo->cantidad_en_pedido->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_pedido->caption() ?></td>
            <td<?= $articulo->cantidad_en_pedido->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_pedido">
<span<?= $articulo->cantidad_en_pedido->viewAttributes() ?>>
<?= $articulo->cantidad_en_pedido->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->cantidad_en_transito->Visible) { // cantidad_en_transito ?>
        <tr id="r_cantidad_en_transito"<?= $articulo->cantidad_en_transito->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->cantidad_en_transito->caption() ?></td>
            <td<?= $articulo->cantidad_en_transito->cellAttributes() ?>>
<span id="el_articulo_cantidad_en_transito">
<span<?= $articulo->cantidad_en_transito->viewAttributes() ?>>
<?= $articulo->cantidad_en_transito->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->descuento->Visible) { // descuento ?>
        <tr id="r_descuento"<?= $articulo->descuento->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->descuento->caption() ?></td>
            <td<?= $articulo->descuento->cellAttributes() ?>>
<span id="el_articulo_descuento">
<span<?= $articulo->descuento->viewAttributes() ?>>
<?= $articulo->descuento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($articulo->activo->Visible) { // activo ?>
        <tr id="r_activo"<?= $articulo->activo->rowAttributes() ?>>
            <td class="<?= $articulo->TableLeftColumnClass ?>"><?= $articulo->activo->caption() ?></td>
            <td<?= $articulo->activo->cellAttributes() ?>>
<span id="el_articulo_activo">
<span<?= $articulo->activo->viewAttributes() ?>>
<?= $articulo->activo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
