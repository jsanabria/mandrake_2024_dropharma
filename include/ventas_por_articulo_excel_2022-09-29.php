<?php 
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	$sql = "SELECT 
					d.id,
					d.codigo, 
					SUBSTRING(RPAD(LTRIM(REPLACE(REPLACE(CONCAT(REPLACE(IFNULL(d.principio_activo, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.presentacion, ' '), '\n', '')), '\n', ''), '\r', '')), 32, ' '), 1, 32) AS nombre, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCFCV','TDCASA') AND a.estatus = 'PROCESADO'
					AND a.documento = 'FC' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				GROUP BY d.id, CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) 
				ORDER BY cantidad_movimiento DESC;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "VENTAS_ARTICULO_" . date('Ymd') . ".xls";

?>