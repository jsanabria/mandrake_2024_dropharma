<?php
$strcon = "mandrake"; //$_SESSION["strcon"];
$host="localhost";
$user="root";
$password="";
$data=$strcon;
$enlace = mysqli_connect($host,$user,$password) or die(mysqli_error());
$link = $enlace;

mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas');

?>