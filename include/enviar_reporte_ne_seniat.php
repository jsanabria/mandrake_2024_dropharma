<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "connect.php";

// Si usas Composer / PHPMaker 2024
require_once "../vendor/autoload.php";

// ===============================
// CONFIGURACIÓN
// ===============================
$correoDestino = "jsanabria44@gmail.com"; // Cambiar
$correoCopia = ""; // Opcional

/*
$correoCC = [
    "mc@empresa.com",
    "administracion@empresa.com"
];
/*/

$smtpHost = "mail.decodibo.com";
$smtpUser = "info@decodibo.com";
$smtpPass = "rFVLnbb!ztn}";
$smtpPort = 587;
$smtpSecure = "tls"; // tls o ssl

$correoDesde = $smtpUser;
$nombreDesde = "Sistema Mandrake V2024";

// ===============================
// DATOS DE LA COMPAÑÍA
// ===============================
$sql = "SELECT ci_rif AS rif, nombre AS empresa 
        FROM compania 
        LIMIT 1";

$rowCia = mysqli_fetch_assoc(mysqli_query($link, $sql));

$rifEmpresa = $rowCia["rif"] ?? "";
$nombreEmpresa = $rowCia["empresa"] ?? "";

// ===============================
// RESUMEN DE NOTAS PENDIENTES
// ===============================
$sql = "
    SELECT 
        COUNT(*) AS cantidad_documentos,
        IFNULL(SUM(a.total), 0) AS monto_total
    FROM salidas AS a
    WHERE 
        a.tipo_documento = 'TDCNET'
        AND a.estatus = 'NUEVO'
        AND EXISTS (
            SELECT 1
            FROM entradas_salidas AS b
            WHERE b.id_documento = a.id
              AND b.tipo_documento = a.tipo_documento
              AND b.newdata = 'S'
        )
";

$row = mysqli_fetch_assoc(mysqli_query($link, $sql));

$cantidad = intval($row["cantidad_documentos"] ?? 0);
$montoTotal = floatval($row["monto_total"] ?? 0);

// Si no hay notas pendientes, no envía correo
if ($cantidad <= 0) {
    include "desconnect.php";
    echo "No existen notas de entrega pendientes por facturar.";
    exit;
}

// ===============================
// DETALLE OPCIONAL
// ===============================
$sql = "
    SELECT 
        a.id,
        a.nro_documento,
        DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha,
        a.total
    FROM salidas AS a
    WHERE 
        a.tipo_documento = 'TDCNET'
        AND a.estatus = 'NUEVO'
        AND EXISTS (
            SELECT 1
            FROM entradas_salidas AS b
            WHERE b.id_documento = a.id
              AND b.tipo_documento = a.tipo_documento
              AND b.newdata = 'S'
        )
    ORDER BY a.fecha, a.id
";

$rsDetalle = mysqli_query($link, $sql);

$detalleHtml = "";

while ($d = mysqli_fetch_assoc($rsDetalle)) {
    $detalleHtml .= "
        <tr>
            <td>{$d["id"]}</td>
            <td>{$d["nro_documento"]}</td>
            <td>{$d["fecha"]}</td>
            <td style='text-align:right;'>" . number_format(floatval($d["total"]), 2, ",", ".") . "</td>
        </tr>
    ";
}

// ===============================
// CUERPO DEL CORREO
// ===============================
$fechaReporte = date("d/m/Y");

$asunto = "Relación de Órdenes de Entrega pendientes por facturar - " . $nombreEmpresa;

$mensaje = "
<html>
<body style='font-family: Arial, sans-serif; font-size: 14px;'>

<p>
    <strong>Atención:</strong><br>
    Servicio Nacional Integrado de Administración Aduanera y Tributaria SENIAT
</p>

<p>
    La compañía <strong>{$nombreEmpresa}</strong>, RIF <strong>{$rifEmpresa}</strong>,
    cumple con informar sobre la relación de Órdenes de Entrega pendientes por facturar
    al día <strong>{$fechaReporte}</strong>.
</p>

<table border='1' cellpadding='6' cellspacing='0' style='border-collapse: collapse;'>
    <tr style='background:#f2f2f2;'>
        <th>Cantidad de Órdenes de Entrega</th>
        <th>Monto Total</th>
    </tr>
    <tr>
        <td style='text-align:center;'>{$cantidad}</td>
        <td style='text-align:right;'>" . number_format($montoTotal, 2, ",", ".") . "</td>
    </tr>
</table>

<br>

<table border='1' cellpadding='6' cellspacing='0' style='border-collapse: collapse; width:100%;'>
    <tr style='background:#f2f2f2;'>
        <th>ID</th>
        <th>Nro Documento</th>
        <th>Fecha</th>
        <th>Total</th>
    </tr>
    {$detalleHtml}
</table>

<p>
    Atentamente,<br>
    <strong>{$nombreEmpresa}</strong><br>
    <strong>Sistema de Facturaci&oacute;n e Inventario {$nombreDesde}</strong>
</p>

</body>
</html>
";

// ===============================
// ENVÍO DE CORREO
// ===============================
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->SMTPSecure = $smtpSecure;
    $mail->Port = $smtpPort;

    $mail->CharSet = "UTF-8";
    $mail->setFrom($correoDesde, $nombreDesde);
    $mail->addAddress($correoDestino);

    if (trim($correoCopia) != "") {
        $mail->addCC($correoCopia);
    }

    /*
    foreach ($correoCC as $correo) {
        if (trim($correo) != "") {
            $mail->addCC($correo);
        }
    }
    */

    $mail->isHTML(true);
    $mail->Subject = $asunto;
    $mail->Body = $mensaje;

// $mail->SMTPDebug = 2;
// $mail->Debugoutput = "html";

    $mail->send();

    echo "Correo enviado correctamente.";

} catch (Exception $e) {
    echo "Error enviando correo: " . $mail->ErrorInfo;
}

include "desconnect.php";
?>