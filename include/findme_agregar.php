<?php
session_start();

include "connect.php";
include "rutinas.php";

$id = $_POST["id"];
$nota = $_POST["nota"];
$username = $_POST["username"];

$factura = $_POST["factura"];
$ci_rif = $_POST["ci_rif"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];

$sql = "SELECT tipo_documento FROM salidas WHERE id = $id;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tipo_documento = $row["tipo_documento"];

$articulo = $_POST["articulo"];

$lote = $_POST["lote"];
$fecha_vencimiento = $_POST["fecha"];
$existencia = $_POST["existencia"];
$almacen = $_POST["almacen"];


$cnt = $_POST["cantidad"];
$un = $_POST["unidad"];

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento_inventario = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento_inventario = $row["tipo_documento"];

$sql = "SELECT valor1 AS mostrar_precio FROM parametro WHERE codigo = '" . ($tipo_documento == "TDCNET" ? "051" : "052") . "';";
$mostrar_precio = 'N';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $mostrar_precio = $row["mostrar_precio"];

$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento_inventario = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento_inventario = $row["tipo_documento"];

$sql = "SELECT descuento, fabricante FROM articulo WHERE id = $articulo;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$descuento = floatval($row["descuento"]);
$fabricante = $row["fabricante"];

$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$un';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cantidad_um = floatval($row["cantidad"]);

$asignado = $cnt * $cantidad_um;

// Consulto el ultimo costo del artículo
$sql = "SELECT ultimo_costo FROM articulo WHERE id = $articulo;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$costo_unidad = floatval($row["ultimo_costo"]);
$costo = $costo_unidad * $asignado;

//// **** ////
$sql = "SELECT IFNULL(b.alicuota, 0) as alicuota 
		FROM 
			articulo AS a JOIN alicuota AS b ON b.codigo = a.alicuota 
		WHERE a.id = $articulo AND b.activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$alicuota = $row["alicuota"];

$sql = "SELECT b.tarifa FROM salidas AS a JOIN cliente AS b ON b.id = a.cliente WHERE a.id = $id;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tarifa = $row["tarifa"];

/*** Consultar precio según tarifa y cliente ***/
$sql = "SELECT 
			a.precio AS precio_ful, 
			(a.precio - (a.precio * ($descuento/100))) AS precio 
		FROM tarifa_articulo AS a WHERE a.tarifa = $tarifa AND a.articulo = $articulo;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
if($mostrar_precio == "S") {
	$precio_unidad = $row["precio"];
	$precio = $asignado * $precio_unidad;
	$precio_ful = $row["precio_ful"];	
} 
else {
	$precio_unidad = 0;
	$precio = $asignado * $precio_unidad;
	$precio_ful = 0;	
}

/* **** Consulto nuevamente la existecia **** */
$sql = "SELECT 
		  x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen, SUM(x.cantidad_movimiento) AS cantidad 
		FROM 
		  (
		    SELECT 
		       a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
		       a.fecha_vencimiento, 
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
		       ) AND a.articulo = $articulo AND a.newdata = 'S' 
		    UNION ALL SELECT 
		       a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
		       a.fecha_vencimiento, 
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
		       ) AND a.articulo = $articulo AND a.newdata = 'S' 
		  ) AS x 
		WHERE x.lote = '$lote' AND IFNULL(x.fecha_vencimiento, '1990-01-01') = '$fecha_vencimiento' AND x.codalm = '$almacen' 
		GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
		HAVING SUM(x.cantidad_movimiento) <> 0 
		ORDER BY x.fecha_vencimiento ASC;"; 
$xReal = 0;
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $xReal = $row["cantidad"];

if($asignado > $xReal) die("Catidad ya no disponible para el lote seleccionado. !!! Verifique !!!");

$asignado *= -1;

////////////////////////////////////////////////
if(trim($fecha_vencimiento) === "") {
	$sql = "INSERT INTO entradas_salidas 
				(id, tipo_documento, id_documento, 
				fabricante, articulo, lote, almacen, cantidad_articulo, costo_unidad, costo, 
				articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc) 
			VALUES 
				(NULL, '$tipo_documento', '$id', 
				'$fabricante', '$articulo', '$lote', '$almacen', '$cnt', $costo_unidad, $costo, 
				'$un', '$cantidad_um', '$asignado', '$precio_unidad', '$precio', '$alicuota', $descuento, $precio_ful);"; 	
} 
else {
	$sql = "INSERT INTO entradas_salidas 
				(id, tipo_documento, id_documento, 
				fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, costo_unidad, costo, 
				articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc) 
			VALUES 
				(NULL, '$tipo_documento', '$id', 
				'$fabricante', '$articulo', '$lote', '$fecha_vencimiento', '$almacen', '$cnt', $costo_unidad, $costo, 
				'$un', '$cantidad_um', '$asignado', '$precio_unidad', '$precio', '$alicuota', $descuento, $precio_ful);"; 	
}
mysqli_query($link, $sql);

$sql = "UPDATE salidas 
		SET 
			estatus = 'NUEVO', 
			factura = '$factura', 
			ci_rif = '$ci_rif', 
			nombre = '$nombre', 
			direccion = '$direccion', 
			nota = '$nota', 
			telefono = '$telefono', 
			username = '$username'   
		WHERE id = $id;";
mysqli_query($link, $sql);

require_once("findme_cabecera_totales.php");

$id_documento = $id; 

require_once("findme_detalle.php");
?>

