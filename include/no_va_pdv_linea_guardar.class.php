<?php 
class pdv_linea_guardar {

  var $tipo_documento;
  var $salida;
  var $cliente;
  var $cod_articulo;
  var $articulo;
  var $cantidad;

  var $resultado;

  var $tarifa;
  var $descuento;
  var $cantidad_en_mano;
  var $cantidad_unidad;
  var $articulo_inventario;
  var $alicuota;
  var $almacen;
  var $precio;
  var $precio_ful;

  var $link;

  function __construct()
  {
    include "connect.php";
    $this->link = $link;
  }

  function pedido_abierto()
  {
    $sql = "SELECT estatus FROM salidas WHERE tipo_documento = '" . $this->tipo_documento . "' AND id = '" . $this->salida . "';"; 
    $rs = mysqli_query($this->link, $sql);
    $row = mysqli_fetch_array($rs);
    $status_doc = $row["estatus"];
    if($status_doc == "NUEVO") 
      return true;
    else 
      return false;

  }

  function datos_articulo()
  {
    $sql = "SELECT 
              b.tarifa 
            FROM 
              salidas AS a 
              LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
            WHERE 
              a.id = " . $this->salida . ";";
    $rs = mysqli_query($this->link, $sql);
    $row = mysqli_fetch_array($rs);
    $this->tarifa = $row["tarifa"];

    $sql = "SELECT 
              IFNULL(descuento, 0) AS descuento, 
              (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano, 
              unidad_medida_defecto AS unidad_medida, cantidad_por_unidad_medida, articulo_inventario, id  
            FROM 
              articulo WHERE id = '" . $this->cod_articulo . "';"; 
    $rs = mysqli_query($this->link, $sql);
    if($row = mysqli_fetch_array($rs)) 
    {
      $this->descuento = floatval($row["descuento"]);
      $this->cantidad_en_mano = floatval($row["cantidad_en_mano"]);
      $this->cantidad_unidad = floatval($row["cantidad_por_unidad_medida"]);
      $this->articulo_inventario = $row["articulo_inventario"];
      $this->unidad_medida = $row["unidad_medida"];
      $this->articulo = $row["id"];

    
      $sql = "SELECT alicuota FROM articulo WHERE id = '" . $this->articulo . "';"; 
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs);
      $codigo_alicuota = $row["alicuota"];

      $sql = "SELECT alicuota FROM alicuota
          WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs);
      $this->alicuota = floatval($row["alicuota"]);

      $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs);
      $this->almacen = $row["almacen"];


      $this->cantidad = $cantidad_por_unidad_medida * $this->cantidad;


      if($this->articulo_inventario == "S") {
        if($cantidad > 0) {
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
          $rs = mysqli_query($this->link, $sql);
          $row = mysqli_fetch_array($rs);
          $pedidos_nuevos = floatval($row["pedidos_nuevos"]);
          if((($this->cantidad_en_mano - $this->cantidad) + $pedidos_nuevos) < 0) $this->resultado = 0; 
          else $this->resultado = 1;
        }
      } 
      else $this->resultado = 1;
    }
    else $this->resultado = 0;
  }

  function precio_articulo() 
  {
    $sql = "SELECT
              precio AS precio_ful,
              (precio - (precio * ($descuento/100))) AS precio 
            FROM tarifa_articulo
            WHERE tarifa = $this->tarifa AND articulo = '" . $this->articulo . "';";
    $rs = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($rs);
    $this->precio = floatval($row["precio"]);
    $this->precio_ful = floatval($row["precio_ful"]);
    $this->total = $this->precio * $this->cantidad;
    $this->cantidad_movimiento = $this->cantidad * (-1);
  }

  function insertar_articulo($tipo_documento="TDCPDV", $salida, $cliente, $cod_articulo, $cantidad) 
  { 
    $this->tipo_documento = $tipo_documento;
    $this->salida = $salida;
    $this->cliente = $cliente;
    $this->cod_articulo = $cod_articulo;
    $this->cantidad = $cantidad;

    $this->resultado = 1;

    if($this->pedido_abierto()) 
    {
      $this->datos_articulo();

      if($this->resultado == 0) return false;
    } 
    else return false;

    $this->precio_articulo(); 

    $sql = "INSERT INTO entradas_salidas
          (id, tipo_documento, id_documento, 
          fabricante, articulo, almacen, 
          cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
          precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc)
        VALUES 
          (NULL, '$this->tipo_documento', $this->salida, 
          $this->fabricante, $this->articulo, '$this->almacen', 
          $this->cantidad, '$this->unidad_medida', $this->cantidad_unidad, $this->cantidad_movimiento, 
          $this->precio, $this->total, $this->alicuota, $this->descuento, $this->precio_ful);
        ";
    mysqli_query($this->link, $sql);

    $sql = "SELECT COUNT(DISTINCT alicuota ) AS cantidad FROM entradas_salidas
            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
    $rs = mysqli_query($this->link, $sql);
    $row = mysqli_fetch_array($rs);
    if(intval($row["cantidad"]) > 1) $alicuota = 0;
    else {
      $sql = "SELECT DISTINCT alicuota FROM entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
      $rs = mysqli_query($this->link, $sql);
      $row = mysqli_fetch_array($rs);
      $alicuota = floatval($row["alicuota"]);
    }

    // Se actualiza el encabezado del pedido de venta //
    $sql = "SELECT
              SUM(precio) AS precio, 
              SUM((precio * (alicuota/100))) AS iva, 
              SUM(precio) + SUM((precio * (alicuota/100))) AS total 
            FROM 
              entradas_salidas
            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida'";
    $rs = mysqli_query($this->link, $sql);
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
            WHERE tipo_documento = '$this->tipo_documento' AND id = '$this->salida'";
    mysqli_query($this->link, $sql);

    /* Se actualizan las cantidades de unidades en el encabezado de la salida */
    $sql = "UPDATE 
              salidas AS a 
              JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
            SET 
              a.unidades = b.cantidad 
            WHERE a.id = $this->salida;";
    $rs = mysqli_query($this->link, $sql);


    include "rutinas.php";
    ActInv($articulo); 
    return true;
  }

} // fin de la clase Verdura
?>