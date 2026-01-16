<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=SalidasGeneralesPorArticuloDetallado.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $id = $_REQUEST["id"];
    $fecha_desde = $_REQUEST["fd"];
    $fecha_hasta = $_REQUEST["fh"];
    $tipo = $_REQUEST["tipo"];

    include 'connect.php';
  }
} 

	$where = '';
	if($tipo != "") $where = "AND d.categoria IN ($tipo)";
	
	//$out .= '<button class="btn btn-primary" onclick="js:window.location.href = \'listado_master_buscar_excel.php?id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out = '<button class="btn btn-primary" onclick="js:window.location.href = \'include/salidas_genreales_por_articulo_detallado.php?toexcel=SI&id=' . $id .'&fd=' . $fecha_desde .'&fh=' . $fecha_hasta. '&tipo=' . $tipo . '\'">Exportar a TXT/XLS</button>';
	$out .= '<table class="table table-hover table-bordered">';
	  $out .= '<thead>';
		$out .= '<tr>';
		  $out .= '<th scope="col">ID</th>';
		  $out .= '<th scope="col">DOCUMENTO</th>';
		  $out .= '<th scope="col">FECHA</th>';
		  $out .= '<th scope="col">CLIENTE</th>';
		  $out .= '<th scope="col">ASESOR</th>';
		  $out .= '<th scope="col">CIUDAD</th>';
		  $out .= '<th scope="col">CODIGO</th>';
		  $out .= '<th scope="col">NOMBRE COMERCIAL</th>';
		  $out .= '<th scope="col">NOMBRE</th>';
		  $out .= '<th scope="col">PRESENTACION</th>';
		  $out .= '<th scope="col">FABRICANTE</th>';
		  $out .= '<th scope="col">LOTE</th>';
		  $out .= '<th scope="col">VENCIMIENTO</th>';
		  $out .= '<th scope="col">CANT</th>';
		  $out .= '<th scope="col">PRECIO UNIDAD</th>';
		  $out .= '<th scope="col">TOTAL ARTICULO</th>';
		  $out .= '<th scope="col">TOTAL SIN IVA</th>';
		  $out .= '<th scope="col">IVA</th>';
		  $out .= '<th scope="col">TOTAL FACTURA</th>';
		  $out .= '<th scope="col">TIPO</th>';
		  $out .= '<th scope="col">UNIDADES</th>';
		  $out .= '<th scope="col">DOC NE</th>';
		$out .= '</tr>';
	  $out .= '</thead>';
	  $out .= '<tbody>';

	$sql = "SELECT
					a.id, 
					a.nro_documento, 
					date_format(a.fecha, '%d/%m/%Y') AS fecha, 
					g.id AS codigo, 
					g.nombre AS cliente, (SELECT nombre FROM usuario WHERE username = a.asesor_asignado LIMIT 0, 1) AS asesor, 
					(SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'CIUDAD' AND campo_codigo = g.ciudad) AS ciudad, 
					d.codigo AS codart, 
					d.nombre_comercial, d.principio_activo, d.presentacion, 
					c.nombre AS fabricante, 
					b.lote, b.fecha_vencimiento,
					ABS(b.cantidad_movimiento) AS cantidad_movimiento,
					b.precio_unidad, 
					b.precio AS total_articulo, 
					a.monto_total, a.iva, a.total,  
					(SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
					a.unidades, 
					(SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = a.id_documento_padre) AS DOC_NE   
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
				ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 
	$rs = mysqli_query($link, $sql);

	if(!$rs) {
		var_dump(mysqli_error($link));
		die();
	}
	$contar = 0;
	while( $row = mysqli_fetch_array($rs) ) {
		$out .= '<tr>';
		  $out .= '<td>' . $row["id"] . '</td>';
		  $out .= '<td>' . $row["nro_documento"] . '</td>';
		  $out .= '<td>' . $row["fecha"] . '</td>';
		  $out .= '<td>' . $row["cliente"] . '</td>';
		  $out .= '<td>' . $row["asesor"] . '</td>';
		  $out .= '<td>' . $row["ciudad"] . '</td>';
		  $out .= '<td>' . $row["codart"] . '</td>';
		  $out .= '<td>' . $row["nombre_comercial"] . '</td>';
		  $out .= '<td>' . $row["principio_activo"] . '</td>';
		  $out .= '<td>' . $row["presentacion"] . '</td>';
		  $out .= '<td>' . $row["fabricante"] . '</td>';
		  $out .= '<td>' . $row["lote"] . '</td>';
		  $out .= '<td>' . $row["fecha_vencimiento"] . '</td>';
		  $out .= '<td>' . intval($row["cantidad_movimiento"]) . '</td>';
		  $out .= '<td>' . $row["precio_unidad"] . '</td>';
		  $out .= '<td>' . $row["total_articulo"] . '</td>';
		  $out .= '<td>' . $row["monto_total"] . '</td>';
		  $out .= '<td>' . $row["iva"] . '</td>';
		  $out .= '<td>' . $row["total"] . '</td>';
		  $out .= '<td>' . $row["tipo"] . '</td>';
		  $out .= '<td>' . intval($row["unidades"]) . '</td>';
		  $out .= '<td>' . $row["DOC_NE"] . '</td>';
		$out .= '</tr>';
		$contar++;
	}
	$out .= '<tr>
				<th colspan="18" class="text-right">Art&iacute;culos: ' . number_format($contar, 0, "", ".") . '</th>
			</tr>';
  	  $out .= '</tbody>';
	$out .= '</table>';

	if(isset($_REQUEST["toexcel"])) {
		echo $out;
	}	
?>