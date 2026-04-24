<?php
include "../connect.php";

// Captura de datos
$pedido = intval($_REQUEST["pedido"]); 
$id_item = intval($_REQUEST["id_item"]);
$articulo = intval($_REQUEST["articulo"]); 
$cantidad = floatval($_REQUEST["cantidad"]);
$precio_full = floatval($_REQUEST["precio_full"]);
$desc_item = floatval($_REQUEST["descuento_item"]);
$lote = $_REQUEST["lote"];
$vence = $_REQUEST["vence"];

$tasa_usd = floatval($_REQUEST["tasa_usd"]); 
$moneda = $_REQUEST["moneda"]; 
$username = $_REQUEST["username"]; 
$descuento_global = floatval($_REQUEST["descuento_global"]); 
$descTransferencista = floatval($_REQUEST["descTransferencista"]); 
$nota = $_REQUEST["nota"]; 

$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

// --- VALIDACIÓN DE VALORES POSITIVOS ---
if ($cantidad <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "La cantidad debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}

if ($precio_full <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "El precio debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}

if ($desc_item == 100) {
    echo json_encode(array("estatus" => "0", "mensaje" => "El descuento del item no puede ser 100%."), JSON_UNESCAPED_UNICODE);
    exit();
}

if ($descTransferencista == 100) {
    echo json_encode(array("estatus" => "0", "mensaje" => "El descuento transferencista no puede ser 100%."), JSON_UNESCAPED_UNICODE);
    exit();
}
// 1. Obtener datos actuales del encabezado
$sql_check = "SELECT documento, doc_afe FROM salidas WHERE id = $pedido";
$rs_check = mysqli_query($link, $sql_check);
$row_check = mysqli_fetch_array($rs_check);
$tipo_doc_actual = $row_check['documento'];
$doc_afectado = intval($row_check['doc_afe']);

// --- VALIDACIÓN PARA NC EN MODIFICACIÓN ---
if ($tipo_doc_actual == "NC") {
    $nuevo_precio_neto = $precio_full - ($precio_full * ($desc_item / 100));

    // A. Obtener datos originales
    $sql_orig = "SELECT SUM(cantidad_articulo) AS cantidad_articulo, MIN(precio_unidad) AS precio_unidad FROM entradas_salidas 
                 WHERE id_documento = $doc_afectado AND articulo = $articulo AND tipo_documento = '$tipo_documento';";
    $rs_orig = mysqli_query($link, $sql_orig);
    $row_orig = mysqli_fetch_array($rs_orig);

    if ($row_orig) {
        $cant_original = floatval($row_orig["cantidad_articulo"]);
        $precio_original = floatval($row_orig["precio_unidad"]);

        // Validar Precio
        if (abs($nuevo_precio_neto - $precio_original) > 0.01) {
            echo json_encode(array("estatus" => "0", "mensaje" => "Error: El nuevo precio neto ($nuevo_precio_neto) debe ser igual al original ($precio_original)."), JSON_UNESCAPED_UNICODE);
            exit();
        }

        // Validar Cantidad (Excluyendo el item actual que se está editando)
        $sql_prev = "SELECT SUM(es.cantidad_articulo) as cant_nc_previa 
                     FROM entradas_salidas es
                     INNER JOIN salidas s ON es.id_documento = s.id
                     WHERE s.doc_afe = $doc_afectado AND es.articulo = $articulo 
                     AND s.documento = 'NC' AND es.tipo_documento = '$tipo_documento'
                     AND es.id <> $id_item"; // EXCLUIR EL REGISTRO ACTUAL
        $rs_prev = mysqli_query($link, $sql_prev);
        $row_prev = mysqli_fetch_array($rs_prev);
        $cant_acumulada_nc = floatval($row_prev["cant_nc_previa"]);

        if (($cant_acumulada_nc + $cantidad) > $cant_original) {
            $disponible = $cant_original - $cant_acumulada_nc;
            echo json_encode(array("estatus" => "0", "mensaje" => "La cantidad sumada ($cantidad + $cant_acumulada_nc) supera la original ($cant_original)."), JSON_UNESCAPED_UNICODE);
            exit();
        }
    }
}

// 2. Calcular el nuevo precio neto de la línea e insertarlo
$nuevo_precio_neto = $precio_full - ($precio_full * ($desc_item / 100));
$nuevo_total_linea = $nuevo_precio_neto * $cantidad;
$cant_mov = ($tipo_doc_actual == "NC") ? $cantidad : ($cantidad * -1);

$sql_update = "UPDATE entradas_salidas SET 
                cantidad_articulo = $cantidad,
                cantidad_movimiento = $cant_mov, 
                precio_unidad_sin_desc = $precio_full,
                descuento = $desc_item,
                precio_unidad = $nuevo_precio_neto,
                precio = $nuevo_total_linea,
                lote = '$lote',
                fecha_vencimiento = '$vence'
               WHERE id = $id_item;";
mysqli_query($link, $sql_update);

// 3. RECALCULO DE TOTALES (Lógica idéntica a moneda_tdcfcv_totales.php)
$sql_ali = "SELECT DISTINCT alicuota FROM entradas_salidas WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento' ORDER BY 1 DESC LIMIT 0, 1;";
$rs_ali = mysqli_query($link, $sql_ali);
$row_ali = mysqli_fetch_array($rs_ali);
$xalicuota = floatval($row_ali["alicuota"] ?? 0);

$sql_totales = "SELECT
            SUM(precio_unidad_sin_desc) AS precio_sin_desc_total, 
            SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento_global/100)), 0)) AS exento, 
            SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento_global/100)))) AS gravado, 
            COUNT(articulo) AS renglones, 
            ABS(SUM(cantidad_movimiento)) AS unidades 
        FROM entradas_salidas 
        WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 

