<?php
include "../../connect.php";

$tipo = trim($_GET["tipo"]);

$cedula = trim($_GET["cedula"]);
$nombre = trim($_GET["nombre"]);
$fecha_desde = trim($_GET["fecha_desde"]);
$fecha_hasta = trim($_GET["fecha_hasta"]);

$where = "";

switch($tipo) {
case "001":
	if($cedula != "")
		$where .= "AND ci_rif LIKE '%$cedula%'";
	else {
		if($nombre != "") {
			$where .= "AND (nombre LIKE '%$nombre%' or email1 like '%$nombre%' or contacto like '%$nombre%') ";
		}

		/*if($fecha_desde != "" and $fecha_hasta != "") {
			$ff = explode("/", $fecha_desde);
			$fecha_desde = $ff[2] . "-". $ff[1] . "-" . $ff[0];
			$ff = explode("/", $fecha_hasta);
			$fecha_hasta = $ff[2] . "-". $ff[1] . "-" . $ff[0];

			$where .= "AND a.fecha_registro BETWEEN '$fecha_desde' AND '$fecha_hasta'";
		}*/
	}


	$sql = "SELECT COUNT(id) AS cantidad FROM cliente WHERE LTRIM(RTRIM(email1)) <> '' $where;";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$total = $row["cantidad"];

	$sql = "SELECT 
				id, ci_rif, nombre, email1 
			FROM 
				cliente
			WHERE 
				LTRIM(RTRIM(email1)) <> '' $where ORDER BY nombre;";

	$selall = '<input type="checkbox" id="seleccion[]" name="seleccion[]" value="" onclick="seleccionar(this.value);" /><b>C.I. / R.I.F. | Cliente | Email </b>[Seleccionar Todo ('.$total.' Items)]';
	break;
case "002":
	if($cedula != "")
		$where .= "AND ci_rif LIKE '%$cedula%'";
	else {
		if($nombre != "") {
			$where .= "AND (nombre LIKE '%$nombre%' or email1 like '%$nombre%') ";
		}
	}

	$sql = "SELECT COUNT(id) AS cantidad FROM proveedor WHERE LTRIM(email1) <> '' $where;"; 
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$total = $row[0];

	$sql = "SELECT 
				id, ci_rif, nombre, email1 
			FROM proveedor 
			WHERE LTRIM(RTRIM(email1)) <> '' $where ORDER BY nombre";

	$selall = '<input type="checkbox" id="seleccion[]" name="seleccion[]" value="" onclick="seleccionar(this.value);" /><b>C.I. / R.I.F. | Proveedor | Email </b>[Seleccionar Todo ('.$total.' Items)]';
	break;
case "003":
	if($cedula != "")
		$where .= "AND username LIKE '%$cedula%'";
	else {
		if($nombre != "") {
			$where .= "AND (nombre LIKE '%$nombre%' ";
			$where .= "OR username LIKE '%$nombre%' or email like '%$nombre%') ";
		}
	}

	$sql = "SELECT COUNT(username) AS cantidad FROM usuario WHERE activo = 'S' AND LTRIM(email) <> '' $where;"; 

	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$total = $row[0];

	$sql = "SELECT 
				id, username, nombre, email 
			FROM usuario WHERE activo = 'S' AND LTRIM(email) <> '' $where ORDER BY nombre";

	$selall = '<input type="checkbox" id="seleccion[]" name="seleccion[]" value="" onclick="seleccionar(this.value);" /><b>Username | Nombre | Email </b>[Seleccionar Todo ('.$total.' Items)]';
	break;
case "004":
	$sql = "SELECT CURDATE() AS fecha;";
	break;
}

$rs = mysqli_query($link, $sql);

echo $selall;
while($row = mysqli_fetch_array($rs)) {
	switch($tipo) {
	case "001":
		echo '<br /><input type="checkbox" id="seleccion[]" name="seleccion[]" value="'.$row["email1"].'" />' . $row["ci_rif"] . ' | ' . $row["nombre"] . ' | <b>' . $row["email1"] . '</b>';
		break;
	case "002":
		echo '<br /><input type="checkbox" id="seleccion[]" name="seleccion[]" value="'.$row["email1"].'" />' . $row["ci_rif"] . ' | ' . $row["nombre"] . ' | <b>' . $row["email1"] . '</b>';
		break;
	case "003":
		echo '<br /><input type="checkbox" id="seleccion[]" name="seleccion[]" value="'.$row["email"].'" />' . $row["username"] . ' | ' . $row["nombre"] . ' | <b>' . $row["email"] . '</b>';
		break;
	case "004":
		break;
	}
}

?>
