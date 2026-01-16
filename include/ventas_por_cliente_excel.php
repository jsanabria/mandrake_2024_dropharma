<?php 
	if($tipo != "") $where = "AND b.ciudad = '$tipo'";
	$sql = "SELECT
				c.campo_descripcion AS ciudad, b.nombre AS cliente, 
				COUNT(a.nro_documento) AS facturas, 
				SUM(a.monto_total) AS total, SUM(a.unidades) AS unidades 
			FROM 
				salidas AS a 
				LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
				LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD' 
			WHERE 
				a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
				AND a.estatus  = 'PROCESADO' AND a.tipo_documento = 'TDCFCV' 
				AND IFNULL(a.documento, '') = 'FC' 
				$where 
			GROUP BY 
				c.campo_descripcion, b.nombre 
			ORDER BY 4 DESC;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "VENTAS_CLIENTE_" . date('Ymd') . ".xls";

?>