<?php
session_start();

include "connect.php";

$sql = "SET NAMES 'utf8';";
mysqli_query($link, $sql);

$sql = "SET CHARACTER SET utf8;";
mysqli_query($link, $sql);

if (!isset($_FILES['uploadedFile']) or !$_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK or trim($_FILES['uploadedFile']['name']) == "") {
	$_SESSION['error'] = "Debe seleccionar un archivo. " . $_FILES['uploadedFile']['error'];
	header("Location: Error " . $_SESSION['error']);
	die();
}

// get details of the uploaded file
$fichero = $_FILES["uploadedFile"];
$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
$fileName = $_FILES['uploadedFile']['name'];
$fileSize = $_FILES['uploadedFile']['size'];
$fileType = $_FILES['uploadedFile']['type'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

$newFileName = md5(time() . $fileName) . '.' . $fileExtension;

$allowedfileExtensions = array('csv');

if (!in_array($fileExtension, $allowedfileExtensions)) {
	$_SESSION['error'] = "El archivo debe ser tipo .csv";
	header("Location: Error " . $_SESSION['error']);
	die();
}

// directory in which the uploaded file will be moved
$uploadFileDir = '../carpetacarga/';
$dest_path = $uploadFileDir . $newFileName;

if(move_uploaded_file($fileTmpPath, $dest_path)) {
	$_SESSION['message'] = 'El archivo subio exitosamente.';

	$linea = 0;
	//Abrimos nuestro archivo
	$archivo = fopen($dest_path, "r");
	//Lo recorremos
	$factura = "";
	$fecha = "";
	while (($datos = fgetcsv($archivo, 0, ";")) == true) {
		$num = count($datos);
		//Recorremos las columnas de esa linea
		if($num == 2) {
			for($columna = 0; $columna < $num; $columna++) {
				switch($columna) {
				case 0:	
					$factura = str_pad($datos[$columna], 6, "0",STR_PAD_LEFT);
					break;
				case 1:	
					$fecha = $datos[$columna];
					break;
				}
			}

			$sql = "SELECT * FROM salidas 
					WHERE tipo_documento = 'TDCFCV' AND SUBSTRING(nro_documento, 6, LENGTH(nro_documento))  = '$factura';"; 
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) {
				$sql = "UPDATE salidas SET entregado = 'S', fecha_entrega = '$fecha' 
						WHERE tipo_documento = 'TDCFCV' AND SUBSTRING(nro_documento, 6, LENGTH(nro_documento))  = '$factura';"; 
				mysqli_query($link, $sql);
				$linea++;
			}
		}
		$factura = "";
		$fecha = "";
	}
	//Cerramos el archivo
	$_SESSION['message'] = $_SESSION['message'] . " Actualizadas: " . $linea;
	fclose($archivo);
}
else {
  $_SESSION['error'] = 'Hay alg&uacute;n error en la copia del archivo al directorio de carga.';
}

header("Location: entregar_facturas.php");

die();

/////////////////
?>
