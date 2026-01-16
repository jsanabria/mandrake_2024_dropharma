<?php 
$host="localhost";
$user="root";
$password="";
//$datasco="sco";
$data="certificacion";
$enlace = mysqli_connect($host,$user,$password) or die(mysql_error());
$link = $enlace;
mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas'); 
?>