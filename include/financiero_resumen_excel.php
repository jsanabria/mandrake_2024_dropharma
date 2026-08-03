<?php
session_start();
include 'include/connect.php';

$fecha_desde = trim((string)($_GET['fd'] ?? ''));
$fecha_hasta = trim((string)($_GET['fh'] ?? ''));
$tipo = strtoupper(trim((string)($_GET['tipo'] ?? '')));

$fechaValida = static function (string $fecha): bool {
    $obj = DateTime::createFromFormat('Y-m-d', $fecha);
    return $obj && $obj->format('Y-m-d') === $fecha;
};

if (!$fechaValida($fecha_desde) || !$fechaValida($fecha_hasta) || $fecha_desde > $fecha_hasta) {
    http_response_code(400);
    exit('Rango de fechas no válido.');
}

$tiposPermitidos = ['', 'COMPRAS', 'VENTAS', 'GIROS'];
if (!in_array($tipo, $tiposPermitidos, true)) {
    $tipo = '';
}

$ejecutar = static function (string $sql): array {
    if (function_exists('ExecuteRows')) {
        $rows = ExecuteRows($sql);
        return is_array($rows) ? $rows : [];
    }

    foreach (['conn', 'link', 'mysqli', 'conexion', 'db'] as $nombre) {
        if (!isset($GLOBALS[$nombre])) {
            continue;
        }

        $conexion = $GLOBALS[$nombre];
        if ($conexion instanceof mysqli) {
            $resultado = $conexion->query($sql);
            if (!$resultado) {
                throw new RuntimeException($conexion->error);
            }
            $rows = [];
            while ($row = $resultado->fetch_assoc()) {
                $rows[] = $row;
            }
            $resultado->free();
            return $rows;
        }

        if ($conexion instanceof PDO) {
            $resultado = $conexion->query($sql);
            if (!$resultado) {
                $error = $conexion->errorInfo();
                throw new RuntimeException($error[2] ?? 'Error al ejecutar la consulta.');
            }
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    throw new RuntimeException('No se encontró una conexión compatible.');
};

$desdeSql = $fecha_desde . ' 00:00:00';
$hastaSql = $fecha_hasta . ' 23:59:59';

$reportes = [
    'COMPRAS' => [
        'columnas' => ['TIPO', 'MONEDA', 'UNIDADES', 'COSTO'],
        'sql' => "SELECT 'COMPRAS' AS TIPO, b.moneda AS MONEDA,
                        FORMAT(SUM(ABS(a.cantidad_movimiento)), 0) AS UNIDADES,
                        FORMAT(SUM(a.costo), 2) AS COSTO
                 FROM entradas_salidas AS a
                 JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento
                 JOIN proveedor AS c ON c.id = b.proveedor
                 WHERE a.tipo_documento = 'TDCNRP'
                   AND b.estatus = 'PROCESADO'
                   AND b.fecha BETWEEN '{$desdeSql}' AND '{$hastaSql}'
                 GROUP BY b.moneda"
    ],
    'VENTAS' => [
        'columnas' => ['TIPO', 'MONEDA', 'UNIDADES', 'COSTO_USD', 'PRECIO', 'PRECIO_USD'],
        'sql' => "SELECT 'VENTAS' AS TIPO, b.moneda AS MONEDA,
                        FORMAT(SUM(ABS(a.cantidad_movimiento)), 0) AS UNIDADES,
                        FORMAT(SUM(a.costo), 2) AS COSTO_USD,
                        FORMAT(SUM(a.precio), 2) AS PRECIO,
                        FORMAT(SUM(a.precio / NULLIF(b.tasa_dia, 0)), 2) AS PRECIO_USD
                 FROM entradas_salidas AS a
                 JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento
                 JOIN cliente AS c ON c.id = b.cliente
                 WHERE a.tipo_documento = 'TDCFCV'
                   AND b.estatus = 'PROCESADO'
                   AND b.fecha BETWEEN '{$desdeSql}' AND '{$hastaSql}'
                 GROUP BY b.moneda"
    ],
    'GIROS' => [
        'columnas' => ['TIPO', 'MONEDA', 'UNIDADES', 'COSTO_USD', 'PRECIO', 'PRECIO_USD'],
        'sql' => "SELECT 'GIROS' AS TIPO, b.moneda AS MONEDA,
                        FORMAT(SUM(ABS(a.cantidad_movimiento)), 0) AS UNIDADES,
                        FORMAT(SUM(a.costo), 2) AS COSTO_USD,
                        FORMAT(SUM(a.precio), 2) AS PRECIO,
                        FORMAT(SUM(a.precio / NULLIF(IF(b.moneda = 'USD', 1, b.tasa_dia), 0)), 2) AS PRECIO_USD
                 FROM entradas_salidas AS a
                 JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento
                 JOIN cliente AS c ON c.id = b.cliente
                 WHERE a.tipo_documento = 'TDCNET'
                   AND b.estatus = 'PROCESADO'
                   AND b.fecha BETWEEN '{$desdeSql}' AND '{$hastaSql}'
                   AND IFNULL(b.id_documento_padre, 0) = 0
                 GROUP BY b.moneda"
    ]
];

$seleccionados = $tipo === '' ? array_keys($reportes) : [$tipo];
$nombre = 'resumen_financiero_' . $fecha_desde . '_al_' . $fecha_hasta . '.xls';

header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $nombre . '"');
header('Cache-Control: max-age=0');

echo "\xEF\xBB\xBF";

try {
    foreach ($seleccionados as $indice => $clave) {
        $reporte = $reportes[$clave];
        $filas = $ejecutar($reporte['sql']);

        if ($indice > 0) {
            echo "\r\n";
        }

        echo $clave . "\r\n";
        echo implode("\t", $reporte['columnas']) . "\r\n";

        foreach ($filas as $fila) {
            $valores = [];
            foreach ($reporte['columnas'] as $campo) {
                $valor = str_replace(["\t", "\r", "\n"], ' ', (string)($fila[$campo] ?? ''));
                $valores[] = $valor;
            }
            echo implode("\t", $valores) . "\r\n";
        }
    }
} catch (Throwable $e) {
    echo 'ERROR' . "\t" . str_replace(["\t", "\r", "\n"], ' ', $e->getMessage()) . "\r\n";
}