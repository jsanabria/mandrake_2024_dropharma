<?php
session_start();
require("../include/connect.php");

$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "0";
$anho = isset($_REQUEST["anho"]) ? $_REQUEST["anho"] : "0";
$excel = isset($_REQUEST["excel"]) ? $_REQUEST["excel"] : "N";

$GLOBALS["proveedor"] = $id;
$GLOBALS["periodo"] = "Desde 01/01/$anho Hasta 31/12/$anho";

// --- 1. CARGA DE DATOS PREVIA ---
// Datos de la Compañía (Agente)
$sqlCia = "SELECT ci_rif, nombre, direccion, telefono1, logo FROM compania ORDER BY id ASC LIMIT 1";
$rsCia = mysqli_query($link, $sqlCia);
$rowCia = mysqli_fetch_array($rsCia);
$cia_nombre = $rowCia["nombre"];
$cia_rif = $rowCia["ci_rif"];
$cia_dir = $rowCia["direccion"];
$cia_tel = $rowCia["telefono1"];
$cia_logo = $rowCia["logo"];
$GLOBALS["rif_agente"] = $cia_rif;

// Datos del Proveedor (Beneficiario)
$sqlProv = "SELECT a.ci_rif, a.nombre, a.direccion, 
            CONCAT(ifnull(a.telefono1,''), ' ', ifnull(a.telefono2,'')) as telf 
            FROM proveedor AS a WHERE a.id = '$id'";
$rsProv = mysqli_query($link, $sqlProv);
$rowProv = mysqli_fetch_array($rsProv);
$prov_nombre = $rowProv["nombre"];
$prov_rif = $rowProv["ci_rif"];
$prov_dir = $rowProv["direccion"];
$prov_tel = $rowProv["telf"];

// --- 2. GENERACIÓN EXCEL O PDF ---

if ($excel == "S") {
    header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=Reporte_ARI_$anho.xls");
    
    echo "<table border='1'>";
    echo "<tr><th colspan='6' style='font-size:14pt;'>COMPROBANTE DE RETENCIONES VARIAS DEL ISLR</th></tr>";
    echo "<tr><th colspan='6' align='left' style='background-color: #EEE;'>DATOS DEL AGENTE DE RETENCION:</th></tr>";
    echo "<tr><td><b>Nombre:</b></td><td colspan='5'>$cia_nombre</td></tr>";
    echo "<tr><td><b>R.I.F.:</b></td><td colspan='5'>$cia_rif</td></tr>";
    echo "<tr><td><b>Telefonos:</b></td><td colspan='5'>$cia_tel</td></tr>";
    echo "<tr><td><b>Direccion:</b></td><td colspan='5'>$cia_dir</td></tr>";
    
    echo "<tr><th colspan='6' align='left' style='background-color: #EEE;'>DATOS DEL BENEFICIARIO:</th></tr>";
    echo "<tr><td><b>Nombre:</b></td><td colspan='5'>$prov_nombre</td></tr>";
    echo "<tr><td><b>R.I.F.:</b></td><td colspan='5'>$prov_rif</td></tr>";
    echo "<tr><td><b>Direccion:</b></td><td colspan='5'>$prov_dir</td></tr>";
    echo "<tr><td><b>Periodo:</b></td><td colspan='5'>".$GLOBALS["periodo"]."</td></tr>";
    echo "<tr><td colspan='6'></td></tr>";
    
    echo "<tr style='background-color:#CCC;'>
            <th>Año</th><th>Mes</th><th>Monto Objeto</th><th>Impuesto Retenido</th><th>Ret. Acumulado</th><th>ISLR Acumulado</th>
          </tr>";
} else {
    require('rcs/fpdf.php');
    class PDF extends FPDF {
        function Header() {
        	$this->Ln(20);
            global $cia_nombre, $cia_rif, $cia_dir, $cia_tel, $cia_logo, $prov_nombre, $prov_rif, $prov_dir, $prov_tel;
            
            if(trim($cia_logo) != "") { $this->Image("../carpetacarga/$cia_logo", 10, 10, 60); }
            $this->SetFont('Arial','',12);
            $this->Cell(200, 6, "COMPROBANTE DE RETENCIONES VARIAS",0,1,'C');
            $this->Cell(200, 6, "DEL IMPUESTO SOBRE LA RENTA",0,1,'C');
            $this->Ln(5);

            // AGENTE
            $this->SetFont('Arial','U',11);
            $this->Cell(20, 6);
            $this->Cell(80, 6, "DATOS DEL AGENTE DE RETENCION:",0,1,'L');
            $this->SetFont('Arial','',9);
            $this->Cell(20, 5); $this->Cell(45, 5, "Nombre o Razon Social:",0,0); $this->Cell(125, 5, $cia_nombre,0,1);
            $this->Cell(20, 5); $this->Cell(45, 5, "Nro. R.I.F.:",0,0); $this->Cell(125, 5, $cia_rif,0,1);
            $this->Cell(20, 5); $this->Cell(45, 5, "Telefonos:",0,0); $this->Cell(125, 5, $cia_tel,0,1);
            $this->Cell(20, 5); $this->Cell(45, 5, "Direccion:",0,0); $this->MultiCell(130, 5, $cia_dir,0,'L');
            $this->Ln(3);

            // BENEFICIARIO
            $this->SetFont('Arial','U',11);
            $this->Cell(20, 6);
            $this->Cell(80, 6, "DATOS DEL BENEFICIARIO:",0,1,'L');
            $this->SetFont('Arial','',9);
            $this->Cell(20, 5); $this->Cell(45, 5, "Nombre o Razon Social:",0,0); $this->Cell(125, 5, $prov_nombre,0,1);
            $this->Cell(20, 5); $this->Cell(45, 5, "Nro. R.I.F.:",0,0); $this->Cell(125, 5, $prov_rif,0,1);
            $this->Cell(20, 5); $this->Cell(45, 5, "Direccion:",0,0); $this->MultiCell(130, 5, $prov_dir,0,'L');
            $this->Cell(20, 5); $this->Cell(45, 5, "Periodo:",0,0); $this->Cell(125, 5, $GLOBALS["periodo"],0,1);
            $this->Ln(5);

            // ENCABEZADO TABLA
            $this->SetFont('Arial', '', 8);
            $this->Cell(10, 4);
            $this->Cell(15, 4, "Año", "B", 0, 'L');
            $this->Cell(25, 4, "Mes", "B", 0, 'L');
            $this->Cell(35, 4, "Monto Objeto", "B", 0, 'R');
            $this->Cell(35, 4, "Impuesto Retenido", "B", 0, 'R');
            $this->Cell(35, 4, "Monto Acum.", "B", 0, 'R');
            $this->Cell(35, 4, "ISLR Acum.", "B", 1, 'R');
        }

        function Footer() {
            $this->SetY(-60);
            $this->SetFont('Arial','',10);
            $this->Cell(200, 5, "________________________________________________", 0, 1, 'C');
            $this->Cell(200, 5, "Firma y Sello Agente de Retencion", 0, 1, 'C');
            $this->Cell(200, 5, "R.I.F. Nro.: " . $GLOBALS["rif_agente"], 0, 1, 'C');
            
            $this->Image('../images/firma_reterncion.jpg', 20, 230, 45); // Ajustar posición según tu imagen
            $this->SetY(-15);
            $this->SetFont('Arial','I',8);
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }
    }
    $pdf = new PDF('P', 'mm', 'Letter');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',8);
}

