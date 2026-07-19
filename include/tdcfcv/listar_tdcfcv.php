<?php

namespace PHPMaker2024\mandrake;

require_once __DIR__ . "/tdcfcv_bootstrap.php";

$pedido = TdcfcvRequestInt("pedido");
$tipo_documento = "TDCFCV";
$lineByPage = 50;

function TdcfcvHtmlResponse(string $html): void
{
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($html, JSON_UNESCAPED_UNICODE);
    exit;
}

function TdcfcvH($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

if ($pedido <= 0) {
    TdcfcvHtmlResponse("");
}

$moneda = ExecuteScalar("
    SELECT valor1
    FROM parametro
    WHERE codigo = '006'
      AND valor2 = 'default'
    LIMIT 1
");

$moneda = $moneda ?: "Bs.";

$tasa = floatval(ExecuteScalar("
    SELECT tasa
    FROM tasa_usd
    WHERE moneda = 'USD'
    ORDER BY id DESC
    LIMIT 1
"));

$tasa = ($tasa <= 0 ? 1 : $tasa);

$rowSalida = ExecuteRow("
    SELECT
        cliente,
        IFNULL(id_documento_padre,0) AS id_documento_padre
    FROM salidas
    WHERE id = {$pedido}
      AND tipo_documento = '{$tipo_documento}'
    LIMIT 1
");

if (!$rowSalida) {
    TdcfcvHtmlResponse(
        '<div class="alert alert-warning mb-0">No se encontró el documento solicitado.</div>'
    );
}

$cliente = intval($rowSalida["cliente"]);
$idDocumentoPadre = intval($rowSalida["id_documento_padre"]);

if (!$cliente) {
    TdcfcvHtmlResponse(
        '<div class="alert alert-warning mb-0">No se encontró el documento solicitado.</div>'
    );
}

$cantidad = intval(ExecuteScalar("
    SELECT COUNT(z.id)
    FROM entradas_salidas AS z
    INNER JOIN articulo AS a ON a.id = z.articulo
    LEFT JOIN fabricante AS b ON b.Id = a.fabricante
    WHERE z.id_documento = {$pedido}
      AND z.tipo_documento = '{$tipo_documento}'
"));

if ($cantidad <= 0) {
    TdcfcvHtmlResponse(
        '<div class="alert alert-info mb-0">La cesta no tiene artículos cargados.</div>'
    );
}

$rows = ExecuteRows("
    SELECT
        a.id,
        z.id AS id_item,
        IFNULL(a.foto, '') AS foto,
        IFNULL(a.nombre_comercial, '') AS nombre_comercial,
        IFNULL(b.nombre, '') AS fabricante,
        IFNULL(a.principio_activo, '') AS principio_activo,
        IFNULL(a.presentacion, '') AS presentacion,
        IFNULL(z.precio_unidad_sin_desc, 0) AS precio_ful,
        IFNULL(z.cantidad_articulo, 0) AS cantidad,
        IFNULL(z.descuento, 0) AS descuento,
        IFNULL(z.descuento2, 0) AS descuento2,
        IFNULL(z.precio_unidad, 0) AS precio,
        IFNULL(z.precio, 0) AS total,
        IFNULL(z.lote, '') AS lote,
        IFNULL(z.fecha_vencimiento, '') AS fecha_vencimiento,
        IFNULL(z.almacen, '') AS almacen, IFNULL(a.codigo, '') AS codigo  
    FROM entradas_salidas AS z
    INNER JOIN articulo AS a ON a.id = z.articulo
    LEFT JOIN fabricante AS b ON b.Id = a.fabricante
    WHERE z.id_documento = {$pedido}
      AND z.tipo_documento = '{$tipo_documento}'
    ORDER BY a.principio_activo, a.presentacion
    LIMIT 0, {$lineByPage}
");

$html = '';

$html .= '<table class="table table-bordered table-hover table-striped table-sm">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th width="10%">&nbsp;</th>';
$html .= '<th width="20%">Art&iacute;culo</th>';
$html .= '<th width="10%" class="text-center">Cant.</th>';
$html .= '<th width="10%" class="text-center">Lote</th>';
$html .= '<th width="10%" class="text-center">Vence</th>';
$html .= '<th width="10%" class="text-center">Precio Full</th>';
$html .= '<th width="10%" class="text-center">% Desc.1</th>';
$html .= '<th width="10%" class="text-center">% Desc.2</th>';
$html .= '<th width="10%" class="text-center">Precio</th>';
$html .= '<th width="10%" class="text-center">Total</th>';
$html .= '<th width="10%" class="text-center">Agr/Eli</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$i = 1;
$url = './carpetacarga/';

foreach ($rows as $row) {
    $idArticulo = intval($row["id"]);
    $idItem = intval($row["id_item"]);

    $xCant = intval($row["cantidad"]);
    $xPrecioFull = floatval($row["precio_ful"]);
    $xDescuento = floatval($row["descuento"]);
    $xDescuento2 = floatval($row["descuento2"]);
    $xPrecio = floatval($row["precio"]);
    $xTotal = floatval($row["total"]);
    $xLote = (string)$row["lote"];
    $xVence = (string)$row["fecha_vencimiento"];

    $foto = TdcfcvH($row["foto"]);
    $nombreComercial = TdcfcvH($row["nombre_comercial"]);
    $principioActivo = TdcfcvH($row["principio_activo"]);
    $presentacion = TdcfcvH($row["presentacion"]);
    $fabricante = TdcfcvH($row["fabricante"]);
    $codigo_articulo = TdcfcvH($row["codigo"]);

    $disabled = ($xCant == 0 ? '' : ' disabled="disabled"');

    $html .= '<tr>';

    $html .= '<td>';
    if (trim($foto) !== '') {
        $html .= '<div class="thumbnail">';
        $html .= '<a href="' . $url . $foto . '" target="_blank">';
        $html .= '<img src="' . $url . $foto . '" alt="' . $nombreComercial . '" width="100" class="img-thumbnail">';
        $html .= '</a>';
        $html .= '</div>';
    }
    $html .= '</td>';

    $html .= '<td>';
    $html .= '<strong>' . $nombreComercial . '</strong><br>';
    $html .= '<small>' . $principioActivo . '</small><br>';
    $html .= '<small><i>' . $presentacion . '</i></small><br>';
    $html .= '<small><i style="display:inline-block; max-width: 100px; word-break: break-all;">Cod.:' . $codigo_articulo . '</i></small><br>';
    $html .= '<strong><small>Fabricante: ' . $fabricante . '</small></strong><br>';
    $html .= '<small>Unidad</small>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    // $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xCant == 0 ? '' : $xCant) . '" style="width: 80px;"' . $disabled . '>';
    $html .= '<input type="number" class="form-control"
                id="x' . $i . '_cantidad"
                name="x' . $i . '_cantidad"
                size="4"
                onkeyup="myCalc(' . $i . ');"
                onchange="myCalc(' . $i . ');"
                data-original-cantidad="' . $xCant . '"
                value="' . ($xCant == 0 ? '' : $xCant) . '"
                style="width: 80px;"' . $disabled . '>';
    $html .= '<input type="hidden" id="x' . $i . '_moneda" name="x' . $i . '_moneda" value="' . TdcfcvH($moneda) . '">';
    $html .= '<input type="hidden" id="x' . $i . '_articulo" name="x' . $i . '_articulo" value="' . $idArticulo . '">';
    $html .= '<input type="hidden"
                 id="x' . $i . '_id_item"
                 name="x' . $i . '_id_item"
                 value="' . $idItem . '">';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="text" class="form-control" id="x' . $i . '_lote" name="x' . $i . '_lote" size="4" value="' . TdcfcvH($xLote) . '" style="width: 100px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="date" class="form-control" id="x' . $i . '_vence" name="x' . $i . '_vence" size="4" value="' . TdcfcvH($xVence) . '" style="width: 140px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="text" class="form-control tdcfcv-autorizado" id="x' . $i . '_precioFull" name="x' . $i . '_precioFull" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xPrecioFull == 0 ? '' : number_format($xPrecioFull, 2, ".", "")) . '" style="width: 100px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="number" class="form-control tdcfcv-autorizado" id="x' . $i . '_descuento" name="x' . $i . '_descuento" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . $xDescuento . '" style="width: 60px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="number" class="form-control tdcfcv-autorizado" id="x' . $i . '_descuento2" name="x' . $i . '_descuento2" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . $xDescuento2 . '" style="width: 60px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="number" class="form-control" id="x' . $i . '_precio" name="x' . $i . '_precio" size="4" readonly="yes" value="' . ($xPrecio == 0 ? '' : number_format($xPrecio, 2, ".", "")) . '" style="width: 100px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<input type="number" class="form-control" id="x' . $i . '_total" name="x' . $i . '_total" size="4" readonly="yes" value="' . ($xTotal == 0 ? '' : number_format($xTotal, 2, ".", "")) . '" style="width: 120px;"' . $disabled . '>';
    $html .= '</td>';

    $html .= '<td class="text-center">';
    $html .= '<div id="x' . $i . '_contenedor_botones" class="d-flex justify-content-around">';
    $html .= '<span id="x' . $i . '_boton_edit">';
    $html .= '<i class="fa-solid fa-pencil text-warning" style="cursor:pointer;" title="Modificar" onclick="js:habilitarEdicion(' . $i . ', ' . $idItem . ')"></i>';
    $html .= '</span>';

    $html .= '<span id="x' . $i . '_boton_delete">';
    if ($idDocumentoPadre > 0) {
        $html .= '<i class="fa-solid fa-trash text-secondary"
                    style="cursor:not-allowed;opacity:.45;"
                    title="La factura proviene de una Orden de Entrega y no permite eliminar artículos."></i>';
    } else {
        $html .= '<i class="fa-solid fa-trash text-danger"
                    style="cursor:pointer;"
                    title="Eliminar"
                    onclick="js:eliminar(' . $i . ', ' . $idItem . ')"></i>';
    }

    $html .= '</span>';    
    $html .= '</div>';
    $html .= '</td>';

    $html .= '</tr>';

    $i++;
}

$html .= '<tr>';
$html .= '<td colspan="11"><center><b>Registros ' . ($i - 1) . ' de ' . $cantidad . '</b></center></td>';
$html .= '</tr>';

$html .= '</tbody>';
$html .= '</table>';

TdcfcvHtmlResponse($html);