<?php
if(!isset($_COOKIE["strcon"])) {
	session_destroy();
	echo '<h2 style="color: blue;">Falla de Conexi&oacute;n! Reinicie su sesi&oacute;n...</h2>';
	echo '<a href="../logout" 
			style="background-color: #4CAF50; 
					border: none;
					color: white;
					padding: 15px 32px;
					text-align: center;
					text-decoration: none;
					display: inline-block;
					font-size: 16px; border-radius: 25px;"
		>Click aqu&iacute; para reiniciar la sesi&oacute;n</a>';
	die();
}

$strcon = $_COOKIE["strcon"];
$host="localhost";
//$user="drophqsc_drake";
//$password="Tomj@vas001";
//$data="drophqsc_mandrake";
$user="root";
$password="";
$data=$strcon;
$enlace = mysqli_connect($host,$user,$password) or die(mysqli_error());
$link = $enlace;
mysqli_select_db($link, $data);
mysqli_query($link, "SET NAMES 'utf8'");
ini_set('date.timezone', 'America/Caracas'); 
date_default_timezone_set('America/La_Paz');

?>