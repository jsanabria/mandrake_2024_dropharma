<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    if ($tipo !== "") {
        $where = "AND b.id = '" . mysqli_real_escape_string($link, $tipo) . "'";
    }

    // 2. Consulta SQL optimizada
    $sql = "SELECT
                c.campo_descripcion AS ciudad, b.nombre AS cliente, 
                COUNT(a.nro_documento) AS facturas, 
                SUM(a.monto_total) AS total, SUM(a.unidades) AS unidades 
            FROM 
                salidas AS a 
                LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
                LEFT OUTER JOIN tabla AS c ON c.campo_codigo = b.ciudad AND c.tabla = 'CIUDAD' 
            WHERE 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
                AND a.estatus  = 'PROCESADO' 
                AND a.tipo_documento = 'TDCFCV' 
                AND IFNULL(a.documento, '') = 'FC' 
                $where 
            GROUP BY 
                c.campo_descripcion, b.nombre 
            ORDER BY 4 DESC;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configurar cabeceras HTTP de descarga forzada para Excel (.xls)
    $filename = "VENTAS_CLIENTE_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Imprimir la primera fila con los títulos de las columnas
    echo "CIUDAD\tCLIENTE\tFACTURAS\tTOTAL\tUNIDADES\n";

    // 5. Volcado e impresión sanitizada de los datos
    foreach ($developer_records as $row) {
        // Asignaciones seguras con operador de fusión de nulos (PHP 8.1+)
        $ciudad   = trim($row["ciudad"] ?? '');
        $cliente  = trim($row["cliente"] ?? '');
        $facturas = $row["facturas"] ?? 0;
        $total    = $row["total"] ?? 0;
        $unidades = $row["unidades"] ?? 0;

        // Sanitización para evitar saltos de línea o tabuladores que rompan las columnas del Excel plano
        $ciudad   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $ciudad);
        $cliente  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente);

        // Imprimir fila formateada por delimitador de tabulaciones (\t)
        echo "{$ciudad}\t{$cliente}\t{$facturas}\t{$total}\t{$unidades}\n";
    }

    exit();
?>