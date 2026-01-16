<?php 
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	$sql = "SELECT
					a.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					g.nombre AS cliente, (SELECT nombre FROM usuario WHERE username = a.asesor_asignado LIMIT 0, 1) AS asesor, 
					(SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'CIUDAD' AND campo_codigo = g.ciudad) AS ciudad,  
					d.codigo AS codart, 
					d.nombre_comercial, d.principio_activo, d.presentacion, 
					c.nombre AS fabricante, 
					b.lote, b.fecha_vencimiento, 
					ABS(b.cantidad_movimiento) AS cantidad_movimiento,
					b.precio_unidad, 
					b.precio AS total_articulo, 
					a.monto_total, a.iva, a.total,  
					(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
					a.unidades, 
					(SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = a.id_documento_padre) AS DOC_NE  
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					(
					(a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' AND a.documento = 'FC') 
					OR (a.tipo_documento = 'TDCASA' AND a.estatus = 'PROCESADO' AND a.factura = 'S')
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

	$filename = "SALIDAS_ARTICULO_DETALLADO_" . date('Ymd') . ".xls";

?>