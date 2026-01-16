<?php 
// Configuración rutina de envío de correo 
// PHPMailer Servidor de Correos Saliente
define('SMTP_SERVER', 'smtp.gmail.com');

// PHPMailer Servidor de Correos Saliente Puerto
define('SMTP_PORT', 465);

// PHPMailer Servidor de Correos Saliente Requiere Autentificacion
define('SMTP_AUTH', true);

// PHPMailer Servidor de correos Saliente Nombre de Usuario
define('SMTP_USERNAME', 'callcenter@deleste.com.ve');

//PHPMailer Servidor de Correos Saliente Clave de Usuario 89L786
define('SMTP_PASSWORD', 'asistencia.123');

include("../phpmailer/class.phpmailer.php");

$mail = new PHPMailer();                        // Declaramos una instancia de PhpMailer
$mail->PluginDir = "../phpmailer/";        		// Carpeta de PhpMailer
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
//$mail->SMTPSecure = 'tls';
?>