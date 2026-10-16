<?php
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"[cite: 9]
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';
    
    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números y comas en caso de un "IN" múltiple[cite: 9]
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.articulo IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada incorporando tipo_movimiento y tipo_documento[cite: 9]
    $sql = "SELECT 
                b.tipo_documento AS tipo_movimiento,
                COALESCE(NULLIF(a.documento, ''), 'FC') AS tipo_documento,
                d.id,
                d.codigo, 
                d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre AS fabricante, 
                SUM(CASE WHEN a.documento = 'NC' THEN -ABS(b.cantidad_movimiento) ELSE ABS(b.cantidad_movimiento) END) AS cantidad_movimiento 
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                b.tipo_documento IN ('TDCFCV','TDCASA') 
                AND a.estatus = 'PROCESADO'  
                AND (IFNULL(a.documento, '') IN ('FC', 'NC', 'ND') OR (IFNULL(a.documento, '') = '' AND a.factura = 'S')) 
                AND a.activo = 'S' 
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            GROUP BY 
                b.tipo_documento, a.documento, d.id, d.codigo, d.nombre_comercial, d.principio_activo, d.presentacion, c.nombre 
            HAVING 
                SUM(CASE WHEN a.documento = 'NC' THEN -ABS(b.cantidad_movimiento) ELSE ABS(b.cantidad_movimiento) END) <> 0
            ORDER BY cantidad_movimiento DESC;";

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configurar cabeceras HTTP para forzar la descarga del Excel (.xls)[cite: 9]
    $filename = "SALIDAS_ARTICULO_" . date('Ymd_His') . ".xls";
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
    <x:Name>Salidas por Articulo</x:Name>
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
            <th>TIPO MOV.</th>
            <th>TIPO DOC</th>
            <th>CODIGO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>PRINCIPIO ACTIVO</th>
            <th>PRESENTACION</th>
            <th>FABRICANTE</th>
            <th>CANTIDAD</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $tipo_mov     = trim($row["tipo_movimiento"] ?? '');
        $tipo_doc     = trim($row["tipo_documento"] ?? '');
        $codigo       = trim($row["codigo"] ?? '');
        $n_comercial  = trim($row["nombre_comercial"] ?? '');
        $p_activo     = trim($row["principio_activo"] ?? '');
        $presentacion = trim($row["presentacion"] ?? '');
        $fabricante   = trim($row["fabricante"] ?? '');
        
        // Conversión numérica pura
        $cantidad     = (float)($row["cantidad_movimiento"] ?? 0);

        echo "<tr>";
        echo "<td class='texto'>{$tipo_mov}</td>";
        echo "<td class='texto'>{$tipo_doc}</td>";
        echo "<td class='texto'>{$codigo}</td>";
        echo "<td class='texto'>" . htmlspecialchars($n_comercial, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($p_activo, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($presentacion, ENT_QUOTES, 'UTF-8') . "</td>";
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
    exit();
?>