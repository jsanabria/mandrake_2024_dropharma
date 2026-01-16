<?php
include "../connect.php";

/*
$lp = explode(";", isset($_REQUEST["lista_pedido"]) ? trim(htmlspecialchars($_REQUEST["lista_pedido"])) : "");
$lista_pedido = $lp[0];
*/
$consignacion = isset($_REQUEST["consignacion"]) ? trim(htmlspecialchars($_REQUEST["consignacion"])) : "";
$fabricante = isset($_REQUEST["fabricante"]) ? trim(htmlspecialchars($_REQUEST["fabricante"])) : "";
$articulo = isset($_REQUEST["articulo"]) ? trim(htmlspecialchars($_REQUEST["articulo"])) : "";
$pedido = isset($_REQUEST["pedido"]) ? trim(htmlspecialchars($_REQUEST["pedido"])) : "";
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? trim(htmlspecialchars($_REQUEST["tipo_documento"])) : "";
$descuentoG = isset($_REQUEST["descuentoG"]) ? trim(htmlspecialchars($_REQUEST["descuentoG"])) : "";
$hubb = isset($_REQUEST["hubb"]) ? trim(htmlspecialchars($_REQUEST["hubb"])) : "NO";
$cliente = isset($_REQUEST["cliente"]) ? trim(htmlspecialchars($_REQUEST["cliente"])) : "";

$username = $_POST["username"];

$where = "";

/*** Busco la tarifa del cliente ***/
$sql = "SELECT tarifa FROM cliente WHERE id = '$cliente';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tarifa = intval($row["tarifa"]);

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$almacen = $row["ppal"];

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
$almacenconsig = $row["ppal"];


if($fabricante != "") $where .= " AND a.fabricante = $fabricante ";

/////////////
/// Busco la moneda por defecto del sistema ///
$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$moneda = $row["moneda"];

/// Busco la tasa del día ///
$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$tasa = floatval($row["tasa"]);
$tasa_usd = $tasa;

// die(json_encode("PASO CINCO " . $username, JSON_UNESCAPED_UNICODE)); 
/// Pregunto si el sistema factura en Bs ///
$sql = "SELECT valor1 AS fact_bs FROM parametro WHERE codigo = '053';";
$rs = mysqli_query($link, $sql); 
$row = mysqli_fetch_array($rs); 
$fact_bs = $row["fact_bs"];

if($fact_bs == "S") $moneda = "Bs.";
else $tasa = 1;

/////////////




$sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
$tipo_documento_inventario = 'TDCNET';
$rs = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($rs)) $tipo_documento_inventario = $row["tipo_documento"];


//////////////////
$pagination = "Hello World";
$sql = "SELECT 
          COUNT(a.id) AS cantidad  
        FROM 
          articulo AS a 
          LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
          INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
        WHERE 
          a.articulo_inventario = 'S'  
          AND (a.codigo LIKE '%$articulo%' OR a.nombre_comercial LIKE '%$articulo%' OR a.principio_activo LIKE '%$articulo%' OR a.codigo_de_barra = '%$articulo%')
          $where;"; 
// die(json_encode($sql, JSON_UNESCAPED_UNICODE));       
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$cantidad = $row["cantidad"];

$LineByPage = 50;

$html = "";
$sql = "SELECT 
          a.id, 
          IFNULL(a.foto, '') AS foto, a.nombre_comercial, b.nombre AS fabricante, 
          a.principio_activo, a.presentacion, c.precio AS precio_ful, 
          d.descripcion AS unidad_medida, 
          a.descuento, (c.precio - (c.precio * (a.descuento/100))) AS precio, 0 AS cantidad   
        FROM 
          articulo AS a 
          LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
          INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
          INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
        WHERE 
          a.activo = 'S' AND a.articulo_inventario = 'S' 
          AND (a.codigo LIKE '%$articulo%' OR a.nombre_comercial LIKE '%$articulo%' OR a.principio_activo LIKE '%$articulo%' OR a.codigo_de_barra = '$articulo')
          $where 
        ORDER BY a.principio_activo, a.presentacion LIMIT 0, $LineByPage;";
