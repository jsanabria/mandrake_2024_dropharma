<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/../include/tdcfcv/tdcfcv_bootstrap.php";

use PHPMailer\PHPMailer\PHPMailer;

$cia = ExecuteRow("SELECT ci_rif, nombre FROM compania WHERE id = 1;");

$destino = "js@decodibo.com"; // "proveedores.sistemas@seniat.gob.ve";
$fromEmail = "sistema@tudominio.com";
$fromName = "Sistema de Facturación Mandrake V2024 Compañía: " . $cia["ci_rif"] . " R.I.F.:" . $cia["nombre"] . "";

$periodo = $_GET["periodo"] ?? date("Y-m", strtotime("first day of previous month"));

if (!preg_match('/^\d{4}-\d{2}$/', $periodo)) {
    die("Periodo inválido. Use formato YYYY-MM.");
}

$fechaInicio = $periodo . "-01";
$fechaFin = date("Y-m-d", strtotime($fechaInicio . " +1 month"));
$periodoTexto = date("m/Y", strtotime($fechaInicio));

$yaEnviado = intval(ExecuteScalar("
    SELECT COUNT(*)
    FROM reporte_seniat_ne
    WHERE periodo = " . AdjustSql($periodo)
));

if ($yaEnviado > 0 && empty($_GET["reenviar"])) {
    die("El reporte del periodo {$periodoTexto} ya fue enviado. Use ?periodo={$periodo}&reenviar=1 para reenviar.");
}

$row = ExecuteRow("
    SELECT 
        COUNT(*) AS cantidad,
        IFNULL(SUM(a.total), 0) AS monto_total
    FROM salidas AS a
    WHERE 
        a.tipo_documento = 'TDCNET'
        AND a.estatus = 'NUEVO'
        AND a.fecha >= " . AdjustSql($fechaInicio) . "
        AND a.fecha < " . AdjustSql($fechaFin) . "
        AND EXISTS (
            SELECT 1
            FROM entradas_salidas AS b
            WHERE b.id_documento = a.id
              AND b.tipo_documento = a.tipo_documento
              AND b.newdata = 'S'
        )
");

$cantidad = intval($row["cantidad"] ?? 0);
$montoTotal = floatval($row["monto_total"] ?? 0);

$asunto = "Reporte de notas de entrega sin facturar - Periodo {$periodoTexto}";

$cuerpoTexto = "
Estimados,

Por medio de la presente se informa que al cierre del periodo impositivo {$periodoTexto}, el sistema registra:

Cantidad de notas de entrega sin facturar: {$cantidad}
Monto acumulado: Bs. " . number_format($montoTotal, 2, ",", ".") . "

Atentamente,
Sistema de Facturación
";

$cuerpoHtml = "
<p>Estimados,</p>

<p>Por medio de la presente se informa que al cierre del periodo impositivo <strong>{$periodoTexto}</strong>, el sistema registra:</p>

<table border='1' cellpadding='6' cellspacing='0'>
    <tr>
        <th align='left'>Cantidad de notas de entrega sin facturar</th>
        <td>{$cantidad}</td>
    </tr>
    <tr>
        <th align='left'>Monto acumulado</th>
        <td>Bs. " . number_format($montoTotal, 2, ",", ".") . "</td>
    </tr>
</table>

<p>Atentamente,<br>Sistema de Facturación</p>
";

$mail = new PHPMailer(true);

try {
    $mailer = Config("SMTP.PHPMAILER_MAILER") ?: "mail";

    if ($mailer === "smtp") {
        $mail->isSMTP();
        $mail->Host = Config("SMTP.SERVER");
        $mail->Port = intval(Config("SMTP.SERVER_PORT"));
        $mail->SMTPAuth = Config("SMTP.SERVER_USERNAME") !== "";
        $mail->Username = Config("SMTP.SERVER_USERNAME");
        $mail->Password = Config("SMTP.SERVER_PASSWORD");

        if (Config("SMTP.SECURE_OPTION") !== "") {
            $mail->SMTPSecure = Config("SMTP.SECURE_OPTION");
        }
    } else {
        $mail->isMail();
    }

    $mail->CharSet = "UTF-8";
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($destino);
    $mail->Subject = $asunto;
    $mail->isHTML(true);
    $mail->Body = $cuerpoHtml;
    $mail->AltBody = $cuerpoTexto;

    $mail->send();

    ExecuteStatement("
        INSERT INTO reporte_seniat_ne
            (periodo, cantidad, monto_total, email_destino, enviado_en, usuario)
        VALUES
            (
                " . AdjustSql($periodo) . ",
                {$cantidad},
                {$montoTotal},
                " . AdjustSql($destino) . ",
                NOW(),
                " . AdjustSql(TdcfcvCurrentUser()) . "
            )
        ON DUPLICATE KEY UPDATE
            cantidad = VALUES(cantidad),
            monto_total = VALUES(monto_total),
            email_destino = VALUES(email_destino),
            enviado_en = NOW(),
            usuario = VALUES(usuario)
    ");

    $detalle = AdjustSql(json_encode([
        "tipo" => "INCIDENCIA",
        "codigo" => "REPORTE_NE_SENIAT",
        "periodo" => $periodo,
        "cantidad" => $cantidad,
        "monto_total" => $montoTotal,
        "destino" => $destino,
        "fecha_envio" => date("Y-m-d H:i:s")
    ], JSON_UNESCAPED_UNICODE));

    ExecuteStatement("
        INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
        VALUES
            (
                NULL,
                NOW(),
                'Envío reporte SENIAT notas de entrega sin facturar',
                " . AdjustSql(TdcfcvCurrentUser()) . ",
                'L',
                'reporte_seniat_ne',
                'periodo',
                " . AdjustSql($periodo) . ",
                '',
                '{$detalle}'
            )
    ");

    echo "Reporte enviado correctamente para el periodo {$periodoTexto}. Cantidad: {$cantidad}. Monto: Bs. " . number_format($montoTotal, 2, ",", ".");

} catch (\Throwable $e) {
    echo "Error enviando reporte: " . $e->getMessage();
}