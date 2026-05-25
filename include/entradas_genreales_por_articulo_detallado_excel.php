<?php
    // 1. Cabeceras HTTP para forzar descarga limpia sin alertas de formato HTML
    $filename = "ENTRADAS_DETALLADO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 2. Preparar condiciones dinámicas
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.articulo IN (" . $tipo . ")";
    }

    // 3. Consulta de datos
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                g.nombre AS proveedor, 
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                b.lote, 
                b.fecha_vencimiento, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                b.precio_unidad_sin_desc AS costo_ful, 
                b.descuento, 
                b.costo_unidad, 
                b.costo, 
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, 
                a.estatus  
            FROM 
                entradas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN proveedor AS g ON g.id = a.proveedor 
            WHERE 
                (
                    (a.tipo_documento = 'TDCNRP' AND a.estatus IN ('NUEVO', 'PROCESADO')) 
                    OR (a.tipo_documento = 'TDCAEN' AND a.estatus IN ('NUEVO', 'PROCESADO'))
                )
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Encabezados planos de Excel delimitados por tabulaciones (\t)
    echo "TIPO\tDOCUMENTO\tFECHA\tPROVEEDOR\tCODIGO\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tFABRICANTE\tLOTE\tFECHA VENCIMIENTO\tCANTIDAD\tCOSTO FUL\tDESCUENTO\tCOSTO UNIDAD\tCOSTO TOTAL\tESTATUS\n";

    // 5. Volcado iterativo aplicando limpieza rigurosa de saltos de línea y tabuladores en texto
    while ($row = mysqli_fetch_assoc($rs)) {
        
        $tipo        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["tipo"] ?? ''));
        $documento   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nro_documento"] ?? ''));
        $fecha       = trim($row["fecha"] ?? '');
        $proveedor   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["proveedor"] ?? ''));
        $codart      = trim($row["codart"] ?? '');
        $comercial   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nombre_comercial"] ?? ''));
        $principio   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["principio_activo"] ?? ''));
        $presentacio = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["presentacion"] ?? ''));
        $fabricante  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["fabricante"] ?? ''));
        $lote        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["lote"] ?? ''));
        $fec_venc    = trim($row["fecha_vencimiento"] ?? '');
        $estatus     = trim($row["estatus"] ?? '');

        // Valores numéricos limpios extraídos directamente para Excel
        $cantidad    = $row["cantidad_movimiento"] ?? 0;
        $costo_ful   = $row["costo_ful"] ?? 0;
        $descuento   = $row["descuento"] ?? 0;
        $costo_uni   = $row["costo_unidad"] ?? 0;
        $costo_tot   = $row["costo"] ?? 0;

        // Escritura de la línea estructurada
        echo "{$tipo}\t{$documento}\t{$fecha}\t{$proveedor}\t{$codart}\t{$comercial}\t{$principio}\t{$presentacio}\t{$fabricante}\t{$lote}\t{$fec_venc}\t{$cantidad}\t{$costo_ful}\t{$descuento}\t{$costo_uni}\t{$costo_tot}\t{$estatus}\n";
    }

    exit();
?>