<?php
$filename = "COSTO_VS_PRECIO_" . date('Ymd') . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

if($tipo != "") $where = "AND c.tarifa = $tipo";
// LPAD(a.nro_documento, 12, '0') AS codigo, 
$sql = "SELECT
				c.nombre AS fabricante, 
				d.nombre_comercial, 
				d.principio_activo, 
				d.presentacion, 
				g.nombre AS cliente, 
				ABS(b.cantidad_movimiento) AS cantidad, 
				-- b.costo_unidad, 
				-- d.ultimo_costo AS costo_unidad, // NO USAR -- Queda siempre el ultimo precio de compra y descuadra el margen de ganacia. Al facturar se debe guardadr es el ultimo precio de compra o ultimo_costo que queda registrado en la tabla artículos y no el costo de compra del lote correspondiente
				d.ultimo_costo AS costo_unidad, 
				(d.ultimo_costo * abs(b.cantidad_movimiento)) as costo, 
				b.precio_unidad, 
				b.precio, 
				b.precio - (d.ultimo_costo * abs(b.cantidad_movimiento)) AS margen, 
				(((b.precio - (d.ultimo_costo * abs(b.cantidad_movimiento))) / b.precio) * 100) AS porcentaje, 
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
				AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
				$where 
			ORDER BY a.fecha;"; 
$rs = mysqli_query($link, $sql);

if(!$rs) {
	var_dump(mysqli_error($link));
	die();
}

$out = '<div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>FABRICANTE</th>
                <th>NOMBRE COMERCIAL</th>
                <th>PRINCIPIO ACTIVO</th>
                <th>PRESENTACION</th>
                <th>CLIENTE</th>
                <th>CANTIDAD</th>
                <td align="right"><b>COSTO UNIDAD</b></td>
                <td align="right"><b>COSTO</b></td>
                <td align="right"><b>PRECIO UNIDAD</b></td>
                <td align="right"><b>PRECIO</b></td>
                <td align="right"><b>MARGEN</b></td>
                <td align="right"><b>PORCENTAJE</b></td>
                <td align="right"><b>FECHA</b></td>
              </tr>
            </thead>
            <tbody>';

while($row = mysqli_fetch_array($rs)) {
	$out .= '<tr>
		<td>' . $row["fabricante"] . '</td>
		<td>' . $row["nombre_comercial"] . '</td>
		<td>' . $row["principio_activo"] . '</td>
		<td>' . $row["presentacion"] . '</td>
		<td>' . $row["cliente"] . '</td>
		<td>' . number_format(floatval($row["cantidad"]), 2, ",", ".") . '</td>
		<td align="right">' . number_format(floatval($row["costo_unidad"]), 2, ",", ".") . '</td>
		<td align="right">' . number_format(floatval($row["costo"]), 2, ",", ".") . '</td>
		<td align="right">' . number_format(floatval($row["precio_unidad"]), 2, ",", ".") . '</td>
		<td align="right">' . number_format(floatval($row["precio"]), 2, ",", ".") . '</td>
		<td align="right">' . number_format(floatval($row["margen"]), 2, ",", ".") . '</td>
		<td align="right">' . number_format(floatval($row["porcentaje"]), 2, ",", ".") . '</td>
		<td>' . $row["fecha"] . '</td>
	</tr>';
}

     $out .= '</tbody>
          </table>
        </div>';
$out .= '</div>';

echo "$out";
?>