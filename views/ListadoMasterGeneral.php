<?php

namespace PHPMaker2024\mandrake;

// Page object
$ListadoMasterGeneral = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_GET["id"];
$codigo = $_GET["codigo"];

$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];

$out = '';

/*$f = explode("-", $fecha_desde);
$fecdesde = $f["1"] . "-" . $f["2"] . "-" . $f["0"];
$f = explode("-", $fecha_hasta);
$fechasta = $f["1"] . "-" . $f["2"] . "-" . $f["0"];*/

$where = "";
switch($id) {
case "VENTAS POR ARTICULO":
	if($codigo != "") $where = "AND d.id IN ($codigo)";
	
	//$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">NOMBRE COMERCIAL</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">DOCUMENTO</th>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">CANTIDAD</th>';
		  $out .= '<th scope="col">PRECIO UNIDAD</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

  	$sql = "SELECT
					d.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, b.precio_unidad, 
					d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre AS fabricante,  
					g.id AS codigo, 
					g.nombre AS cliente, 
					SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
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
				GROUP BY d.id, a.nro_documento, date_format(a.fecha, '%d/%m/%Y'),  b.precio_unidad, g.id, g.nombre, c.nombre  
				ORDER BY cantidad_movimiento DESC;"; 
	$rows = ExecuteRows($sql);
	$contar = count($rows);

	$art = 0;
  	for($i = 0; $i < $contar; $i++) {
  		$row = $rows[$i];

		$out .= '<tr>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>';
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . $row["nro_documento"] . '</td>';
		  $out .= '<td>' . $row["fecha"] . '</td>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["cantidad_movimiento"] . '</td>';
		  $out .= '<td>' . $row["precio_unidad"] . '</td>';
		$out .= '</tr>';
		$art += intval($row["cantidad_movimiento"]);
	}
	$out .= '<tr>
				<th colspan="9" class="text-right">Clientes ' . number_format($contar, 0, "", ".") . ' - Unidades: ' . number_format($art, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
	break;
case "SALIDAS GENERALES POR ARTICULO":
	//if($codigo != "") $where = "AND d.codigo = '$codigo'";
	if($codigo != "") $where = "AND d.id = '$codigo'";
	

	//$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">TIPO</th>';
		  $out .= '<th scope="col">DOCUMENTO</th>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE COMERCIAL</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">CANT</th>';
		  $out .= '<th scope="col">PRECIO UNIDAD</th>';
		  $out .= '<th scope="col">TOTAL ARTICULO</th>';
		  $out .= '<th scope="col">TOTAL SIN IVA</th>';
		  $out .= '<th scope="col">TOTAL FACTURA</th>';
		  $out .= '<th scope="col">UNIDADES</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';


	$sql = "SELECT
					a.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					g.id AS codigo, 
					g.nombre AS cliente,
					c.nombre AS fabricante, 
					ABS(b.cantidad_movimiento) AS cantidad_movimiento,
					b.precio_unidad, 
					b.precio AS total_articulo, 
					a.monto_total, a.total,  
					(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
					d.nombre_comercial, d.principio_activo, d.presentacion, d.codigo AS codart, 
					a.unidades 
				FROM 
					salidas AS a 
					JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
					LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
					LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
					LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
				WHERE 
					(
					(a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' AND a.documento = 'FC') 
					OR (a.tipo_documento = 'TDCASA' AND a.estatus = 'PROCESADO' AND a.factura = 'S')
					)
					AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
					$where 
				ORDER BY cantidad_movimiento DESC;"; 
	
	$rows = ExecuteRows($sql);
	$contar = count($rows);
	  
	$art = 0;
  	for($i = 0; $i < $contar; $i++) {
		$row = $rows[$i];

		$out .= '<tr>';
		  $out .= '<td>' . $row["tipo"] . '</td>';
		  $out .= '<td>' . $row["nro_documento"] . '</td>';
		  $out .= '<td>' . $row["fecha"] . '</td>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["codart"] . '</td>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>';
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . intval($row["cantidad_movimiento"]) . '</td>';
		  $out .= '<td>' . $row["precio_unidad"] . '</td>';
		  $out .= '<td>' . $row["total_articulo"] . '</td>';
		  $out .= '<td>' . $row["monto_total"] . '</td>';
		  $out .= '<td>' . $row["total"] . '</td>';
		  $out .= '<td>' . intval($row["unidades"]) . '</td>';
		$out .= '</tr>';
		$art += intval($row["cantidad_movimiento"]);
	}
	$out .= '<tr>
				<th colspan="15" class="text-right">Registros ' . number_format($contar, 0, "", ".") . ' - Unidades: ' . number_format($art, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';
	break;
case "otros": // Para configurarlo más adelante, por los momnetos funcionara el primero
	break;
default:
	die("El reporte no existet...");
}

// $out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';

echo $out;

?>


<?= GetDebugMessage() ?>
