<?php

// ============================================================
// VENTAS POR CLIENTE - FACTURAS
// Detalla lo comprado por cada cliente.
// ============================================================

// 1. Inicializar parámetros
$id          = isset($id) ? trim($id) : '';
$tipo        = isset($tipo) ? trim($tipo) : '';
$fecha_desde = isset($fecha_desde) ? trim($fecha_desde) : '';
$fecha_hasta = isset($fecha_hasta) ? trim($fecha_hasta) : '';

$where = "";

// ------------------------------------------------------------
// Filtro opcional por cliente.
// $tipo contiene el ID seleccionado en listado_master.php.
// ------------------------------------------------------------
if ($tipo !== "") {
    $cliente_id = mysqli_real_escape_string($link, $tipo);
    $where .= " AND g.id = '$cliente_id' ";
}


// ============================================================
// 2. CONSULTA
// ============================================================

$sql = "
    SELECT
        a.id,
        a.cliente AS id_cliente,
        a.nro_documento,
        DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha,

        g.nombre AS cliente,

        (
            SELECT campo_descripcion
            FROM tabla
            WHERE tabla = 'CIUDAD'
              AND campo_codigo = g.ciudad
            LIMIT 1
        ) AS ciudad,

        d.codigo AS codigo_articulo,
        TRIM(
            CONCAT_WS(
                ' - ',
                NULLIF(TRIM(d.nombre_comercial), ''),
                NULLIF(TRIM(d.principio_activo), '')
            )
        ) AS articulo,

        b.cantidad_movimiento AS cantidad,
        b.precio_unidad AS precio_unidad,
        b.precio AS total_articulo,

        a.moneda

    FROM salidas AS a

        JOIN entradas_salidas AS b
            ON b.tipo_documento = a.tipo_documento
           AND b.id_documento = a.id

        LEFT JOIN articulo AS d
            ON d.id = b.articulo

        LEFT JOIN cliente AS g
            ON g.id = a.cliente

    WHERE
        a.fecha BETWEEN '$fecha_desde 00:00:00'
                    AND '$fecha_hasta 23:59:59'

        AND a.estatus = 'PROCESADO'

        AND a.tipo_documento = 'TDCFCV'

        AND IFNULL(a.documento, '') = 'FC'

        $where

    ORDER BY
        g.nombre,
        a.fecha,
        a.nro_documento,
        d.nombre_comercial
";

$rs = mysqli_query($link, $sql);

if (!$rs) {
    var_dump(mysqli_error($link));
    die();
}


// ============================================================
// 3. ENCABEZADO DEL REPORTE
// ============================================================

$out .= <<<HTML

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="text-secondary m-0">
        Ventas por Cliente - Facturas
    </h4>
</div>

<div class="table-responsive">

<table class="table table-sm table-hover align-middle"
       style="font-size: 0.80rem; border: 1px solid #dee2e6;">

    <thead class="table-light text-uppercase text-nowrap"
           style="font-size: 0.72rem; letter-spacing: 0.4px;">

        <tr>
            <th>Fecha</th>
            <th>Documento</th>
            <th>Código</th>
            <th>Artículo</th>

            <th class="text-end">Cantidad</th>
            <th class="text-end">Precio Unidad</th>
            <th class="text-end">Total</th>

            <th class="text-center">Moneda</th>
        </tr>

    </thead>

    <tbody>

HTML;


// ============================================================
// 4. VARIABLES DE CONTROL
// ============================================================

$cliente_anterior = null;

$total_cliente_cantidad = 0;
$total_cliente_monto    = 0;

$total_general_cantidad = 0;
$total_general_monto    = 0;

$total_clientes = 0;
$total_lineas   = 0;


// ============================================================
// 5. RECORRER RESULTADOS
// ============================================================

