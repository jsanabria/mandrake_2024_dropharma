<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda para evitar "Undefined variable"[cite: 8]
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = '';
    if ($tipo !== "") {
        // Sanitizamos para admitir únicamente números y comas (evitar inyección SQL básica)[cite: 8]
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.articulo IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL optimizada de alta densidad (23 columnas)[cite: 8]
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
                (SELECT IFNULL(nro_documento, '') AS nro_documento FROM salidas WHERE id = a.id_documento_padre) AS DOC_NE, a.nota   
            FROM 
                salidas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento= a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN cliente AS g ON g.id = a.cliente 
            WHERE 
                (
                    (a.tipo_documento = 'TDCNET' AND a.estatus IN ('NUEVO','PROCESADO')) 
                    OR (a.tipo_documento = 'TDCASA' AND a.estatus IN ('NUEVO','PROCESADO') AND IFNULL(a.documento, '') <> 'TR')
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

    // 3. Configurar cabeceras HTTP para forzar la descarga del Excel (.xls)[cite: 8]
    $filename = "SALIDAS_ARTICULO_DETALLADO_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Marca UTF-8 BOM para caracteres especiales limpios (ñ, acentos)
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
    <x:Name>Salidas Detalladas</x:Name>
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
            <th>DOCUMENTO</th>
            <th>FECHA</th>
            <th>CLIENTE</th>
            <th>ASESOR</th>
            <th>CIUDAD</th>
            <th>CODIGO ART.</th>
            <th>NOMBRE COMERCIAL</th>
            <th>ARTICULO</th>
            <th>PRESENTACION</th>
            <th>FABRICANTE</th>
            <th>LOTE</th>
            <th>VENCIMIENTO</th>
            <th>CANTIDAD</th>
            <th>PRECIO UNIDAD</th>
            <th>TOTAL ARTICULO</th>
            <th>TOTAL SIN IVA</th>
            <th>IVA</th>
            <th>TOTAL FACTURA</th>
            <th>TIPO DOC</th>
            <th>UNIDADES FACTURA</th>
            <th>DOC NE</th>
            <th>NOTA</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
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
        $nota          = trim($row["nota"] ?? '');

        // Conversión a valores numéricos flotantes puros
        $cantidad     = (float)($row["cantidad_movimiento"] ?? 0);
        $precio_unid  = (float)($row["precio_unidad"] ?? 0);
        $tot_art      = (float)($row["total_articulo"] ?? 0);
        $monto_tot    = (float)($row["monto_total"] ?? 0);
        $iva          = (float)($row["iva"] ?? 0);
        $total_fac    = (float)($row["total"] ?? 0);
        $unidades_fac = (float)($row["unidades"] ?? 0);

        echo "<tr>";
        echo "<td class='entero'>{$id}</td>";
        echo "<td class='texto'>{$nro_documento}</td>";
        echo "<td class='texto'>{$fecha}</td>";
        echo "<td class='texto'>" . htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($asesor, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($ciudad, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$codart}</td>";
        echo "<td class='texto'>" . htmlspecialchars($n_comercial, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($p_activo, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($presentacion, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($fabricante, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$lote}</td>";
        echo "<td class='texto'>{$fec_venc}</td>";
        
        // Cantidades e Indicadores
        echo "<td class='entero'>{$cantidad}</td>";
        
        // Celdas monetarias formateadas automáticamente
        echo "<td class='num'>{$precio_unid}</td>";
        echo "<td class='num'>{$tot_art}</td>";
        echo "<td class='num'>{$monto_tot}</td>";
        echo "<td class='num'>{$iva}</td>";
        echo "<td class='num'>{$total_fac}</td>";
        
        echo "<td class='texto'>" . htmlspecialchars($tipo_doc, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='entero'>{$unidades_fac}</td>";
        echo "<td class='texto'>{$doc_ne}</td>";
        echo "<td class='texto'>" . htmlspecialchars($nota, ENT_QUOTES, 'UTF-8') . "</td>";
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