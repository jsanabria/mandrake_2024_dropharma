<?php 
	if($tipo != "") $where = "AND b.fabricante IN ($tipo)";
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">ALMACEN</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRINCIPO ACTIVO</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">LABORATORIO</th>';
		  $out .= '<th scope="col">MEDIDA</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		  $out .= '<th scope="col">ASESOR</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT
				d.codigo, f.descripcion AS almacen, c.nombre AS fabricante, 
				d.principio_activo, d.presentacion, 
				d.nombre_comercial, 
				e.descripcion AS unidad_medida, 
				ABS(SUM(b.cantidad_movimiento)) AS cantidad, 
				g.nombre as asesor_asignado  
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.id_documento = a.id 
				LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
				LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
				LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
				LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
				LEFT OUTER JOIN usuario AS g ON g.username = a.asesor_asignado 
			WHERE 
				a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
				AND a.estatus  = 'PROCESADO' AND b.tipo_documento IN ('TDCFCV','TDCASA') 
				AND IFNULL(a.documento, '') = 'FC' 
				$where 
			GROUP BY 
				d.codigo, f.descripcion, c.nombre, d.principio_activo, 
				d.presentacion, d.nombre_comercial, e.descripcion, g.nombre 
			ORDER BY fabricante, cantidad DESC;"; 

	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["almacen"] . '</td>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>';
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . $row["unidad_medida"] . '</td>';
		  $out .= '<td>' . $row["cantidad"] . '</td>';
		  $out .= '<td>' . $row["asesor_asignado"] . '</td>';
		$out .= '</tr>';
		$contar++;
	}
	$out .= '<tr>
				<th colspan="9" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>