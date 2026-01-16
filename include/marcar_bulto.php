<?php 
include "connect.php"; 
date_default_timezone_set('America/La_Paz');

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];


$sql = "UPDATE salidas
        SET
          fecha_despacho = '" . date("Y-m-d H:i:s") . "', 
          user_despacho = '$username' 
        WHERE id = $id;"; 
mysqli_query($link, $sql); 

include "desconnect.php"; 

header("Location: ../ViewBultosList");
?>
