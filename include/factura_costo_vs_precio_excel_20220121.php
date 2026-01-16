<?php 
	if($tipo != "") $where = "AND c.tarifa = $tipo";
	// LPAD(a.nro_documento, 12, '0') AS codigo, 
	$sql = "SELECT
					d.codigo, 
					d.nombre_comercial, 
					d.principio_activo, 
					d.presentacion, 
					g.nombre AS cliente, 
					ABS(b.cantidad_movimiento) AS cantidad, 
					b.costo_unidad, 
					b.costo, 
					b.precio_unidad, 
					b.precio, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' 
					AND a.documento = 'FC' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
					$where 
				ORDER BY a.fecha;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "COSTO_VS_PRECIO_" . date('Ymd') . ".xls";
?>