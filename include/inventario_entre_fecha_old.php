<?php 
	$reporte = $id;

	if($tipo != "") $where = "AND a.almacen = '$tipo'";

    $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
	$rs = mysqli_query($link, $sql);
    $tipo_documento = 'TDCNET';
  	if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">LABORATORIO</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">CODIGO BARRA</th>';
		  $out .= '<th scope="col">UNIDAD MEDIDA</th>';
		  $out .= '<th scope="col">ENTRADAS</th>';
		  $out .= '<th scope="col">SALIDA</th>';
		  $out .= '<th scope="col">EXISTENCIA</th>';
		  $out .= '<th scope="col">COSTO C/U</th>';
		  $out .= '<th scope="col">PRECIO C/U</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$contar = 0;
	$cnt = 0;

	$sql = "SELECT 
				art.id, art.codigo, art.codigo_de_barra, art.nombre AS laboratorio, 
				'UNIDAD' AS unidad_medida, art.principio_activo, 
				art.presentacion, art.nombre_comercial, 
				IFNULL(ent.cantidad, 0) AS entradas, ABS(IFNULL(sal.cantidad,0)) AS salidas, 
				(IFNULL(ent.cantidad, 0) - ABS(IFNULL(sal.cantidad,0))) AS existencia 
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
                        a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
                    FROM 
                        entradas_salidas AS a 
                        JOIN salidas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                            (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                        ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
						$where 
					GROUP BY a.articulo
				) AS sal ON sal.articulo = art.Id 
				LEFT OUTER JOIN 
				(
                    SELECT 
                        a.articulo, SUM(a.cantidad_movimiento) AS cantidad 
                    FROM 
                        entradas_salidas AS a 
                        JOIN entradas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                        JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE 
                        (
                            (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                            (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                        ) AND b.fecha < '$fecha_hasta 23:59:59' AND a.newdata = 'S' 
						$where 
					GROUP BY a.articulo
				) AS ent ON ent.articulo = art.Id 
			WHERE 1 ORDER BY art.nombre, art.principio_activo, art.presentacion;";  
	$rs = mysqli_query($link, $sql);
  	while($row = mysqli_fetch_array($rs)) {
  		$idArt = $row["id"]; 
		$laboratorio = $row["laboratorio"]; 
		$codigo = $row["codigo"]; 
		$codigo_de_barra = $row["codigo_de_barra"]; 
		$nombre = $row["nombre_comercial"] . ' ' . $row["principio_activo"] . ' ' . $row["presentacion"]; 
		$unidad_medida = $row["unidad_medida"];
		$entradas = $row["entradas"];
		$salidas = $row["salidas"];
		$existencia = $row["existencia"];

		$sql = "SELECT ultimo_costo, precio FROM articulo WHERE id = $idArt";
		$rs2 = mysqli_query($link, $sql);
		$row2 = mysqli_fetch_array($rs2);
		$costo = $row2["ultimo_costo"];
		$precio = $row2["precio"];

		$out .= '<tr>';
		  $out .= '<td>' . $laboratorio . '</td>';
		  $out .= '<td>' . $nombre . '</td>';
		  $out .= '<td>' . $codigo . '</td>';
		  $out .= '<td>' . $codigo_de_barra . '</td>';
		  $out .= '<td>' . $unidad_medida . '</td>';
		  $out .= '<td>' . number_format($entradas, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($salidas, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($existencia, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($costo, 2, ',', '.') . '</td>';
		  $out .= '<td>' . number_format($precio, 2, ',', '.') . '</td>';
		$out .= '</tr>';

		$cnt++;
	}
	$out .= '<tr>
				<th colspan="10" class="text-right">Items: ' . number_format($cnt, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';

	$id = $reporte;
?>