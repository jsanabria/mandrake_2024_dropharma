<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=reporteLibroMayor.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  }
}

include "connect.php";

$fecha_desde = $_REQUEST["fecha_desde"];
$fecha_hasta = $_REQUEST["fecha_hasta"];
$cuenta = $_REQUEST["cuenta"];

$sql = "SELECT ci_rif, nombre FROM compania WHERE id = 1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$rif = $row["ci_rif"];
$cia = $row["nombre"];

$sql = "SELECT descripcion FROM view_plancta WHERE id = $cuenta;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cta = $row["descripcion"];

$sql = "SELECT 
          date_format(a.fecha, '%d/%m/%Y') AS fecha, b.comprobante, 
          a.descripcion AS descripcion, 
          b.debe, b.haber, 
          c.codigo, c.descripcion AS cta, b.nota, b.referencia 
        FROM 
          cont_comprobante AS a 
          JOIN cont_asiento AS b ON b.comprobante = a.id 
          JOIN view_plancta AS c ON c.id = b.cuenta 
        WHERE 
          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
          AND b.cuenta = $cuenta 
        ORDER BY a.fecha ASC, b.comprobante ASC, b.debe DESC "; 
$rs = mysqli_query($link, $sql);

$out = '<div class="container">';
$out .= '<h4><b><a target="_blank" href="include/libro_mayor_listar.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '&cuenta=' . $cuenta . '">Libro Mayor Anal&iacute;tico</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>De la Cuenta: ' . $cta . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '</h4>';
$out .= '<div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>FECHA</th>
                <th>COMPROBANTE</th>
                <th>DESCRIPCION</th>
                <td align="right"><b>DEBE</b></td>
                <td align="right"><b>HABER</b></td>
                <td align="right"><b>SALDO</b></td>
              </tr>
            </thead>
            <tbody>';
            $debitos = 0;
            $creditos = 0;
            $saldo = 0;
            while($row = mysqli_fetch_array($rs)) {
                $out .= '<tr>
                  <td>' . $row["fecha"] . '</td>
                  <td>' . $row["comprobante"] . '</td>
                  <td>' . $row["descripcion"] . ' ' . trim($row["nota"]) . ' ' . (trim($row["referencia"]) == '' ? '': ' - Ref#:' . trim($row["referencia"])) . '</td>
                  <td align="right">' . (floatval($row["debe"])==0 ? "" : number_format(floatval($row["debe"]), 2, ",", ".")) . '</td>
                  <td align="right">' . (floatval($row["haber"])==0 ? '' : number_format(floatval($row["haber"]), 2, ",", ".")) . '</td>
                  <td align="right">' . ($saldo+(floatval($row["debe"])-floatval($row["haber"]))==0 ? '' : number_format($saldo+(floatval($row["debe"])-floatval($row["haber"])), 2, ",", ".")) . '</td>
                </tr>';

                $debitos += floatval($row["debe"]);
                $creditos += floatval($row["haber"]);
                $saldo = $saldo+(floatval($row["debe"])-floatval($row["haber"]));
            }

            $out .= '<tr>
              <td colspan="3" align="right"></td>
              <td align="right"><strong><u>' . number_format($debitos, 2, ",", ".") . '</u></strong></td>
              <td align="right"><strong><u>' . number_format($creditos, 2, ",", ".") . '</u></strong></td>
              <td align="right"><strong><u>' . number_format($debitos-$creditos, 2, ",", ".") . '</u></strong></td>
            </tr>';

     $out .= '</tbody>
          </table>
        </div>';
$out .= '</div>';

echo "$out";

?>
