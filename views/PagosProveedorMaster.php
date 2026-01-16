<?php

namespace PHPMaker2024\mandrake;

// Table
$pagos_proveedor = Container("pagos_proveedor");
$pagos_proveedor->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($pagos_proveedor->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_pagos_proveedormaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($pagos_proveedor->id->Visible) { // id ?>
        <tr id="r_id"<?= $pagos_proveedor->id->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->id->caption() ?></td>
            <td<?= $pagos_proveedor->id->cellAttributes() ?>>
<span id="el_pagos_proveedor_id">
<span<?= $pagos_proveedor->id->viewAttributes() ?>>
<?= $pagos_proveedor->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->proveedor->Visible) { // proveedor ?>
        <tr id="r_proveedor"<?= $pagos_proveedor->proveedor->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->proveedor->caption() ?></td>
            <td<?= $pagos_proveedor->proveedor->cellAttributes() ?>>
<span id="el_pagos_proveedor_proveedor">
<span<?= $pagos_proveedor->proveedor->viewAttributes() ?>>
<?= $pagos_proveedor->proveedor->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->tipo_pago->Visible) { // tipo_pago ?>
        <tr id="r_tipo_pago"<?= $pagos_proveedor->tipo_pago->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->tipo_pago->caption() ?></td>
            <td<?= $pagos_proveedor->tipo_pago->cellAttributes() ?>>
<span id="el_pagos_proveedor_tipo_pago">
<span<?= $pagos_proveedor->tipo_pago->viewAttributes() ?>>
<?= $pagos_proveedor->tipo_pago->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->banco->Visible) { // banco ?>
        <tr id="r_banco"<?= $pagos_proveedor->banco->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->banco->caption() ?></td>
            <td<?= $pagos_proveedor->banco->cellAttributes() ?>>
<span id="el_pagos_proveedor_banco">
<span<?= $pagos_proveedor->banco->viewAttributes() ?>>
<?= $pagos_proveedor->banco->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $pagos_proveedor->fecha->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->fecha->caption() ?></td>
            <td<?= $pagos_proveedor->fecha->cellAttributes() ?>>
<span id="el_pagos_proveedor_fecha">
<span<?= $pagos_proveedor->fecha->viewAttributes() ?>>
<?= $pagos_proveedor->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->referencia->Visible) { // referencia ?>
        <tr id="r_referencia"<?= $pagos_proveedor->referencia->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->referencia->caption() ?></td>
            <td<?= $pagos_proveedor->referencia->cellAttributes() ?>>
<span id="el_pagos_proveedor_referencia">
<span<?= $pagos_proveedor->referencia->viewAttributes() ?>>
<?= $pagos_proveedor->referencia->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $pagos_proveedor->moneda->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->moneda->caption() ?></td>
            <td<?= $pagos_proveedor->moneda->cellAttributes() ?>>
<span id="el_pagos_proveedor_moneda">
<span<?= $pagos_proveedor->moneda->viewAttributes() ?>>
<?= $pagos_proveedor->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->monto_dado->Visible) { // monto_dado ?>
        <tr id="r_monto_dado"<?= $pagos_proveedor->monto_dado->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->monto_dado->caption() ?></td>
            <td<?= $pagos_proveedor->monto_dado->cellAttributes() ?>>
<span id="el_pagos_proveedor_monto_dado">
<span<?= $pagos_proveedor->monto_dado->viewAttributes() ?>>
<?= $pagos_proveedor->monto_dado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($pagos_proveedor->monto->Visible) { // monto ?>
        <tr id="r_monto"<?= $pagos_proveedor->monto->rowAttributes() ?>>
            <td class="<?= $pagos_proveedor->TableLeftColumnClass ?>"><?= $pagos_proveedor->monto->caption() ?></td>
            <td<?= $pagos_proveedor->monto->cellAttributes() ?>>
<span id="el_pagos_proveedor_monto">
<span<?= $pagos_proveedor->monto->viewAttributes() ?>>
<?= $pagos_proveedor->monto->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
