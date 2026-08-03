<?php

namespace PHPMaker2024\mandrake;

// Table
$pagos_compras = Container("pagos_compras");
$pagos_compras->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($pagos_compras->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_pagos_comprasmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($pagos_compras->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $pagos_compras->proveedor->rowAttributes() ?>>
            <td class="<?= $pagos_compras->TableLeftColumnClass ?>"><?= $pagos_compras->proveedor->caption() ?></td>
            <td<?= $pagos_compras->proveedor->cellAttributes() ?>>
<span id="el_pagos_compras_proveedor">
<span<?= $pagos_compras->proveedor->viewAttributes() ?>>
<?= $pagos_compras->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_compras->id_documento->Visible) { // id_documento ?>
        <tr id="r_id_documento"<?= $pagos_compras->id_documento->rowAttributes() ?>>
            <td class="<?= $pagos_compras->TableLeftColumnClass ?>"><?= $pagos_compras->id_documento->caption() ?></td>
            <td<?= $pagos_compras->id_documento->cellAttributes() ?>>
<span id="el_pagos_compras_id_documento">
<span<?= $pagos_compras->id_documento->viewAttributes() ?>>
<?= $pagos_compras->id_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_compras->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $pagos_compras->fecha->rowAttributes() ?>>
            <td class="<?= $pagos_compras->TableLeftColumnClass ?>"><?= $pagos_compras->fecha->caption() ?></td>
            <td<?= $pagos_compras->fecha->cellAttributes() ?>>
<span id="el_pagos_compras_fecha">
<span<?= $pagos_compras->fecha->viewAttributes() ?>>
<?= $pagos_compras->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_compras->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $pagos_compras->moneda->rowAttributes() ?>>
            <td class="<?= $pagos_compras->TableLeftColumnClass ?>"><?= $pagos_compras->moneda->caption() ?></td>
            <td<?= $pagos_compras->moneda->cellAttributes() ?>>
<span id="el_pagos_compras_moneda">
<span<?= $pagos_compras->moneda->viewAttributes() ?>>
<?= $pagos_compras->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_compras->pago->Visible) { // pago ?>
        <tr id="r_pago"<?= $pagos_compras->pago->rowAttributes() ?>>
            <td class="<?= $pagos_compras->TableLeftColumnClass ?>"><?= $pagos_compras->pago->caption() ?></td>
            <td<?= $pagos_compras->pago->cellAttributes() ?>>
<span id="el_pagos_compras_pago">
<span<?= $pagos_compras->pago->viewAttributes() ?>>
<?= $pagos_compras->pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
