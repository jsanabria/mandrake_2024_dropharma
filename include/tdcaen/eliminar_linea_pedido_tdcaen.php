<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$articulo = intval($_REQUEST["articulo"]); 
$username = $_REQUEST["username"]; 
$id_item = intval($_REQUEST["id_item"]); 
$nota = $_REQUEST["nota"]; 

$tipo_documento = "TDCAEN";

/**** ----- Actualizo campos en la cabecera del pedido ----- ****/
$sql = "UPDATE entradas SET nota = '$nota' WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
mysqli_query($link, $sql);

$sql = "SELECT nro_documento FROM entradas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$nro_documento = $row["nro_documento"];

/**** ----- Elimino el item ----- ****/
$sql = "DELETE FROM entradas_salidas WHERE id = $id_item;";
// die(json_encode($sql, JSON_UNESCAPED_UNICODE)); 
mysqli_query($link, $sql);


$sql = "INSERT INTO audittrail
	(id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
	VALUES (NULL, '" . date("Y-m-d H:i:s") . "', 'Eliminar Articulo de Ajuste de Entrada NRO $nro_documento Articulo $articulo', '$username', 'D', 'view_in_tdcaen', 'id', '$pedido', '$articulo', '');";
mysqli_query($link, $sql);


/**** ----- Valido si elimino la cabecera ----- ****/
$sql = "SELECT * FROM entradas_salidas WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
	/// Obtengo el $nro_documento  /////
	$sql = "SELECT nro_documento FROM entradas WHERE id = '$pedido' AND tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$nro_documento = $row["nro_documento"];

	//////////////// Actualizo Cabecera ////////////////
	// Verifico si los artículos tienen una misma alicuota o varias por cada uno de ellos //
	$sql = "SELECT 
		      COUNT(DISTINCT alicuota) AS cantidad  
		    FROM 
		      entradas_salidas 
		    WHERE 
		      id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	if(intval($row["cantidad"]) > 1) $alicuota = 0;
	else {
	  $sql = "SELECT 
	        DISTINCT alicuota 
	      FROM 
	        entradas_salidas 
	      WHERE 
	        id_documento = '$pedido' AND tipo_documento = '$tipo_documento';";
	  $rs = mysqli_query($link, $sql);
	  $row = mysqli_fetch_array($rs);
	  $alicuota = floatval($row["alicuota"]);
	}

	// Se actualiza el encabezado del documento //
	$sql = "SELECT
				COUNT(articulo) AS renglones, ABS(SUM(cantidad_movimiento)) AS unidades 
		    FROM 
		      entradas_salidas 
		    WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$renglones = floatval($row["renglones"]);
	$unidades = floatval($row["unidades"]);


	$html = '{
				"pedido":"' . $pedido . '",
				"renglones":"' . $renglones . '",
				"unidades":"' . $unidades . '",
	         	"mensaje":"Hello World", 
	         	"estatus":"1",  
	         	"nro_documento":"' . $nro_documento . '"  
	        }';
} 
else {
	$sql = "DELETE FROM entradas WHERE id = $pedido AND tipo_documento = '$tipo_documento';";
	mysqli_query($link, $sql);
	$nro_documento = "0000000";

	$html = '{
				"pedido":"0",
				"renglones":"0",
				"unidades":"0",
	         	"mensaje":"Hello World", 
	         	"estatus":"1", 
	         	"nro_documento":"' . $nro_documento . '"  
	        }';
}

/// Actualizo el campo unidades ///
$sql = "UPDATE 
			entradas AS a 
			JOIN (SELECT 
						id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
					FROM 
						entradas_salidas 
					WHERE tipo_documento = '$tipo_documento' AND id_documento = $pedido 
					GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
		SET 
			a.unidades = b.cantidad 
		WHERE a.id = $pedido;";
mysqli_query($link, $sql);

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>