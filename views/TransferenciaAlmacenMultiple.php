<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaAlmacenMultiple = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

/**
 * Custom File: TransferenciaAlmacenMultiple.php
 * Objetivo: transferir varios artículos/lotes entre almacenes en una sola operación lógica.
 * Requiere en Global Code PHPMaker:
 *   ReservarConsecutivoDocumento($tipo_documento, $serie = '')
 */

function TAmSql($value) {
    return AdjustSql(trim((string)$value));
}

function TAmJson($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function TAmFechaSql($fecha) {
    $fecha = trim((string)$fecha);
    return ($fecha == "" || $fecha == "0000-00-00") ? "1990-01-01" : $fecha;
}

function TAmGetParam($codigo, $default = '') {
    $codigo = TAmSql($codigo);
    $valor = ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '$codigo' LIMIT 1");
    return ($valor === null || $valor === false || $valor === '') ? $default : $valor;
}

function TAmExistencia($articulo, $lote, $fecha_vencimiento, $almacen) {
    $articulo = intval($articulo);
    $lote = TAmSql($lote);
    $fecha_vencimiento = TAmSql(TAmFechaSql($fecha_vencimiento));
    $almacen = TAmSql($almacen);
    $tipo_documento_050 = TAmSql(TAmGetParam('050', 'TDCNET'));

    $sql = "
        SELECT IFNULL(SUM(x.cantidad_movimiento), 0) AS existencia
        FROM (
            SELECT a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento
            JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO')
                    OR
                    (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                  )
              AND a.articulo = $articulo
              AND IFNULL(a.lote, '') = '$lote'
              AND IFNULL(a.fecha_vencimiento, '1990-01-01') = '$fecha_vencimiento'
              AND a.almacen = '$almacen'
              AND a.newdata = 'S'

            UNION ALL

            SELECT a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento
            JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCPDV' AND b.estatus = 'NUEVO')
                    OR
                    (a.tipo_documento IN ('$tipo_documento_050', 'TDCASA') AND b.estatus <> 'ANULADO')
                  )
              AND a.articulo = $articulo
              AND IFNULL(a.lote, '') = '$lote'
              AND IFNULL(a.fecha_vencimiento, '1990-01-01') = '$fecha_vencimiento'
              AND a.almacen = '$almacen'
              AND a.newdata = 'S'
        ) AS x";

    return floatval(ExecuteScalar($sql));
}

