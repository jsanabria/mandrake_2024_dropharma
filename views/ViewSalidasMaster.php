<?php

namespace PHPMaker2024\mandrake;

// Table
$view_salidas = Container("view_salidas");
$view_salidas->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_salidas->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_salidasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_salidas->nombre_documento->Visible) { // nombre_documento ?>
        <tr id="r_nombre_documento"<?= $view_salidas->nombre_documento->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->nombre_documento->caption() ?></td>
            <td<?= $view_salidas->nombre_documento->cellAttributes() ?>>
<span id="el_view_salidas_nombre_documento">
<span<?= $view_salidas->nombre_documento->viewAttributes() ?>>
<?= $view_salidas->nombre_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->cliente->Visible) { // cliente ?>
        <tr id="r_cliente"<?= $view_salidas->cliente->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->cliente->caption() ?></td>
            <td<?= $view_salidas->cliente->cellAttributes() ?>>
<span id="el_view_salidas_cliente">
<span<?= $view_salidas->cliente->viewAttributes() ?>>
<?= $view_salidas->cliente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_salidas->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->nro_documento->caption() ?></td>
            <td<?= $view_salidas->nro_documento->cellAttributes() ?>>
<span id="el_view_salidas_nro_documento">
<span<?= $view_salidas->nro_documento->viewAttributes() ?>>
<?= $view_salidas->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_salidas->fecha->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->fecha->caption() ?></td>
            <td<?= $view_salidas->fecha->cellAttributes() ?>>
<span id="el_view_salidas_fecha">
<span<?= $view_salidas->fecha->viewAttributes() ?>>
<?= $view_salidas->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->nota->Visible) { // nota ?>
        <tr id="r_nota"<?= $view_salidas->nota->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->nota->caption() ?></td>
            <td<?= $view_salidas->nota->cellAttributes() ?>>
<span id="el_view_salidas_nota">
<span<?= $view_salidas->nota->viewAttributes() ?>>
<?= $view_salidas->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->consignacion->Visible) { // consignacion ?>
        <tr id="r_consignacion"<?= $view_salidas->consignacion->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->consignacion->caption() ?></td>
            <td<?= $view_salidas->consignacion->cellAttributes() ?>>
<span id="el_view_salidas_consignacion">
<span<?= $view_salidas->consignacion->viewAttributes() ?>>
<?= $view_salidas->consignacion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->tasa_dia->Visible) { // tasa_dia ?>
        <tr id="r_tasa_dia"<?= $view_salidas->tasa_dia->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->tasa_dia->caption() ?></td>
            <td<?= $view_salidas->tasa_dia->cellAttributes() ?>>
<span id="el_view_salidas_tasa_dia">
<span<?= $view_salidas->tasa_dia->viewAttributes() ?>>
<?= $view_salidas->tasa_dia->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_salidas->unidades->Visible) { // unidades ?>
        <tr id="r_unidades"<?= $view_salidas->unidades->rowAttributes() ?>>
            <td class="<?= $view_salidas->TableLeftColumnClass ?>"><?= $view_salidas->unidades->caption() ?></td>
            <td<?= $view_salidas->unidades->cellAttributes() ?>>
<span id="el_view_salidas_unidades">
<span<?= $view_salidas->unidades->viewAttributes() ?>>
<?= $view_salidas->unidades->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
