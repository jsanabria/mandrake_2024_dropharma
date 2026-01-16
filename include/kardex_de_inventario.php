<?php 
	if($tipo != "") $where = "AND c.tarifa = $tipo";
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">EXISTENCIA (INV. INI)</th>';
		  $out .= '<th scope="col">COSTO UNI</th>';
		  $out .= '<th scope="col">COSTO</th>';
		  $out .= '<th scope="col">EXISTENCIA (MOV)</th>';
		  $out .= '<th scope="col">COSTO UNI</th>';
		  $out .= '<th scope="col">COSTO</th>';
		  $out .= '<th scope="col">EXISTENCIA (INV. FIN)</th>';
		  $out .= '<th scope="col">COSTO UNI</th>';
		  $out .= '<th scope="col">COSTO</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

$anoi = substr($fecha_desde, 0, 4);
$mesi = substr($fecha_desde, 5, 2);

if($mesi == "01") {
	$fecha_desde_i = strval(intval($anoi)-1) . "-12-01";
	$fecha_hasta_i = strval(intval($anoi)-1) . "-12-31";
}
else { 
	$mesi = str_pad(intval($mesi)-1, 2, "0", STR_PAD_LEFT);

	$fecha_desde_i = $anoi . "-$mesi-01"; 

	if($mesi == "02") {
		if((intval($anoi)%4 == 0 && intval($anoi)%100 != 0) || intval($anoi)%400 == 0) {
			$fecha_hasta_i = $anoi . "-$mesi-29";
		} 
		else {
			$fecha_hasta_i = $anoi . "-$mesi-28";
		}
	}
	else if($mesi == "04" or $mesi == "06" or $mesi == "09"  or $mesi == "11") {
		$fecha_hasta_i = $anoi . "-$mesi-30"; 
	} 
	else {
		$fecha_hasta_i = $anoi . "-$mesi-31"; 
	}
	
}
$fecha_desde_i = "2020-01-01";

	$sql = "SELECT 
				x.id, 
				x.fabricante, 
				x.nombre_comercial, x.principio_activo, x.presentacion,  
				y.existencia AS exit_ii, y.costo_unidad AS costo_und_ii, ABS((y.existencia * y.costo_unidad)) AS costo_ii,   
				z.existencia AS exit_mov, z.costo_unidad AS costo_und_mov, ABS((z.existencia * z.costo_unidad)) AS costo_mov,   
				-- 100 AS exit_mov, 200 AS costo_und_mov, 2000 AS costo_mov,   
				((IFNULL(y.entradas, 0) + IFNULL(z.entradas, 0)) - (IFNULL(y.salidas, 0) + IFNULL(z.salidas, 0))) AS exit_if, 
				x.ultimo_costo AS costo_und_if, 
				ABS((((IFNULL(y.entradas, 0) + IFNULL(z.entradas, 0)) - (IFNULL(y.salidas, 0) + IFNULL(z.salidas, 0))) * x.ultimo_costo)) AS costo_if  
			FROM 
				(
					SELECT 
						a.id, 
						b.nombre AS fabricante, a.nombre_comercial, a.principio_activo, a.presentacion, 
						a.cantidad_en_mano, a.ultimo_costo  
					FROM 
						articulo AS a JOIN fabricante AS b ON b.Id = a.fabricante 
					WHERE 1 
				) AS x  
				LEFT OUTER JOIN 
				(
					SELECT 
						art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
						'UNIDAD' AS unidad_medida, art.principio_activo, 
						art.presentacion, art.nombre_comercial, 
						ent.cantidad AS entradas, ABS(sal.cantidad) AS salidas, 
						(IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad, 0))) AS existencia, 
						( 
							SELECT 
								IFNULL(cc.costo_unidad, 0) AS costo_unidad 
							FROM 
								entradas_salidas AS cc 
								JOIN salidas AS cs ON cs.id = cc.id_documento AND cs.tipo_documento = cc.tipo_documento 
							WHERE 
								cs.tipo_documento = 'TDCFCV' AND cs.estatus = 'PROCESADO' 
								AND cs.fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 00:00:00' AND cc.articulo = art.id 
							ORDER BY cc.id DESC LIMIT 0, 1
						) AS costo_unidad 
					FROM 
						(
							SELECT 
								a.id, a.codigo, a.codigo_de_barra, b.nombre, 
								'UNIDAD' AS unidad_medida, a.principio_activo, 
								a.presentacion, a.nombre_comercial 
							FROM 
								articulo AS a 
								LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
							WHERE 
								0 = 0
						) AS art 
						LEFT OUTER JOIN 
						(
							SELECT 
								a.articulo, SUM(IFNULL(a.cantidad_movimiento,0)) AS cantidad  
							FROM 
								entradas_salidas AS a 
								JOIN salidas AS b ON
									b.tipo_documento = a.tipo_documento
									AND b.id = a.id_documento 
								JOIN almacen AS c ON
									c.codigo = a.almacen AND c.movimiento = 'S' 
							WHERE
								a.tipo_documento IN ('TDCNET', 'TDCASA') 
								AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
								b.fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 00:00:00' 
							GROUP BY a.articulo
						) AS sal ON sal.articulo = art.Id 
						LEFT OUTER JOIN 
						(
							SELECT 
								a.articulo, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad 
							FROM 
								entradas_salidas AS a 
								JOIN entradas AS b ON
									b.tipo_documento = a.tipo_documento
									AND b.id = a.id_documento 
								JOIN almacen AS c ON
									c.codigo = a.almacen AND c.movimiento = 'S'
							WHERE
								((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
								AND b.estatus = 'PROCESADO') OR 
								(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
								AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
								b.fecha BETWEEN '$fecha_desde_i 00:00:00' AND '$fecha_hasta_i 00:00:00' 
							GROUP BY a.articulo
						) AS ent ON ent.articulo = art.Id 
					WHERE ent.cantidad > 0 OR ABS(sal.cantidad) > 0
				) AS y ON y.id = x.id 
				LEFT OUTER JOIN 
				(
					SELECT 
						art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
						'UNIDAD' AS unidad_medida, art.principio_activo, 
						art.presentacion, art.nombre_comercial, 
						ent.cantidad AS entradas, ABS(sal.cantidad) AS salidas, 
						(ABS(IFNULL(ent.cantidad, 0)) - ABS(IFNULL(sal.cantidad, 0))) AS existencia,  
						( 
							SELECT 
								IFNULL(cc.costo_unidad, 0) AS costo_unidad 
							FROM 
								entradas_salidas AS cc 
								JOIN salidas AS cs ON cs.id = cc.id_documento AND cs.tipo_documento = cc.tipo_documento 
							WHERE 
								cs.tipo_documento = 'TDCFCV' AND cs.estatus = 'PROCESADO' 
								AND cs.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 00:00:00' AND cc.articulo = art.id 
							ORDER BY cc.id DESC LIMIT 0, 1
						) AS costo_unidad 
					FROM 
						(
							SELECT 
								a.id, a.codigo, a.codigo_de_barra, b.nombre, 
								'UNIDAD' AS unidad_medida, a.principio_activo, 
								a.presentacion, a.nombre_comercial 
							FROM 
								articulo AS a 
								LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante  
							WHERE 
								0 = 0
						) AS art 
						LEFT OUTER JOIN 
						(
							SELECT 
								a.articulo, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad  
							FROM 
								entradas_salidas AS a 
								JOIN salidas AS b ON
									b.tipo_documento = a.tipo_documento
									AND b.id = a.id_documento 
								JOIN almacen AS c ON
									c.codigo = a.almacen AND c.movimiento = 'S' 
							WHERE
								a.tipo_documento IN ('TDCNET', 'TDCASA') 
								AND b.estatus <> 'ANULADO' AND b.activo = 'S' AND 
								b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 00:00:00' 
							GROUP BY a.articulo
						) AS sal ON sal.articulo = art.Id 
						LEFT OUTER JOIN 
						(
							SELECT 
								a.articulo, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad 
							FROM 
								entradas_salidas AS a 
								JOIN entradas AS b ON
									b.tipo_documento = a.tipo_documento
									AND b.id = a.id_documento 
								JOIN almacen AS c ON
									c.codigo = a.almacen AND c.movimiento = 'S'
							WHERE
								((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
								AND b.estatus = 'PROCESADO') OR 
								(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
								AND b.estatus <> 'ANULADO') AND b.consignacion = 'S') AND 
								b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 00:00:00' 
							GROUP BY a.articulo
						) AS ent ON ent.articulo = art.Id 
					WHERE ent.cantidad > 0 OR ABS(sal.cantidad) > 0
				) AS z ON z.id = x.id ORDER BY x.fabricante, x.principio_activo, x.presentacion;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	$i = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>'; 
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["exit_ii"] . '</td>';
		  $out .= '<td>' . number_format($row["costo_und_ii"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($row["costo_ii"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . $row["exit_mov"] . '</td>';
		  $out .= '<td>' . number_format($row["costo_und_mov"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($row["costo_mov"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . $row["exit_if"] . '</td>';
		  $out .= '<td>' . number_format($row["costo_und_if"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($row["costo_if"], 2, '.', ',') . '</td>';
		$out .= '</tr>';

		if($i >= 20) {
			$out .= '<tr>
				<th colspan="9" class="text-right">Se visualizan 20 registros - Exportar para ver todos los registros...</th>
			</tr>';
			break;
		}
		$contar++;
	}
	$out .= '<tr>
				<th colspan="13" class="text-right">Items: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>