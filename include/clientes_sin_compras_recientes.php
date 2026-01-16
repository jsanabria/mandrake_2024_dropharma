<?php 
	if($tipo != "") $where = "AND c.asesor = $tipo";
	

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">DIRECCION</th>';
		  $out .= '<th scope="col">CIUDAD</th>';
		  $out .= '<th scope="col">TELEFONO 1</th>';
		  $out .= '<th scope="col">TELEFONO 2</th>';
		  $out .= '<th scope="col">RIF</th>';
		  $out .= '<th scope="col">ASESOR</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';
 	
	$sql = "SELECT 
								f.codigo, 
								f.nombre, f.direccion, 
								f.ciudad, 
								f.telefono1, f.telefono2, f.ci_rif,
								f.asesor 
							FROM 
			(SELECT 
								a.id AS codigo, 
								a.nombre, a.direccion, 
								b.campo_descripcion AS ciudad, 
								a.telefono1, a.telefono2, a.ci_rif,
								d.nombre AS asesor 
							FROM 
								cliente AS a 
								LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
								JOIN asesor_cliente AS c ON c.cliente = a.id $where 
								JOIN asesor AS d ON d.id = c.asesor) AS f 
			LEFT OUTER JOIN  
			(SELECT  
								b.id AS codigo, 
								b.nombre, b.direccion, 
								c.campo_descripcion AS ciudad, 
								b.telefono1, b.telefono2, b.ci_rif 
							FROM 
								salidas AS a 
								JOIN cliente AS b ON b.id = a.cliente 
								LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
							WHERE 
								a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
								AND a.documento = 'FC' 
								AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
								GROUP BY b.id, b.nombre, b.direccion, c.campo_descripcion, b.telefono1, b.telefono2, b.ci_rif) g 
				ON g.codigo = f.codigo where g.nombre IS NULL;"; 
	$rs = mysqli_query($link, $sql);

	$contar = 0;
  	while($row = mysqli_fetch_array($rs)) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td>' . $row["nombre"] . '</td>';
		  $out .= '<td>' . $row["direccion"] . '</td>';
		  $out .= '<td>' . $row["ciudad"] . '</td>';
		  $out .= '<td>' . $row["telefono1"] . '</td>';
		  $out .= '<td>' . $row["telefono2"] . '</td>';
		  $out .= '<td>' . $row["ci_rif"] . '</td>';
		  $out .= '<td>' . $row["asesor"] . '</td>';
		$out .= '</tr>';

		$contar++;
	}
	$out .= '<tr>
				<th colspan="8" class="text-right">Clientes: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>