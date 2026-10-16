<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';
    
    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números y comas en caso de un "IN" múltiple
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.articulo IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada
    $sql = "SELECT 
                d.id,
                d.codigo, 
                d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre AS fabricante, 
                SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND a.estatus = 'PROCESADO'  
                AND (IFNULL(a.documento, '') = 'FC' OR (IFNULL(a.documento, '') = '' AND a.factura = 'S')) 
                AND a.activo = 'S' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
            ORDER BY cantidad_movimiento DESC;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configurar cabeceras HTTP para forzar la descarga del Excel (.xls)
    $filename = "SALIDAS_ARTICULO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Imprimir la fila de encabezados planos de columna
    echo "CODIGO\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tFABRICANTE\tCANTIDAD\n";

    // 5. Volcado e impresión sanitizada de los datos
    foreach ($developer_records as $row) {
        // Asignaciones seguras con operador de fusión de nulos (PHP 8.1+)
        $codigo       = trim($row["codigo"] ?? '');
        $n_comercial  = trim($row["nombre_comercial"] ?? '');
        $p_activo     = trim($row["principio_activo"] ?? '');
        $presentacion = trim($row["presentacion"] ?? '');
        $fabricante   = trim($row["fabricante"] ?? '');
        $cantidad     = $row["cantidad_movimiento"] ?? 0;

        // Limpiar strings de caracteres de salto o tabuladores que rompan las celdas de Excel
        $codigo       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $codigo);
        $n_comercial  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $n_comercial);
        $p_activo     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $p_activo);
        $presentacion = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $presentacion);
        $fabricante   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $fabricante);

        // Imprimir fila formateada por tabulaciones (\t)
        echo "{$codigo}\t{$n_comercial}\t{$p_activo}\t{$presentacion}\t{$fabricante}\t{$cantidad}\n";
    }

    exit();
?>