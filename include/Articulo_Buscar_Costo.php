<?php 
session_start();

include "connect.php";

$articulo = intval($_REQUEST["articulo"]); 

$sql = "SELECT IFNULL(ultimo_costo, 0) AS costo FROM articulo WHERE id = $articulo;";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) 
	$costo = floatval($row["costo"]);
else 
	$costo = 0;

echo $costo;
?>
