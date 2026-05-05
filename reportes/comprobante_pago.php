<?php
session_start();
// 1. Evitar que cualquier error previo ensucie la salida del PDF
ob_start(); 

require('rcs/fpdf.php');
require("../include/connect2.php");

// Función moderna para reemplazar utf8_decode
function to_iso($text) {
    return mb_convert_encoding($text ?? '', 'ISO-8859-1', 'UTF-8');
}

$id_compra = isset($_REQUEST["id_compra"]) ? intval($_REQUEST["id_compra"]) : 0;

// 1. Obtener datos de la factura y cliente
// Nota: Corregí "INNER JOIN cliente" por "INNER JOIN clientes" si ese es el nombre real de tu tabla
$sqlFact = "SELECT a.nro_documento, b.nombre AS cliente, a.fecha, a.tasa_dia 
            FROM salidas a 
            INNER JOIN cliente b ON a.cliente = b.id 
            WHERE a.id = $id_compra";
$resFact = mysqli_query($link, $sqlFact);
$factura = mysqli_fetch_array($resFact);

// 2. Lógica de Sumatoria
$sqlPago = "SELECT DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha FROM cobros_cliente WHERE id_documento = $id_compra";
$resPago = mysqli_query($link, $sqlPago);
$row = mysqli_fetch_array($resPago);
$fecha_pago = $row['fecha'];

$sqlPagos = "SELECT metodo_pago, referencia, monto_bs, moneda, monto_moneda  
             FROM cobros_cliente_detalle 
             WHERE cobros_cliente IN (SELECT id FROM cobros_cliente WHERE id_documento = $id_compra)";
$resPagos = mysqli_query($link, $sqlPagos);

$total_pagos = 0;
$total_igtf = 0;
$detalle_pagos = [];

while ($row = mysqli_fetch_array($resPagos)) {
    $detalle_pagos[] = $row;
    if ($row['metodo_pago'] !== 'IG') {
        $total_pagos += $row['monto_bs'];
    } else {
        $total_igtf += $row['monto_bs'];
    }
}

// 3. Generación del PDF
$pdf = new FPDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Encabezado
$pdf->Ln(8);
$pdf->Cell(160, 6);
$pdf->Cell(20, 5,'Fecha: ','0','0','R');
$pdf->Cell(20, 5, date("d/m/Y"), 0, 0, 'R');


$pdf->SetFont('Arial', 'B', 14);
$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$cia =  $row["id"];

$sql = "SELECT 
            a.ci_rif, a.nombre, b.campo_descripcion AS ciudad, 
            a.direccion, a.telefono1, a.email1, logo  
        FROM 
            compania AS a 
            LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD' 
        WHERE a.id = '$cia';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$ciudad = $row["ciudad"];
$direccion = $row["direccion"]; 
$cia =  $row["nombre"];
$logo =  $row["logo"];
$ci_rif =  $row["ci_rif"];

if(trim($logo) != "") {
    $pdf->Image("../carpetacarga/$logo", 10, 10, 50);
}

$pdf->Ln(25);

$pdf->Cell(190, 10, to_iso("COMPROBANTE DE RECEPCIÓN DE PAGOS"), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 6, "Cliente:", 0, 0);
$pdf->Cell(100, 6, to_iso($factura['cliente']), 0, 1);
$pdf->Cell(30, 6, to_iso("Relación Factura:"), 0, 0);
$pdf->Cell(100, 6, $factura['nro_documento'], 0, 1);
$pdf->Cell(30, 6, "Fecha Emision:", 0, 0);
$pdf->Cell(100, 6, $fecha_pago, 0, 1);
$pdf->Ln(10);

// Tabla de Detalle
$pdf->SetFillColor(230, 230, 230);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(40, 7, "METODO", 1, 0, 'C', true);
$pdf->Cell(60, 7, "REFERENCIA", 1, 0, 'C', true);
$pdf->Cell(45, 7, "MONTO DIVISA", 1, 0, 'C', true);
$pdf->Cell(45, 7, "MONTO BS.", 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
foreach ($detalle_pagos as $p) {
    $es_igtf = ($p['metodo_pago'] == 'IG');
    $pdf->Cell(40, 6, $es_igtf ? "IGTF 3%" : $p['metodo_pago'], 1, 0, 'L');
    $pdf->Cell(60, 6, $p['referencia'], 1, 0, 'L');
    $pdf->Cell(45, 6, $p['moneda'] != 'Bs.' ? $p['moneda']." ".number_format($p['monto_moneda'], 2) : '-', 1, 0, 'R');
    $pdf->Cell(45, 6, number_format($p['monto_bs'], 2, ',', '.'), 1, 1, 'R');
}

// Totales Finales
$pdf->Ln(5);
$pdf->SetX(120);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 7, "TOTAL MONTO PAGOS EFECTUADOS:", 0, 0, 'R');
$pdf->Cell(35, 7, "Bs. ".number_format($total_pagos, 2, ',', '.'), 0, 1, 'R');

$pdf->SetX(120);
$pdf->SetTextColor(200, 0, 0);
$pdf->Cell(40, 7, "TOTAL IGTF (3%):", 0, 0, 'R');
$pdf->Cell(35, 7, "Bs. ".number_format($total_igtf, 2, ',', '.'), 0, 1, 'R');

$pdf->SetX(120);
$pdf->SetTextColor(0, 0, 150);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(40, 10, "Total General Factura:", 'T', 0, 'R');
$pdf->Cell(35, 10, "Bs. ".number_format($total_pagos - $total_igtf, 2, ',', '.'), 'T', 1, 'R');

// Limpiar el buffer para asegurar que solo salga el PDF
if (ob_get_length()) ob_end_clean();
$pdf->Output();