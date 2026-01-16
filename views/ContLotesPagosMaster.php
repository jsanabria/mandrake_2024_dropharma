<?php

namespace PHPMaker2024\mandrake;

// Table
$cont_lotes_pagos = Container("cont_lotes_pagos");
$cont_lotes_pagos->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($cont_lotes_pagos->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cont_lotes_pagosmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cont_lotes_pagos->id->Visible) { // id ?>
        <tr id="r_id"<?= $cont_lotes_pagos->id->rowAttributes() ?>>
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->id->caption() ?></td>
            <td<?= $cont_lotes_pagos->id->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_id">
<span<?= $cont_lotes_pagos->id->viewAttributes() ?>>
<?= $cont_lotes_pagos->id->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->fecha->Visible) { // fecha ?>
        <tr id="r_fecha"<?= $cont_lotes_pagos->fecha->rowAttributes() ?>>
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->fecha->caption() ?></td>
            <td<?= $cont_lotes_pagos->fecha->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_fecha">
<span<?= $cont_lotes_pagos->fecha->viewAttributes() ?>>
<?= $cont_lotes_pagos->fecha->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->procesado->Visible) { // procesado ?>
        <tr id="r_procesado"<?= $cont_lotes_pagos->procesado->rowAttributes() ?>>
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->procesado->caption() ?></td>
            <td<?= $cont_lotes_pagos->procesado->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_procesado">
<span<?= $cont_lotes_pagos->procesado->viewAttributes() ?>>
<?= $cont_lotes_pagos->procesado->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->nota->Visible) { // nota ?>
        <tr id="r_nota"<?= $cont_lotes_pagos->nota->rowAttributes() ?>>
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->nota->caption() ?></td>
            <td<?= $cont_lotes_pagos->nota->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_nota">
<span<?= $cont_lotes_pagos->nota->viewAttributes() ?>>
<?= $cont_lotes_pagos->nota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($cont_lotes_pagos->usuario->Visible) { // usuario ?>
        <tr id="r_usuario"<?= $cont_lotes_pagos->usuario->rowAttributes() ?>>
            <td class="<?= $cont_lotes_pagos->TableLeftColumnClass ?>"><?= $cont_lotes_pagos->usuario->caption() ?></td>
            <td<?= $cont_lotes_pagos->usuario->cellAttributes() ?>>
<span id="el_cont_lotes_pagos_usuario">
<span<?= $cont_lotes_pagos->usuario->viewAttributes() ?>>
<?= $cont_lotes_pagos->usuario->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
