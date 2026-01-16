<?php 
	if($tipo != "") $where = "AND c.asesor = $tipo";
	$sql = "SELECT 
				f.codigo,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.nombre,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS nombre,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.direccion,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS direccion,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.ciudad,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS ciudad,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.telefono1,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS telefono1,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.telefono2,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS telefono2,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.ci_rif,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS ci_rif,
				REPLACE(REPLACE(REPLACE(REPLACE(IFNULL(f.asesor,' '), '\"', ''), '\t', ''), '\n', ''), '\r', '') AS asesor 
						FROM 
		(SELECT 
							a.id AS codigo, 
							a.nombre, a.direccion, 
							b.campo_descripcion AS ciudad, 
							a.telefono1, a.telefono2, a.ci_rif,
							d.nombre AS asesor 
						FROM 
							cliente AS a 
							LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
							JOIN asesor_cliente AS c ON c.cliente = a.id $where 
							JOIN asesor AS d ON d.id = c.asesor) AS f 
		LEFT OUTER JOIN  
		(SELECT  
							b.id AS codigo, 
							b.nombre, b.direccion, 
							c.campo_descripcion AS ciudad, 
							b.telefono1, b.telefono2, b.ci_rif 
						FROM 
							salidas AS a 
							JOIN cliente AS b ON b.id = a.cliente 
							LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
						WHERE 
							a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
							AND a.documento = 'FC' 
							AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
							GROUP BY b.id, b.nombre, b.direccion, c.campo_descripcion, b.telefono1, b.telefono2, b.ci_rif) g 
			ON g.codigo = f.codigo where g.nombre IS NULL;"; 
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