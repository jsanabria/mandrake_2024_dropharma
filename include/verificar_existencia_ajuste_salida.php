<?php 

include "connect.php";

$id = $_REQUEST["id"];

if(isset($_REQUEST["lote"])) {
	$xlot = explode(",", $_REQUEST["lote"]);

	if(count($xlot) == 2) {
		$lote = $xlot[0];
		$existencia = floatval($xlot[1]);

		$cantidad = floatval($_REQUEST["cantidad"]);
		$unidad = $_REQUEST["unidad"]; 

		$sql = "SELECT cantidad FROM unidad_medida WHERE codigo = '$unidad';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$cantidad_um = floatval($row["cantidad"]);

		$solicitado = $cantidad * $cantidad_um;

		//die("Lote: $lote - Existencia: $existencia - Solicitado: $solicitado");

		if($solicitado > $existencia) echo "0";
		else echo "1";
	} 
	else echo "0";

} 
else echo "0";
?>

