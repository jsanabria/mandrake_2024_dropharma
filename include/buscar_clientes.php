<?php
include "connect.php";

$cliente = $_POST["cliente"];
$username = $_POST["username"];

$asesor = "";
$sql = "SELECT 
          IFNULL(asesor, '') AS asesor 
        FROM usuario WHERE username = '$username';"; 
$result = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($result)) {
  $asesor = $row["asesor"];
}


$html = "";
if($asesor == "" or $asesor == "0") {
	$sql = "SELECT 
				id, ci_rif, nombre  
			FROM 
				cliente 
			WHERE nombre LIKE '%$cliente%' OR ci_rif LIKE '%$cliente%' 
			ORDER BY nombre ASC LIMIT 0, 20;"; 
} 
else {
	$sql = "SELECT COUNT(fabricante) AS cantidad FROM asesor_fabricante WHERE asesor = $asesor;";
	$result2 = mysqli_query($link, $sql);
	$row2 = mysqli_fetch_array($result2);

	if($row2["cantidad"] > 0) {
		$sql = "SELECT 
					id, ci_rif, nombre  
				FROM 
					cliente 
				WHERE nombre LIKE '%$cliente%' OR ci_rif LIKE '%$cliente%' 
				ORDER BY nombre ASC LIMIT 0, 20;"; 
	}
	else {
		$sql = "SELECT 
					a.id, a.ci_rif, a.nombre  
				FROM 
					cliente AS a 
					JOIN asesor_cliente AS b ON b.cliente = a.id 
				WHERE b.asesor = $asesor AND (a.nombre LIKE '%$cliente%' OR a.ci_rif LIKE '%$cliente%') 
				ORDER BY a.nombre ASC LIMIT 0, 20";
	}
}
$result = mysqli_query($link, $sql);

// echo json_encode($sql, JSON_UNESCAPED_UNICODE);
// die();

while ($row = mysqli_fetch_array($result)) {
	$html .= "<li onclick=\"mostrar('" . $row["id"] . "')\" class=\"list-group-item\">" . $row["ci_rif"] . " - " . $row["nombre"] . "</li>";
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
