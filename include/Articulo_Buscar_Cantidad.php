<?php
session_start();

include "connect.php";

$articulo = intval($_REQUEST["articulo"]); 
$cantidad = intval($_REQUEST["cantidad"]); 
$cliente = intval($_REQUEST["cliente"]); 

$sql = "SELECT IFNULL(cantidad_en_mano, 0) AS cantidad_en_mano FROM articulo WHERE id = $articulo;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cnt = floatval($row["cantidad_en_mano"]);

$out = "";

if($cantidad > $cnt) $out = "S";
else $out = "N";

$sql = "SELECT tarifa FROM cliente WHERE id = $cliente;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$tarifa = $row["tarifa"];
else 
	$tarifa = 0;

$sql = "SELECT ABS(IFNULL(precio, 0)) AS precio FROM tarifa_articulo WHERE articulo = $articulo AND tarifa = $tarifa;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$out .= "|" . floatval($row["precio"]);
else 
	$out .= "|" . 0;


echo $out;
?>
