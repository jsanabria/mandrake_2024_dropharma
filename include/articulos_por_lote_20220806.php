<?php 
$filename = "ARTICULOS_POR_LOTE_" . date('Ymd') . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

include 'connect.php';

$sql = "SELECT 
	a.id, a.codigo, a.codigo_de_barra, b.nombre AS fabricante, a.principio_activo, a.presentacion, a.nombre_comercial, 
	a.cantidad_en_mano, a.ultimo_costo, a.precio, a.activo  
FROM 
	articulo AS a 
	JOIN fabricante AS b ON b.id = a.fabricante 
ORDER BY 
	b.nombre, a.principio_activo, a.presentacion;"; 
$rs = mysqli_query($link, $sql);

if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}

$out = '<!DOCTYPE html>
		<html>
			<head>
				<title>ARTICULOS POR LOTE</title>
			</head>
			<body>';


$out .= '<div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
              	<th>ID</th>
              	<th>CODIGO</th>
              	<th>BARCODE</th>
                <th>FABRICANTE</th>
                <th>NOMBRE COMERCIAL</th>
                <th>PRINCIPIO ACTIVO</th>
                <th>PRESENTACION</th>
                <td><b>LOTE</b></td>
                <td><b>VENCIMIENTO</b></td>
                <td align="right"><b>CANTIDAD LOTE</b></td>
                <td align="right"><b>EXISTENCIA</b></td>
                <td align="right"><b>COSTO</b></td>
                <td align="right"><b>PRECIO</b></td>
                <th>ACTIVO</th>
              </tr>
            </thead>
            <tbody>';

while($row = mysqli_fetch_array($rs)) { 
	$id = intval($row["id"]);

	$sql2 = "SELECT 
				a.id, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
				(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad,
				a.id_documento ,a.tipo_documento, a.articulo 
			FROM 
				entradas_salidas AS a 
				JOIN entradas AS b ON
					b.tipo_documento = a.tipo_documento
					AND b.id = a.id_documento 
				JOIN almacen AS c ON
					c.codigo = a.almacen AND c.movimiento = 'S' 
				LEFT OUTER JOIN (
						SELECT 
							a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON
								b.tipo_documento = a.tipo_documento
								AND b.id = a.id_documento 
							LEFT OUTER JOIN almacen AS c ON
								c.codigo = a.almacen AND c.movimiento = 'S'
						WHERE
							a.tipo_documento IN ('TDCNET','TDCASA') 
							AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = $id 
						GROUP BY a.id_compra
					) AS d ON d.id = a.id 
			WHERE
				((a.tipo_documento IN ('TDCNRP','TDCAEN') 
				AND b.estatus = 'PROCESADO')
				 OR
				(a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
				AND b.estatus = 'NUEVO')) AND a.articulo = $id 
				AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0 
			ORDER BY a.fecha_vencimiento ASC;"; 
	$rs2 = mysqli_query($link, $sql2);

	if(!$rs2) {
		var_dump(mysqli_error($link));
		die();
	}

	$sw = false;
	while($row2 = mysqli_fetch_array($rs2)) { 
		$lote = $row2["lote"];
		$fecha = $row2["fecha_vencimiento"];
		$cantidad_lote = $row2["cantidad"];

		$out .= '<tr>
			<td>' . $id . '</td>
			<td>' . $row["codigo"] . '</td>
			<td>' . $row["codigo_de_barra"] . '</td>
			<td>' . $row["fabricante"] . '</td>
			<td>' . $row["nombre_comercial"] . '</td>
			<td>' . $row["principio_activo"] . '</td>
			<td>' . $row["presentacion"] . '</td>
			<td>' . $lote . '</td>
			<td>' . $fecha . '</td>
			<td align="right">' . number_format(floatval($cantidad_lote), 2, ",", ".") . '</td>
			<td align="right">' . number_format(floatval($row["cantidad_en_mano"]), 2, ",", ".") . '</td>
			<td align="right">' . number_format(floatval($row["ultimo_costo"]), 2, ",", ".") . '</td>
			<td align="right">' . number_format(floatval($row["precio"]), 2, ",", ".") . '</td>
			<td>' . $row["activo"] . '</td>
		</tr>';
		$sw = true;
	}

	if($sw == false) {
		$lote = "";
		$fecha = "";
		$cantidad_lote = 0;

		$out .= '<tr>
			<td>' . $id . '</td>
			<td>' . $row["codigo"] . '</td>
			<td>' . $row["codigo_de_barra"] . '</td>
			<td>' . $row["fabricante"] . '</td>
			<td>' . $row["nombre_comercial"] . '</td>
			<td>' . $row["principio_activo"] . '</td>
			<td>' . $row["presentacion"] . '</td>
			<td>' . $lote . '</td>
			<td>' . $fecha . '</td>
			<td align="right">' . number_format(floatval($cantidad_lote), 2, ",", ".") . '</td>
			<td align="right">' . number_format(floatval($row["cantidad_en_mano"]), 2, ",", ".") . '</td>
			<td align="right">' . number_format(floatval($row["ultimo_costo"]), 2, ",", ".") . '</td>
			<td align="right">' . number_format(floatval($row["precio"]), 2, ",", ".") . '</td>
			<td>' . $row["activo"] . '</td>
		</tr>';
	}
}

     $out .= '</tbody>
          </table>
        </div>';
$out .= '</div>';

$out .= '</body>
	</html>';

echo "$out";
?>
