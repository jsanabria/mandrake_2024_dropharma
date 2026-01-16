<?php 
class CrearComprobante {
	var $regla = 0;
	var $username = "";
	
	var $IdComprobante = "";
	var $Tipo="";
	var $Modalidad="";
	var $FechaD="";
	var $FechaH="";	 
	var $NoExite=false;

	var $NroComprobante=0;


	function __construct($xregla, $tipo_documento, $fecha, $nota, $xusername) {
		$this->regla = $xregla;
		$this->username = $xusername;


		include "connect.php"; 

		if($this->VerificaPeriodoContable($fecha, $tipo_documento)) {
			$sql = "SELECT COUNT(id) AS cantidad 
					FROM salidas 
					WHERE tipo_documento = '$tipo_documento' 
						AND estatus = 'PROCESADO' 
						AND fecha = '$fecha' AND comprobante IS NULL;"; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$cantidad = intval($row["cantidad"]);

			if($cantidad > 0) {
				// Se crea el comprobante
				$sql = "INSERT INTO cont_comprobante
							(id, tipo, fecha, contabilizacion, 
							descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
						VALUES 
							(NULL, '$tipo_documento', '$fecha', NULL, 
							'$nota', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
				mysqli_query($link, $sql);

				$sql = "SELECT LAST_INSERT_ID() AS id;"; 
				$rs = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($rs);
				$this->NroComprobante = $row["id"];
			} else $this->NoExite=true;
		} 
		else $this->NoExite=true;
	}	

	function Comprobante($id) {
		include "connect.php";

		switch($this->regla) {
		case 4:
			////// ----- Ventas de Mercancias ----- //////

			$aplica_retencion = 'N';

			$sql = "SELECT 
						a.tipo_documento, a.cliente, a.nro_documento AS documento, a.fecha, 
						a.nota AS descripcion, a.monto_total AS subtotal, a.alicuota_iva AS alicuota, 
						a.iva AS monto_iva, a.total AS monto_total, a.documento as tipo_trasn,  
						(SELECT SUM(costo) FROM entradas_salidas 
							WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS costo  
					FROM  
						salidas AS a  
					WHERE 
						a.id = " . $id; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$tipo_documento = $row["tipo_documento"];
			$fecha_documento = $row["fecha"];

			
			$cliente = $row["cliente"]; 
			$documento = $row["documento"]; 
			$descripcion = $row["descripcion"]; 
			
			$subtotal = $row["subtotal"]; 
			$alicuota = $row["alicuota"]; 
			$monto_iva = $row["monto_iva"]; 
			
			$monto_total = $row["monto_total"]; 
			$tipo_trasn = $row["tipo_trasn"]; 
			$referencia = $row["documento"]; 

			$costo = $row["costo"];

			// Se crea el asiento por cada factura
			$montos = ["tipo_trasn" => "$tipo_trasn", 
						"banco" => "0.00", 
						"monto_exento" => "0.00", 
						"monto_gravado" => "0.00", 
						"subtotal" => "$subtotal", 
						"monto_iva" => "$monto_iva", 
						"monto_total" => "0.00",  
						"ret_iva" => "0.00", 
						"ret_islr" => "0.00",
						"monto_pagar" => "$monto_total", "costo" => "$costo"];


			$this->Asiento($this->NroComprobante, $aplica_retencion, $cliente, $montos, $id, $referencia);

			$sql = "UPDATE salidas SET comprobante = " . $this->NroComprobante . " WHERE id = " . $id; 
			mysqli_query($link, $sql);

			break;	
		}

		include "desconnect.php";
	}
	
	function Asiento($comprobante, $aplica_retencion, $auxiliar, $montos, $ref, $xreferencia) {
		include "connect.php";

		$arr = ["100", "200", "300", "400", "500", "600", "700", "800", "900"];

		foreach ($arr as $key => $value) { 
			$sql = "SELECT cuenta, cargo FROM cont_reglas WHERE regla = " . $this->regla . " AND codigo = '$value';"; 

			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) {
				$cuenta = $row["cuenta"];
				$cargo = $row["cargo"];

				$debe = 0;
				$haber = 0;

				switch($value) {
				case "100": // Compra y Ventas
					switch ($this->regla) {
					case 1:
						$sql = "SELECT IFNULL(cuenta_gasto, 0) AS cuenta FROM proveedor WHERE id = $auxiliar;"; 
						$rs = mysqli_query($link, $sql);
						if($row = mysqli_fetch_array($rs)) {
							if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
						}
						
						if($cargo == "DEBE") $debe = $montos["monto_exento"] + $montos["monto_gravado"];
						if($cargo == "HABER") $haber = $montos["monto_exento"] + $montos["monto_gravado"];
						break;
					case 2:
						if($cargo == "DEBE") $debe = $montos["subtotal"];
						if($cargo == "HABER") $haber = $montos["subtotal"];
						break;
					case 4:
						if($montos["tipo_trasn"] == "NC") {
							if($cargo == "DEBE") $cargo = "HABER";
							else $cargo = "DEBE";
						}
						
						if($cargo == "DEBE") $debe = $montos["subtotal"];
						if($cargo == "HABER") $haber = $montos["subtotal"];
						break;
					}
					break;
				case "200": // IVA crédito y débito
					if($montos["tipo_trasn"] == "NC") {
						if($cargo == "DEBE") $cargo = "HABER";
						else $cargo = "DEBE";
					}
					
					if($cargo == "DEBE") $debe = $montos["monto_iva"];
					if($cargo == "HABER") $haber = $montos["monto_iva"];
					break;
				case "300": // Ret IVA por Pagar
					if($cargo == "DEBE") $debe = $montos["ret_iva"];
					if($cargo == "HABER") $haber = $montos["ret_iva"];
					break;
				case "400":
					if($cargo == "DEBE") $debe = $montos["ret_islr"];
					if($cargo == "HABER") $haber = $montos["ret_islr"];
					break;
				case "500":  // Cuentas por Pagar
					$sql = "SELECT cuenta_auxiliar AS cuenta FROM proveedor WHERE id = $auxiliar;"; 
					$rs = mysqli_query($link, $sql);
					if($row = mysqli_fetch_array($rs)) {
						if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
					}
					
					if($cargo == "DEBE") $debe = $montos["monto_pagar"];
					if($cargo == "HABER") $haber = $montos["monto_pagar"];
					break;
				case "600": // Caja y Banco
					$sql = "SELECT cuenta FROM compania_cuenta WHERE id = " . $montos["banco"] . ";"; 
					$rs = mysqli_query($link, $sql);
					if($row = mysqli_fetch_array($rs)) {
						if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
					}

					if($cargo == "DEBE") $debe = $montos["monto_pagar"];
					if($cargo == "HABER") $haber = $montos["monto_pagar"];
					break;
				case "700":  // Cuentas por Cobrar
					$sql = "SELECT cuenta FROM cliente WHERE id = $auxiliar;"; 
					$rs = mysqli_query($link, $sql);
					if($row = mysqli_fetch_array($rs)) {
						if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
					}
					
					if($montos["tipo_trasn"] == "NC") {
						if($cargo == "DEBE") $cargo = "HABER";
						else $cargo = "DEBE";
					}
					
					if($cargo == "DEBE") $debe = $montos["monto_pagar"];
					if($cargo == "HABER") $haber = $montos["monto_pagar"];
					break;
				case "800":
					if($this->regla == 4) {
						if($cargo == "DEBE") $debe = $montos["costo"];
						if($cargo == "HABER") $haber = $montos["costo"];
					} 
					else {
						if($cargo == "DEBE") $debe = $montos["subtotal"];
						if($cargo == "HABER") $haber = $montos["subtotal"];
					}
					break;
				case "900":
					if($this->regla == 4) {
						if($cargo == "DEBE") $debe = $montos["costo"];
						if($cargo == "HABER") $haber = $montos["costo"];
					} 
					else {
						if($cargo == "DEBE") $debe = $montos["subtotal"];
						if($cargo == "HABER") $haber = $montos["subtotal"];
					}
					break;
				default: 
					if($cargo == "DEBE") $debe = $montos["subtotal"];
					if($cargo == "HABER") $haber = $montos["subtotal"];
				}
				
				if($debe != 0 or $haber != 0) {
					$sql = "INSERT INTO cont_asiento
								(id, comprobante, cuenta, referencia, nota, debe, haber, id_referencia)
							VALUES 
								(NULL, $comprobante, $cuenta, '$xreferencia', '', $debe, $haber, $ref)"; 
					mysqli_query($link, $sql);			
				}
			}
		}

		include "desconnect.php";
	}


	function VerificaPeriodoContable($fecha, $tipo) {
		include "connect.php";

		$sql = "SELECT 
					cerrado 
				FROM 
					cont_periodo_contable 
				WHERE 
					'$fecha' BETWEEN fecha_inicio AND fecha_fin;"; 
		$rs = mysqli_query($link, $sql);

		if(!$row = mysqli_fetch_array($rs)) {
			//$this->CancelMessage = "El periodo contable no existe; verifique.";
			return FALSE;
		}
		else { 
			if($row["cerrado"] == "S") {
				//$this->CancelMessage = "El periodo contable est&aacute; cerrado; verifique.";
				return FALSE;
			}
		}


		$fc = explode("-", $fecha);
		$mes = "M" . str_pad($fc["1"], 2, "0", STR_PAD_LEFT);
		$sql = "SELECT 
					id 
				FROM 
					cont_mes_contable 
				WHERE 
					tipo_comprobante = '$tipo' AND $mes = 'S';";
		$rs = mysqli_query($link, $sql);
		if($row = mysqli_fetch_array($rs)) {
			//$this->CancelMessage = "El mes contable est&aacute; cerrado para el tipo de comprobante; verifique.";
			return FALSE;
		}

		include "desconnect.php";
		return TRUE;
	}
}
?>
