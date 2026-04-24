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
    echo json_encode(array("estatus" => "0", "mensaje" => "Error: La cantidad debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}

if ($precio_full <= 0) {
    echo json_encode(array("estatus" => "0", "mensaje" => "Error: El precio debe ser mayor a cero."), JSON_UNESCAPED_UNICODE);
    exit();
}
// ---------------------------------------

// --- VALIDACIÓN DE REGLAS SENIAT PARA NOTAS DE CRÉDITO ---
// Obtenemos el tipo de documento y el documento afectado (padre)
$sql_check = "SELECT documento, doc_afe FROM salidas WHERE id = $pedido";
$rs_check = mysqli_query($link, $sql_check);
$row_check = mysqli_fetch_array($rs_check);
$tipo_doc_actual = $row_check['documento'];
$id_factura_original = intval($row_check['doc_afe']);

if ($tipo_doc_actual == "NC" && $id_factura_original > 0) {
    // 1. Obtener cantidad original en la Factura original
    $sql_orig = "SELECT ABS(cantidad_movimiento) as cant_facturada 
                 FROM entradas_salidas 
                 WHERE id_documento = $id_factura_original AND articulo = $articulo";
    $rs_orig = mysqli_query($link, $sql_orig);
    $row_orig = mysqli_fetch_array($rs_orig);
    $cant_facturada = floatval($row_orig['cant_facturada']);

    // 2. Obtener cuánto se ha devuelto en OTRAS notas de crédito (excluyendo la actual)
    $sql_otros = "SELECT SUM(ABS(es.cantidad_movimiento)) as total_devuelto 
                  FROM entradas_salidas es 
                  INNER JOIN salidas s ON es.id_documento = s.id 
                  WHERE s.doc_afe = $id_factura_original 
                  AND s.documento = 'NC' 
                  AND es.articulo = $articulo 
                  AND s.id != $pedido"; 
    $rs_otros = mysqli_query($link, $sql_otros);
    $row_otros = mysqli_fetch_array($rs_otros);
    $total_devuelto_previo = floatval($row_otros['total_devuelto']);

    $disponible = $cant_facturada - $total_devuelto_previo;

    // 3. Validar excedente
    if ($cantidad > $disponible) {
        echo json_encode(array(
            "estatus" => "0",
            "mensaje" => "Regla SENIAT: No puede devolver $cantidad unidades. El saldo disponible en la factura es de $disponible unidades (Ya se devolvieron $total_devuelto_previo anteriormente)."
        ), JSON_UNESCAPED_UNICODE);
        exit();
    }
}
// --- FIN VALIDACIÓN SENIAT ---

// 1. Calcular el nuevo precio neto de la línea (precio con descuento de item)
$nuevo_precio_neto = $precio_full - ($precio_full * ($desc_item / 100));
$nuevo_total_linea = $nuevo_precio_neto * $cantidad;

// 2. Actualizar la línea en entradas_salidas
// IMPORTANTE: Si es NC, la cantidad_movimiento debe guardarse negativa para el inventario
$cant_mov = ($tipo_doc_actual == "NC") ? ($cantidad * -1) : $cantidad;

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

// 3. Auditoría del cambio
$sql_audit = "INSERT INTO audittrail (datetime, script, `user`, `action`, `table`, keyvalue, oldvalue, newvalue)
              VALUES ('" . date("Y-m-d H:i:s") . "', 'Modificar Item ID $id_item en Pedido $pedido', '$username', 'U', 'entradas_salidas', '$id_item', 'Cant: $cantidad', 'Precio: $nuevo_total_linea');";
mysqli_query($link, $sql_audit);

// 4. Recalcular la cabecera de la factura
$sql_ali = "SELECT alicuota FROM entradas_salidas WHERE id_documento = '$pedido' LIMIT 1;";
$rs_ali = mysqli_query($link, $sql_ali);
$row_ali = mysqli_fetch_array($rs_ali);
$xalicuota = floatval($row_ali["alicuota"]);

$sql_totales = "SELECT
            SUM(precio_unidad_sin_desc * cantidad_articulo) AS precio_unidad_sin_desc, 
            SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento_global/100)), 0)) AS exento, 
            SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento_global/100)))) AS gravado, 
            COUNT(articulo) AS renglones, 
            ABS(SUM(cantidad_movimiento)) AS unidades 
        FROM entradas_salidas 
        WHERE id_documento = '$pedido' AND tipo_documento = '$tipo_documento';"; 

$rs_t = mysqli_query($link, $sql_totales);
$row = mysqli_fetch_array($rs_t);

$monto_sin_descuento = floatval($row["precio_unidad_sin_desc"]);
$xExento = floatval($row["exento"]) * (1 - ($descTransferencista / 100));
$xGravado = floatval($row["gravado"]) * (1 - ($descTransferencista / 100));

$costo = $xExento + $xGravado;
$iva = $xGravado * ($xalicuota / 100);
$total = $costo + $iva;
$total_usd = round((strpos(strtolower($moneda), 'bs') !== false ? ($total / $tasa_usd) : $total), 2);

// Actualizar cabecera
$sql_cab = "UPDATE salidas SET tasa_dia = $tasa_usd, monto_total = $costo, iva = $iva, total = $total, monto_usd = $total_usd, monto_sin_descuento = $monto_sin_descuento WHERE id = '$pedido'";
mysqli_query($link, $sql_cab);

// Respuesta JSON
echo json_encode(array(
    "estatus" => "1",
    "pedido" => (string)$pedido,
    "total" => (string)$total,
    "renglones" => (string)$row["renglones"],
    "unidades" => (string)$row["unidades"],
    "total_usd" => (string)$total_usd,
    "monto_sin_descuento" => (string)$monto_sin_descuento
), JSON_UNESCAPED_UNICODE);
?>