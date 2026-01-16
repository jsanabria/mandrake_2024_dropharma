<?php
// ini_set("session.cookie_lifetime","86400");
// ini_set("session.gc_maxlifetime","86400");
session_start();

include "connect.php";
include "rutinas.php";

$tipo_documento = "TDCPDV";
$salida = $_REQUEST["salida"];
$cliente = $_REQUEST["cliente"];
$articulo = $_REQUEST["articulo"];
$cantidad = intval($_REQUEST["cantidad"]);

// --- Valido que aun el pedido esté en estatus NUEVO para poderlo modificar 21/12/2020 --- //
$sql = "SELECT estatus FROM salidas WHERE tipo_documento = '$tipo_documento' AND id = '$salida';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$status_doc = $row["estatus"];
if($status_doc != "NUEVO") {
  echo "001|Este pedido pasó a recibido para ser procesado y no se puede modificar. !!! ESTATUS ACTUAL $status_doc !!!|0";
  die();
}

/* ----- Traigo la tarifa del cliente */
$sql = "SELECT 
      b.tarifa 
    FROM 
      salidas AS a 
      LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
    WHERE 
      a.id = $salida;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tarifa = $row["tarifa"];

/*** traigo el % de descuento del articulo ***/
$sql = "SELECT IFNULL(descuento, 0) AS descuento FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$descuento = floatval($row["descuento"]);

$sql = "SELECT
      precio AS precio_ful,
      (precio - (precio * ($descuento/100))) AS precio 
    FROM tarifa_articulo
    WHERE tarifa = $tarifa AND articulo = $articulo;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$precio = floatval($row["precio"]);
$precio_ful = floatval($row["precio_ful"]);

/*** Busco la alicuota del IVA asociada al artículo ***/
$sql = "SELECT alicuota FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$codigo_alicuota = $row["alicuota"];

$sql = "SELECT alicuota FROM alicuota
    WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$alicuota = floatval($row["alicuota"]);

/**** Almacen por defecto ****/
$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$almacen = $row["almacen"];

/**** ----- Manejo de las unidades de medida ----- ****/
$sql = "SELECT cantidad_por_unidad_medida FROM articulo WHERE id = '$articulo';"; 
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs))
  $cantidad_unidad = intval($row["cantidad_por_unidad_medida"]);
else 
  $cantidad_unidad = 1;

$cantidad_movimiento = $cantidad_unidad * $cantidad;


