<?php
/**
 * findme_agregar.php
 */
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once "connect.php";
require_once "rutinas.php";
require_once "findme_procesador_calculos.php";

$response = [
    "success" => false,
    "message" => "",
    "data" => null,
    "error" => ""
];

try {
    $link->begin_transaction();

    $id = (int)($_POST["id"] ?? 0);
    $nota = trim($_POST["nota"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $factura = trim($_POST["factura"] ?? "");
    $ci_rif = trim($_POST["ci_rif"] ?? "");
    $nombre = trim($_POST["nombre"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $articulo = (int)($_POST["articulo"] ?? 0);
    $lote = trim($_POST["lote"] ?? "");
    $fecha_vencimiento = trim($_POST["fecha"] ?? "");
    $almacen = trim($_POST["almacen"] ?? "");
    $cnt = floatval($_POST["cantidad"] ?? 0);
    $un = trim($_POST["unidad"] ?? "");

    if ($id <= 0 || $articulo <= 0 || $cnt <= 0) {
        throw new Exception("Parámetros obligatorios incompletos o erróneos.");
    }

    // Identificar tipo de documento madre
    $stmt = $link->prepare("SELECT tipo_documento FROM salidas WHERE id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resSalida = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$resSalida) throw new Exception("El documento base no existe.");
    $tipo_documento = $resSalida["tipo_documento"];

    // Buscar parámetro de inventario
    $tipo_documento_inventario = 'TDCNET';
    $resParam = $link->query("SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';");
    if ($resParam && $rowP = $resParam->fetch_assoc()) $tipo_documento_inventario = $rowP["tipo_documento"];

    // Validar visualización de precios
    $codParamPrecio = ($tipo_documento === "TDCNET") ? "051" : "052";
    $mostrar_precio = 'N';
    $resPrecio = $link->query("SELECT valor1 AS mostrar_precio FROM parametro WHERE codigo = '$codParamPrecio';");
    if ($resPrecio && $rowPr = $resPrecio->fetch_assoc()) $mostrar_precio = $rowPr["mostrar_precio"];

    // Consultar descuentos asignados
    $stmt = $link->prepare("SELECT a.descuento, IFNULL(b.descuento, 0) AS descuento2, a.fabricante FROM articulo AS a LEFT JOIN fabricante AS b ON b.Id = a.fabricante WHERE a.id = ?;");
    $stmt->bind_param("i", $articulo);
    $stmt->execute();
    $rowArt = $stmt->get_result()->fetch_assoc();
    $descuento = floatval($rowArt["descuento"] ?? 0);
    $descuento2 = floatval($rowArt["descuento2"] ?? 0);
    $fabricante = $rowArt["fabricante"] ?? "";
    $stmt->close();

    // Obtener equivalencia de unidad de medida
    $stmt = $link->prepare("SELECT cantidad FROM unidad_medida WHERE codigo = ?;");
    $stmt->bind_param("s", $un);
    $stmt->execute();
    $cantidad_um = floatval($stmt->get_result()->fetch_assoc()["cantidad"] ?? 1);
    $stmt->close();

    $asignado = $cnt * $cantidad_um;

    // Calcular costos
    $stmt = $link->prepare("SELECT ultimo_costo FROM articulo WHERE id = ?;");
    $stmt->bind_param("i", $articulo);
    $stmt->execute();
    $costo_unidad = floatval($stmt->get_result()->fetch_assoc()["ultimo_costo"] ?? 0);
    $costo = $costo_unidad * $asignado;
    $stmt->close();

    // Alícuota impositiva
    $stmt = $link->prepare("SELECT IFNULL(b.alicuota, 0) as alicuota FROM articulo AS a JOIN alicuota AS b ON b.codigo = a.alicuota WHERE a.id = ? AND b.activo = 'S';");
    $stmt->bind_param("i", $articulo);
    $stmt->execute();
    $alicuota = floatval($stmt->get_result()->fetch_assoc()["alicuota"] ?? 0);
    $stmt->close();

    // Tarifa del cliente
    $stmt = $link->prepare("SELECT b.tarifa FROM salidas AS a JOIN cliente AS b ON b.id = a.cliente WHERE a.id = ?;");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $tarifa = (int)($stmt->get_result()->fetch_assoc()["tarifa"] ?? 0);
    $stmt->close();

    // Calcular precio por escala de tarifas
    $stmt = $link->prepare("SELECT a.precio AS precio_ful, ROUND(((a.precio - (a.precio * (? / 100))) - ((a.precio - (a.precio * (? / 100))) * (? / 100))), 2) AS precio FROM tarifa_articulo AS a WHERE a.tarifa = ? AND a.articulo = ?;");
    $stmt->bind_param("ddiii", $descuento, $descuento, $descuento2, $tarifa, $articulo);
    $stmt->execute();
    $rowPre = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($mostrar_precio === "S" && $rowPre) {
        $precio_unidad = floatval($rowPre["precio"]);
        $precio = $asignado * $precio_unidad;
        $precio_ful = floatval($rowPre["precio_ful"]);	
    } else {
        $precio_unidad = 0;
        $precio = 0;
        $precio_ful = 0;	
    }

    // Re-verificación estricta de stock disponible en lote
    $sqlCheck = "SELECT SUM(x.cantidad_movimiento) AS cantidad FROM (
                    SELECT a.cantidad_movimiento FROM entradas_salidas AS a 
                    JOIN entradas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
                    JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE ((a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO'))
                      AND a.articulo = ? AND a.newdata = 'S' AND a.lote = ? AND IFNULL(a.fecha_vencimiento, '1990-01-01') = ? AND a.almacen = ?
                    UNION ALL 
                    SELECT a.cantidad_movimiento FROM entradas_salidas AS a 
                    JOIN salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id = a.id_documento 
                    JOIN almacen AS c ON c.codigo = a.almacen AND c.movimiento = 'S'
                    WHERE ((a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR (a.tipo_documento IN (?, 'TDCASA') AND b.estatus <> 'ANULADO'))
                      AND a.articulo = ? AND a.newdata = 'S' AND a.lote = ? AND IFNULL(a.fecha_vencimiento, '1990-01-01') = ? AND a.almacen = ?
                 ) AS x;";
    
    $stmtCheck = $link->prepare($sqlCheck);
    $vencParam = ($fecha_vencimiento === "") ? '1990-01-01' : $fecha_vencimiento;
	$stmtCheck->bind_param("issssisss", $articulo, $lote, $vencParam, $almacen, $tipo_documento_inventario, $articulo, $lote, $vencParam, $almacen);
    $stmtCheck->execute();
    $xReal = floatval($stmtCheck->get_result()->fetch_assoc()["cantidad"] ?? 0);
    $stmtCheck->close();

    if ($asignado > $xReal) {
        throw new Exception("Cantidad ya no disponible para el lote seleccionado. !!! Verifique !!!");
    }

    // Inserción en negativo (Salida física de inventario)
    $asignadoNegativo = $asignado * -1;

    $sqlInsert = "INSERT INTO entradas_salidas 
        (id, tipo_documento, id_documento, fabricante, articulo, lote, fecha_vencimiento, almacen, cantidad_articulo, costo_unidad, costo, 
        articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, precio_unidad, precio, alicuota, descuento, descuento2, precio_unidad_sin_desc) 
        VALUES (NULL, ?, ?, ?, ?, ?, NULLIF(?, ''), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    
    $stmtIns = $link->prepare($sqlInsert);
    $stmtIns->bind_param("sisissssddssddddddd", 
        $tipo_documento, $id, $fabricante, $articulo, $lote, $fecha_vencimiento, $almacen, $cnt, $costo_unidad, $costo,
        $un, $cantidad_um, $asignadoNegativo, $precio_unidad, $precio, $alicuota, $descuento, $descuento2, $precio_ful
    );
    $stmtIns->execute();
    $stmtIns->close();

    // Sincronizar datos complementarios de cabecera
    $stmtUpd = $link->prepare("UPDATE salidas SET estatus = 'NUEVO', factura = ?, ci_rif = ?, nombre = ?, direccion = ?, nota = ?, telefono = ?, username = ? WHERE id = ?;");
    $stmtUpd->bind_param("sssssssi", $factura, $ci_rif, $nombre, $direccion, $nota, $telefono, $username, $id);
    $stmtUpd->execute();
    $stmtUpd->close();

    // Ejecución del motor interno matemático unificado
    $dataActualizada = calcularYObtenerDetalleJSON($link, $tipo_documento, $id);

    $link->commit();

    $response["success"] = true;
    $response["message"] = "Artículo añadido exitosamente.";
    $response["data"] = $dataActualizada;

} catch (Exception $e) {
    $link->rollback();
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;