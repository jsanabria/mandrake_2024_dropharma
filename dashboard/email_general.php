<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

$username = "robot";
include "../connect.php";

$sql = "SELECT valor1 AS enviar_mail, valor3 AS email_default FROM parametro WHERE codigo = '017'"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$enviar = $row["enviar_mail"];
$email_default = trim($row["email_default"]);
$limit = $email_default == "" ? 5 : 2;
if($enviar == "S") {
    $sql = "SELECT 
                Nnotificaciones, notificacion, notificar, notificados, notificados_efectivos, asunto, adjunto 
            FROM notificaciones a 
            WHERE enviado = '0' LIMIT 0, 1;"; 
    $rs = mysqli_query($link, $sql);
    if($row = mysqli_fetch_array($rs)) { 
        $id = $row["Nnotificaciones"];
        $notificar = $row["notificar"];
        $notificados = $row["notificados"];
        $notificados_efectivos = $row["notificados_efectivos"];
        $asunto = $row["asunto"];
        $adjunto = $row["adjunto"];
        $notificacion = $row["notificacion"];
        $sql = "SELECT valor2 AS tipo FROM parametro WHERE codigo = '016' AND valor1 = '$notificar';";
        $rs = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($rs);
        $notificar = $row["tipo"];

        //$xSel = "";

        $seleccion = explode(",",$notificados);
        $efectivos = explode(",",$notificados_efectivos);

        $contador = 0;
        foreach($seleccion as $sel)
        {
            $sel = trim($sel); 
            if($sel != "")
            {
                //if($contador < $limit) $xSel .= $sel.",";
                $sw = true;
                foreach ($efectivos as $efc) {
                    if(trim($efc) == trim($sel)) $sw = false;
                }

                if($contador < $limit and $sw) {
                    $from = "callcenter@deleste.com.ve";
                    $fromname = "Dropharma - No Responder, Cta no Monitoreada"; 
                    $subject = $asunto;
                    $body = $notificacion;
                    $altbody = "";

                    unset($mail);
                    $mail = new PHPMailer(true);

                    $resp = "";
                    try {
                        $mail->SMTPDebug = 0;                               //SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                        $mail->isSMTP();                                    // Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';               // Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                           // Enable SMTP authentication
                        //$mail->Username   = 'jsanabria44@gmail.com';      // SMTP username
                        //$mail->Password   = '??????';               // SMTP password
                        $mail->Username   = 'Dropharmadmsistema@gmail.com'; // SMTP username
                        $mail->Password   = 'abeabe123';                    // SMTP password
                        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                        $mail->SMTPSecure = 'tls';
                        $mail->Port       = 587;                            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                        $mail->CharSet = "UTF-8";
                        $mail->setFrom($from, $fromname);

                        if($email_default == "") { 
                            $to = $sel;
                            $toname = $notificar;
                        } 
                        else {
                            $to = $email_default;
                            $toname = $notificar;
                        }
                        $mail->addAddress($to, $toname);      // Add a recipient

                        $mail->isHTML(true);                                // Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body    = $body;
                        $mail->AltBody = $altbody;

                        $arhivo = basename($adjunto);
                        if($adjunto != "") 
                            $mail->AddAttachment("../carpetacarga/" . $adjunto, $arhivo);

                        $mail->send();
                        $resp = "Message has been sent";

                        $sql = "UPDATE notificaciones SET notificados_efectivos=CONCAT(IFNULL(notificados_efectivos, ''),' $sel,') WHERE Nnotificaciones = '$id'"; 
                        mysqli_query($link, $sql);

                        $contador++;
                    } catch (Exception $e) {
                        $resp = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        die($resp);
                    }
                }
            }       
        }
        unset($mail);


        if($contador == 0) {
            $sql = "UPDATE notificaciones SET enviado = '1' WHERE Nnotificaciones = '$id'";
            mysqli_query($link, $sql);
        }
    }
    unset($mail);
}
?>
