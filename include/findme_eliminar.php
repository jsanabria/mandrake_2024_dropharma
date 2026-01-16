<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];


$sql= "SELECT id_documento, tipo_documento, articulo FROM entradas_salidas WHERE id = $id;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$id_documento = $row["id_documento"]; 
$tipo_documento = $row["tipo_documento"]; 
$articulo = $row["articulo"];

$sql = "DELETE FROM entradas_salidas WHERE id = $id;";
$rs = mysqli_query($link, $sql);

$sql = "UPDATE salidas SET estatus = 'PROCESADO', username = '$username'  WHERE id = '$id_documento'";
mysqli_query($link, $sql);

$id = $id_documento; 
require_once("findme_cabecera_totales.php");

$id_documento = $id; 
require_once("findme_detalle.php");

?>