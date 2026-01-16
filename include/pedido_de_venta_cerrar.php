<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];

$sql = "UPDATE salidas SET nombre='$username' WHERE tipo_documento='TDCPDV' AND id = $id;"; 
$rs = mysqli_query($link, $sql);


//////////////////////////
$sql = "UPDATE 
      salidas AS a 
      JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas WHERE tipo_documento = 'TDCPDV' GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
    SET 
      a.unidades = b.cantidad 
    WHERE a.id = $id;";
$rs = mysqli_query($link, $sql);
/////////////////////////

header("Location: ../SalidasList?tipo=TDCPDV");

include "desconnect.php"; 
?>
