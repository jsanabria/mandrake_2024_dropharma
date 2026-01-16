<?php
include "../connect.php";


$pedido = intval($_REQUEST["pedido"]); 

$tipo_documento = "TDCFCV";

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

$LineByPage = 100;
$Pages = intval($cantidad/$LineByPage);
if((($cantidad/$LineByPage) - intval($cantidad/$LineByPage)) > 0) $Pages++;

$LineByPage = 50;

$html = "";
$sql = "SELECT 
          a.id, z.id AS id_item, 
          a.foto, a.nombre_comercial, b.nombre AS fabricante, 
          a.principio_activo, a.presentacion, z.precio_unidad_sin_desc AS precio_ful, 
          z.cantidad_articulo AS cantidad, 
          z.descuento, z.precio_unidad AS precio, z.precio as total, z.lote, z.fecha_vencimiento, z.almacen  
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
                  $xCant = intval($row["cantidad"]);
                  $xultimo_precio_ful = floatval($row["precio_ful"]);
                  $xdescuentoG = floatval($row["descuento"]);
                  $xultimo_precio = floatval($row["precio"]);
                  $precio = floatval($row["total"]);
                  $xlote = $row["lote"];
                  $xvence = $row["fecha_vencimiento"];
  // die(json_encode("Hello World - 13", JSON_UNESCAPED_UNICODE));
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xCant==0 ? "" : $xCant) . '" style="width: 80px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
               // die(json_encode("TEST " . $row["ultimo_precio_ful"], JSON_UNESCAPED_UNICODE)); 
                  $html .= '<input type="hidden" id="x' . $i . '_moneda" name="x' . $i . '_moneda" value="' . $moneda . '">';
                  $html .= '<input type="hidden" id="x' . $i . '_articulo" name="x' . $i . '_articulo" value="' . $row["id"] . '">';
// die(json_encode($sql3, JSON_UNESCAPED_UNICODE)); 

      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="text" class="form-control" id="x' . $i . '_lote" name="x' . $i . '_lote" size="4" value="' . $xlote . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="date" class="form-control" id="x' . $i . '_vence" name="x' . $i . '_vence" size="4" value="' . $xvence . '" style="width: 140px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="text" class="form-control" id="x' . $i . '_precioFull" name="x' . $i . '_precioFull" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xultimo_precio_ful==0 ? "" : number_format($xultimo_precio_ful, 2, ".", "")) . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_descuento" name="x' . $i . '_descuento" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . $xdescuentoG . '" style="width: 60px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
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
                    $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-trash" onclick="js:eliminar(' . $i . ', ' . $row["id_item"] . ')"></i></span>';
                  }
      $html .= '</td>';
    $html .= '</tr>';

    $i++;
}
      $html .= '<tr><td colspan="10"> <center><b>Registros ' . $i-1 . ' de ' . $cantidad . '</b></center> </td></tr>';

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>
