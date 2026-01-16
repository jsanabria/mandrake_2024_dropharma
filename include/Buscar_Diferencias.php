<?php
session_start();

include "connect.php";

$id_articulo = 40; // intval(isset($_REQUEST["id_articulo"]) ? $_REQUEST["id_articulo"] : "0");
$fecha = "2024-09-30 23:59:59";
if($id_articulo <= 0) {
	echo "<br><b><i>No ha indicado id de art&iacute;culo; !!! VERIFIQUE !!!</i></b><br>";
	die();
}

/// Consulto si existe el ajuste de entrada nro 0000000 ///

$id_ajuste = 0;
$sql = "SELECT id FROM entradas WHERE tipo_documento = 'TDCAEN' AND nro_documento = '0000000';";
$rs = mysqli_query($link, $sql);
if(!$row = mysqli_fetch_array($rs)) {
	echo "<br><b><i>No Existe el Ajuste de Entrada 0000000; !!! VERIFIQUE !!!</i></b><br>";
	die();
}

$id_ajuste = $row["id"];

//////////////////////////////////////////////////////////

/// Consulto si existe el ajuste de salida nro 0000000 ///

/*
$id_ajuste_salida = 0;
$sql = "SELECT id FROM salidas WHERE tipo_documento = 'TDCASA' AND nro_documento = '0000000';";
$rs = mysqli_query($link, $sql);
if(!$row = mysqli_fetch_array($rs)) {
	echo "<br><b><i>No Existe el Ajuste de Salida 0000000; !!! VERIFIQUE !!!</i></b><br>";
	die();
}

$id_ajuste_salida = $row["id"]; 
*/

//////////////////////////////////////////////////////////