function TAmBuscarArticulos($q) {
    $q = TAmSql($q);
    $tipo_documento_050 = TAmSql(TAmGetParam('050', 'TDCNET'));

    if ($q == '') {
        TAmJson(["success" => false, "message" => "Debe indicar texto de búsqueda."]);
    }

    $sql = "
        SELECT
            z.articulo,
            ar.codigo,
            ar.codigo_de_barra,
            TRIM(CONCAT(IFNULL(ar.nombre_comercial,''), ' ', IFNULL(ar.principio_activo,''), ' ', IFNULL(ar.presentacion,''))) AS descripcion,
            IFNULL(f.nombre, '') AS fabricante,
            z.lote,
            z.fecha_vencimiento,
            DATE_FORMAT(NULLIF(z.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha_fmt,
            z.almacen,
            al.descripcion AS almacen_nombre,
            SUM(z.cantidad_movimiento) AS existencia
        FROM (
            SELECT a.articulo, IFNULL(a.lote,'') AS lote, IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, a.almacen, a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento
            JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO')
                    OR
                    (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                  )
              AND a.newdata = 'S'

            UNION ALL

            SELECT a.articulo, IFNULL(a.lote,'') AS lote, IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, a.almacen, a.cantidad_movimiento
            FROM entradas_salidas AS a
            JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento
            JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
            WHERE (
                    (a.tipo_documento = 'TDCPDV' AND b.estatus = 'NUEVO')
                    OR
                    (a.tipo_documento IN ('$tipo_documento_050', 'TDCASA') AND b.estatus <> 'ANULADO')
                  )
              AND a.newdata = 'S'
        ) AS z
        JOIN articulo AS ar ON ar.id = z.articulo
        LEFT JOIN fabricante AS f ON f.Id = ar.fabricante
        LEFT JOIN almacen AS al ON al.codigo = z.almacen
        WHERE (
            ar.codigo LIKE '%$q%'
            OR ar.codigo_de_barra LIKE '%$q%'
            OR ar.nombre_comercial LIKE '%$q%'
            OR ar.principio_activo LIKE '%$q%'
            OR ar.presentacion LIKE '%$q%'
        )
        GROUP BY z.articulo, ar.codigo, ar.codigo_de_barra, descripcion, fabricante, z.lote, z.fecha_vencimiento, z.almacen, al.descripcion
        HAVING existencia > 0
        ORDER BY descripcion, z.fecha_vencimiento, z.lote, z.almacen
        LIMIT 100";

    $rows = ExecuteRows($sql);
    TAmJson(["success" => true, "data" => $rows ?: []]);
}

function TAmProcesarTransferencia() {
    $raw = $_POST['items'] ?? '[]';
    $items = json_decode($raw, true);

    if (!is_array($items) || count($items) == 0) {
        TAmJson(["success" => false, "message" => "Debe agregar al menos un artículo a transferir."]);
    }

    if (!function_exists(__NAMESPACE__ . "\\ReservarConsecutivoDocumento")) {
        TAmJson(["success" => false, "message" => "No existe la función ReservarConsecutivoDocumento() en Global Code."]);
    }

    $username = CurrentUserName();
    $cliente = intval(ExecuteScalar("SELECT id FROM cliente WHERE nombre LIKE '%ajuste%' LIMIT 1"));
    $proveedor = intval(ExecuteScalar("SELECT id FROM proveedor WHERE nombre LIKE '%ajuste%' LIMIT 1"));

    if ($cliente <= 0) {
        TAmJson(["success" => false, "message" => "No se encontró cliente de ajuste."]);
    }

    if ($proveedor <= 0) {
        TAmJson(["success" => false, "message" => "No se encontró proveedor de ajuste."]);
    }

    $detalle = [];
    $total_unidades = 0;

    foreach ($items as $idx => $item) {
        $fila = $idx + 1;
        $articulo = intval($item['articulo'] ?? 0);
        $lote = trim((string)($item['lote'] ?? ''));
        $fecha = TAmFechaSql($item['fecha_vencimiento'] ?? '');
        $almacen_origen = trim((string)($item['almacen_origen'] ?? ''));
        $almacen_destino = trim((string)($item['almacen_destino'] ?? ''));
        $cantidad = floatval($item['cantidad'] ?? 0);

        if ($articulo <= 0) {
            TAmJson(["success" => false, "message" => "Artículo inválido en la fila $fila."]);
        }

        if ($almacen_origen == '' || $almacen_destino == '') {
            TAmJson(["success" => false, "message" => "Debe indicar almacén origen y destino en la fila $fila."]);
        }

        if ($almacen_origen == $almacen_destino) {
            TAmJson(["success" => false, "message" => "El almacén destino no puede ser igual al origen en la fila $fila."]);
        }

        if ($cantidad <= 0) {
            TAmJson(["success" => false, "message" => "Cantidad inválida en la fila $fila."]);
        }

        $destinoOk = intval(ExecuteScalar("SELECT COUNT(*) FROM almacen WHERE codigo = '" . TAmSql($almacen_destino) . "' AND movimiento = 'S'"));
        if ($destinoOk <= 0) {
            TAmJson(["success" => false, "message" => "El almacén destino no es válido en la fila $fila."]);
        }

        $existencia = TAmExistencia($articulo, $lote, $fecha, $almacen_origen);
        if ($cantidad > $existencia) {
            TAmJson(["success" => false, "message" => "La fila $fila supera la existencia real disponible. Disponible: $existencia."]);
        }

        $rowArt = ExecuteRow("SELECT fabricante, ultimo_costo FROM articulo WHERE id = $articulo LIMIT 1");
        if (!$rowArt) {
            TAmJson(["success" => false, "message" => "No se encontró el artículo de la fila $fila."]);
        }

        $detalle[] = [
            "articulo" => $articulo,
            "fabricante" => intval($rowArt['fabricante'] ?? 0),
            "costo" => floatval($rowArt['ultimo_costo'] ?? 0),
            "lote" => $lote,
            "fecha_vencimiento" => $fecha,
            "almacen_origen" => $almacen_origen,
            "almacen_destino" => $almacen_destino,
            "cantidad" => $cantidad
        ];

        $total_unidades += $cantidad;
    }

    $salida_id = 0;
    $entrada_id = 0;

    try {
        Execute("START TRANSACTION");

        $nro_salida = ReservarConsecutivoDocumento("TDCASA", "DOC");
        $nro_entrada = ReservarConsecutivoDocumento("TDCAEN", "DOC");

        $nro_salida = str_pad(intval($nro_salida), 7, "0", STR_PAD_LEFT);
        $nro_entrada = str_pad(intval($nro_entrada), 7, "0", STR_PAD_LEFT);

        $fecha_actual = date("Y-m-d H:i:s");
        $usuario = TAmSql($username);

        $sql = "INSERT INTO salidas
            (id, tipo_documento, username, fecha, cliente, nro_documento, doc_afectado, nota, estatus, documento, nombre, moneda, unidades)
            VALUES
            (NULL, 'TDCASA', '$usuario', '$fecha_actual', $cliente, '$nro_salida', '$nro_entrada', 'TRANSFERENCIA ENTRE ALMACENES', 'PROCESADO', 'TR', 'TRANSFERENCIA', 'Bs', $total_unidades)";
        Execute($sql);
        $salida_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));

        $sql = "INSERT INTO entradas
            (id, tipo_documento, username, fecha, proveedor, nro_documento, doc_afectado, nota, estatus, documento, moneda, unidades)
            VALUES
            (NULL, 'TDCAEN', '$usuario', '$fecha_actual', $proveedor, '$nro_entrada', '$nro_salida', 'TRANSFERENCIA ENTRE ALMACENES', 'PROCESADO', 'TR', 'Bs', $total_unidades)";
        Execute($sql);
        $entrada_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));

        foreach ($detalle as $item) {
            $articulo = intval($item['articulo']);
            $fabricante = intval($item['fabricante']);
            $cantidad = floatval($item['cantidad']);
            $costo_unidad = floatval($item['costo']);
            $costo_total = $cantidad * $costo_unidad;
            $lote = TAmSql($item['lote']);
            $fecha = TAmSql($item['fecha_vencimiento']);
            $almacen_origen = TAmSql($item['almacen_origen']);
            $almacen_destino = TAmSql($item['almacen_destino']);

            $sql = "INSERT INTO entradas_salidas
                (id, tipo_documento, id_documento, fabricante, articulo, almacen, lote, fecha_vencimiento,
                 cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento,
                 costo_unidad, costo, newdata)
                VALUES
                (NULL, 'TDCASA', $salida_id, $fabricante, $articulo, '$almacen_origen', '$lote', '$fecha',
                 $cantidad, 'UDM001', 1, (-1) * $cantidad, $costo_unidad, $costo_total, 'S')";
            Execute($sql);

            $sql = "INSERT INTO entradas_salidas
                (id, tipo_documento, id_documento, fabricante, articulo, almacen, lote, fecha_vencimiento,
                 cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento,
                 costo_unidad, costo, newdata)
                VALUES
                (NULL, 'TDCAEN', $entrada_id, $fabricante, $articulo, '$almacen_destino', '$lote', '$fecha',
                 $cantidad, 'UDM001', 1, $cantidad, $costo_unidad, $costo_total, 'S')";
            Execute($sql);
        }

        Execute("INSERT INTO audittrail
            (id, datetime, script, `user`, `action`, `table`, `field`, keyvalue, oldvalue, newvalue)
            VALUES
            (NULL, '$fecha_actual', 'Transferencia múltiple entre almacenes Salida $nro_salida / Entrada $nro_entrada', '$usuario', 'I', 'transferencia_almacen_multiple', 'id', '$salida_id', '', '$entrada_id')");

        Execute("COMMIT");

        TAmJson([
            "success" => true,
            "message" => "Transferencia procesada correctamente.",
            "salida_id" => $salida_id,
            "entrada_id" => $entrada_id,
            "nro_salida" => $nro_salida,
            "nro_entrada" => $nro_entrada
        ]);

    } catch (\Throwable $e) {
        Execute("ROLLBACK");

        // Rollback manual para MyISAM
        if ($entrada_id > 0) {
            Execute("DELETE FROM entradas_salidas WHERE tipo_documento = 'TDCAEN' AND id_documento = $entrada_id");
            Execute("DELETE FROM entradas WHERE id = $entrada_id AND tipo_documento = 'TDCAEN'");
        }

        if ($salida_id > 0) {
            Execute("DELETE FROM entradas_salidas WHERE tipo_documento = 'TDCASA' AND id_documento = $salida_id");
            Execute("DELETE FROM salidas WHERE id = $salida_id AND tipo_documento = 'TDCASA'");
        }

        TAmJson(["success" => false, "message" => $e->getMessage()]);
    }
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
if ($action == 'buscar') {
    TAmBuscarArticulos($_POST['q'] ?? '');
}
if ($action == 'procesar') {
    TAmProcesarTransferencia();
}

