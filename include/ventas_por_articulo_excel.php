<?php 
    // 1. Recibir y sanitizar parámetros por URL
    $fecha_desde = $_GET['fd'] ?? '';[cite: 12]
    $fecha_hasta = $_GET['fh'] ?? '';[cite: 12]
    $tipo        = $_GET['tipo'] ?? '';[cite: 12]
    $id          = $_GET['id'] ?? '';[cite: 12]

    // Condición dinámica por categoría sanitizada para prevenir inyección SQL
    $where = "";
    if (trim($tipo) != "") {
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = " AND b.articulo IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada
    $sql = "SELECT 
                d.id,
                d.codigo, 
                d.nombre_comercial, 
                d.principio_activo, 
                d.presentacion, 
                c.nombre AS fabricante, 
                SUM(ABS(b.cantidad_movimiento)) AS cantidad_movimiento 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND a.estatus = 'PROCESADO'
                AND a.documento = 'FC' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY 
                d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
            ORDER BY 
                cantidad_movimiento DESC;";[cite: 12]

    $rs = mysqli_query($link, $sql);[cite: 12]

    if (!$rs) {
        var_dump(mysqli_error($link));[cite: 12]
        die();[cite: 12]
    }

    $developer_records = array();[cite: 12]
    while ($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;[cite: 12]
    }

    // 3. Configuración de Cabeceras de Descarga para Excel
    $filename = "VENTAS_ARTICULO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");[cite: 12]
    header("Content-Disposition: attachment; filename=\"$filename\"");[cite: 12]
    header("Pragma: no-cache");[cite: 12]
    header("Expires: 0");[cite: 12]

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
    <x:Name>Ventas por Articulo</x:Name>
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
            <th>CODIGO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>PRINCIPIO ACTIVO</th>
            <th>PRESENTACION</th>
            <th>LABORATORIO</th>
            <th>CANTIDAD</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $codigo      = trim($row["codigo"] ?? '');[cite: 12]
        $comercial   = trim($row["nombre_comercial"] ?? '-');[cite: 12]
        $principio   = trim($row["principio_activo"] ?? '-');[cite: 12]
        $presentacio = trim($row["presentacion"] ?? '-');[cite: 12]
        $fabricante  = trim($row["fabricante"] ?? 'SIN LABORATORIO');[cite: 12]
        
        // Conversión a número flotante puro
        $cantidad    = (float)($row["cantidad_movimiento"] ?? 0);[cite: 12]

        echo "<tr>";
        echo "<td class='texto'>{$codigo}</td>";
        echo "<td class='texto'>" . htmlspecialchars($comercial, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($principio, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($presentacio, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($fabricante, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='entero'>{$cantidad}</td>";
        echo "</tr>";
    }
?>
    </tbody>
</table>
</body>
</html>
<?php
    exit();[cite: 12]
?>