<?php 
	if($tipo != "") $where = "AND a.cliente IN ($tipo)"; 
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">DOCUMENTO</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		  $out .= '<th scope="col">PRECIO</th>';
		  $out .= '<th scope="col">TOTAL</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
					g.nombre AS cliente, 
					a.nro_documento, 
					d.codigo, 
					CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
					ABS(b.cantidad_movimiento) AS cantidad_entregada, 
					b.precio_unidad, 
					b.precio  
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCFCV') 
					AND a.estatus = 'PROCESADO' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					AND a.consignacion = 'S' 
					$where 
				ORDER BY g.nombre, a.nro_documento, c.nombre, d.principio_activo, d.presentacion;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$cnt = 0;
	$contar = 0;
	$facturado = 0.00;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["nro_documento"] . '</td>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td>' . $row["articulo"] . '</td>';
		  $out .= '<td>' . intval($row["cantidad_entregada"]) . '</td>';
		  $out .= '<td>' . number_format(floatval($row["precio_unidad"]), 2, ",", ".") . '</td>';
		  $out .= '<td>' . number_format(floatval($row["precio"]), 2, ",", ".") . '</td>';
		$out .= '</tr>';
		$cnt++;
		$contar += intval($row["cantidad_entregada"]);
		$facturado += floatval($row["precio"]);
	}
	$out .= '<tr>
				<th colspan="7" class="text-right">
					Items: ' . number_format($cnt, 0, "", ".") . ' <br>
					Art&iacute;culos en Facturados a Consignaci&oacute;n: ' . number_format($contar, 0, "", ".") . ' <br>
					Monto Facturado: ' . number_format($facturado, 2, ",", ".") . '
				</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>