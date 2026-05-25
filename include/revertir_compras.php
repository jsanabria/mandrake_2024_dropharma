<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"];

$sql = "DELETE FROM pagos_compras_detalle WHERE pagos_compras IN (SELECT id FROM pagos_compras WHERE id_documento = $id);";
mysqli_query($link, $sql);

$sql = "DELETE FROM pagos_compras WHERE id_documento = $id;";
mysqli_query($link, $sql);

$sql = "UPDATE compra SET pagado = 'N' WHERE id = $id;";
mysqli_query($link, $sql);

echo "Proceso exitoso...";
?>
