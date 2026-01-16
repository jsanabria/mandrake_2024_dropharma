<?php 
	if($tipo != "") $where = "AND a.almacen = '$tipo'";
	// LPAD(a.nro_documento, 12, '0') AS codigo, 
	$username = "admin";
	
	  $sql = "SELECT 
				a.tipo_documento, a.nro_documento, 
				b.nombre AS fabricante, c.codigo, 
				CONCAT(IFNULL(c.nombre_comercial, ''), ' ', IFNULL(c.principio_activo, ''), ' ', IFNULL(c.presentacion, '')) AS articulo, 
				a.cantidad_movimiento, a.cantidad_entre_fechas, a.cantidad_acumulada, a.cantidad_ajuste, (a.cantidad_movimiento-a.cantidad_acumulada) AS cantidad_pendiente 
			FROM 
				temp_consignacion AS a 
				LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
				LEFT OUTER JOIN articulo AS c ON c.id = a.articulo 
			WHERE 
				a.username = '$username'
			ORDER BY 
				a.tipo_documento, a.nro_documento, b.nombre, articulo;";
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "INVENTARIO_" . date('Ymd') . ".xls";
?>