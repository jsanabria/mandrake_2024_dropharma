<?php

namespace PHPMaker2024\mandrake;

// Table
$cont_comprobante = Container("cont_comprobante");
$cont_comprobante->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($cont_comprobante->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cont_comprobantemaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cont_comprobante->id->Visible) { // id ?>
        <tr id="r_id"<?= $cont_comprobante->id->rowAttributes() ?>>
            <td class="<?= $cont_comprobante->TableLeftColumnClass ?>"><?= $cont_comprobante->id->caption() ?></td>
            <td<?= $cont_comprobante->id->cellAttributes() ?>>
<span id="el_cont_comprobante_id">
<span<?= $cont_comprobante->id->viewAttributes() ?>>
<?= $cont_comprobante->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_comprobante->tipo->Visible) { // tipo ?>
        <tr id="r_tipo"<?= $cont_comprobante->tipo->rowAttributes() ?>>
            <td class="<?= $cont_comprobante->TableLeftColumnClass ?>"><?= $cont_comprobante->tipo->caption() ?></td>
            <td<?= $cont_comprobante->tipo->cellAttributes() ?>>
<span id="el_cont_comprobante_tipo">
<span<?= $cont_comprobante->tipo->viewAttributes() ?>>
<?= $cont_comprobante->tipo->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_comprobante->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $cont_comprobante->fecha->rowAttributes() ?>>
            <td class="<?= $cont_comprobante->TableLeftColumnClass ?>"><?= $cont_comprobante->fecha->caption() ?></td>
            <td<?= $cont_comprobante->fecha->cellAttributes() ?>>
<span id="el_cont_comprobante_fecha">
<span<?= $cont_comprobante->fecha->viewAttributes() ?>>
<?= $cont_comprobante->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_comprobante->descripcion->Visible) { // descripcion ?>
        <tr id="r_descripcion"<?= $cont_comprobante->descripcion->rowAttributes() ?>>
            <td class="<?= $cont_comprobante->TableLeftColumnClass ?>"><?= $cont_comprobante->descripcion->caption() ?></td>
            <td<?= $cont_comprobante->descripcion->cellAttributes() ?>>
<span id="el_cont_comprobante_descripcion">
<span<?= $cont_comprobante->descripcion->viewAttributes() ?>>
<?= $cont_comprobante->descripcion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_comprobante->contabilizacion->Visible) { // contabilizacion ?>
        <tr id="r_contabilizacion"<?= $cont_comprobante->contabilizacion->rowAttributes() ?>>
            <td class="<?= $cont_comprobante->TableLeftColumnClass ?>"><?= $cont_comprobante->contabilizacion->caption() ?></td>
            <td<?= $cont_comprobante->contabilizacion->cellAttributes() ?>>
<span id="el_cont_comprobante_contabilizacion">
<span<?= $cont_comprobante->contabilizacion->viewAttributes() ?>>
<?= $cont_comprobante->contabilizacion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
