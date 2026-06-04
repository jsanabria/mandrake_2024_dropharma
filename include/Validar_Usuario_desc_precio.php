<?php 
session_start();

include "connect.php";

$usernama = $_REQUEST["usernama"] ?? ""; 
$password = md5($_REQUEST["password"] ?? ""); 
$idPurga = $_REQUEST["idPurga"] ?? 0;
$usercaja = $_REQUEST["usercaja"] ?? $usernama;

$tipo_documento = $_REQUEST["tipo_documento"] ?? "";
$nro_documento  = $_REQUEST["nro_documento"] ?? "";
$contexto       = $_REQUEST["contexto"] ?? "";

$usernama_sql = mysqli_real_escape_string($link, $usernama);
$password_sql = mysqli_real_escape_string($link, $password);
$usercaja_sql = mysqli_real_escape_string($link, $usercaja);
$tipo_documento_sql = mysqli_real_escape_string($link, $tipo_documento);
$nro_documento_sql = mysqli_real_escape_string($link, $nro_documento);
$contexto_sql = mysqli_real_escape_string($link, $contexto);

$tipo_descripcion = "";

$sql = "SELECT descripcion AS tipo
        FROM tipo_documento
        WHERE codigo = '$tipo_documento_sql'
        LIMIT 1";

$rsTipo = mysqli_query($link, $sql);
if ($rowTipo = mysqli_fetch_array($rsTipo)) {
    $tipo_descripcion = $rowTipo["tipo"];
}

$tipo_descripcion_sql = mysqli_real_escape_string($link, $tipo_descripcion);

$resp = "N";

$sql = "SELECT id 
        FROM usuario 
        WHERE username = '$usernama_sql' 
          AND password = '$password_sql'
        LIMIT 1;";

$rs = mysqli_query($link, $sql);

if ($row = mysqli_fetch_array($rs)) {
    $sql = "SELECT valor1 AS usuario 
            FROM parametro 
            WHERE codigo = '070' 
              AND RTRIM(valor1) = '$usernama_sql'
            LIMIT 1;";

    $rs = mysqli_query($link, $sql);

    if ($row = mysqli_fetch_array($rs)) {
        $resp = "S";
    }
}

$detalle = "TIPO DOCUMENTO: $tipo_documento_sql | ";
$detalle .= "DESCRIPCION: $tipo_descripcion_sql | ";
$detalle .= "NRO DOCUMENTO: $nro_documento_sql (salidas.id = $idPurga) | ";
$detalle .= "USUARIO EN CAJA: $usercaja_sql | ";
$detalle .= "CONTEXTO: $contexto_sql | ";
$detalle .= "AUTORIZADO: $resp";

$detalle_sql = mysqli_real_escape_string($link, $detalle);

$sql = "INSERT INTO audittrail
        (id, datetime, script, user, `action`, `table`, `field`)
        VALUES (
            NULL,
            NOW(),
            'SOLICITAR AUTORIZACION CAMBIO DESCUENTO PRECIO',
            '$usernama_sql',
            'DOCUMENTO: $nro_documento_sql',
            '$detalle_sql',
            ''
        )";

mysqli_query($link, $sql);	

echo $resp;
?>