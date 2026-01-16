<?php
ini_set('display_errors', 1);
include "../../connect.php";

$tipo = '01'; //$_POST["tipo"];
$notificacion = $_POST["notificacion"];
$notificar = $_POST["notificar"];
$seleccion = $_POST["seleccion"];
$guardayyenviar = $_POST["guardayyenviar"];
$username = $_POST["username"];
$asunto = $_POST["asunto"];
$adjunto = $_FILES["adjunto"]["name"];

//$notificacion .= '<br /><img alt="" src="http://www.cementeriodeleste.com.ve/images/samples/logo.png">';
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

$notificados = "";
foreach ($seleccion as $key => $value) {
	$notificados .= " $value,";
}


$sql = "INSERT INTO notificaciones
		(Nnotificaciones, tipo, asunto, notificacion, notificar, notificados, adjunto, username, fecha, enviado)
		VALUES (NULL, '$tipo', '$asunto', '$notificacion', '$notificar', '$notificados', '$adjunto_name', '$username', NOW(), 0)"; 
$result = mysqli_query($link, $sql);

$id = mysqli_insert_id($link);

/*$xSel = "";

$contador = 0;
//include("../autogestion/variables_mail.php");
foreach($seleccion as $sel)
{
	if($sel != "")
	{
		$xSel .= $sel.",";
		if($guardayyenviar=="on")
		{
			if($contador < 10) {
                unset($mail);
                $mail = new PHPMailer();                        // Declaramos una instancia de PhpMailer
                $mail->PluginDir = "../../phpmailer/";              // Carpeta de PhpMailer
                $mail->Host = SMTP_SERVER;                      // Servidor SMTP
                $mail->Helo = SMTP_SERVER;                      // Servidor SMTP
                $mail->Port = SMTP_PORT;                        // Puerto del Servidor
                $mail->SMTPAuth = SMTP_AUTH;                    // Servidor Autentificado o No
                $mail->Username = SMTP_USERNAME;                // Login de Usuario Autorizado para Envio
                $mail->Password = SMTP_PASSWORD;                // Clave de Usuario Autorizado
                $mail->IsSMTP();                                // Indicamos que es un servidor SMTP
                $mail->Mailer = "smtp";                         // Que funciones utilizaremos para Envio     *
                $mail->CharSet = "UTF-8";                       // CharSet de Caracteres
                $mail->SMTPSecure = 'ssl';

                $mail->From = "callcenter@deleste.com.ve";
                $mail->FromName = "Deleste - No Responder, Cta no Monitoreada"; 
                $mail->Subject = $asunto;
                $mail->CharSet = "UTF-8";
                $mail->IsHTML(true);


				if($sel!="") $mail->AddAddress($sel,$tipo." ".$notificar);
				
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
			unset($mail);		
		}
	}		
}*/


//if($guardayyenviar!="on")
//{
	//$sql = "UPDATE sco_notificaciones SET notificados='$xSel', enviado = '0' WHERE Nnotificaciones = '$id'";
	//$result = mysql_query($sql);
//}


header("Location: resume_email.php?id=$id&resp=".$resp);
?>