/**** ----- Valido la Existencia ----- ****/
$sql = "SELECT
      (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano,
      unidad_medida_defecto, principio_activo, presentacion, nombre_comercial,
      articulo_inventario, unidad_medida_defecto, 
      CONCAT(IFNULL(principio_activo, ''), ' - ', IFNULL(presentacion, ''), ' - ', IFNULL(nombre_comercial, '')) AS nombre, fabricante   
    FROM articulo
    WHERE id = '$articulo';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$unidad_medida = $row["unidad_medida_defecto"];
$nombre_articulo = $row["nombre"];
$fabricante = $row["fabricante"];
$articulo_inventario = $row["articulo_inventario"];
$cantidad_en_mano = intval($row["cantidad_en_mano"]);

$sql = "SELECT descripcion AS um FROM unidad_medida
  WHERE codigo = '$unidad_medida';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$unidad_medida_nombre = $row["um"];


//////////////// SE VALIDA QUE HAYA CANTIDAD DISPONIBLE DEL ARTICULO PARA AGREGAR AL DESPACHO ///////////////////
///// Se activa el 17-08-2020
if($articulo_inventario == "S") {
  if($cantidad > 0) {
      ////// Obtengo la cantidad en pedido de venta NUEVOS 21/08/2020 //////
      $sql = "SELECT 
                SUM(a.cantidad_movimiento) AS pedidos_nuevos 
              FROM 
                entradas_salidas AS a 
                JOIN salidas AS b ON
                  b.tipo_documento = a.tipo_documento
                AND b.id = a.id_documento 
                JOIN almacen AS c ON
                  c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo = '$almacen' 
              WHERE
                a.tipo_documento IN ('TDCPDV')
                AND a.articulo = $articulo AND b.estatus = 'NUEVO';";
      $rs = mysqli_query($link, $sql);
      $row = mysqli_fetch_array($rs);
      $pedidos_nuevos = floatval($row["pedidos_nuevos"]);
      //////////////////////////////////////////////////////////////////////
    // Se comenta la validadión de la cantidad existente en el bloque if
    if((($cantidad_en_mano - $cantidad_movimiento) + $pedidos_nuevos) < 0) {
      $error = 'La cantidad de ' . number_format($cantidad, 0, ".", ",") . ' ' . $unidad_medida_nombre . ' 
        solicitada para el artículo ' . $nombre_articulo  . '
        es mayor a la existencia actual.';
      echo "002|$error|0";
      die();
    }
  }
}
//////////////// SE VALIDA QUE HAYA CANTIDAD DISPONIBLE DEL ARTICULO PARA AGREGAR AL DESPACHO ///////////////////


$total = $precio * $cantidad;
$cantidad_movimiento = $cantidad_movimiento * (-1);

if($cantidad > 0) {
  $sql = "SELECT cantidad_movimiento FROM entradas_salidas 
      WHERE tipo_documento = '$tipo_documento' AND id_documento = $salida AND articulo = $articulo;"; 
  $rs = mysqli_query($link, $sql);
  if($row = mysqli_fetch_array($rs)) {
    $sql = "DELETE FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $salida AND articulo = $articulo;";
    $rs = mysqli_query($link, $sql);
  } 

  $sql = "INSERT INTO entradas_salidas
        (id, tipo_documento, id_documento, 
        fabricante, articulo, almacen, 
        cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
        precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc)
      VALUES 
        (NULL, '$tipo_documento', $salida, 
        $fabricante, $articulo, '$almacen', 
        $cantidad, '$unidad_medida', $cantidad_unidad, $cantidad_movimiento, 
        $precio, $total, $alicuota, $descuento, $precio_ful);
      ";
  mysqli_query($link, $sql);
}
else {
  $sql = "DELETE FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $salida AND articulo = $articulo;";
  $rs = mysqli_query($link, $sql);
}


//////////////// Actualizo Cabecera ////////////////

// Verifico si los articulos tienen una misma alicuota o varia por cada uno de ellos //
$sql = "SELECT 
      COUNT(DISTINCT alicuota ) AS cantidad  
    FROM 
      entradas_salidas
    WHERE 
      tipo_documento = '$tipo_documento' 
      AND id_documento = '$salida';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
if(intval($row["cantidad"]) > 1) $alicuota = 0;
else {
  $sql = "SELECT 
        DISTINCT alicuota 
      FROM 
        entradas_salidas
      WHERE 
        tipo_documento = '$tipo_documento' 
        AND id_documento = '$salida';";
  $rs = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($rs);
  $alicuota = floatval($row["alicuota"]);
}

// Se actualiza el encabezado del padido de venta //
$sql = "SELECT
      SUM(precio) AS precio, 
      SUM((precio * (alicuota/100))) AS iva, 
      SUM(precio) + SUM((precio * (alicuota/100))) AS total 
    FROM 
      entradas_salidas
    WHERE tipo_documento = '$tipo_documento' AND 
      id_documento = '$salida'";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$precio = floatval($row["precio"]);
$iva = floatval($row["iva"]);
$total = floatval($row["total"]);

$sql = "UPDATE salidas 
    SET
      monto_total = $precio,
      alicuota_iva = $alicuota, 
      iva = $iva,
      total = $total
    WHERE tipo_documento = '$tipo_documento' AND 
      id = '$salida'";
mysqli_query($link, $sql);

/* Se actualizan las cantidades de unidades en el encabezado de la salida */
$sql = "UPDATE 
      salidas AS a 
      JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas WHERE tipo_documento = 'TDCPDV' AND id_documento = $salida GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
    SET 
      a.unidades = b.cantidad 
    WHERE a.id = $salida;";
$rs = mysqli_query($link, $sql);
////////////

ActInv($articulo); 

echo "999|SUCCESS|$cantidad";
?>
