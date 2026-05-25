<?php  
    // 1. Preparar condiciones dinámicas
    $where = "";
    if (trim($tipo) != "")    $where .= " AND a.documento = '$tipo'";
    if (trim($cliente) != "") $where .= " AND a.cliente = '$cliente'";
    if (trim($asesor) != "")  $where .= " AND a.asesor_asignado = '$asesor'";

    $contar = 0;

    // 2. Encabezado de sección y tabla responsiva adaptada a Bootstrap
    $out .= <<<HTML
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-secondary m-0">Libro de Ventas</h4>
        <div>
            <a class="btn btn-outline-primary ms-2" href="reportes/libro_de_ventas.php?xfecha={$fecha_desde}&yfecha={$fecha_hasta}" target="_blank">
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
                    <th scope="col">Comprobante</th>
                    <th scope="col">Nro Factura</th>
                    <th scope="col">Nro Control</th>
                    <th scope="col">Nota Débito</th>
                    <th scope="col">Nota Crédito</th>
                    <th scope="col">Doc Afectado</th>
                    <th scope="col" class="text-end">Total Ventas</th>
                    <th scope="col" class="text-end">Total Exentas</th>
                    <th scope="col" class="text-end">Total Base</th>
                    <th scope="col" class="text-center">%</th>
                    <th scope="col" class="text-end">IVA</th>
                    <th scope="col" class="text-end">IVA Ret</th>
                    <th scope="col">Asesor</th>
                    <th scope="col" class="text-center">Días Cred</th>
                    <th scope="col" class="text-center">Fecha Venc</th>
                </tr>
            </thead>
            <tbody>
HTML;

    // 3. Consulta SQL
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
                    SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * (IFNULL(a.descuento, 0)/100)), 0)) AS exento_2 
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id), 2) AS no_gravadas, 
                ROUND((SELECT
                    SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * (IFNULL(a.descuento, 0)/100)))) AS gravado_2 
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id), 2) AS base, 
                (SELECT
                    alicuota  
                FROM entradas_salidas
                WHERE tipo_documento = a.tipo_documento AND 
                    id_documento = a.id ORDER BY alicuota DESC LIMIT 0, 1) AS alic, 
                a.`iva` AS iva, 
                0 AS iva_ret, 'N' AS orden, a.asesor_asignado AS asesor, a.descuento, a.tipo_documento, a.estatus, 
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

    $i = 1;
    // 4. Renderizado seguro con fusión de nulos (??)
    while($row = mysqli_fetch_array($rs)) {
        $contar++;
        $estatus = trim($row["estatus"] ?? '');
        $es_anulado = ($estatus == "ANULADO");

        $cliente_name = $es_anulado ? "ANULADA" : trim($row["cliente"] ?? '');
        $rif          = $es_anulado ? "" : trim($row["ci_rif"] ?? '');

        // Validaciones numéricas puras para el multiplicador de Nota de Crédito
        $factor = (trim($row["documento"] ?? '') == "NC") ? -1 : 1;

        $val_total_ventas = $es_anulado ? 0 : (($row["total_ventas"] ?? 0) * $factor);
        $val_no_gravadas  = $es_anulado ? 0 : (($row["no_gravadas"] ?? 0) * $factor);
        $val_base         = $es_anulado ? 0 : (($row["base"] ?? 0) * $factor);
        $val_alic         = $row["alic"] ?? 0;
        $val_iva          = $es_anulado ? 0 : ($row["iva"] ?? 0);
        $val_iva_ret      = $row["iva_ret"] ?? 0;

        $out .= <<<HTML
        <tr>
            <td class="text-center font-monospace">{$i}</td>
            <td class="text-center text-nowrap">{$row['fecha']}</td>
            <td class="text-nowrap font-monospace">{$rif}</td>
            <td class="text-uppercase text-wrap">{$cliente_name}</td>
            <td class="font-monospace">{$row['comprobante']}</td>
            <td class="font-monospace">{$row['nro_factura']}</td>
            <td class="font-monospace">{$row['nro_control']}</td>
            <td class="font-monospace">{$row['nota_debito']}</td>
            <td class="font-monospace">{$row['nota_credito']}</td>
            <td class="font-monospace">{$row['afectado']}</td>
HTML;

        // Formatos numéricos consistentes
        $out .= '<td class="text-end font-monospace">' . ($val_total_ventas == 0 ? "" : number_format($val_total_ventas, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-end font-monospace">' . ($val_no_gravadas == 0 ? "" : number_format($val_no_gravadas, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-end font-monospace">' . ($val_base == 0 ? "" : number_format($val_base, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-center font-monospace">' . number_format($val_alic, 2, ".", ",") . '</td>';
        $out .= '<td class="text-end font-monospace">' . ($es_anulado ? "0.00" : number_format($val_iva, 2, ".", ",")) . '</td>';
        $out .= '<td class="text-end font-monospace">' . number_format($val_iva_ret, 2, ".", ",") . '</td>';
        
        $out .= <<<HTML
            <td>{$row['asesor']}</td>
            <td class="text-center font-monospace">{$row['dias_credito']}</td>
            <td class="text-center text-nowrap">{$row['fec_venc']}</td>
        </tr>
HTML;

        // Control del límite en pantalla (Top 20)
		/*
        if($contar >= 20) {
            $out .= <<<HTML
            <tr>
                <th colspan="19" class="text-center table-warning py-3 text-secondary" style="font-size: 0.85rem;">
                    ⚠️ Se visualizan las primeras {$contar} filas. Para ver el reporte completo, por favor usa el botón de exportar.
                </th>
            </tr>
HTML;
            break;
        }
		*/
        $i++;
    }

    // 5. Cierre y totales con colspan corregido a 19
    $total_items = number_format($contar, 0, "", ".");
    $out .= <<<HTML
            <tr>
                <th colspan="19" class="text-end table-light py-3 pe-4 text-secondary">
                    Total ítems Procesados: <span class="text-dark">{$total_items}</span>
                </th>
            </tr>
        </tbody>
    </table>
</div>
HTML;
?>