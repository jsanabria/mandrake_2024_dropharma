<?php
	if($tipo != "") $where = "AND a.tarifa = $tipo";
	$sql = "SELECT DISTINCT  
				b.id AS codigo, 
				SUBSTRING(RPAD(LTRIM(IFNULL(b.nombre,' ')), 32, ' '), 1, 32) AS nombre, 
				SUBSTRING(RPAD(LTRIM(REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(b.direccion,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '')), 64, ' '), 1, 64) AS direccion,
				SUBSTRING(RPAD(LTRIM(REPLACE(IFNULL(c.campo_descripcion,' '), '\"', '')), 16, ' '), 1, 16) AS ciudad, 
				SUBSTRING(RPAD(LTRIM(' '), 16, ' '), 1, 16) AS estado, 
				SUBSTRING(RPAD(LTRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(b.telefono1,' '), '\"', ''), '-', ''), ' ', ''), '(', ''), ')', '')), 12, ' '), 1, 12) AS telefono1, 
				SUBSTRING(RPAD(LTRIM(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(b.telefono2,' '), '\"', ''), '-', ''), ' ', ''), '(', ''), ')', '')), 12, ' '), 1, 12) AS telefono2, 
				SUBSTRING(RPAD(REPLACE(LTRIM(b.ci_rif), '-', ''), 12, ' '), 1, 12) AS ci_rif 
			FROM 
				salidas AS a 
				JOIN cliente AS b ON b.id = a.cliente 
				LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
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
	$ii = 0;
	while( $row = mysqli_fetch_assoc($rs) ) {
		$developer_records[] = $row;
		$ii++;
	}

	$filename = "MAECLI_" . date('Ymd') . ".xls";
	//$filename = "MAECLI_" . date('Ymd') . ".txt";
	//$excel = false;
?>