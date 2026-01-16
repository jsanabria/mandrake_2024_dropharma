<?php 
include "connect.php";

$tipo = $_REQUEST["tipo"];

if($tipo == "vendedor") {
	$sql = "SELECT 
				a.username, a.nombre AS asesor 
			FROM 
				usuario AS a LEFT OUTER JOIN asesor AS b ON b.id = a.asesor 
			WHERE a.asesor IS NOT NULL 
			ORDER BY a.nombre;";
	$rs = mysqli_query($link, $sql);
	echo '<select class="form-control" id="vendedor" name="vendedor" multiple>';
	echo '<option value="">Todos</option>';
	while($row = mysqli_fetch_array($rs)) {
		echo '<option value="' . $row["username"] . '"> ' . $row["asesor"] . '</option>';
	}
	echo '</select>';
}
else if($tipo == "ciudad") {
	$sql = "SELECT campo_codigo AS codigo, campo_descripcion AS ciudad FROM tabla WHERE tabla = 'CIUDAD' ORDER BY campo_descripcion;";
	$rs = mysqli_query($link, $sql);
	echo '<select class="form-control" id="ciudad" name="ciudad" multiple>';
	echo '<option value="">Todos</option>';
	while($row = mysqli_fetch_array($rs)) {
		echo '<option value="' . $row["codigo"] . '"> ' . $row["ciudad"] . '</option>';
	}
	echo '</select>';
}
