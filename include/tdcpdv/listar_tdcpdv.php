<?php
include "../connect.php";

$pedido = intval($_REQUEST["pedido"]); 

$tipo_documento = "TDCPDV";

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$almacen = $row["ppal"];

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
$almacenconsig = $row["ppal"];

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$tasa = floatval($row["tasa"]);

$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$moneda = $row["moneda"];

$sql = "SELECT a.cliente FROM salidas AS a WHERE a.id = $pedido AND a.tipo_documento = '$tipo_documento';";

$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$cliente = $row["cliente"];

/*** Busco la tarifa del cliente ***/
$sql = "SELECT tarifa FROM cliente WHERE id = '$cliente';"; 
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$tarifa = intval($row["tarifa"]);

$pagina = intval(trim(isset($_REQUEST["pagina"])?$_REQUEST["pagina"]:"0"));
//////////////////
$pagination = "Hello World";
$sql = "SELECT 
          COUNT(a.id) AS cantidad 
        FROM 
           entradas_salidas AS z 
          INNER JOIN articulo AS a ON a.id = z.articulo 
          LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
        WHERE 
         z.id_documento = $pedido AND z.tipo_documento = '$tipo_documento'"; 
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

$cantidad = $row["cantidad"];

$LineByPage = 50;
$Pages = intval($cantidad/$LineByPage);
if((($cantidad/$LineByPage) - intval($cantidad/$LineByPage)) > 0) $Pages++;

$LineByPage = 100;

$html = "";
$sql = "SELECT 
          a.id, z.id AS id_item, 
          a.foto, a.nombre_comercial, b.nombre AS fabricante, 
          a.principio_activo, a.presentacion, z.precio_unidad_sin_desc AS precio_ful, 
          z.cantidad_articulo AS cantidad, a.cantidad_en_mano, 
          z.descuento, z.precio_unidad AS precio, z.precio as total, a.lote, a.fecha_vencimiento, z.almacen  
        FROM 
          entradas_salidas AS z 
          INNER JOIN articulo AS a ON a.id = z.articulo 
          LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
        WHERE 
         z.id_documento = $pedido AND tipo_documento = '$tipo_documento' 
        ORDER BY a.principio_activo, a.presentacion LIMIT 0, $LineByPage;"; 
// $html = "$consignacion - $fabricante - $articulo - $sql";
// die(json_encode($sql, JSON_UNESCAPED_UNICODE)); 
$result = mysqli_query($link, $sql);
$i = 0;

         $html .= '<table class="table table-bordered table-hover table-striped table-sm">
          <thead>
            <tr>
              <td colspan="8">
                <div class="col-12 d-flex justify-content-center" id="Paginacion1">' . $pagination . '
                </div>
              </td>
            </tr>          
            <tr>
              <th width="10%">&nbsp</th>
              <th width="40%">At&iacute;culo</th>
              <th width="10%" class="text-center">Precio Bs</th>
              <th width="10%" class="text-center">REF.</th>
              <th width="10%" class="text-center">Disponible</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="10%" class="text-center">Agr/Eli</th>
            </tr>
          </thead>
          <tbody>';
