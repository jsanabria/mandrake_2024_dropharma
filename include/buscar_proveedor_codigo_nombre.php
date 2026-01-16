<?php
include "connect.php";

$proveedor = $_POST["proveedor"];

$html = "";
$sql = "SELECT 
			a.id, a.nombre, a.ci_rif, 
			CONCAT(IFNULL(a.telefono1, ''), IF(IFNULL(a.telefono2, '') = '', '', ' / '), IFNULL(a.telefono2, '')) AS telefono 
		FROM 
			proveedor AS a 
		WHERE a.id = $proveedor;"; 
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$html = $row["id"] . "|" . $row["nombre"] . "|" . $row["ci_rif"] . "|" . $row["telefono"];

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
