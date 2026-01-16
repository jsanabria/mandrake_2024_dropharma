<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=reporteEstadoResultados.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  }
}

include "connect.php";

$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];

$sql = "SELECT ci_rif, nombre FROM compania WHERE id = 1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$rif = $row["ci_rif"];
$cia = $row["nombre"];
$nombrecta = "";
$monto = 0.00;
$total = 0.00;
$utilidad_neta = 0.00;
$utilidad_operacional = 0.00;
$utilidad_antes_impuesto = 0.00;

$out = '<div class="container">';
$out .= '<h4><b><a target="_blank" href="include/estado_resultados_listar.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '">Estado Resultados</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '</h4>';
$out .= '<div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th><h4><strong>VENTAS BRUTAS</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';
                // INGRESOS //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '4' AND grupo = '1';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion , 
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '4' AND d.grupo = '1' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total += $monto;


                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b>' . ($monto==0 ? '' : number_format($monto, 2, ",", ".")) . '</b></td>';
                $out .= '</tr>';

                // DEVOLUCIONES //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '4' AND grupo = '1' AND cuenta = '4';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion,  
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '4' AND d.grupo = '1' AND d.cuenta = '4' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total += $monto;

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  // $out .= '<td align="right"><b>' . ($monto==0 ? '' : number_format($monto, 2, ",", ".")) . '</b></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';

                // COSTOS //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '5';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion, 
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '5' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total += $monto;


                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  // $out .= '<td align="right"><b>' . ($monto==0 ? '' : number_format($monto, 2, ",", ".")) . '</b></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><h4><strong><i>UTILIDAD BRUTA</i></strong></h4></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b><i><u>' . number_format($total, 2, ",", ".") . '</u></i></b></td>';
                $out .= '</tr>';
                $utilidad_neta = $total;

         $out .= '</table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th><h4><strong>GASTOS DE OPERACION</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';


                // GASTOS 1 //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '6' AND grupo = '1';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];
                $monto = 0.00;
                $total = 0.00;


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion, 
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '6' AND d.grupo = '1' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total += $monto;

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';


                // GASTOS 2 //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '6' AND grupo = '2';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];
                $monto = 0.00;
                $total = 0.00;


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion, 
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '6' AND d.grupo = '2' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total += $monto;

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';


                // GASTOS 3 //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '6' AND grupo = '3';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];
                $monto = 0.00;
                $total = 0.00;


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion, 
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '6' AND d.grupo = '3' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total += $monto;

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';


                $utilidad_operacional = $utilidad_neta - ($total);
                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><h4><strong><i>UTILIDAD OPERACIONAL</i></strong></h4></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b><i><u>' . number_format( $utilidad_operacional, 2, ",", ".") . '</u></i></b></td>';
                $out .= '</tr>';


         $out .= '</table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th><h4><strong>INGRESOS NO OPERACIONALES</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';

                // INGRESOS NO OPERACIONALES //
                $sql = "SELECT descripcion FROM cont_plancta WHERE clase = '4' AND grupo = '2';"; 
                $rs = mysqli_query($link, $sql); 
                $row = mysqli_fetch_array($rs);
                $nombrecta = $row["descripcion"];
                $monto = 0.00;
                $total = 0.00;


                $sql = "SELECT 
                          d.clase, d.grupo,  
                          d.descripcion, 
                          SUM(b.debe) AS cargos, 
                          SUM(b.haber) AS abonos, 
                          SUM(IFNULL(b.debe,0)-IFNULL(b.haber,0)) AS saldo 
                        FROM 
                          cont_comprobante AS a 
                          JOIN cont_asiento AS b ON b.comprobante = a.id 
                          JOIN view_plancta AS c ON c.id = b.cuenta 
                          JOIN cont_plancta AS d ON d.id = c.id 
                        WHERE 
                          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
                          AND d.clase = '4' AND d.grupo = '2' 
                        GROUP BY d.clase, d.grupo, d.descripcion;"; 
                $rs = mysqli_query($link, $sql); 
                if($row = mysqli_fetch_array($rs)) $monto = floatval($row["saldo"]);
                else $monto = 0;
                $total = $monto;

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';



                $utilidad_antes_impuesto = $utilidad_operacional + $total;
                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><h4><strong><i>UTILIDAD ANTES DE IMPUESTO</i></strong></h4></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b><i><u>' . number_format( $utilidad_antes_impuesto, 2, ",", ".") . '</u></i></b></td>';
                $out .= '</tr>';


         $out .= '</table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th><h4><strong>IMPUESTOS</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';

                // RESERVA LEGAL //

                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><b><i>' . $nombrecta . ':</i></b></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b>' . number_format($monto, 2, ",", ".") . '</b></td>';
                $out .= '</tr>';



                $utilidad_neta_disponible = $utilidad_antes_impuesto + ($total);
                $out .= '<tr>';
                  $out .= '<td colspan="3" align="left"><h4><strong><i>UTILIDAD NETA</i></strong></h4></td>';
                  $out .= '<td></td>';
                  $out .= '<td align="right"><b><i><u>' . number_format( $utilidad_neta, 2, ",", ".") . '</u></i></b></td>';
                $out .= '</tr>';
         $out .= '</table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </thead>
            <tbody>';
     $out .= '</tbody>
          </table>
        </div>';
$out .= '<div class="row">'; 
$out .= '<div class="col-md-4 text-center">______________________________</div>';
$out .= '<div class="col-md-4 text-center">______________________________</div>';
$out .= '<div class="col-md-4 text-center">______________________________</div>';
$out .= '</div>'; 

$out .= '<div class="row">'; 
$out .= '<div class="col-md-4 text-center"><h4><strong>Representante Legal</strong></h4></div>';
$out .= '<div class="col-md-4 text-center"><h4><strong>Contador</strong></h4></div>';
$out .= '<div class="col-md-4 text-center"><h4><strong>Auditor</strong></h4></div>';
$out .= '</div>'; 
$out .= '</div>';

echo "$out";

?>
