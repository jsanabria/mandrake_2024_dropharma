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
		  $out .= '<th scope="col">ENTREGADO</th>';
		  $out .= '<th scope="col">FACTURADO</th>';
		  $out .= '<th scope="col">PENDIENTE</th>';
		  $out .= '<th scope="col">VENTA</th>';
		  $out .= '<th scope="col">FACTURA</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
					g.nombre AS cliente, 
					a.nro_documento,  
					d.codigo, 
					CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
					ABS(b.cantidad_movimiento) AS cantidad_entregada, 
					b.cantidad_movimiento_consignacion AS cantidad_facturada, 
					(ABS(b.cantidad_movimiento) - b.cantidad_movimiento_consignacion) AS cantidad_pendiente, 
					b.precio AS venta, 
					(b.precio_unidad * b.cantidad_movimiento_consignacion) AS facturado
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCNET') 
					AND a.estatus <> 'ANULADO' 
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
	$contar2 = 0;
	$venta = 0.00;
	$facturado = 0.00;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["nro_documento"] . '</td>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td>' . $row["articulo"] . '</td>';
		  $out .= '<td>' . intval($row["cantidad_entregada"]) . '</td>';
		  $out .= '<td>' . intval($row["cantidad_facturada"]) . '</td>';
		  $out .= '<td>' . intval($row["cantidad_pendiente"]) . '</td>';
		  $out .= '<td>' . number_format(floatval($row["venta"]), 2, ",", ".") . '</td>';
		  $out .= '<td>' . number_format(floatval($row["facturado"]), 2, ",", ".") . '</td>';
		$out .= '</tr>';
		$cnt++;
		$contar += intval($row["cantidad_entregada"]);
		$contar2 += intval($row["cantidad_facturada"]);
		$venta += floatval($row["venta"]);
		$facturado += floatval($row["facturado"]);
	}
	$out .= '<tr>
				<th colspan="9" class="text-right">
					Items: ' . number_format($cnt, 0, "", ".") . ' <br>
					Art&iacute;culos en Consignaci&oacute;n: ' . number_format($contar, 0, "", ".") . ' <br>
					Art&iacute;culos en Facturados: ' . number_format($contar2, 0, "", ".") . ' <br>
					Monto Venta: ' . number_format($venta, 2, ",", ".") . ' <br>
					Monto Facturado: ' . number_format($facturado, 2, ",", ".") . '
				</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>