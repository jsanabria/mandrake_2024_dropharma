<?php
session_start();

include "connect.php";

//$password = md5($_REQUEST["password"]); 
$password = $_REQUEST["password"]; 
$tipo = $_REQUEST["tipo"]; 

//$sql = "SELECT nombre FROM almacenista WHERE MD5(clave) = '$password' AND tipo = '$tipo';"; 
$sql = "SELECT nombre FROM almacenista WHERE clave = '$password' AND tipo = '$tipo';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
	$resp = "S"; 

	switch($tipo) {
	case "CHECKER":
		$_SESSION["checker"] = $row["nombre"];
		unset($_SESSION["packer"]);
		break;
	case "PACKER":
		unset($_SESSION["checker"]);
		$_SESSION["packer"] = $row["nombre"];
		break;
	default:
		unset($_SESSION["checker"]);
		unset($_SESSION["packer"]);
	}
} 
else {
	unset($_SESSION["checker"]);
	unset($_SESSION["packer"]);
	$resp = "N";
}

/*
$sql = "INSERT INTO audittrail
	(id, datetime, script, user, `action`, `table`, `field`)
	VALUES (NULL, NOW(), 'SOLICITAR AUTORIZACION PROCESAR CONTEO FISICO', '$usernama', 'PURGA NRO.: $idPurga', 'USUARIO EN CAJA: $usercaja | AUTORIZADO: $resp', '')"; 
mysqli_query($link, $sql);	
*/

echo $resp;
?>
