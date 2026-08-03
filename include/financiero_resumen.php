<?php
/**
 * Resumen financiero para listado_master_buscar.php.
 *
 * Variables recibidas desde el controlador:
 *   $fecha_desde, $fecha_hasta, $tipo y $out.
 */

$fecha_desde = trim((string)($fecha_desde ?? ''));
$fecha_hasta = trim((string)($fecha_hasta ?? ''));
$tipo = strtoupper(trim((string)($tipo ?? '')));

$tiposPermitidos = ['', 'COMPRAS', 'VENTAS', 'GIROS'];
if (!in_array($tipo, $tiposPermitidos, true)) {
    $tipo = '';
}

$fechaValida = static function (string $fecha): bool {
    $obj = DateTime::createFromFormat('Y-m-d', $fecha);
    return $obj && $obj->format('Y-m-d') === $fecha;
};

if (!$fechaValida($fecha_desde) || !$fechaValida($fecha_hasta)) {
    $out .= '<div class="alert alert-warning">El rango de fechas recibido no es válido.</div>';
    return;
}

if ($fecha_desde > $fecha_hasta) {
    $out .= '<div class="alert alert-warning">La fecha desde no puede ser mayor que la fecha hasta.</div>';
    return;
}

/**
 * Ejecuta una consulta SELECT aprovechando la conexión disponible en connect.php.
 * Admite PHPMaker ExecuteRows(), mysqli o PDO.
 */
$finEjecutarConsulta = static function (string $sql): array {
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
                throw new RuntimeException($error[2] ?? 'No fue posible ejecutar la consulta.');
            }
            return $resultado->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    throw new RuntimeException('No se encontró una conexión compatible en include/connect.php.');
};

$finTabla = static function (string $titulo, array $columnas, array $filas): string {
    $html = '<div class="card shadow-sm border-0 mb-4">';
    $html .= '<div class="card-header bg-light fw-semibold">' . htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') . '</div>';
    $html .= '<div class="card-body p-0">';

    if (!$filas) {
        $html .= '<div class="alert alert-info m-3 mb-3">No se encontraron movimientos para el período consultado.</div>';
        $html .= '</div></div>';
        return $html;
    }

    $html .= '<div class="table-responsive"><table class="table table-striped table-hover table-bordered align-middle mb-0">';
    $html .= '<thead class="table-dark"><tr>';
    foreach ($columnas as $campo => $caption) {
        $html .= '<th>' . htmlspecialchars($caption, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $html .= '</tr></thead><tbody>';

    foreach ($filas as $fila) {
        $html .= '<tr>';
        foreach ($columnas as $campo => $caption) {
            $valor = $fila[$campo] ?? '';
            $alineacion = in_array($campo, ['UNIDADES', 'COSTO', 'COSTO_USD', 'PRECIO', 'PRECIO_USD'], true)
                ? ' class="text-end"'
                : '';
            $html .= '<td' . $alineacion . '>' . htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8') . '</td>';
        }
        $html .= '</tr>';
    }

    $html .= '</tbody></table></div></div></div>';
    return $html;
};

$desdeSql = $fecha_desde . ' 00:00:00';
$hastaSql = $fecha_hasta . ' 23:59:59';

$reportes = [
    'COMPRAS' => [
        'titulo' => 'Total Compras',
        'columnas' => [
            'TIPO' => 'TIPO',
            'MONEDA' => 'MONEDA',
            'UNIDADES' => 'UNIDADES',
            'COSTO' => 'COSTO'
        ],
        'sql' => "SELECT
                    'COMPRAS' AS TIPO,
                    b.moneda AS MONEDA,
                    FORMAT(SUM(ABS(a.cantidad_movimiento)), 0) AS UNIDADES,
                    FORMAT(SUM(a.costo), 2) AS COSTO
                FROM entradas_salidas AS a
                JOIN entradas AS b
                  ON b.id = a.id_documento
                 AND b.tipo_documento = a.tipo_documento
                JOIN proveedor AS c ON c.id = b.proveedor
                WHERE a.tipo_documento = 'TDCNRP'
                  AND b.estatus = 'PROCESADO'
                  AND b.fecha BETWEEN '{$desdeSql}' AND '{$hastaSql}'
                GROUP BY b.moneda"
    ],
    'VENTAS' => [
        'titulo' => 'Total Ventas',
        'columnas' => [
            'TIPO' => 'TIPO',
            'MONEDA' => 'MONEDA',
            'UNIDADES' => 'UNIDADES',
            'COSTO_USD' => 'COSTO USD',
            'PRECIO' => 'PRECIO',
            'PRECIO_USD' => 'PRECIO USD'
        ],
        'sql' => "SELECT
                    'VENTAS' AS TIPO,
                    b.moneda AS MONEDA,
                    FORMAT(SUM(ABS(a.cantidad_movimiento)), 0) AS UNIDADES,
                    FORMAT(SUM(a.costo), 2) AS COSTO_USD,
                    FORMAT(SUM(a.precio), 2) AS PRECIO,
                    FORMAT(SUM(a.precio / NULLIF(b.tasa_dia, 0)), 2) AS PRECIO_USD
                FROM entradas_salidas AS a
                JOIN salidas AS b
                  ON b.id = a.id_documento
                 AND b.tipo_documento = a.tipo_documento
                JOIN cliente AS c ON c.id = b.cliente
                WHERE a.tipo_documento = 'TDCFCV'
                  AND b.estatus = 'PROCESADO'
                  AND b.fecha BETWEEN '{$desdeSql}' AND '{$hastaSql}'
                GROUP BY b.moneda"
    ],
    'GIROS' => [
        'titulo' => 'Total Giros',
        'columnas' => [
            'TIPO' => 'TIPO',
            'MONEDA' => 'MONEDA',
            'UNIDADES' => 'UNIDADES',
            'COSTO_USD' => 'COSTO USD',
            'PRECIO' => 'PRECIO',
            'PRECIO_USD' => 'PRECIO USD'
        ],
        'sql' => "SELECT
                    'GIROS' AS TIPO,
                    b.moneda AS MONEDA,
                    FORMAT(SUM(ABS(a.cantidad_movimiento)), 0) AS UNIDADES,
                    FORMAT(SUM(a.costo), 2) AS COSTO_USD,
                    FORMAT(SUM(a.precio), 2) AS PRECIO,
                    FORMAT(SUM(a.precio / NULLIF(IF(b.moneda = 'USD', 1, b.tasa_dia), 0)), 2) AS PRECIO_USD
                FROM entradas_salidas AS a
                JOIN salidas AS b
                  ON b.id = a.id_documento
                 AND b.tipo_documento = a.tipo_documento
                JOIN cliente AS c ON c.id = b.cliente
                WHERE a.tipo_documento = 'TDCNET'
                  AND b.estatus = 'PROCESADO'
                  AND b.fecha BETWEEN '{$desdeSql}' AND '{$hastaSql}'
                  AND IFNULL(b.id_documento_padre, 0) = 0
                GROUP BY b.moneda"
    ]
];

$seleccionados = $tipo === '' ? array_keys($reportes) : [$tipo];

try {
    foreach ($seleccionados as $clave) {
        $reporte = $reportes[$clave];
        $filas = $finEjecutarConsulta($reporte['sql']);
        $out .= $finTabla($reporte['titulo'], $reporte['columnas'], $filas);
    }
} catch (Throwable $e) {
    $out .= '<div class="alert alert-danger">No fue posible generar el resumen financiero: '
        . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        . '</div>';
}