<?php
include("variables_mail.php");

$mail->From = $from;

$mail->FromName = $fromname;

$mail->Subject = $subject;

$mail->AddAddress($to, $toname);

if(strlen(trim($to2)) > 0) $mail->AddAddress($to2, $toname);
if(strlen(trim($to3)) > 0) $mail->AddAddress($to3, $toname);
if(strlen(trim($to4)) > 0) $mail->AddAddress($to4, $toname);

$mail->IsHTML(true);
$mail->Body = $notificacion;

//die("$to <br> $to2 <br> $to3 <br> $to4 <br> $from | $fromname | $subject | $to | $toname | $notificacion");
if(strlen(trim($adjunto)) > 0) {
	$arhivo = basename($adjunto);
	$mail->AddAttachment($adjunto,$arhivo);
}

if($mail->Send()) $resp ="Correo Enviado Exitosamente";
else $resp = "Error ".$mail->ErrorInfo;
?>
