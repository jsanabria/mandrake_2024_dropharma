<?php 

$id = $_REQUEST["id"];
$lote = explode("|", $_REQUEST["lote"]);
$cantidad = floatval($_REQUEST["cantidad"]);
$unidad = $_REQUEST["unidad"];
$codart = $_REQUEST["articulo"];

// Traigo la infromación del lote y fecha de vencimiento
$xLote = $lote[0];
$xVencimiento = $lote[1];

include 'connect.php';
$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento_inventario = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento_inventario = $row["tipo_documento"];

$sql = "SELECT a.fabricante, IFNULL(b.alicuota, 0) as alicuota 
		FROM 
			articulo AS a JOIN alicuota AS b ON b.codigo = a.alicuota 
		WHERE a.id = $codart AND b.activo = 'S';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $alicuota = $row["alicuota"];
else $alicuota = $row["alicuota"];


$sql = "SELECT 
          x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen, SUM(x.cantidad_movimiento) AS cantidad 
        FROM 
          (
            SELECT 
               a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
               IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
               a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
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
               ) AND a.articulo = $codart AND a.lote = '$xLote' AND a.fecha_vencimiento = '$xVencimiento' AND a.newdata = 'S' 
            UNION ALL SELECT 
               a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
               IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
               a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
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
                   (a.tipo_documento IN ('$tipo_documento_inventario', 'TDCASA') AND b.estatus <> 'ANULADO') 
               ) AND a.articulo = $codart AND a.lote = '$xLote' AND a.fecha_vencimiento = '$xVencimiento' AND a.newdata = 'S' 
          ) AS x 
        WHERE 1  
        GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
        HAVING SUM(x.cantidad_movimiento) <> 0 
        ORDER BY x.fecha_vencimiento ASC;"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
	$existencia = intval($row["cantidad"]);
else 
	$existencia = 0;

$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$unidad';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cantidad_um = floatval($row["cantidad"]);

$solicitado = $cantidad * $cantidad_um;

$sql = "SELECT 
	(cantidad_articulo * cantidad_unidad_medida) AS cantidad 
FROM 
	entradas_salidas WHERE id = '$id';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cantidad_pedida = floatval($row["cantidad"]);

//die("Lote: $lote - Existencia: $existencia - Solicitado: $solicitado");

if($solicitado > $existencia) echo "0";
else  {
	if($solicitado > $cantidad_pedida) echo "0";
	else {
		$sql = "UPDATE entradas_salidas
				SET
					cantidad_articulo = '$cantidad',
					articulo_unidad_medida = '$unidad',
					cantidad_unidad_medida = '$cantidad_um',
					cantidad_movimiento = '" . (-1) * $solicitado. "',
					lote = '$xLote', 
					fecha_vencimiento = '$xVencimiento',
					precio = precio_unidad * $cantidad, id_compra = 0, alicuota = $alicuota  
				WHERE 
					id = '$id';"; 
		mysqli_query($link, $sql);

		if(($cantidad_pedida - $solicitado) > 0) {
			$sql = "INSERT INTO entradas_salidas
						(id, tipo_documento, id_documento, 
						fabricante, articulo, lote, 
						fecha_vencimiento, almacen, 
						cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
						cantidad_movimiento, id_compra, alicuota,
						precio_unidad, precio)
					SELECT 
						NULL, tipo_documento, id_documento, 
						fabricante, articulo, NULL, 
						NULL, almacen, 
						($cantidad_pedida - $solicitado), '$unidad', $cantidad_um, 
						($cantidad_pedida - $solicitado)/$cantidad_um, NULL, alicuota,
						precio_unidad,
						(precio_unidad * ($cantidad_pedida - $solicitado)) AS precio 
					FROM 
						entradas_salidas 
					WHERE 
						id = '$id';";
			mysqli_query($link, $sql);
		}

		echo "1";
	}
}

?>
