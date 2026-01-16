<?php

namespace PHPMaker2024\mandrake;

// Page object
$VerificarExistenciaUpdate2 = &$Page;
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
	entradas_salidas WHERE id = '$id ';";
$row = ExecuteRow($sql);

$cantidad_pedida = floatval($row["cantidad"]);

// Traigo la infromación del lote y fecha de vencimiento
$sql = "SELECT lote, fecha_vencimiento FROM entradas_salidas WHERE id = '$lote';"; 
$row = ExecuteRow($sql);
$xLote = $row["lote"];
$xVencimiento = $row["fecha_vencimiento"];

//die("Lote: $lote - Existencia: $existencia - Solicitado: $solicitado");

if($solicitado > $existencia) echo "0";
else  {
	if($solicitado > $cantidad_pedida) echo "0";
	else {
		$sql = "UPDATE entradas_salidas
				SET
					cantidad_articulo = '$cantidad',
					articulo_unidad_medida = '$unidad',
					cantidad_unidad_medida = '$cantidad_um',
					cantidad_movimiento = '" . (-1) * $solicitado. "',
					id_compra = '$lote', 
					lote = '$xLote', 
					fecha_vencimiento = '$xVencimiento',
					precio = precio_unidad * $cantidad 
				WHERE 
					id = '$id';"; 
		Execute($sql);

		if(($cantidad_pedida - $solicitado) > 0) {
			$sql = "INSERT INTO entradas_salidas
						(id, tipo_documento, id_documento, 
						fabricante, articulo, lote, 
						fecha_vencimiento, almacen, 
						cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
						cantidad_movimiento, id_compra, alicuota,
						precio_unidad, precio)
					SELECT 
						NULL, tipo_documento, id_documento, 
						fabricante, articulo, NULL, 
						NULL, almacen, 
						($cantidad_pedida - $solicitado), '$unidad', $cantidad_um, 
						($cantidad_pedida - $solicitado)/$cantidad_um, NULL, alicuota,
						precio_unidad,
						(precio_unidad * ($cantidad_pedida - $solicitado)) AS precio 
					FROM 
						entradas_salidas 
					WHERE 
						id = '$id';";
			Execute($sql);
		}
		echo "1";
	}
}

?>



<?= GetDebugMessage() ?>
