<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$nota = $_REQUEST["nota"]; 

/**** ----- Actualizo el campo descuento en la cabecera del pedido ----- ****/
$sql = "UPDATE entradas SET nota = '$nota' WHERE id = $pedido;";
mysqli_query($link, $sql);

$html = "Se actualizo la nota exitosamente.";
echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>