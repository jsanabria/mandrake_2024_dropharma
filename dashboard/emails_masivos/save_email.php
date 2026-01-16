<?php
ini_set('display_errors', 1);
include '../../connect.php';

$id = $_POST["id"];

////////////////////////////////////////////////
$sql = "select notificar, notificados_efectivos from notificaciones a where Nnotificaciones = '$id';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$notificar = $row["notificar"];
$notificados_efectivos = $row["notificados_efectivos"];
$sql = "SELECT valor2 AS tipo FROM parametro WHERE codigo = '040' AND valor1 = '$notificar';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$notificar = $row["tipo"];
////////////////////////////////////////////////


$notificacion = $_POST["notificacion"];
//$tipo = $_POST["tipo"];
//$notificar = $_POST["notificar"];
$notificados = $_POST["seleccion"];
$guardayyenviar = $_POST["guardayyenviar"];
$username = $_POST["username"];
$asunto = $_POST["asunto"];
$adjunto = $_FILES["adjunto"]["name"];
$adjunto_name = "";
if(strlen(trim($adjunto))>0)
{
	$tmp_name = $_FILES["adjunto"]["tmp_name"];

	$adjunto = date("Ymd_His")."_".$adjunto;
	$adjunto_name = $adjunto;
	//$adjunto = "/var/www/html/windeco/xml/$adjunto";
	$adjunto = "../../carpetacarga/$adjunto";


	if (!copy($tmp_name, $adjunto)) die("Error al copiar $archivo...\n");
}

$sql = "UPDATE notificaciones SET asunto = '$asunto', notificacion = '$notificacion', adjunto = '$adjunto_name' WHERE Nnotificaciones = '$id'";
mysqli_query($link, $sql);

/*$xSel = "";

$seleccion = explode(",",$notificados);
$efectivos = explode(",",$notificados_efectivos);

$contador = 0;
foreach($seleccion as $sel)
{
	if($sel != "")
	{
		if($contador < 10) $xSel .= $sel.",";
		if($guardayyenviar=="on")
		{
			$sw = true;
			foreach ($efectivos as $efc) {
				if($efc == $sel) $sw = false;
			}

			if($contador < 10 and $sw) {
				include("../autogestion/variables_mail.php");
				$mail->From = "info@deleste.com.ve";
				
				$mail->FromName = "Deleste - No Responder, Cta no Monitoreada";
				
				$mail->CharSet = "UTF-8";
				
				$mail->Subject = $asunto;
				
				if($sel!="") $mail->AddAddress($sel,$tipo." ".$notificar);
				//if($correo2!="") $mail->AddAddress($correo2,$nombre);
				
				$mail->IsHTML(true);
				$mail->Body = $notificacion;
				
				//$mail->AddAttachment("archivos/Ordenes/ordenS".$param[0].".html", "ordenS".$param[0].".html");
				$arhivo = basename($adjunto);
				$mail->AddAttachment($adjunto,$arhivo);
				
				if($mail->Send()) $resp ="Correo Enviado";
				else $resp = "Error ".$mail->ErrorInfo;
			
				$mail = null;

				$sql = "UPDATE sco_notificaciones SET notificados_efectivos='$xSel', enviado = '1' WHERE Nnotificaciones = '$id'";
				$result = mysql_query($sql);

				$contador++;
			}
		}
	}		
}

if($notificados==$xSel)
	$sql = "UPDATE sco_notificaciones SET enviado = '1' WHERE Nnotificaciones = '$id'";
else
	$sql = "UPDATE sco_notificaciones SET enviado = '0' WHERE Nnotificaciones = '$id'";

$result = mysql_query($sql);*/

header("Location: resume_email.php?id=$id&resp=".$resp);
?>