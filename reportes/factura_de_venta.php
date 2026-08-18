<?php
session_start();

require('rcs/fpdf.php');
require("../include/connect2.php");

/*
====================================================================================
 FACTURA DE VENTA - VERSION SIMPLIFICADA (6 columnas)
 ------------------------------------------------------------------------------------
 Diferencias respecto a factura_de_venta.php (version completa):

 1) Tamaño de página: Carta completa (215.9 x 279.4 mm), igual que la versión
    original. Ver la creación del objeto PDF al final del archivo.

 2) La tabla de artículos pasa de 16 a 6 columnas:
        CANT | DESCRIPCION (+ LOTE concatenado) | PRECIO UNIT | % ALIC | TOT. Bs. | TOT. $
    Se eliminan las columnas LOTE, VENC, DP, DL, DC, DT, P.UNI Bs./$ y PREC $ que
    existían en la versión completa.

 3) entradas_salidas.descuento (DP) y entradas_salidas.descuento2 (DL) YA vienen
    aplicados dentro de entradas_salidas.precio_unidad (así lo confirmó el usuario),
    por lo que aquí NO se vuelve a "engordar" el precio ni se recalculan: se usa
    precio_unidad tal cual llega de la base de datos.

 4) salidas.descuento (DC) y salidas.descuento2 (DT) YA NO se aplican línea por
    línea. Ahora se muestran como dos líneas nuevas dentro del bloque de totales
    (Descuento X% / Descuento2 Y%), tal como se ve en la Imagen 2 de referencia.

 5) Se agrega la hora de salidas.fecha debajo de la fecha de la factura.

 6) Se elimina la leyenda "DP: Descuento Producto DL: ... DC: ... DT: ..." ya que
    esas columnas no existen en este formato.

 7) "CUENTA Nro:" = cliente.id formateado a 8 dígitos (sprintf('%08d', id)),
    confirmado por el usuario. Ver variable $GLOBALS["cuenta_cliente"] en Header().
====================================================================================
*/

$id_invoice = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "0";

/**
 * Convierte cualquier texto de la base de datos al formato que requieren
 * las fuentes internas de FPDF: Windows-1252.
 */
function TextoFpdf($texto)
{
    $texto = html_entity_decode(
        (string)$texto,
        ENT_QUOTES | ENT_HTML5,
        "UTF-8"
    );

    $codificacion = mb_detect_encoding(
        $texto,
        ["UTF-8", "Windows-1252", "ISO-8859-1"],
        true
    );

    if ($codificacion && $codificacion !== "UTF-8") {
        $texto = mb_convert_encoding(
            $texto,
            "UTF-8",
            $codificacion
        );
    }

    return mb_convert_encoding(
        $texto,
        "Windows-1252",
        "UTF-8"
    );
}

/**
 * Divide un texto por palabras sin cortar letras ni caracteres especiales.
 *
 * Devuelve:
 * [0] Primera línea
 * [1] Texto restante
 */
function DividirTextoPorPalabras($texto, $maxCaracteres = 55)
{
    $texto = trim(
        preg_replace('/\s+/u', ' ', (string)$texto)
    );

    if (mb_strlen($texto, "UTF-8") <= $maxCaracteres) {
        return [$texto, ""];
    }

    $trozoInicial = mb_substr(
        $texto,
        0,
        $maxCaracteres,
        "UTF-8"
    );

    $ultimaPosicionEspacio = mb_strrpos(
        $trozoInicial,
        " ",
        0,
        "UTF-8"
    );

    // Si no hay espacios, no queda otra opción que cortar por longitud.
    if ($ultimaPosicionEspacio === false) {
        $ultimaPosicionEspacio = $maxCaracteres;
    }

    $primeraLinea = trim(
        mb_substr(
            $texto,
            0,
            $ultimaPosicionEspacio,
            "UTF-8"
        )
    );

    $textoRestante = trim(
        mb_substr(
            $texto,
            $ultimaPosicionEspacio,
            null,
            "UTF-8"
        )
    );

    return [$primeraLinea, $textoRestante];
}

$sql = "SELECT valor1 AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["moneda_default"] = $row["moneda"];

/////////////////////////////
// Si el documento no tiene aún cantidad_movimiento calculada, se calcula (igual que en la versión completa)
$sql = "SELECT
			cantidad_articulo, cantidad_movimiento
		FROM
			entradas_salidas
		WHERE
			id_documento = $id_invoice
			AND tipo_documento = 'TDCFCV'
			AND cantidad_movimiento IS NULL;";
$rs = mysqli_query($link, $sql);
if ($row = mysqli_fetch_array($rs)) {
	$sql = "UPDATE entradas_salidas
				SET cantidad_movimiento = (-1)*cantidad_articulo
			WHERE
				id_documento = $id_invoice
				AND tipo_documento = 'TDCFCV'
				AND cantidad_movimiento IS NULL;";
	mysqli_query($link, $sql);
}
/////////////////////////////

// NOTA: se agrega date_format(fecha, '%H:%i:%s') AS hora para mostrar la hora (con segundos) debajo de la fecha
$sql = "SELECT
			id, date_format(fecha, '%d/%m/%Y') as fecha,
			date_format(fecha, '%H:%i:%s') AS hora,
			date_format(fecha, '%Y/%m/%d') AS fech, cliente, nro_documento, nro_control, tipo_documento, estatus,
			asesor, documento, monto_usd, IFNULL(tasa_dia, 0) AS tasa_dia, asesor_asignado, dias_credito,
			date_format(DATE_ADD(fecha,INTERVAL IFNULL(dias_credito, 0) DAY), '%d/%m/%y') AS fec_venc, doc_afectado,
			descuento, descuento2, moneda, impreso, IFNULL(doc_afe, 0) AS doc_afe, IFNULL(igtf_alicuota, 0) AS igtf_alicuota,
			IFNULL(username, '') AS username, IFNULL(entregado, '') AS entregado
		FROM salidas where id = '$id_invoice';";
