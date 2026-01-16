<?php

namespace PHPMaker2024\mandrake;

// Page object
$ConvertirAFactura = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_GET["id"];

$fact = true;
$pagina = 1;
$contador = 0;
$cantidad = 0;
$lineas = 0; // Lineas de items del pedido de ventas

$sql = "SELECT valor1 AS items FROM parametro WHERE codigo = '004';";
$items = ExecuteScalar($sql); // Lineas de items máximo para cada factura

while($fact) {

	// Tomo el siguiente número de factura
	$sql = "SELECT valor1, valor2, valor3 FROM parametro WHERE codigo = '003';";
	$row = ExecuteRow($sql);
	$numero = intval($row["valor1"]) + 1;
	$prefijo = trim($row["valor2"]);
	$padeo = intval($row["valor3"]);
	$factura = $prefijo . str_pad($numero, $padeo, "0", STR_PAD_LEFT); 
	$sql = "UPDATE parametro SET valor1='$numero' 
			WHERE codigo = '003';";
	Execute($sql);
	
	// Inserto el encabezado de la primera factura
	$sql = "INSERT INTO factura
			(id, username, fecha, cliente, 
			nro_factura, nota, estatus, id_venta)
		SELECT 
			NULL, '" . CurrentUserName() . "' AS username, '" . date("Y-m-d H:i:s") . "' AS fecha, cliente, 
			'$factura' AS factura, 'VIENE DE PEDIDO DE VENTA No. $id - Pagina $pagina' AS nota,
			'NUEVA' AS estatus, id  
		FROM venta 
		WHERE id = '$id';";
	ExecuteScalar($sql);

	// Obtengo el id de la nueva factura
	$factura_id = ExecuteScalar("SELECT LAST_INSERT_ID();");

	$pagina++;

	// Poblo el detalle de la factura
	$sql = "SELECT COUNT(id) AS cantidad FROM entrada_salida WHERE venta = '$id' LIMIT 0, 500;";
	$lineas = ExecuteScalar($sql);

	for($i = 0; $i < $lineas; $i++) {
		if($i > $items) {
			$fact = true;
			break;
		}

		// Consulto cantidad solicitada por cada articulo del pedido de venta
		$sql = "SELECT 
					articulo, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento 
				FROM entrada_salida WHERE venta = '$id' LIMIT $i, 0;";
		$row = ExecuteRow($sql))
		$articulo = $row["articulo"];
		$cantidad_articulo = $row["cantidad_articulo"];
		$articulo_unidad_medida = $row["articulo_unidad_medida"];
		$cantidad_unidad_medida = $row["cantidad_unidad_medida"];
		$cantidad_movimiento = $row["cantidad_movimiento"];

		// Consulto cantidad disponible por cada articulo del pedido de venta en la tabla entrada_salida 
		$sql = "SELECT 
					COUNT((IFNULL(a.cantidad_movimiento, 0) - IFNULL(b.cantidad_movimiento, 0))) AS cantidad 
				FROM 
					entrada_salida AS a 
					LEFT OUTER JOIN 
					(
						SELECT id_compra, SUM(cantidad_movimiento) AS cantidad_movimiento 
						FROM entrada_salida 
						WHERE articulo = '$articulo' AND IFNULL(factura, 0) > 0 
						GROUP BY id_compra HAVING IFNULL(id_compra, 0) > 0
					) AS b ON b.id_compra = a.id
				WHERE 
					a.articulo = '$articulo' AND IFNULL(a.compra, 0) > 0;";
		$cnt = ExecuteScalar($sql);

		$cm = $cantidad_movimiento;
		for($j = 0; $j < $cnt; $j++) {
			$sql = "SELECT 
						(IFNULL(a.cantidad_movimiento, 0) - IFNULL(b.cantidad_movimiento, 0)) AS cantidad, 
						a.fecha_vencimiento, a.lote, a.id, a.fabricante, a.almacen  
					FROM 
						entrada_salida AS a 
						LEFT OUTER JOIN 
						(
							SELECT id_compra, SUM(cantidad_movimiento) AS cantidad_movimiento 
							FROM entrada_salida 
							WHERE articulo = '$articulo' AND IFNULL(factura, 0) > 0 
							GROUP BY id_compra HAVING IFNULL(id_compra, 0) > 0
						) AS b ON b.id_compra = a.id
					WHERE 
						a.articulo = '$articulo' AND IFNULL(a.compra, 0) > 0 
					ORDER BY a.fecha_vencimiento ASC LIMIT $j, 1;";
			$row2 = ExecuteRow($sql);
			$cantidad = $row2["cantidad"];
			$fecha_vencimiento = $row2["fecha_vencimiento"];
			$lote = $row2["lote"];
			$id_compra = $row2["id"];
			$fabricante = $row2["fabricante"];
			$almacen = $row2["almacen"];

			$sql = "SELECT 
						d.precio 
					FROM 
						venta a 
						JOIN entrada_salida b ON b.venta = a.id 
						JOIN cliente c ON c.id = a.cliente 
						JOIN tarifa_articulo d ON d.tarifa = c.tarifa AND d.articulo = b.articulo 
					WHERE 
						b.venta = '$id' AND b.articulo = '$articulo';";
			$precio = floatval(ExecuteScalar($sql));

			if($cm < $cantidad) {
				$precio_total = $precio*$cantidad_movimiento;
				$sql = "INSERT INTO entrada_salida
							(id, factura, fabricante, almacen, 
							articulo, lote, fecha_vencimiento, 
							cantidad_articulo, articulo_unidad_medida, 
							cantidad_unidad_medida, cantidad_movimiento, 
							precio_unidad, precio, id_compra) 
						VALUES (NULL, '$factura_id', '$fabricante', '$almacen', 
								'$articulo', '$lote', '$fecha_vencimiento', 
								'$cantidad_articulo', '$articulo_unidad_medida', 
								'$cantidad_unidad_medida', '$cantidad_movimiento', 
								'$precio', '$precio_total', '$id_compra')";
				Execute($sql);
				break;
			}
			else {
				$precio_total = $precio*$cantidad;
				$sql = "INSERT INTO entrada_salida
							(id, factura, fabricante, almacen, 
							articulo, lote, fecha_vencimiento, 
							cantidad_articulo, articulo_unidad_medida, 
							cantidad_unidad_medida, cantidad_movimiento, 
							precio_unidad, precio, id_compra) 
						VALUES (NULL, '$factura_id', '$fabricante', '$almacen', 
								'$articulo', '$lote', '$fecha_vencimiento', 
								'($cantidad/$cantidad_unidad_medida)', '$articulo_unidad_medida', 
								'$cantidad_unidad_medida', '$cantidad', 
								'$precio', '$precio_total', '$id_compra')";
				Execute($sql);
				$cm -= $cantidad;
			}
		}

		$contador++;
	}

	if($contador > $lineas) $fact = false;
}

?>

<?= GetDebugMessage() ?>
