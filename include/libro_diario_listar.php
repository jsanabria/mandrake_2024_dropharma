<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=reporteLibroDiario.xls");
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
          b.comprobante, date_format(a.fecha, '%d/%m/%Y') AS fecha, c.codigo, c.descripcion, b.nota, b.referencia, 
          b.debe, b.haber, a.descripcion AS concepto 
        FROM 
          cont_comprobante AS a 
          JOIN cont_asiento AS b ON b.comprobante = a.id 
          JOIN view_plancta AS c ON c.id = b.cuenta 
        WHERE 
          a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' AND IFNULL(a.contabilizacion, '0000-00-00') <> '0000-00-00' 
        ORDER BY a.fecha ASC, b.comprobante ASC, b.debe DESC "; 
$rs = mysqli_query($link, $sql);


$out = '<div class="container">';
$out .= '<h4><b><a target="_blank" href="include/libro_diario_listar.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '">Libro Diario</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '</h4>';
$out .= '<div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>FECHA</th>
                <th>CODIGO</th>
                <th>DESCRIPCION</th>
                <td align="right"><b>DEBE</b></td>
                <td align="right"><b>HABER</b></td>
              </tr>
            </thead>
            <tbody>';
            $comp = 0;
            $debe = 0;
            $haber = 0;
            $sw = false;
            $debitos = 0;
            $creditos = 0;
            $concepto = "";
            while($row = mysqli_fetch_array($rs)) {
                if($row["comprobante"] != $comp) {
                  if($sw) {
                    $out .= '<tr>
                      <td colspan="3" align="right"><strong>POR CONCEPTO DE: ' . $concepto . '</strong></td>
                      <td align="right"><strong><u>' . number_format($debe, 2, ",", ".") . '</u></strong></td>
                      <td align="right"><strong><u>' . number_format($haber, 2, ",", ".") . '</u></strong></td>
                    </tr>';
                  }

                  $out .= '<tr>
                    <td>' . $row["fecha"] . '</td>
                    <td>' . $row["codigo"] . '</td>
                    <td>' . $row["descripcion"] . ' ' . trim($row["nota"]) . ' ' . (trim($row["referencia"]) == '' ? '': ' - Ref#:' . trim($row["referencia"])) . '</td>
                    <td align="right">' . (floatval($row["debe"])==0 ? "" : number_format(floatval($row["debe"]), 2, ",", ".")) . '</td>
                    <td align="right">' . (floatval($row["haber"])==0 ? '' : number_format(floatval($row["haber"]), 2, ",", ".")) . '</td>
                  </tr>';
                  $debe = 0;
                  $haber = 0;
                } 
                else {
                  $out .= '<tr>
                    <td></td>
                    <td>' . $row["codigo"] . '</td>
                    <td>' . $row["descripcion"] . ' ' . trim($row["nota"]) . ' ' . (trim($row["referencia"]) == '' ? '': ' - Ref#:' . trim($row["referencia"])) . '</td>
                    <td align="right">' . (floatval($row["debe"])==0 ? "" : number_format(floatval($row["debe"]), 2, ",", ".")) . '</td>
                    <td align="right">' . (floatval($row["haber"])==0 ? '' : number_format(floatval($row["haber"]), 2, ",", ".")) . '</td>
                  </tr>';
                  $sw = true;
                }

                $debe += floatval($row["debe"]);
                $haber += floatval($row["haber"]);
                $comp = $row["comprobante"];
                $debitos += floatval($row["debe"]);
                $creditos += floatval($row["haber"]);
                $concepto =  $row["concepto"];
            }

            if($sw) {
              $out .= '<tr>
                <td colspan="3" align="right"><strong>POR CONCEPTO DE: ' . $concepto . '</strong></td>
                <td align="right"><strong><u>' . number_format($debe, 2, ",", ".") . '</u></strong></td>
                <td align="right"><strong><u>' . number_format($haber, 2, ",", ".") . '</u></strong></td>
              </tr>';
            }

            $out .= '<tr>
              <td colspan="3" align="right"></td>
              <td align="right"><strong><u><i>' . number_format($debitos, 2, ",", ".") . '</i></u></strong></td>
              <td align="right"><strong><u><i>' . number_format($creditos, 2, ",", ".") . '</i></u></strong></td>
            </tr>';

     $out .= '</tbody>
          </table>
        </div>';
$out .= '</div>';

echo "$out";

?>