$almacenes = ExecuteRows("SELECT codigo, descripcion FROM almacen WHERE movimiento = 'S' ORDER BY descripcion");
?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <h1 class="h4 text-secondary mb-0">Transferencia Múltiple entre Almacenes</h1>
        <a class="btn btn-sm btn-info text-white" href="TransferenciaArticulo">
            <i class="fa fa-arrow-left"></i> Transferencia por Artículo
        </a>
    </div>

    <div class="card shadow-sm mb-3 border-primary">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-9">
                    <label class="form-label small fw-bold text-primary mb-1">Buscar artículo por nombre, código o código de barra</label>
                    <input type="text" id="txtBuscarTransferencia" class="form-control form-control-sm" placeholder="Escriba parte del artículo, código o código de barra">
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-primary w-100" onclick="BuscarTransferenciaMultiple();">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </div>
            </div>
            <div id="ResultadoBusquedaTransferencia" class="mt-3"></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
            <span class="small fw-bold text-secondary">Artículos a transferir</span>
            <button type="button" class="btn btn-sm btn-success" onclick="ProcesarTransferenciaMultiple();">
                <i class="fa fa-check"></i> Procesar Transferencia
            </button>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped align-middle" id="tablaTransferenciaMultiple" style="font-size:0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Artículo</th>
                            <th>Lote</th>
                            <th>Venc.</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th class="text-end">Disponible</th>
                            <th style="width:100px;" class="text-end">Cantidad</th>
                            <th style="width:50px;"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const almacenesTransferencia = <?= json_encode($almacenes ?: [], JSON_UNESCAPED_UNICODE) ?>;
