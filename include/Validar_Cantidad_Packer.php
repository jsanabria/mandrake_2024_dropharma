<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"]; 
$cantidad = intval($_REQUEST["cantidad"]); 

$sql = "SELECT cantidad_articulo FROM entradas_salidas WHERE id = '$id';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$xcantidad = intval($row["cantidad_articulo"]); 

if($cantidad > $xcantidad or $cantidad < 1) {
	$resp = "N";
} 
else {
	$sql = "UPDATE entradas_salidas SET packer_cantidad = cantidad_articulo WHERE id = '$id';"; 
	mysqli_query($link, $sql);

	$sql = "UPDATE entradas_salidas SET cantidad_articulo = $cantidad, cantidad_movimiento = (-1)*$cantidad, precio = precio_unidad*$cantidad WHERE id = '$id';"; 
	mysqli_query($link, $sql);	

	$resp = "S";
}

echo $resp;
?>
