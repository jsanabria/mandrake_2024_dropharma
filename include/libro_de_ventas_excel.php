<?php 

	if(trim($tipo) != "") $where = " AND a.documento = '$tipo' ";

	if(trim($cliente) != "") $where .= " AND a.cliente = '$cliente' ";

	if(trim($asesor) != "") $where .= " AND c.asesor = '$asesor'" ;



	// die("$tipo - $cliente - $asesor");

	// IF(a.documento='NC' or a.documento='ND', REPLACE(REPLACE(REPLACE(SUBSTRING_INDEX(a.nota, ':', -1), 'FACT-', ''), 'NC-', ''), 'ND-', ''), '') AS afectado2, 



	$sql = "SELECT 

				a.id, 

				date_format(a.fecha, '%d/%m/%Y') AS fecha, 

				b.`ci_rif`, 

				b.`nombre` AS cliente, 

				'' AS comprobante, 

				a.`documento`, 

				IF(a.documento = 'FC', REPLACE(a.nro_documento, 'FACT-', ''), '') AS nro_factura, 

				a.`nro_control`, 

				IF(a.documento = 'ND', REPLACE(a.nro_documento, 'ND-', ''), '') AS nota_debito, 

				IF(a.documento = 'NC', REPLACE(a.nro_documento, 'NC-', ''), '') AS nota_credito, 

				REPLACE(a.doc_afectado, 'FACT-', '') AS afectado, 

				a.`total` AS total_ventas, 

				ROUND((SELECT

					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * (IFNULL(a.descuento, 0)/100)), 0)) AS exento_2 

				FROM entradas_salidas

				WHERE tipo_documento = a.tipo_documento AND 

					id_documento = a.id), 2) AS no_gravadas, 

				ROUND((SELECT

					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * (IFNULL(a.descuento, 0)/100)))) AS gravado_2 

				FROM entradas_salidas

				WHERE tipo_documento = a.tipo_documento AND 

					id_documento = a.id), 2) AS base, 

				(SELECT

					alicuota  

				FROM entradas_salidas

				WHERE tipo_documento = a.tipo_documento AND 

					id_documento = a.id ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 

				a.`iva` AS iva, 

				0 AS iva_ret, 'N' AS orden, a.asesor_asignado AS asesor, a.descuento, a.tipo_documento, a.estatus,  
				a.dias_credito, date_format(DATE_ADD(a.fecha,INTERVAL IFNULL(a.dias_credito, 0) DAY), '%d/%m/%Y') AS fec_venc   

			FROM 

				salidas AS a 

				LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 

			WHERE 

				a.tipo_documento = 'TDCFCV' AND 

				a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' AND 
				a.estatus = 'PROCESADO' $where 

			UNION ALL 

			SELECT 

				a.id_documento AS id, 

				date_format(a.fecha, '%d/%m/%Y') AS fecha, 

				c.ci_rif, c.nombre AS cliente, 

				a.referencia AS comprobante, 

				a.tipo_pago AS documento, 

				'' AS nro_factura, '' AS nro_control, '' AS nota_debito, '' AS nota_credito, 

				b.nro_documento AS afectado, 0 AS total_ventas, 0 AS no_gravadas, 0 AS base, 0 AS iva, 

				(SELECT

					alicuota  

				FROM entradas_salidas

				WHERE tipo_documento = a.tipo_documento AND 

					id_documento = a.id_documento ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 

				a.monto AS iva_ret, 'S' AS orden, b.asesor_asignado AS asesor, 0 AS descuento, a.tipo_documento, b.estatus,  
				0 AS dias_credito, NULL AS fec_venc 

			FROM 

				pagos AS a 

				JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 

				LEFT OUTER JOIN cliente AS c ON c.id = b.cliente 

			WHERE 

				a.tipo_documento = 'TDCFCV' AND 

				a.tipo_pago IN ('RI','RR') AND 

				a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' AND 
				b.estatus = 'PROCESADO' $where 

			ORDER BY fecha, orden, nro_control;"; 



	$rs = mysqli_query($link, $sql); 

	if(!$rs) {

		var_dump(mysqli_error($link));

		die();

	}



	$i = 1;

	$developer_records = array();

	while( $row = mysqli_fetch_assoc($rs) ) {

		$total = trim($row["estatus"])=="ANULADO" ? "" : ($row["total_ventas"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["total_ventas"], 2, ",", "."));

		$exentas = trim($row["estatus"])=="ANULADO" ? "" : ($row["no_gravadas"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["no_gravadas"], 2, ",", "."));

		$base = trim($row["estatus"])=="ANULADO" ? "" : ($row["base"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["base"], 2, ",", "."));



		$array = [

				    "ID" => $i, //$row["id"],

				    "FECHA" => $row["fecha"],

				    "RIF" => (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]),

				    "RAZON SOCIAL" => (trim($row["estatus"])=="ANULADO" ? "ANULADA" : $row["cliente"]),

				    "COMPROBANTE" => $row["comprobante"],

   				    "NRO FACTURA" => $row["nro_factura"],

   				    "NRO CONTROL" => $row["nro_control"],

   				    "NOTA DEBITO" => $row["nota_debito"],

   				    "NOTA CREDITO" => $row["nota_credito"],

				    "DOC AFECTADO" => $row["afectado"],

				    "TOTAL VENTAS" => $total,

				    "TOTAL EXENTAS" => $exentas,

				    "TOTAL BASE" => $base,

				    "ALICUOTA" => number_format($row["alic"], 2, ",", "."),

				    "IVA" => number_format($row["iva"], 2, ",", "."),

				    "IVA RET" => number_format($row["iva_ret"], 2, ",", "."),

				    "ASESOR" => $row["asesor"],
				    "DIAS CREDITO" => $row["dias_credito"],
				    "FECHA VENCIMIENTO" => $row["fec_venc"],

				];

		$developer_records[] = $array;

		$i++;

	}

	

	$filename = "LIBRO_VENTAS_" . date('Ymd') . ".xls";

?>