// $html = "$consignacion - $fabricante - $articulo - $sql";
// die(json_encode($sql, JSON_UNESCAPED_UNICODE)); 
$result = mysqli_query($link, $sql);
$i = 0;

         $html .= '<table class="table table-bordered table-hover table-striped table-sm">
          <thead>
            <tr>
              <th width="10%">&nbsp</th>
              <th width="20%">At&iacute;culo</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="10%" class="text-center">Lote</th>
              <th width="10%" class="text-center">Vence</th>
              <th width="10%" class="text-center">Precio Full</th>
              <th width="10%" class="text-center">% Desc.</th>
              <th width="10%" class="text-center">Precio</th>
              <th width="10%" class="text-center">Total</th>
              <th width="10%" class="text-center">Agr/Eli</th>
            </tr>
          </thead>
          <tbody>';
// echo json_encode($html, JSON_UNESCAPED_UNICODE);
// die();
$i = 1;
while ($row = mysqli_fetch_array($result)) { 

    // $url = 'https://www.dropharmadm.com/dropharma/carpetacarga/';
    $url = '../carpetacarga/';
    $html .= '<tr>';
      $html .= '<td>';
              
              // if(file_exists($url . $row["foto"]) and trim($row["foto"]) != "") {
                $html .= '<div class="thumbnail">
                  <a href="' . $url . $row["foto"] . '" target="_blank">
                    <img src="' . $url . $row["foto"] . '" alt="' . $row["nombre_comercial"] . '" width="100" class="img-thumbnail">
                  </a>
                </div>';
      
      $html .= '</td>';
      $html .= '<td><strong>' . $row["nombre_comercial"] . 
                    '</strong><br><small>' . $row["principio_activo"] . '</small><br>
                    <small><i>' . $row["presentacion"] . '</i></small><br>
                    <strong><small>Fabricante: ' . $row["fabricante"] . '<strong></small><br>
                    <small> Unidad</small>
                </td>';
      $html .= '<td class="text-center">'; 
                  $xCant = 0;
                  $xultimo_precio_ful = 0;
                  $sql3 = "SELECT precio FROM tarifa_articulo WHERE tarifa = $tarifa AND articulo = " . intval($row["id"]) . ";";
                  $result3 = mysqli_query($link, $sql3);
                  if($row3 = mysqli_fetch_array($result3)) 
                    $xultimo_precio_ful = floatval($row3["precio"]) * $tasa;
                  $xdescuentoG = 0; // floatval($descuentoG);
                  $xdescuento = floatval($row["descuento"]);
                  $xultimo_precio = $xultimo_precio_ful - ($xultimo_precio_ful*($xdescuento/100));
                  $precio = 0.00;
                  $xlote = "";
                  $xvence = "";
                  $id_item = 0;
                  if($hubb == "SI") {
                    $sql3 = "SELECT 
                                cantidad_articulo AS cantidad, precio_unidad_sin_desc, descuento, precio_unidad, precio, lote, fecha_vencimiento, id AS id_item  
                            FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $pedido AND articulo = " . intval($row["id"]) . " AND 0=1;";
                  } 
                  else {
                    $sql3 = "SELECT 
                                cantidad_articulo AS cantidad, precio_unidad_sin_desc, descuento, precio_unidad, precio, lote, fecha_vencimiento, id AS id_item  
                            FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $pedido AND articulo = " . intval($row["id"]) . ";";
                  }
// die(json_encode($sql3, JSON_UNESCAPED_UNICODE)); 
                  $result3 = mysqli_query($link, $sql3);
                  if($row3 = mysqli_fetch_array($result3)) {
                    $xCant = intval($row3["cantidad"]);
                    $xultimo_precio_ful = floatval($row3["precio_unidad_sin_desc"]);
                    $xdescuentoG = floatval($row3["descuento"]);
                    $xdescuento = floatval($row["descuento"]);
                    $xultimo_precio = floatval($row3["precio_unidad"]);
                    $precio = floatval($row3["precio"]);
                    $xlote = $row3["lote"];
                    $xvence = $row3["fecha_vencimiento"];
                    $id_item = $row3["id_item"];
                 } 
                if($tipo_documento_inventario == "TDCFCV") 
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" onkeyup="myCalc(' . $i . ');" onchange="validarCantidadLote(' . $i . ');" value="' . ($xCant==0 ? "" : $xCant) . '" style="width: 80px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
                else
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" onkeyup="myCalc(' . $i . ');" value="' . ($xCant==0 ? "" : $xCant) . '" style="width: 80px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
               // die(json_encode("TEST " . $row["ultimo_precio_ful"], JSON_UNESCAPED_UNICODE)); 
                  $html .= '<input type="hidden" id="x' . $i . '_moneda" name="x' . $i . '_moneda" value="' . $moneda . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_articulo" name="x' . $i . '_articulo" value="' . $row["id"] . '">';

      $html .= '</td>';
      $html .= '<td class="text-center">';  
                if($tipo_documento_inventario == "TDCFCV") {
                  $html .= '<select class="form-control" id="x' . $i . '_lote" name="x' . $i . '_lote" value="' . $xlote . '" style="width: 100px;"' . ($xCant==0 ? '' : 'disabled="disabled"') . ' onchange="js: setDateToLote(this.value, ' . $i . ');">';
                    $html .= '<option value="0|0|0|0"></option>';

                    $sql4 = "SELECT 
                              x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen, SUM(x.cantidad_movimiento) AS cantidad 
                            FROM 
                              (
                                SELECT 
                                   a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                                   a.fecha_vencimiento, 
                                   a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
                                FROM 
                                   entradas_salidas AS a 
                                   JOIN entradas AS b ON
                                       b.tipo_documento = a.tipo_documento
                                       AND b.id = a.id_documento 
                                   JOIN almacen AS c ON
                                       c.codigo = a.almacen AND c.movimiento = 'S'
                                WHERE 
                                   (
                                       (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                                       (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                                   ) AND a.articulo = " . intval($row["id"]) . " AND a.newdata = 'S' 
                                UNION ALL SELECT 
                                   a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                                   a.fecha_vencimiento, 
                                   a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen  
                                FROM 
                                   entradas_salidas AS a 
                                   JOIN salidas AS b ON
                                       b.tipo_documento = a.tipo_documento
                                       AND b.id = a.id_documento 
                                   JOIN almacen AS c ON
                                       c.codigo = a.almacen AND c.movimiento = 'S'
                                WHERE 
                                   (
                                       (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                                       (a.tipo_documento IN ('$tipo_documento_inventario', 'TDCASA') AND b.estatus <> 'ANULADO') 
                                   ) AND a.articulo = " . intval($row["id"]) . " AND a.newdata = 'S' 
                              ) AS x 
                            WHERE 1 
                            GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
                            ORDER BY x.fecha_vencimiento ASC;"; 
                    $result4 = mysqli_query($link, $sql4);
                    while($row4 = mysqli_fetch_array($result4)) {
                      $html .= '<option value="' . $row4["lote"] . '|' . $row4["fecha_vencimiento"] . '|' . intval($row4["cantidad"]) . '|' . $row4["codalm"] . '">' . $row4["lote"] . ' - ' . $row4["fecha"] . ' - ' . $row4["almacen"] . '(' . intval($row4["cantidad"]) . ')</option>';
                    }
                  $html .= '</select>';
                    
                } 
                else {
                  $html .= '<input type="text" class="form-control" id="x' . $i . '_lote" name="x' . $i . '_lote" size="4" value="' . $xlote . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
                }
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="date" class="form-control" id="x' . $i . '_vence" name="x' . $i . '_vence" size="4" value="' . ($xvence == "1990-01-01" ? "" : $xvence) . '" style="width: 140px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="text" class="form-control" id="x' . $i . '_precioFull" name="x' . $i . '_precioFull" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xultimo_precio_ful==0 ? "" : number_format($xultimo_precio_ful, 2, ".", "")) . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_descuento" name="x' . $i . '_descuento" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . $xdescuento . '" style="width: 60px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_precio" name="x' . $i . '_precio" size="4" readonly="yes" value="' . ($xultimo_precio==0 ? "" : number_format($xultimo_precio, 2, ".", "")) . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_total" name="x' . $i . '_total" size="4" readonly="yes" value="' . ($precio==0 ? "" : $precio) . '" style="width: 120px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  if($xCant == 0) {
                    $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-cart-shopping" onclick="js:insertar(' . $i . ')"></i></span>';
                  }
                  else {
                    $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-trash" onclick="js:eliminar(' . $i . ', ' . $id_item . ')"></i></span>';
                  }
      $html .= '</td>';
    $html .= '</tr>';

    $i++;
}
// die(json_encode("PASO DOS", JSON_UNESCAPED_UNICODE)); 

$html .= '<tr><td colspan="10"> <center><b>Registros ' . $i-1 . ' de ' . $cantidad . '</b></center> </td></tr>';


echo json_encode($html, JSON_UNESCAPED_UNICODE);

include "../desconnect.php";
?>
