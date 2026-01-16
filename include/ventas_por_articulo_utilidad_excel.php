<?php 
if($tipo != "") $where = "AND d.codigo IN ('$tipo')"; 
	$sql = "SELECT 
					d.id,
					d.codigo AS codigo,
					CONCAT(IFNULL(d.nombre_comercial, ' '), ' ', IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, 
					ROUND(d.ultimo_costo, 2) AS costo_unidad, 
					ROUND(SUM(b.cantidad_articulo*d.ultimo_costo), 2) AS costo, 
					ROUND((b.precio_unidad/IFNULL(a.tasa_dia, 1)), 2) AS precio_unidad, 
					ROUND(SUM(b.precio/IFNULL(a.tasa_dia, 1)), 2) AS precio, 
					ROUND(((ROUND((b.precio_unidad/IFNULL(a.tasa_dia, 1)), 2) - ROUND(d.ultimo_costo, 2)) / (ROUND((b.precio_unidad/IFNULL(a.tasa_dia, 1)), 2)))*100, 2) AS utilidad 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCFCV') AND a.estatus = 'PROCESADO'
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				GROUP BY 
					d.id, 
					CONCAT(IFNULL(d.nombre_comercial, ' '), ' ', IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')), 
					ROUND(d.ultimo_costo, 2), ROUND((b.precio_unidad/IFNULL(a.tasa_dia, 1)), 2) 
				ORDER BY 3 ASC;"; 
				
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		// var_dump($row); die();
		$developer_records[] = $row;
	}

	$filename = "VENTAS_ARTICULO_" . date('Ymd') . ".xls";

?>