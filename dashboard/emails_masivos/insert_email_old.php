<?php
ini_set('display_errors', 1);
require_once("../includes/conexBD.php");

$tipo = '01'; //$_POST["tipo"];
$notificacion = $_POST["notificacion"];
$notificar = $_POST["notificar"];
$seleccion = $_POST["seleccion"];
$guardayyenviar = $_POST["guardayyenviar"];
$username = $_POST["username"];
$asunto = $_POST["asunto"];
$adjunto = $_FILES["adjunto"]["name"];

$notificacion .= '<br /><img alt="" src="http://www.cementeriodeleste.com.ve/images/samples/logo.png">';
$notificacion .= "<br /> <h3> Esta cuenta de e-mail no es monitoreada, por favor no resporder la misma. Gracias... </h3>";

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

$sql = "INSERT INTO sco_notificaciones
		(Nnotificaciones, tipo, asunto, notificacion, notificar, notificados, adjunto, username, fecha, enviado)
		VALUES (NULL, '$tipo', '$asunto', '$notificacion', '$notificar', '', '$adjunto_name', '$username', NOW(), 0)"; 
$result = mysql_query($sql);

$id = mysql_insert_id($link);

$xSel = "";

$contador = 0;
foreach($seleccion as $sel)
{
	if($sel != "")
	{
		$xSel .= $sel.",";
		if($guardayyenviar=="on")
		{
			if($contador < 10) {
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


//if($guardayyenviar!="on")
//{
	$sql = "UPDATE sco_notificaciones SET notificados='$xSel', enviado = '0' WHERE Nnotificaciones = '$id'";
	$result = mysql_query($sql);
//}


header("Location: resume_email.php?id=$id&resp=".$resp);
?>