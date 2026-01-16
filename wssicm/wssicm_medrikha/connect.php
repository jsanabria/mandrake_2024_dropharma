<?php
$strcon = "drophqsc_medrika"; //$_SESSION["strcon"];
$host="localhost";
$user="drophqsc_drake";
$password="Tomj@vas001";
$data=$strcon;
$enlace = mysqli_connect($host,$user,$password) or die(mysqli_error());
$link = $enlace;

mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas');

?>