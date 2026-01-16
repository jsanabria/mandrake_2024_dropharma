<?php
include "connect.php";

$fabricante = $_POST["fabricante"];
$username = $_POST["username"];

$asesor = 0;
$sql = "SELECT 
          IFNULL(asesor, 0) AS asesor 
        FROM usuario WHERE username = '$username';"; 
$result = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($result)) {
	$asesor = $row["asesor"];
	$sql = "SELECT IFNULL(fabricante, 0) AS fabricante FROM asesor_fabricante WHERE asesor = $asesor;";
	$result = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($result)) {
		$where = " Id IN (SELECT IFNULL(fabricante, 0) AS fabricante FROM asesor_fabricante WHERE asesor = $asesor)";
	} 
	else {
		if(trim($fabricante) == "") $where = " 1 ";
		else $where = " nombre LIKE '%$fabricante%'";
	}
}
else {
	if(trim($fabricante) == "") $where = " 1 ";
	else $where = " nombre LIKE '%$fabricante%'";
}

$html = "";
$sql = "SELECT 
			Id, nombre  
		FROM 
			fabricante 
		WHERE $where AND activo = 'S' 
		ORDER BY nombre ASC LIMIT 0, 200;"; 
$result = mysqli_query($link, $sql);

while ($row = mysqli_fetch_array($result)) {
	$html .= "<li onclick=\"mostrar('" . $row["Id"] . "')\" class=\"list-group-item\">" . $row["nombre"] . "</li>";
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
