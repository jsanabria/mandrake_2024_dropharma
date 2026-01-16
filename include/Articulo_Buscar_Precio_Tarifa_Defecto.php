<?php 
session_start();

include "connect.php";

$articulo = isset($_REQUEST["articulo"]) ? intval($_REQUEST["articulo"]) : 0; 
$cliente = isset($_REQUEST["cliente"]) ? intval($_REQUEST["cliente"]) : 0; 

/*
$sql = "SELECT valor2 AS tarifa_por_defecto FROM parametro WHERE codigo = '051';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$tarifa = intval($row["tarifa_por_defecto"]);
else 
	$tarifa = 3;
*/

$sql = "SELECT tarifa FROM cliente WHERE id = $cliente;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$tarifa = $row["tarifa"];
else 
	$tarifa = 2;


$sql = "SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = $articulo;"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$precio = floatval($row["precio"]);
else 
	$precio = 0;

echo $precio;
?>
