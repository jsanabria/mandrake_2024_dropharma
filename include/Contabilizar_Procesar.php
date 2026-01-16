<?php 
include "connect.php";

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];

$sql= "UPDATE cont_comprobante SET contabiliza = '$username', contabilizacion = CURDATE(), fecha_contabiliza = CURDATE() WHERE id = $id;"; 
mysqli_query($link, $sql);

include "desconnect.php";

echo "";
?>
