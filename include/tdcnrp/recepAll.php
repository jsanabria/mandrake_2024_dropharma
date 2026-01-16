<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$username = $_REQUEST["username"];
$recepcionar = $_REQUEST["recepcionar"];

$sql = "SELECT nro_documento FROM entradas WHERE id = $pedido AND tipo_documento = 'TDCNRP';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];

$sql = "UPDATE entradas_salidas SET check_ne = '$recepcionar' WHERE id_documento = $pedido AND tipo_documento = 'TDCNRP';"; 
mysqli_query($link, $sql);

if($recepcionar == "S") {
	$sql = "INSERT INTO audittrail
		(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
		VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Los Items de la Nota de Recepcion NRO $nro_documento se RECEPCIONARON', '$username', 'U', 'salidas, entradas_salidas.check_ne', 'id', '$pedido', 'N', 'S');";
} 
else {
	$sql = "INSERT INTO audittrail
		(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
		VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Los Items de la Nota de Recepcion NRO $nro_documento se DESRECEPCIONARON', '$username', 'U', 'salidas, entradas_salidas.check_ne', 'id', '$pedido', 'S', 'N');";
}

mysqli_query($link, $sql);

echo json_encode('{"estatus":"1"}', JSON_UNESCAPED_UNICODE);
?>