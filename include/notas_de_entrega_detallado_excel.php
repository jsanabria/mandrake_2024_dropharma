<?php 
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	$sql = "SELECT
					a.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					g.nombre AS cliente, 
					d.codigo AS codart, 
					d.nombre_comercial, d.principio_activo, d.presentacion, 
					c.nombre AS fabricante, 
					ABS(b.cantidad_movimiento) AS cantidad_movimiento,
					(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, a.estatus, 
					date_format(b.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, b.lote 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					(
					(a.tipo_documento = 'TDCNET' AND a.estatus IN ('NUEVO', 'PROCESADO', 'ANULADO')) 
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

	$filename = "NOTAS_DE_ENTREGA__DETALLADO_" . date('Ymd') . ".xls";

?>