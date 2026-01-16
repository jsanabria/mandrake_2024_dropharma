<?php
session_start();

include "connect.php";

$id = $_REQUEST["id"]; 

// Leo el campo de fotos de la tabla salida 
$sql = "SELECT fotos FROM salidas WHERE id = $id;"; 
$rs = mysqli_query($link, $sql);;
$row = mysqli_fetch_array($rs);
$fotos = $row["fotos"];
$xfotos = "";


//Como el elemento es un arreglos utilizamos foreach para extraer todos los valores
foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)
{
	//Validamos que el archivo exista
	if($_FILES["archivo"]["name"][$key]) {
		$filename = $_FILES["archivo"]["name"][$key]; //Obtenemos el nombre original del archivo
		$filename =  $id . "_" . date("YmsHis") . "_$filename";
		$source = $_FILES["archivo"]["tmp_name"][$key]; //Obtenemos un nombre temporal del archivo
		
		$directorio = '../carpetacarga/'; //Declaramos un  variable con la ruta donde guardaremos los archivos
		
		//Validamos si la ruta de destino existe, en caso de no existir la creamos
		if(!file_exists($directorio)){
			mkdir($directorio, 0777) or die("No se puede crear el directorio de extracci&oacute;n");	
		}
		
		$dir=opendir($directorio); //Abrimos el directorio de destino
		$target_path = $directorio.'/'.$filename; //Indicamos la ruta de destino, así como el nombre del archivo
		
		//Movemos y validamos que el archivo se haya cargado correctamente
		//El primer campo es el origen y el segundo el destino
		if(move_uploaded_file($source, $target_path)) {	
			$xfotos .= $filename . ","; 
		} else {	
			echo "Ha ocurrido un error, por favor inténtelo de nuevo.<br>";
		}
		closedir($dir); //Cerramos el directorio de destino
	}
} 

if(trim($fotos) == "") $fotos = substr($xfotos, 0, strlen($xfotos)-1); 
else $fotos = $fotos . "," . substr($xfotos, 0, strlen($xfotos)-1); 

$sql = "UPDATE salidas SET fotos = '$fotos' WHERE id = $id;";
mysqli_query($link, $sql);

header("Location: ../SalidasView?showdetail=entradas_salidas&id=$id&tipo=TDCNET");

?>