$rs_t = mysqli_query($link, $sql_totales);
if($row_t = mysqli_fetch_array($rs_t)) {
    $monto_sin_descuento = floatval($row_t["precio_sin_desc_total"]);
    $renglones = floatval($row_t["renglones"]);
    $unidades = floatval($row_t["unidades"]);

    // Aplicar descuento de transferencista sobre bases
    $xExento = floatval($row_t["exento"]);
    $xExento = $xExento - ($xExento * ($descTransferencista / 100));

    $xGravado = floatval($row_t["gravado"]);
    $xGravado = $xGravado - ($xGravado * ($descTransferencista / 100));

    $costo = $xExento + $xGravado;
    $iva = $xGravado * ($xalicuota / 100);
    $total_final = $costo + $iva;

    // Conversión USD
    $total_usd = round((strtoupper(substr($moneda, 0, 3)) == "BS." ? ($total_final / $tasa_usd) : $total_final), 2);

    // 4. Actualizar cabecera
    $sql_cab = "UPDATE salidas SET 
                    tasa_dia = $tasa_usd, 
                    monto_total = $costo, 
                    iva = $iva, 
                    total = $total_final, 
                    unidades = $unidades,
                    monto_usd = $total_usd, 
                    moneda = '$moneda',
                    monto_sin_descuento = $monto_sin_descuento 
                WHERE id = '$pedido'";
    mysqli_query($link, $sql_cab);

    // 5. Respuesta JSON con alternancia de moneda
    $response = array(
        "total" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($costo / $tasa_usd), 2) : $costo),
        "total_usd" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? $costo : round(($costo * $tasa_usd), 2)),
        "monto_sin_descuento" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($monto_sin_descuento / $tasa_usd), 2) : $monto_sin_descuento),
        "total_usd_sin_descuento" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? $monto_sin_descuento : round(($monto_sin_descuento * $tasa_usd), 2)),
        "estatus" => "1",
        "mensaje" => "Item modificado y totales actualizados"
    );
} else {
    $response = array("estatus" => "0", "mensaje" => "Error al recalcular totales");
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>