$rs = mysqli_query($link, $sql);
$row = mysqli_fetch_array($rs);
$GLOBALS["invoice"] = $row["nro_documento"];
$GLOBALS["invoice_id"] = $row["id"];
$GLOBALS["cliente"] = $row["cliente"];
$GLOBALS["fecha"] = $row["fecha"];
$GLOBALS["hora"] = $row["hora"];
$GLOBALS["username"] = $row["username"];
$GLOBALS["control"] = $row["nro_control"];
$GLOBALS["tipo_documento"] = $row["tipo_documento"];
$GLOBALS["nro_documento"] = $row["nro_documento"];
$GLOBALS["estatus_doc"] = $row["estatus"];
$GLOBALS["estatus"] = $row["estatus"] == "ANULADO" ? $row["estatus"] . " - " : "";
$GLOBALS["documento"] = $row["documento"];
$GLOBALS["dias_credito"] = $row["dias_credito"];
$GLOBALS["fec_venc"] = $row["fec_venc"];
$GLOBALS["doc_afectado"] = $row["doc_afectado"];
$GLOBALS["doc_afe"] = $row["doc_afe"];
$GLOBALS["moneda"] = $row["moneda"];
$GLOBALS["entregado"] = $row["entregado"];
$GLOBALS["impreso"] = $row["impreso"];
$GLOBALS["alicuota_dinamica"] = $row["igtf_alicuota"];

// -----------------------------------------------------------------------
// Validación: una factura de contado (salidas.entregado = 'S') debe tener
// al menos un método de pago registrado en cobros_cliente_detalle antes de
// poder emitirse/imprimirse. Si no tiene ninguno, se detiene la generación
// del PDF y se muestra un mensaje al usuario.
// -----------------------------------------------------------------------
if ($GLOBALS["entregado"] == "S") {
	$sql_chk = "SELECT COUNT(*) AS total
				FROM cobros_cliente_detalle
				WHERE cobros_cliente IN (
					SELECT id FROM cobros_cliente WHERE id_documento = '$id_invoice'
				);";
	$rs_chk = mysqli_query($link, $sql_chk);
	$row_chk = mysqli_fetch_array($rs_chk);

	if (($GLOBALS["documento"] === 'FC' OR $GLOBALS["documento"] === 'ND') AND (int)($row_chk["total"] ?? 0) === 0) {
		header('Content-Type: text/html; charset=UTF-8');
		echo '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>
				<div style="font-family: Arial, sans-serif; max-width: 480px; margin: 80px auto; padding: 20px 30px;
							border: 1px solid #e0a800; background: #fff3cd; color: #856404; border-radius: 6px; text-align: center;">
					<h3 style="margin-top:0;">No se puede emitir la factura</h3>
					<p>La factura de contado debe indicar m&eacute;todos de pago antes de emitirse.</p>
				</div>
			  </body></html>';
		exit;
	}
}

// Si es una prefactura (sin número de documento asignado y estatus NUEVO), se muestra
// un número de prefactura basado en el id interno y la fecha del día en curso.
// NOTA: se usan variables "_display" separadas (no se sobreescribe nro_documento/fecha)
// porque el resto del script usa $GLOBALS["nro_documento"] para decidir si marcar el
// documento como impreso, buscar el doc_afe, etc. -esa lógica de negocio no debe cambiar-.
if (trim($GLOBALS["nro_documento"] ?? "") == "" && $GLOBALS["estatus_doc"] == "NUEVO") {
	$GLOBALS["nro_documento_display"] = "PREFACT-" . $GLOBALS["invoice_id"];
	$GLOBALS["fecha_display"] = date("d/m/Y");
	$GLOBALS["es_prefactura"] = true;
} else {
	$GLOBALS["nro_documento_display"] = $GLOBALS["nro_documento"];
	$GLOBALS["fecha_display"] = $GLOBALS["fecha"];
	$GLOBALS["es_prefactura"] = false;
}

if (trim($GLOBALS["nro_documento"] ?? "") != "") {
	if ($row["impreso"] != "S") {
		$sql_impreso = "UPDATE salidas
						SET impreso = 'S'
						WHERE id = '$id_invoice'";
		mysqli_query($link, $sql_impreso);
	}
}

if (trim($GLOBALS["nro_documento"] ?? "") == "") $GLOBALS["impreso"] = "S"; // Esto por si se imprime sin nro de documento o control

$descuento_comercial = floatval($row["descuento"]);   // salidas.descuento  (DC)
$descuento_comercial2 = floatval($row["descuento2"]); // salidas.descuento2 (DT)

$monto_usd = floatval($row["monto_usd"]);
$tasa_dia = floatval($row["tasa_dia"]);

$asesor = isset($row["asesor"]) ? $row["asesor"] : "";
$asesor_asignado = isset($row["asesor_asignado"]) ? $row["asesor_asignado"] : "";


if (($monto_usd == 0 or $tasa_dia == 0) and strtotime($row["fech"]) >= strtotime("2020-09-27 00:00:00")) {
	$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
	$rs = mysqli_query($link, $sql);
	$row = mysqli_fetch_array($rs);
	$tasa = floatval($row["tasa"]);

	if ($tasa > 0) {
		$sql = "UPDATE salidas SET monto_usd = (total/$tasa), tasa_dia = $tasa WHERE id = '$id_invoice'";
		mysqli_query($link, $sql);
	}
	$tasa_dia = $tasa;
}


