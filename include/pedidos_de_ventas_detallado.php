<?php 
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">TIPO</th>';
		  $out .= '<th scope="col">DOCUMENTO</th>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE COMERCIAL</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">CANT</th>';
		  $out .= '<th scope="col">ESTATUS</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT
					a.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					g.id AS codigo, 
					g.nombre AS cliente, 
					d.codigo AS codart, 
					d.nombre_comercial, d.principio_activo, d.presentacion, 
					c.nombre AS fabricante, 
					ABS(b.cantidad_movimiento) AS cantidad_movimiento,
					b.precio_unidad, 
					b.precio, 
					(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, a.estatus   
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					(
					(a.tipo_documento = 'TDCPDV' AND a.estatus IN ('NUEVO', 'PROCESADO')) 
					)
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["tipo"] . '</td>';
		  $out .= '<td>' . $row["nro_documento"] . '</td>';
		  $out .= '<td>' . $row["fecha"] . '</td>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["codart"] . '</td>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>';
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . intval($row["cantidad_movimiento"]) . '</td>';
		  $out .= '<td>' . $row["estatus"] . '</td>';
		$out .= '</tr>';
		$contar++;
	}
	$out .= '<tr>
				<th colspan="11" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>