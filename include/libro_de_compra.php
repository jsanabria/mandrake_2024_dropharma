<?php 
	if($tipo != "") $where = "AND a.documento = '$tipo'";

	$contar = 0; 

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button> <a class="btn btn-primary" href="reportes/libro_de_compras.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Compras</a>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">FACTURA</th>';
		  $out .= '<th scope="col">NOTA CREDITO</th>';
		  $out .= '<th scope="col">NRO DOC. AFEC</th>';
		  $out .= '<th scope="col">NRO CONTROL</th>';
		  $out .= '<th scope="col">NOMBRE O RAZON SOCIAL</th>';
		  $out .= '<th scope="col">RIF NRO</th>';
		  $out .= '<th scope="col">TOTAL VENTAS</th>';
		  $out .= '<th scope="col">VENTAS EXENTAS</th>';
		  $out .= '<th scope="col">BASE</th>';
		  $out .= '<th scope="col">%</th>';
		  $out .= '<th scope="col">IMPUESTO</th>';
		  $out .= '<th scope="col">IVA RET</th>';
		  $out .= '<th scope="col">ISLR RET</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
				a.fecha AS fecfac, 
				date_format(a.fecha, '%d/%m/%Y') AS fecha, 
				IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', a.nro_documento), a.nro_documento)) AS nro_documento, 
				IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
				a.doc_afectado AS doc_afectado, 
				a.nro_control,  
				b.nombre AS proveedor, 
				b.ci_rif, 
				IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.monto_total) AS monto_total, 
				IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.iva) AS iva, 
				IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.total) AS total, 
				IF(a.documento = 'NC', -1, 1) * (SELECT 
					SUM(IF(IFNULL(alicuota, 0)=0, costo, 0)) AS exenta 
				FROM entradas_salidas 
				WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta, 
				IF(a.documento = 'NC', -1, 1) * (SELECT 
					SUM(IF(IFNULL(alicuota, 0)=0, 0, costo)) AS gravable 
				FROM entradas_salidas 
				WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable, 
				IF(a.documento = 'NC', -1, 1) * (SELECT 
					MAX(alicuota) AS alicuota_iva 
				FROM entradas_salidas 
				WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva, 
				a.estatus, ret_iva, ret_islr 
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
				IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total, 
				IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS iva, 
				IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total, 
				IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS exenta, 
				IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS gravable, 
				a.alicuota AS alicuota_iva, '' AS estatus, 
				IF(a.tipo_documento = 'NC', -1, 1) * a.ret_iva, 
				IF(a.tipo_documento = 'NC', -1, 1) * a.ret_islr   
			FROM 
				compra AS a
				LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
			WHERE 
				a.fecha_registro BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
			ORDER BY fecfac, nro_documento;"; 
	$rs = mysqli_query($link, $sql);
	
  	while($row = mysqli_fetch_array($rs)) {
		$out .= '<tr>';
		  $out .= '<td scope="col">' . $row["fecha"] . '</td>';
		  $out .= '<td scope="col">' . $row["nro_documento"] . '</td>';
		  $out .= '<td scope="col">' . $row["nota_credito"] . '</td>';
		  $out .= '<td scope="col">' . $row["doc_afectado"] . '</td>';
		  $out .= '<td scope="col">' . $row["nro_control"] . '</td>';
		  $out .= '<td scope="col">' . (trim($row["estatus"])=="ANULADO" ? "ANULADA" : $row["proveedor"]) . '</td>';
		  $out .= '<td scope="col">' . (trim($row["estatus"])=="ANULADO" ? "" : $row["ci_rif"]) . '</td>';
		  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $row["total"]), 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $row["exenta"]), 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">' . number_format((trim($row["estatus"])=="ANULADO" ? 0 : $row["gravable"]), 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">' . number_format($row["alicuota_iva"], 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">' . number_format($row["iva"], 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">' . number_format($row["ret_iva"], 2, ".", ",") . '</td>';
		  $out .= '<td scope="col">' . number_format($row["ret_islr"], 2, ".", ",") . '</td>';
		$out .= '</tr>';

		$contar++;
		if($contar >= 20) {
			$out .= '<tr>
				<th colspan="15" class="text-right">Se visualizan ' . $contar . ' registros - Exportar para ver todos los registros...</th>
			</tr>';
			break;
		}
	}
	$out .= '<tr>
				<th colspan="15" class="text-right">Items: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';

	$out .= '<tr>
				<th colspan="15" class="text-right">
					<a class="btn btn-primary" href="reportes/libro_de_compras.php?xfecha=' . $fecha_desde . '&yfecha=' . $fecha_hasta . '" target="_blank">Imprimir Libro de Ventas</a> 
					Items: ' . number_format($contar, 0, "", ".") . '
				</th>
			</tr>';

  	  $out .= '</tbody>';
	$out .= '</table>';
?>