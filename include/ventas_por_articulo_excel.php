<?php 
    // 1. Recibir parámetros por URL en caso de que se limpie el entorno global
    $fecha_desde = $_GET['fd'] ?? '';
    $fecha_hasta = $_GET['fh'] ?? '';
    $tipo        = $_GET['tipo'] ?? '';
    $id          = $_GET['id'] ?? '';

    // Condición dinámica por categoría
    $where = "";
    if (trim($tipo) != "") {
        $where = " AND b.articulo IN ($tipo)";
    }

    // 2. Consulta idéntica a tu estructura original de Excel
    $sql = "SELECT 
                d.id,
                d.codigo, 
                d.nombre_comercial, 
                d.principio_activo, 
                d.presentacion, 
                c.nombre AS fabricante, 
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
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY 
                d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
            ORDER BY 
                cantidad_movimiento DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configuración de Cabeceras de Descarga para Excel
    $filename = "VENTAS_ARTICULO_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Encabezados de Columnas del Excel (Separados por tabulación \t)
    echo "CODIGO\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tLABORATORIO\tCANTIDAD\n";

    // 5. Volcado de registros limpio y compatible con PHP 8
    foreach ($developer_records as $row) {
        $codigo      = trim($row["codigo"] ?? '');
        $comercial   = trim($row["nombre_comercial"] ?? '-');
        $principio   = trim($row["principio_activo"] ?? '-');
        $presentacio = trim($row["presentacion"] ?? '-');
        $fabricante  = trim($row["fabricante"] ?? 'SIN LABORATORIO');
        $cantidad    = intval($row["cantidad_movimiento"] ?? 0);

        // Limpieza de caracteres de salto de línea o tabuladores internos que puedan romper el formato
        $comercial   = str_replace(["\r", "\n", "\t"], " ", $comercial);
        $principio   = str_replace(["\r", "\n", "\t"], " ", $principio);
        $presentacio = str_replace(["\r", "\n", "\t"], " ", $presentacio);
        $fabricante  = str_replace(["\r", "\n", "\t"], " ", $fabricante);

        // Impresión de fila delimitada por tabuladores
        echo "{$codigo}\t{$comercial}\t{$principio}\t{$presentacio}\t{$fabricante}\t{$cantidad}\n";
    }
    exit;
?>