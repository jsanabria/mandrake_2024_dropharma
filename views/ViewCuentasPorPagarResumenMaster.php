<?php

namespace PHPMaker2024\mandrake;

// Table
$view_cuentas_por_pagar_resumen = Container("view_cuentas_por_pagar_resumen");
$view_cuentas_por_pagar_resumen->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_cuentas_por_pagar_resumen->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_cuentas_por_pagar_resumenmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_cuentas_por_pagar_resumen->proveedor_rif->Visible) { // proveedor_rif ?>
        <tr id="r_proveedor_rif"<?= $view_cuentas_por_pagar_resumen->proveedor_rif->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->proveedor_rif->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->proveedor_rif->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_proveedor_rif">
<span<?= $view_cuentas_por_pagar_resumen->proveedor_rif->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->proveedor_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->proveedor_nombre->Visible) { // proveedor_nombre ?>
        <tr id="r_proveedor_nombre"<?= $view_cuentas_por_pagar_resumen->proveedor_nombre->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->proveedor_nombre->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->proveedor_nombre->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_proveedor_nombre">
<span<?= $view_cuentas_por_pagar_resumen->proveedor_nombre->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->proveedor_nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->cantidad_documentos->Visible) { // cantidad_documentos ?>
        <tr id="r_cantidad_documentos"<?= $view_cuentas_por_pagar_resumen->cantidad_documentos->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->cantidad_documentos->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->cantidad_documentos->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_cantidad_documentos">
<span<?= $view_cuentas_por_pagar_resumen->cantidad_documentos->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->cantidad_documentos->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->documentos_pendientes->Visible) { // documentos_pendientes ?>
        <tr id="r_documentos_pendientes"<?= $view_cuentas_por_pagar_resumen->documentos_pendientes->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->documentos_pendientes->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->documentos_pendientes->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_documentos_pendientes">
<span<?= $view_cuentas_por_pagar_resumen->documentos_pendientes->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->documentos_pendientes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->documentos_parciales->Visible) { // documentos_parciales ?>
        <tr id="r_documentos_parciales"<?= $view_cuentas_por_pagar_resumen->documentos_parciales->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->documentos_parciales->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->documentos_parciales->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_documentos_parciales">
<span<?= $view_cuentas_por_pagar_resumen->documentos_parciales->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->documentos_parciales->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->monto_documentos_bs->Visible) { // monto_documentos_bs ?>
        <tr id="r_monto_documentos_bs"<?= $view_cuentas_por_pagar_resumen->monto_documentos_bs->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->monto_documentos_bs->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->monto_documentos_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_monto_documentos_bs">
<span<?= $view_cuentas_por_pagar_resumen->monto_documentos_bs->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->monto_documentos_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->monto_documentos_usd->Visible) { // monto_documentos_usd ?>
        <tr id="r_monto_documentos_usd"<?= $view_cuentas_por_pagar_resumen->monto_documentos_usd->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->monto_documentos_usd->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->monto_documentos_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_monto_documentos_usd">
<span<?= $view_cuentas_por_pagar_resumen->monto_documentos_usd->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->monto_documentos_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->total_pagado_bs->Visible) { // total_pagado_bs ?>
        <tr id="r_total_pagado_bs"<?= $view_cuentas_por_pagar_resumen->total_pagado_bs->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->total_pagado_bs->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->total_pagado_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_total_pagado_bs">
<span<?= $view_cuentas_por_pagar_resumen->total_pagado_bs->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->total_pagado_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->total_pagado_usd->Visible) { // total_pagado_usd ?>
        <tr id="r_total_pagado_usd"<?= $view_cuentas_por_pagar_resumen->total_pagado_usd->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->total_pagado_usd->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->total_pagado_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_total_pagado_usd">
<span<?= $view_cuentas_por_pagar_resumen->total_pagado_usd->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->total_pagado_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->saldo_bs->Visible) { // saldo_bs ?>
        <tr id="r_saldo_bs"<?= $view_cuentas_por_pagar_resumen->saldo_bs->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->saldo_bs->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->saldo_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_saldo_bs">
<span<?= $view_cuentas_por_pagar_resumen->saldo_bs->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->saldo_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_pagar_resumen->saldo_usd->Visible) { // saldo_usd ?>
        <tr id="r_saldo_usd"<?= $view_cuentas_por_pagar_resumen->saldo_usd->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_pagar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_pagar_resumen->saldo_usd->caption() ?></td>
            <td<?= $view_cuentas_por_pagar_resumen->saldo_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_pagar_resumen_saldo_usd">
<span<?= $view_cuentas_por_pagar_resumen->saldo_usd->viewAttributes() ?>>
<?= $view_cuentas_por_pagar_resumen->saldo_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