let cestaTransferencia = [];

function HtmlEscapeTransferencia(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function BuscarTransferenciaMultiple() {
    const q = $('#txtBuscarTransferencia').val().trim();
    if (q === '') {
        ew.alert('Debe indicar un texto para buscar.');
        return;
    }

    $('#ResultadoBusquedaTransferencia').html('<div class="text-center p-3 text-secondary"><i class="fa fa-spinner fa-spin"></i> Buscando...</div>');

    $.ajax({
        url: window.location.href,
        type: 'POST',
        dataType: 'json',
        data: { action: 'buscar', q: q },
        success: function(resp) {
            if (!resp || !resp.success) {
                $('#ResultadoBusquedaTransferencia').html('<div class="alert alert-danger py-2">' + HtmlEscapeTransferencia(resp.message || 'Error buscando.') + '</div>');
                return;
            }
            PintarResultadosTransferencia(resp.data || []);
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            $('#ResultadoBusquedaTransferencia').html('<div class="alert alert-danger py-2">Error de comunicación.</div>');
        }
    });
}

function PintarResultadosTransferencia(rows) {
    if (rows.length === 0) {
        $('#ResultadoBusquedaTransferencia').html('<div class="alert alert-warning py-2">No se encontraron existencias para esa búsqueda.</div>');
        return;
    }

    let html = '<div class="table-responsive"><table class="table table-sm table-bordered table-hover align-middle" style="font-size:0.85rem;">';
    html += '<thead class="table-light"><tr>';
    html += '<th>Código</th><th>Artículo</th><th>Lote</th><th>Venc.</th><th>Almacén Origen</th><th class="text-end">Disponible</th><th>Destino</th><th class="text-end" style="width:100px;">Cantidad</th><th style="width:50px;"></th>';
    html += '</tr></thead><tbody>';

    rows.forEach(function(r, idx) {
        const selectDestino = CrearSelectDestinoTransferencia('destino_bus_' + idx, r.almacen);
        html += '<tr>';
        html += '<td>' + HtmlEscapeTransferencia(r.codigo) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.descripcion) + '<br><small class="text-muted">' + HtmlEscapeTransferencia(r.fabricante) + '</small></td>';
        html += '<td>' + HtmlEscapeTransferencia(r.lote) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.fecha_fmt || '') + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.almacen) + ' - ' + HtmlEscapeTransferencia(r.almacen_nombre) + '</td>';
        html += '<td class="text-end">' + parseFloat(r.existencia).toFixed(0) + '</td>';
        html += '<td>' + selectDestino + '</td>';
        html += '<td><input type="number" id="cantidad_bus_' + idx + '" class="form-control form-control-sm text-end" min="1" max="' + parseFloat(r.existencia) + '"></td>';
        html += '<td class="text-center"><button type="button" class="btn btn-sm btn-success" onclick="AgregarACestaTransferencia(' + idx + ')"><i class="fa fa-plus-circle"></i></button></td>';
        html += '</tr>';
    });

    html += '</tbody></table></div>';
    $('#ResultadoBusquedaTransferencia').html(html);
    window.__resultadosTransferencia = rows;
}

