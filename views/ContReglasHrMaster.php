<?php

namespace PHPMaker2024\mandrake;

// Table
$cont_reglas_hr = Container("cont_reglas_hr");
$cont_reglas_hr->TableClass = "table table-sm ew-table ew-master-table";
?>
<?php if ($cont_reglas_hr->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_cont_reglas_hrmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($cont_reglas_hr->descripcion->Visible) { // descripcion ?>
        <tr id="r_descripcion"<?= $cont_reglas_hr->descripcion->rowAttributes() ?>>
            <td class="<?= $cont_reglas_hr->TableLeftColumnClass ?>"><?= $cont_reglas_hr->descripcion->caption() ?></td>
            <td<?= $cont_reglas_hr->descripcion->cellAttributes() ?>>
<span id="el_cont_reglas_hr_descripcion">
<span<?= $cont_reglas_hr->descripcion->viewAttributes() ?>>
<?= $cont_reglas_hr->descripcion->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
