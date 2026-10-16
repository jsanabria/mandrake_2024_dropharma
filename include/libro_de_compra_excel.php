<?php
// Script llamado por listado_master_buscar_excel.php para tax_libro_compra

$filename = "LIBRO_DE_COMPRAS_" . date('Ymd_His') . ".xls";

$where_tipo = "";
$where_tipo2 = "";
if (!empty($tipo)) {
    $where_tipo  = " AND a.documento = '$tipo'";
    $where_tipo2 = " AND a.tipo_documento = '$tipo'";
}

$where = "";
if (trim($proveedor) != "") {
    $where .= " AND a.proveedor = '$proveedor'";
}

$sql = "SELECT 
            a.fecha AS fecfac, 
            date_format(a.fecha, '%d/%m/%Y') AS fecha, 
            IF(a.documento = 'NC', '', IF(a.documento = 'ND', CONCAT('ND-', a.nro_documento), a.nro_documento)) AS nro_documento, 
            IF(a.documento = 'NC', a.nro_documento, '') AS nota_credito, 
            a.doc_afectado AS doc_afectado, 
            a.nro_control,  
            b.nombre AS proveedor, 
            b.ci_rif, 
            IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.monto_total) AS monto_total, 
            IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.iva) AS iva, 
            IF(a.documento = 'NC', -1, 1) * IF(a.estatus = 'ANULADO', 0, a.total) AS total, 
            IF(a.documento = 'NC', -1, 1) * (SELECT 
                SUM(IF(IFNULL(alicuota, 0)=0, costo, 0)) AS exenta 
            FROM entradas_salidas 
            WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS exenta, 
            IF(a.documento = 'NC', -1, 1) * (SELECT 
                SUM(IF(IFNULL(alicuota, 0)=0, 0, costo)) AS gravable 
            FROM entradas_salidas 
            WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS gravable, 
            IF(a.documento = 'NC', -1, 1) * (SELECT 
                MAX(alicuota) AS alicuota_iva 
            FROM entradas_salidas 
            WHERE id_documento = a.id AND tipo_documento = a.tipo_documento) AS alicuota_iva, 
            a.estatus, ret_iva, ret_islr 
        FROM 
            entradas AS a 
            LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
        WHERE 
            a.tipo_documento = 'TDCFCC' AND 
            a.fecha_libro_compra BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' AND a.estatus = 'PROCESADO' $where_tipo $where 
        UNION ALL   
        SELECT 
            a.fecha AS fecfac, 
            DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
            IF(a.tipo_documento = 'NC', '', IF(a.tipo_documento = 'ND', CONCAT('ND-', a.documento), a.documento)) AS nro_documento, 
            IF(a.tipo_documento = 'NC', a.documento, '') AS nota_credito, 
            a.doc_afectado AS doc_afectado, 
            a.nro_control,  
            b.nombre AS proveedor, 
            b.ci_rif, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.monto_iva AS iva, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.monto_total AS total, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.monto_exento AS exenta, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.monto_gravado AS gravable, 
            a.alicuota AS alicuota_iva, '' AS estatus, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.ret_iva, 
            IF(a.tipo_documento = 'NC', -1, 1) * a.ret_islr   
        FROM 
            compra AS a
            LEFT OUTER JOIN proveedor AS b ON b.id = a.proveedor 
        WHERE 
            a.fecha_registro BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59' $where_tipo2 $where 
        ORDER BY fecfac, nro_documento;"; 

$rs = mysqli_query($link, $sql);

$developer_records = [];

if ($rs) {
    while ($row = mysqli_fetch_assoc($rs)) {
        $estatus    = trim($row["estatus"] ?? '');
        $es_anulado = ($estatus == "ANULADO");

        $developer_records[] = [
            'FECHA'                 => trim($row["fecha"] ?? ''),
            'FACTURA'               => trim($row["nro_documento"] ?? ''),
            'NOTA CREDITO'          => trim($row["nota_credito"] ?? ''),
            'DOC AFECTADO'          => trim($row["doc_afectado"] ?? ''),
            'NRO CONTROL'           => trim($row["nro_control"] ?? ''),
            'NOMBRE O RAZON SOCIAL' => $es_anulado ? "ANULADA" : trim($row["proveedor"] ?? ''),
            'RIF NRO'               => $es_anulado ? "" : trim($row["ci_rif"] ?? ''),
            'TOTAL COMPRAS'         => $es_anulado ? 0 : (float)($row["total"] ?? 0),
            'COMPRAS EXENTAS'       => $es_anulado ? 0 : (float)($row["exenta"] ?? 0),
            'BASE'                  => $es_anulado ? 0 : (float)($row["gravable"] ?? 0),
            'ALICUOTA (%)'          => (float)($row["alicuota_iva"] ?? 0),
            'IMPUESTO'              => $es_anulado ? 0 : (float)($row["iva"] ?? 0),
            'IVA RET.'              => (float)($row["ret_iva"] ?? 0),
            'ISLR RET.'             => (float)($row["ret_islr"] ?? 0)
        ];
    }
}
?>