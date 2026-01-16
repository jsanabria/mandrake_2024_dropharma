<?php 
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE COMERCIAL</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
					d.id,
					d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre AS fabricante, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					b.tipo_documento IN ('TDCFCV', 'TDCASA') AND a.estatus = 'PROCESADO'
					-- AND IFNULL(a.documento, '') IN ('FC', '') AND a.activo = 'S' 
					AND (IFNULL(a.documento, '') = 'FC' OR (IFNULL(a.documento, '') = '' AND a.factura = 'S')) AND a.activo = 'S' 
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				GROUP BY d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
				ORDER BY cantidad_movimiento DESC"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td><a href="ListadoMasterGeneral?id=' . $id . '&codigo=' . $row["id"] . '&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '" target="_blank">' . $row["codigo"] . '</a></td>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>';
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . $row["cantidad_movimiento"] . '</td>';
		$out .= '</tr>';
		$contar++;
	}
	$out .= '<tr>
				<th colspan="6" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>