$sql = "SELECT a.nombre
		FROM
			usuario AS u
			JOIN asesor AS a ON a.id = u.asesor
		WHERE
			u.username = '$asesor';";
$rs = mysqli_query($link, $sql);
if ($row = mysqli_fetch_array($rs))
	$GLOBALS["asesor"] = substr($row["nombre"], 0, 15);
else
	$GLOBALS["asesor"] = "";


class PDF extends FPDF
{
	function MarcaDeAgua()
	{
		$this->SetFont('Courier', 'B', 40);
		$this->SetTextColor(230, 230, 230);
		$this->RotatedText(25, 220, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 45);

		$this->SetTextColor(0, 0, 0);
	}

	function RotatedText($x, $y, $txt, $angle)
	{
		$this->_out(sprintf(
			'q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm CP n',
			cos(deg2rad($angle)),
			sin(deg2rad($angle)),
			-sin(deg2rad($angle)),
			cos(deg2rad($angle)),
			$x, $y, -$x, -$y
		));
		$this->Text($x, $y, $txt);
		$this->_out('Q');
	}

	// Cabecera de página
	function Header()
	{
		/*
		if ($GLOBALS["impreso"] == "S") {
			$this->MarcaDeAgua();
		}
		*/

		// Consulto datos de la compañía
		require("../include/connect2.php");
		$sql = "SELECT id FROM compania ORDER BY id ASC LIMIT 0,1;";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$cia = $row["id"];

		$sql = "SELECT
					a.ci_rif, a.nombre, b.campo_descripcion AS ciudad,
					a.direccion, a.telefono1, a.email1
				FROM
					compania AS a
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
				WHERE a.id = '$cia';";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);
		$ciudad_compania = $row["ciudad"];

		if ($GLOBALS["estatus_doc"] == "PROCESADO") {
			// Documento fiscal procesado: imprimir datos congelados en salidas
			$sql = "SELECT
					cliente AS id,
					cliente_ci_rif AS ci_rif,
					cliente_nombre AS nombre,
					cliente_direccion AS direccion,
					cliente_telefono AS telf,
					'' AS ciudad
				FROM salidas
				WHERE id = '" . $GLOBALS["invoice_id"] . "';";
		} else {
			// Documento no procesado: imprimir datos vivos del maestro cliente
			$sql = "SELECT
					a.id, a.ci_rif, a.nombre, a.contacto,
					a.direccion, b.campo_descripcion AS ciudad,
					CONCAT(REPLACE(ifnull(a.telefono1,''), ' ', ''), ' ', REPLACE(ifnull(a.telefono2,''), ' ', '')) as telf
				FROM cliente AS a
					LEFT OUTER JOIN tabla AS b ON b.campo_codigo = a.ciudad AND b.tabla = 'CIUDAD'
				WHERE a.id = '" . $GLOBALS["cliente"] . "';";
		}

		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$rif = $row["ci_rif"];
		$razon_social = html_entity_decode(
		    $row["nombre"] ?? "",
		    ENT_QUOTES | ENT_HTML5,
		    "UTF-8"
		);
		$direccion_cliente = $row["direccion"];
		$ciudad_cliente = $row["ciudad"];
		$telf = $row["telf"];

		// --- ASUNCION: "CUENTA Nro:" = cliente.id formateado a 8 dígitos. Ajustar aquí si el origen real es otro. ---
		$GLOBALS["cuenta_cliente"] = sprintf('%08d', intval($row["id"]));

		require("../include/desconnect.php");

		// Condición de pago derivada de dias_credito (0 = Contado)
		// $condicion_pago = (intval($GLOBALS["dias_credito"]) <= 0) ? "Contado" : ("Credito " . $GLOBALS["dias_credito"] . " dias");
		$condicion_pago = ($GLOBALS["entregado"] == "S") ? "Contado" : "Credito" . ((intval($GLOBALS["dias_credito"]) > 1) ? " dias " . $GLOBALS["dias_credito"] : "");

		$tdoc = ($GLOBALS["documento"] == "FC" ? "Nro. Factura: " : ($GLOBALS["documento"] == "NC" ? "Nro. Nota de Credito: " : ($GLOBALS["documento"] == "ND" ? "Nro. Nota de Debito: " : "N/A")));
		if ($GLOBALS["es_prefactura"]) {
			$tdoc = "Nro. Pre-Factura: ";
		}

		// ---------------------------------------------------------------
		// Nro. de documento (o Nro. de prefactura), solo, en su propia línea
		// ---------------------------------------------------------------
		$this->Ln(15);
		$this->SetFont('Courier', 'B', 9);
		$this->Cell(150, 4, "", 0, 0, 'L');
		// rtrim() quita el espacio final de $tdoc (ej. "Nro. Factura: ") que descuadraba
		// el alineado a la derecha frente a "Fecha:" y "Hora:"
		$this->Cell(24, 4, rtrim($tdoc), 0, 0, 'R');
		$this->SetFont('Courier', '', 9);
		$this->Cell(20, 4, $GLOBALS["nro_documento_display"], 0, 1, 'L');

		$this->Ln(1);

		// ---------------------------------------------------------------
		// CLIENTE (izquierda) alineado con Fecha (derecha), misma línea
		// ---------------------------------------------------------------
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(5, 4);
		$this->Cell(25, 4, "CLIENTE:", 0, 0, 'L');
		$this->SetFont('Courier', '', 8);
		$razonSocialCorta = mb_substr(
		    $razon_social,
		    0,
		    55,
		    "UTF-8"
		);

		$this->Cell(
		    120,
		    4,
		    TextoFpdf($razonSocialCorta),
		    0,
		    0,
		    'L'
		);
		$this->SetFont('Courier', 'B', 9);
		$this->Cell(24, 4, "Fecha:", 0, 0, 'R');
		$this->SetFont('Courier', '', 9);
		$this->Cell(20, 4, $GLOBALS["fecha_display"], 0, 1, 'L');

		// ---------------------------------------------------------------
		// CUENTA Nro (izquierda) alineado con Hora (derecha), misma línea
		// ---------------------------------------------------------------
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(5, 4);
		$this->Cell(25, 4, "CUENTA Nro:", 0, 0, 'L');
		$this->SetFont('Courier', '', 8);
		$this->Cell(120, 4, $GLOBALS["cuenta_cliente"], 0, 0, 'L');
		$this->SetFont('Courier', 'B', 9);
		$this->Cell(24, 4, "Hora:", 0, 0, 'R');
		$this->SetFont('Courier', '', 9);
		$this->Cell(20, 4, $GLOBALS["hora"], 0, 1, 'L');

		$this->SetFont('Courier', 'B', 8);
		$this->Cell(5, 4);
		$this->Cell(25, 4, "DIRECCION:", 0, 0, 'L');
		$this->SetFont('Courier', '', 8);
		$direccion_completa = trim(
		    $direccion_cliente . ". " . $ciudad_cliente,
		    ". "
		);

		$direccion_completa = mb_substr(
		    $direccion_completa,
		    0,
		    150,
		    "UTF-8"
		);

		$this->MultiCell(
		    170,
		    3,
		    TextoFpdf($direccion_completa),
		    0,
		    'L'
		);

		$this->Ln(1);

		// ---------------------------------------------------------------
		// BLOQUE: Telefonos / RIF / Condiciones de pago / Tasa BCV
		// ---------------------------------------------------------------
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(5, 4);
		$this->Cell(20, 4, "TELEFONOS:", 0, 0, 'L');
		$this->SetFont('Courier', '', 8);
		$this->Cell(45, 4, str_replace("-", "", $telf ?? ""), 0, 0, 'L');

		$this->SetFont('Courier', 'B', 8);
		$this->Cell(15, 4, "R.I.F.:", 0, 0, 'L');
		$this->SetFont('Courier', '', 8);
		$this->Cell(35, 4, str_replace("-", "", $rif ?? ""), 0, 0, 'L');

		$this->SetFont('Courier', 'BI', 8);
		$this->Cell(40, 4, "CONDICIONES DE PAGO:", 0, 0, 'L');
		$this->Cell(25, 4, $condicion_pago, 0, 1, 'L');
		$this->SetFont('Courier', '', 8);

		$this->Ln(1);
		/*
		if ($GLOBALS["impreso"] == "S") {
			$this->SetFont('Courier', 'B', 7);
			$this->Cell(0, 4, mb_convert_encoding("SIN DERECHO A CRÉDITO FISCAL", "ISO-8859-1"), 0, 1, 'C');
		}
		*/

		// ---------------------------------------------------------------
		// Documento afectado (Notas de Credito / Debito), igual que en la versión completa
		// ---------------------------------------------------------------
		if ($GLOBALS["doc_afe"] != 0 && ($GLOBALS["documento"] == "NC" || $GLOBALS["documento"] == "ND")) {

			require("../include/connect2.php");
			$sql = "SELECT
						DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
						nro_documento,
						total
					FROM salidas
					WHERE id = " . intval($GLOBALS["doc_afe"]) . "
					LIMIT 1";
			$rs = mysqli_query($link, $sql);
			$rowDoc = mysqli_fetch_array($rs);
			require("../include/desconnect.php");

			$docAfectado = $rowDoc["nro_documento"] ?? $GLOBALS["doc_afectado"];
			$fechaAfectado = $rowDoc["fecha"] ?? "";
			$montoAfectado = floatval($rowDoc["total"] ?? 0);

			$this->Cell(5, 4);
			$this->SetFont('Courier', 'B', 7);
			$this->Cell(28, 4, 'Doc. Afectado:', 0, 0, 'R');
			$this->SetFont('Courier', '', 7);
			$this->Cell(25, 4, $docAfectado, 0, 0, 'L');
			$this->SetFont('Courier', 'B', 7);
			$this->Cell(15, 4, 'Fecha:', 0, 0, 'R');
			$this->SetFont('Courier', '', 7);
			$this->Cell(20, 4, $fechaAfectado, 0, 0, 'L');
			$this->SetFont('Courier', 'B', 7);
			$this->Cell(15, 4, 'Monto:', 0, 0, 'R');
			$this->SetFont('Courier', '', 7);
			$this->Cell(25, 4, number_format($montoAfectado, 2, ",", "."), 0, 1, 'L');
		}

		// ---------------------------------------------------------------
		// ENCABEZADO DE LA TABLA DE ARTICULOS (6 columnas)
		// ---------------------------------------------------------------
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(5, 5);
		$this->Cell(10, 5, "CANT", 1, 0, 'C');
		$this->Cell(85, 5, "DESCRIPCION", 1, 0, 'L');
		$this->Cell(28, 5, "PRECIO UNIT", 1, 0, 'R');
		$this->Cell(15, 5, "% ALIC", 1, 0, 'R');
		$this->Cell(33, 5, "TOT. Bs.", 1, 0, 'R');
		$this->Cell(22, 5, "TOT. $", 1, 0, 'R');
		$this->SetFont('Courier', '', 8);
		$this->Ln(5);
	}

