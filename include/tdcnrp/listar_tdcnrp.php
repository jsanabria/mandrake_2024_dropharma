<?php
include "../connect.php";


$pedido = intval($_REQUEST["pedido"]); 

$tipo_documento = "TDCNRP";

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

$sql = "SELECT a.proveedor FROM entradas AS a WHERE a.id = $pedido AND a.tipo_documento = '$tipo_documento';";

$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs); 
$proveedor = $row["proveedor"];

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

$pagination = '<nav aria-label="Page navigation example">';
  $pagination .= '<ul class="pagination">';
    $pagination .= '<li class="page-item ' . ($pagina == 0 ? ' active' : '') . '">';
      $pagination .= '<a class="page-link" onclick="js: buscarItem2(0, 1);" href="#" aria-label="Previous">';
        $pagination .= '<span aria-hidden="true">&laquo;</span>';
      $pagination .= '</a>';
    $pagination .= '</li>';

for($i=0; $i<$Pages; $i++) {
      $pagination .= '<li class="page-item ' . ($pagina == ($i+1) ? ' active' : '') . '"><a class="page-link" onclick="js: buscarItem2(' . ($i+1) . ', 1);" href="#">' . ($i+1) . '</a></li>';
}

    $pagination .= '<li class="page-item ' . ($pagina == 9999 ? ' active' : '') . '">';
      $pagination .= '<a class="page-link" onclick="js: buscarItem2(9999, 1);" href="#" aria-label="Next">';
        $pagination .= '<span aria-hidden="true">&raquo;</span>';
      $pagination .= '</a>';
    $pagination .= '</li>';
  $pagination .= '</ul>';
$pagination .= '</nav>';


if($pagina == 9999) $start = ($Pages-1)*$LineByPage;
elseif($pagina == 0) $start = 0;
else $start = ($pagina-1)*$LineByPage;
//////////////////

$html = "";

$recpAll = '';
$sql = "SELECT COUNT(*) AS cantidad 
        FROM entradas_salidas 
        WHERE id_documento = $pedido AND tipo_documento = '$tipo_documento' AND check_ne = 'N';";
$result = mysqli_query($link, $sql);
if($row = mysqli_fetch_array($result)) {
  if($row["cantidad"] > 0) $recpAll = '<input class="form-check-input" type="checkbox" value="1" id="recepAll" onclick="js: recepAll();">';
  else $recpAll = '<input class="form-check-input" type="checkbox" value="1" checked="checked" id="recepAll" onclick="js: recepAll();">';
}

$sql = "SELECT 
          a.id, z.id AS id_item, 
          a.foto, a.nombre_comercial, b.nombre AS fabricante, 
          a.principio_activo, a.presentacion, z.precio_unidad_sin_desc AS costo_ful, 
          z.cantidad_articulo AS cantidad, 
          z.descuento, z.costo_unidad AS costo, z.costo as total, z.lote, z.fecha_vencimiento, z.check_ne  
        FROM 
          entradas_salidas AS z 
          INNER JOIN articulo AS a ON a.id = z.articulo 
          LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
        WHERE 
         z.id_documento = $pedido AND tipo_documento = '$tipo_documento' 
        ORDER BY a.principio_activo, a.presentacion LIMIT $start, $LineByPage;"; 
$result = mysqli_query($link, $sql);

/*
$html = $sql;
echo json_encode($html, JSON_UNESCAPED_UNICODE);
die();
*/

$i = 0;

         $html .= '<table class="table table-bordered table-hover table-striped table-sm">
          <thead>
            <tr>
              <td colspan="10">
                <div class="col-12 d-flex justify-content-center" id="Paginacion1">' . $pagination . '
                </div>
              </td>
            </tr>          
            <tr>
              <th width="10%">&nbsp</th>
              <th width="20%">At&iacute;culo</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="10%" class="text-center">Lote</th>
              <th width="10%" class="text-center">Vence</th>
              <th width="10%" class="text-center">Costo Full</th>
              <th width="10%" class="text-center">% Desc.</th>
              <th width="10%" class="text-center">Costo</th>
              <th width="10%" class="text-center">Total</th>
              <th width="10%" class="text-center">' . $recpAll . '<br>(Agr/Eli)</th>
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
                  $xultimo_costo_ful = floatval($row["costo_ful"]);
                  $xdescuentoG = floatval($row["descuento"]);
                  $xultimo_costo = floatval($row["costo"]);
                  $costo = floatval($row["total"]);
                  $xlote = $row["lote"];
                  $xvence = $row["fecha_vencimiento"];
                  $check_ne = $row["check_ne"];
  // die(json_encode("Hello World - 13", JSON_UNESCAPED_UNICODE));
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xCant==0 ? "" : $xCant) . '" style="width: 80px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
               // die(json_encode("TEST " . $row["ultimo_costo_ful"], JSON_UNESCAPED_UNICODE)); 
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
                  $html .= '<input type="text" class="form-control" id="x' . $i . '_costoFull" name="x' . $i . '_costoFull" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . ($xultimo_costo_ful==0 ? "" : number_format($xultimo_costo_ful, 2, ".", "")) . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_descuento" name="x' . $i . '_descuento" size="4" onkeyup="myCalc(' . $i . ');" onchange="myCalc(' . $i . ');" value="' . $xdescuentoG . '" style="width: 60px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_costo" name="x' . $i . '_costo" size="4" readonly="yes" value="' . ($xultimo_costo==0 ? "" : number_format($xultimo_costo, 2, ".", "")) . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_total" name="x' . $i . '_total" size="4" readonly="yes" value="' . ($costo==0 ? "" : $costo) . '" style="width: 120px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<br><input type="checkbox" id="x' . $i . '_checkne" name="x' . $i . '_checkne" ' . ($check_ne=="S" ? "checked" : "") . ' onclick="js:check_item(' . $row["id_item"] . ')"><br><br> ';
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
