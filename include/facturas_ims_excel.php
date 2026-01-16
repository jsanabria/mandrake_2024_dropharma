<?php 
	if($tipo != "") $where = "AND c.tarifa = $tipo";
	// LPAD(a.nro_documento, 12, '0') AS codigo, 
	$sql = "SELECT
					b.articulo AS producto, 
					a.cliente AS cliente, 
					ABS(b.cantidad_movimiento) AS cantidad,
					ROUND((b.precio_unidad/a.tasa_dia), 2) AS precio_unidad, 
					ROUND((ROUND((b.precio_unidad/a.tasa_dia), 2)*ABS(b.cantidad_movimiento)), 2) AS precio, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN fabricante AS c ON c.Id = d.fabricante  
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' 
					AND a.documento = 'FC' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
					$where;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "FACMES_" . date('Ymd') . ".xls";
	//$filename = "FACMES_" . date('Ymd') . ".txt";
	//$excel = false;
?>