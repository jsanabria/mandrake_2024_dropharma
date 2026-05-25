<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = '';
    if ($tipo !== "") {
        // Sanitizamos para admitir únicamente números y comas (evitar inyección SQL básica)
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.articulo IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada de alta densidad (22 columnas)
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                g.nombre AS cliente, 
                (SELECT nombre FROM usuario WHERE username = a.asesor_asignado LIMIT 0, 1) AS asesor, 
                (SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'CIUDAD' AND campo_codigo = g.ciudad) AS ciudad,  
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                b.lote, b.fecha_vencimiento, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                b.precio_unidad, 
                b.precio AS total_articulo, 
                a.monto_total, a.iva, a.total,  
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo,
                a.unidades, 
                (SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = a.id_documento_padre) AS DOC_NE  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                (
                    (a.tipo_documento = 'TDCFCV' AND a.estatus = 'PROCESADO' AND a.documento = 'FC') 
                    OR (a.tipo_documento = 'TDCASA' AND a.estatus = 'PROCESADO' AND a.factura = 'S')
                )
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

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
    $filename = "SALIDAS_ARTICULO_DETALLADO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 4. Imprimir la primera fila con los títulos de las columnas planos para Excel
    echo "ID\tDOCUMENTO\tFECHA\tCLIENTE\tASESOR\tCIUDAD\tCODIGO ART.\tNOMBRE COMERCIAL\tPRINCIPIO ACTIVO\tPRESENTACION\tFABRICANTE\tLOTE\tVENCIMIENTO\tCANTIDAD\tPRECIO UNIDAD\tTOTAL ARTICULO\tTOTAL SIN IVA\tIVA\tTOTAL FACTURA\tTIPO DOC\tUNIDADES FACTURA\tDOC NE\n";

    // 5. Volcado e impresión sanitizada de los datos
    foreach ($developer_records as $row) {
        // Asignaciones seguras con operador de fusión de nulos (PHP 8.1+)
        $id            = trim($row["id"] ?? '');
        $nro_documento = trim($row["nro_documento"] ?? '');
        $fecha         = trim($row["fecha"] ?? '');
        $cliente       = trim($row["cliente"] ?? '');
        $asesor        = trim($row["asesor"] ?? '');
        $ciudad        = trim($row["ciudad"] ?? '');
        $codart        = trim($row["codart"] ?? '');
        $n_comercial   = trim($row["nombre_comercial"] ?? '');
        $p_activo      = trim($row["principio_activo"] ?? '');
        $presentacion  = trim($row["presentacion"] ?? '');
        $fabricante    = trim($row["fabricante"] ?? '');
        $lote          = trim($row["lote"] ?? '');
        $fec_venc      = trim($row["fecha_vencimiento"] ?? '');
        $tipo_doc      = trim($row["tipo"] ?? '');
        $doc_ne        = trim($row["DOC_NE"] ?? '');

        // Conversión segura de tipos numéricos
        $cantidad      = $row["cantidad_movimiento"] ?? 0;
        $precio_unid   = $row["precio_unidad"] ?? 0;
        $tot_art       = $row["total_articulo"] ?? 0;
        $monto_tot     = $row["monto_total"] ?? 0;
        $iva           = $row["iva"] ?? 0;
        $total_fac     = $row["total"] ?? 0;
        $unidades_fac  = $row["unidades"] ?? 0;

        // Sanitización para evitar que saltos de línea, tabuladores o comillas rompan las celdas del Excel plano
        $cliente       = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente);
        $asesor        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $asesor);
        $ciudad        = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $ciudad);
        $n_comercial   = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $n_comercial);
        $p_activo      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $p_activo);
        $presentacion  = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $presentacion);
        $fabricante    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $fabricante);
        $lote          = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $lote);
        $tipo_doc      = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $tipo_doc);

        // Imprimir fila formateada por delimitador de tabulaciones (\t)
        echo "{$id}\t{$nro_documento}\t{$fecha}\t{$cliente}\t{$asesor}\t{$ciudad}\t{$codart}\t{$n_comercial}\t{$p_activo}\t{$presentacion}\t{$fabricante}\t{$lote}\t{$fec_venc}\t{$cantidad}\t{$precio_unid}\t{$tot_art}\t{$monto_tot}\t{$iva}\t{$total_fac}\t{$tipo_doc}\t{$unidades_fac}\t{$doc_ne}\n";
    }

    exit();
?>