function CrearSelectDestinoTransferencia(id, almacenOrigen) {
    let html = '<select id="' + id + '" class="form-select form-select-sm">';
    html += '<option value="">Seleccione...</option>';

    almacenesTransferencia.forEach(function(a) {
        if (a.codigo !== almacenOrigen) {
            html += '<option value="' + HtmlEscapeTransferencia(a.codigo) + '">' + HtmlEscapeTransferencia(a.descripcion) + '</option>';
        }
    });

    html += '</select>';
    return html;
}

function AgregarACestaTransferencia(idx) {
    const rows = window.__resultadosTransferencia || [];
    const r = rows[idx];
    if (!r) return;

    const cantidad = parseFloat($('#cantidad_bus_' + idx).val() || 0);
    const destino = $('#destino_bus_' + idx).val();
    const disponible = parseFloat(r.existencia || 0);

    if (destino === '') {
        ew.alert('Debe seleccionar almacén destino.');
        return;
    }

    if (cantidad <= 0) {
        ew.alert('Debe indicar cantidad mayor a cero.');
        return;
    }

    if (cantidad > disponible) {
        ew.alert('La cantidad supera la existencia disponible.');
        return;
    }

    const key = r.articulo + '|' + (r.lote || '') + '|' + (r.fecha_vencimiento || '1990-01-01') + '|' + r.almacen + '|' + destino;
    const existe = cestaTransferencia.findIndex(x => x.key === key);

    if (existe >= 0) {
        const nuevaCantidad = parseFloat(cestaTransferencia[existe].cantidad) + cantidad;
        if (nuevaCantidad > disponible) {
            ew.alert('La cantidad acumulada supera la existencia disponible.');
            return;
        }
        cestaTransferencia[existe].cantidad = nuevaCantidad;
    } else {
        cestaTransferencia.push({
            key: key,
            articulo: parseInt(r.articulo, 10),
            codigo: r.codigo,
            descripcion: r.descripcion,
            lote: r.lote || '',
            fecha_vencimiento: r.fecha_vencimiento || '1990-01-01',
            almacen_origen: r.almacen,
            almacen_origen_nombre: r.almacen_nombre,
            almacen_destino: destino,
            almacen_destino_nombre: $('#destino_bus_' + idx + ' option:selected').text(),
            disponible: disponible,
            cantidad: cantidad
        });
    }

    PintarCestaTransferencia();
}

