<?php 
	$reporte = $id;

	$CurrentUserName = "admin";

	if($tipo != "") $where = "AND b.fabricante = '$tipo'";
	
	$fabricante = 0;
	$articulo = 0;
	$id = 0;
	$cantidad_movimiento = 0;

	$cantidad_entre_fechas = 0;
	$cantidad_acumulada = 0;
	$cantidad_ajuste = 0;

	$sql = "DELETE FROM temp_consignacion WHERE username = '" . $CurrentUserName . "';"; 
	mysqli_query($link, $sql);

	//AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
	$sql = "SELECT 
				b.fabricante, b.articulo, b.id, a.nro_documento, b.cantidad_movimiento, 
				a.id AS id_documento, a.tipo_documento 
			FROM 
				entradas AS a 
				JOIN entradas_salidas AS b ON b.id_documento = a.id 
					AND b.tipo_documento = a.tipo_documento 
			WHERE 
				a.consignacion = 'S' 
				AND a.consignacion_reportada = 'N' AND 
				a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
				$where 
			ORDER BY b.fabricante, b.articulo;"; 
	$rs = mysqli_query($link, $sql);
	while($row = mysqli_fetch_array($rs)) {
		$fabricante = $row["fabricante"];
		$articulo = $row["articulo"];
		$id = $row["id"];
		$nro_documento = $row["nro_documento"];
		$cantidad_movimiento = $row["cantidad_movimiento"];
		$id_documento = $row["id_documento"];
		$tipo_documento = $row["tipo_documento"];

		$sql2 = "SELECT 
					a.id, a.tipo_documento, ABS(a.cantidad_movimiento) AS cantidad_movimiento, b.factura, a.id_compra 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
				WHERE a.id_compra = $id AND b.estatus = 'PROCESADO';"; 
		$rs2 = mysqli_query($link, $sql2);
		while($row2 = mysqli_fetch_array($rs2)) {
			if($row2["tipo_documento"] == "TDCFCV" or ($row2["tipo_documento"] == "TDCASA" and $row2["factura"] == "S")) {
				$cantidad_acumulada += floatval($row2["cantidad_movimiento"]);
			} 
			else {
				$sql3 = "SELECT 
							a.id, a.tipo_documento, ABS(a.cantidad_movimiento) AS cantidad_movimiento, b.factura, a.id_compra 
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
						WHERE a.id_compra = " . $row2["id"] . "  AND b.estatus = 'PROCESADO';"; 
				$rs3 = mysqli_query($link, $sql3);
				while($row3 = mysqli_fetch_array($rs3)) {
					if($row3["tipo_documento"] == "TDCFCV" or ($row3["tipo_documento"] == "TDCASA" and $row3["factura"] == "S")) {
						$cantidad_acumulada += floatval($row3["cantidad_movimiento"]);
					} 
				}
			}
		}

		$sql2 = "SELECT 
					a.id, a.tipo_documento, ABS(a.cantidad_movimiento) AS cantidad_movimiento, b.factura, a.id_compra 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
				WHERE a.id_compra = $id AND b.estatus = 'PROCESADO' 
					AND b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
		$rs2 = mysqli_query($link, $sql2);
		while($row2 = mysqli_fetch_array($rs2)) {
			if($row2["tipo_documento"] == "TDCFCV" or ($row2["tipo_documento"] == "TDCASA" and $row2["factura"] == "S")) {
				$cantidad_entre_fechas += floatval($row2["cantidad_movimiento"]);
			} 
			else {
				$sql3 = "SELECT 
							a.id, a.tipo_documento, ABS(a.cantidad_movimiento) AS cantidad_movimiento, b.factura, a.id_compra 
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
						WHERE a.id_compra = " . $row2["id"] . "  AND b.estatus = 'PROCESADO' 
							AND b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
				$rs3 = mysqli_query($link, $sql3);
				while($row3 = mysqli_fetch_array($rs3)) {
					if($row3["tipo_documento"] == "TDCFCV" or ($row3["tipo_documento"] == "TDCASA" and $row3["factura"] == "S")) {
						$cantidad_entre_fechas += floatval($row3["cantidad_movimiento"]);
					} 
				}
			}
		}

		////////////////////////
		$sql4 = "SELECT 
					a.id, a.tipo_documento, ABS(a.cantidad_movimiento) AS cantidad_movimiento, b.factura, a.id_compra 
				FROM 
					entradas_salidas AS a 
					JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
				WHERE a.id_compra = $id AND b.estatus = 'PROCESADO' 
					AND b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
		$rs4 = mysqli_query($link, $sql4);
		$cantidad_ajuste = 0;
		while($row4 = mysqli_fetch_array($rs4)) {
			if($row4["tipo_documento"] == "TDCASA" and $row4["factura"] == "N") {
				$cantidad_ajuste += floatval($row4["cantidad_movimiento"]);
			} 
			else {
				$sql5 = "SELECT 
							a.id, a.tipo_documento, ABS(a.cantidad_movimiento) AS cantidad_movimiento, b.factura, a.id_compra 
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
						WHERE a.id_compra = " . $row4["id"] . "  AND b.estatus = 'PROCESADO' 
							AND b.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59';"; 
				$rs5 = mysqli_query($link, $sql5);
				
				while($row5 = mysqli_fetch_array($rs5)) {
					if($row5["tipo_documento"] == "TDCASA" and $row5["factura"] == "N") {
						$cantidad_ajuste += floatval($row5["cantidad_movimiento"]);
					} 
				}
			}
		} // if($articulo == 754) die("<br>Es: $cantidad_ajuste");
		////////////////////////

		$sql4 = "INSERT INTO temp_consignacion
					(id, username, nro_documento, id_documento, 
					tipo_documento, fabricante, articulo, 
					cantidad_movimiento, cantidad_entre_fechas, cantidad_acumulada, cantidad_ajuste)
				VALUES (NULL, '" . $CurrentUserName . "', '$nro_documento', $id_documento, 
					'$tipo_documento', $fabricante, $articulo, 
					$cantidad_movimiento, $cantidad_entre_fechas, $cantidad_acumulada, $cantidad_ajuste)"; 
		mysqli_query($link, $sql4);

		$cantidad_acumulada = 0;
		$cantidad_entre_fechas = 0;
	}

	$cnt = 0;


	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?username=' . $CurrentUserName . '&id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">TIPO</th>';
		  $out .= '<th scope="col">DOCUMENTO</th>';
		  $out .= '<th scope="col">LABORATORIO</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">CANTIDAD EN CONSIGACION</th>';
		  $out .= '<th scope="col">CANTIDAD VENTA PERIODO A REPORTAR</th>';
		  $out .= '<th scope="col">CANTIDAD VENTA ACUMULADA</th>';
		  $out .= '<th scope="col">AJUSTE SALIDA</th>';
		  $out .= '<th scope="col">CANTIDAD PENDIENTE</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	  $sql = "SELECT 
				a.tipo_documento, a.nro_documento, 
				b.nombre AS fabricante, c.codigo, 
				CONCAT(IFNULL(c.nombre_comercial, ''), ' ', IFNULL(c.principio_activo, ''), ' ', IFNULL(c.presentacion, '')) AS articulo, 
				a.cantidad_movimiento, a.cantidad_entre_fechas, a.cantidad_acumulada, a.cantidad_ajuste 
			FROM 
				temp_consignacion AS a 
				LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
				LEFT OUTER JOIN articulo AS c ON c.id = a.articulo 
			WHERE 
				username = '" . $CurrentUserName . "'
			ORDER BY 
				a.tipo_documento, a.nro_documento, b.nombre, articulo;";
	  $rs = mysqli_query($link, $sql);
	  while($row = mysqli_fetch_array($rs)) {
		$tipo_documento = $row["tipo_documento"]; 
		$nro_documento = $row["nro_documento"]; 
		$fabricante = $row["fabricante"]; 
		$codigo = $row["codigo"]; 
		$articulo = $row["articulo"]; 
		$cantidad_movimiento = $row["cantidad_movimiento"];
		$cantidad_entre_fechas = $row["cantidad_entre_fechas"];
		$cantidad_acumulada = $row["cantidad_acumulada"];
		$cantidad_ajuste = $row["cantidad_ajuste"];
		$cantidad_pendiente = $cantidad_movimiento - ($cantidad_acumulada + $cantidad_ajuste);


		$out .= '<tr>';
		  $out .= '<td>' . $tipo_documento . '</td>';
		  $out .= '<td>' . $nro_documento . '</td>';
		  $out .= '<td>' . $fabricante . '</td>';
		  $out .= '<td>' . $codigo . '</td>';
		  $out .= '<td>' . $articulo . '</td>';
		  $out .= '<td>' . number_format($cantidad_movimiento, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($cantidad_entre_fechas, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($cantidad_acumulada, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($cantidad_ajuste, 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($cantidad_pendiente, 2, '.', ',') . '</td>';
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