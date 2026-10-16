<?php

// ============================================================
// VENTAS POR CLIENTE - ORDENES DE ENTREGA - EXCEL
// ============================================================

// 1. Inicializar parámetros
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

        AND a.tipo_documento = 'TDCNET'

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
// 3. NOMBRE Y CABECERAS DEL ARCHIVO
// ============================================================

$filename = "VENTAS_CLIENTE_ORDENES_ENTREGA_" . date('Ymd_His') . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// BOM UTF-8 para acentos
echo "\xEF\xBB\xBF";

?>
<html
    xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns="http://www.w3.org/TR/REC-html40"
>
<head>

<meta http-equiv="Content-Type"
      content="text/html; charset=utf-8" />

<!--[if gte mso 9]>
<xml>
    <x:ExcelWorkbook>
        <x:ExcelWorksheets>
            <x:ExcelWorksheet>

                <x:Name>Ordenes por Cliente</x:Name>

                <x:WorksheetOptions>
                    <x:DisplayGridlines/>
                </x:WorksheetOptions>

            </x:ExcelWorksheet>
        </x:ExcelWorksheets>
    </x:ExcelWorkbook>
</xml>
<![endif]-->

<style>

    .texto {
        mso-number-format:"\@";
    }

    .num {
        mso-number-format:"0.00";
        text-align: right;
    }

    .cantidad {
        mso-number-format:"0.00";
        text-align: right;
    }

    .fecha {
        mso-number-format:"\@";
        text-align: center;
    }

    th {
        background-color: #002060;
        color: #FFFFFF;
        font-weight: bold;
        text-align: center;
        border: 0.5pt solid #000000;
    }

    td {
        border: 0.5pt solid #D9D9D9;
        vertical-align: middle;
    }

</style>

</head>

<body>

<table border="1">

    <thead>

        <tr>
            <th>CLIENTE</th>
            <th>CIUDAD</th>
            <th>FECHA</th>
            <th>DOCUMENTO</th>
            <th>CÓDIGO</th>
            <th>ARTÍCULO</th>
            <th>CANTIDAD</th>
            <th>PRECIO UNIDAD</th>
            <th>TOTAL</th>
            <th>MONEDA</th>
        </tr>

    </thead>

    <tbody>

<?php

// ============================================================
// 4. RECORRER RESULTADOS
// ============================================================

$total_cantidad = 0;
$total_monto    = 0;
$total_lineas   = 0;

while ($row = mysqli_fetch_assoc($rs)) {

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

    $fecha = trim(
        isset($row["fecha"])
            ? $row["fecha"]
            : ''
    );

    $documento = trim(
        isset($row["nro_documento"])
            ? $row["nro_documento"]
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


    // --------------------------------------------------------
    // Acumulados
    // --------------------------------------------------------

    $total_cantidad += $cantidad;
    $total_monto    += $total_articulo;
    $total_lineas++;


    // --------------------------------------------------------
    // Proteger texto
    // --------------------------------------------------------

    $cliente   = htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8');
    $ciudad    = htmlspecialchars($ciudad, ENT_QUOTES, 'UTF-8');
    $fecha     = htmlspecialchars($fecha, ENT_QUOTES, 'UTF-8');
    $documento = htmlspecialchars($documento, ENT_QUOTES, 'UTF-8');
    $codigo    = htmlspecialchars($codigo, ENT_QUOTES, 'UTF-8');
    $articulo  = htmlspecialchars($articulo, ENT_QUOTES, 'UTF-8');
    $moneda    = htmlspecialchars($moneda, ENT_QUOTES, 'UTF-8');


    // --------------------------------------------------------
    // Fila
    // --------------------------------------------------------

    echo "<tr>";

    echo "<td class='texto'>{$cliente}</td>";
    echo "<td class='texto'>{$ciudad}</td>";
    echo "<td class='fecha'>{$fecha}</td>";

    // Documento como texto para preservar ceros a la izquierda
    echo "<td class='texto'>{$documento}</td>";

    // Código como texto
    echo "<td class='texto'>{$codigo}</td>";

    echo "<td class='texto'>{$articulo}</td>";

    echo "<td class='cantidad'>{$cantidad}</td>";
    echo "<td class='num'>{$precio_unidad}</td>";
    echo "<td class='num'>{$total_articulo}</td>";

    echo "<td class='texto'>{$moneda}</td>";

    echo "</tr>";
}


// ============================================================
// 5. TOTAL GENERAL
// ============================================================

echo "<tr>";

echo "<td colspan='6'
          style='font-weight:bold; text-align:right;'>
          TOTAL GENERAL - LÍNEAS: {$total_lineas}
      </td>";

echo "<td class='cantidad'
          style='font-weight:bold;'>
          {$total_cantidad}
      </td>";

echo "<td></td>";

echo "<td class='num'
          style='font-weight:bold;'>
          {$total_monto}
      </td>";

echo "<td></td>";

echo "</tr>";

?>

    </tbody>

</table>

</body>
</html>

<?php

exit();

?>