function PintarCestaTransferencia() {
    let html = '';

    cestaTransferencia.forEach(function(r, idx) {
        html += '<tr>';
        html += '<td>' + HtmlEscapeTransferencia(r.codigo) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.descripcion) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.lote) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.fecha_vencimiento) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.almacen_origen) + ' - ' + HtmlEscapeTransferencia(r.almacen_origen_nombre) + '</td>';
        html += '<td>' + HtmlEscapeTransferencia(r.almacen_destino) + ' - ' + HtmlEscapeTransferencia(r.almacen_destino_nombre) + '</td>';
        html += '<td class="text-end">' + parseFloat(r.disponible).toFixed(0) + '</td>';
        html += '<td><input type="number" class="form-control form-control-sm text-end" min="1" max="' + r.disponible + '" value="' + r.cantidad + '" onchange="ActualizarCantidadCestaTransferencia(' + idx + ', this.value)"></td>';
        html += '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="EliminarCestaTransferencia(' + idx + ')"><i class="fa fa-trash"></i></button></td>';
        html += '</tr>';
    });

    if (html === '') {
        html = '<tr><td colspan="9" class="text-center text-muted">No hay artículos agregados.</td></tr>';
    }

    $('#tablaTransferenciaMultiple tbody').html(html);
}

function ActualizarCantidadCestaTransferencia(idx, value) {
    const cantidad = parseFloat(value || 0);
    if (!cestaTransferencia[idx]) return;

    if (cantidad <= 0 || cantidad > parseFloat(cestaTransferencia[idx].disponible)) {
        ew.alert('Cantidad inválida.');
        PintarCestaTransferencia();
        return;
    }

    cestaTransferencia[idx].cantidad = cantidad;
}

function EliminarCestaTransferencia(idx) {
    cestaTransferencia.splice(idx, 1);
    PintarCestaTransferencia();
}

function ProcesarTransferenciaMultiple() {
    if (cestaTransferencia.length === 0) {
        ew.alert('Debe agregar al menos un artículo.');
        return;
    }

    if (!confirm('¿Está seguro de procesar esta transferencia múltiple?')) {
        return;
    }

    $.ajax({
        url: window.location.href,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'procesar',
            items: JSON.stringify(cestaTransferencia)
        },
        success: function(resp) {
            if (resp && resp.success) {
                ew.alert(resp.message + ' Salida: ' + resp.nro_salida + ' / Entrada: ' + resp.nro_entrada);
                window.location.href = 'TransferenciaResultado?salida=' + resp.salida_id + '&entrada=' + resp.entrada_id;
            } else {
                ew.alert(resp.message || 'No se pudo procesar la transferencia.');
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            ew.alert('Error de comunicación al procesar transferencia.');
        }
    });
}

$(document).ready(function() {
    PintarCestaTransferencia();

    $('#txtBuscarTransferencia').on('keypress', function(e) {
        if (e.which === 13) {
            BuscarTransferenciaMultiple();
            e.preventDefault();
        }
    });
});
</script>
<?= GetDebugMessage() ?>
