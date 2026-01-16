<?php

namespace PHPMaker2021\mandrake;

// Table
$view_transferencias = Container("view_transferencias");
?>
<?php if ($view_transferencias->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_transferenciasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_transferencias->tipo_documento->Visible) { // tipo_documento ?>
        <tr id="r_tipo_documento">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->tipo_documento->caption() ?></td>
            <td <?= $view_transferencias->tipo_documento->cellAttributes() ?>>
<span id="el_view_transferencias_tipo_documento">
<span<?= $view_transferencias->tipo_documento->viewAttributes() ?>>
<?= $view_transferencias->tipo_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->_username->Visible) { // username ?>
        <tr id="r__username">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->_username->caption() ?></td>
            <td <?= $view_transferencias->_username->cellAttributes() ?>>
<span id="el_view_transferencias__username">
<span<?= $view_transferencias->_username->viewAttributes() ?>>
<?= $view_transferencias->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->fecha->Visible) { // fecha ?>
        <tr id="r_fecha">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->fecha->caption() ?></td>
            <td <?= $view_transferencias->fecha->cellAttributes() ?>>
<span id="el_view_transferencias_fecha">
<span<?= $view_transferencias->fecha->viewAttributes() ?>>
<?= $view_transferencias->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->nro_documento->caption() ?></td>
            <td <?= $view_transferencias->nro_documento->cellAttributes() ?>>
<span id="el_view_transferencias_nro_documento">
<span<?= $view_transferencias->nro_documento->viewAttributes() ?>>
<?= $view_transferencias->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->fabricante->Visible) { // fabricante ?>
        <tr id="r_fabricante">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->fabricante->caption() ?></td>
            <td <?= $view_transferencias->fabricante->cellAttributes() ?>>
<span id="el_view_transferencias_fabricante">
<span<?= $view_transferencias->fabricante->viewAttributes() ?>>
<?= $view_transferencias->fabricante->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->articulo->Visible) { // articulo ?>
        <tr id="r_articulo">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->articulo->caption() ?></td>
            <td <?= $view_transferencias->articulo->cellAttributes() ?>>
<span id="el_view_transferencias_articulo">
<span<?= $view_transferencias->articulo->viewAttributes() ?>>
<?= $view_transferencias->articulo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->lote->Visible) { // lote ?>
        <tr id="r_lote">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->lote->caption() ?></td>
            <td <?= $view_transferencias->lote->cellAttributes() ?>>
<span id="el_view_transferencias_lote">
<span<?= $view_transferencias->lote->viewAttributes() ?>>
<?= $view_transferencias->lote->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
        <tr id="r_fecha_vencimiento">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->fecha_vencimiento->caption() ?></td>
            <td <?= $view_transferencias->fecha_vencimiento->cellAttributes() ?>>
<span id="el_view_transferencias_fecha_vencimiento">
<span<?= $view_transferencias->fecha_vencimiento->viewAttributes() ?>>
<?= $view_transferencias->fecha_vencimiento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->cantidad_articulo->Visible) { // cantidad_articulo ?>
        <tr id="r_cantidad_articulo">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->cantidad_articulo->caption() ?></td>
            <td <?= $view_transferencias->cantidad_articulo->cellAttributes() ?>>
<span id="el_view_transferencias_cantidad_articulo">
<span<?= $view_transferencias->cantidad_articulo->viewAttributes() ?>>
<?= $view_transferencias->cantidad_articulo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_transferencias->almacen->Visible) { // almacen ?>
        <tr id="r_almacen">
            <td class="<?= $view_transferencias->TableLeftColumnClass ?>"><?= $view_transferencias->almacen->caption() ?></td>
            <td <?= $view_transferencias->almacen->cellAttributes() ?>>
<span id="el_view_transferencias_almacen">
<span<?= $view_transferencias->almacen->viewAttributes() ?>>
<?= $view_transferencias->almacen->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