/// Recorro todos los articulos buscando diferencias ///
$sql = "SELECT 
					art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
					'UNIDAD' AS unidad_medida, art.principio_activo, 
					art.presentacion, art.nombre_comercial, 
					ent.cantidad AS entradas, ABS(sal.cantidad) AS salidas, 
					(ent.cantidad - ABS(sal.cantidad)) AS existencia, art.fabricante  
				FROM 
					(
						SELECT 
							a.id, a.codigo, a.codigo_de_barra, b.nombre, 
							'UNIDAD' AS unidad_medida, a.principio_activo, 
							a.presentacion, a.nombre_comercial, a.fabricante 
						FROM 
							articulo AS a 
							LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
						WHERE 
							0 = 0 AND a.id = $id_articulo    
					) AS art 
					LEFT OUTER JOIN 
					(
						SELECT 
							a.articulo, SUM(a.cantidad_movimiento) AS cantidad  
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON
								b.tipo_documento = a.tipo_documento
								AND b.id = a.id_documento 
							JOIN almacen AS c ON
								c.codigo = a.almacen AND c.movimiento = 'S' 
						WHERE
							a.tipo_documento IN ('TDCNET', 'TDCASA') 
							AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
							b.fecha < '$fecha' AND a.articulo = $id_articulo 
						GROUP BY a.articulo
					) AS sal ON sal.articulo = art.Id 
					LEFT OUTER JOIN 
					(
						SELECT 
							a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
						FROM 
							entradas_salidas AS a 
							JOIN entradas AS b ON
								b.tipo_documento = a.tipo_documento
								AND b.id = a.id_documento 
							JOIN almacen AS c ON
								c.codigo = a.almacen AND c.movimiento = 'S'
						WHERE
							((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
							AND b.estatus = 'PROCESADO') OR 
							(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
							AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
							b.fecha < '$fecha' AND a.articulo = $id_articulo 
						GROUP BY a.articulo
					) AS ent ON ent.articulo = art.Id 
				WHERE ent.cantidad > 0 OR ABS(sal.cantidad) > 0 
				ORDER BY art.id;"; 
$rs = mysqli_query($link, $sql);
$sw = false;
$contador = 0;
$entradas = 0;
$salidas = 0;
$real = 0;
$forzado = 0;
$cantidad = 0;
$notas_de_entrega = [];
echo "CODIGO|ENTRADAS|SALIDAS|NOMBRE|REAL|FORZADO";
$string_id = "";
while($row = mysqli_fetch_array($rs)) { 
	$articulo = $row["id"];
	$fabricante = $row["fabricante"];
	$entradas = floatval($row["entradas"]);
	$salidas = floatval($row["salidas"]);
	$real = floatval($row["existencia"]);
	$sql2 = "SELECT 
				IFNULL(cantidad_en_mano, 0) AS cantidad_en_mano, 
				CONCAT(IFNULL(nombre_comercial,''), ' ', IFNULL(principio_activo,''), ' ', IFNULL(presentacion,'')) AS nombre 
			FROM articulo WHERE id = $articulo;"; 
	$rs2 = mysqli_query($link, $sql2);
	$row2 = mysqli_fetch_array($rs2);
	$forzado = floatval($row2["cantidad_en_mano"]);
	$nombre = $row2["nombre"];
	
	if($real != $forzado) {
		echo "<br>$articulo|$entradas|$salidas|$nombre|$real|$forzado<br>";
		$sw = true;
		$contador++;

		$sql3 = "SELECT 
					a.id, b.fecha, a.cantidad_movimiento 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
				WHERE 
					a.articulo = $articulo AND 
					a.tipo_documento = 'TDCNET' AND IFNULL(a.id_compra, 0) = 0 AND b.estatus <> 'ANULADO' 
				ORDER BY b.fecha;";
		$rs3 = mysqli_query($link, $sql3);
		$cantidad = 0;
		while($row3 = mysqli_fetch_array($rs3)) { 
			$notas_de_entrega[] = $row3["id"];
			$cantidad += $row3["cantidad_movimiento"];
			if(($real+$cantidad) > $forzado) break;
		}
		
		if($cantidad >= 0) { 
			$cantidad = abs($cantidad);
			$cantidad += ($forzado-$cantidad); 
		} 
		else {
			if($forzado == 0) {
				if($real >= 0) $cantidad = (-1)*$real;	
				else $cantidad = $real;	
			}
			else $cantidad = $real-$forzado;
		}

		$sql4 = "INSERT INTO entradas_salidas 
					SET 
						id = NULL, 
						tipo_documento = 'TDCAEN', 
						id_documento = $id_ajuste, 
						fabricante = $fabricante, 
						articulo = $articulo, 
						lote = '20240731', 
						fecha_vencimiento = '2024-07-31', 
						almacen = 'ALM001', 
						cantidad_articulo = $cantidad, 
						articulo_unidad_medida = 'UDM001', 
						cantidad_unidad_medida = 1.00, 
						cantidad_movimiento = $cantidad, 
						costo_unidad = 0.00, 
						costo = 0.00, 
						check_ne = 'N';";
		mysqli_query($link, $sql4);

		$sql4 = "SELECT LAST_INSERT_ID() AS id_compra;";
		$rs4 = mysqli_query($link, $sql4);
		$row4 = mysqli_fetch_array($rs4);
		$id_compra = $row4["id_compra"];

		$string_id = "";
		foreach ($notas_de_entrega as $key => $value) {
			$sql4 = "UPDATE entradas_salidas SET id_compra = $id_compra WHERE id = $value;";
			mysqli_query($link, $sql4);
			// echo "<br>$sql4<br>";
			$string_id .= ",$value";
		}
		//////////////////////
		$sql5 = "SELECT 
				art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
				'UNIDAD' AS unidad_medida, art.principio_activo, 
				art.presentacion, art.nombre_comercial, 
				ent.cantidad AS entradas, ABS(sal.cantidad) AS salidas, 
				(ent.cantidad - ABS(sal.cantidad)) AS existencia, art.fabricante  
			FROM 
				(
					SELECT 
						a.id, a.codigo, a.codigo_de_barra, b.nombre, 
						'UNIDAD' AS unidad_medida, a.principio_activo, 
						a.presentacion, a.nombre_comercial, a.fabricante 
					FROM 
						articulo AS a 
						LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
					WHERE 
						0 = 0 AND a.id = $articulo    
				) AS art 
				LEFT OUTER JOIN 
				(
					SELECT 
						a.articulo, SUM(a.cantidad_movimiento) AS cantidad  
					FROM 
						entradas_salidas AS a 
						JOIN salidas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S' 
					WHERE
						a.tipo_documento IN ('TDCNET', 'TDCASA') 
						AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
						b.fecha < '$fecha' AND a.articulo = $articulo 
					GROUP BY a.articulo
				) AS sal ON sal.articulo = art.Id 
				LEFT OUTER JOIN 
				(
					SELECT 
						a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
					FROM 
						entradas_salidas AS a 
						JOIN entradas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S'
					WHERE
						((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
						AND b.estatus = 'PROCESADO') OR 
						(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
						AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
						b.fecha < '$fecha' AND a.articulo = $articulo 
					GROUP BY a.articulo
				) AS ent ON ent.articulo = art.Id 
			WHERE ent.cantidad > 0 OR ABS(sal.cantidad) > 0 
			ORDER BY art.id;";
		$rs5 = mysqli_query($link, $sql5);
		$real5 = 0;
		$diferencia = 0;
		if($row5 = mysqli_fetch_array($rs5)) { 
			$real5 = floatval($row5["existencia"]); 
			$diferencia = $forzado - $real5;
		}

		$sql6 = "UPDATE entradas_salidas 
					SET cantidad_articulo = (cantidad_articulo + ($diferencia)), cantidad_movimiento = (cantidad_movimiento + ($diferencia)) 
				WHERE id = $id_compra;";
		mysqli_query($link, $sql6);

		$existencia = ActualizarExitenciaArticulo($link, $articulo);
		// echo "Cantidad en Mano ($articulo): " . $existencia . " | Reporte Inventario: " . ($real5 + $diferencia) . "<br>";

		/*
		if($existencia != ($real5 + $diferencia)) { 
			$diferencia = $existencia - ($real5 + $diferencia);
			$sql = "INSERT INTO entradas_salidas
						(id, tipo_documento, id_documento, 
						fabricante, articulo, almacen, lote, fecha_vencimiento, 
						cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
						cantidad_movimiento, costo_unidad, costo, id_compra) 
					SELECT 
						NULL AS id, 'TDCASA' AS tipo_documento, $id_ajuste_salida AS id_documento, 
						fabricante, articulo, almacen, lote, fecha_vencimiento, 
						$diferencia AS cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
						(-1)*$diferencia AS cantidad_movimiento, costo_unidad, costo, $id_compra AS id_compra 
					FROM 
						entradas_salidas WHERE id = $id_compra;"; 
			mysqli_query($link, $sql);
		}
		$existencia = ActualizarExitenciaArticulo($link, $articulo);
		*/
		echo "Cantidad en Mano ($articulo): " . $existencia . " | Reporte Inventario: " . ($real5 + $diferencia) . "<br>";
		//////////////////////
	}

	// if($sw) die(".");
}
////////////////////////////////////////////////////////

echo "<br><b><i>!!! Termin&oacute;; Total $contador !!! $string_id</i></b><br>";

function ActualizarExitenciaArticulo($link, $articulo) {
	$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$almacen = $row["ppal"];
	$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$almacenconsig = $row["ppal"];
	$sql = "SELECT 
				   IFNULL(SUM(a.cantidad_movimiento), 0) AS pedidos_nuevos 
				FROM 
				  entradas_salidas AS a 
				  JOIN salidas AS b ON
					b.tipo_documento = a.tipo_documento
					AND b.id = a.id_documento 
				  JOIN almacen AS c ON
					c.codigo = a.almacen AND c.movimiento = 'S'
				WHERE
				  a.tipo_documento IN ('TDCPDV')
				  AND a.articulo = $articulo AND b.estatus = 'NUEVO';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$pedido = floatval($row["pedidos_nuevos"]);
	$sql = "SELECT 
		  			IFNULL(SUM(a.cantidad_movimiento), 0) AS entrada 
		  		FROM 
		  			entradas_salidas AS a 
		  			JOIN entradas AS b ON
		  			b.tipo_documento = a.tipo_documento
		  			AND b.id = a.id_documento 
		  			JOIN almacen AS c ON
		  			c.codigo = a.almacen AND c.movimiento = 'S'
		  		WHERE
		  			a.tipo_documento IN ('TDCFCC') 
		  			AND b.estatus = 'NUEVO' AND a.articulo = '$articulo';"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$transito = floatval($row["entrada"]);
	$sql = "SELECT 
			SUM(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad 
		FROM 
			entradas_salidas AS a 
			JOIN entradas AS b ON
				b.tipo_documento = a.tipo_documento
				AND b.id = a.id_documento 
			JOIN almacen AS c ON
				c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
			LEFT OUTER JOIN (
					SELECT 
						a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
					FROM 
						entradas_salidas AS a 
						JOIN salidas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						LEFT OUTER JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
					WHERE
						a.tipo_documento IN ('TDCNET','TDCASA') 
						AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '$articulo' 
					GROUP BY a.id_compra
				) AS d ON d.id = a.id 
		WHERE
			((a.tipo_documento IN ('TDCNRP','TDCAEN') 
			AND b.estatus = 'PROCESADO')
			 OR
			(a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
			AND b.estatus = 'NUEVO')) AND a.articulo = '$articulo';";
			// AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$cantidad_en_mano = floatval($row["cantidad"]);				
	$sql = "UPDATE articulo
			SET
				cantidad_en_mano = $cantidad_en_mano,
				cantidad_en_pedido = IFNULL(ABS($pedido), 0),
				cantidad_en_transito = IFNULL(ABS($transito), 0) 
			WHERE id = '$articulo'";
	mysqli_query($link, $sql);

	return $cantidad_en_mano;
}
?>