while ($row = mysqli_fetch_assoc($rs)) {

    $id_cliente = isset($row["id_cliente"])
        ? $row["id_cliente"]
        : '';

    $cliente = trim(
        isset($row["cliente"])
            ? $row["cliente"]
            : ''
    );

    $ciudad = trim(
        isset($row["ciudad"])
            ? $row["ciudad"]
            : ''
    );

    $codigo = trim(
        isset($row["codigo_articulo"])
            ? $row["codigo_articulo"]
            : ''
    );

    $articulo = trim(
        isset($row["articulo"])
            ? $row["articulo"]
            : ''
    );

    $moneda = trim(
        isset($row["moneda"])
            ? $row["moneda"]
            : ''
    );

    $cantidad = floatval(
        isset($row["cantidad"])
            ? $row["cantidad"]
            : 0
    );

    $precio_unidad = floatval(
        isset($row["precio_unidad"])
            ? $row["precio_unidad"]
            : 0
    );

    $total_articulo = floatval(
        isset($row["total_articulo"])
            ? $row["total_articulo"]
            : 0
    );


    // ========================================================
    // CAMBIO DE CLIENTE
    // ========================================================

    if ($cliente_anterior !== $id_cliente) {

        // ----------------------------------------------------
        // Cerrar cliente anterior
        // ----------------------------------------------------
        if ($cliente_anterior !== null) {

            $total_cliente_cantidad_form =
                number_format($total_cliente_cantidad, 2, ",", ".");

            $total_cliente_monto_form =
                number_format($total_cliente_monto, 2, ",", ".");

            $out .= <<<HTML

            <tr class="table-light">
                <th colspan="4"
                    class="text-end pe-3">
                    TOTAL CLIENTE
                </th>

                <th class="text-end font-monospace">
                    {$total_cliente_cantidad_form}
                </th>

                <th></th>

                <th class="text-end font-monospace fw-bold">
                    {$total_cliente_monto_form}
                </th>

                <th></th>
            </tr>

            <tr>
                <td colspan="8"
                    style="height: 10px; background: #ffffff;">
                </td>
            </tr>

HTML;
        }


        // ----------------------------------------------------
        // Abrir nuevo cliente
        // ----------------------------------------------------

        $total_clientes++;

        $total_cliente_cantidad = 0;
        $total_cliente_monto    = 0;

        $cliente_disp = ($cliente !== "")
            ? htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8')
            : "CLIENTE NO REGISTRADO";

        $ciudad_disp = ($ciudad !== "")
            ? htmlspecialchars($ciudad, ENT_QUOTES, 'UTF-8')
            : "SIN CIUDAD";

        $out .= <<<HTML

        <tr class="table-primary">
            <th colspan="8"
                class="py-2">

                <span class="fw-bold">
                    CLIENTE:
                </span>

                {$cliente_disp}

                <span class="text-muted ms-3">
                    {$ciudad_disp}
                </span>

            </th>
        </tr>

HTML;

        $cliente_anterior = $id_cliente;
    }


    // ========================================================
    // ACUMULAR
    // ========================================================

    $total_cliente_cantidad += $cantidad;
    $total_cliente_monto    += $total_articulo;

    $total_general_cantidad += $cantidad;
    $total_general_monto    += $total_articulo;

    $total_lineas++;


    // ========================================================
    // FORMATO
    // ========================================================

    $cantidad_form =
        number_format($cantidad, 2, ",", ".");

    $precio_unidad_form =
        number_format($precio_unidad, 2, ",", ".");

    $total_articulo_form =
        number_format($total_articulo, 2, ",", ".");

    $fecha = htmlspecialchars(
        $row["fecha"],
        ENT_QUOTES,
        'UTF-8'
    );

    $documento = htmlspecialchars(
        $row["nro_documento"],
        ENT_QUOTES,
        'UTF-8'
    );

    $codigo_disp = htmlspecialchars(
        $codigo,
        ENT_QUOTES,
        'UTF-8'
    );

    $articulo_disp = htmlspecialchars(
        $articulo,
        ENT_QUOTES,
        'UTF-8'
    );

    $moneda_disp = htmlspecialchars(
        $moneda,
        ENT_QUOTES,
        'UTF-8'
    );


    // ========================================================
    // DETALLE
    // ========================================================

    $out .= <<<HTML

    <tr>

        <td class="text-nowrap">
            {$fecha}
        </td>

        <td class="font-monospace text-nowrap">
            {$documento}
        </td>

        <td class="font-monospace">
            {$codigo_disp}
        </td>

        <td class="text-uppercase">
            {$articulo_disp}
        </td>

        <td class="text-end font-monospace">
            {$cantidad_form}
        </td>

        <td class="text-end font-monospace">
            {$precio_unidad_form}
        </td>

        <td class="text-end font-monospace fw-semibold">
            {$total_articulo_form}
        </td>

        <td class="text-center">
            {$moneda_disp}
        </td>

    </tr>

HTML;
}


// ============================================================
// 6. CERRAR ÚLTIMO CLIENTE
// ============================================================

if ($cliente_anterior !== null) {

    $total_cliente_cantidad_form =
        number_format($total_cliente_cantidad, 2, ",", ".");

    $total_cliente_monto_form =
        number_format($total_cliente_monto, 2, ",", ".");

    $out .= <<<HTML

    <tr class="table-light">

        <th colspan="4"
            class="text-end pe-3">

            TOTAL CLIENTE

        </th>

        <th class="text-end font-monospace">
            {$total_cliente_cantidad_form}
        </th>

        <th></th>

        <th class="text-end font-monospace fw-bold">
            {$total_cliente_monto_form}
        </th>

        <th></th>

    </tr>

HTML;
}


// ============================================================
// 7. TOTAL GENERAL
// ============================================================

$total_general_cantidad_form =
    number_format($total_general_cantidad, 2, ",", ".");

$total_general_monto_form =
    number_format($total_general_monto, 2, ",", ".");

$total_clientes_form =
    number_format($total_clientes, 0, "", ".");

$total_lineas_form =
    number_format($total_lineas, 0, "", ".");


$out .= <<<HTML

    <tr class="table-dark">

        <th colspan="4"
            class="text-end pe-3 py-3">

            TOTAL GENERAL

            <span class="ms-3">
                Clientes: {$total_clientes_form}
            </span>

            <span class="ms-3">
                Líneas: {$total_lineas_form}
            </span>

        </th>

        <th class="text-end font-monospace">
            {$total_general_cantidad_form}
        </th>

        <th></th>

        <th class="text-end font-monospace">
            {$total_general_monto_form}
        </th>

        <th></th>

    </tr>

    </tbody>

</table>

</div>

HTML;

?>