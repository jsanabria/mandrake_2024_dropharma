<?php

namespace PHPMaker2024\mandrake;

// Page object
$NotaDeEntregaParcialProcesar = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = intval($_POST["id"]);
$items_seleccionados = $_POST["item_seleccionado"] ?? [];
$cantidades_despacho = $_POST["cantidad_despacho"] ?? [];

if (empty($items_seleccionados)) {
    header("Location: ViewOutTdcnetList");
    die();
}

// 1. Obtener la metadata esencial de la Nota de Entrega Original (Padre)
$sqlOriginal = "SELECT tipo_documento, cliente, moneda, id_documento_padre, asesor, documento, dias_credito, consignacion, nro_documento, estatus 
                FROM salidas WHERE id = $id;";
$meta = ExecuteRow($sqlOriginal);
$tipo = $meta["tipo_documento"];

$es_entrega_total_global = true; // Flag centinela

// 2. Obtener todos los renglones actuales de la Nota Origen para contrastar
$sqlDetalles = "SELECT id, articulo, cantidad_articulo, IFNULL(cantidad_entregada, 0) AS cantidad_entregada FROM entradas_salidas WHERE id_documento = $id AND tipo_documento = '$tipo';";
$detalles_db = ExecuteRows($sqlDetalles);

foreach ($detalles_db as $det) {
    $id_det = $det["id"];
    $pendiente_actual = intval($det["cantidad_articulo"]) - intval($det["cantidad_entregada"]);
    
    $unidades_a_despachar = 0;
    if (isset($items_seleccionados[$id_det])) {
        $unidades_a_despachar = intval($cantidades_despacho[$id_det]);
    }
    
    // Si lo que se va a despachar es menor al saldo remanente del renglón, es entrega parcial
    if ($unidades_a_despachar < $pendiente_actual) {
        $es_entrega_total_global = false;
    }
}

// Artículos únicos que necesitarán recalcular inventario al finalizar el proceso
$articulos_afectados = [];

