<?php
session_start();

include "connect.php";

$findme = $_REQUEST["findme"];

if(trim($findme) == "")
	die('<span class="glyphicon glyphicon-remove-sign"></span> <strong>Coloque datos</strong>');

$sql = "SELECT 
			a.id, 
			CONCAT(
				IFNULL(a.nombre_comercial, ''), ' ', 
				IFNULL(a.principio_activo, ''), ' ', 
				IFNULL(a.presentacion, ''), ' (', 
				IFNULL(b.nombre, ''), ')') AS articulo 
		FROM 
			articulo AS a 
			JOIN fabricante AS b ON b.Id = a.fabricante 
		WHERE 
			a.codigo = '$findme' OR a.codigo_de_barra = '$findme' OR a.principio_activo LIKE '%$findme%' 
			OR a.nombre_comercial LIKE '%$findme%' 
		ORDER BY 2;";
$rs = mysqli_query($link, $sql);

$out = '<select class="form-select form-select-sm" id="xItem" name="xItem" class="form-control" onchange="js:BuscarLote(this.value);">';
// $out .= '<option value=""></option>';
$cant = 0;
$id = 0;
while($row = mysqli_fetch_array($rs)) {
	$out .= '<option value="' . $row["id"] . '">' . $row["articulo"] . '</option>';
	$cant++;
	$id = intval($row["id"]);
}
$out .= '</select>';

if($cant == 0) $out = '<span class="glyphicon glyphicon-remove-sign"></span> <strong>!!! No Existe !!!</strong>';
if($cant == 1) $out = $id;

echo $out;
?>