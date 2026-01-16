<?php 
	if($tipo != "") $where = "AND b.tarifa = $tipo";
	else $where = "AND b.tarifa = 2";
	
	// SUBSTRING(RPAD(LTRIM(REPLACE(REPLACE(CONCAT(REPLACE(IFNULL(d.principio_activo, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.presentacion, ' '), '\n', '')), '\n', ''), '\r', '')), 32, ' '), 1, 32) AS nombre, 
	$sql = "SELECT DISTINCT 
				b.articulo AS codigo, 
				LTRIM(REPLACE(REPLACE(CONCAT(REPLACE(IFNULL(d.nombre_comercial, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.principio_activo, ' '), '\t', ''), ' ', REPLACE(IFNULL(d.presentacion, ' '), '\n', '')), '\n', ''), '\r', '')) AS nombre, 
				SUBSTRING(RPAD(IFNULL(c.nombre, ' '), 32, ' '), 1, 32) AS fabricante, 
				IFNULL(f.precio, 0) AS precio, 
				-- LPAD(LTRIM(REPLACE(CAST(f.precio AS CHAR), '.', '')), 8, '0') AS precio, 
				SUBSTRING(RPAD(IFNULL(d.codigo_de_barra, ' '), 13, '0'), 1, 13) AS codigo_de_barra  
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
				LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
				LEFT OUTER JOIN fabricante AS c ON c.Id = d.fabricante  
				LEFT OUTER JOIN tarifa_articulo AS f ON f.fabricante = d.fabricante AND f.articulo = b.articulo AND f.tarifa = 2 
			WHERE 
				a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
				AND a.documento = 'FC' 
				AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
	}

	$filename = "MAEINV_" . date('Ymd') . ".xls";
	//$filename = "MAEINV_" . date('Ymd') . ".txt";
	//$excel = false;
?>