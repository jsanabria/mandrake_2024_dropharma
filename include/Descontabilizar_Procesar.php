<?php 
include "connect.php";

$id = $_REQUEST["id"];
$username = $_REQUEST["username"];

$sql = "SELECT tipo, contabilizacion AS fecha FROM cont_comprobante WHERE id = $id "; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$fecha = $row["fecha"]; 
$tipo = $row["tipo"];

if(VerificaPeriodoContable($fecha, $tipo)) {
	$sql= "UPDATE cont_comprobante SET contabiliza = '$username', contabilizacion = NULL, fecha_contabiliza = CURDATE() WHERE id = $id;"; 
	mysqli_query($link, $sql);
	echo 1;
}
else echo 0;

include "desconnect.php";


function VerificaPeriodoContable($fecha, $tipo) {
	include "connect.php";

	$sql = "SELECT 
				cerrado 
			FROM 
				cont_periodo_contable 
			WHERE 
				'$fecha' BETWEEN fecha_inicio AND fecha_fin;"; 
	echo $sql . "<br";
	$rs = mysqli_query($link, $sql);

	if(!$row = mysqli_fetch_array($rs)) {
		//$this->CancelMessage = "El periodo contable no existe; verifique.";
		return FALSE;
	}
	else { 
		if($row["cerrado"] == "S") {
			//$this->CancelMessage = "El periodo contable est&aacute; cerrado; verifique.";
			return FALSE;
		}
	}


	$fc = explode("-", $fecha);
	$mes = "M" . str_pad($fc["1"], 2, "0", STR_PAD_LEFT);
	$sql = "SELECT 
				id 
			FROM 
				cont_mes_contable 
			WHERE 
				tipo_comprobante = '$tipo' AND $mes = 'S';"; 
	echo $sql . "<br";
	$rs = mysqli_query($link, $sql);
	if($row = mysqli_fetch_array($rs)) {
		//$this->CancelMessage = "El mes contable est&aacute; cerrado para el tipo de comprobante; verifique.";
		return FALSE;
	}

	include "desconnect.php";
	return TRUE;
}

?>
