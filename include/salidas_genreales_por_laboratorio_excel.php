<?php 
	$where = "";
	$where2 = "";

	if($tipo != "") {
		$where = "AND b.fabricante IN ($tipo)";
		$where2 = "AND a.fabricante IN ($tipo)";
	}

	$sql = "SELECT 
				j.almacen, i.codigo, i.nombre_comercial, 
				i.principio_activo, i.presentacion, i.fabricante, j.unidad_medida, 
				j.cantidad 
			FROM 
				(SELECT 
					a.id AS articulo, a.codigo, b.nombre AS fabricante, 
					a.principio_activo, a.presentacion, a.nombre_comercial 
				FROM 
					articulo AS a 
					JOIN fabricante AS b ON b.Id = a.fabricante 
				WHERE 
					0=0 $where2) AS i 
				LEFT OUTER JOIN  
				(SELECT
					b.articulo, d.codigo, f.descripcion AS almacen, c.nombre AS fabricante, 
					d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion AS unidad_medida, 
					ABS(SUM(b.cantidad_movimiento)) AS cantidad  
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
					LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
				WHERE 
					a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					AND a.estatus  = 'PROCESADO' AND b.tipo_documento IN ('TDCFCV','TDCASA') 
					-- AND IFNULL(a.documento, '') IN ('FC', '') AND a.activo = 'S' 
					AND (IFNULL(a.documento, '') = 'FC' OR (IFNULL(a.documento, '') = '' AND a.factura = 'S'))
					$where 
				GROUP BY 
					b.articulo, d.codigo, f.descripcion, c.nombre, 
					d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion) AS j ON j.articulo = i.articulo 
			ORDER BY i.fabricante, j.almacen DESC, j.cantidad DESC;";

	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "SALIDAS_LABORATORIO_" . date('Ymd') . ".xls";

?>