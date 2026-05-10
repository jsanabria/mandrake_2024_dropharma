<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$username = $_REQUEST["username"]; 
$tipo_documento = "TDCFCV";
$consignacion = $_REQUEST["consignacion"]; 
$doc_afectado = $_REQUEST["doc_afectado"]; 

///////// INI - Pare reversar consignaciones /////////////
$sql = "SELECT consignacion, IFNULL(id_documento_padre, 0) AS id_documento_padre, IFNULL(direccion, '') AS direccion FROM salidas WHERE id = $pedido;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);  
$consignacion = $row["consignacion"];
$id_documento_padre = intval($row["id_documento_padre"]);
$direccion = $row["direccion"];

if($consignacion == "S") {
	$sql = "SELECT 
				cantidad_movimiento, id_consignacion
			FROM 	
				entradas_salidas 			
			WHERE 
				id_documento = $pedido 
				AND tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);  
	while ($row = mysqli_fetch_array($rs)) {
		if(intval($row["id_consignacion"]) > 0) {
			$cantidad = intval($row["cantidad_movimiento"]);
			$sql = "UPDATE entradas_salidas
					SET cantidad_movimiento_consignacion = (cantidad_movimiento_consignacion + ($cantidad))
					WHERE id = " . $row["id_consignacion"] . ";";
			mysqli_query($link, $sql); 
		}		# code...
	}

	if($id_documento_padre > 0) {
		$sql = "UPDATE salidas
			SET estatus = 'NUEVO'
			WHERE id = $id_documento_padre;";
		mysqli_query($link, $sql); 
	}

	if(trim($direccion) != "") {
		// Naso en que hayan varias notas de entrega en la misma factura. Informacion Guardada en el campo direccion
		$sql = "UPDATE salidas
				SET estatus = 'NUEVO'
				WHERE id IN ($direccion);";
		mysqli_query($link, $sql); 
	}
}
///////// FIN - Pare reversar consignaciones /////////////


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
$nro_documento = "0000000";
$pedido = 0;

$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar Factura de Venta NRO/ID $nro_documento/$pedido', '$username', 'D', 'view_in_tdcfcv', 'id', '$pedido', '', '');";
mysqli_query($link, $sql);

$html = '{
			"pedido":"' . $pedido . '",
         	"estatus":"1", 
         	"consignacion":"' . $consignacion . '", 
         	"doc_afectado":"' . $doc_afectado . '", 
         	"nro_documento":"' . $nro_documento . '"  
        }';


echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>