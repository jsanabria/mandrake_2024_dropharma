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
$descuentoG = intval($_REQUEST["descuentoG"]); 
$descTransferencista = floatval($_REQUEST["descTransferencista"]); 
$nota = $_REQUEST["nota"]; 
$consignacion = $_REQUEST["consignacion"]; 
$doc_afectado = (isset($_REQUEST["doc_afectado"]) && $_REQUEST["doc_afectado"] != "") ? intval($_REQUEST["doc_afectado"]) : 0; 
$lote = $_REQUEST["lote"]; 
$vence = $_REQUEST["vence"]; 
$vence = ($vence == "" ? "1990-01-01" : $vence);
$tipo_documento = "TDCFCV";
$tasa_usd = ($tasa_usd == 0 ? 1 : $tasa_usd);

// --- VALIDACIÓN DE VALORES POSITIVOS ---
if ($cantidad <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "Error: La cantidad debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}
if (floatval($precioFull) <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "Error: El precio debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}

// --- VALIDACIÓN REGLAS SENIAT (NC) ---
if($consignacion == "NC" && $doc_afectado > 0) {
    $sql_orig = "SELECT ABS(cantidad_movimiento) as cant_facturada FROM entradas_salidas WHERE id_documento = $doc_afectado AND articulo = $articulo";
    $rs_orig = mysqli_query($link, $sql_orig);
    $row_orig = mysqli_fetch_array($rs_orig);
    $cant_facturada = floatval($row_orig['cant_facturada'] ?? 0);

    if($cant_facturada == 0) {
        echo json_encode(array("estatus"=>"0", "mensaje"=>"Error: El artículo no existe en la factura original."), JSON_UNESCAPED_UNICODE);
        exit();
    }

    $sql_otros = "SELECT SUM(ABS(es.cantidad_movimiento)) as total_devuelto FROM entradas_salidas es INNER JOIN salidas s ON es.id_documento = s.id WHERE s.doc_afe = $doc_afectado AND s.documento = 'NC' AND es.articulo = $articulo";
    $rs_otros = mysqli_query($link, $sql_otros);
    $row_otros = mysqli_fetch_array($rs_otros);
    $total_devuelto_previo = floatval($row_otros['total_devuelto']);
    $disponible = $cant_facturada - $total_devuelto_previo;

    if ($cantidad > $disponible) {
        echo json_encode(array("estatus"=>"0", "mensaje"=>"Regla SENIAT: Excede la cantidad disponible ($disponible)."), JSON_UNESCAPED_UNICODE);
        exit();
    }
}

// Lógica de Moneda y Almacén
/*
$sql = "SELECT valor1 AS fact_bs FROM parametro WHERE codigo = '053';";
$rs = mysqli_query($link, $sql); 
$row = mysqli_fetch_array($rs); 
if(($row["fact_bs"] ?? "") == "S") $moneda = "Bs.";
*/

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
$alicuota = floatval($row_ali["alicuota"] ?? 0);

$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row_alm = mysqli_fetch_array($rs);
$almacen = ($alma == "0") ? $row_alm["almacen"] : $alma;

$cantidad = abs($cantidad);
$cantidad_movimiento = ($consignacion == "NC") ? ($cantidad_unidad * $cantidad) : (-1)*($cantidad_unidad * $cantidad);
$costo_unidad = floatval($row_art["ultimo_costo"]);
$costo_total_item = $cantidad * $costo_unidad;

// Manejo de Cabecera
if($pedido == 0) {
    $sql = "INSERT INTO salidas (id, tipo_documento, username, fecha, cliente, nota, estatus, moneda, consignacion, descuento, descuento2, documento, doc_afe) 
            VALUES (NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "', $cliente, '$nota', 'NUEVO', '$moneda', 'N', $descuentoG, $descTransferencista, '$consignacion', $doc_afectado);";
    mysqli_query($link, $sql);
    $pedido = mysqli_insert_id($link);
    $nro_documento = "";
} else {
    $sql = "UPDATE salidas SET descuento = $descuentoG, descuento2 = $descTransferencista WHERE id = $pedido;";
    mysqli_query($link, $sql);
    
    $sql_nro = "SELECT nro_documento FROM salidas WHERE id = $pedido";
    $rs_nro = mysqli_query($link, $sql_nro);
    $row_nro = mysqli_fetch_array($rs_nro);
    $nro_documento = $row_nro["nro_documento"] ?? "";
}

// Inserción del Item
$sql = "INSERT INTO entradas_salidas (id, tipo_documento, id_documento, fabricante, articulo, almacen, cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, costo_unidad, costo, precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc, newdata, lote, fecha_vencimiento)
        VALUES (NULL, '$tipo_documento', $pedido, $fabricante, $articulo, '$almacen', $cantidad, '$unidad_medida', $cantidad_unidad, $cantidad_movimiento, $costo_unidad, $costo_total_item, $precio, $total, $alicuota, $descuento, $precioFull, 'S', '$lote', '$vence');"; 
mysqli_query($link, $sql);
$id_item = mysqli_insert_id($link);

// --- RECALCULO DE TOTALES (LO QUE FALTABA) ---
$sql_calc = "SELECT 
                SUM(precio) AS monto_sin_desc, 
                COUNT(id) AS total_renglones,
                SUM(ABS(cantidad_movimiento)) AS total_unidades,
                SUM(IF(alicuota=0, precio - (precio * ($descuentoG/100)), 0)) AS exento,
                SUM(IF(alicuota>0, precio - (precio * ($descuentoG/100)), 0)) AS gravado,
                MAX(alicuota) as max_alicuota
             FROM entradas_salidas 
             WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento'";
$rs_calc = mysqli_query($link, $sql_calc);
$row_c = mysqli_fetch_array($rs_calc);

$monto_sin_descuento = floatval($row_c["monto_sin_desc"]);
$renglones = intval($row_c["total_renglones"]);
$unidades = floatval($row_c["total_unidades"]);

$xExento = floatval($row_c["exento"]);
$xExento = $xExento - ($xExento * ($descTransferencista/100));
$xGravado = floatval($row_c["gravado"]);
$xGravado = $xGravado - ($xGravado * ($descTransferencista/100));

$costo_final = $xExento + $xGravado;
$iva_final = $xGravado * (floatval($row_c["max_alicuota"])/100);
$total_final = $costo_final + $iva_final;

$total_usd = round((strtoupper(substr($moneda, 0, 3)) == "BS." ? ($total_final / $tasa_usd) : $total_final), 2);
$total_usd_sin_desc = round((strtoupper(substr($moneda, 0, 3)) == "BS." ? ($monto_sin_descuento / $tasa_usd) : $monto_sin_descuento), 2);

// Actualizar cabecera con los totales reales
$sql_upd = "UPDATE salidas SET tasa_dia = $tasa_usd, monto_total = $costo_final, iva = $iva_final, total = $total_final, unidades = $unidades, monto_usd = $total_usd, monto_sin_descuento = $monto_sin_descuento WHERE id = $pedido";
mysqli_query($link, $sql_upd);

$response = array(
    "estatus" => "1",
    "pedido" => (string)$pedido,
    "id_item" => (string)$id_item,
    "nro_documento" => (string)$nro_documento,
    "renglones" => (string)$renglones,
    "unidades" => (string)$unidades,
    "total" => (string)(strtoupper(substr($moneda, 0, 3)) == "BS." ? round(($total_final), 2) : $total_final),
    "total_usd" => (string)$total_usd,
    "monto_sin_descuento" => (string)$monto_sin_descuento,
    "total_usd_sin_descuento" => (string)$total_usd_sin_desc,
    "mensaje" => "Item procesado correctamente"
);

header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit();