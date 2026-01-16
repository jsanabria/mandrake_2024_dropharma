<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$username = $_REQUEST["username"]; 
$tipo_documento = "TDCPDV";

/* Actualizo las existencias */
$sql = "SELECT articulo FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) { 
	/**** ----- Elimino item por item ----- ****/
	$sql = "DELETE FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento' AND articulo = " . $row["articulo"] . ";";
	mysqli_query($link, $sql);
}

////////////

$sql = "SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];

$sql = "DELETE FROM salidas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar Pedido de Venta NRO/ID $nro_documento/$pedido', '$username', 'D', 'view_in_tdcpdv', 'id', '$pedido', '', '');";
mysqli_query($link, $sql);

$nro_documento = "0000000";
$pedido = 0;

$html = '{
			"pedido":"' . $pedido . '",
         	"estatus":"1", 
         	"nro_documento":"' . $nro_documento . '"  
        }';


echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>