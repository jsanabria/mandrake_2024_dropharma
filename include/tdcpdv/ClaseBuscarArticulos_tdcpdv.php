<?php


class BuscarArticulos {
   var $link; 

   var $pedido; 
   var $lista_pedido; 
   var $fabricante; 
   var $articulo; 
   var $cliente;
   var $username;

   var $cantidad_articulos;
   var $pagina;
   var $start;
   var $LineByPage;

   var $almacen;
   var $tasa;
   var $moneda;
   var $tarifa;
   var $tipo_documento;

   var $where;

   var $onHand;
   var $fecha_vencimiento;

   function __construct($ppedido, $plista_pedido, $pfabricante, $particulo, $pcliente, $pusername, $ppagina, $plink)
   {
      $this->link = $plink;
      // die(json_encode($this->link, JSON_UNESCAPED_UNICODE));

      $this->pedido = $ppedido; 
      $this->lista_pedido = $plista_pedido; 
      $this->fabricante = $pfabricante; 
      $this->articulo = $particulo; 
      $this->cliente = $pcliente;
      $this->username = $pusername;
      $this->pagina = $ppagina;

      $sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
      $result = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($result); 
      $this->almacen = $row["ppal"];  

      $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs); 
      $this->tasa = floatval($row["tasa"]);

