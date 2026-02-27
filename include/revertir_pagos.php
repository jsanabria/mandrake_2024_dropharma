<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"];

$sql = "DELETE FROM cobros_cliente_detalle WHERE cobros_cliente IN (SELECT id FROM cobros_cliente WHERE id_documento = $id );";
mysqli_query($link, $sql);

$sql = "DELETE FROM cobros_cliente WHERE id_documento = $id;";
mysqli_query($link, $sql);

$sql = "UPDATE salidas SET pagado = 'N' WHERE id = $id;";
mysqli_query($link, $sql);

echo "Proceso exitoso...";
?>
