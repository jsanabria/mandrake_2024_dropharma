<?php
	if($tipo != "") $where = "AND a.tarifa = $tipo";
	
	$contar = 0;

	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">DIRECCION</th>';
		  $out .= '<th scope="col">CIUDAD</th>';
		  $out .= '<th scope="col">ESTADO</th>';
		  $out .= '<th scope="col">TELEFONO 1</th>';
		  $out .= '<th scope="col">TELEFONO 2</th>';
		  $out .= '<th scope="col">RIF</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT DISTINCT  
				b.id AS codigo, 
				b.nombre, b.direccion, 
				c.campo_descripcion AS ciudad, 
				'' AS estado, 
				b.telefono1, b.telefono2, b.ci_rif 
			FROM 
				salidas AS a 
				JOIN cliente AS b ON b.id = a.cliente 
				LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD'  
			WHERE 
				a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
				AND a.documento = 'FC' 
				AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
    $rs = mysqli_query($link, $sql);

    while($row = mysqli_fetch_array($rs)) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["codigo"] . '</td>';
		  $out .= '<td>' . $row["nombre"] . '</td>';
		  $out .= '<td>' . $row["direccion"] . '</td>';
		  $out .= '<td>' . $row["ciudad"] . '</td>';
		  $out .= '<td>' . $row["estado"] . '</td>';
		  $out .= '<td>' . $row["telefono1"] . '</td>';
		  $out .= '<td>' . $row["telefono2"] . '</td>';
		  $out .= '<td>' . $row["ci_rif"] . '</td>';
		$out .= '</tr>';

		$contar++;
		if($contar >= 20) {
			$out .= '<tr>
				<th colspan="8" class="text-right">Se visualizan ' . $contar . ' registros - Exportar para ver todos los registros...</th>
			</tr>';
			break;
		}
	}
	$out .= '<tr>
				<th colspan="8" class="text-right">Clientes: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';

	//echo $out;
?>