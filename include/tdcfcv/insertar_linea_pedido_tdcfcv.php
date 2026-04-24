<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 
$cliente = $_REQUEST["cliente"]; 
$precioFull = $_REQUEST["precioFull"]; 
$descuento = $_REQUEST["descuento"]; 
$precio = $_REQUEST["precio"]; 
$moneda = $_REQUEST["moneda"]; 
$total = $_REQUEST["total"]; 
$cantidad = intval($_REQUEST["cantidad"]); 
$articulo = intval($_REQUEST["articulo"]); 
$tasa_usd = floatval($_REQUEST["tasa_usd"]); 
$username = $_REQUEST["username"]; 
$descuentoG = floatval($_REQUEST["descuentoG"]); // Cambiado a floatval para precisión
$descTransferencista = floatval($_REQUEST["descTransferencista"]); 
$nota = $_REQUEST["nota"]; 
$consignacion = $_REQUEST["consignacion"]; 
$doc_afectado = (isset($_REQUEST["doc_afectado"]) && $_REQUEST["doc_afectado"] != "") ? intval($_REQUEST["doc_afectado"]) : ""; 
$lote = $_REQUEST["lote"]; 
$vence = $_REQUEST["vence"]; 
$vence = ($vence == "" ? "1990-01-01" : $vence);
$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

$doc_afectado_id = 0;

// --- VALIDACIONES INICIALES ---
if ($cantidad <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "Error: La cantidad debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}
if (floatval($precioFull) <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "Error: El precio debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}

if ($descuento == 100) {
    echo json_encode(array("estatus" => "0", "mensaje" => "El descuento del item no puede ser 100%."), JSON_UNESCAPED_UNICODE);
    exit();
}

if ($descTransferencista == 100) {
    echo json_encode(array("estatus" => "0", "mensaje" => "El descuento transferencista no puede ser 100%."), JSON_UNESCAPED_UNICODE);
    exit();
}
// --- VALIDACIÓN ESPECÍFICA PARA NOTA DE CRÉDITO (NC) ---
if ($consignacion == "NC") {
    // Busco el NRO de documento que afecta la NC
    $sql_nro = "SELECT doc_afe FROM salidas WHERE id = $pedido;";
    $rs_nro = mysqli_query($link, $sql_nro);
    $row_nro = mysqli_fetch_array($rs_nro);
    $doc_afectado_id = $row_nro["doc_afe"] ?? 0;
    //////

    if ($doc_afectado_id == 0) {
        echo json_encode(array("estatus" => "0", "mensaje" => "Error: La Nota de Crédito debe afectar a un documento origen."), JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 1. Obtener cantidad y precio original en la Factura Afectada
    $sql_orig = "SELECT SUM(cantidad_articulo) AS cantidad_articulo, MIN(precio_unidad) AS precio_unidad FROM entradas_salidas 
                 WHERE id_documento = $doc_afectado_id AND articulo = $articulo AND tipo_documento = '$tipo_documento';";
    $rs_orig = mysqli_query($link, $sql_orig);
    $row_orig = mysqli_fetch_array($rs_orig);

    if (!$row_orig) {
        echo json_encode(array("estatus" => "0", "mensaje" => "El artículo no existe en la factura afectada."), JSON_UNESCAPED_UNICODE);
        exit();
    }

    $cant_original = floatval($row_orig["cantidad_articulo"]);
    $precio_original = floatval($row_orig["precio_unidad"]);

    // 2. Validar que el precio sea el mismo
    if (abs($precio - $precio_original) > 0.01) {
        echo json_encode(array("estatus" => "0", "mensaje" => "El precio unidad ($precio) no coincide con el de la factura original ($precio_original)."), JSON_UNESCAPED_UNICODE);
        exit();
    }

    // 3. Consultar NC previas (incluyendo la actual si ya tiene items)
    $sql_prev = "SELECT SUM(es.cantidad_articulo) as cant_nc_previa 
                 FROM entradas_salidas es
                 INNER JOIN salidas s ON es.id_documento = s.id
                 WHERE s.doc_afe = $doc_afectado_id AND es.articulo = $articulo 
                 AND s.documento = 'NC' AND es.tipo_documento = '$tipo_documento'";
    $rs_prev = mysqli_query($link, $sql_prev);
    $row_prev = mysqli_fetch_array($rs_prev);
    $cant_acumulada_nc = floatval($row_prev["cant_nc_previa"]);

    if (($cant_acumulada_nc + $cantidad) > $cant_original) {
        $disponible = $cant_original - $cant_acumulada_nc;
        echo json_encode(array("estatus" => "0", "mensaje" => "Excede la cantidad disponible. Original: $cant_original, Ya devuelto: $cant_acumulada_nc, Disponible: $disponible"), JSON_UNESCAPED_UNICODE);
        exit();
    }
}
///////////////////
// --- LÓGICA DE ALMACÉN Y ARTÍCULO ---
$alma = "0";
$sql = "SELECT valor1 AS tipo_doc_inv FROM parametro WHERE codigo = '050';";
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) { 
    if($row["tipo_doc_inv"] == "TDCFCV") {
        $xlote = explode("|", $_REQUEST["lote"]);
        $lote = $xlote[0]; 
        $alma = $xlote[2] ?? "0"; 
    } 
} 

$sql = "SELECT alicuota, cantidad_por_unidad_medida, ultimo_costo, fabricante, unidad_medida_defecto FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
$row_art = mysqli_fetch_array($rs);
$codigo_alicuota = $row_art["alicuota"];
$cantidad_unidad = intval($row_art["cantidad_por_unidad_medida"]);
$fabricante = $row_art["fabricante"];
$unidad_medida = $row_art["unidad_medida_defecto"];

$sql = "SELECT alicuota FROM alicuota WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row_ali = mysqli_fetch_array($rs);
$alicuota_item = floatval($row_ali["alicuota"] ?? 0);

$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row_alm = mysqli_fetch_array($rs);
$almacen = ($alma == "0") ? $row_alm["almacen"] : $alma;

$cantidad_movimiento = ($consignacion == "NC") ? ($cantidad_unidad * $cantidad) : (-1)*($cantidad_unidad * $cantidad);
$costo_unidad = floatval($row_art["ultimo_costo"]);
$costo_total_item = $cantidad * $costo_unidad;

// --- MANEJO DE CABECERA ---
if($pedido == 0) {
    $sql = "INSERT INTO salidas (id, tipo_documento, username, fecha, cliente, nota, estatus, moneda, consignacion, descuento, descuento2, documento, doc_afectado, doc_afe) 
            VALUES (NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "', $cliente, '$nota', 'NUEVO', '$moneda', 'N', $descuentoG, $descTransferencista, '$consignacion', '$doc_afectado', $doc_afectado_id);";
    mysqli_query($link, $sql);
    $pedido = mysqli_insert_id($link);
    $nro_documento = "";
} else {
    $sql = "UPDATE salidas SET descuento = $descuentoG, descuento2 = $descTransferencista WHERE id = $pedido;";
    mysqli_query($link, $sql);
}

// --- INSERCIÓN DEL ITEM ---
$sql = "INSERT INTO entradas_salidas (id, tipo_documento, id_documento, fabricante, articulo, almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, costo_unidad, costo, precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc, newdata, lote, fecha_vencimiento)
        VALUES (NULL, '$tipo_documento', $pedido, $fabricante, $articulo, '$almacen', $cantidad, '$unidad_medida', $cantidad_unidad, $cantidad_movimiento, $costo_unidad, $costo_total_item, $precio, $total, $alicuota_item, $descuento, $precioFull, 'S', '$lote', '$vence');"; 
mysqli_query($link, $sql);
$id_item = mysqli_insert_id($link);

// --- RECALCULO DE TOTALES (Lógica de moneda_tdcfcv_totales.php) ---

// 1. Obtener alícuota general (si hay varias, se usa la mayor según tu lógica de moneda_tdcfcv_totales)
$sql = "SELECT DISTINCT alicuota FROM entradas_salidas WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento' ORDER BY 1 DESC LIMIT 0, 1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$xalicuota = floatval($row["alicuota"] ?? 0);

// 2. Cálculos globales de la cesta
$sql_calc = "SELECT
                SUM(precio) AS precio_unidad_sin_desc, 
                SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuentoG/100)), 0)) AS exento, 
                SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuentoG/100)))) AS gravado, 
                COUNT(articulo) AS renglones, 
                ABS(SUM(cantidad_movimiento)) AS unidades 
            FROM entradas_salidas 
            WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 
