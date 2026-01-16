<?php 
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	$sql = "SELECT
					a.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					g.nombre AS proveedor, 
					d.codigo AS codart, 
					d.nombre_comercial, d.principio_activo, d.presentacion, 
					c.nombre AS fabricante, 
					b.lote, 
					b.fecha_vencimiento, 
					ABS(b.cantidad_movimiento) AS cantidad_movimiento,
					b.precio_unidad_sin_desc AS costo_ful, 
					b.descuento, 
					b.costo_unidad, 
					b.costo, 
					(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, a.estatus  
				FROM 
					entradas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN proveedor AS g ON g.id = a.proveedor 
				WHERE 
					(
					(a.tipo_documento = 'TDCNRP' AND a.estatus IN ('NUEVO', 'PROCESADO')) 
					OR (a.tipo_documento = 'TDCAEN' AND a.estatus IN ('NUEVO', 'PROCESADO'))
					)
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "ENTRADAS_ARTICULO_DETALLADO_" . date('Ymd') . ".xls";

?>