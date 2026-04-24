<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$host = "localhost";
$user = "dropharm_drake";
$password = "Tomj@vas001";
$data = "dropharm_mandrake";

$link = mysqli_connect($host, $user, $password, $data);
if (!$link) {
    die("Error de conexión MySQL: " . mysqli_connect_error() . PHP_EOL);
}

mysqli_set_charset($link, "utf8");
date_default_timezone_set('America/Caracas');

/**
 * Escribe una línea tipo CSV separada por ;
 * Evita warnings por NULL y valida el handle.
 */
function _fputcsv_safe($handle, array $fields, string $delimiter = ";", string $enclosure = '', string $escapeChar = "\\", string $recordSeparator = "\r\n"): int|false
{
    if (!is_resource($handle)) {
        return false;
    }

    $result = [];
    foreach ($fields as $field) {
        $field = $field ?? '';
        $field = (string)$field;
        if ($enclosure !== '') {
            $field = str_replace($enclosure, $escapeChar . $enclosure, $field);
        }
        $result[] = $enclosure . $field . $enclosure;
    }

    return fwrite($handle, implode($delimiter, $result) . $recordSeparator);
}

/**
 * Crea carpeta si no existe.
 */
function ensureDirectory(string $path): void
{
    if (is_dir($path)) {
        return;
    }

    if (!mkdir($path, 0775, true) && !is_dir($path)) {
        die("No se pudo crear la carpeta: {$path}" . PHP_EOL);
    }
}

/**
 * Abre archivo para escritura, aborta si falla.
 */
function openFileOrFail(string $filename)
{
    $handle = fopen($filename, 'w');
    if ($handle === false) {
        die("No se pudo abrir el archivo para escritura: {$filename}" . PHP_EOL);
    }
    return $handle;
}

/**
 * Ejecuta query y aborta si falla.
 */
function queryOrFail(mysqli $link, string $sql)
{
    $rs = mysqli_query($link, $sql);
    if ($rs === false) {
        die("Error SQL: " . mysqli_error($link) . PHP_EOL . "Consulta: " . $sql . PHP_EOL);
    }
    return $rs;
}

/**
 * Escapa valor string para SQL.
 */
function sqlValue(mysqli $link, $value): string
{
    return "'" . mysqli_real_escape_string($link, (string)$value) . "'";
}


$basePath = "/home2/dropharm/dropharmadm/ftpexportar/";
$clientesPath = $basePath . "clientes/";

ensureDirectory($basePath);
ensureDirectory($clientesPath);

/*
|--------------------------------------------------------------------------
| 1) Exportar inventario.txt
|--------------------------------------------------------------------------
*/
$inventarioFile = $basePath . "inventario.txt";
$f = openFileOrFail($inventarioFile);

$tasa = 1.0;
$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 1";
$rs = queryOrFail($link, $sql);
if ($row = mysqli_fetch_assoc($rs)) {
    $tasa = (float)$row["tasa"];
}

$sql = "SELECT 
            a.id AS codigo,
            CONCAT(IFNULL(a.principio_activo, ''), IFNULL(a.presentacion, '')) AS descripcion,
            'MED' AS grupo,
            'MEDICINA' AS grupo_des,
            a.codigo_de_barra,
            (c.precio * $tasa) AS precio,
            a.cantidad_en_mano AS existencia1,
            0 AS existencia2,
            0 AS existencia3,
            0 AS existencia4,
            ((c.precio * $tasa) - ((c.precio * $tasa) * (a.descuento / 100))) AS oferta,
            'NO' AS regulado,
            (SELECT alicuota FROM alicuota WHERE codigo = a.alicuota AND activo = 'S') AS impuesto,
            0 AS lote,
            0 AS preempaque,
            0 AS oferta_preempaque,
            a.fabricante,
            b.nombre AS fabricante_des,
            ROUND(c.precio, 2) AS precio_usd
        FROM articulo AS a
        LEFT JOIN fabricante AS b
            ON b.Id = a.fabricante
        INNER JOIN tarifa_articulo AS c
            ON c.articulo = a.id AND c.tarifa = 2
        WHERE a.activo = 'S'
          AND a.cantidad_en_mano > 0
          AND a.lista_pedido NOT IN ('PED001', 'PED005')
          AND b.activo = 'S'";

