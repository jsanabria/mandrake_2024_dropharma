<?php

namespace PHPMaker2024\mandrake;

// Cargar el autoloader de PHPMaker 2024 desde la raíz del proyecto
if (file_exists(__DIR__ . "/../vendor/autoload.php")) {
    require_once __DIR__ . "/../vendor/autoload.php";
} elseif (file_exists(__DIR__ . "/../autoload.php")) {
    require_once __DIR__ . "/../autoload.php";
}

// Manejo de exportación a Excel
if (isset($_REQUEST["toexcel"]) && $_REQUEST["toexcel"] === "SI") {
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header("Content-Disposition: attachment; filename=FinancieroEntreFechas_" . date('Ymd_His') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}

$fDesde = $_REQUEST["FechaDesde"] ?? date("Y-m-d");
$fHasta = $_REQUEST["FechaHasta"] ?? date("Y-m-d");
$xTipo  = $_REQUEST["xTipo"] ?? "";

/**
 * Función que reutiliza la conexión directa de include/connect.php
 */
function ejecutarYMostrarTabla2024(string $sql): string {
    // 1. Incluir connect.php si aún no se ha cargado la conexión $link
    global $link;
    
    if (!isset($link) || !$link) {
        $pathConnect = __DIR__ . '/connect.php';
        if (!file_exists($pathConnect)) {
            $pathConnect = __DIR__ . '/../include/connect.php';
        }
        
        if (file_exists($pathConnect)) {
            require_once $pathConnect;
        }
    }

    if (!isset($link) || !$link) {
        return '<div class="alert alert-danger m-3">No se pudo obtener el recurso de conexión desde connect.php.</div>';
    }

    // 2. Ejecutar la consulta con la conexión global activa
    $rows = [];
    $result = @mysqli_query($link, $sql);

    if (!$result) {
        return '<div class="alert alert-danger m-3">Error en la consulta SQL: ' . htmlspecialchars(mysqli_error($link), ENT_QUOTES, 'UTF-8') . '</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    if (empty($rows)) {
        return '<div class="alert alert-info m-3">No se encontraron datos para el período consultado.</div>';
    }

    // 3. Generar HTML
    $html = '<div class="table-responsive"><table class="table table-striped table-hover table-bordered align-middle mb-0">';
    $html .= '<thead class="table-dark"><tr>';
    foreach (array_keys($rows[0]) as $colName) {
        $html .= '<th>' . htmlspecialchars((string)$colName, ENT_QUOTES, 'UTF-8') . '</th>';
    }
    $html .= '</tr></thead><tbody>';

    foreach ($rows as $row) {
        $html .= '<tr>';
        foreach ($row as $val) {
            $html .= '<td>' . ($val !== null ? $val : '') . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table></div>';

    return $html;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Reporte Financiero</title>
    <!-- Incluir Bootstrap 5 nativo del proyecto -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/adminlte.min.css">
    <!-- Fallback CDN en caso de que la ruta local de css varíe -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

<div class="container-fluid bg-white p-4 rounded shadow-sm">
    <h4 class="mb-4">
        <b>
            <a class="text-decoration-none" target="_blank" href="FinancieroDetalle.php?toexcel=SI&FechaDesde=<?= htmlspecialchars($fDesde) ?>&FechaHasta=<?= htmlspecialchars($fHasta) ?>&xTipo=<?= htmlspecialchars($xTipo) ?>">
                DETALLE REPORTE FINANCIERO DESDE <?= htmlspecialchars($fDesde) ?> HASTA <?= htmlspecialchars($fHasta) ?>
            </a>
        </b>
    </h4>

    <form class="row g-3 align-items-center mb-4" method="post" action="FinancieroDetalle.php">
      <div class="col-auto">
        <label for="FechaDesde" class="col-form-label font-weight-bold">Fecha desde:</label>
        <input type="date" id="FechaDesde" name="FechaDesde" value="<?= htmlspecialchars($fDesde) ?>" class="form-control">
      </div>
      <div class="col-auto">
        <label for="FechaHasta" class="col-form-label font-weight-bold">Fecha hasta:</label>
        <input type="date" id="FechaHasta" name="FechaHasta" value="<?= htmlspecialchars($fHasta) ?>" class="form-control">
      </div>
      <div class="col-auto">
        <label for="xTipo" class="col-form-label font-weight-bold">Tipo:</label>
        <select class="form-select" id="xTipo" name="xTipo">
            <option value="" <?= ($xTipo == "" ? 'selected' : '') ?>></option>
            <option value="COMPRAS" <?= ($xTipo == "COMPRAS" ? 'selected' : '') ?>>COMPRAS</option>
            <option value="VENTAS" <?= ($xTipo == "VENTAS" ? 'selected' : '') ?>>VENTAS</option>
            <option value="GIROS" <?= ($xTipo == "GIROS" ? 'selected' : '') ?>>GIROS</option>
        </select>
      </div>
      <div class="col-auto mt-4">
        <button type="submit" class="btn btn-primary">Enviar</button>
        <button type="button" class="btn btn-secondary" onclick="GoBack('<?= htmlspecialchars($fDesde) ?>','<?= htmlspecialchars($fHasta) ?>');">Regresar</button>
      </div>
    </form>

    <?php if ($xTipo === "" || $xTipo === "COMPRAS") { ?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white"><b>Detalle Compras</b></div>
        <div class="card-body p-0">
        <?php
        $sql = "SELECT
            'COMPRAS' AS TIPO, 
            c.nombre AS PROVEEDOR, 
            DATE_FORMAT(b.fecha, '%d/%m/%Y') AS FECHA, 
            b.nro_documento AS DOCUMENTO, 
            d.principio_activo AS ARTICULO, 
            FORMAT(ABS(a.cantidad_movimiento), 0) AS UNIDADES, 
            FORMAT(a.costo_unidad, 2) AS COSTO_UNIDAD, 
            FORMAT(a.costo, 2) AS COSTO, 
            b.moneda AS MONEDA, 
            FORMAT(b.tasa_dia, 2) AS TASA  
        FROM entradas_salidas AS a 
        JOIN entradas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
        JOIN proveedor AS c ON c.id = b.proveedor 
        LEFT OUTER JOIN articulo AS d ON d.id = a.articulo 
        WHERE a.tipo_documento = 'TDCNRP' AND b.estatus = 'PROCESADO' 
          AND b.fecha BETWEEN '{$fDesde} 00:00:00' AND '{$fHasta} 23:59:59' 
        ORDER BY b.fecha;";

        echo ejecutarYMostrarTabla2024($sql);
        ?>
        </div>
    </div>
    <?php } ?>

    <?php if ($xTipo === "" || $xTipo === "VENTAS") { ?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-success text-white"><b>Detalle Ventas</b></div>
        <div class="card-body p-0">
        <?php
        $sql = "SELECT
            'VENTAS' AS TIPO, 
            c.nombre AS CLIENTE, 
            DATE_FORMAT(b.fecha, '%d/%m/%Y') AS FECHA, 
            b.nro_documento AS DOCUMENTO, 
            d.principio_activo AS ARTICULO, 
            FORMAT(ABS(a.cantidad_movimiento), 0) AS UNIDADES,
            FORMAT(a.costo_unidad, 2) AS COSTO_UNIDAD_USD,
            FORMAT(a.costo, 2) AS COSTO_USD, 
            FORMAT(a.precio/NULLIF(b.tasa_dia, 0), 2) AS PRECIO_USD, 
            FORMAT(a.precio_unidad, 2) AS PRECIO_UNIDAD, 
            FORMAT(a.precio, 2) AS PRECIO, 
            b.moneda AS MONEDA,
            FORMAT(b.tasa_dia, 2) AS TASA  
        FROM entradas_salidas AS a 
        JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
        JOIN cliente AS c ON c.id = b.cliente 
        LEFT OUTER JOIN articulo AS d ON d.id = a.articulo 
        WHERE a.tipo_documento = 'TDCFCV' AND b.estatus = 'PROCESADO' 
          AND b.fecha BETWEEN '{$fDesde} 00:00:00' AND '{$fHasta} 23:59:59' 
        ORDER BY b.fecha;";

        echo ejecutarYMostrarTabla2024($sql);
        ?>
        </div>
    </div>
    <?php } ?>

    <?php if ($xTipo === "" || $xTipo === "GIROS") { ?>
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-info text-white"><b>Total Giros</b></div>
        <div class="card-body p-0">
        <?php
        $sql = "SELECT
            'GIROS' AS TIPO, 
            c.nombre AS CLIENTE, 
            DATE_FORMAT(b.fecha, '%d/%m/%Y') AS FECHA, 
            b.nro_documento AS DOCUMENTO, 
            d.principio_activo AS ARTICULO, 
            FORMAT(ABS(a.cantidad_movimiento), 0) AS UNIDADES, 
            FORMAT(a.costo_unidad, 2) AS COSTO_UNIDAD_USD,
            FORMAT(a.costo, 2) AS COSTO_USD, 
            FORMAT(a.precio/NULLIF(b.tasa_dia, 0), 2) AS PRECIO_USD, 
            FORMAT(a.precio_unidad, 2) AS PRECIO_UNIDAD, 
            FORMAT(a.precio, 2) AS PRECIO, 
            b.moneda AS MONEDA,
            FORMAT(b.tasa_dia, 2) AS TASA  
        FROM entradas_salidas AS a 
        JOIN salidas AS b ON b.id = a.id_documento AND b.tipo_documento = a.tipo_documento 
        JOIN cliente AS c ON c.id = b.cliente 
        LEFT OUTER JOIN articulo AS d ON d.id = a.articulo 
        WHERE a.tipo_documento = 'TDCNET' AND b.estatus = 'PROCESADO' AND IFNULL(b.id_documento_padre, 0) = 0 
          AND b.fecha BETWEEN '{$fDesde} 00:00:00' AND '{$fHasta} 23:59:59' 
        ORDER BY b.fecha;";

        echo ejecutarYMostrarTabla2024($sql);
        ?>
        </div>
    </div>
    <?php } ?>
</div>

<script>
function GoBack(xfd, xfh) {
    window.location.href = "FinancieroResumen?FechaDesde=" + encodeURIComponent(xfd) + "&FechaHasta=" + encodeURIComponent(xfh);
}
</script>
</body>
</html>