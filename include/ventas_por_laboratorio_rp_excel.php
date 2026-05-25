<?php
    // 1. Cabeceras HTTP nativas para la generación y descarga directa del archivo Excel (.xls)
    $filename = "VENTAS_LABORATORIO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 2. Preparar condiciones dinámicas de fabricante (laboratorio)
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.fabricante IN (" . $tipo . ")";
    }

    // 3. Consulta idéntica estructurada para la exportación total
    $sql = "SELECT
                f.descripcion AS almacen, 
                d.codigo, 
                d.nombre_comercial, 
                d.principio_activo, 
                d.presentacion, 
                c.nombre AS fabricante, 
                e.descripcion AS unidad_medida, 
                ABS(SUM(b.cantidad_movimiento)) AS cantidad, 
                g.nombre AS asesor_asignado  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
                LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
                LEFT OUTER JOIN usuario AS g ON g.username = a.asesor_asignado 
            WHERE 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                AND a.estatus = 'PROCESADO' 
                AND b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND IFNULL(a.documento, '') = 'FC' 
                $where 
            GROUP BY 
                d.codigo, f.descripcion, c.nombre, d.principio_activo, 
                d.presentacion, d.nombre_comercial, e.descripcion, g.nombre 
            ORDER BY fabricante, cantidad DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Cabeceras tabulares planas limpias
    echo "ALMACEN\tCODIGO\tNOMBRE COMERCIAL\tNOMBRE ARTICULO\tPRESENTACION\tFABRICANTE\tMEDIDA\tCANTIDAD\tASESOR\n";

    // 5. Volcado instantáneo línea por línea a la salida estándar
    while ($row = mysqli_fetch_assoc($rs)) {
        
        // Sanitización rigurosa de strings contra caracteres destructivos de tabulación
        $almacen     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["almacen"] ?? ''));
        $codigo      = trim($row["codigo"] ?? '');
        $comercial   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nombre_comercial"] ?? ''));
        $principio   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["principio_activo"] ?? ''));
        $presentacio = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["presentacion"] ?? ''));
        $fabricante  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["fabricante"] ?? ''));
        $medida      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["unidad_medida"] ?? ''));
        $asesor      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["asesor_asignado"] ?? ''));
        
        // Cantidad calculada en crudo para comportamiento numérico nativo en Excel
        $cantidad    = $row["cantidad"] ?? 0;

        // Escritura directa del registro delimitado por tabuladores
        echo "{$almacen}\t{$codigo}\t{$comercial}\t{$principio}\t{$presentacio}\t{$fabricante}\t{$medida}\t{$cantidad}\t{$asesor}\n";
    }

    exit();
?>