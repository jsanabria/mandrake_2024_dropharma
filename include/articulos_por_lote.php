<?php 
$filename = "ARTICULOS_POR_LOTE_" . date('Ymd') . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

include 'connect.php';

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$rs = mysqli_query($link, $sql);
$tipo_documento = 'TDCNET';
if($row = mysqli_fetch_array($rs)) $tipo_documento = $row["tipo_documento"];

$sql = "SELECT 
	a.id, a.codigo_de_barra, a.codigo, 
	a.nombre_comercial, a.principio_activo, a.presentacion, b.nombre AS fabricante, 
	a.cantidad_en_mano, a.ultimo_costo, a.precio, a.activo  
FROM 
	articulo AS a 
	JOIN fabricante AS b ON b.id = a.fabricante 
ORDER BY 
	b.nombre, a.nombre_comercial, a.principio_activo, a.presentacion;"; 
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
              	<th>BARCODE</th>
              	<th>CODIGO</th>
                <th>NOMBRE COMERCIAL</th>
                <th>PRINCIPIO ACTIVO</th>
                <th>PRESENTACION</th>
                <th>FABRICANTE</th>
                <td><b>LOTE</b></td>
                <td><b>VENCIMIENTO</b></td>
                <td><b>ALMACEN</b></td>
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

	$onhand = intval($row["cantidad_en_mano"]);

	if($onhand > 0) {
		// **** Traigo la Existencia por Lotes **** //
		$sql2 = "SELECT 
                    x.almacen, x.articulo, x.lote, x.fecha AS fecha_vencimiento, x.fecha_vencimiento AS fecha, SUM(x.cantidad_movimiento) AS cantidad 
                FROM 
                    (
                        SELECT 
                            c.descripcion AS almacen, a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                            a.fecha_vencimiento, 
                            a.cantidad_movimiento 
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
                            ) AND a.articulo = $id AND a.newdata = 'S' 
                        UNION ALL SELECT 
                            c.descripcion AS almacen, a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                            a.fecha_vencimiento, 
                            a.cantidad_movimiento  
                        FROM 
                            entradas_salidas AS a 
                            JOIN salidas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                            JOIN almacen AS c ON
                                c.codigo = a.almacen AND c.movimiento = 'S'
                        WHERE 
                            (
                                (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                            ) AND a.articulo = $id AND a.newdata = 'S' 
                    ) AS x 
                WHERE 1 
                GROUP BY x.almacen, x.articulo, x.lote, x.fecha, x.fecha_vencimiento  
                HAVING SUM(x.cantidad_movimiento) <> 0 
                ORDER BY x.fecha ASC;"; 
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
			$almacen = $row2["almacen"];

			$out .= '<tr>
				<td>' . $id . '</td>
				<td>' . $row["codigo_de_barra"] . '</td>
				<td>' . $row["codigo"] . '</td>
				<td>' . $row["nombre_comercial"] . '</td>
				<td>' . $row["principio_activo"] . '</td>
				<td>' . $row["presentacion"] . '</td>
				<td>' . $row["fabricante"] . '</td>
				<td>' . $lote . '</td>
				<td>' . $fecha . '</td>
				<td>' . $almacen . '</td>
				<td align="right">' . number_format(floatval($cantidad_lote), 2, ",", ".") . '</td>
				<td align="right">' . number_format(floatval($row["cantidad_en_mano"]), 2, ",", ".") . '</td>
				<td align="right">' . number_format(floatval($row["ultimo_costo"]), 2, ",", ".") . '</td>
				<td align="right">' . number_format(floatval($row["precio"]), 2, ",", ".") . '</td>
				<td>' . $row["activo"] . '</td>
			</tr>';
			$sw = true;
		}
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
