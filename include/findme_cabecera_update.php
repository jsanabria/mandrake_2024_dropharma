<?php
session_start();


include "connect.php";

$id = $_POST["id"];
$nota = $_POST["nota"];
$username = $_POST["username"];

$factura = $_POST["factura"];
$ci_rif = $_POST["ci_rif"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];

$descuento = floatval($_POST["descuento"]);
$tasa_dia = floatval($_POST["tasa"]);

if($tasa_dia == 0) {
	$sql = "SELECT tasa FROM tasa_usd ORDER BY id DESC LIMIT 0, 1;";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);										
	$tasa_dia = floatval($row["tasa"]);
}

$sql = "SELECT tipo_documento FROM salidas WHERE id = $id;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tipo_documento = $row["tipo_documento"];

$sql = "UPDATE salidas 
		SET 
			descuento = $descuento, 
			tasa_dia = $tasa_dia, 
			estatus = 'PROCESADO', 
			factura = '$factura', 
			ci_rif = '$ci_rif', 
			nombre = '$nombre', 
			direccion = '$direccion', 
			nota = '$nota', 
			telefono = '$telefono', 
			username = '$username'  
		WHERE tipo_documento = '$tipo_documento' AND  id = $id;"; 
mysqli_query($link, $sql);

require_once("findme_cabecera_totales.php");

$id_documento = $id; 

require_once("findme_detalle.php");
?>

