<?php
	if($tipo != "") $where = "AND b.tarifa = $tipo";
	else $where = "AND b.tarifa = 2";
	
	$contar = 0;

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">LABORATORIO</th>';
		  $out .= '<th scope="col">PRECIO</th>';
		  $out .= '<th scope="col">BARRA</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT DISTINCT 
				d.id AS codigo, 
				CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS nombre, 
				IFNULL(c.nombre, ' ') AS fabricante, 
				IFNULL(f.precio, 0) AS precio, 
				IFNULL(d.codigo_de_barra, ' ') AS codigo_de_barra  
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
				LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
				LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
				LEFT OUTER JOIN tarifa_articulo AS f ON f.fabricante = b.fabricante AND f.articulo = b.articulo AND f.tarifa = 2 
			WHERE 
				a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
				AND a.documento = 'FC'  
				AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
	$rs = mysqli_query($link, $sql);

	while($row = mysqli_fetch_array($rs)) {
  		// LPAD(a.id, 12, '0') AS codigo, 
  		// LPAD(LTRIM(REPLACE(CAST(f.precio AS CHAR), '.', '')), 8, '0') AS precio, 


		$out .= '<tr>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td>' . $row["nombre"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . number_format($row["precio"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . $row["codigo_de_barra"] . '</td>';
		$out .= '</tr>';

		$contar++;
		if($contar >= 20) {
			$out .= '<tr>
				<th colspan="5" class="text-right">Se visualizan ' . $contar . ' registros - Exportar para ver todos los registros...</th>
			</tr>';
			break;
		}
	}
	$out .= '<tr>
				<th colspan="5" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>