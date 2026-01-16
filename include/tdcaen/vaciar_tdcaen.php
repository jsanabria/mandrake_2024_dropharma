<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$username = $_REQUEST["username"]; 
$tipo_documento = "TDCAEN";

/* Actualizo las existencias */
$sql = "SELECT articulo FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) { 
	/**** ----- Elimino item por item ----- ****/
	$sql = "DELETE FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento' AND articulo = " . $row["articulo"] . ";";
	mysqli_query($link, $sql);
}

////////////

$sql = "SELECT nro_documento FROM entradas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];


/**** ----- Elimino la cabecera ----- ****/
$sql = "DELETE FROM entradas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar Factura de Compra NRO $nro_documento', '$username', 'D', 'view_in_tdcfcc', 'id', '$pedido', '', '');";
mysqli_query($link, $sql);

$html = '{
         	"estatus":"1" 
        }';


echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>