<?php

function ActInv($articulo) {
    include "connect.php";

    /**** Almacen por defecto ****/
    $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
    $rs = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($rs);
    $almacen = $row["almacen"];

    $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '014';";
    $rs = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($rs);
    $almacenconsig = $row["almacen"];

    $sql = "SELECT 
                   IFNULL(SUM(a.cantidad_movimiento), 0) AS pedidos_nuevos 
                FROM 
                  entradas_salidas AS a 
                  JOIN salidas AS b ON
                    b.tipo_documento = a.tipo_documento
                    AND b.id = a.id_documento 
                  JOIN almacen AS c ON
                    c.codigo = a.almacen AND c.movimiento = 'S'
                WHERE
                  a.tipo_documento IN ('TDCPDV')
                  AND a.articulo = $articulo AND b.estatus = 'NUEVO';";
    $rs = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($rs);
    $pedido = floatval($row["pedidos_nuevos"]); 

    $sql = "SELECT 
          IFNULL(SUM(a.cantidad_movimiento), 0) AS entrada 
        FROM 
          entradas_salidas AS a 
          JOIN entradas AS b ON
            b.tipo_documento = a.tipo_documento
            AND b.id = a.id_documento 
          JOIN almacen AS c ON
            c.codigo = a.almacen AND c.movimiento = 'S'
        WHERE
          a.tipo_documento IN ('TDCFCC') 
          AND b.estatus = 'NUEVO' AND a.articulo = '$articulo';"; 
    $rs = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($rs);
    $transito = floatval($row["entrada"]);

    $sql = "SELECT 
        SUM(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad 
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
              AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '$articulo' 
            GROUP BY a.id_compra
          ) AS d ON d.id = a.id 
      WHERE
        ((a.tipo_documento IN ('TDCNRP','TDCAEN') 
        AND b.estatus = 'PROCESADO')
         OR
        (a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
        AND b.estatus = 'NUEVO')) AND a.articulo = '$articulo' 
        AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0;"; 
    $rs = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($rs);
    $cantida_en_mano = floatval($row["cantidad"]);

    $sql = "UPDATE articulo
        SET
          cantidad_en_mano = $cantida_en_mano, 
          cantidad_en_pedido = ABS($pedido), 
          cantidad_en_transito = ABS($transito) 
        WHERE id = '$articulo'";  
    mysqli_query($link, $sql);
}

?>