<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=reporteBalanceComprobacion.xls");
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

$sql = "SELECT 
          c.codigo, c.descripcion AS cuenta, 
          SUM(b.debe) AS cargos, SUM(b.haber) AS abonos 
        FROM 
          cont_comprobante AS a 
          JOIN cont_asiento AS b ON b.comprobante = a.id 
          JOIN view_plancta AS c ON c.id = b.cuenta 
        WHERE 
          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
        GROUP BY c.codigo, c.descripcion 
        ORDER BY c.codigo ASC;"; 
$rs = mysqli_query($link, $sql);

$out = '<div class="container">';
$out .= '<h4><b><a target="_blank" href="include/balance_comprobacion_listar.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '">Balance de Comprobaci&oacute;n</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '</h4>';
$out .= '<div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>
                  CODIGO
                </th>
                <th>
                  CUENTA
                </th>
                <td align="right"><b>SALDO INICIAL</b></td>
                <td align="right"><b>CARGOS</b></td>
                <td align="right"><b>ABONOS</b></td>
                <td align="right"><b>SALDO FINAL</b></td>
              </tr>
            </thead>
            <tbody>';
            $saldo_inicial = 0;
            $cargos = 0;
            $abonos = 0;
            $saldo_final = 0;
            $saldo = 0;
            while($row = mysqli_fetch_array($rs)) {
                //if(substr($row["codigo"], 0, 1) == "1") {
                  $saldo = $saldo_inicial+(floatval($row["cargos"])-floatval($row["abonos"]));
                /*} 
                else {
                  $saldo = $saldo_inicial-(floatval($row["cargos"])+floatval($row["abonos"]));
                }*/

                $out .= '<tr>
                  <td>' . $row["codigo"] . '</td>
                  <td>' . $row["cuenta"] . '</td>
                  <td align="right">' . ($saldo_inicial==0 ? "" : number_format($saldo_inicial, 2, ",", ".")) . '</td>
                  <td align="right">' . (floatval($row["cargos"])==0 ? "" : number_format(floatval($row["cargos"]), 2, ",", ".")) . '</td>
                  <td align="right">' . (floatval($row["abonos"])==0 ? '' : number_format(floatval($row["abonos"]), 2, ",", ".")) . '</td>
                  <td align="right">' . ($saldo==0 ? '' : number_format($saldo, 2, ",", ".")) . '</td>
                </tr>';

                $cargos += floatval($row["cargos"]);
                $abonos += floatval($row["abonos"]);
                $saldo_final += $saldo;
                $saldo = 0;
            }

            $out .= '<tr>
              <td colspan="2" align="right"></td>
              <td align="right">' . ($saldo_inicial==0 ? "" : number_format($saldo_inicial, 2, ",", ".")) . '</td>
              <td align="right"><strong><u>' . number_format($cargos, 2, ",", ".") . '</u></strong></td>
              <td align="right"><strong><u>' . number_format($abonos, 2, ",", ".") . '</u></strong></td>
              <td align="right"><strong><u>' . number_format($saldo_final, 2, ",", ".") . '</u></strong></td>
            </tr>';

     $out .= '</tbody>
          </table>
        </div>';

echo "$out";

?>
