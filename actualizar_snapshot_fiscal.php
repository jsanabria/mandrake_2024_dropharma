<?php
session_start();
require("include/connect2.php");

mysqli_set_charset($link, "utf8");
mysqli_query($link, "SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysqli_query($link, "SET CHARACTER SET utf8");
mysqli_query($link, "SET collation_connection = 'utf8_general_ci'");

$tipo_documento = "TDCFCV";
$limite = 3000;

// 1) Congelar datos fiscales del cliente en salidas
$sql = "
    SELECT
        s.id,
        c.ci_rif,
        c.nombre,
        IFNULL(c.direccion,'') AS direccion,
        CONCAT(
            REPLACE(IFNULL(c.telefono1,''), ' ', ''),
            ' ',
            REPLACE(IFNULL(c.telefono2,''), ' ', '')
        ) AS telefono
    FROM salidas s
    JOIN cliente c ON c.id = s.cliente
    WHERE s.tipo_documento = CONVERT('{$tipo_documento}' USING utf8) COLLATE utf8_general_ci
        AND s.estatus = CONVERT('PROCESADO' USING utf8) COLLATE utf8_general_ci
      AND (
            s.cliente_ci_rif IS NULL OR s.cliente_ci_rif = ''
         OR s.cliente_nombre IS NULL OR s.cliente_nombre = ''
         OR s.cliente_direccion IS NULL OR s.cliente_direccion = ''
         OR s.cliente_telefono IS NULL OR s.cliente_telefono = ''
      )
    LIMIT {$limite}
";

$rs = mysqli_query($link, $sql) or die(mysqli_error($link));

$salidasActualizadas = 0;

while ($row = mysqli_fetch_assoc($rs)) {
    $id = intval($row["id"]);

    $ci_rif = mysqli_real_escape_string($link, $row["ci_rif"]);
    $nombre = mysqli_real_escape_string($link, $row["nombre"]);
    $direccion = mysqli_real_escape_string($link, trim($row["direccion"]));
    $telefono = mysqli_real_escape_string($link, trim($row["telefono"]));

    $update = "
    UPDATE salidas
        SET
            cliente_ci_rif = IF(cliente_ci_rif IS NULL OR CHAR_LENGTH(TRIM(cliente_ci_rif)) = 0, '{$ci_rif}', cliente_ci_rif),
            cliente_nombre = IF(cliente_nombre IS NULL OR CHAR_LENGTH(TRIM(cliente_nombre)) = 0, '{$nombre}', cliente_nombre),
            cliente_direccion = IF(cliente_direccion IS NULL OR CHAR_LENGTH(TRIM(cliente_direccion)) = 0, '{$direccion}', cliente_direccion),
            cliente_telefono = IF(cliente_telefono IS NULL OR CHAR_LENGTH(TRIM(cliente_telefono)) = 0, '{$telefono}', cliente_telefono),
            igtf_alicuota = 3
        WHERE id = {$id}
        LIMIT 1
    ";

    mysqli_query($link, $update) or die(mysqli_error($link));
    $salidasActualizadas++;
}

// 2) Congelar datos del artículo en entradas_salidas
// 2) Congelar datos del artículo en entradas_salidas
$sql = "
    SELECT
        e.id,
        IFNULL(a.codigo, '') AS articulo_codigo,
        LTRIM(RTRIM(CONCAT(
            IFNULL(a.nombre_comercial, ''),
            ' ',
            IFNULL(a.principio_activo, ''),
            ' ',
            IFNULL(a.presentacion, '')
        ))) AS articulo_descripcion
    FROM entradas_salidas e
    JOIN salidas s 
        ON s.id = e.id_documento 
       AND CONVERT(s.tipo_documento USING utf8) COLLATE utf8_general_ci =
           CONVERT(e.tipo_documento USING utf8) COLLATE utf8_general_ci
    JOIN articulo a ON a.id = e.articulo
    WHERE CONVERT(e.tipo_documento USING utf8) COLLATE utf8_general_ci =
          CONVERT('{$tipo_documento}' USING utf8) COLLATE utf8_general_ci
      AND CONVERT(s.estatus USING utf8) COLLATE utf8_general_ci =
          CONVERT('PROCESADO' USING utf8) COLLATE utf8_general_ci
      AND (
            e.articulo_codigo IS NULL OR CHAR_LENGTH(TRIM(e.articulo_codigo)) = 0
         OR e.articulo_descripcion IS NULL OR CHAR_LENGTH(TRIM(e.articulo_descripcion)) = 0
      )
    LIMIT {$limite}
";

$rs = mysqli_query($link, $sql) or die(mysqli_error($link));

$detallesActualizados = 0;

while ($row = mysqli_fetch_assoc($rs)) {
    $id = intval($row["id"]);

    $codigo = mysqli_real_escape_string($link, $row["articulo_codigo"]);
    $descripcion = mysqli_real_escape_string($link, $row["articulo_descripcion"]);

    $update = "
        UPDATE entradas_salidas
        SET
            articulo_codigo = '{$codigo}',
            articulo_descripcion = '{$descripcion}'
        WHERE id = {$id}
        LIMIT 1
    ";

    mysqli_query($link, $update) or die(mysqli_error($link));
    $detallesActualizados++;
}

require("include/desconnect.php");

echo "Proceso terminado.<br>";
echo "Salidas actualizadas: {$salidasActualizadas}<br>";
echo "Detalles actualizados: {$detallesActualizados}<br>";
?>