// --- 3. PROCESAMIENTO DE FILAS ---
$sql = "SELECT YEAR(fecha) AS anho, MONTH(fecha) AS mes, SUM(IFNULL(monto_gravado, 0)) AS gravado, SUM(IFNULL(ret_islr, 0)) AS islr 
        FROM compra WHERE proveedor = '$id' AND YEAR(fecha) = $anho AND anulado = 'N' 
        GROUP BY 1, 2 ORDER BY 2 ASC"; 
$rs = mysqli_query($link, $sql);
$t_base = 0; $t_islr = 0;

while($row = mysqli_fetch_array($rs)) {
    $t_base += floatval($row["gravado"]);
    $t_islr += floatval($row["islr"]);
    $m_nom = mes_nombre($row["mes"]);

    if ($excel == "S") {
        echo "<tr>
                <td>{$row['anho']}</td><td>$m_nom</td>
                <td align='right'>".number_format($row["gravado"], 2, ",", "")."</td>
                <td align='right'>".number_format($row["islr"], 2, ",", "")."</td>
                <td align='right'>".number_format($t_base, 2, ",", "")."</td>
                <td align='right'>".number_format($t_islr, 2, ",", "")."</td>
              </tr>";
    } else {
        $pdf->Cell(10, 5);
        $pdf->Cell(15, 5, $row["anho"], 0);
        $pdf->Cell(25, 5, $m_nom, 0);
        $pdf->Cell(35, 5, number_format($row["gravado"], 2, ",", "."), 0, 0, 'R');
        $pdf->Cell(35, 5, number_format($row["islr"], 2, ",", "."), 0, 0, 'R');
        $pdf->Cell(35, 5, number_format($t_base, 2, ",", "."), 0, 0, 'R');
        $pdf->Cell(35, 5, number_format($t_islr, 2, ",", "."), 0, 1, 'R');
    }
}

// --- 4. CIERRE ---
if ($excel == "S") {
    echo "<tr style='background-color:#EEE;'>
            <th colspan='2'>TOTALES</th>
            <th align='right'>".number_format($t_base, 2, ",", "")."</th>
            <th align='right'>".number_format($t_islr, 2, ",", "")."</th>
            <th colspan='2'></th>
          </tr></table>";
} else {
    // CORRECCIÓN AQUÍ: Usamos $pdf en lugar de $this
    $pdf->Ln(2); 
    $pdf->Cell(10, 0); 
    $pdf->Cell(185, 0, "", "T", 1); // Línea final de tabla
    $pdf->Ln(1);
    
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10, 5);
    $pdf->Cell(40, 5, "TOTALES:", 0, 0, 'L');
    $pdf->Cell(35, 5, number_format($t_base, 2, ",", "."), 0, 0, 'R');
    $pdf->Cell(35, 5, number_format($t_islr, 2, ",", "."), 0, 1, 'R');
    
    $pdf->Output();
}

require("../include/desconnect.php");

function mes_nombre($mes) {
    $m = ["", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"];
    return $m[(int)$mes];
}
?>