<?php

namespace PHPMaker2024\mandrake;

// Table
$view_facturas_cobranza = Container("view_facturas_cobranza");
$view_facturas_cobranza->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($view_facturas_cobranza->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_view_facturas_cobranzamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($view_facturas_cobranza->documento->Visible) { // documento ?>
        <tr id="r_documento"<?= $view_facturas_cobranza->documento->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->documento->caption() ?></td>
            <td<?= $view_facturas_cobranza->documento->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_documento">
<span<?= $view_facturas_cobranza->documento->viewAttributes() ?>>
<?= $view_facturas_cobranza->documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->codcli->Visible) { // codcli ?>
        <tr id="r_codcli"<?= $view_facturas_cobranza->codcli->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->codcli->caption() ?></td>
            <td<?= $view_facturas_cobranza->codcli->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_codcli">
<span<?= $view_facturas_cobranza->codcli->viewAttributes() ?>>
<?= $view_facturas_cobranza->codcli->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $view_facturas_cobranza->fecha->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->fecha->caption() ?></td>
            <td<?= $view_facturas_cobranza->fecha->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_fecha">
<span<?= $view_facturas_cobranza->fecha->viewAttributes() ?>>
<?= $view_facturas_cobranza->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->nro_documento->Visible) { // nro_documento ?>
        <tr id="r_nro_documento"<?= $view_facturas_cobranza->nro_documento->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->nro_documento->caption() ?></td>
            <td<?= $view_facturas_cobranza->nro_documento->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_nro_documento">
<span<?= $view_facturas_cobranza->nro_documento->viewAttributes() ?>>
<?= $view_facturas_cobranza->nro_documento->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->nro_nota->Visible) { // nro_nota ?>
        <tr id="r_nro_nota"<?= $view_facturas_cobranza->nro_nota->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->nro_nota->caption() ?></td>
            <td<?= $view_facturas_cobranza->nro_nota->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_nro_nota">
<span<?= $view_facturas_cobranza->nro_nota->viewAttributes() ?>>
<?= $view_facturas_cobranza->nro_nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->ciudad->Visible) { // ciudad ?>
        <tr id="r_ciudad"<?= $view_facturas_cobranza->ciudad->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->ciudad->caption() ?></td>
            <td<?= $view_facturas_cobranza->ciudad->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_ciudad">
<span<?= $view_facturas_cobranza->ciudad->viewAttributes() ?>>
<?= $view_facturas_cobranza->ciudad->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->moneda->Visible) { // moneda ?>
        <tr id="r_moneda"<?= $view_facturas_cobranza->moneda->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->moneda->caption() ?></td>
            <td<?= $view_facturas_cobranza->moneda->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_moneda">
<span<?= $view_facturas_cobranza->moneda->viewAttributes() ?>>
<?= $view_facturas_cobranza->moneda->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->total->Visible) { // total ?>
        <tr id="r_total"<?= $view_facturas_cobranza->total->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->total->caption() ?></td>
            <td<?= $view_facturas_cobranza->total->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_total">
<span<?= $view_facturas_cobranza->total->viewAttributes() ?>>
<?= $view_facturas_cobranza->total->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->monto_pagado->Visible) { // monto_pagado ?>
        <tr id="r_monto_pagado"<?= $view_facturas_cobranza->monto_pagado->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->monto_pagado->caption() ?></td>
            <td<?= $view_facturas_cobranza->monto_pagado->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_monto_pagado">
<span<?= $view_facturas_cobranza->monto_pagado->viewAttributes() ?>>
<?= $view_facturas_cobranza->monto_pagado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->pendiente->Visible) { // pendiente ?>
        <tr id="r_pendiente"<?= $view_facturas_cobranza->pendiente->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->pendiente->caption() ?></td>
            <td<?= $view_facturas_cobranza->pendiente->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pendiente">
<span<?= $view_facturas_cobranza->pendiente->viewAttributes() ?>>
<?= $view_facturas_cobranza->pendiente->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->pendiente2->Visible) { // pendiente2 ?>
        <tr id="r_pendiente2"<?= $view_facturas_cobranza->pendiente2->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->pendiente2->caption() ?></td>
            <td<?= $view_facturas_cobranza->pendiente2->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pendiente2">
<span<?= $view_facturas_cobranza->pendiente2->viewAttributes() ?>>
<?= $view_facturas_cobranza->pendiente2->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->pendiente3->Visible) { // pendiente3 ?>
        <tr id="r_pendiente3"<?= $view_facturas_cobranza->pendiente3->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->pendiente3->caption() ?></td>
            <td<?= $view_facturas_cobranza->pendiente3->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pendiente3">
<span<?= $view_facturas_cobranza->pendiente3->viewAttributes() ?>>
<?= $view_facturas_cobranza->pendiente3->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->fecha_despacho->Visible) { // fecha_despacho ?>
        <tr id="r_fecha_despacho"<?= $view_facturas_cobranza->fecha_despacho->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->fecha_despacho->caption() ?></td>
            <td<?= $view_facturas_cobranza->fecha_despacho->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_fecha_despacho">
<span<?= $view_facturas_cobranza->fecha_despacho->viewAttributes() ?>>
<?= $view_facturas_cobranza->fecha_despacho->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->fecha_entrega->Visible) { // fecha_entrega ?>
        <tr id="r_fecha_entrega"<?= $view_facturas_cobranza->fecha_entrega->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->fecha_entrega->caption() ?></td>
            <td<?= $view_facturas_cobranza->fecha_entrega->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_fecha_entrega">
<span<?= $view_facturas_cobranza->fecha_entrega->viewAttributes() ?>>
<?= $view_facturas_cobranza->fecha_entrega->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->dias_credito->Visible) { // dias_credito ?>
        <tr id="r_dias_credito"<?= $view_facturas_cobranza->dias_credito->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->dias_credito->caption() ?></td>
            <td<?= $view_facturas_cobranza->dias_credito->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_dias_credito">
<span<?= $view_facturas_cobranza->dias_credito->viewAttributes() ?>>
<?= $view_facturas_cobranza->dias_credito->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->dias_transcurridos->Visible) { // dias_transcurridos ?>
        <tr id="r_dias_transcurridos"<?= $view_facturas_cobranza->dias_transcurridos->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->dias_transcurridos->caption() ?></td>
            <td<?= $view_facturas_cobranza->dias_transcurridos->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_dias_transcurridos">
<span<?= $view_facturas_cobranza->dias_transcurridos->viewAttributes() ?>>
<?= $view_facturas_cobranza->dias_transcurridos->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->dias_vencidos->Visible) { // dias_vencidos ?>
        <tr id="r_dias_vencidos"<?= $view_facturas_cobranza->dias_vencidos->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->dias_vencidos->caption() ?></td>
            <td<?= $view_facturas_cobranza->dias_vencidos->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_dias_vencidos">
<span<?= $view_facturas_cobranza->dias_vencidos->viewAttributes() ?>>
<?= $view_facturas_cobranza->dias_vencidos->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->pagado->Visible) { // pagado ?>
        <tr id="r_pagado"<?= $view_facturas_cobranza->pagado->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->pagado->caption() ?></td>
            <td<?= $view_facturas_cobranza->pagado->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_pagado">
<span<?= $view_facturas_cobranza->pagado->viewAttributes() ?>>
<?= $view_facturas_cobranza->pagado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->bultos->Visible) { // bultos ?>
        <tr id="r_bultos"<?= $view_facturas_cobranza->bultos->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->bultos->caption() ?></td>
            <td<?= $view_facturas_cobranza->bultos->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_bultos">
<span<?= $view_facturas_cobranza->bultos->viewAttributes() ?>>
<?= $view_facturas_cobranza->bultos->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($view_facturas_cobranza->asesor_asignado->Visible) { // asesor_asignado ?>
        <tr id="r_asesor_asignado"<?= $view_facturas_cobranza->asesor_asignado->rowAttributes() ?>>
            <td class="<?= $view_facturas_cobranza->TableLeftColumnClass ?>"><?= $view_facturas_cobranza->asesor_asignado->caption() ?></td>
            <td<?= $view_facturas_cobranza->asesor_asignado->cellAttributes() ?>>
<span id="el_view_facturas_cobranza_asesor_asignado">
<span<?= $view_facturas_cobranza->asesor_asignado->viewAttributes() ?>>
<?= $view_facturas_cobranza->asesor_asignado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
