<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcfccProcess = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$pedido = $_REQUEST["pedido"];

$sql = "UPDATE entradas SET estatus = 'PROCESADO', username = '" . CurrentUserName() . "' WHERE id = $pedido;";
Execute($sql);

$sql = "SELECT 
			articulo, AVG(costo_unidad) AS costo, SUM(cantidad_movimiento) AS cantidad 
		FROM 
			entradas_salidas WHERE id_documento = $pedido AND tipo_documento = 'TDCNRP' 
		GROUP BY articulo;";
$rows = ExecuteRows($sql); 
foreach ($rows as $key => $value) {
	CalcularCostoPromedioPonderado((int)$value["articulo"], (float)$value["costo"], (int)$value["cantidad"], $pedido);
}	

header("Location: ViewInList?showmaster=view_in_tdcfcc&fk_id=" . $pedido . "&fk_tipo_documento=TDCFCC");
die();
?>
<?= GetDebugMessage() ?>
