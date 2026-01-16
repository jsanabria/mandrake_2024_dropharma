<?php
include "../connect.php";

/*
$lp = explode(";", isset($_REQUEST["lista_pedido"]) ? trim(htmlspecialchars($_REQUEST["lista_pedido"])) : "");
$lista_pedido = $lp[0];
*/

$fabricante = isset($_REQUEST["fabricante"]) ? trim(htmlspecialchars($_REQUEST["fabricante"])) : "";
$articulo = isset($_REQUEST["articulo"]) ? trim(htmlspecialchars($_REQUEST["articulo"])) : "";
$pedido = isset($_REQUEST["pedido"]) ? trim(htmlspecialchars($_REQUEST["pedido"])) : "";
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? trim(htmlspecialchars($_REQUEST["tipo_documento"])) : "";
$hubb = isset($_REQUEST["hubb"]) ? trim(htmlspecialchars($_REQUEST["hubb"])) : "NO";

//die(json_encode($hubb, JSON_UNESCAPED_UNICODE)); 
$username = $_POST["username"];

$where = "";

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$almacen = $row["ppal"];


if($fabricante != "") $where .= " AND a.fabricante = $fabricante ";


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

$LineByPage = 100;

$html = "";
$sql = "SELECT 
          a.id, 
          IFNULL(a.foto, '') AS foto, a.nombre_comercial, b.nombre AS fabricante, 
          a.principio_activo, a.presentacion, a.ultimo_costo, 
          0 AS cantidad_en_mano, 
          d.descripcion AS unidad_medida, 
          a.descuento, 0 AS cantidad 
        FROM 
          articulo AS a 
          LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
          INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto           
        WHERE 
          a.activo = 'S' 
          AND (a.codigo LIKE '%$articulo%' OR a.nombre_comercial LIKE '%$articulo%' OR a.principio_activo LIKE '%$articulo%' OR a.codigo_de_barra = '$articulo')
          $where 
        ORDER BY a.principio_activo, a.presentacion LIMIT 0, $LineByPage;"; 
// die(json_encode($sql, JSON_UNESCAPED_UNICODE)); 
// $html = "$consignacion - $fabricante - $articulo - $sql";
// echo json_encode("Hello World", JSON_UNESCAPED_UNICODE);
// die();

$result = mysqli_query($link, $sql);
$i = 0;

         $html .= '<table class="table table-bordered table-hover table-striped table-sm">
          <thead>
            <tr>
              <th width="10%">&nbsp</th>
              <th width="60%">At&iacute;culo</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="20%" class="text-center">Lote</th>
              <th width="20%" class="text-center">Vence</th>
              <th width="10%" class="text-center">Agr/Eli</th>
            </tr>
          </thead>
          <tbody>';
// echo json_encode($html, JSON_UNESCAPED_UNICODE);
// die();
$i = 1;
while ($row = mysqli_fetch_array($result)) { 

    //$url = 'https://www.dropharmadm.com/dropharma/carpetacarga/';
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
                  $xCant = intval($row["cantidad_en_mano"]);
                  $xlote = "";
                  $xvence = "";
                  $id_item = 0;
                  if($hubb == "SI") {
                    $sql3 = "SELECT 
                                cantidad_articulo AS cantidad, precio_unidad_sin_desc, descuento, costo_unidad, costo, lote, fecha_vencimiento, id AS id_item  
                            FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $pedido AND articulo = " . intval($row["id"]) . " AND 0=1;";
                  } 
                  else {
                    $sql3 = "SELECT 
                                cantidad_articulo AS cantidad, precio_unidad_sin_desc, descuento, costo_unidad, costo, lote, fecha_vencimiento, id AS id_item  
                            FROM entradas_salidas WHERE tipo_documento = '$tipo_documento' AND id_documento = $pedido AND articulo = " . intval($row["id"]) . ";";
                  }
                  $result3 = mysqli_query($link, $sql3);
                  if($row3 = mysqli_fetch_array($result3)) {
                    $xCant = intval($row3["cantidad"]);
                    $xlote = $row3["lote"];
                    $xvence = $row3["fecha_vencimiento"];
                    $id_item = $row3["id_item"];
                 } 
                  $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" value="' . ($xCant==0 ? "" : $xCant) . '" style="width: 80px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
                  $html .= '<input type="hidden" id="x' . $i . '_articulo" name="x' . $i . '_articulo" value="' . $row["id"] . '">';
// die(json_encode($sql3, JSON_UNESCAPED_UNICODE)); 

      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="text" class="form-control" id="x' . $i . '_lote" name="x' . $i . '_lote" size="4" value="' . $xlote . '" style="width: 100px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
      $html .= '</td>';
      $html .= '<td class="text-center">';  
                  $html .= '<input type="date" class="form-control" id="x' . $i . '_vence" name="x' . $i . '_vence" size="4" value="' . ($xvence == "1990-01-01" ? "" : $xvence) . '" style="width: 140px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
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

$html .= '<tr><td colspan="10"> <center><b>Registros ' . $i-1 . ' de ' . $cantidad . '</b></center> </td></tr>';


echo json_encode($html, JSON_UNESCAPED_UNICODE);

include "../desconnect.php";
?>
