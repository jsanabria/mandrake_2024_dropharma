<?php

namespace PHPMaker2024\mandrake;

// Page object
$PedidoDeVentaDetalle = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php 
$id = trim($_REQUEST["id"]);
$art = trim(isset($_REQUEST["art"])?$_REQUEST["art"]:"");
$pagina = intval(trim(isset($_REQUEST["pagina"])?$_REQUEST["pagina"]:"0"));

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
$almacen = ExecuteScalar($sql);

$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
$almacenconsig = ExecuteScalar($sql);

$header = ''; 
$contador = 0;

if($id == "") {
  header("Location: SalidasList?tipo=TDCPDV");
  die();
}

/////////////////
$fabricantes = FiltraFabricantes(); 
$fabricantes = str_replace("id IN (", "a.fabricante IN (", $fabricantes);
if(trim($fabricantes) != "") $fabricantes .= " AND "; 
/////////////////

$TotGen = 0;
$Cant = 0;

$sql = "SELECT valor1 FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$moneda = ExecuteScalar($sql);

$sql = "SELECT 
          date_format(a.fecha, '%d/%m/%Y') AS fecha, a.nro_documento, b.nombre AS cliente, 
          a.cliente AS codcliente, a.estatus, b.ci_rif, b.direccion, nota, lista_pedido, IFNULL(a.nombre, '') AS cerrado  
        FROM 
          salidas AS a 
          LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
        WHERE 
          a.id = $id;"; 
if($row = ExecuteRow($sql)) {
  if(VerificaFuncion('006') == false) {
    if($row["estatus"] != "NUEVO") {
      header("Location: SalidasList?tipo=TDCPDV");
      die();
    }

    if($row["cerrado"] != "") {
      header("Location: SalidasList?tipo=TDCPDV");
      die();
    }
  }

  $documento = $row["nro_documento"];
  $fecha = $row["fecha"];
  $cliente = $row["cliente"];
  $codcliente = $row["codcliente"];
  $ci_rif = $row["ci_rif"];
  $direccion = $row["direccion"];
  $nota = $row["nota"];
  $lista_pedido = $row["lista_pedido"];
} 
else {
  header("Location: SalidasList?tipo=TDCPDV");
  die();
}

$where = "";
if(trim($lista_pedido) != "") 
  $where = " AND a.lista_pedido = '$lista_pedido' ";

$sql = "SELECT tarifa FROM cliente WHERE id = $codcliente"; 
$tarifa = intval(ExecuteScalar($sql));


//////////////////////
$facturas = "";

$sql = "SELECT 
          nro_documento,
          (TIMESTAMPDIFF(DAY, fecha_entrega, CURDATE()) - IFNULL(dias_credito, 0)) AS dias 
        FROM 
          salidas 
        WHERE 
          tipo_documento = 'TDCFCV' AND 
          cliente = $codcliente AND 
          pagado = 'N' AND 
          entregado = 'S' AND 
          IFNULL(dias_credito, 0) > 0 AND 
          TIMESTAMPDIFF(DAY, fecha_entrega, CURDATE()) > IFNULL(dias_credito, 0);"; 
$rows = ExecuteRows($sql);
foreach ($rows as $key => $value) {
  $facturas .= $value["nro_documento"] . " d&iacute;as vencida " . $value["dias"] . '<br>';
}

if(trim($facturas) != "") {
  $header = '<div class="alert alert-danger" role="alert">
      <h3>!!! Cliente con facturas vencidas !!!</h3>' . $facturas . '
      </div>';
}
//////////////////////

?>
<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>-->
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>

<div class="container">
    <?php
    if (isset($_SESSION['error']) && $_SESSION['error']) {
      echo '<div class="alert alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Alerta!</strong> ' . $_SESSION['error'] . '
          </div>';
      // printf('<b>%s</b>', $_SESSION['error']);
      unset($_SESSION['error']);
    }
    ?>
<?php echo $header; ?>
  <form name="frm" class="form-horizontal" method="post" action="PedidoDeVentaDetalleGuardar">
    <div class="form-group">
      <label class="control-label col-sm-2" for="documento">Nro Documento</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="documento" name="documento" placeholder="Nro Documento" readonly="yes" value="<?php echo $documento; ?>">
        <a class="btn btn-info" target="_blank" href="reportes/listado_articulos_por_tarifa.php?codcliente=<?php echo $codcliente; ?>&tarifa=">Tarifa Articulos</a>

      <!--<label class="control-label col-sm-2" for="fecha">Fecha</label>-->
        <input type="text" class="form-control" id="fecha" name="fecha" placeholder="Fecha" readonly="yes" value="<?php echo $fecha; ?>">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <input type="hidden" id="moneda" name="moneda" value="<?php echo $moneda; ?>">
        <input type="hidden" id="username" name="username" value="<?php echo CurrentUserName(); ?>">
        <input type="hidden" id="pagina" name="pagina" value="<?php echo $pagina; ?>">
        <input type="hidden" id="switch_page" name="switch_page" value="NO">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="cliente">Cliente</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Cliente" readonly="yes" value="<?php echo $cliente; ?>" size="60">
        <input type="text" class="form-control" id="ci_rif" name="ci_rif" placeholder="RIF" readonly="yes" value="R.I.F: <?php echo $ci_rif; ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="direccion">Direcci&oacute;n</label>
      <div class="col-sm-10">
        <textarea class="form-control" id="direccion" name="direccion" placeholder="DIRECCION" readonly="yes" rows="2" cols="96"><?php echo $direccion; ?></textarea>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="direccion">Nota</label>
      <div class="col-sm-10">
        <textarea class="form-control" id="nota" name="nota" placeholder="Nota" rows="2" cols="96"><?php echo $nota; ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <div class="input-group">
      <div class="col-sm-6">
        <?php 
        if($tarifa == 0) {
          echo '<div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error: </span>
                El cliente no tiene una tarifa asociada
              </div>';
        }
        else {
          echo '<button type="submit" class="btn btn-primary" id="btnSend" name="btnSend" onclick="js:return validar_envio();" value="Se Envia">Guardar Pedido</button>';

          if(VerificaFuncion('006')) {
          echo ' <a class="btn btn-warning" href="SalidasEdit?showdetail=&id=' . $id . '&tipo=TDCPDV">Cambiar Estatus</a>';
          }
        }
        ?>

      </div>

      <div class="col-sm-6">
        <div class="input-group-btn" id="result">
        <input type="text" class="form-control" aria-label="..." id="txtArticulo" name="txtArticulo">
          <button type="submit" class="btn btn-default" id="btnFind" name="btnFind" onclick="js: buscarItem(0);  "><span class="fas fa-search"></span></button>
          <button type="button" class="btn btn-default" id="btnFind2" onclick="js: limpiarItem();  "><span class="fas fa-eraser"></span></button>
        </div><!-- /btn-group -->
        </div><!-- /input-group -->
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover table-striped">
        <thead>
          <tr>
            <td colspan="8">
              <div class="col-12 d-flex justify-content-center" id="Paginacion1">
              </div>
            </td>
          </tr>          
          <tr>
            <td colspan="7" align="right">
              <strong><div id="MyCant2">Total Productos Seleccionados: <?php echo $Cant; ?> de <?php echo $contador; ?></div></strong></td>
            <td align="right"><strong><div id="MyTot2"><?php echo number_format($TotGen, 1,".", ","); ?> <?php echo $moneda; ?></div></strong></td>
          </tr>
          <tr>
            <th width="10%">&nbsp</th>
            <th width="15%">Fabricante</th>
            <th width="20%">At&iacute;culo</th>
            <th width="20%">Presentaci&oacute;n</th>
            <th width="10%">Precio</th>
            <th width="6%">Cantidad</th>
            <!--<th width="5%">U.M.</th>-->
            <th width="5%">Disponible</th>
            <th width="14%">Total</th>
          </tr>
        </thead>
        <tbody>


          <?php 

          if($art == "") {
            $sql = "SELECT 
                      a.id, 
                      a.foto, a.nombre_comercial, b.nombre AS fabricante, 
                      a.principio_activo, a.presentacion, c.precio AS precio_ful, 
                      (CAST(a.cantidad_en_mano AS SIGNED)-CAST(a.cantidad_en_pedido AS SIGNED)) AS cantidad_en_mano, 
                      d.descripcion AS unidad_medida, 
                      a.descuento, (c.precio - (c.precio * (a.descuento/100))) AS precio  
                    FROM 
                      articulo AS a 
                      LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                      INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
                      INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
                    WHERE 
                      $fabricantes 
                      a.activo = 'S' AND a.articulo_inventario = 'S' AND a.cantidad_en_mano > 0 AND b.activo = 'S'  
                      $where 
                    ORDER BY a.principio_activo, a.presentacion;"; 
          }
          else {
            $sql = "SELECT 
                      1 AS myorder, 
                      a.id, 
                      a.foto, a.nombre_comercial, b.nombre AS fabricante, 
                      a.principio_activo, a.presentacion, c.precio AS precio_ful, 
                      (CAST(a.cantidad_en_mano AS SIGNED)-CAST(a.cantidad_en_pedido AS SIGNED)) AS cantidad_en_mano, 
                      d.descripcion AS unidad_medida, 
                      a.descuento, (c.precio - (c.precio * (a.descuento/100))) AS precio   
                    FROM 
                      articulo AS a 
                      LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                      INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
                      INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
                    WHERE 
                      $fabricantes 
                      a.activo = 'S' AND a.articulo_inventario = 'S' AND a.cantidad_en_mano > 0 AND b.activo = 'S'  
                      $where 
                      AND (a.principio_activo LIKE '%$art%' OR a.nombre_comercial LIKE '%$art%') 
                    UNION ALL 
                    SELECT 
                      2 AS myorder, 
                      a.id, 
                      a.foto, a.nombre_comercial, b.nombre AS fabricante, 
                      a.principio_activo, a.presentacion, c.precio AS precio_ful, 
                      (a.cantidad_en_mano+a.cantidad_en_pedido)-a.cantidad_en_transito AS cantidad_en_mano, 
                      d.descripcion AS unidad_medida, 
                      a.descuento, (c.precio - (c.precio * (a.descuento/100))) AS precio   
                    FROM 
                      articulo AS a 
                      LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                      INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $tarifa 
                      INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
                      INNER JOIN entradas_salidas AS e ON e.fabricante = a.fabricante AND e.articulo = a.id 
                    WHERE 
                      $fabricantes 
                      a.activo = 'S' AND a.articulo_inventario = 'S' AND a.cantidad_en_mano > 0 
                      $where 
                      AND e.tipo_documento = 'TDCPDV' AND e.id_documento = $id 
                    ORDER BY myorder, principio_activo, presentacion;"; 
          } 

          $rows = ExecuteRows($sql);
          $cantidad = count($rows);

          $LineByPage = 200;
          $Pages = intval($cantidad/$LineByPage);
          if((($cantidad/$LineByPage) - intval($cantidad/$LineByPage)) > 0) $Pages++;


          $pagination = '<nav aria-label="Page navigation example">';
            $pagination .= '<ul class="pagination">';
              $pagination .= '<li class="page-item">';
                $pagination .= '<a class="page-link" onclick="js: buscarItem2(0);" aria-label="Previous">';
                  $pagination .= '<span aria-hidden="true">&laquo;</span>';
                $pagination .= '</a>';
              $pagination .= '</li>';
          for($i=0; $i<$Pages; $i++) {
                $pagination .= '<li class="page-item" ' . ($pagina == ($i+1) ? ' class="active"' : '') . '><a class="page-link" onclick="js: buscarItem2(' . ($i+1) . ');">' . ($i+1) . '</a></li>';
          }
              $pagination .= '<li class="page-item">';
                $pagination .= '<a class="page-link" onclick="js: buscarItem2(9999);" aria-label="Next">';
                  $pagination .= '<span aria-hidden="true">&raquo;</span>';
                $pagination .= '</a>';
              $pagination .= '</li>';
            $pagination .= '</ul>';
          $pagination .= '</nav>';


          if($pagina == 9999) $start = ($Pages-1)*$LineByPage;
          elseif($pagina == 0) $start = 0;
          else $start = ($pagina-1)*$LineByPage;

          $contador = $start;
          $Tot = 0.00;
          $Cant = 0;


            $i = 0;
            for($i=$start; $i<(($pagina==0 or $pagina==99999) ? $LineByPage : ($pagina*$LineByPage)); $i++) {
              if($i>($cantidad-1)) break;

              if($row = $rows[$i]) {
                $sql = "SELECT 
                          a.cantidad_articulo, a.precio_unidad, a.precio  
                        FROM 
                          entradas_salidas AS a 
                        WHERE 
                          a.tipo_documento = 'TDCPDV' AND a.id_documento = $id AND a.articulo = " . $row["id"] . ";"; 
                if($row2 = ExecuteRow($sql)) {
                  $cantidad_articulo = floatval($row2["cantidad_articulo"]);
                  $precio_unidad_articulo = floatval($row2["precio_unidad"]);
                  $precio_articulo = floatval($row2["precio"]);
                }
                else {
                  $cantidad_articulo = 0;
                  $precio_unidad_articulo = 0;
                  $precio_articulo = 0;                  
                }
                

                ////// Obtengo la fecha de vencimiento del lote proximo a vencerse NUEVOS 31/08/2021 //////
                $sql = "SELECT 
                    a.id, a.lote, date_format(a.fecha_vencimiento, '%d/%m/%Y') AS fecha_vencimiento, 
                    (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad,
                    a.id_documento ,a.tipo_documento
                  FROM 
                    entradas_salidas AS a 
                    JOIN entradas AS b ON
                      b.tipo_documento = a.tipo_documento
                      AND b.id = a.id_documento 
                    JOIN almacen AS c ON
                      c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
                    LEFT OUTER JOIN (
                        SELECT 
                          a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
                        FROM 
                          entradas_salidas AS a 
                          JOIN salidas AS b ON
                            b.tipo_documento = a.tipo_documento
                            AND b.id = a.id_documento 
                          LEFT OUTER JOIN almacen AS c ON
                            c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
                        WHERE
                          a.tipo_documento IN ('TDCNET','TDCASA') 
                          AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '" . $row["id"] . "' 
                        GROUP BY a.id_compra
                      ) AS d ON d.id = a.id 
                  WHERE
                    ((a.tipo_documento IN ('TDCNRP','TDCAEN') 
                    AND b.estatus = 'PROCESADO')
                     OR
                    (a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
                    AND b.estatus = 'NUEVO')) AND a.articulo = '" . $row["id"] . "' 
                    AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0 
                  ORDER BY a.fecha_vencimiento ASC, 1 LIMIT 0, 1;"; 
                $row3 = ExecuteRow($sql);
                // $FechaVencimiento = $row3["fecha_vencimiento"];
                $FechaVencimiento = isset($row3["fecha_vencimiento"]) ? $row3["fecha_vencimiento"] : "";
                //////////////////////////////////////////////////////////////////////

                echo '<tr>';
                  echo '<td>';
                          if(file_exists('carpetacarga/' . $row["foto"]) and trim($row["foto"]) != "") {
                            echo '<div class="thumbnail">
                              <a href="carpetacarga/' . $row["foto"] . '" target="_blank">
                                <input type="hidden" class="form-control" id="x' . $i . '_articulo" name="x' . $i . '_articulo" size="6" value="' . $row["id"] . '">
                                <img src="carpetacarga/' . $row["foto"] . '" alt="' . $row["nombre_comercial"] . '" width="70">
                                <div class="caption">
                                  ' . $row["nombre_comercial"] . ' Fec. Venc. '. $FechaVencimiento . '
                                </div>
                              </a>
                            </div>';
                          }
                          else {
                            echo '<div class="thumbnail"><input type="hidden" class="form-control" id="x' . $i . '_articulo" name="x' . $i . '_articulo" size="6" value="' . $row["id"] . '">
                                  <div class="caption">' . $row["nombre_comercial"] . ' Fec. Venc. '.$FechaVencimiento . '</div></div>';
                          }
                  echo '</td>';
                  echo '<td>' . $row["fabricante"] . '</td>';
                  echo '<td>' . $row["principio_activo"] . '</td>';
                  echo '<td>' . $row["presentacion"] . '</td>';
                  if(floatval($row["descuento"]) > 0)
                    echo '<td align="right"><i>Descuento 
                            ' . number_format(floatval($row["descuento"]), 2, "," ,".") . '% sobre ' . number_format(floatval($row["precio_ful"]), 2, "," ,".") . '=</i><b><div id="x' . $i . '_precio">
                            ' . number_format(($cantidad_articulo==0?$row["precio"]:($precio_unidad_articulo>0?$precio_unidad_articulo:$row["precio"])), 2, ".", ",") . ' ' . $moneda . '</div></b>
                          </td>';
                  else 
                    echo '<td align="right"><div id="x' . $i . '_precio">
                            ' . number_format(($cantidad_articulo==0?$row["precio"]:($precio_unidad_articulo>0?$precio_unidad_articulo:$row["precio"])), 2, ".", ",") . ' ' . $moneda . '
                            </div> 
                          <!--<input type="hidden" id="x' . $i . '_precio" name="x' . $i . '_precio" value="' . ($cantidad_articulo==0?$row["precio"]:($precio_unidad_articulo>0?$precio_unidad_articulo:$row["precio"])) . '">-->
                          <!--<input type="hidden" id="x' . $i . '_moneda" name="x' . $i . '_moneda" value="' . $moneda . '">-->
                          <!--<input type="hidden" id="x' . $i . '_onhand" name="x' . $i . '_onhand" value="' . ($cantidad_articulo==0?$row["cantidad_en_mano"]:intval($row["cantidad_en_mano"]) + $cantidad_articulo) . '">-->
                        </td>';
                  echo '<td><input type="text" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="6" onkeyup="js:precioTotalLinea(this.name, this.value);" onchange="js:guardarRegitro(' . $id . ', ' . $codcliente . ', ' . $row["id"] . ', this.name, this.value);" value="' . ($cantidad_articulo==0?"":$cantidad_articulo) . '"></td>';
                  //$onHand = floatval($row["cantidad_en_mano"]) + $pedidos_nuevos;
                  $onHand = floatval($row["cantidad_en_mano"]);
                  if($onHand < 0) $onHand = 0;
                  echo '<td>' . number_format($onHand, 0, "", "") . " " . $row["unidad_medida"] . '</td>';
                  if($cantidad_articulo==0) {
                    $Tot = $cantidad_articulo * $row["precio"];
                  }
                  else {
                    $Tot = $cantidad_articulo * ($precio_unidad_articulo>0?$precio_unidad_articulo:$row["precio"]);
                  }
                  $TotGen += $Tot;
                  if($cantidad_articulo > 0) $Cant++;
                  echo '<td align="right"><div id="x' . $i . '_total">' . number_format($Tot, 1,".", ",") . ' ' . $moneda . '</div></td>';
                echo '</tr>';

                $contador++;
              }
            }
          ?>
          <tr>
          </tr>
          <tr>
            <td colspan="7" align="right">
              <input type="hidden" id="contador" name="contador" value="<?php echo $contador; ?>">
              <input type="hidden" id="start" name="start" value="<?php echo $start; ?>">
              <strong><div id="MyCant">Total Productos Seleccionados: <?php echo $Cant; ?> de <?php echo $contador; ?></div></strong></td>
            <td align="right"><strong><div id="MyTot"><?php echo number_format($TotGen, 1,".", ","); ?> <?php echo $moneda; ?></div></strong></td>
          </tr>
          <tr>
            <td colspan="8" align="center">
              <div class="col-12 d-flex justify-content-center" id="Paginacion2">
              </div>
            </td>
          </tr>          
        </tbody>
      </table>
    </div>
  </form>
</div>

<script type="text/javascript">
  <!-- jQuery(document).ready(function($){ -->
  $(document).ready(function() {
     ContarSumarSeleccionado();

     $("#Paginacion1").html('<?php echo $pagination; ?>')
     $("#Paginacion2").html('<?php echo $pagination; ?>')
  });


  function precioTotalLinea(control, cantidad) {
    if(isNaN(cantidad) === true || cantidad.trim() === "") {
      $("#" + control).val("");
      ContarSumarSeleccionado();
      return false;
    }
    var xCant = parseFloat(cantidad);

    control = control.replace("cantidad", "precio");
    var xPrecio = parseFloat($("#" + control).html().replace(/\,/g, "")) * xCant;
    control = control.replace("precio", "moneda");
    var xMoneda = $("#moneda").val();


    control = control.replace("moneda", "onhand");
    
    control = control.replace("onhand", "total");

    $("#" + control).html(formatNumber(xPrecio) + " " + xMoneda);

    ContarSumarSeleccionado();
  }

  function formatNumber(number)
  {
      number = number.toFixed(2) + '';
      x = number.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
  }  

  function ContarSumarSeleccionado() {
    var i =0;
    var controlCantidad = "";
    var controlPrecio = "";
    var contador = 0;
    var total = 0.00;
    var xMoneda = $("#moneda").val();
    var xUnidades = 0;
    
    for(i = 0; i < <?php echo $contador; ?>; i++) {
      controlCantidad = "x" + i + "_cantidad";
      controlPrecio = "x" + i + "_precio";

      if(parseFloat($("#" + controlCantidad).val()) > 0) {
        contador++; 
        total += parseFloat($("#" + controlCantidad).val()) * parseFloat($("#" + controlPrecio).html().replace(/\,/g, ""));
        xUnidades += parseFloat($("#" + controlCantidad).val());
      }
    }

    $("#MyCant").html("Total Unidades " + xUnidades + " en Total Productos Seleccionados: " + contador + " de <?php echo $contador; ?>");
    $("#MyCant2").html("Total Unidades " + xUnidades + " en Total Productos Seleccionados: " + contador + " de <?php echo $contador; ?>");
    $("#MyTot").html(formatNumber(total) + " " + xMoneda);
    $("#MyTot2").html(formatNumber(total) + " " + xMoneda);
  }

  function validar_envio() {
    var i =0;

    for(i = 0; i < <?php echo $contador; ?>; i++) {
      controlCantidad = "x" + i + "_cantidad";

      if(parseFloat($("#" + controlCantidad).val()) > 0) {
        return true;
      }
    }

    return true;
  }

  function buscarItem(page) {
    $("#pagina").val(page);
    $("frm").submit();
    
  }

  function buscarItem2(page) {
    $("#pagina").val(page);
    $("#switch_page").val("S");
    $("#btnSend").click();
    $("#btnSend").prop( "disabled", true );
  }

  function limpiarItem() {
    $(location).attr('href', 'PedidoDeVentaDetalle?id=<?php echo $id; ?>');
  }

  function guardarRegitro(salida, cliente, articulo, control, cantidad) {
    //alert("PASO 001: " + salida + ", " + cliente + ", " + articulo + ", " + control + ", " + cantidad);
    $.ajax({
      url : "include/pdv_linea_guardar.php",
      type: "POST",
      data : {salida: salida, cliente: cliente, articulo: articulo, cantidad : cantidad},
      beforeSend: function(){
        $("#btnSend").prop('disabled', true);
        $("#" + control).val("Espere. . .");
      }
    })
    .done(function(data) {
      var result = data.split("|");

      if(result[0] == "001" || result[0] == "002" || result[0] == "003") {
        alert(result[1]);
        $("#" + control).val(result[2]);
      }

      if(result[0] == "999") {
        $("#" + control).val(result[2]);
      }

      $("#btnSend").prop('disabled', false);
    })
    .fail(function(data) {
      alert( "error" + data );
    })
    .always(function(data) {
      //alert( "complete" );
      //$("#result").html("Espere. . . ");
    });
  }
</script>

<?= GetDebugMessage() ?>