$i = 1;
$FechaVencimiento = "00/00/0000";
while ($row = mysqli_fetch_array($result)) { 
    $FechaVencimiento = $row["fecha_vencimiento"];
    $id_item = $row["id_item"];

    // $url = 'https://www.dropharmadm.com/dropharma/carpetacarga/';
    $url = '../admin/carpetacarga/';
    $html .= '<tr>';
      $html .= '<td>';
              
              // if(file_exists('../carpetacarga/' . $row["foto"]) and trim($row["foto"]) != "") {
                $html .= '<div class="thumbnail">
                  <a href="' . $url . $row["foto"] . '" target="_blank">
                    <img src="' . $url . $row["foto"] . '" alt="' . $row["nombre_comercial"] . '" width="100" class="img-thumbnail">
                  </a>
                </div>';
              /*}
              else {
                $html .= '<div class="thumbnail"><input type="hidden" class="form-control" id="x' . $i . '_articulo" name="x' . $i . '_articulo" size="6" value="' . $row["id"] . '">
                      <div class="caption">' . $row["nombre_comercial"] . ' Fec. Venc. '. $FechaVencimiento . '</div></div>';
              }*/
      
      $html .= '</td>';
      $html .= '<td><strong>' . $row["nombre_comercial"] . 
                    '</strong><br><small>' . $row["principio_activo"] . '</small><br>
                    <small><i>' . $row["presentacion"] . '</i></small><br>
                    <strong><small>Fabricante: ' . $row["fabricante"] . '<strong></small><br>
                    <small><strong> Fec. Venc. ' . $FechaVencimiento . '</strong></small>' . (floatval($row["descuento"]) > 0 ? ' | Descuento: <span class="badge text-bg-info">' . number_format(floatval($row["descuento"]), 2, "," ,".") . '%</span>' : '') . ' | <small>Unidad</small></td>';
      $html .= '<td class="text-center">';  
                  if(floatval($row["descuento"]) > 0) {
                    if($moneda == "USD") {
                      $html .= '<span class="text-primary"><strong>' . number_format(round($row["precio"], 2)*$tasa, 2, ".", ",") . '</strong></span><br>';
                      // $html .= '<span class="text-dark"><strong><del>' . number_format(floatval($row["precio_ful"]*$tasa), 2, "," ,".") . '</del></strong></span>';
                    }
                    else {
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span><br>';
                      $html .= '<span class="text-dark"><strong><del>' . number_format(floatval($row["precio_ful"]), 2, "," ,".") . '</del></strong></span>';
                    }
                  } 
                  else { 
                    if($moneda == "USD") 
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"]*$tasa, 2, ".", ",") . '</strong></span>';
                    else 
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span>';
                  }
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  if(floatval($row["descuento"]) > 0) { 
                    if($moneda == "USD") { 
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span><br>';
                      $html .= '<span class="text-dark"><strong><del>' . number_format($row["precio_ful"], 2, "," ,".") . '</del></strong></span>';  
                    }
                    else 
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"]/$tasa, 2, ".", ",") . '</strong></span><br>';
                  } 
                  else { 
                    if($moneda == "USD")  
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span>';
                    else 
                      $html .= '<span class="text-primary"><strong>' . number_format($row["precio"]/$tasa, 2, ".", ",") . '</strong></span>';
                  }
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $onHand = floatval($row["cantidad_en_mano"]);
                  if($onHand < 0) $onHand = 0;
                  $html .= '<span class="text-dark"><strong>' . number_format($onHand, 0, "", "") . '</strong></span>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" value="' . (intval($row["cantidad"])==0 ? "" : intval($row["cantidad"])) . '" style="width: 80px;" ' . (intval($row["cantidad"])==0 ? '' : 'disabled="disabled"') . '>';
                  $html .= '<input type="hidden" id="x' . $i . '_precio" name="x' . $i . '_precio" value="' . round($row["precio"], 2) . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_descuento" name="x' . $i . '_descuento" value="' . round($row["descuento"], 2) . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_precioFull" name="x' . $i . '_precioFull" value="' . round($row["precio_ful"], 2) . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_moneda" name="x' . $i . '_moneda" value="' . $moneda . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_onhand" name="x' . $i . '_onhand" value="' . $onHand . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_articulo" name="x' . $i . '_articulo" value="' . $row["id"] . '">';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  if(intval($row["cantidad"]) == 0) {
                    $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-cart-shopping" onclick="js:insertar(' . $i . ')"></i></span>';
                  }
                  else {
                    $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-trash" onclick="js:eliminar(' . $i . ', ' . $id_item . ')"></i></span>';
                  }
                  // $html .= '<i class="fa-solid fa-trash"></i>';
      $html .= '</td>';
    $html .= '</tr>';

    $i++;
}
      $html .= '<tr><td colspan="7"> <center><b>Registros ' . $i-1 . ' de ' . $cantidad . '</b></center> </td></tr>';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
