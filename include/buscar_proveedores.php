<?php
// die(json_encode("Hello World", JSON_UNESCAPED_UNICODE));
include "connect.php";

$proveedor = $_POST["proveedor"];
$username = $_POST["username"];



$html = "";
$sql = "SELECT 
			id, ci_rif, nombre  
		FROM 
			proveedor 
		WHERE nombre LIKE '%$proveedor%' OR ci_rif LIKE '%$proveedor%' 
		ORDER BY nombre ASC LIMIT 0, 20;"; 
$result = mysqli_query($link, $sql);

// echo json_encode("Hello World", JSON_UNESCAPED_UNICODE);
// die();

while ($row = mysqli_fetch_array($result)) {
	$html .= "<li onclick=\"mostrar('" . $row["id"] . "')\" class=\"list-group-item\">" . $row["ci_rif"] . " - " . $row["nombre"] . "</li>";
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
