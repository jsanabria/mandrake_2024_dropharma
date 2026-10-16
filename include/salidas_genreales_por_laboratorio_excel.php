<?php 
    // 1. Recibir y sanitizar parámetros de URL o del ámbito superior[cite: 2]
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    $where2 = "";

    if (!empty($tipo)) {
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.fabricante IN ($tipo_clean)";
            $where2 = "AND a.fabricante IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada trayendo ambos campos
    $sql = "SELECT 
                j.tipo_movimiento, j.tipo_documento, j.almacen, i.codigo, i.nombre_comercial, 
                i.principio_activo, i.presentacion, i.fabricante, j.unidad_medida, 
                j.cantidad 
            FROM 
                (SELECT 
                    a.id AS articulo, a.codigo, b.nombre AS fabricante, 
                    a.principio_activo, a.presentacion, a.nombre_comercial 
                FROM 
                    articulo AS a 
                    JOIN fabricante AS b ON b.Id = a.fabricante 
                WHERE 
                    0=0 $where2) AS i 
                JOIN  
                (SELECT
                    b.tipo_documento AS tipo_movimiento,
                    COALESCE(NULLIF(a.documento, ''), 'FC') AS tipo_documento, 
                    b.articulo, d.codigo, f.descripcion AS almacen, c.nombre AS fabricante, 
                    d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion AS unidad_medida, 
                    SUM(CASE WHEN a.documento = 'NC' THEN -ABS(b.cantidad_movimiento) ELSE ABS(b.cantidad_movimiento) END) AS cantidad  
                FROM 
                    salidas AS a 
                    JOIN entradas_salidas AS b ON b.id_documento = a.id 
                    LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante 
                    LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                    LEFT OUTER JOIN unidad_medida AS e ON e.codigo = b.articulo_unidad_medida 
                    LEFT OUTER JOIN almacen AS f ON f.codigo = b.almacen 
                WHERE 
                    a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                    AND a.estatus  = 'PROCESADO' 
                    AND b.tipo_documento IN ('TDCFCV','TDCASA') 
                    AND (IFNULL(a.documento, '') IN ('FC', 'NC', 'ND') OR (IFNULL(a.documento, '') = '' AND a.factura = 'S'))
                    $where 
                GROUP BY 
                    b.tipo_documento, a.documento, b.articulo, d.codigo, f.descripcion, c.nombre, 
                    d.principio_activo, d.presentacion, d.nombre_comercial, e.descripcion
                HAVING 
                    SUM(CASE WHEN a.documento = 'NC' THEN -ABS(b.cantidad_movimiento) ELSE ABS(b.cantidad_movimiento) END) <> 0) AS j ON j.articulo = i.articulo 
            ORDER BY i.fabricante, j.almacen DESC, j.cantidad DESC;";

    $rs = mysqli_query($link, $sql);

    if(!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configurar cabeceras HTTP para forzar la descarga del Excel (.xls)[cite: 2]
    $filename = "SALIDAS_LABORATORIO_" . date('Ymd_His') . ".xls";
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
    <x:Name>Salidas Laboratorio</x:Name>
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
            <th>ALMACEN</th>
            <th>CODIGO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>PRINCIPIO ACTIVO</th>
            <th>PRESENTACION</th>
            <th>FABRICANTE</th>
            <th>UNIDAD MEDIDA</th>
            <th>CANTIDAD</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $tipo_movimiento  = trim($row["tipo_movimiento"] ?? '');
        $tipo_documento   = trim($row["tipo_documento"] ?? '');
        $almacen          = trim($row["almacen"] ?? '');
        $codigo           = trim($row["codigo"] ?? '');
        $nombre_comercial = trim($row["nombre_comercial"] ?? '');
        $principio_activo = trim($row["principio_activo"] ?? '');
        $presentacion     = trim($row["presentacion"] ?? '');
        $fabricante       = trim($row["fabricante"] ?? '');
        $unidad_medida    = trim($row["unidad_medida"] ?? '');
        
        // Conversión a valor flotante puro
        $cantidad         = (float)($row["cantidad"] ?? 0);

        echo "<tr>";
        echo "<td class='texto'>{$tipo_movimiento}</td>";
        echo "<td class='texto'>{$tipo_documento}</td>";
        echo "<td class='texto'>" . htmlspecialchars($almacen, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$codigo}</td>";
        echo "<td class='texto'>" . htmlspecialchars($nombre_comercial, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($principio_activo, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($presentacion, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($fabricante, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($unidad_medida, ENT_QUOTES, 'UTF-8') . "</td>";
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