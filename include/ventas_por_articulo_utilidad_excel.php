<?php 
    // 1. Captura y sanitización estricta de parámetros GET por URL
    $fecha_desde = $_GET['fd'] ?? '';[cite: 13]
    $fecha_hasta = $_GET['fh'] ?? '';[cite: 13]
    $tipo        = $_GET['tipo'] ?? '';[cite: 13]

    // Sanitización para prevenir inyección SQL en la cláusula IN
    $where = "";
    if (trim($tipo) != "") {
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = " AND b.articulo IN ($tipo_clean)";
        }
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
                venta_total DESC;";[cite: 13]

    $rs = mysqli_query($link, $sql);[cite: 13]

    if (!$rs) {
        var_dump(mysqli_error($link));[cite: 13]
        die();[cite: 13]
    }

    $developer_records = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Cabeceras HTTP Estables para Forzar la Descarga del Documento
    $filename = "RENTABILIDAD_VENTAS_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");[cite: 13]
    header("Content-Disposition: attachment; filename=\"$filename\"");[cite: 13]
    header("Pragma: no-cache");[cite: 13]
    header("Expires: 0");[cite: 13]

    // Marca UTF-8 BOM para forzar acentos y caracteres especiales limpios
    echo "\xEF\xBB\xBF"; 
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Rentabilidad de Ventas</x:Name>
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
    .porcentaje { mso-number-format:"0\.00%"; text-align: right; }
    .entero { mso-number-format:"0"; text-align: center; }
    th { background-color: #002060; color: #FFFFFF; font-weight: bold; text-align: center; border: 0.5pt solid #000000; }
    td { border: 0.5pt solid #D9D9D9; vertical-align: middle; }
</style>
</head>
<body>
<table border="1">
    <thead>
        <tr>
            <th>CODIGO</th>
            <th>ARTICULO / DESCRIPCION</th>
            <th>CANTIDAD</th>
            <th>COSTO UND</th>
            <th>COSTO TOTAL</th>
            <th>PRECIO UND</th>
            <th>VENTA TOTAL</th>
            <th>UTILIDAD MONTO</th>
            <th>% MARGEN</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $codigo      = trim($row["codigo"] ?? '-');[cite: 13]
        $articulo    = trim($row["articulo"] ?? 'Sin Detalle');[cite: 13]
        
        // Conversiones numéricas puras para Excel
        $cant_mov    = (float)($row["cantidad_movimiento"] ?? 0);[cite: 13]
        $costo_und   = (float)($row["costo_unidad"] ?? 0);[cite: 13]
        $costo_tot   = (float)($row["costo_total"] ?? 0);[cite: 13]
        $precio_und  = (float)($row["precio_unidad"] ?? 0);[cite: 13]
        $venta_tot   = (float)($row["venta_total"] ?? 0);[cite: 13]

        // Operaciones Financieras Libres de Divisiones por Cero
        $utilidad_monto = $venta_tot - $costo_tot;[cite: 13]
        $porc_utilidad  = ($venta_tot > 0) ? ($utilidad_monto / $venta_tot) : 0; // Valor decimal para la clase .porcentaje

        echo "<tr>";
        echo "<td class='texto'>{$codigo}</td>";
        echo "<td class='texto'>" . htmlspecialchars($articulo, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='entero'>{$cant_mov}</td>";
        echo "<td class='num'>{$costo_und}</td>";
        echo "<td class='num'>{$costo_tot}</td>";
        echo "<td class='num'>{$precio_und}</td>";
        echo "<td class='num'>{$venta_tot}</td>";
        echo "<td class='num'>{$utilidad_monto}</td>";
        echo "<td class='porcentaje'>{$porc_utilidad}</td>";
        echo "</tr>";
    }
?>
    </tbody>
</table>
</body>
</html>
<?php
    exit();[cite: 13]
?>