      $sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs); 
      $this->moneda = $row["moneda"];

      $sql = "SELECT tarifa FROM cliente WHERE id = $this->cliente;"; 
      $rs = mysqli_query($this->link, $sql);
      if($row = mysqli_fetch_array($rs)) $this->tarifa = $row["tarifa"]; 
      else $this->tarifa = 2; 

      $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
      $this->tipo_documento = 'TDCNET';
      $rs = mysqli_query($this->link, $sql);
      if($row = mysqli_fetch_array($rs)) $this->tipo_documento = $row["tipo_documento"];

      if($this->lista_pedido != "") $this->where .= " AND a.lista_pedido = '$this->lista_pedido' ";
      if($this->fabricante != "") $this->where .= " AND a.fabricante = $this->fabricante ";

      $asesor = 0;
      $sql = "SELECT 
                IFNULL(asesor, 0) AS asesor 
              FROM usuario WHERE username = '$this->username';"; 
      $result = mysqli_query($this->link, $sql);
      if($row = mysqli_fetch_array($result)) {
        $asesor = $row["asesor"];
        $sql = "SELECT IFNULL(fabricante, 0) AS fabricante FROM asesor_fabricante WHERE asesor = $asesor;"; 
        $result = mysqli_query($this->link, $sql);
        if($row = mysqli_fetch_array($result)) {
          $this->where = " AND a.fabricante IN (SELECT IFNULL(fabricante, 0) AS fabricante FROM asesor_fabricante WHERE asesor = $asesor)";
        } 
      } 
   } 

   function ArticulosDisponibilidadMayorCero() 
   {
      $sql = "SELECT 
                COUNT(a.id) AS cantidad 
              FROM 
                articulo AS a 
                LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $this->tarifa 
                INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
              WHERE 
                a.activo = 'S' AND a.articulo_inventario = 'S' 
                AND (a.codigo LIKE '%$this->articulo%' OR a.nombre_comercial LIKE '%$this->articulo%' OR a.principio_activo LIKE '%$this->articulo%' OR a.codigo_de_barra = '$this->articulo') 
                $this->where 
                AND 
                a.cantidad_en_mano > 0;"; 
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs); 
      $this->cantidad_articulos = floatval($row["cantidad"]);

   }

   function ListarArticulosDisponibilidadMayorCero() 
   {
      $sql = "SELECT 
                a.id, 
                IFNULL(a.foto, '') AS foto, a.nombre_comercial, b.nombre AS fabricante, 
                a.principio_activo, a.presentacion, c.precio AS precio_ful, 
                CAST(a.cantidad_en_mano AS SIGNED) AS cantidad_en_mano, 
                -- (CAST(a.cantidad_en_mano AS SIGNED)-CAST(a.cantidad_en_pedido AS SIGNED)) AS cantidad_en_mano, 
                d.descripcion AS unidad_medida, 
                a.descuento, (c.precio - (c.precio * (a.descuento/100))) AS precio, 0 AS cantidad, a.codigo, 
                a.lote, a.fecha_vencimiento      
              FROM 
                articulo AS a 
                LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
                INNER JOIN tarifa_articulo AS c ON c.articulo = a.id AND c.tarifa = $this->tarifa 
                INNER JOIN unidad_medida AS d ON d.codigo = a.unidad_medida_defecto 
              WHERE 
                a.activo = 'S' AND a.articulo_inventario = 'S' 
                AND (a.codigo LIKE '%$this->articulo%' OR a.nombre_comercial LIKE '%$this->articulo%' OR a.principio_activo LIKE '%$this->articulo%' OR a.codigo_de_barra = '$this->articulo') 
                $this->where 
                AND 
                a.cantidad_en_mano > 0 ORDER BY a.principio_activo, a.presentacion LIMIT $this->start, $this->LineByPage;"; 
      $rs = mysqli_query($this->link, $sql);

      $i = 1;
      $FechaVencimiento = "00/00/0000";
      $html = '';
      while ($row = mysqli_fetch_array($rs)) { 
          $FechaVencimiento = $row["fecha_vencimiento"];
          $onHand = floatval($row["cantidad_en_mano"]);

          if($onHand > 0) {
            
            // $url = 'https://www.dropharmadm.com/dropharma/carpetacarga/';
            $url = '../admin/carpetacarga/';
            $html .= '<tr>';
              $html .= '<td>';
                      
                      // if(file_exists($url . $row["foto"]) and trim($row["foto"]) != "") {
                        $html .= '<div class="thumbnail">
                          <a href="' . $url . $row["foto"] . '" target="_blank">
                            <img src="' . $url . $row["foto"] . '" alt="' . $row["nombre_comercial"] . '" width="100" class="img-thumbnail">
                          </a>
                        </div>';
                      /* }
                      else {
                        $html .= '<div class="thumbnail"><input type="hidden" class="form-control" id="x' . $i . '_articulo" name="x' . $i . '_articulo" size="6" value="' . $row["id"] . '">
                              <div class="caption">' . $row["nombre_comercial"] . ' Fec. Venc. '. $FechaVencimiento . '</div></div>';
                      }*/
              
              $html .= '</td>';
              $html .= '<td><strong>' . $row["nombre_comercial"] . 
                            '</strong><br><small>' . $row["principio_activo"] . ' (Cod.: ' . $row["codigo"]  . ')</small><br>
                            <small><i>' . $row["presentacion"] . '</i></small><br>
                            <strong><small>Fabricante: ' . $row["fabricante"] . '<strong></small><br>
                            <small><strong> Fec. Venc. ' . $FechaVencimiento . '</strong></small>' . (floatval($row["descuento"]) > 0 ? ' | Descuento: <span class="badge text-bg-info">' . number_format(floatval($row["descuento"]), 2, "," ,".") . '%</span>' : '') . ' | <small>Unidad</small></td>';
              $html .= '<td class="text-center">';  
                          if(floatval($row["descuento"]) > 0) {
                            if($this->moneda == "USD") {
                              $html .= '<span class="text-primary"><strong>' . number_format(round($row["precio"], 2)*$this->tasa, 2, ".", ",") . '</strong></span><br>';
                              // $html .= '<span class="text-dark"><strong><del>' . number_format(floatval($row["precio_ful"]*$tasa), 2, "," ,".") . '</del></strong></span>';
                            }
                            else {
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span><br>';
                              $html .= '<span class="text-dark"><strong><del>' . number_format(floatval($row["precio_ful"]), 2, "," ,".") . '</del></strong></span>';
                            }
                          } 
                          else { 
                            if($this->moneda == "USD") 
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"]*$this->tasa, 2, ".", ",") . '</strong></span>';
                            else 
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span>';
                          }
              $html .= '</td>';
              $html .= '<td class="text-center">';  
                          if(floatval($row["descuento"]) > 0) { 
                            if($this->moneda == "USD") { 
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span><br>';
                              $html .= '<span class="text-dark"><strong><del>' . number_format($row["precio_ful"], 2, "," ,".") . '</del></strong></span>';  
                            }
                            else 
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"]/$tasa, 2, ".", ",") . '</strong></span><br>';
                          } 
                          else { 
                            if($this->moneda == "USD")  
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"], 2, ".", ",") . '</strong></span>';
                            else 
                              $html .= '<span class="text-primary"><strong>' . number_format($row["precio"]/$tasa, 2, ".", ",") . '</strong></span>';
                          }
              $html .= '</td>';
              $html .= '<td class="text-center">';  
                          if($onHand < 0) $onHand = 0;
                          $html .= '<span class="text-dark"><strong>' . number_format($onHand, 0, "", "") . '</strong></span>';
              $html .= '</td>';
              $html .= '<td class="text-center">';  
                          $xCant = intval($row["cantidad"]);
                          $sql3 = "SELECT cantidad_articulo AS cantidad FROM pedidos_detalles_online WHERE id_documento = $this->pedido AND articulo = " . intval($row["id"]) . ";";
                          $result3 = mysqli_query($this->link, $sql3);
                          if($row3 = mysqli_fetch_array($result3)) $xCant = intval($row3["cantidad"]);
                          $html .= '<input type="number" class="form-control" id="x' . $i . '_cantidad" name="x' . $i . '_cantidad" size="4" value="' . ($xCant==0 ? "" : $xCant) . '" style="width: 80px;" ' . ($xCant==0 ? '' : 'disabled="disabled"') . '>';
                          $html .= '<input type="hidden" id="x' . $i . '_precio" name="x' . $i . '_precio" value="' . round($row["precio"], 2) . '">';
                          $html .= '<input type="hidden" id="x' . $i . '_descuento" name="x' . $i . '_descuento" value="' . round($row["descuento"], 2) . '">';
                          $html .= '<input type="hidden" id="x' . $i . '_precioFull" name="x' . $i . '_precioFull" value="' . round($row["precio_ful"], 2) . '">';
                          $html .= '<input type="hidden" id="x' . $i . '_moneda" name="x' . $i . '_moneda" value="' . $this->moneda . '">';
                          $html .= '<input type="hidden" id="x' . $i . '_onhand" name="x' . $i . '_onhand" value="' . $onHand . '">';
                          $html .= '<input type="hidden" id="x' . $i . '_articulo" name="x' . $i . '_articulo" value="' . $row["id"] . '">';
              $html .= '</td>';
              $html .= '<td class="text-center">';  
                          if($xCant == 0) {
                            $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-cart-shopping" onclick="js:insertar(' . $i . ')"></i></span>';
                          }
                          else {
                            $html .= '<span id="x' . $i . '_boton"><i class="fa-solid fa-trash" onclick="js:eliminar(' . $i . ')"></i></span>';
                          }
              $html .= '</td>';
            $html .= '</tr>';

            $i++;
          }
      }


      $html .= '<tr><td colspan="7"> <center><b>Registros ' . $i-1 . ' de ' . $this->cantidad_articulos . '</b></center> </td></tr>';
      $html .= '</thead></tbody>';

      return $html;
   }

   function imprimir_encanezado() {
     $html = '<table class="table table-bordered table-hover table-striped table-sm">
      <thead>
        <tr>
          <td colspan="8">
            <div class="col-12 d-flex justify-content-center" id="Paginacion1">' . $this->pagineo($this->cantidad_articulos) . '
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
      return $html;    
   }

   function pagineo($cantidad)
   {
      $this->LineByPage = 50;
      $Pages = intval($cantidad/$this->LineByPage);
      if((($cantidad/$this->LineByPage) - intval($cantidad/$this->LineByPage)) > 0) $Pages++;

      $pagination = '<nav aria-label="Page navigation example">';
        $pagination .= '<ul class="pagination">';
          $pagination .= '<li class="page-item  ' . ($this->pagina == 0 ? ' active' : '') . '">';
            $pagination .= '<a class="page-link" onclick="js: buscarItem2(0, 0);" href="#" aria-label="Previous">';
              $pagination .= '<span aria-hidden="true">&laquo;</span>';
            $pagination .= '</a>';
          $pagination .= '</li>';

      for($i=0; $i<$Pages; $i++) {
            $pagination .= '<li class="page-item ' . ($this->pagina == ($i+1) ? ' active' : '') . '"><a class="page-link" onclick="js: buscarItem2(' . ($i+1) . ', 0);" href="#">' . ($i+1) . '</a></li>';
      }

          $pagination .= '<li class="page-item ' . ($this->pagina == 9999 ? ' active' : '') . '">';
            $pagination .= '<a class="page-link" onclick="js: buscarItem2(9999, 0);" href="#" aria-label="Next">';
              $pagination .= '<span aria-hidden="true">&raquo;</span>';
            $pagination .= '</a>';
          $pagination .= '</li>';
        $pagination .= '</ul>';
      $pagination .= '</nav>';

      if($this->pagina == 9999) $this->start = ($Pages-1)*$this->LineByPage;
      elseif($this->pagina == 0) $this->start = 0;
      else $this->start = ($this->pagina-1)*$this->LineByPage;

       return $pagination;
   }
} // fin de la clase BuscarArticulos


?>