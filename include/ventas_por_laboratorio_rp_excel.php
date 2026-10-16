<?php
    // 1. Inicializar y sanitizar parámetros de búsqueda
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    // 2. Preparar condiciones dinámicas de fabricante (laboratorio) sanitizando $tipo
    $where = "";
    if (!empty($tipo)) {
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = " AND b.fabricante IN (" . $tipo_clean . ")";
        }
    }

    // 3. Consulta idéntica estructurada para la exportación total
    $sql = "SELECT
                f.descripcion AS almacen, 
                d.codigo, 
                d.nombre_comercial, 
                d.principio_activo, 
                d.presentacion, 
                c.nombre AS fabricante, 
                e.descripcion AS unidad_medida, 
                ABS(SUM(b.cantidad_movimiento)) AS cantidad, 
                g.nombre AS asesor_asignado  
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
                LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
                LEFT OUTER JOIN usuario AS g ON g.username = a.asesor_asignado 
            WHERE 
                a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                AND a.estatus = 'PROCESADO' 
                AND b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND IFNULL(a.documento, '') = 'FC' 
                $where 
            GROUP BY 
                d.codigo, f.descripcion, c.nombre, d.principio_activo, 
                d.presentacion, d.nombre_comercial, e.descripcion, g.nombre 
            ORDER BY fabricante, cantidad DESC;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 4. Cabeceras HTTP nativas para la generación y descarga directa del archivo Excel (.xls)
    $filename = "VENTAS_LABORATORIO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

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
    <x:Name>Ventas Laboratorio</x:Name>
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
            <th>ALMACEN</th>
            <th>CODIGO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>NOMBRE ARTICULO</th>
            <th>PRESENTACION</th>
            <th>FABRICANTE</th>
            <th>MEDIDA</th>
            <th>CANTIDAD</th>
            <th>ASESOR</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $almacen     = trim($row["almacen"] ?? '');
        $codigo      = trim($row["codigo"] ?? '');
        $comercial   = trim($row["nombre_comercial"] ?? '');
        $principio   = trim($row["principio_activo"] ?? '');
        $presentacio = trim($row["presentacion"] ?? '');
        $fabricante  = trim($row["fabricante"] ?? '');
        $medida      = trim($row["unidad_medida"] ?? '');
        $asesor      = trim($row["asesor_asignado"] ?? '');
        
        // Conversión numérica pura
        $cantidad    = (float)($row["cantidad"] ?? 0);

        echo "<tr>";
        echo "<td class='texto'>" . htmlspecialchars($almacen, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$codigo}</td>";
        echo "<td class='texto'>" . htmlspecialchars($comercial, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($principio, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($presentacio, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($fabricante, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($medida, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='entero'>{$cantidad}</td>";
        echo "<td class='texto'>" . htmlspecialchars($asesor, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "</tr>";
    }
?>
    </tbody>
</table>
</body>
</html>
<?php
    exit();
?>