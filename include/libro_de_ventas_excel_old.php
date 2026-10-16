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
                    SUM(IF(IFNULL(alicuota,0)=0, 0, (precio * (1 - (IFNULL(a.descuento, 0)/100))) * (1 - (IFNULL(a.descuento2, 0)/100)))) AS gravado_2 
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id), 2) AS base,
                ROUND((SELECT
                    SUM(IF(IFNULL(alicuota,0)=0, 0, (precio * (1 - (IFNULL(a.descuento, 0)/100))) * (1 - (IFNULL(a.descuento2, 0)/100)))) AS gravado_2 
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id), 2) AS base,
                (SELECT
                    alicuota  
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 
                a.`iva` AS iva, 
                0 AS iva_ret, 'N' AS orden, a.asesor_asignado AS asesor, a.descuento, a.descuento2, a.tipo_documento, a.estatus,  
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

// 4. Cabeceras HTTP
    $filename = "LIBRO_VENTAS_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Cache-Control: max-age=0");
    header("Pragma: no-cache");

    echo "\xEF\xBB\xBF"; // UTF-8 BOM
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Libro de Ventas</x:Name>
    <x:WorksheetOptions>
     <x:DisplayGridlines/>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
<style>
    .texto { mso-number-format:"\@"; }
    .num { mso-number-format:"0\.00"; text-align: right; }
    .entero { mso-number-format:"0"; text-align: center; }
    th { background-color: #002060; color: #FFFFFF; font-weight: bold; text-align: center; border: 0.5pt solid #000000; }
    td { border: 0.5pt solid #D9D9D9; vertical-align: middle; }
</style>
</head>
<body>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>FECHA</th>
            <th>RIF</th>
            <th>RAZON SOCIAL</th>
            <th>COMPROBANTE</th>
            <th>NRO FACTURA</th>
            <th>NRO CONTROL</th>
            <th>NOTA DEBITO</th>
            <th>NOTA CREDITO</th>
            <th>DOC AFECTADO</th>
            <th>TOTAL VENTAS</th>
            <th>TOTAL EXENTAS</th>
            <th>TOTAL BASE</th>
            <th>ALICUOTA</th>
            <th>IVA</th>
            <th>IVA RET</th>
            <th>ASESOR</th>
            <th>DIAS CREDITO</th>
            <th>FECHA VENCIMIENTO</th>
        </tr>
    </thead>
    <tbody>
<?php
    $i = 1;
    foreach ($developer_records as $row) {
        $estatus    = trim($row["estatus"] ?? '');
        $es_anulado = ($estatus == "ANULADO");

        $fecha        = trim($row["fecha"] ?? '');
        $rif          = $es_anulado ? "" : trim($row["ci_rif"] ?? '');
        $cliente_name = $es_anulado ? "ANULADA" : trim($row["cliente"] ?? '');
        $comprobante  = trim($row["comprobante"] ?? '');
        $factura      = trim($row["nro_factura"] ?? '');
        $nro_control  = trim($row["nro_control"] ?? '');
        $nd           = trim($row["nota_debito"] ?? '');
        $nc           = trim($row["nota_credito"] ?? '');
        $afectado     = trim($row["afectado"] ?? '');
        $asesor_name  = trim($row["asesor"] ?? '');
        $dias_cred    = trim($row["dias_credito"] ?? '0');
        $fec_venc     = trim($row["fec_venc"] ?? '');

        $factor = (trim($row["documento"] ?? '') == "NC") ? -1 : 1;

        // Se pasan como números puros de base de datos (con punto decimal)
        $total    = $es_anulado ? 0 : (float)(($row["total_ventas"] ?? 0) * $factor);
        $exentas  = $es_anulado ? 0 : (float)(($row["no_gravadas"] ?? 0) * $factor);
        $base     = $es_anulado ? 0 : (float)(($row["base"] ?? 0) * $factor);
        $alicuota = (float)($row["alic"] ?? 0);
        $iva      = $es_anulado ? 0 : (float)($row["iva"] ?? 0);
        $iva_ret  = (float)($row["iva_ret"] ?? 0);

        echo "<tr>";
        echo "<td class='entero'>{$i}</td>";
        echo "<td class='texto'>{$fecha}</td>";
        echo "<td class='texto'>{$rif}</td>";
        echo "<td class='texto'>" . htmlspecialchars($cliente_name, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$comprobante}</td>";
        echo "<td class='texto'>{$factura}</td>";
        echo "<td class='texto'>{$nro_control}</td>";
        echo "<td class='texto'>{$nd}</td>";
        echo "<td class='texto'>{$nc}</td>";
        echo "<td class='texto'>{$afectado}</td>";
        
        // mso-number-format:"0\.00" junto con el valor puro le dice a Excel
        // que adapte automáticamente las comas/puntos según el idioma de la PC
        echo "<td class='num'>{$total}</td>";
        echo "<td class='num'>{$exentas}</td>";
        echo "<td class='num'>{$base}</td>";
        echo "<td class='num'>{$alicuota}</td>";
        echo "<td class='num'>{$iva}</td>";
        echo "<td class='num'>{$iva_ret}</td>";
        
        echo "<td class='texto'>" . htmlspecialchars($asesor_name, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='entero'>{$dias_cred}</td>";
        echo "<td class='texto'>{$fec_venc}</td>";
        echo "</tr>";
        $i++;
    }
?>
    </tbody>
</table>
</body>
</html>
<?php
    exit();
?>