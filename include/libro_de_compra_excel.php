<?php 
	if($tipo != "") $where = "AND a.documento = '$tipo'";
	$sql = "SELECT 
					a.fecha AS fecfac, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', a.nro_documento), a.nro_documento)) AS nro_documento, 
					IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
					a.doc_afectado AS doc_afectado, 
					a.nro_control,  
					b.nombre AS proveedor, 
					b.ci_rif, 
					IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.total) AS total, 
					IF(a.documento = 'NC', -1, 1) * (SELECT 
						SUM(IF(IFNULL(alicuota, 0)=0, costo, 0)) AS exenta 
					FROM entradas_salidas 
					WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta, 
					IF(a.documento = 'NC', -1, 1) * (SELECT 
						SUM(IF(IFNULL(alicuota, 0)=0, 0, costo)) AS gravable 
					FROM entradas_salidas 
					WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable, 
					(SELECT 
						MAX(alicuota) AS alicuota_iva 
					FROM entradas_salidas 
					WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva, 
					IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.iva) AS iva, 
					IF(a.documento = 'NC', -1, 1) * a.ret_iva, IF(a.documento = 'NC', -1, 1) * a.ret_islr 
				FROM 
					entradas AS a 
					LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
				WHERE 
					a.tipo_documento = 'TDCFCC' AND 
					a.fecha_libro_compra BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' AND a.estatus = 'PROCESADO' 
				UNION ALL 	
				SELECT 
					a.fecha AS fecfac, 
					DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
					IF(a.tipo_documento = 'NC', '', IF(a.tipo_documento = 'ND', CONCAT('ND-', a.documento), a.documento)) AS nro_documento, 
					IF(a.tipo_documento = 'NC', a.documento, '')  AS nota_credito, 
					a.doc_afectado AS doc_afectado, 
					a.nro_control,  
					b.nombre AS proveedor, 
					b.ci_rif, 
					IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total, 
					IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS exenta, 
					IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS gravable, 
					a.alicuota AS alicuota_iva, 
					IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS iva, 
					IF(a.tipo_documento = 'NC', -1, 1) * a.ret_iva, 
					IF(a.tipo_documento = 'NC', -1, 1) * a.ret_islr   
				FROM 
					compra AS a
					LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
				WHERE 
					a.fecha_registro BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
				ORDER BY fecfac, nro_documento;"; 
	$rs = mysqli_query($link, $sql);

	/*if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}*/

	$developer_records = array();
	while( $row = mysqli_fetch_assoc($rs) ) {
		//$developer_records[] = $row;
		$array = [
				    "Fecha" => $row["fecha"],
				    "Factura" => $row["nro_documento"],
				    "NC" => $row["nota_credito"],
				    "Afectado" => $row["doc_afectado"],
				    "Nro_Control" => $row["nro_control"],
				    "Razon_Social" => $row["proveedor"],
				    "RIF" => $row["ci_rif"],
				    "Total_ventas" => number_format($row["total"], 2, ",", "."),
				    "Total_Exentas" => number_format($row["exenta"], 2, ",", "."),
				    "Base" => number_format($row["gravable"], 2, ",", "."),
				    "Alicuota" => number_format($row["alicuota_iva"], 2, ",", "."),
				    "Impuesto" => number_format($row["iva"], 2, ",", ".")
		];
		$developer_records[] = $array;
	}

	$filename = "LIBRO_COMPRAS_" . date('Ymd') . ".xls";
?>