$rs = queryOrFail($link, $sql);
while ($row = mysqli_fetch_assoc($rs)) {
    $lineData = [
        $row['codigo'],
        $row['descripcion'],
        $row['grupo'],
        $row['grupo_des'],
        $row['codigo_de_barra'],
        $row['precio'],
        $row['existencia1'],
        $row['existencia2'],
        $row['existencia3'],
        $row['existencia4'],
        $row['oferta'],
        $row['regulado'],
        $row['impuesto'],
        $row['lote'],
        $row['preempaque'],
        $row['oferta_preempaque'],
        $row['fabricante'],
        $row['fabricante_des'],
        $row['precio_usd']
    ];

    if (_fputcsv_safe($f, $lineData) === false) {
        die("No se pudo escribir en {$inventarioFile}" . PHP_EOL);
    }
}
fclose($f);

/*
|--------------------------------------------------------------------------
| 2) Exportar facturas recientes por cliente
|--------------------------------------------------------------------------
*/
$sql = "SELECT 
            a.id,
            a.cliente,
            REPLACE(a.nro_documento, 'FACT-', '') AS nro_documento,
            a.fecha,
            0.00 AS tot1,
            0.00 AS tot2,
            a.monto_total,
            a.alicuota_iva,
            a.iva,
            a.total
        FROM salidas AS a
        WHERE a.tipo_documento = 'TDCFCV'
          AND a.estatus = 'PROCESADO'
          AND a.fecha BETWEEN DATE_ADD(CURDATE(), INTERVAL -10 DAY) AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)
        ORDER BY a.id DESC";

$rs = queryOrFail($link, $sql);

while ($row = mysqli_fetch_assoc($rs)) {
    $sql3 = "SELECT factura 
             FROM ftp_fact_pedi_procesado 
             WHERE factura = " . sqlValue($link, $row["id"]);
    $rs3 = queryOrFail($link, $sql3);

    if (!mysqli_fetch_assoc($rs3)) {
        $clienteDir = $clientesPath . $row["cliente"] . "/";
        ensureDirectory($clienteDir);

        $file = $row["nro_documento"] . ".TXT";
        $filename = $clienteDir . $file;
        $f = openFileOrFail($filename);

        $sql2 = "SELECT 
                    REPLACE(a.nro_documento, 'FACT-', '') AS nro_documento,
                    ABS(b.cantidad_movimiento) AS cantidad,
                    c.codigo_de_barra,
                    CONCAT(IFNULL(c.principio_activo, ''), ' ', IFNULL(c.presentacion, '')) AS articulo,
                    b.precio_unidad_sin_desc,
                    (b.precio_unidad_sin_desc - b.precio_unidad) AS descuento1,
                    0 AS descuento2,
                    0 AS descuento3,
                    b.precio_unidad,
                    b.alicuota,
                    b.precio,
                    0 AS regulado,
                    c.id
                FROM salidas AS a
                JOIN entradas_salidas AS b
                    ON b.tipo_documento = a.tipo_documento
                   AND b.id_documento = a.id
                LEFT JOIN articulo AS c
                    ON c.id = b.articulo
                WHERE a.tipo_documento = 'TDCFCV'
                  AND a.id = " . (int)$row["id"] . "
                  AND a.estatus = 'PROCESADO'
                ORDER BY a.cliente, b.id_documento";

        $rs2 = queryOrFail($link, $sql2);

        while ($row2 = mysqli_fetch_assoc($rs2)) {
            $lineData = [
                "D",
                $row2['nro_documento'],
                $row2['cantidad'],
                $row2['codigo_de_barra'],
                $row2['articulo'],
                $row2['precio_unidad_sin_desc'],
                $row2['descuento1'],
                $row2['descuento2'],
                $row2['descuento3'],
                $row2['precio_unidad'],
                $row2['alicuota'],
                $row2['precio'],
                $row2['regulado'],
                $row2['id']
            ];

            if (_fputcsv_safe($f, $lineData) === false) {
                fclose($f);
                die("No se pudo escribir detalle en {$filename}" . PHP_EOL);
            }
        }

        $lineData = [
            "S",
            $row['tot1'],
            $row['tot2'],
            $row['monto_total'],
            $row['alicuota_iva'],
            $row['iva'],
            $row['total']
        ];

        if (_fputcsv_safe($f, $lineData) === false) {
            fclose($f);
            die("No se pudo escribir resumen en {$filename}" . PHP_EOL);
        }

        fclose($f);

        $sqlInsert = "INSERT INTO ftp_fact_pedi_procesado
                        (id, factura, pedido, fecha_hora)
                      VALUES
                        (NULL, " . sqlValue($link, $row["id"]) . ", '', NOW())";
        queryOrFail($link, $sqlInsert);
    }
}

echo "Proceso finalizado correctamente." . PHP_EOL;