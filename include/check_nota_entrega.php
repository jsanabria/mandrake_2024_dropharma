<?php
session_start();

$id = $_REQUEST["id"];
$tipo_documento = $_REQUEST["tipo_documento"];
$codbar = $_REQUEST["codbar"];
$resp = "";

include "connect.php";

$sql = "SELECT id AS articulo FROM articulo WHERE codigo_de_barra = '$codbar';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
	$articulo = $row["articulo"];
	$sql = "SELECT id FROM entradas_salidas WHERE id_documento = $id AND tipo_documento = '$tipo_documento' AND articulo = $articulo;";
	$rs = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($rs)) {
		$detalle = $row["id"];
		$sql = "UPDATE entradas_salidas SET check_ne = 'S' WHERE id = $detalle;";
		mysqli_query($link, $sql);
		$resp = "S";
	}
	else {
		$resp = "N";
	}

}
else {
	$resp = "N";
}

include "desconnect.php";
echo $resp;
?>