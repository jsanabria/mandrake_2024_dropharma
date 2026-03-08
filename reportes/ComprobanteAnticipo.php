<?php
ob_start();
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require('rcs/fpdf.php');
require("../include/connect.php");

$anticipo_id = isset($_REQUEST["anticipo_id"]) ? intval($_REQUEST["anticipo_id"]) : 0;
if ($anticipo_id <= 0) {
    die("Anticipo inválido.");
}

// -------------------------
// Helpers
function enc($txt) {
    // Convierte UTF-8 a ISO-8859-1 para FPDF
    return mb_convert_encoding((string)$txt, "ISO-8859-1", "UTF-8");
}

function money($n) {
    return number_format(floatval($n), 2, ",", ".");
}

// -------------------------
// Datos compañía (1ra compañía)
$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cia_id = $row ? intval($row["id"]) : 1;

$sqlCia = "
    SELECT
        a.ci_rif, a.nombre, b.campo_descripcion AS ciudad,
        a.direccion, a.telefono1, a.email1, a.logo
    FROM compania a
    LEFT JOIN tabla b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
    WHERE a.id = '$cia_id'
    LIMIT 1
"; 
$rsCia = mysqli_query($link, $sqlCia);
$cia = mysqli_fetch_array($rsCia);

$cia_nombre = $cia["nombre"] ?? "";
$cia_rif    = $cia["ci_rif"] ?? "";
$cia_dir    = $cia["direccion"] ?? "";
$cia_tel    = $cia["telefono1"] ?? "";
$cia_email  = $cia["email1"] ?? "";
$cia_logo   = $cia["logo"] ?? "";

// -------------------------
// Cabecera anticipo
$sqlCab = "
    SELECT
        cc.id,
        DATE_FORMAT(cc.fecha, '%d/%m/%Y') AS fecha,
        cc.username,
        c.nombre AS cliente_nom,
        c.ci_rif AS cliente_rif,
        cc.monto AS total_bs
    FROM cobros_cliente cc
    LEFT JOIN cliente c ON c.id = cc.cliente
    WHERE cc.id = " . intval($anticipo_id) . "
      AND cc.id_documento = 0
    LIMIT 1
";
$rsCab = mysqli_query($link, $sqlCab);
$cab = mysqli_fetch_array($rsCab);
if (!$cab) {
    die("No se encontró el anticipo #$anticipo_id.");
}

// -------------------------
// Detalles anticipo
$sqlDet = "
    SELECT
        d.id,
        d.metodo_pago,
        p.valor2 AS metodo_nom,
        d.referencia,
        d.moneda,
        d.monto_moneda,
        d.tasa_moneda,
        d.monto_bs,
        d.banco_origen,
        t_origen.campo_descripcion AS banco_origen_nom,
        d.banco AS cuenta_destino_id,
        CONCAT(
            TRIM(t_dest.campo_descripcion),
            CASE WHEN IFNULL(cc.numero,'')<>'' THEN CONCAT(' · ', TRIM(cc.numero)) ELSE '' END
        ) AS cuenta_destino_nom
    FROM cobros_cliente_detalle d
    LEFT JOIN parametro p
        ON p.codigo='009' AND p.valor1 = d.metodo_pago
    LEFT JOIN tabla t_origen
        ON t_origen.tabla='BANCO' AND t_origen.campo_codigo = d.banco_origen
    LEFT JOIN compania_cuenta cc
        ON cc.id = d.banco
    LEFT JOIN tabla t_dest
        ON t_dest.tabla='BANCO' AND t_dest.campo_codigo = cc.banco
    WHERE d.cobros_cliente = " . intval($anticipo_id) . "
    ORDER BY d.id ASC
";
$rsDet = mysqli_query($link, $sqlDet);

// -------------------------
// PDF
class PDF extends FPDF
{
    public $cia_logo_path = "";
    public $cia_nombre = "";
    public $cia_rif = "";
    public $cia_dir = "";
    public $cia_tel = "";
    public $cia_email = "";

    public $titulo = "";
    public $subtitulo = "";

    function Header()
    {
        // Logo
        if (trim($this->cia_logo_path) !== "" && file_exists($this->cia_logo_path)) {
            $this->Image($this->cia_logo_path, 10, 10, 35);
        }

        // Encabezado compañía
        $this->SetFont('Arial','B',12);
        $this->SetXY(50, 10);
        $this->Cell(0, 6, enc($this->cia_nombre), 0, 1, 'L');

        $this->SetFont('Arial','',9);
        $this->SetX(50);
        $this->Cell(0, 5, enc("RIF: " . $this->cia_rif), 0, 1, 'L');

        $this->SetX(50);
        $this->MultiCell(0, 4, enc($this->cia_dir), 0, 'L');

        $this->SetX(50);
        $this->Cell(0, 5, enc("Tel: " . $this->cia_tel . "  |  Email: " . $this->cia_email), 0, 1, 'L');

        // Fecha/hora
        $this->SetFont('Arial','',8);
        $this->SetXY(10, 30);
        $this->Cell(0, 5, enc("Fecha impresión: " . date("d/m/Y") . "  " . date("H:i:s")), 0, 1, 'R');

        // Título
        $this->Ln(4);
        $this->SetFont('Arial','B',14);
        $this->Cell(0, 7, enc($this->titulo), 0, 1, 'C');

        $this->SetFont('Arial','',10);
        if (trim($this->subtitulo) !== "") {
            $this->Cell(0, 6, enc($this->subtitulo), 0, 1, 'C');
        }

        $this->Ln(4);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(4);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0, 5, 'Page '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);

$pdf->cia_logo_path = (trim($cia_logo) !== "") ? "../carpetacarga/" . $cia_logo : "";
$pdf->cia_nombre = $cia_nombre;
$pdf->cia_rif = $cia_rif;
$pdf->cia_dir = $cia_dir;
$pdf->cia_tel = $cia_tel;
$pdf->cia_email = $cia_email;

$pdf->titulo = "COMPROBANTE DE ANTICIPO";
$pdf->subtitulo = "Anticipo #".$cab["id"]."";

$pdf->AddPage();

$pdf->SetFont('Arial','',10);

// Bloque datos cliente/anticipo
$pdf->SetFillColor(245,245,245);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0, 7, enc("Datos del anticipo"), 1, 1, 'L', true);

