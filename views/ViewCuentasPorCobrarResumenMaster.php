<?php

namespace PHPMaker2024\mandrake;

// Table
$view_cuentas_por_cobrar_resumen = Container("view_cuentas_por_cobrar_resumen");
$view_cuentas_por_cobrar_resumen->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_cuentas_por_cobrar_resumen->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_cuentas_por_cobrar_resumenmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_cuentas_por_cobrar_resumen->cliente_rif->Visible) { // cliente_rif ?>
        <tr id="r_cliente_rif"<?= $view_cuentas_por_cobrar_resumen->cliente_rif->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->cliente_rif->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->cliente_rif->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_cliente_rif">
<span<?= $view_cuentas_por_cobrar_resumen->cliente_rif->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->cliente_rif->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->cliente_nombre->Visible) { // cliente_nombre ?>
        <tr id="r_cliente_nombre"<?= $view_cuentas_por_cobrar_resumen->cliente_nombre->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->cliente_nombre->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->cliente_nombre->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_cliente_nombre">
<span<?= $view_cuentas_por_cobrar_resumen->cliente_nombre->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->cliente_nombre->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->cantidad_documentos->Visible) { // cantidad_documentos ?>
        <tr id="r_cantidad_documentos"<?= $view_cuentas_por_cobrar_resumen->cantidad_documentos->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->cantidad_documentos->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->cantidad_documentos->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_cantidad_documentos">
<span<?= $view_cuentas_por_cobrar_resumen->cantidad_documentos->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->cantidad_documentos->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->documentos_pendientes->Visible) { // documentos_pendientes ?>
        <tr id="r_documentos_pendientes"<?= $view_cuentas_por_cobrar_resumen->documentos_pendientes->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->documentos_pendientes->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->documentos_pendientes->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_documentos_pendientes">
<span<?= $view_cuentas_por_cobrar_resumen->documentos_pendientes->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->documentos_pendientes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->documentos_parciales->Visible) { // documentos_parciales ?>
        <tr id="r_documentos_parciales"<?= $view_cuentas_por_cobrar_resumen->documentos_parciales->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->documentos_parciales->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->documentos_parciales->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_documentos_parciales">
<span<?= $view_cuentas_por_cobrar_resumen->documentos_parciales->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->documentos_parciales->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->monto_documentos_bs->Visible) { // monto_documentos_bs ?>
        <tr id="r_monto_documentos_bs"<?= $view_cuentas_por_cobrar_resumen->monto_documentos_bs->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->monto_documentos_bs->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->monto_documentos_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_monto_documentos_bs">
<span<?= $view_cuentas_por_cobrar_resumen->monto_documentos_bs->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->monto_documentos_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->monto_documentos_usd->Visible) { // monto_documentos_usd ?>
        <tr id="r_monto_documentos_usd"<?= $view_cuentas_por_cobrar_resumen->monto_documentos_usd->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->monto_documentos_usd->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->monto_documentos_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_monto_documentos_usd">
<span<?= $view_cuentas_por_cobrar_resumen->monto_documentos_usd->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->monto_documentos_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->total_cobrado_bs->Visible) { // total_cobrado_bs ?>
        <tr id="r_total_cobrado_bs"<?= $view_cuentas_por_cobrar_resumen->total_cobrado_bs->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->total_cobrado_bs->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->total_cobrado_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_total_cobrado_bs">
<span<?= $view_cuentas_por_cobrar_resumen->total_cobrado_bs->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->total_cobrado_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->total_cobrado_usd->Visible) { // total_cobrado_usd ?>
        <tr id="r_total_cobrado_usd"<?= $view_cuentas_por_cobrar_resumen->total_cobrado_usd->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->total_cobrado_usd->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->total_cobrado_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_total_cobrado_usd">
<span<?= $view_cuentas_por_cobrar_resumen->total_cobrado_usd->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->total_cobrado_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->saldo_bs->Visible) { // saldo_bs ?>
        <tr id="r_saldo_bs"<?= $view_cuentas_por_cobrar_resumen->saldo_bs->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->saldo_bs->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->saldo_bs->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_saldo_bs">
<span<?= $view_cuentas_por_cobrar_resumen->saldo_bs->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->saldo_bs->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_cuentas_por_cobrar_resumen->saldo_usd->Visible) { // saldo_usd ?>
        <tr id="r_saldo_usd"<?= $view_cuentas_por_cobrar_resumen->saldo_usd->rowAttributes() ?>>
            <td class="<?= $view_cuentas_por_cobrar_resumen->TableLeftColumnClass ?>"><?= $view_cuentas_por_cobrar_resumen->saldo_usd->caption() ?></td>
            <td<?= $view_cuentas_por_cobrar_resumen->saldo_usd->cellAttributes() ?>>
<span id="el_view_cuentas_por_cobrar_resumen_saldo_usd">
<span<?= $view_cuentas_por_cobrar_resumen->saldo_usd->viewAttributes() ?>>
<?= $view_cuentas_por_cobrar_resumen->saldo_usd->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
