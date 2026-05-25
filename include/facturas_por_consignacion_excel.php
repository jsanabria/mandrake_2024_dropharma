<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números y comas en caso de un "IN" de clientes múltiple
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND a.cliente IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada para el reporte detallado
    $sql = "SELECT 
                g.nombre AS cliente, 
                a.nro_documento, 
                d.codigo, 
                CONCAT(IFNULL(d.principio_activo, ' '), ' ', IFNULL(d.presentacion, ' '), ' ', IFNULL(d.nombre_comercial, ' ')) AS articulo, 
                ABS(b.cantidad_movimiento) AS cantidad_entregada, 
                b.precio_unidad, 
                b.precio  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV') 
                AND a.estatus = 'PROCESADO' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                AND a.consignacion = 'S' 
                $where 
            ORDER BY g.nombre, a.nro_documento, c.nombre, d.principio_activo, d.presentacion;"; 

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
    $filename = "FACTURAS_CONSIGNACION_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Imprimir los títulos de las columnas (separados por tabulación \t)
    echo "CLIENTE\tDOCUMENTO\tCODIGO\tARTICULO\tCANTIDAD\tPRECIO\tTOTAL\n";

    // 5. Volcado e impresión sanitizada de los registros
    foreach ($developer_records as $row) {
        // Asignaciones seguras con operador de fusión de nulos (PHP 8.1+)
        $cliente       = trim($row["cliente"] ?? '');
        $nro_documento = trim($row["nro_documento"] ?? '');
        $codigo        = trim($row["codigo"] ?? '');
        $articulo      = trim($row["articulo"] ?? '');

        // Conversiones seguras para enteros y flotantes
        $cantidad  = $row["cantidad_entregada"] ?? 0;
        $precio_u  = $row["precio_unidad"] ?? 0;
        $precio_t  = $row["precio"] ?? 0;

        // Sanitización para evitar desalineación de columnas en Excel plano
        $cliente   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente);
        $articulo  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $articulo);

        // Imprimir fila formateada
        echo "{$cliente}\t{$nro_documento}\t{$codigo}\t{$articulo}\t{$cantidad}\t{$precio_u}\t{$precio_t}\n";
    }

    exit();
?>