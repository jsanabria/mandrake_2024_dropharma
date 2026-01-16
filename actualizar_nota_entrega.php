<?php 
include "connect.php";

$id = $_REQUEST["id"];
$nota = $_REQUEST["nota"];

$sql = "UPDATE salidas SET nota = '$nota' WHERE id = '$id'"; 
mysqli_query($link, $sql);


$sql = "SELECT tipo_documento FROM salidas WHERE id = '$id'"; 
mysqli_query($link, $sql);
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tipo = $row["tipo_documento"];


$sql = "DELETE FROM entradas_salidas 
		WHERE id_documento = '$id' AND tipo_documento = '$tipo' 
			AND IFNULL(cantidad_movimiento, 0) = 0"; 
mysqli_query($link, $sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
// 22-09-2021 Habia dejad de actualizar este proceso aqui, ya deben quedar las unidades actualizadas
$sql = "UPDATE 
			salidas AS a 
			JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $id;";
mysqli_query($link, $sql);
/**************/

echo "1";

?>
