<?php 
    // 1. Recibir parámetros por URL
    $fecha_desde = isset($_GET['fd']) ? $_GET['fd'] : '';
    $fecha_hasta = isset($_GET['fh']) ? $_GET['fh'] : '';
    $tipo        = isset($_GET['tipo']) ? $_GET['tipo'] : '';

    // 2. Preparar condiciones dinámicas
    $where_tipo = "";
    if (!empty($tipo)) {
        $where_tipo = "AND a.documento = '$tipo'";
    }

    // 3. Consulta de datos unificada
    $sql = "SELECT 
                a.fecha AS fecfac, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', a.nro_documento), a.nro_documento)) AS nro_documento, 
                IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
                a.doc_afectado AS doc_afectado, 
                a.nro_control,  
                b.nombre AS proveedor, 
                b.ci_rif, 
                IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.total) AS total, 
                IF(a.documento = 'NC', -1, 1) * (SELECT 
                    SUM(IF(IFNULL(alicuota, 0)=0, costo, 0)) AS exenta 
                FROM entradas_salidas 
                WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta, 
                IF(a.documento = 'NC', -1, 1) * (SELECT 
                    SUM(IF(IFNULL(alicuota, 0)=0, 0, costo)) AS gravable 
                FROM entradas_salidas 
                WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable, 
                (SELECT 
                    MAX(alicuota) AS alicuota_iva 
                FROM entradas_salidas 
                WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva, 
                IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.iva) AS iva, 
                IF(a.documento = 'NC', -1, 1) * a.ret_iva AS ret_iva, 
                IF(a.documento = 'NC', -1, 1) * a.ret_islr AS ret_islr
            FROM 
                entradas AS a 
                LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
            WHERE 
                a.tipo_documento = 'TDCFCC' AND 
                a.fecha_libro_compra BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' AND a.estatus = 'PROCESADO' 
            UNION ALL 	
            SELECT 
                a.fecha AS fecfac, 
                DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
                IF(a.tipo_documento = 'NC', '', IF(a.tipo_documento = 'ND', CONCAT('ND-', a.documento), a.documento)) AS nro_documento, 
                IF(a.tipo_documento = 'NC', a.documento, '')  AS nota_credito, 
                a.doc_afectado AS doc_afectado, 
                a.nro_control,  
                b.nombre AS proveedor, 
                b.ci_rif, 
                IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total, 
                IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS exenta, 
                IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS gravable, 
                a.alicuota AS alicuota_iva, 
                IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS iva, 
                IF(a.tipo_documento = 'NC', -1, 1) * a.ret_iva AS ret_iva, 
                IF(a.tipo_documento = 'NC', -1, 1) * a.ret_islr AS ret_islr   
            FROM 
                compra AS a
                LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
            WHERE 
                a.fecha_registro BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' 
            ORDER BY fecfac, nro_documento;"; 

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 4. Cabeceras HTTP para descarga forzada de archivo excel (.xls)
    $filename = "LIBRO_COMPRAS_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 5. Encabezado de columnas plano de Excel
    echo "FECHA\tFACTURA\tNOTA CREDITO\tDOC AFECTADO\tNRO CONTROL\tRAZON SOCIAL\tRIF\tTOTAL VENTAS\tTOTAL EXENTAS\tBASE GRAVABLE\tALICUOTA\tIMPUESTO IVA\tRETENCION IVA\tRETENCION ISLR\n";

    // 6. Volcado de información protegido contra Null y rupturas de tabulación
    foreach ($developer_records as $row) {
        $fecha        = trim($row["fecha"] ?? '');
        $factura      = trim($row["nro_documento"] ?? '');
        $nc           = trim($row["nota_credito"] ?? '');
        $afectado     = trim($row["doc_afectado"] ?? '');
        $nro_control  = trim($row["nro_control"] ?? '');
        $proveedor    = trim($row["proveedor"] ?? '');
        $rif          = trim($row["ci_rif"] ?? '');
        
        // Asignaciones numéricas puras para evitar el error deprecado en number_format de Excel
        $total        = $row["total"] ?? 0;
        $exenta       = $row["exenta"] ?? 0;
        $gravable     = $row["gravable"] ?? 0;
        $alicuota_iva = $row["alicuota_iva"] ?? 0;
        $iva          = $row["iva"] ?? 0;
        $ret_iva      = $row["ret_iva"] ?? 0;
        $ret_islr     = $row["ret_islr"] ?? 0;

        // Sanitización estricta de textos
        $proveedor    = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $proveedor);

        // Imprimir fila delimitada por tabulación
        echo "{$fecha}\t{$factura}\t{$nc}\t{$afectado}\t{$nro_control}\t{$proveedor}\t{$rif}\t{$total}\t{$exenta}\t{$gravable}\t{$alicuota_iva}\t{$iva}\t{$ret_iva}\t{$ret_islr}\n";
    }

    exit();
?>