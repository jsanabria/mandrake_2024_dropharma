<?php
    // 1. Cabeceras HTTP para descarga forzada de archivo excel (.xls) plano
    $filename = "COSTO_VS_PRECIO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 2. Control dinámico de parámetros
    $where = "";
    if (!empty($tipo)) {
        $where = "AND c.tarifa = " . intval($tipo);
    }

    // 3. Consulta de base de datos
    $sql = "SELECT
                c.nombre AS fabricante, 
                d.nombre_comercial, 
                d.principio_activo, 
                d.presentacion, 
                g.nombre AS cliente, 
                ABS(b.cantidad_movimiento) AS cantidad, 
                d.ultimo_costo AS costo_unidad, 
                (d.ultimo_costo * abs(b.cantidad_movimiento)) as costo, 
                b.precio_unidad, 
                b.precio, 
                b.precio - (d.ultimo_costo * abs(b.cantidad_movimiento)) AS margen, 
                (((b.precio - (d.ultimo_costo * abs(b.cantidad_movimiento))) / b.precio) * 100) AS porcentaje, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' 
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
                $where 
            ORDER BY a.fecha;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Encabezado plano de columnas de Excel (separados por \t)
    echo "FABRICANTE\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tCLIENTE\tCANTIDAD\tCOSTO UNIDAD\tCOSTO TOTAL\tPRECIO UNIDAD\tPRECIO TOTAL\tMARGEN\tPORCENTAJE\tFECHA\n";

    // 5. Iteración y limpieza estricta de strings
    while($row = mysqli_fetch_assoc($rs)) {
        // Sanitización de textos para evitar desplazamientos de columnas en Excel
        $fabricante = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["fabricante"] ?? ''));
        $comercial  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nombre_comercial"] ?? ''));
        $activo     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["principio_activo"] ?? ''));
        $presenta   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["presentacion"] ?? ''));
        $cliente    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["cliente"] ?? ''));
        $fecha      = trim($row["fecha"] ?? '');

        // Resguardos numéricos puros
        $cantidad     = $row["cantidad"] ?? 0;
        $costo_unidad = $row["costo_unidad"] ?? 0;
        $costo_total  = $row["costo"] ?? 0;
        $precio_unidad= $row["precio_unidad"] ?? 0;
        $precio_total = $row["precio"] ?? 0;
        $margen       = $row["margen"] ?? 0;
        $porcentaje   = $row["porcentaje"] ?? 0;

        // Volcado de la fila con delimitadores de tabulación nativos
        echo "{$fabricante}\t{$comercial}\t{$activo}\t{$presenta}\t{$cliente}\t{$cantidad}\t{$costo_unidad}\t{$costo_total}\t{$precio_unidad}\t{$precio_total}\t{$margen}\t{$porcentaje}\t{$fecha}\n";
    }

    exit();
?>