$pdf->SetFont('Arial','',10);
$cliente = "Cliente:\nNombre del cliente para salto en varias líneas" . ($cab["cliente_nom"] ?? "");
$rif     = "RIF/CI: " . ($cab["cliente_rif"] ?? "");

$yInicial = $pdf->GetY();

// Celda izquierda con dos líneas
$pdf->MultiCell(95, 6, enc($cliente), 1, 'L');

// Guardamos altura usada
$yFinal = $pdf->GetY();
$altura = $yFinal - $yInicial;

// Volvemos arriba para imprimir la celda derecha
$pdf->SetXY(105, $yInicial);

// Celda derecha con misma altura
$pdf->MultiCell(95, $altura, enc($rif), 1, 'L');

// Bajamos al siguiente bloque correctamente
$pdf->SetY(max($yFinal, $pdf->GetY()));


$pdf->Cell(95, 7, enc("Fecha: " . ($cab["fecha"] ?? "")), 1, 0, 'L');
$pdf->Cell(95, 7, enc("Usuario: " . ($cab["username"] ?? "")), 1, 1, 'L');

$pdf->Ln(4);

// Tabla detalle
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(30,30,30);
$pdf->SetTextColor(255,255,255);

$pdf->Cell(20, 7, enc("Método"), 1, 0, 'L', true);
$pdf->Cell(35, 7, enc("Referencia"), 1, 0, 'L', true);
$pdf->Cell(12, 7, enc("Mon"), 1, 0, 'C', true);
$pdf->Cell(20, 7, enc("Monto"), 1, 0, 'R', true);
$pdf->Cell(15, 7, enc("Tasa"), 1, 0, 'R', true);
$pdf->Cell(20, 7, enc("Bs."), 1, 0, 'R', true);
$pdf->Cell(33, 7, enc("Banco origen"), 1, 0, 'L', true);
$pdf->Cell(35, 7, enc("Cuenta destino"), 1, 1, 'L', true);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',8);

$totalsByCurrency = []; // ["USD" => 15000, ...]
$totalBs = 0;

while ($d = mysqli_fetch_array($rsDet)) {
    $metodo = $d["metodo_nom"] ?? $d["metodo_pago"] ?? "";
    $ref = $d["referencia"] ?? "";
    $mon = $d["moneda"] ?? "";
    $monto = floatval($d["monto_moneda"] ?? 0);
    $tasa = floatval($d["tasa_moneda"] ?? 0);
    $bs = floatval($d["monto_bs"] ?? 0);

    $bancoOri = trim((string)($d["banco_origen_nom"] ?? ""));
    $cuentaDest = trim((string)($d["cuenta_destino_nom"] ?? ""));

    $totalsByCurrency[$mon] = ($totalsByCurrency[$mon] ?? 0) + $monto;
    $totalBs += $bs;

    $pdf->Cell(20, 7, enc($metodo), 1, 0, 'L');
    $pdf->Cell(35, 7, enc($ref), 1, 0, 'L');
    $pdf->Cell(12, 7, enc($mon), 1, 0, 'C');
    $pdf->Cell(20, 7, money($monto), 1, 0, 'R');
    $pdf->Cell(15, 7, number_format($tasa, 2, ",", "."), 1, 0, 'R');
    $pdf->Cell(20, 7, money($bs), 1, 0, 'R');
    $pdf->Cell(33, 7, substr(enc($bancoOri), 0, 22), 1, 0, 'L');
    $pdf->Cell(35, 7, substr(enc($cuentaDest), 0, 22), 1, 1, 'L');
}

// Totales
$pdf->Ln(3);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0, 7, enc("Totales"), 0, 1, 'L');

$pdf->SetFont('Arial','',9);
foreach ($totalsByCurrency as $mon => $val) {
    $pdf->Cell(0, 5, enc("Total $mon: " . money($val)), 0, 1, 'L');
}
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0, 7, enc("TOTAL (Bs.): " . money($totalBs)), 0, 1, 'L');

$pdf->Ln(4);

// Nota
$pdf->SetFont('Arial','',9);
$pdf->MultiCell(0, 5, enc("NOTA: Este comprobante certifica un ANTICIPO a favor del cliente. "
    . "El saldo podrá ser aplicado a futuras facturas según las políticas de la compañía. "
    . "Conserve este documento para fines de control y conciliación."), 1, 'L');

$pdf->Ln(8);

// Firmas
$pdf->SetFont('Arial','',10);
$pdf->Cell(90, 6, enc("______________________________"), 0, 0, 'C');
$pdf->Cell(10, 6, "", 0, 0, 'C');
$pdf->Cell(90, 6, enc("______________________________"), 0, 1, 'C');
$pdf->Cell(90, 6, enc("Entregado por"), 0, 0, 'C');
$pdf->Cell(10, 6, "", 0, 0, 'C');
$pdf->Cell(90, 6, enc("Recibido por (Cliente)"), 0, 1, 'C');

// Salida
$pdf->Output("I", "Anticipo_$anticipo_id.pdf");
require("../include/desconnect.php");
ob_end_flush();