	// Pie de página
	function Footer()
	{
		$this->SetY(-10);
		$this->SetFont('Courier', 'I', 7);
	}

	function EndReport($id_invoice)
	{
		require("../include/connect2.php");

		// 1. Datos generales de la factura y moneda
		$sql = "SELECT a.alicuota_iva, a.total, a.igtf, a.monto_base_igtf, a.monto_igtf,
					   IFNULL(a.nota, '') AS nota, a.moneda, a.id_documento_padre,
					   a.monto_usd, IFNULL(a.tasa_dia, 0) AS tasa_dia, a.descuento, a.descuento2, a.unidades,
					   IFNULL(a.nro_despacho, '') as nro_despacho
				FROM salidas a where a.id = '$id_invoice'";
		$rs = mysqli_query($link, $sql);
		$row = mysqli_fetch_array($rs);

		$moneda = mb_convert_encoding($row["moneda"], "UTF-8", mb_detect_encoding($row["moneda"]));
		$tasa_dia = ($row["tasa_dia"] == 0) ? 1 : $row["tasa_dia"];
		$dc = floatval($row["descuento"]);   // salidas.descuento  (DC) -> ahora se muestra solo en totales
		$dt = floatval($row["descuento2"]);  // salidas.descuento2 (DT) -> ahora se muestra solo en totales
		$igtf_status = $row["igtf"];
		$monto_igtf = floatval($row["monto_igtf"]);
		$monto_base_igtf = floatval($row["monto_base_igtf"]);
		$nota = mb_convert_encoding($row["nota"], "UTF-8", mb_detect_encoding($row["nota"]));
		$nro_despacho = $row["nro_despacho"];
		$alicuota_dinamica = $GLOBALS["alicuota_dinamica"];

		// 2. Totales de artículos SIN aplicar todavía DC/DT (montos "en bruto")
		//    (DP y DL ya vienen incluidos en precio_unidad desde la base de datos)
		$sql = "SELECT SUM(IF(IFNULL(alicuota, 0) = 0, precio_unidad, 0) * cantidad_articulo) AS exento,
					   SUM(IF(IFNULL(alicuota, 0) = 0, 0, precio_unidad) * cantidad_articulo) AS gravado,
					   MAX(IFNULL(alicuota,0)) AS alicuota_act
				FROM entradas_salidas WHERE tipo_documento = 'TDCFCV' AND id_documento = '$id_invoice';";
		$rs = mysqli_query($link, $sql);
		$row_tot = mysqli_fetch_array($rs);

		$exento_raw = floatval($row_tot["exento"]);
		$gravado_raw = floatval($row_tot["gravado"]);
		$xalicuota = floatval($row_tot["alicuota_act"]);

		// 3. Aplicar DC y DT en cascada (una sola vez, a nivel de totales) sobre exento y gravado
		$exento_desc_dc = $exento_raw * ($dc / 100);
		$gravado_desc_dc = $gravado_raw * ($dc / 100);
		$exento_tras_dc = $exento_raw - $exento_desc_dc;
		$gravado_tras_dc = $gravado_raw - $gravado_desc_dc;

		$exento_desc_dt = $exento_tras_dc * ($dt / 100);
		$gravado_desc_dt = $gravado_tras_dc * ($dt / 100);
		$exento = $exento_tras_dc - $exento_desc_dt;   // exento final, tras DC y DT
		$gravado = $gravado_tras_dc - $gravado_desc_dt; // gravado final, tras DC y DT

		$monto_dc = $exento_desc_dc + $gravado_desc_dc; // monto total descontado por DC
		$monto_dt = $exento_desc_dt + $gravado_desc_dt; // monto total descontado por DT

		$xIVA = $gravado * ($xalicuota / 100);
		$xTotal = $exento + $gravado + $xIVA;

		// Helper local para convertir un monto expresado en la moneda de la factura a Bs. y a $
		$to_bs = function ($monto) use ($moneda, $tasa_dia) {
			return ($moneda != 'Bs.') ? $monto * $tasa_dia : $monto;
		};
		$to_usd = function ($monto) use ($moneda, $tasa_dia) {
			return ($moneda != 'Bs.') ? $monto : ($tasa_dia > 0 ? $monto / $tasa_dia : 0);
		};

		// Se ancla el inicio del bloque de totales cerca de la mitad de la hoja Carta,
		// sin importar cuántas líneas tenga la tabla de artículos arriba.
		$this->Ln(95 - $this->GetY());

		// ---------------------------------------------------------------
		// COLETILLA LEGAL (a la izquierda del bloque de totales)
		// Se imprime con MultiCell y luego se restaura la Y para que el
		// bloque de totales, a la derecha, arranque en la misma altura.
		// ---------------------------------------------------------------
		$y_totales = $this->GetY();
		$this->SetFont('Courier', '', 5);
		$this->MultiCell(
		    100,
		    3,
		    TextoFpdf(
		        "Este documento se expresa en Dólares Americanos con su equivalente en Bolívares al tipo de cambio corriente " .
				"del mercado a la fecha de su emisión, según lo establecido en el artículo 13 numeral 14 de la Providencia " .
				"Administrativa SNAT/2011/0071, el artículo 128 de la Ley del Banco Central de Venezuela, el artículo 25 de la " .
				"Ley que establece el Impuesto al Valor Agregado y el 38 del Reglamento General de la Ley que establece el I.V.A."
		    ),
		    0,
		    'L'
		);

		// Usuario, debajo de la coletilla legal
		$this->SetFont('Courier', 'B', 6);
		$this->Cell(18, 3, "Usuario:", 0, 0, 'L');
		$this->SetFont('Courier', '', 6);
		$this->Cell(40, 3, $GLOBALS["username"], 0, 1, 'L');

		// ---------------------------------------------------------------
		// MÉTODOS DE PAGO (mismo origen de datos que comprobante_pago.php)
		// Se imprime debajo de "Usuario:" y antes de la nota de IGTF/Tasa BCV.
		// Para que quepa en la impresión a media carta, se concatenan todos
		// los pagos en un solo texto (separados por " / ") dentro de un
		// MultiCell, en vez de una tabla con una fila por pago.
		// ---------------------------------------------------------------
		$sql = "SELECT
					a.metodo_pago,
					IFNULL(b.valor2, a.metodo_pago) AS metodo_descripcion,
					a.referencia,
					a.monto_bs,
					a.moneda,
					a.monto_moneda, a.banco, d.campo_descripcion AS banco_descripcion
				FROM cobros_cliente_detalle AS a
				LEFT JOIN parametro AS b
					ON b.codigo = '009'
				   AND b.valor1 = a.metodo_pago
				LEFT OUTER JOIN compania_cuenta AS c ON c.id = a.banco
				LEFT OUTER JOIN tabla AS d ON d.campo_codigo = c.banco AND d.tabla = 'BANCO'
				WHERE a.cobros_cliente IN (
					SELECT id FROM cobros_cliente WHERE id_documento = '$id_invoice'
				) AND a.metodo_pago <> 'IG';";
		$rs_pagos = mysqli_query($link, $sql);

		$partes_pago = [];
		while ($row_pago = mysqli_fetch_array($rs_pagos)) {
			$es_igtf = ($row_pago['metodo_pago'] == 'IG');

			$metodo_txt = $es_igtf
				? "IGTF 3%"
				: mb_convert_encoding($row_pago['metodo_descripcion'], "UTF-8", mb_detect_encoding($row_pago['metodo_descripcion']));

			$parte = $metodo_txt;

			// Banco (si aplica al método de pago, ej. Transferencia / Pago Movil)
			$banco_txt = trim($row_pago['banco_descripcion'] ?? '');
			if ($banco_txt != '') {
				$parte .= ' ' . mb_convert_encoding($banco_txt, "UTF-8", mb_detect_encoding($banco_txt));
			}

			// Referencia (si aplica, ej. Transferencia / Pago Movil, no en Efectivo)
			$ref_txt = trim($row_pago['referencia'] ?? '');
			if ($ref_txt != '') {
				$parte .= ' #' . $ref_txt;
			}

			// Monto: en la moneda original del pago si no es Bs., si no en Bs.
			if ($row_pago['moneda'] != 'Bs.') {
				$parte .= ' ' . $row_pago['moneda'] . ' ' . number_format($row_pago['monto_moneda'], 2, ",", ".");
			} else {
				$parte .= ' Bs. ' . number_format($row_pago['monto_bs'], 2, ",", ".");
			}

			$partes_pago[] = $parte;
		}

		if (count($partes_pago) > 0) {
			$this->SetFont('Courier', 'BI', 6);
			$this->MultiCell(
			    100,
			    3,
			    TextoFpdf(
			        "Pagos: " . implode(' / ', $partes_pago)
			    ),
			    0,
			    'L'
			);
		}

		$y_fin_coletilla = $this->GetY();
		$this->SetY($y_totales);

		// Se reduce temporalmente el margen izquierdo 3mm para correr todo el bloque
		// de totales (etiquetas + montos) un poco a la izquierda. Se restaura justo
		// después de "TOTAL GENERAL", antes de la nota de IGTF/Tasa BCV del pie.
		$margen_izq_original = $this->lMargin;
		$this->SetLeftMargin($margen_izq_original - 3);
		$this->SetX($margen_izq_original - 3);

		// --- SUB-TOTAL EXENTO (en bruto, antes de DC/DT) ---
		// Solo se muestra esta línea si realmente hay monto exento (> 0)
		if ($exento_raw > 0) {
			$this->SetFont('Courier', 'B', 8);
			$this->Cell(155, 4, "SUB-TOTAL EXENTO:", 0, 0, 'R');
			$this->SetFont('Courier', '', 8);
			$this->Cell(21, 4, number_format($to_bs($exento_raw), 2, ",", "."), 0, 0, 'R');
			$this->Cell(22, 4, number_format($to_usd($exento_raw), 2, ",", "."), 0, 1, 'R');
		}

		// --- SUB-TOTAL GRAVADO (en bruto, antes de DC/DT) ---
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(155, 4, "SUB-TOTAL GRAVADO:", 0, 0, 'R');
		$this->SetFont('Courier', '', 8);
		$this->Cell(21, 4, number_format($to_bs($gravado_raw), 2, ",", "."), 0, 0, 'R');
		$this->Cell(22, 4, number_format($to_usd($gravado_raw), 2, ",", "."), 0, 1, 'R');

		// --- Descuento (DC) ---
		if ($dc > 0) {
			$this->SetFont('Courier', 'B', 8);
			$this->Cell(155, 4, "Descuento " . number_format($dc, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Courier', '', 8);
			$this->Cell(21, 4, number_format($to_bs($monto_dc), 2, ",", "."), 0, 0, 'R');
			$this->Cell(22, 4, number_format($to_usd($monto_dc), 2, ",", "."), 0, 1, 'R');
		}

		// --- Descuento2 (DT) ---
		if ($dt > 0) {
			$this->SetFont('Courier', 'B', 8);
			$this->Cell(155, 4, "Descuento2 " . number_format($dt, 2, ",", ".") . "%:", 0, 0, 'R');
			$this->SetFont('Courier', '', 8);
			$this->Cell(21, 4, number_format($to_bs($monto_dt), 2, ",", "."), 0, 0, 'R');
			$this->Cell(22, 4, number_format($to_usd($monto_dt), 2, ",", "."), 0, 1, 'R');
		}

		// --- IVA sobre el gravado ya neto de DC y DT ---
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(155, 4, "IVA " . number_format($xalicuota, 2, ",", ".") . "% SOBRE Bs. " . number_format($to_bs($gravado), 2, ",", ".") . ":", 0, 0, 'R');
		$this->SetFont('Courier', '', 8);
		$this->Cell(21, 4, number_format($to_bs($xIVA), 2, ",", "."), 0, 0, 'R');
		$this->Cell(22, 4, number_format($to_usd($xIVA), 2, ",", "."), 0, 1, 'R');

		// --- TOTAL ---
		$this->SetFont('Courier', 'B', 8);
		$this->Cell(155, 4, "TOTAL:", 0, 0, 'R');
		$this->SetFont('Courier', '', 8);
		$this->Cell(21, 4, number_format($to_bs($xTotal), 2, ",", "."), 0, 0, 'R');
		$this->Cell(22, 4, number_format($to_usd($xTotal), 2, ",", "."), 0, 1, 'R');

		// 4. Alícuota de IGTF vigente (se usa como referencia cuando salidas.igtf = 'N')
		$sql = "SELECT alicuota FROM alicuota WHERE codigo = 'IGT' AND activo = 'S' LIMIT 1;";
		$rs = mysqli_query($link, $sql);
		$row_igtf_def = mysqli_fetch_array($rs);
		$alicuota_igtf_defecto = floatval($row_igtf_def["alicuota"] ?? 0);

		// --- IGTF ---
		// salidas.monto_base_igtf y salidas.monto_igtf SIEMPRE quedan guardados en Bs.
		// (a diferencia del resto de los montos de la factura), por lo tanto aquí NO se
		// convierten con to_bs()/to_usd(): se usan tal cual para la columna Bs. y se
		// dividen entre la tasa del día para obtener el equivalente en $.
		//
		// MOSTRAR_IGTF_SIEMPRE controla si la fila de IGTF se imprime siempre (aunque
		// salidas.igtf = 'N', mostrando un monto de referencia calculado con la alícuota
		// vigente en la tabla `alicuota`) o solo cuando salidas.igtf = 'S' (comportamiento
		// clásico). Cambiar aquí a false para volver al comportamiento clásico.
		$MOSTRAR_IGTF_SIEMPRE = true;

		$total_bs = $to_bs($xTotal);

		if ($igtf_status == "S") {
			// IGTF realmente cobrado: se usan los montos reales guardados por el sistema.
			$porcentaje_igtf = $alicuota_dinamica;
			$base_igtf_mostrada = $monto_base_igtf; // salidas.monto_base_igtf, tal cual (ya en Bs.)
			$igtf_bs = $monto_igtf;                 // salidas.monto_igtf, tal cual (ya en Bs.)
		} else {
			// Sin cobro real de IGTF: se calcula un monto de referencia sobre el TOTAL
			// de la factura, con la alícuota vigente consultada en la tabla `alicuota`.
			$porcentaje_igtf = $alicuota_igtf_defecto;
			$base_igtf_mostrada = $total_bs;
			$igtf_bs = $total_bs * ($alicuota_igtf_defecto / 100);
		}
		$igtf_usd = ($tasa_dia > 0) ? $igtf_bs / $tasa_dia : 0;

		// El IGTF (real o de referencia) solo se suma al TOTAL GENERAL si la fila
		// realmente se está mostrando (igtf='S', o MOSTRAR_IGTF_SIEMPRE=true). Si
		// MOSTRAR_IGTF_SIEMPRE=false y salidas.igtf='N', ni se muestra ni se suma.
		$mostrar_fila_igtf = ($igtf_status == "S" || $MOSTRAR_IGTF_SIEMPRE);
		// Si el documento ya fue entregado (salidas.entregado='S') y está en Bs.,
		// normalmente se ocultan IGTF y Total General. Sin embargo, si la factura
		// realmente tiene IGTF (salidas.igtf='S'), la línea del IGTF debe mostrarse.
		$ocultar_igtf_total_general = (
		    $GLOBALS["entregado"] == "S" &&
		    $GLOBALS["moneda"] == "Bs." &&
		    $igtf_status != "S"
		);

		if ($ocultar_igtf_total_general) {
		    $mostrar_fila_igtf = false;
		}

		$total_general_bs = $total_bs + ($mostrar_fila_igtf ? $igtf_bs : 0);
		$total_general_usd = ($tasa_dia > 0) ? $total_general_bs / $tasa_dia : 0;

		if ($mostrar_fila_igtf) {
			$this->SetFont('Courier', 'B', 8);
			$this->Cell(155, 4, "I.G.T.F. " . number_format($porcentaje_igtf, 2, ",", ".") . "% SOBRE Bs. " . number_format($base_igtf_mostrada, 2, ",", ".") . ":", 0, 0, 'R');
			$this->SetFont('Courier', '', 8);
			$this->Cell(21, 4, number_format($igtf_bs, 2, ",", "."), 0, 0, 'R');
			$this->Cell(22, 4, number_format($igtf_usd, 2, ",", "."), 0, 1, 'R');
		}

		// --- TOTAL GENERAL ---
		if (!$ocultar_igtf_total_general) {
			$this->SetFont('Courier', 'B', 8);
			$this->Cell(155, 4, "TOTAL GENERAL:", 0, 0, 'R');
			$this->SetFont('Courier', '', 8);
			$this->Cell(21, 4, number_format($total_general_bs, 2, ",", "."), 0, 0, 'R');
			$this->Cell(22, 4, number_format($total_general_usd, 2, ",", "."), 0, 1, 'R');
		}

		// Se restaura el margen izquierdo original (el resto del pie sigue con el margen normal)
		$this->SetLeftMargin($margen_izq_original);

		// Continuar desde la posición más baja entre la coletilla (izquierda) y el bloque de totales (derecha)
		$this->SetY(max($y_fin_coletilla, $this->GetY()));
		$this->SetX($margen_izq_original);
		$this->Ln(2);
		$this->SetFont('Courier', 'B', 7);
		$this->Cell(115, 4, mb_convert_encoding("IGTF Sujeto a Pago Recibido (Efectivo $) segun Art 1 GO 42339 17/03/2022.", "UTF-8"), 0, 0, 'L');
		$this->Cell(63, 4, "Tasa BCV del Dia:", 0, 0, 'R');
		$this->SetFont('Courier', '', 7);
		$this->Cell(20, 4, number_format($tasa_dia, 2, ",", "."), 0, 1, 'R');

		if (trim($nota) != "") {
			$this->SetFont('Courier', 'B', 7);
			$this->Cell(5, 4);
			$this->Cell(0, 4, strtoupper($nota), 0, 1, 'L');
		}
		if (trim($nro_despacho) != "") {
			$this->SetFont('Courier', '', 7);
			$this->Cell(5, 4);
			$this->Cell(0, 4, "Nro. Despacho: " . $nro_despacho, 0, 1, 'L');
		}

		require("../include/desconnect.php");
	}
}

// ------------------------------------------------------------------------------------
// Creación del PDF a tamaño Carta completo (215.9 x 279.4mm)
// ------------------------------------------------------------------------------------
$pdf = new PDF('P', 'mm', 'Letter');
$pdf->SetMargins(7, 10, 10);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Courier', '', 8);


if ($GLOBALS["estatus_doc"] == "PROCESADO") {
	// Documento fiscal procesado: imprimir exclusivamente la foto congelada del detalle
	$select_codigo_articulo = "IFNULL(a.articulo_codigo, '') AS codigo";
	// a.lote es una columna propia de entradas_salidas (no es un dato "congelado"), existe igual en ambas ramas
	$select_descripcion_articulo = "LTRIM(RTRIM(CONCAT(IFNULL(a.articulo_descripcion, ''), ' ', IFNULL(a.lote, '')))) AS articulo";
} else {
	$select_codigo_articulo = "IFNULL(b.codigo, '') AS codigo";
	// Se concatena el lote (a.lote) al final de la descripción, según lo solicitado
	$select_descripcion_articulo = "LTRIM(RTRIM(CONCAT(IFNULL(b.nombre_comercial, ''), ' ', IFNULL(b.principio_activo, ''), ' ', IFNULL(b.presentacion, ''), ' ', IFNULL(a.lote, '')))) AS articulo";
}

$sql = "SELECT
			$select_codigo_articulo,
			$select_descripcion_articulo,
			a.cantidad_articulo AS cantidad,
			a.alicuota,
			a.precio_unidad
		FROM
			entradas_salidas AS a
			LEFT OUTER JOIN articulo AS b ON b.id = a.articulo
		WHERE
			a.id_documento = '$id_invoice' AND a.tipo_documento = '" . $GLOBALS["tipo_documento"] . "'
		ORDER BY b.principio_activo, b.presentacion;";

$rs = mysqli_query($link, $sql) or die(mysqli_error());
$sw = false;
$printE = "";
while ($row = mysqli_fetch_array($rs)) {
	$printE = floatval($row["alicuota"]) == 0.00 ? " (E)" : "";
	$pdf->SetFont('Courier', '', 7);

	$articuloCompleto = trim($row["articulo"]) . $printE;

	list($articuloLinea1, $articuloResto) =
    DividirTextoPorPalabras(
        $articuloCompleto,
        55
    );

	// precio_unidad ya viene neto de DP y DL (según lo indicado), se usa tal cual
	$precio_unit_db = floatval($row["precio_unidad"]);
	$cantidad = intval($row["cantidad"]);

	// Conversión a Bs./$ según la moneda de la factura (misma lógica usada en el resto del sistema)
	if ($GLOBALS["moneda"] != 'Bs.') {
		$val_precio = $precio_unit_db; // valor mostrado en PRECIO UNIT, en la moneda propia de la factura ($)
	} else {
		$val_precio = $precio_unit_db; // valor mostrado en PRECIO UNIT, en la moneda propia de la factura (Bs.)
	}

	$precio_linea = $precio_unit_db * $cantidad;
	if ($GLOBALS["moneda"] != 'Bs.') {
		$val_total_bs = $precio_linea * $tasa_dia;
		$val_total_usd = $precio_linea;
	} else {
		$val_total_bs = $precio_linea;
		$val_total_usd = ($tasa_dia > 0) ? $precio_linea / $tasa_dia : 0;
	}

	$pdf->Cell(5, 3);
	$pdf->Cell(10, 3, number_format($cantidad, 0, "", ""), 0, 0, 'C');
	$pdf->Cell(
	    85,
	    3,
	    TextoFpdf($articuloLinea1),
	    0,
	    0,
	    'L'
	);
	$pdf->Cell(28, 3, number_format($val_precio, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(15, 3, number_format($row["alicuota"], 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(33, 3, number_format($val_total_bs, 2, ",", "."), 0, 0, 'R');
	$pdf->Cell(22, 3, number_format($val_total_usd, 2, ",", "."), 0, 0, 'R');

	if ($articuloResto !== "") {
	    $pdf->Ln();
	    $pdf->Cell(15, 3);

	    $pdf->MultiCell(
	        160,
	        3,
	        TextoFpdf($articuloResto),
	        0,
	        'L'
	    );

	    $sw = true;
	}

	if ($sw == false) $pdf->Ln();
	$sw = false;

	// Reservamos espacio al final de la página para el bloque de totales + footer
	if ($pdf->GetY() > 250) $pdf->AddPage();
}

$pdf->EndReport($id_invoice);

require("../include/desconnect.php");

$pdf->Output();