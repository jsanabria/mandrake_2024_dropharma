<?php
include "connect.php";

$fabricante = $_POST["fabricante"];

$html = "";
$sql = "SELECT 
			Id, nombre 
		FROM 
			fabricante 
		WHERE Id = $fabricante;"; 
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$html = $row["Id"] . "|" . $row["nombre"];

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
