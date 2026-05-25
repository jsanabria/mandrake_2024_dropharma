<?php
    // 1. Envío de cabeceras HTTP nativas para descarga de Excel sin distorsiones
    $filename = "NOTAS_DE_ENTREGA_DETALLADO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 2. Preparar condiciones dinámicas de categorías
    $where = "";
    if (!empty($tipo)) {
        $where = " AND b.articulo IN (" . $tipo . ")";
    }

    // 3. Consulta optimizada incluyendo lote y fecha de vencimiento solicitados originalmente
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                g.nombre AS cliente, 
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, 
                a.estatus, 
                date_format(b.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
                b.lote 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                (
                    (a.tipo_documento = 'TDCNET' AND a.estatus IN ('NUEVO', 'PROCESADO', 'ANULADO')) 
                )
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    // 4. Encabezados de columnas tabulares planas (\t)
    echo "TIPO\tDOCUMENTO\tFECHA\tCLIENTE\tCODIGO\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tFABRICANTE\tCANTIDAD\tLOTE\tVENCIMIENTO\tESTATUS\n";

    // 5. Volcado veloz línea por línea eliminando arreglos intermedios pesados en memoria
    while ($row = mysqli_fetch_assoc($rs)) {
        
        // Limpieza de caracteres de escape que puedan corromper las celdas en Excel
        $tipo        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["tipo"] ?? ''));
        $documento   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nro_documento"] ?? ''));
        $fecha       = trim($row["fecha"] ?? '');
        $cliente     = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["cliente"] ?? ''));
        $codart      = trim($row["codart"] ?? '');
        $comercial   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["nombre_comercial"] ?? ''));
        $principio   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["principio_activo"] ?? ''));
        $presentacio = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["presentacion"] ?? ''));
        $fabricante  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["fabricante"] ?? ''));
        $lote        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), trim($row["lote"] ?? ''));
        $fec_venc    = trim($row["fecha_vencimiento"] ?? '');
        $estatus     = trim($row["estatus"] ?? '');

        // Cantidad numérica directa
        $cantidad    = $row["cantidad_movimiento"] ?? 0;

        // Impresión limpia del registro estructurado
        echo "{$tipo}\t{$documento}\t{$fecha}\t{$cliente}\t{$codart}\t{$comercial}\t{$principio}\t{$presentacio}\t{$fabricante}\t{$cantidad}\t{$lote}\t{$fec_venc}\t{$estatus}\n";
    }

    exit();
?>