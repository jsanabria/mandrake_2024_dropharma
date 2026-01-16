<?php
include "connect.php";

$cliente = $_POST["cliente"];

$html = "";
$sql = "SELECT 
			a.id, a.nombre, a.ci_rif, 
			CONCAT(IFNULL(a.telefono1, ''), IF(IFNULL(a.telefono2, '') = '', '', ' / '), IFNULL(a.telefono2, '')) AS telefono, 
			a.tarifa, a.ciudad, a.codigo_ims, IF(a.activo='S', 'ACTIVO', 'INACTIVO') AS activo, 
			(SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'TIPO_CLIENTE' AND campo_codigo = a.tipo_cliente) AS tipo_cliente    
		FROM 
			cliente AS a 
		WHERE a.id = $cliente;"; 
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$html = $row["id"] . "|" . $row["nombre"] . "|" . $row["ci_rif"] . "|" . $row["telefono"];
$tarifa = $row["tarifa"];
$tipo_cliente = $row["tipo_cliente"];
$codigo_ims = $row["codigo_ims"];
$activo = $row["activo"];

$sql = "SELECT nombre FROM tarifa WHERE id = $tarifa;"; 
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$html = $html . "|" . $row["nombre"];

$sql = "SELECT IFNULL(dias_credito, 0) AS dias_credito FROM salidas WHERE tipo_documento = 'TDCFCV' AND cliente = $cliente AND estatus = 'PROCESADO' ORDER BY id DESC;"; 
$result = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($result))
	$html = $html . "|" . $row["dias_credito"];
else 
	$html = $html . "|0";

$sql = "SELECT 
			b.nombre as asesor,  
			(SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'CIUDAD' AND campo_codigo = b.ciudad) AS ciudad, 
			CONCAT(IFNULL(b.telefono1, ''), IF(IFNULL(b.telefono2, '') = '', '', ' / '), IFNULL(b.telefono2, '')) AS telefono, 
			CONCAT(IFNULL(b.email1, ''), IF(IFNULL(b.email2, '') = '', '', ' / '), IFNULL(b.telefono2, '')) AS email   
		FROM 
			asesor_cliente AS a 
			JOIN asesor AS b ON b.id = a.asesor 
		WHERE a.cliente = $cliente;";
$result = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($result))
	$html = $html . "|" . $row["asesor"] . "|" . $row["ciudad"] . "|" . $row["telefono"] . "|" . $row["email"];
else 
	$html = $html . "|0|0000||";

$html = $html . "|" . $tipo_cliente . "|" . $codigo_ims . "|" . $activo;

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
