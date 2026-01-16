<?php  

	if(trim($tipo) != "") $where = " AND a.documento = '$tipo' ";

	if(trim($cliente) != "") $where .= " AND a.cliente = '$cliente' ";

	if(trim($asesor) != "") $where .= " AND c.asesor = '$asesor' ";



	$contar = 0;



	//$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button> <a class="btn btn-primary" href="reportes/libro_de_ventas.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas</a> <a class="btn btn-primary" href="reportes/libro_de_ventas_asesor.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas con Asesor</a>';

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button> <a class="btn btn-primary" href="reportes/libro_de_ventas.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas</a>';

	$out .= '<table class="table table-hover table-bordered">';

	  $out .= '<thead>';

		$out .= '<tr>';

		  $out .= '<th scope="col">ID</th>';

		  $out .= '<th scope="col">FECHA</th>';

		  $out .= '<th scope="col">RIF</th>';

		  $out .= '<th scope="col">RAZON SOCIAL</th>';

		  $out .= '<th scope="col">COMPROBANTE</th>';

		  $out .= '<th scope="col">NRO FACTURA</th>';

		  $out .= '<th scope="col">NRO CONTROL</th>';

		  $out .= '<th scope="col">NOTA DEBITO</th>';

		  $out .= '<th scope="col">NOTA CREDITO</th>';

		  $out .= '<th scope="col">DOC AFECTADO</th>';

		  $out .= '<th scope="col">TOTAL VENTAS</th>';

		  $out .= '<th scope="col">TOTAL EXENTAS</th>';

		  $out .= '<th scope="col">TOTAL BASE</th>';

		  $out .= '<th scope="col">ALICUOTA</th>';

		  $out .= '<th scope="col">IVA</th>';

		  $out .= '<th scope="col">IVA RET</th>';

		  $out .= '<th scope="col">ASESOR</th>';
		  $out .= '<th scope="col">DIAS CRED</th>';
		  $out .= '<th scope="col">FECHA VENC</th>';

		$out .= '</tr>';

	  $out .= '</thead>';

	  $out .= '<tbody>';



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



	$exenta = 0;

	$gravable = 0;

	$alicuota_iva = 0;

	$i = 1;

	while($row = mysqli_fetch_array($rs)) {

		$total = trim($row["estatus"])=="ANULADO" ? "" : ($row["total_ventas"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["total_ventas"], 2, ",", "."));

		$exentas = trim($row["estatus"])=="ANULADO" ? "" : ($row["no_gravadas"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["no_gravadas"], 2, ",", "."));

		$base = trim($row["estatus"])=="ANULADO" ? "" : ($row["base"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["base"], 2, ",", "."));



		$out .= '<tr>';

		  $out .= '<td scope="col">' . $i . '</td>';

		  $out .= '<td scope="col">' . $row["fecha"] . '</td>';

		  $out .= '<td scope="col">' . (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]) . '</td>';

		  $out .= '<td scope="col">' . (trim($row["estatus"])=="ANULADO" ? "ANULADA" : $row["cliente"]) . '</td>';

		  $out .= '<td scope="col">' . $row["comprobante"] . '</td>';

		  $out .= '<td scope="col">' . $row["nro_factura"] . '</td>';

		  $out .= '<td scope="col">' . $row["nro_control"] . '</td>';

		  $out .= '<td scope="col">' . $row["nota_debito"] . '</td>';

		  $out .= '<td scope="col">' . $row["nota_credito"] . '</td>';

		  $out .= '<td scope="col">' . $row["afectado"] . '</td>';

		  $out .= '<td scope="col">' . $total . '</td>';

		  //$out .= '<td scope="col">' . trim($row["estatus"])=="ANULADO" ? "" : ($row["total_ventas"]==0 ? "" : number_format(($row["documento"]=="NC" ? -1 : 1)*$row["total_ventas"], 2, ",", ".")) . '</td>';

		  $out .= '<td scope="col">' . $exentas . '</td>';

		  $out .= '<td scope="col">' . $base . '</td>';

		  $out .= '<td scope="col">' . number_format($row["alic"], 2, ",", ".") . '</td>';

		  $out .= '<td scope="col">' . number_format($row["iva"], 2, ",", ".") . '</td>';

		  $out .= '<td scope="col">' . number_format($row["iva_ret"], 2, ",", ".") . '</td>';

		  $out .= '<td scope="col">' . $row["asesor"] . '</td>';
		  $out .= '<td scope="col">' . $row["dias_credito"] . '</td>';
		  $out .= '<td scope="col">' . $row["fec_venc"] . '</td>';

		$out .= '</tr>';



		$contar++;

		if($contar >= 20) {

			$out .= '<tr>

				<th colspan="19" class="text-right">Se visualizan ' . $contar . ' registros - Exportar para ver todos los registros...</th>

			</tr>';

			break;

		}

		$i++;

	}

	$out .= '<tr>

				<th colspan="17" class="text-right">

					<a class="btn btn-primary" href="reportes/libro_de_ventas.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas</a> 

					Items: ' . number_format($contar, 0, "", ".") . '

				</th>

			</tr>';

  	  $out .= '</tbody>';

	$out .= '</table>';

?>