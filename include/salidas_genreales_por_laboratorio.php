<?php 
	$where = "";
	$where2 = "";
	if($tipo != "") {
		$where = "AND b.fabricante IN ($tipo)";
		$where2 = "AND a.fabricante IN ($tipo)";
	}
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">ALMACEN</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE COMERCIAL</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">MEDIDA</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
				i.codigo, j.almacen, i.fabricante, 
				i.principio_activo, i.presentacion, i.nombre_comercial, j.unidad_medida, 
				j.cantidad 
			FROM 
				(SELECT 
					a.id AS articulo, a.codigo, b.nombre AS fabricante, 
					a.principio_activo, a.presentacion, a.nombre_comercial 
				FROM 
					articulo AS a 
					JOIN fabricante AS b ON b.Id = a.fabricante 
				WHERE 
					0=0 $where2) AS i 
				LEFT OUTER JOIN  
				(SELECT
					b.articulo, d.codigo, f.descripcion AS almacen, c.nombre AS fabricante, 
					d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion AS unidad_medida, 
					ABS(SUM(b.cantidad_movimiento)) AS cantidad  
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
					LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
				WHERE 
					a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					AND a.estatus  = 'PROCESADO' AND b.tipo_documento IN ('TDCFCV','TDCASA') 
					-- AND IFNULL(a.documento, '') IN ('FC', '') AND a.activo = 'S' 
					AND (IFNULL(a.documento, '') = 'FC' OR (IFNULL(a.documento, '') = '' AND a.factura = 'S'))
					$where 
				GROUP BY 
					b.articulo, d.codigo, f.descripcion, c.nombre, 
					d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion) AS j ON j.articulo = i.articulo 
			ORDER BY i.fabricante, j.almacen DESC, j.cantidad DESC;"; 

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
		$out .= '</tr>';
		$contar++;
	}
	$out .= '<tr>
				<th colspan="8" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>