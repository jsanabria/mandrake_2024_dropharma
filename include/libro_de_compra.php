<?php  
    // 1. Preparar condiciones dinámicas
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

    $contar = 0;

    // 2. Encabezado de sección y tabla responsiva adaptada a Bootstrap
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Libro de Compras</h4>
        <div>
            <a class="btn btn-outline-primary ms-2" href="reportes/libro_de_compras.php?xfecha={$fecha_desde}&yfecha={$fecha_hasta}" target="_blank">
                <i class="bi bi-printer"></i> Imprimir PDF
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle" style="font-size: 0.8rem; border: 1px solid #dee2e6;">
            <thead class="table-light text-uppercase text-nowrap" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                <tr>
                    <th scope="col" class="text-center">ID</th>
                    <th scope="col" class="text-center">Fecha</th>
                    <th scope="col">RIF</th>
                    <th scope="col" style="min-width: 180px;">Razón Social</th>
                    <th scope="col">Factura</th>
                    <th scope="col">Nota Crédito</th>
                    <th scope="col">Doc Afectado</th>
                    <th scope="col">Nro Control</th>
                    <th scope="col" class="text-end">Total Compras</th>
                    <th scope="col" class="text-end">Total Exentas</th>
                    <th scope="col" class="text-end">Total Base</th>
                    <th scope="col" class="text-center">%</th>
                    <th scope="col" class="text-end">IVA</th>
                    <th scope="col" class="text-end">IVA Ret</th>
                    <th scope="col" class="text-end">ISLR Ret</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL unificada
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

    $i = 1;
    // 4. Renderizado seguro con fusión de nulos (??)
    while($row = mysqli_fetch_array($rs)) {
        $contar++;
        $estatus    = trim($row["estatus"] ?? '');
        $es_anulado = ($estatus == "ANULADO");

        $proveedor_name = $es_anulado ? "ANULADA" : trim($row["proveedor"] ?? '');
        $rif            = $es_anulado ? "" : trim($row["ci_rif"] ?? '');

        // Formateo de valores numéricos
        $val_total    = $es_anulado ? 0 : (float)($row["total"] ?? 0);
        $val_exenta   = $es_anulado ? 0 : (float)($row["exenta"] ?? 0);
        $val_gravable = $es_anulado ? 0 : (float)($row["gravable"] ?? 0);
        $val_alicuota = (float)($row["alicuota_iva"] ?? 0);
        $val_iva      = $es_anulado ? 0 : (float)($row["iva"] ?? 0);
        $val_ret_iva  = (float)($row["ret_iva"] ?? 0);
        $val_ret_islr = (float)($row["ret_islr"] ?? 0);

        $out .= <<<HTML
        <tr>
            <td class="text-center font-monospace">{$i}</td>
            <td class="text-center text-nowrap">{$row['fecha']}</td>
            <td class="text-nowrap font-monospace">{$rif}</td>
            <td class="text-uppercase text-wrap">{$proveedor_name}</td>
            <td class="font-monospace">{$row['nro_documento']}</td>
            <td class="font-monospace">{$row['nota_credito']}</td>
            <td class="font-monospace">{$row['doc_afectado']}</td>
            <td class="font-monospace">{$row['nro_control']}</td>
HTML;

        // Formatos numéricos consistentes
        $out .= '<td class="text-end font-monospace">' . ($val_total == 0 ? "" : number_format($val_total, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-end font-monospace">' . ($val_exenta == 0 ? "" : number_format($val_exenta, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-end font-monospace">' . ($val_gravable == 0 ? "" : number_format($val_gravable, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-center font-monospace">' . number_format($val_alicuota, 2, ".", ",") . '</td>';
        $out .= '<td class="text-end font-monospace">' . ($es_anulado ? "0.00" : number_format($val_iva, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-end font-monospace">' . number_format($val_ret_iva, 2, ".", ",") . '</td>';
        $out .= '<td class="text-end font-monospace">' . number_format($val_ret_islr, 2, ".", ",") . '</td>';
        
        $out .= <<<HTML
        </tr>
HTML;
        $i++;
    }

    // 5. Cierre y totales con colspan de 15 columnas
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="15" class="text-end table-light py-3 pe-4 text-secondary">
                    Total ítems Procesados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>