$rs_calc = mysqli_query($link, $sql_calc);
if($row_c = mysqli_fetch_array($rs_calc)) {
    $monto_sin_descuento = floatval($row_c["precio_unidad_sin_desc"]);
    $renglones = floatval($row_c["renglones"]);
    $unidades = floatval($row_c["unidades"]);

    // Aplicar descuento de transferencista sobre bases
    $xExento = floatval($row_c["exento"]);
    $xExento = $xExento - ($xExento * ($descTransferencista/100));
    
    $xGravado = floatval($row_c["gravado"]);
    $xGravado = $xGravado - ($xGravado * ($descTransferencista/100));
    
    $costo_final = $xExento + $xGravado;
    $iva_final = $xGravado * ($xalicuota / 100);
    $total_final = $costo_final + $iva_final;

    // Conversión a USD según la moneda actual
    $total_usd = round((substr(strtolower(trim($moneda)), 0, 3) == "bs." ? ($total_final / $tasa_usd) : $total_final), 2);

    // 3. Actualización de la Cabecera
    $sql_upd = "UPDATE salidas 
                SET monto_total = $costo_final,
                    iva = $iva_final,
                    total = $total_final, 
                    tasa_dia = $tasa_usd, 
                    unidades = $unidades,
                    monto_usd = $total_usd, 
                    moneda = '$moneda',  
                    monto_sin_descuento = $monto_sin_descuento 
                WHERE id = '$pedido'";
    mysqli_query($link, $sql_upd);

    // Obtener Nro Documento para la respuesta
    $sql_nro = "SELECT nro_documento FROM salidas WHERE id = $pedido;";
    $rs_nro = mysqli_query($link, $sql_nro);
    $row_nro = mysqli_fetch_array($rs_nro);
    $nro_documento = $row_nro["nro_documento"] ?? "";

    // Respuesta JSON
    $response = array(
        "estatus" => "1",
        "pedido" => (string)$pedido,
        "id_item" => (string)$id_item,
        "total" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($costo_final / $tasa_usd), 2) : $total_final),
        "total_usd" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? $total_final : round(($total_final * $tasa_usd), 2)),
        "monto_sin_descuento" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($monto_sin_descuento / $tasa_usd), 2) : $monto_sin_descuento),
        "total_usd_sin_descuento" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? $monto_sin_descuento : round(($monto_sin_descuento * $tasa_usd), 2)),
        "renglones" => (string)$renglones,
        "unidades" => (string)$unidades,
        "nro_documento" => (string)$nro_documento,
        "mensaje" => "Item insertado y totales actualizados"
    );
}

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();
?>