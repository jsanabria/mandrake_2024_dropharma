<?php 
	if($tipo != "") $where = "AND a.cliente IN ($tipo)"; 
	$sql = "SELECT 
					g.nombre AS cliente, 
					a.nro_documento, 
					d.codigo, 
					CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
					ABS(b.cantidad_movimiento) AS cantidad_entregada, 
					b.precio_unidad, 
					b.precio  
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCFCV') 
					AND a.estatus = 'PROCESADO' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					AND a.consignacion = 'S' 
					$where 
				ORDER BY g.nombre, a.nro_documento, c.nombre, d.principio_activo, d.presentacion;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "FACTURAS_CONSIGNACION_" . date('Ymd') . ".xls";

?>