<?php 
require 'phpqrcode/qrlib.php';

$dir = 'temp/';

if(!file_exists($dir)) 
	mkdir($dir);

$file = "qr_decodibo.png";

$filename = $dir .  $file;

$tamanio = 8;
$level = 'Q';
$frameSize = 1;
$contenido = 'https://www.instagram.com/decodibo?igsh=MXJvOXBmZm8xMzc5OQ==';
//$contenido = 'https://decodibo.com.ve/LifeLength/Report_PCR_IGNACIO_HERNANDEZ.pdf';
//$contenido = 'Junior Enrique Sanabria Rubio';

QRcode::png($contenido, $filename, $level, $tamanio,$frameSize);
?>