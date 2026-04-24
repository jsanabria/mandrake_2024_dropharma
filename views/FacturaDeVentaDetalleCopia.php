<?php

namespace PHPMaker2024\mandrake;

// Page object
$FacturaDeVentaDetalleCopia = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_REQUEST["id"]; // ID de la factura original
$tipo_documento = $_REQUEST["tipo_documento"];
$documento = strtoupper(substr($_REQUEST["documento"], 0, 2)); // NC, ND o FC
$doc = $_REQUEST["doc"];

$username = CurrentUserName();
$estatus = "NUEVO";

// ---------------------------------------------------------
// 1. VALIDACIÓN DE EXCLUSIVIDAD (NC vs ND)
// ---------------------------------------------------------

// Buscamos si ya existen documentos asociados (NC o ND)
$sql_check = "SELECT documento FROM salidas WHERE doc_afe = $id AND tipo_documento = '$tipo_documento' LIMIT 1";
$doc_existente = ExecuteScalar($sql_check);

$error_msg = "";

if ($documento == "NC") {
    // Si voy a crear una NC, no debe existir una ND previa
    if ($doc_existente == "ND") {
        $error_msg = "No se puede crear una Nota de Crédito porque ya existe una Nota de Débito asociada a esta factura.";
    }
} elseif ($documento == "ND") {
    // Si voy a crear una ND, no debe existir NADA previo (ni NC ni otra ND)
    if ($doc_existente == "NC") {
        $error_msg = "No se puede crear una Nota de Débito porque ya existe una Nota de Crédito asociada a esta factura.";
    } elseif ($doc_existente == "ND") {
        $error_msg = "Ya existe una Nota de Débito para esta factura. No se permite crear más de una.";
    }
}

// Si hay error de exclusividad, mostrar pantalla
if ($error_msg != "") {
    mostrar_error_personalizado($error_msg);
    exit();
}

// ---------------------------------------------------------
// 2. VALIDACIÓN DE SALDO POR ARTÍCULOS (SOLO PARA NC)
// ---------------------------------------------------------
if ($documento == "NC") {
    $sql_items = "SELECT articulo, ABS(cantidad_movimiento) as cant_original FROM entradas_salidas WHERE id_documento = $id AND tipo_documento = '$tipo_documento'";
    $rs_items = ExecuteRows($sql_items);

    $total_articulos = count($rs_items);
    $articulos_agotados = 0;

    foreach ($rs_items as $item) {
        $articulo = $item['articulo'];
        $cant_original = $item['cant_original'];

        $sql_acumulado = "SELECT SUM(ABS(es.cantidad_movimiento)) 
                          FROM entradas_salidas es 
                          INNER JOIN salidas s ON es.id_documento = s.id AND es.tipo_documento = s.tipo_documento 
                          WHERE s.doc_afe = $id AND s.documento = 'NC' AND es.articulo = '$articulo'";
        $cant_devuelta = floatval(ExecuteScalar($sql_acumulado));

        if ($cant_devuelta >= $cant_original) {
            $articulos_agotados++;
        }
    }

    if ($articulos_agotados >= $total_articulos && $total_articulos > 0) {
        mostrar_error_personalizado("Todos los artículos de esta factura ya han sido devueltos en su totalidad.");
        exit();
    }
}

// ---------------------------------------------------------
// 3. PROCESO DE INSERCIÓN (CABECERA)
// ---------------------------------------------------------
if ($documento == "FC") {
    $sql = "INSERT INTO salidas (tipo_documento, username, documento, fecha, cliente, monto_total, alicuota_iva, iva, total, nota, estatus, asesor, moneda, id_documento_padre, tasa_dia, monto_usd, dias_credito, fecha_bultos, fecha_despacho, asesor_asignado, descuento, descuento2)
            SELECT '$tipo_documento', '$username', '$documento', '" . date("Y-m-d H:i:s") . "', cliente, 0, 0, 0, 0, nota, '$estatus', asesor, moneda, id_documento_padre, tasa_dia, monto_usd, dias_credito, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', asesor_asignado, descuento, descuento2 
            FROM salidas WHERE id = $id;";
} else {
    $sql = "INSERT INTO salidas (tipo_documento, username, documento, nro_documento, doc_afectado, fecha, cliente, monto_total, alicuota_iva, iva, total, nota, estatus, asesor, moneda, tasa_dia, monto_usd, fecha_bultos, fecha_despacho, asesor_asignado, descuento, descuento2, doc_afe) 
            SELECT '$tipo_documento', '$username', '$documento', NULL, '$doc', '" . date("Y-m-d H:i:s") . "', cliente, 0, 0, 0, 0, nota, '$estatus', asesor, moneda, tasa_dia, monto_usd, '" . date("Y-m-d H:i:s") . "', '" . date("Y-m-d H:i:s") . "', asesor_asignado, descuento, descuento2, '$id' 
            FROM salidas WHERE id = $id;";
}
Execute($sql);
$newid = ExecuteScalar("SELECT LAST_INSERT_ID();");

// ---------------------------------------------------------
// 4. PROCESO DE INSERCIÓN (DETALLE)
// ---------------------------------------------------------
$sql_detalle = "INSERT INTO entradas_salidas (tipo_documento, id_documento, fabricante, articulo, almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, alicuota, precio_unidad, precio, lote, fecha_vencimiento, descuento, precio_unidad_sin_desc, costo_unidad, costo, id_compra)
                SELECT 
                    tipo_documento, $newid, fabricante, articulo, almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida,
                    (saldo_pendiente * " . ($documento == 'NC' ? "-1" : "1") . ") as cantidad_movimiento,
                    alicuota, precio_unidad, precio, lote, fecha_vencimiento, descuento, precio_unidad_sin_desc, costo_unidad, costo, id_compra
                FROM (
                    SELECT *,
                        (ABS(cantidad_movimiento) - (
                            SELECT COALESCE(SUM(ABS(es2.cantidad_movimiento)),0) 
                            FROM entradas_salidas es2 
                            INNER JOIN salidas s2 ON es2.id_documento = s2.id 
                            WHERE s2.doc_afe = $id 
                            AND s2.documento = 'NC' 
                            AND es2.articulo = main.articulo 
                            AND es2.tipo_documento = '$tipo_documento'
                            AND s2.tipo_documento = '$tipo_documento'
                            AND s2.id != $newid
                        )) as saldo_pendiente
                    FROM entradas_salidas main
                    WHERE id_documento = $id AND tipo_documento = '$tipo_documento'
                ) as consulta_saldos
                WHERE saldo_pendiente > 0 OR '$documento' != 'NC';"; 

Execute($sql_detalle);

header("Location: TdcfcvAdd?tipo_documento=TDCFCV&pedido=$newid");
exit();

// Función auxiliar para no repetir código HTML de error
function mostrar_error_personalizado($mensaje) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <title>Error de Validación</title>
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-ban"></i> Acción no permitida</h5>
                        </div>
                        <div class="card-body text-center">
                            <p class="fs-5"><?php echo $mensaje; ?></p>
                            <hr>
                            <button onclick="window.history.back();" class="btn btn-secondary">Regresar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
<?= GetDebugMessage() ?>
