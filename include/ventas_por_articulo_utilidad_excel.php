<?php 
    // 1. Captura y sanitización estricta de parámetros GET por URL
    $fecha_desde = $_GET['fd'] ?? '';
    $fecha_hasta = $_GET['fh'] ?? '';
    $tipo        = $_GET['tipo'] ?? '';

    $where = "";
    if (trim($tipo) != "") {
        $where = " AND b.articulo IN ($tipo)";
    }

    // 2. Consulta idéntica para mantener integridad de datos
    $sql = "SELECT 
                d.id,
                d.codigo AS codigo,
                CONCAT(IFNULL(d.nombre_comercial, ''), ' ', IFNULL(d.principio_activo, ''), ' ', IFNULL(d.presentacion, '')) AS articulo, 
                SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento, 
                ROUND(IFNULL(d.ultimo_costo, 0), 2) AS costo_unidad, 
                ROUND(SUM(ABS(b.cantidad_movimiento) * IFNULL(d.ultimo_costo, 0)), 2) AS costo_total, 
                ROUND(AVG(IFNULL(b.precio, 0) / NULLIF(ABS(b.cantidad_movimiento), 0)), 2) AS precio_unidad, 
                ROUND(SUM(IFNULL(b.precio, 0)), 2) AS venta_total
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV', 'TDCASA') 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC'
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY 
                d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, d.ultimo_costo
            ORDER BY 
                venta_total DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 3. Cabeceras HTTP Estables para Forzar la Descarga del Documento
    $filename = "RENTABILIDAD_VENTAS_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Encabezados de Columnas del Excel (Separados explícitamente por \t)
    echo "CODIGO\tARTICULO / DESCRIPCION\tCANTIDAD\tCOSTO UND\tCOSTO TOTAL\tPRECIO UND\tVENTA TOTAL\tUTILIDAD MONTO\t% MARGEN\n";

    // 5. Procesamiento y Escritura del Stream de Datos
    while ($row = mysqli_fetch_assoc($rs)) {
        $codigo      = trim($row["codigo"] ?? '-');
        $articulo    = trim($row["articulo"] ?? 'Sin Detalle');
        $cant_mov    = floatval($row["cantidad_movimiento"] ?? 0);
        $costo_und   = floatval($row["costo_unidad"] ?? 0);
        $costo_tot   = floatval($row["costo_total"] ?? 0);
        $precio_und  = floatval($row["precio_unidad"] ?? 0);
        $venta_tot   = floatval($row["venta_total"] ?? 0);

        // Operaciones Financieras Libres de Divisiones por Cero
        $utilidad_monto = $venta_tot - $costo_tot;
        $porc_utilidad  = ($venta_tot > 0) ? ($utilidad_monto / $venta_tot) * 100 : 0;

        // Limpieza de strings para evitar que quiebre la grilla de Excel
        $articulo = str_replace(["\r", "\n", "\t"], " ", $articulo);

        // Formateo Numérico Plano con Comas Decimales para Reconocimiento Inmediato de Excel
        $f_cant     = number_format($cant_mov, 0, ",", "");
        $f_c_und    = number_format($costo_und, 2, ",", "");
        $f_c_tot    = number_format($costo_tot, 2, ",", "");
        $f_p_und    = number_format($precio_und, 2, ",", "");
        $f_v_tot    = number_format($venta_tot, 2, ",", "");
        $f_utilidad = number_format($utilidad_monto, 2, ",", "");
        $f_porc     = number_format($porc_utilidad, 2, ",", "") . "%";

        // Impresión en Buffer por Fila con salto de línea estándar \n
        echo "{$codigo}\t{$articulo}\t{$f_cant}\t{$f_c_und}\t{$f_c_tot}\t{$f_p_und}\t{$f_v_tot}\t{$f_utilidad}\t{$f_porc}\n";
    }
    exit;
?>