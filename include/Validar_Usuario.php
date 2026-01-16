<?php 
session_start();

include "connect.php";

$usernama = $_REQUEST["usernama"]; 
$password = $_REQUEST["password"]; 
$idPurga = isset($_REQUEST["idPurga"]) ? $_REQUEST["idPurga"] : "NUEVA";
$usercaja = $_REQUEST["usercaja"];

$sql = "SELECT id FROM usuario WHERE username = '$usernama' AND password = '$password';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) {
	$sql = "SELECT valor1 AS usuario FROM parametro WHERE codigo = '045' AND RTRIM(valor1) = '$usernama';";
	$rs = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($rs)) $resp = "S";
	else $resp = "N";
} 
else {
	$resp = "N";
}


$sql = "INSERT INTO audittrail
	(id, datetime, script, user, `action`, `table`, `field`)
	VALUES (NULL, NOW(), 'SOLICITAR AUTORIZACION PROCESAR CONTEO FISICO', '$usernama', 'PURGA NRO.: $idPurga', 'USUARIO EN CAJA: $usercaja | AUTORIZADO: $resp', '')"; 
mysqli_query($link, $sql);	

echo $resp;
?>
