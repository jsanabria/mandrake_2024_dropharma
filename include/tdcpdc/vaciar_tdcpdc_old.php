<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 

/* Actualizo las existencias */
$sql = "SELECT articulo FROM entradas_salidas WHERE id_documento = $pedido AND tipo_documento = 'TDCPDC';";
$rs = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($rs)) { 
	/**** ----- Elimino item por item ----- ****/
	$sql = "DELETE FROM entradas_salidas WHERE id_documento = $pedido AND articulo = " . $row["articulo"] . ";";
	mysqli_query($link, $sql);
	ActualizarExitenciaArticulo($row["articulo"], $link);
}

////////////


/**** ----- Elimino la cabecera ----- ****/
$sql = "DELETE FROM entradas WHERE id = '$pedido' AND tipo_documento = 'TDCPDC';";
mysqli_query($link, $sql);

$html = '{
         	"estatus":"1" 
        }';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>