<?php
session_start();
require("include/connect2.php");

$tipo_documento = "TDCFCV";
$limite = 500;

// 1) Congelar datos fiscales del cliente en salidas
$sql = "
    SELECT
        s.id,
        c.ci_rif,
        c.nombre,
        CONCAT(IFNULL(c.direccion,''), ' ', IFNULL(t.campo_descripcion,'')) AS direccion,
        CONCAT(REPLACE(IFNULL(c.telefono1,''), ' ', ''), ' ', REPLACE(IFNULL(c.telefono2,''), ' ', '')) AS telefono
    FROM salidas s
    JOIN cliente c ON c.id = s.cliente
    LEFT JOIN tabla t ON t.campo_codigo = c.ciudad AND t.tabla = 'CIUDAD'
    WHERE s.tipo_documento = '{$tipo_documento}'
      AND s.estatus = 'PROCESADO'
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
            cliente_ci_rif = IF(cliente_ci_rif IS NULL OR cliente_ci_rif = '', '{$ci_rif}', cliente_ci_rif),
            cliente_nombre = IF(cliente_nombre IS NULL OR cliente_nombre = '', '{$nombre}', cliente_nombre),
            cliente_direccion = IF(cliente_direccion IS NULL OR cliente_direccion = '', '{$direccion}', cliente_direccion),
            cliente_telefono = IF(cliente_telefono IS NULL OR cliente_telefono = '', '{$telefono}', cliente_telefono), 
            igtf_alicuota = 3 
        WHERE id = {$id}
        LIMIT 1
    ";

    mysqli_query($link, $update) or die(mysqli_error($link));
    $salidasActualizadas++;
}

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
    JOIN salidas s ON s.id = e.id_documento AND s.tipo_documento = e.tipo_documento
    JOIN articulo a ON a.id = e.articulo
    WHERE e.tipo_documento = '{$tipo_documento}'
      AND s.estatus = 'PROCESADO'
      AND (
            e.articulo_codigo IS NULL OR e.articulo_codigo = ''
         OR e.articulo_descripcion IS NULL OR e.articulo_descripcion = ''
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
            articulo_codigo = IF(articulo_codigo IS NULL OR articulo_codigo = '', '{$codigo}', articulo_codigo),
            articulo_descripcion = IF(articulo_descripcion IS NULL OR articulo_descripcion = '', '{$descripcion}', articulo_descripcion)
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