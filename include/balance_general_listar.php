<?php 
if(isset($_REQUEST["toexcel"])) {
  if($_REQUEST["toexcel"]=="SI") {
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=reporteBalanceGeneral.xls");
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

$ConsultarCuentas = new ConsultarClase();

$out = '<div class="container">';
$out .= '<h4><b><a target="_blank" href="include/balance_general_listar.php?toexcel=SI&fecha_desde=' . $fecha_desde . '&fecha_hasta=' . $fecha_hasta . '">Balance General</a></b></h4>';
$out .= '<h4>' . $cia . ' ' . $rif  . '</h4>';
$out .= '<h4>Desde: ' . $fecha_desde . ' Hasta: ' . $fecha_hasta . '</h4>';
$out .= '<div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <td>
                  <table class="table table-striped">
                    <tr>
                      <th><h4><strong>ACTIVO</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';


$out .= $ConsultarCuentas->MostrarClase('1', $fecha_desde, $fecha_hasta, $link);


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
                      <th><h4><strong>PASIVO</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';


$out .= $ConsultarCuentas->MostrarClase('2', $fecha_desde, $fecha_hasta, $link);


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
                      <th><h4><strong>CAPITAL</strong></h4></th>
                    </tr>
                    <tr>
                      <td>
                        <table class="table">';


$out .= $ConsultarCuentas->MostrarClase('3', $fecha_desde, $fecha_hasta, $link);

$xTotPP = $ConsultarCuentas->xPasivo + $ConsultarCuentas->xCapital;
         $out .= '</table>
                  <table class="table table-striped">
                    <tr>
                      <td></td>
                      <td colspan="3" align="right"><h4><strong>Total Pasivo + Patrimonio:</strong></h4></td>
                      <td align="right"><h4><strong><i><u>' . number_format($xTotPP, 2, ",", ".") . '</u></i></h4></strong></td>
                    </tr>
                  </table>
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


class ConsultarClase {
  var $xActivo = 0;
  var $xPasivo = 0;
  var $xCapital = 0;

  function __construct() {
    
  }

  function MostrarClase($clase, $fecha_desde, $fecha_hasta, $link) {
      $out = "";
      $sql = "SELECT 
                c.codigo,  
                d.clase, d.grupo, d.cuenta, 
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
                AND d.clase = '$clase' 
              GROUP BY c.codigo, d.clase, d.grupo, d.cuenta, d.descripcion  
              ORDER BY c.codigo ASC;"; 
      $rs = mysqli_query($link, $sql);
      $xClase = '';
      $xGrupo = '';
      $xCuenta = '';
      $TotalClase = 0;
      $TotalGrupo = 0;
      $sw = false;

      $NombreClase = "";
      $NombreGrupo = "";

      while($row = mysqli_fetch_array($rs)) {
        if($row["clase"] != $xClase) {
          $sql2 = "SELECT descripcion FROM cont_plancta WHERE clase = '" . $row["clase"] . "' 
                      AND IFNULl(grupo, '') = '' 
                      AND IFNULL(cuenta, '') = '' 
                      AND IFNULl(subcuenta, '') = '';"; 
          $rs2 = mysqli_query($link, $sql2);
          $row2 = mysqli_fetch_array($rs2);

          $NombreClase = $row2["descripcion"];

          $out .= '<tr>';
            $out .= '<td colspan="5"><b><i>' . $NombreClase . '</b></i></td>';
          $out .= '</tr>';
          $xClase = $row["clase"];
        }


        if($row["grupo"] != $xGrupo) {
          if($sw) {
            $out .= '<tr>';
              $out .= '<td colspan="3" align="right"><b><i>Total ' . $NombreGrupo . ':</i></b></td>';
              $out .= '<td></td>';
              $out .= '<td align="right"><b><i><u>' . ($TotalGrupo==0 ? '' : number_format($TotalGrupo, 2, ",", ".")) . '</u></i></b></td>';
            $out .= '</tr>';
          }

          $sql2 = "SELECT descripcion FROM cont_plancta WHERE clase = '" . $row["clase"] . "' 
                      AND IFNULl(grupo, '') = '" . $row["grupo"] . "' 
                      AND IFNULL(cuenta, '') = '' 
                      AND IFNULl(subcuenta, '') = '';";
          $rs2 = mysqli_query($link, $sql2);
          $row2 = mysqli_fetch_array($rs2);

          $NombreGrupo = $row2["descripcion"];

          $out .= '<tr>';
            $out .= '<td></td>';
            $out .= '<td colspan="4"><b><i>' . $NombreGrupo . '</b></i></td>';
          $out .= '</tr>';
          
          $TotalGrupo = floatval($row["saldo"]);

          $xGrupo = $row["grupo"];
        }
        else {
          $TotalGrupo += floatval($row["saldo"]);
        }


       $sql2 = "SELECT descripcion FROM cont_plancta WHERE clase = '" . $row["clase"] . "' 
                  AND IFNULl(grupo, '') = '" . $row["grupo"] . "' 
                  AND IFNULL(cuenta, '') = '" . $row["cuenta"] . "' 
                  AND IFNULl(subcuenta, '') = '';";
        $rs2 = mysqli_query($link, $sql2);
        $row2 = mysqli_fetch_array($rs2);

        //if(trim($row2["descripcion"]) == "") echo "$sql2<br>"; // Para identificar cuando la cuenta no existe 

       $out .= '<tr>';
          $out .= '<td></td>';
          $out .= '<td></td>';
          $out .= '<td>' . (trim($row2["descripcion"]) == "" ? $row["descripcion"] : $row2["descripcion"]) . '</td>';
          $out .= '<td align="right">' . ($row["saldo"]==0 ? '' : number_format($row["saldo"], 2, ",", ".")) . '</td>';
          $out .= '<td align="right"></td>';
          $out .= '</tr>';
          $xCuenta = $row["cuenta"];

       $sw = true;
       $TotalClase += floatval($row["saldo"]);
      }

      $out .= '<tr>';
        $out .= '<td colspan="3" align="right"><b><i>Total ' . $NombreGrupo . ':</i></b></td>';
        $out .= '<td></td>';
        $out .= '<td align="right"><b><i><u>' . ($TotalGrupo==0 ? '' : number_format($TotalGrupo, 2, ",", ".")) . '</u></i></b></td>';
      $out .= '</tr>';

      $out .= '<tr>';
        $out .= '<td></td>';
        $out .= '<td colspan="3" align="right"><b><i>Total ' . $NombreClase . ':</i></b></td>';
        $out .= '<td align="right"><b><i><u>' . ($TotalGrupo==0 ? '' : number_format( $TotalClase, 2, ",", ".")) . '</u></i></b></td>';
      $out .= '</tr>';

     switch ($clase) {
        case 1:
          $this->xActivo = $TotalClase;
          break;
        case 2:
          $this->xPasivo = $TotalClase;
          break;
        case 3:
          $this->xCapital = $TotalClase;
          break;
      }

      return $out;
  }
}


?>
