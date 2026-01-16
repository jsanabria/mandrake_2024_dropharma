<?php
session_start();

include "connect.php";

$pedido = $_REQUEST["id"];
$username = $_REQUEST["username"];

$sql = "SELECT tipo_documento FROM salidas WHERE id = $pedido;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tipo_documento = $row["tipo_documento"];

/* Actualizo las existencias */
$sql = "SELECT articulo FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) { 
	/**** ----- Elimino item por item ----- ****/
	$sql = "DELETE FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento' AND articulo = " . $row["articulo"] . ";";
	mysqli_query($link, $sql);
}

////////////

$sql = "SELECT nro_documento FROM salidas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];


/**** ----- Elimino la cabecera ----- ****/
$sql = "DELETE FROM salidas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar documento $tipo_documento NRO $nro_documento', '$username', 'D', 'findme_eliminar_all', 'id', '$pedido', '', '');";
mysqli_query($link, $sql);

if($tipo_documento == "TDCNET") 
	header("Location: ../ViewOutTdcnetList");
else 
	header("Location: ../ViewOutTdcasaList");

die();
?>