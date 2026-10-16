<?php 
    // 1. Inicializar y sanitizar parámetros de búsqueda
    $tipo        = isset($tipo) ? trim($tipo) : '';
    $fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
    $fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

    $where = "";
    if ($tipo !== "") {
        // Sanitizamos para admitir números y comas en caso de un "IN" múltiple
        $tipo_clean = preg_replace('/[^0-9,]/', '', $tipo);
        if (!empty($tipo_clean)) {
            $where = "AND b.articulo IN ($tipo_clean)";
        }
    }

    // 2. Consulta SQL de datos detallados
    $sql = "SELECT
                a.id, 
                a.nro_documento, 
                DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha, 
                g.nombre AS proveedor, 
                d.codigo AS codart, 
                d.nombre_comercial, d.principio_activo, d.presentacion, 
                c.nombre AS fabricante, 
                b.lote, 
                b.fecha_vencimiento, 
                ABS(b.cantidad_movimiento) AS cantidad_movimiento,
                b.precio_unidad_sin_desc AS costo_ful, 
                b.descuento, 
                b.costo_unidad, 
                b.costo, 
                (SELECT descripcion FROM tipo_documento WHERE codigo = a.tipo_documento) AS tipo, 
                a.estatus  
            FROM 
                entradas AS a 
                JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
                LEFT OUTER JOIN fabricante AS c ON c.Id = b.fabricante  
                LEFT OUTER JOIN articulo AS d ON d.id = b.articulo 
                LEFT OUTER JOIN proveedor AS g ON g.id = a.proveedor 
            WHERE 
                (
                    (a.tipo_documento = 'TDCNRP' AND a.estatus IN ('NUEVO', 'PROCESADO')) 
                    OR (a.tipo_documento = 'TDCAEN' AND a.estatus IN ('NUEVO', 'PROCESADO'))
                )
                AND a.fecha BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'
                $where 
            ORDER BY a.nro_documento, c.nombre, d.principio_activo;"; 

    $rs = mysqli_query($link, $sql);

    if (!$rs) {
        var_dump(mysqli_error($link));
        die();
    }

    $developer_records = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $developer_records[] = $row;
    }

    // 3. Configurar cabeceras HTTP para forzar la descarga del archivo Excel (.xls)
    $filename = "ENTRADAS_DETALLADO_" . date('Ymd_His') . ".xls";
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
    <x:Name>Entradas Detalladas</x:Name>
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
            <th>TIPO</th>
            <th>DOCUMENTO</th>
            <th>FECHA</th>
            <th>PROVEEDOR</th>
            <th>CODIGO</th>
            <th>NOMBRE COMERCIAL</th>
            <th>PRINCIPIO ACTIVO</th>
            <th>PRESENTACION</th>
            <th>FABRICANTE</th>
            <th>LOTE</th>
            <th>FECHA VENCIMIENTO</th>
            <th>CANTIDAD</th>
            <th>COSTO FUL</th>
            <th>DESCUENTO</th>
            <th>COSTO UNIDAD</th>
            <th>COSTO TOTAL</th>
            <th>ESTATUS</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach ($developer_records as $row) {
        $tipo         = trim($row["tipo"] ?? '');
        $documento    = trim($row["nro_documento"] ?? '');
        $fecha        = trim($row["fecha"] ?? '');
        $proveedor    = trim($row["proveedor"] ?? '');
        $codart       = trim($row["codart"] ?? '');
        $comercial    = trim($row["nombre_comercial"] ?? '');
        $principio    = trim($row["principio_activo"] ?? '');
        $presentacion = trim($row["presentacion"] ?? '');
        $fabricante   = trim($row["fabricante"] ?? '');
        $lote         = trim($row["lote"] ?? '');
        $fec_venc     = trim($row["fecha_vencimiento"] ?? '');
        $estatus      = trim($row["estatus"] ?? '');

        // Conversiones numéricas
        $cantidad     = (float)($row["cantidad_movimiento"] ?? 0);
        $costo_ful    = (float)($row["costo_ful"] ?? 0);
        $descuento    = (float)($row["descuento"] ?? 0);
        $costo_uni    = (float)($row["costo_unidad"] ?? 0);
        $costo_tot    = (float)($row["costo"] ?? 0);

        echo "<tr>";
        echo "<td class='texto'>" . htmlspecialchars($tipo, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($documento, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$fecha}</td>";
        echo "<td class='texto'>" . htmlspecialchars($proveedor, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$codart}</td>";
        echo "<td class='texto'>" . htmlspecialchars($comercial, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($principio, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($presentacion, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($fabricante, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>" . htmlspecialchars($lote, ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td class='texto'>{$fec_venc}</td>";
        echo "<td class='entero'>{$cantidad}</td>";
        echo "<td class='num'>{$costo_ful}</td>";
        echo "<td class='num'>{$descuento}</td>";
        echo "<td class='num'>{$costo_uni}</td>";
        echo "<td class='num'>{$costo_tot}</td>";
        echo "<td class='texto'>" . htmlspecialchars($estatus, ENT_QUOTES, 'UTF-8') . "</td>";
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