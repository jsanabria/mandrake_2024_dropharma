<?php 

$id = $_REQUEST["id"];

include 'connect.php';
$id = $_REQUEST["id"];

$sql = "DELETE FROM entradas_salidas WHERE id = '$id'"; 
mysqli_query($link, $sql);

echo "1";
?>
