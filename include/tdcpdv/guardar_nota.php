<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$nota = $_REQUEST["nota"]; 
$dias_credito = intval($_REQUEST["dias_credito"]); 

/**** ----- Actualizo el campo descuento en la cabecera del pedido ----- ****/
$sql = "UPDATE salidas SET nota = '$nota', dias_credito = $dias_credito WHERE id = $pedido;";
mysqli_query($link, $sql);

$html = "Se actualizo la nota y dias de credito exitosamente.";
echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>