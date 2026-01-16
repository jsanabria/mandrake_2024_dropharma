<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"]; 
$tipo = $_REQUEST["tipo"]; 

switch($tipo) {
case "CHECKER":
	$sql = "UPDATE salidas SET checker_date = '" . date("Y-m-d H:i:s") . "'  WHERE id = '$id'";
	mysqli_query($link, $sql);

	unset($_SESSION["checker"]);
	break;
case "PACKER":
	$sql = "UPDATE salidas SET packer_date = '" . date("Y-m-d H:i:s") . "'  WHERE id = '$id'";
	mysqli_query($link, $sql);

	unset($_SESSION["packer"]);
	break;
}


/*
$sql = "INSERT INTO audittrail
	(id, datetime, script, user, `action`, `table`, `field`)
	VALUES (NULL, NOW(), 'SOLICITAR AUTORIZACION PROCESAR CONTEO FISICO', '$usernama', 'PURGA NRO.: $idPurga', 'USUARIO EN CAJA: $usercaja | AUTORIZADO: $resp', '')"; 
mysqli_query($link, $sql);	
*/

echo "1";
?>
