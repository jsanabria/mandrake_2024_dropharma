<?php 
    // 1. Recibir parámetros por URL
    $fecha_desde = isset($_GET['fd']) ? $_GET['fd'] : '';
    $fecha_hasta = isset($_GET['fh']) ? $_GET['fh'] : '';
    $tipo        = isset($_GET['tipo']) ? $_GET['tipo'] : '';
    $cliente     = isset($_GET['cliente']) ? $_GET['cliente'] : '';
    $asesor      = isset($_GET['asesor']) ? $_GET['asesor'] : '';

    // 2. Preparar condiciones dinámicas
    $where = "";
    if (!empty($tipo))    $where .= " AND a.documento = '$tipo'";
    if (!empty($cliente)) $where .= " AND a.cliente = '$cliente'";
    if (!empty($asesor))  $where .= " AND a.asesor_asignado = '$asesor'";

    // 3. Consulta de datos
    $sql = "SELECT 
                a.id, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                b.`ci_rif`, 
                b.`nombre` AS cliente, 
                '' AS comprobante, 
                a.`documento`, 
                IF(a.documento = 'FC', REPLACE(a.nro_documento, 'FACT-', ''), '') AS nro_factura, 
                a.`nro_control`, 
                IF(a.documento = 'ND', REPLACE(a.nro_documento, 'ND-', ''), '') AS nota_debito, 
                IF(a.documento = 'NC', REPLACE(a.nro_documento, 'NC-', ''), '') AS nota_credito, 
                REPLACE(a.doc_afectado, 'FACT-', '') AS afectado, 
                a.`total` AS total_ventas, 
                ROUND((SELECT
                    SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * (IFNULL(a.descuento, 0)/100)), 0)) AS exento_2 
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id), 2) AS no_gravadas, 
                ROUND((SELECT
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * (IFNULL(a.descuento, 0)/100)))) AS gravado_2 
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id), 2) AS base, 
                (SELECT
                    alicuota  
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 
                a.`iva` AS iva, 
                0 AS iva_ret, 'N' AS orden, a.asesor_asignado AS asesor, a.descuento, a.tipo_documento, a.estatus,  
                a.dias_credito, date_format(DATE_ADD(a.fecha,INTERVAL IFNULL(a.dias_credito, 0) DAY), '%d/%m/%Y') AS fec_venc   
            FROM 
                salidas AS a 
                LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
            WHERE 
                a.tipo_documento = 'TDCFCV' AND 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' $where 
            /*
            UNION ALL 
            SELECT 
                a.id_documento AS id, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                c.ci_rif, c.nombre AS cliente, 
                a.referencia AS comprobante, 
                a.tipo_pago AS documento, 
                '' AS nro_factura, '' AS nro_control, '' AS nota_debito, '' AS nota_credito, 
                b.nro_documento AS afectado, 0 AS total_ventas, 0 AS no_gravadas, 0 AS base, 0 AS iva, 
                (SELECT
                    alicuota  
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id_documento ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 
                a.monto AS iva_ret, 'S' AS orden, b.asesor_asignado AS asesor, 0 AS descuento, a.tipo_documento, b.estatus,  
                0 AS dias_credito, NULL AS fec_venc 
            FROM 
                pagos AS a 
                JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
                LEFT OUTER JOIN cliente AS c ON c.id = b.cliente 
            WHERE 
                a.tipo_documento = 'TDCFCV' AND 
                a.tipo_pago IN ('RI','RR') AND 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' $where 
            */
            ORDER BY fecha, orden, nro_control;"; 

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
    $filename = "LIBRO_VENTAS_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // 5. Encabezado de columnas plano de Excel
    echo "ID\tFECHA\tRIF\tRAZON SOCIAL\tCOMPROBANTE\tNRO FACTURA\tNRO CONTROL\tNOTA DEBITO\tNOTA CREDITO\tDOC AFECTADO\tTOTAL VENTAS\tTOTAL EXENTAS\tTOTAL BASE\tALICUOTA\tIVA\tIVA RET\tASESOR\tDIAS CREDITO\tFECHA VENCIMIENTO\n";

    // 6. Volcado de información protegido contra Null y rupturas de tabulación
    $i = 1;
    foreach ($developer_records as $row) {
        $estatus    = trim($row["estatus"] ?? '');
        $es_anulado = ($estatus == "ANULADO");

        $fecha       = trim($row["fecha"] ?? '');
        $rif         = $es_anulado ? "" : trim($row["ci_rif"] ?? '');
        $cliente_name = $es_anulado ? "ANULADA" : trim($row["cliente"] ?? '');
        $comprobante = trim($row["comprobante"] ?? '');
        $factura     = trim($row["nro_factura"] ?? '');
        $nro_control = trim($row["nro_control"] ?? '');
        $nd          = trim($row["nota_debito"] ?? '');
        $nc          = trim($row["nota_credito"] ?? '');
        $afectado    = trim($row["afectado"] ?? '');
        $asesor_name = trim($row["asesor"] ?? '');
        $dias_cred   = trim($row["dias_credito"] ?? '0');
        $fec_venc    = trim($row["fec_venc"] ?? '');

        // Cálculos limpios con multiplicador por tipo de documento
        $factor = (trim($row["documento"] ?? '') == "NC") ? -1 : 1;

        $total    = $es_anulado ? 0 : (($row["total_ventas"] ?? 0) * $factor);
        $exentas  = $es_anulado ? 0 : (($row["no_gravadas"] ?? 0) * $factor);
        $base     = $es_anulado ? 0 : (($row["base"] ?? 0) * $factor);
        $alicuota = $row["alic"] ?? 0;
        $iva      = $es_anulado ? 0 : ($row["iva"] ?? 0);
        $iva_ret  = $row["iva_ret"] ?? 0;

        // Sanitización estricta de textos para prevenir saltos imprevistos en las tabulaciones
        $cliente_name = str_replace(array("\n", "\r", "\t", '"'), array(" ", " ", " ", "'"), $cliente_name);

        // Imprimir fila delimitada por tabulaciones
        echo "{$i}\t{$fecha}\t{$rif}\t{$cliente_name}\t{$comprobante}\t{$factura}\t{$nro_control}\t{$nd}\t{$nc}\t{$afectado}\t{$total}\t{$exentas}\t{$base}\t{$alicuota}\t{$iva}\t{$iva_ret}\t{$asesor_name}\t{$dias_cred}\t{$fec_venc}\n";
        $i++;
    }

    exit();
?>