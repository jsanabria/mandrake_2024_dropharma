<?php 
    // 1. Recibir y sanitizar parámetros de URL o del ámbito superior
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    $where2 = "";

    // Evitar inyección de SQL básica si los IDs de laboratorios vienen concatenados
    if (!empty($tipo)) {
        // Asumiendo que $tipo es una lista de IDs separados por coma (e.g. "1,2,3")
        // Sanitizamos asegurando que solo contenga números y comas
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.fabricante IN ($tipo_clean)";
            $where2 = "AND a.fabricante IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada
    $sql = "SELECT 
                j.tipo_documento, j.almacen, i.codigo, i.nombre_comercial, 
                i.principio_activo, i.presentacion, i.fabricante, j.unidad_medida, 
                j.cantidad 
            FROM 
                (SELECT 
                    a.id AS articulo, a.codigo, b.nombre AS fabricante, 
                    a.principio_activo, a.presentacion, a.nombre_comercial 
                FROM 
                    articulo AS a 
                    JOIN fabricante AS b ON b.Id = a.fabricante 
                WHERE 
                    0=0 $where2) AS i 
                LEFT OUTER JOIN  
                (SELECT
                    b.tipo_documento, b.articulo, d.codigo, f.descripcion AS almacen, c.nombre AS fabricante, 
                    d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion AS unidad_medida, 
                    ABS(SUM(b.cantidad_movimiento)) AS cantidad  
                FROM 
                    salidas AS a 
                    JOIN entradas_salidas AS b ON b.id_documento = a.id 
                    LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                    LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                    LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
                    LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
                WHERE 
                    a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                    AND a.estatus  = 'PROCESADO' 
                    AND b.tipo_documento IN ('TDCFCV','TDCASA') 
                    AND (IFNULL(a.documento, '') = 'FC' OR (IFNULL(a.documento, '') = '' AND a.factura = 'S'))
                    $where 
                GROUP BY 
                    b.tipo_documento, b.articulo, d.codigo, f.descripcion, c.nombre, 
                    d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion) AS j ON j.articulo = i.articulo 
            ORDER BY i.fabricante, j.almacen DESC, j.cantidad DESC;";

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
    $filename = "SALIDAS_LABORATORIO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Encabezados de las columnas para el reporte Excel
    echo "TIPO DOC./FABRICANTE/LABORATORIO\tALMACEN\tCODIGO\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tUNIDAD MEDIDA\tCANTIDAD\n";

    // 5. Volcado de información con protección nula y desinfección de celdas
    foreach ($developer_records as $row) {
        // Asignación segura con fusión de nulos (PHP 8.1+)
        $tipo_documento       = trim($row["tipo_documento"] ?? '');
        $fabricante       = trim($row["fabricante"] ?? '');
        $almacen          = trim($row["almacen"] ?? '');
        $codigo           = trim($row["codigo"] ?? '');
        $nombre_comercial = trim($row["nombre_comercial"] ?? '');
        $principio_activo = trim($row["principio_activo"] ?? '');
        $presentacion     = trim($row["presentacion"] ?? '');
        $unidad_medida    = trim($row["unidad_medida"] ?? '');
        $cantidad         = $row["cantidad"] ?? 0;

        // Limpiar strings de tabulaciones y saltos de línea que desalineen el Excel plano
        $tipo_documento       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $tipo_documento);
        $fabricante       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $fabricante);
        $almacen          = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $almacen);
        $codigo           = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $codigo);
        $nombre_comercial = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $nombre_comercial);
        $principio_activo = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $principio_activo);
        $presentacion     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $presentacion);
        $unidad_medida    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $unidad_medida);

        // Imprimir fila formateada por delimitador de tabulaciones (\t)
        echo "{$fabricante}\t{$almacen}\t{$codigo}\t{$nombre_comercial}\t{$principio_activo}\t{$presentacion}\t{$unidad_medida}\t{$cantidad}\n";
    }

    exit();
?>