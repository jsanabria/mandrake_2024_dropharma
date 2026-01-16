<?php 
require '../codigo_qr/phpqrcode/qrlib.php';

if(!file_exists($dir)) 
	mkdir($dir);

$tamanio = 8;
$level = 'Q';
$frameSize = 1;
//$contenido = 'http://lagunita.clublagunita.com/autogestion/encuesta_enviar.php?Nafiliado=Xafiliado&Nencuesta=2';

QRcode::png($contenido, $filename, $level, $tamanio,$frameSize);
?>