// ==========================================
// CASO A: LA ENTREGA ES TOTAL (Checklist completo)
// ==========================================
if ($es_entrega_total_global) {
    
    foreach ($items_seleccionados as $idd) {
        $cant_despachada = intval($cantidades_despacho[$idd]);
        
        // Acumulamos el despacho en la nota controladora y cerramos sus indicadores
        $sqlUp = "UPDATE entradas_salidas 
                  SET cantidad_entregada = IFNULL(cantidad_entregada, 0) + $cant_despachada,
                      check_ne = 'S',
                      cantidad_por_entregar = cantidad_articulo - (IFNULL(cantidad_entregada, 0) + $cant_despachada)
                  WHERE id = $idd;";
        Execute($sqlUp);

        // Guardamos el id del artículo para actualizar el stock por consistencia
        $id_art = ExecuteScalar("SELECT articulo FROM entradas_salidas WHERE id = $idd;");
        if ($id_art) {
            $articulos_afectados[$id_art] = true;
        }
    }
    
    // Cambiamos el estatus de la Madre a PROCESADO y cerramos su ciclo
    // $sqlCabecera = "UPDATE salidas SET entregado = 'S', estatus = 'PROCESADO' WHERE id = $id;";
    $sqlCabecera = "UPDATE salidas SET entregado = 'S' WHERE id = $id;";
    Execute($sqlCabecera);

} 
// ==========================================
// CASO B: LA ENTREGA ES PARCIAL (Fraccionamiento de Documentos)
// ==========================================
else {
    
    // A) Generar el número correlativo para la Nota de Entrega Hija / Despacho Físico Realizado
    $sqlCorrelativo = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = 'TDCNET';";
    $rowCorr = ExecuteRow($sqlCorrelativo);
    $consecutivo = intval($rowCorr["consecutivo"]) + 1; 
    $nro_documento_hijo = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

    // B) Insertamos la cabecera del documento Hijo (Nace cerrado 'S', estatus 'PROCESADO')
    $sqlInsertHijo = "INSERT INTO salidas
                        (id, tipo_documento, username, fecha, cliente, nro_documento,
                        nota, estatus, moneda, id_documento_padre, asesor, documento, 
                        dias_credito, consignacion, doc_afectado, entregado)
                      VALUES 
                        (NULL, '$tipo', '" . CurrentUserName() . "', NOW(), " . $meta['cliente'] . ", '$nro_documento_hijo',
                        'Despacho Parcial - Ref. Origen #" . $meta['nro_documento'] . "', 'PROCESADO', '" . $meta['moneda'] . "', 
                        " . ($meta['id_documento_padre'] ?? 'NULL') . ", " . ($meta['asesor'] ?? 'NULL') . ", '" . $meta['documento'] . "', 
                        " . ($meta['dias_credito'] ?? '0') . ", '" . $meta['consignacion'] . "', '" . $meta['nro_documento'] . "', 'S');";
    Execute($sqlInsertHijo);
    $factura_id_hijo = ExecuteScalar("SELECT LAST_INSERT_ID();");

    // C) Generar el número correlativo para la NUEVA NOTA PADRE (El saldo que se queda en la tienda)
    $consecutivo_rem = $consecutivo + 1;
    $nro_documento_remanente = str_pad($consecutivo_rem, 7, "0", STR_PAD_LEFT);

    // D) Insertamos la cabecera de la Nueva Nota Padre (Nace como Antes 'NUEVO' Ahora $meta['estatus'], entregado 'N' para que se pueda volver a despachar)
    $sqlInsertNuevoPadre = "INSERT INTO salidas
                        (id, tipo_documento, username, fecha, cliente, nro_documento,
                        nota, estatus, moneda, id_documento_padre, asesor, documento, 
                        dias_credito, consignacion, doc_afectado, entregado)
                      VALUES 
                        (NULL, '$tipo', '" . CurrentUserName() . "', NOW(), " . $meta['cliente'] . ", '$nro_documento_remanente',
                        'Saldo Pendiente - Ex Ref. #" . $meta['nro_documento'] . "', '" . $meta['estatus'] . "', '" . $meta['moneda'] . "', 
                        " . ($meta['id_documento_padre'] ?? 'NULL') . ", " . ($meta['asesor'] ?? 'NULL') . ", '" . $meta['documento'] . "', 
                        " . ($meta['dias_credito'] ?? '0') . ", '" . $meta['consignacion'] . "', '" . $meta['nro_documento'] . "', 'N');";
    Execute($sqlInsertNuevoPadre);
    $factura_id_remanente = ExecuteScalar("SELECT LAST_INSERT_ID();");

    $tiene_remanente = false;

    // E) Procesamos los renglones basados en la lectura completa de la Nota Origen vieja
    foreach ($detalles_db as $det) {
        $idd = $det["id"];
        $id_articulo = intval($det["articulo"]);
        $cant_original_linea = intval($det["cantidad_articulo"]);
        
        // Cuánto se lleva el cliente en este viaje
        $cant_despachada = isset($items_seleccionados[$idd]) ? intval($cantidades_despacho[$idd]) : 0;
        
        // Cuánto se queda guardado en la tienda
        $saldo_pendiente = $cant_original_linea - $cant_despachada;

        // Registramos el artículo como afectado para correr el SP al final
        $articulos_afectados[$id_articulo] = true;

        // --- 1. Si hay despacho real, se inserta en la NOTA HIJA (Que se lleva el cliente) ---
        if ($cant_despachada > 0) {
            $sqlInsertHijaLinea = "INSERT INTO entradas_salidas
                                    (id, tipo_documento, id_documento, fabricante, articulo, almacen, 
                                    cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
                                    cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
                                    costo_unidad, costo, cantidad_movimiento_consignacion, id_consignacion,
                                    id_compra, descuento, precio_unidad_sin_desc, check_ne, cantidad_entregada, cantidad_por_entregar, newdata)
                                   SELECT 
                                    NULL, '$tipo', '$factura_id_hijo', a.fabricante, a.articulo, a.almacen, 
                                    $cant_despachada, a.articulo_unidad_medida, a.cantidad_unidad_medida, 
                                    (-1 * $cant_despachada), a.lote, a.fecha_vencimiento, a.precio_unidad, ($cant_despachada * a.precio_unidad), a.alicuota,
                                    a.costo_unidad, (a.costo_unidad * $cant_despachada), 0, a.id, a.id_compra, a.descuento, a.precio_unidad_sin_desc, 'S', $cant_despachada, 0, 'S'  
                                   FROM entradas_salidas AS a 
                                   WHERE a.id = $idd;";
            Execute($sqlInsertHijaLinea);
        }

        // --- 2. Si queda mercancía en la tienda, se inserta en la NUEVA NOTA PADRE ---
        if ($saldo_pendiente > 0) {
            $tiene_remanente = true;
            $sqlInsertRemanenteLinea = "INSERT INTO entradas_salidas
                                    (id, tipo_documento, id_documento, fabricante, articulo, almacen, 
                                    cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, 
                                    cantidad_movimiento, lote, fecha_vencimiento, precio_unidad, precio, alicuota,
                                    costo_unidad, costo, cantidad_movimiento_consignacion, id_consignacion,
                                    id_compra, descuento, precio_unidad_sin_desc, check_ne, cantidad_entregada, cantidad_por_entregar, newdata)
                                   SELECT 
                                    NULL, '$tipo', '$factura_id_remanente', a.fabricante, a.articulo, a.almacen, 
                                    $saldo_pendiente, a.articulo_unidad_medida, a.cantidad_unidad_medida, 
                                    (-1 * $saldo_pendiente), a.lote, a.fecha_vencimiento, a.precio_unidad, ($saldo_pendiente * a.precio_unidad), a.alicuota,
                                    a.costo_unidad, (a.costo_unidad * $saldo_pendiente), 0, a.id, a.id_compra, a.descuento, a.precio_unidad_sin_desc, 'N', 0, $saldo_pendiente, 'S'  
                                   FROM entradas_salidas AS a 
                                   WHERE a.id = $idd;";
            Execute($sqlInsertRemanenteLinea);
        }

        // --- 3. NEUTRALIZACIÓN DE LA NOTA PADRE ORIGINAL (VIEJA) ---
        // Al colocar newdata = 'N' obligamos al SP sp_onhand_item a ignorar este viejo registro por completo.
        Execute("UPDATE entradas_salidas SET newdata = 'N', check_ne = 'S', cantidad_movimiento = 0 WHERE id = $idd;");
    }

    // F) Si por casualidad no quedó remanente en ningún ítem, eliminamos la cabecera remanente vacía
    if (!$tiene_remanente) {
        Execute("DELETE FROM salidas WHERE id = $factura_id_remanente;");
    } else {
        // Cuadrar totales financieros de la NUEVA NOTA PADRE REMANENTE
        $sqlTotalesRem = "SELECT SUM(precio) AS precio, SUM((precio * (IFNULL(alicuota,0)/100))) AS iva, SUM(precio) + SUM((precio * (IFNULL(alicuota,0)/100))) AS total FROM entradas_salidas WHERE tipo_documento = '$tipo' AND id_documento = '$factura_id_remanente'"; 
        $totRem = ExecuteRow($sqlTotalesRem); 
        
        Execute("UPDATE salidas 
                  SET monto_total = " . floatval($totRem["precio"]) . ", 
                      iva = " . floatval($totRem["iva"]) . ", 
                      total = " . floatval($totRem["total"]) . ", 
                      unidades = (SELECT ABS(SUM(cantidad_movimiento)) FROM entradas_salidas WHERE id_documento = $factura_id_remanente AND tipo_documento = '$tipo') 
                  WHERE id = '$factura_id_remanente';");
    }

    // G) Cuadrar totales financieros de la Nota HIJA (Despachada)
    $sqlTotalesHijo = "SELECT SUM(precio) AS precio, SUM((precio * (IFNULL(alicuota,0)/100))) AS iva, SUM(precio) + SUM((precio * (IFNULL(alicuota,0)/100))) AS total FROM entradas_salidas WHERE tipo_documento = '$tipo' AND id_documento = '$factura_id_hijo'"; 
    $totHijo = ExecuteRow($sqlTotalesHijo); 
    
    Execute("UPDATE salidas 
              SET monto_total = " . floatval($totHijo["precio"]) . ", 
                  iva = " . floatval($totHijo["iva"]) . ", 
                  total = " . floatval($totHijo["total"]) . ", 
                  unidades = (SELECT ABS(SUM(cantidad_movimiento)) FROM entradas_salidas WHERE id_documento = $factura_id_hijo AND tipo_documento = '$tipo') 
              WHERE id = '$factura_id_hijo';");

    // H) Anular la Nota Padre VIEJA en el histórico de cabeceras para que no afecte reportes de cuentas por cobrar o ventas
    Execute("UPDATE salidas SET entregado = 'S', estatus = 'ANULADO', nota = CONCAT(nota, ' - MUTADO POR DESPACHO PARCIAL') WHERE id = $id;");
}

// ==========================================
// F) RECALCULO DE EXISTENCIAS REALES
// ==========================================
if (!empty($articulos_afectados)) {
    foreach (array_keys($articulos_afectados) as $articulo_id) {
        Execute("CALL sp_onhand_item(" . intval($articulo_id) . ");");
    }
}

// Redirección limpia al listado estándar manteniendo el contexto de tipo
header("Location: ViewOutTdcnetList");
die();
?>
<?= GetDebugMessage() ?>
