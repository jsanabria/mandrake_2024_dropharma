<?php

namespace PHPMaker2024\mandrake;

// Page object
$VerificarExistencia = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php 

$id = $_REQUEST["id"];
$lote = $_REQUEST["lote"];
$cantidad = floatval($_REQUEST["cantidad"]);
$unidad = $_REQUEST["unidad"];

$sql = "SELECT 
			(IFNULL(a.cantidad_movimiento, 0) + IFNULL(b.cantidad, 0)) AS cantidad 
		FROM 
			entradas_salidas AS a 
			LEFT OUTER JOIN (SELECT id_compra AS id, SUM(IFNULL(cantidad_movimiento, 0)) AS cantidad 
			FROM entradas_salidas
			WHERE id_compra = '$lote'
			GROUP BY id_compra) AS b ON b.id = a.id 
		WHERE a.id = '$lote' AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(b.cantidad, 0)) > 0;"; 
$row = ExecuteRow($sql);

$existencia = $row["cantidad"];

$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$unidad';";
$row = ExecuteRow($sql);

$cantidad_um = floatval($row["cantidad"]);

$solicitado = $cantidad * $cantidad_um;

$sql = "SELECT 
	(cantidad_articulo * cantidad_unidad_medida) AS cantidad 
FROM 
	entradas_salidas WHERE id = '$id';";
$row = ExecuteRow($sql);

$cantidad_pedida = floatval($row["cantidad"]);


//die("Lote: $lote - Existencia: $existencia - Solicitado: $solicitado");

if($solicitado > $existencia) echo "0";
else {
	if($solicitado > $cantidad_pedida) echo "0";
	else echo "1";
} 
die();
?>


<?= GetDebugMessage() ?>
