<?php
session_start();

include "connect.php";

$codart = $_REQUEST["articulo"];
$lote = $_REQUEST["lote"];

$sql = "SELECT 
		a.id, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
		(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad, c.descripcion AS almacen  
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
					AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '$codart' 
				GROUP BY a.id_compra
			) AS d ON d.id = a.id 
	WHERE
		((a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
		AND b.estatus = 'PROCESADO') OR 
		(a.tipo_documento IN ('TDCNRP', 'TDCAEN') 
		AND b.estatus <> 'ANULADO' AND b.consignacion = 'S'))
		AND a.articulo = '$codart' 
		AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0 
	ORDER BY a.fecha_vencimiento ASC;"; 
$rs = mysqli_query($link, $sql);
$sw = "N";
while($row = mysqli_fetch_array($rs)) { 
	if($row["lote"] == $lote) 
		$sw = "S";
}

echo $sw;
?>