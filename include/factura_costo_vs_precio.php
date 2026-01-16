<?php 
	if($tipo != "") $where = "AND c.tarifa = $tipo";
	
	$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">ARTICULO</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">UNIDADES</th>';
		  $out .= '<th scope="col">COSTO</th>';
		  $out .= '<th scope="col">PRECIO</th>';
		  $out .= '<th scope="col">FECHA</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT 
				c.nombre AS fabricante, 
				LPAD(a.nro_documento, 12, '0') AS codigo, 
				CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' ')) AS articulo, 
				d.id AS codigo_articulo, 
				g.nombre AS cliente, 
				g.id AS codigo_cliente, 
				ABS(b.cantidad_movimiento) AS cantidad_movimiento, 
				b.costo_unidad, 
				-- d.ultimo_costo AS costo_unidad, // NO USAR -- Queda siempre el ultimo precio de compra y descuadra el margen de ganacia. Al facturar se debe guardadr es el ultimo precio de compra o ultimo_costo que queda registrado en la tabla artículos y no el costo de compra del lote correspondiente
				b.precio_unidad, 
				date_format(a.fecha, '%d/%m/%Y') AS fecha 
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
				LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
				LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
				LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
			WHERE 
				a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO'
				AND a.documento = 'FC' 
				AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' ORDER BY a.fecha;"; 
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
		  $out .= '<td>' . $row["articulo"] . '</td>';
		  $out .= '<td>' . $row["codigo_articulo"] . '</td>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["codigo_cliente"] . '</td>';
		  $out .= '<td>' . $row["cantidad_movimiento"] . '</td>';
		  $out .= '<td>' . number_format($row["costo_unidad"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . number_format($row["precio_unidad"], 2, '.', ',') . '</td>';
		  $out .= '<td>' . $row["fecha"] . '</td>';
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
				<th colspan="9" class="text-right">Items: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
?>