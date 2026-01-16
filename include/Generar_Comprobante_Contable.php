<?php 
include "connect.php";

$_id = $_REQUEST["id"];
$tipo_documento = $_REQUEST["tipo_documento"];
$_regla = $_REQUEST["regla"];
$_username = $_REQUEST["username"];

/*$sql= "UPDATE cont_comprobante SET contabiliza = '$username', contabilizacion = CURDATE(), fecha_contabiliza = CURDATE() WHERE id = $id;"; 
mysqli_query($link, $sql);
mysqli_fetch_array($rs);*/

$Cmpb = new CrearComprobante($_regla, $_id, $tipo_documento, $_username);

// echo "Datos: " . $Cmpb->regla . " | "  . $Cmpb->id . " | "  . $Cmpb->username ;

class CrearComprobante {
	var $regla = 0;
	var $id = 0;
	var $tipo_documento = "";
	var $username = "";
	
	var $IdComprobante = "";
	var $Tipo="";
	var $Modalidad="";
	var $FechaD="";
	var $FechaH="";	 
	var $NoExite=false;

	var $NroComprobante=0;

	var $moneda_current = "";

	var $fecha_usd = "";

	function __construct($xregla, $xid, $xtipo_documento, $xusername) {
		$this->regla = $xregla;
		$this->id = $xid; 
		$this->tipo_documento = $xtipo_documento; 
		$this->username = $xusername;

		$this->Comprobante();

		echo $this->NroComprobante;
	}	

	function Comprobante() {
		include "connect.php";
		$sql = "SELECT valor1 AS moneda_current FROM parametro WHERE codigo = '006' AND valor2 = 'default';"; 
		$rs = mysqli_query($link, $sql);
		if($row = mysqli_fetch_array($rs)) $this->moneda_current = $row["moneda_current"];

		$this->fecha_usd = date("Y-m-d");

		switch($this->regla) {
		case 1: 
			////// ----- Compras Administrativas ----- //////

			$sql = "SELECT descripcion FROM cont_reglas_hr WHERE id = " . $this->regla; 
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) $regla_nombre = $row["descripcion"];
			else break;

			$sql = "SELECT 
						proveedor, documento, fecha_registro AS fecha, descripcion, 
						aplica_retencion, 
						monto_exento, monto_gravado, alicuota, monto_iva, 
						monto_total, ret_iva, ret_islr, monto_pagar, ref_iva, ref_islr, tipo_documento AS tipo_trasn  
					FROM compra 
					WHERE 
						id = " . $this->id . ";"; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$tipo_documento = "COMPRA";
			$fecha_documento = $row["fecha"];

			if($this->VerificaPeriodoContable($fecha_documento, $tipo_documento)) {
				
				$proveedor = $row["proveedor"]; 
				$documento = $row["documento"]; 
				$descripcion = $row["descripcion"]; 
				
				$aplica_retencion = $row["aplica_retencion"]; 
				
				$monto_exento = $row["monto_exento"]; 
				$monto_gravado = $row["monto_gravado"]; 
				$alicuota = $row["alicuota"]; 
				$monto_iva = $row["monto_iva"]; 
				
				$monto_total = $row["monto_total"]; 
				$ret_iva = $row["ret_iva"]; 
				$ret_islr = $row["ret_islr"]; 
				$monto_pagar = $row["monto_pagar"]; 

				$ref_iva = $row["ref_iva"]; 
				$ref_islr = $row["ref_islr"]; 

				$tipo_trasn = $row["tipo_trasn"];

				// Se crea el comprobante

				$sql = "INSERT INTO cont_comprobante
							(id, tipo, fecha, contabilizacion, 
							descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
						VALUES 
							(NULL, '$tipo_documento', '$fecha_documento', NULL, 
							'$descripcion - $documento', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
				mysqli_query($link, $sql);

				$sql = "SELECT LAST_INSERT_ID() AS id;"; 
				$rs = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($rs);
				$comprobante = $row["id"];

				// Se crea el asiento
				$montos = ["tipo_trasn" => "$tipo_trasn", 
							"banco" => "0.00", 
							"monto_exento" => "$monto_exento", 
							"monto_gravado" => "$monto_gravado", 
							"subtotal" => "0.00",
							"monto_iva" => "$monto_iva", 
							"monto_total" => "$monto_total",  
							"ret_iva" => "$ret_iva", 
							"ret_islr" => "$ret_islr",
							"monto_pagar" => "$monto_pagar", 
							"ref_iva" => "$ref_iva", 
							"ref_islr" => "$ref_islr", 
							"monto_caja" => "0.00", 
							"diferencia" => "0.00"];

				$this->Asiento($comprobante, $aplica_retencion, $proveedor, $montos, '', '');

				$sql = "UPDATE compra SET comprobante = $comprobante WHERE id = " . $this->id; 
				mysqli_query($link, $sql);

				$this->NroComprobante = $comprobante;
			}

			break;
		case 2: 
			////// ----- Compras Inventario ----- //////

			$sql = "SELECT agente_retencion FROM compania WHERE id = 1;"; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$aplica_retencion = $row["agente_retencion"];

			$sql = "SELECT descripcion FROM cont_reglas_hr WHERE id = " . $this->regla; 
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) $regla_nombre = $row["descripcion"];
			else break;

			$sql = "SELECT 
						tipo_documento, 
						proveedor, nro_documento AS documento, fecha_libro_compra AS fecha, nota AS descripcion, 
						monto_total AS subtotal, alicuota_iva AS alicuota, iva AS monto_iva, 
						total AS monto_total, ret_iva, ret_islr, monto_pagar, ref_iva, ref_islr, documento AS tipo_trasn  
					FROM 
						entradas
					WHERE 
						id = " . $this->id . " AND tipo_documento = '" . $this->tipo_documento . "';";  
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$tipo_documento = $row["tipo_documento"];
			$fecha_documento = $row["fecha"];

			if($this->VerificaPeriodoContable($fecha_documento, $tipo_documento)) {
				
				$proveedor = $row["proveedor"]; 
				$documento = $row["documento"]; 
				$descripcion = $row["descripcion"]; 
				
				$subtotal = $row["subtotal"]; 
				$alicuota = $row["alicuota"]; 
				$monto_iva = $row["monto_iva"]; 
				
				$monto_total = $row["monto_total"]; 
				$ret_iva = $row["ret_iva"]; 
				$ret_islr = $row["ret_islr"]; 
				$monto_pagar = $row["monto_pagar"]; 

				$ref_iva = $row["ref_iva"]; 
				$ref_islr = $row["ref_islr"]; 

				$tipo_trasn = $row["tipo_trasn"];


				// Se crea el comprobante
				$sql = "SELECT nombre FROM proveedor WHERE id = $proveedor";
				$rs010 = mysqli_query($link, $sql);
				$row010 = mysqli_fetch_array($rs010);
				$nombre_proveedor = $row010["nombre"];

				$sql = "INSERT INTO cont_comprobante
							(id, tipo, fecha, contabilizacion, 
							descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
						VALUES 
							(NULL, '$tipo_documento', '$fecha_documento', NULL, 
							'$nombre_proveedor - $descripcion - $documento', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
				mysqli_query($link, $sql);

				$sql = "SELECT LAST_INSERT_ID() AS id;"; 
				$rs = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($rs);
				$comprobante = $row["id"];

				// Se crea el asiento
				$montos = ["tipo_trasn" => "$tipo_trasn", 
							"banco" => "0.00", 
							"monto_exento" => "0.00", 
							"monto_gravado" => "0.00", 
							"subtotal" => "$subtotal", 
							"monto_iva" => "$monto_iva", 
							"monto_total" => "$monto_total",  
							"ret_iva" => "$ret_iva", 
							"ret_islr" => "$ret_islr",
							"monto_pagar" => "$monto_pagar", 
							"ref_iva" => "$ref_iva", 
							"ref_islr" => "$ref_islr", 
							"monto_caja" => "0.00", 
							"diferencia" => "0.00"];

				$this->Asiento($comprobante, $aplica_retencion, $proveedor, $montos, '', '');

				$sql = "UPDATE entradas SET comprobante = $comprobante WHERE id = " . $this->id; 
				mysqli_query($link, $sql);

				$this->NroComprobante = $comprobante;
			}

			break;
		case 3: 
			////// ----- Pagos a Proveedores ----- //////

			$sql = "SELECT descripcion FROM cont_reglas_hr WHERE id = " . $this->regla; 
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) $regla_nombre = $row["descripcion"];
			else break;

			$sql = "SELECT  
						b.id,  
						a.proveedor, a.banco, a.fecha, a.referencia, a.nota, 
						c.nro_documento AS documento, b.tipo_documento, b.id_documento, 
						IF(b.abono='S', 'ABONO A FACTURA', 'PAGO COMPLETO FACTURA') AS descripcion, b.monto, 
						a.monto_dado, a.monto AS monto_facturas, 
						c.ret_iva, c.ref_iva, c.ret_islr, c.ref_islr, a.moneda   
					FROM 
						pagos_proveedor AS a 
						JOIN pagos_proveedor_factura AS b ON b.pagos_proveedor = a.id 
						JOIN view_x_pagar AS c ON c.id = b.id_documento AND c.tipo_documento = b.tipo_documento 
					WHERE 
						a.id = " . $this->id . " AND tipo_documento = '" . $this->tipo_documento . "'"; 
			$rs = mysqli_query($link, $sql);

			$_Pagos = 0;
			$_Compr = 0;
			$sw = true;
			$sw2 = true;
			while($row = mysqli_fetch_array($rs)) {
				$tipo_documento = "EGRESO"; // $row["tipo_documento"];
				$fecha_documento = $row["fecha"];
				$this->fecha_usd = $row["fecha"];
				$aplica_retencion = "N";

				if($this->VerificaPeriodoContable($fecha_documento, $tipo_documento)) {
					$id_pago = $row["id"]; 

					$proveedor = $row["proveedor"]; 
					$documento = $row["documento"]; 
					$descripcion = $row["descripcion"]; 
					$referencia = $row["referencia"]; 
					$banco = $row["banco"]; 
					
					$monto_pagar = $row["monto"]; 


					if($sw2) {
						$monto_dado = $row["monto_dado"];
						$monto_facturas = $row["monto_facturas"];
						$monto_dado_pivot = $monto_dado;
						$this->moneda_current = $row["moneda"];

						/* Si la Cuenta Contable está configurada en moneda USD se procede a tomar la tasa de cambio del día y hacer el cálculo cambiario */
						$sql015 = "SELECT cuenta FROM compania_cuenta WHERE id = $banco;"; 
						$rs015 = mysqli_query($link, $sql015);
						if($row015 = mysqli_fetch_array($rs015)) {
							if($row015["cuenta"] > 0) { 
								$cuenta = $row015["cuenta"];

								$sql015 = "SELECT IFNULL(moneda, 'USD') AS moneda FROM cont_plancta WHERE id = $cuenta;";
								$rs015 = mysqli_query($link, $sql015);
								$row015 = mysqli_fetch_array($rs015);
								$moneda = $row015["moneda"];
								if(trim($moneda) == "USD" or trim($this->moneda_current) == "USD") {
									$sql015 = "SELECT tasa FROM tasa_usd WHERE fecha <= '" . $this->fecha_usd . "' ORDER BY id DESC LIMIT 0, 1;"; 
									$rs015 = mysqli_query($link, $sql015);
									$row015 = mysqli_fetch_array($rs015); 
									$tasa = floatval($row015["tasa"]);

									$monto_dado = $monto_dado * $tasa;
								}
							}
						}
						//////////////////////////////

						$diferencia = $monto_dado - $monto_facturas;
						$monto_dado = $monto_dado_pivot;
						$sw2 = false;
					}
					else {
						$monto_dado = 0.00;
						$monto_facturas = 0.00;
						$diferencia = 0.00;
					}

					if($sw) { 
						// Se crea el encabezado del comprobante
						$sql = "SELECT nombre FROM proveedor WHERE id = $proveedor";
						$rs010 = mysqli_query($link, $sql);
						$row010 = mysqli_fetch_array($rs010);
						$nombre_proveedor = $row010["nombre"];
						$sql = "INSERT INTO cont_comprobante
									(id, tipo, fecha, contabilizacion, 
									descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
								VALUES 
									(NULL, '$tipo_documento', '$fecha_documento', NULL, 
									'$descripcion - $documento - # REF $referencia - $nombre_proveedor', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
						mysqli_query($link, $sql);

						$sql = "SELECT LAST_INSERT_ID() AS id;"; 
						$rs2 = mysqli_query($link, $sql);
						$row2 = mysqli_fetch_array($rs2);
						$comprobante = $row2["id"];

						$sw = false;
					}

					// Se crea el asiento
					$montos = ["tipo_trasn" => "", 
								"banco" => "$banco", 
								"monto_exento" => "0.00", 
								"monto_gravado" => "0.00", 
								"subtotal" => "0.00", 
								"monto_iva" => "0.00", 
								"monto_total" => "0.00",  
								"ret_iva" => "0.00", 
								"ret_islr" => "0.00",
								"monto_pagar" => "$monto_pagar", 
								"ref_iva" => "", 
								"ref_islr" => "", 
								"monto_caja" => "$monto_dado", 
								"diferencia" => "$diferencia"];

					$this->Asiento($comprobante, $aplica_retencion, $proveedor, $montos, $documento, $referencia);

					$sql = "UPDATE pagos_proveedor_factura SET comprobante = $comprobante WHERE id = " . $id_pago; 
					mysqli_query($link, $sql);

					$_Compr++;
					$this->NroComprobante .= "$comprobante - ";
				}
				$_Pagos++;
			}

			if($this->NroComprobante != "") { 
				$this->NroComprobante .= "Comprobantes creados $_Compr de $_Pagos (" . $this->NroComprobante . "). !!! De no genrenarse todos los comprobantes debe verificar que los periodos contables esten abiertos !!! VERIFIQUE.";

				$sql = "UPDATE pagos_proveedor SET comprobante = 'S' WHERE id = " . $this->id; 
				mysqli_query($link, $sql);
			}

			break;
		case 4:
			////// ----- Ventas de Mercancias ----- //////

			$aplica_retencion = 'N';

			$sql = "SELECT descripcion FROM cont_reglas_hr WHERE id = " . $this->regla; 
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) $regla_nombre = $row["descripcion"];
			else break;

			$sql = "SELECT 
						a.tipo_documento, a.cliente, a.nro_documento AS documento, a.fecha, 
						a.nota AS descripcion, a.monto_total AS subtotal, a.alicuota_iva AS alicuota, 
						a.iva AS monto_iva, a.total AS monto_total, a.documento as tipo_trasn, 
						(SELECT SUM(costo) FROM entradas_salidas 
							WHERE tipo_documento = a.tipo_documento AND id_documento = a.id) AS costo  
					FROM 
						salidas AS a 
					WHERE 
						a.id = " . $this->id . " AND a.tipo_documento = '" . $this->tipo_documento . "'"; ; 
			$rs = mysqli_query($link, $sql);
			$row = mysqli_fetch_array($rs);
			$tipo_documento = $row["tipo_documento"];
			$fecha_documento = $row["fecha"];

			if($this->VerificaPeriodoContable($fecha_documento, $tipo_documento)) {
				
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


				// Se crea el comprobante

				$sql = "INSERT INTO cont_comprobante
							(id, tipo, fecha, contabilizacion, 
							descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
						VALUES 
							(NULL, '$tipo_documento', '$fecha_documento', NULL, 
							'$descripcion - $documento', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
				mysqli_query($link, $sql);

				$sql = "SELECT LAST_INSERT_ID() AS id;"; 
				$rs = mysqli_query($link, $sql);
				$row = mysqli_fetch_array($rs);
				$comprobante = $row["id"];

				// Se crea el asiento
				$montos = ["tipo_trasn" => "$tipo_trasn", 
							"banco" => "0.00", 
							"monto_exento" => "0.00", 
							"monto_gravado" => "0.00", 
							"subtotal" => "$subtotal", 
							"monto_iva" => "$monto_iva", 
							"monto_total" => "0.00",  
							"ret_iva" => "0.00", 
							"ret_islr" => "0.00",
							"monto_pagar" => "$monto_total", 
							"ref_iva" => "", 
							"ref_islr" => "", "costo" => "$costo", 
							"monto_caja" => "0.00", 
							"diferencia" => "0.00"];


				$this->Asiento($comprobante, $aplica_retencion, $cliente, $montos, $referencia, '');

				$sql = "UPDATE salidas SET comprobante = $comprobante WHERE id = " . $this->id; 
				mysqli_query($link, $sql);

				$this->NroComprobante = $comprobante;
			}

			break;
		case 5:
			////// ----- Cobros a Clientes ----- //////

			$sql = "SELECT descripcion FROM cont_reglas_hr WHERE id = " . $this->regla; 
			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) $regla_nombre = $row["descripcion"];
			else break;

			$sql = "SELECT 
						b.id, a.cliente, a.banco, a.fecha, a.referencia, a.nota, 
						c.nro_documento AS documento, b.tipo_documento, b.id_documento, 
						IF(b.abono='S', 'ABONO A FACTURA', 'PAGO COMPLETO FACTURA') AS descripcion, 
						b.retivamonto, b.retiva, b.retislrmonto, b.retislr, 
						(b.monto + IFNULL(b.retivamonto, 0) + IFNULL(b.retislrmonto, 0)) AS monto, b.monto AS monto_pagar, 
						a.monto_recibido, a.monto AS monto_facturas, 
						b.retiva, b.retislr, a.moneda   
					FROM 
						cobros_cliente AS a 
						JOIN cobros_cliente_factura AS b ON b.cobros_cliente = a.id 
						JOIN salidas AS c ON c.id = b.id_documento AND c.tipo_documento = b.tipo_documento 
					WHERE a.id = " . $this->id . ""; ; 
			$rs = mysqli_query($link, $sql);

			$_Pagos = 0;
			$_Compr = 0;
			$sw = true;
			$sw2 = true;
			while($row = mysqli_fetch_array($rs)) {
				$tipo_documento = "INGRES"; // $row["tipo_documento"];
				$fecha_documento = $row["fecha"];
				$this->fecha_usd = $row["fecha"];

				$aplica_retencion = "N";

				if($this->VerificaPeriodoContable($fecha_documento, $tipo_documento)) {
					$id_pago = $row["id"]; 

					$cliente = $row["cliente"]; 
					$documento = $row["documento"]; 
					$descripcion = $row["descripcion"]; 
					$referencia = $row["referencia"]; 
					$banco = $row["banco"]; 
					
					$monto_pagar = $row["monto_pagar"]; 

					$retivamonto = $row["retivamonto"]; 
					$retislrmonto = $row["retislrmonto"]; 
					$monto = $row["monto"];

					if($sw2) {
						$monto_recibido = $row["monto_recibido"];
						$monto_facturas = $row["monto_facturas"];
						$monto_recibido_pivot = $monto_recibido;
						$this->moneda_current = $row["moneda"];

						/* Si la Cuenta Contable está configurada en moneda USD se procede a tomar la tasa de cambio del día y hacer el cálculo cambiario */
						$sql015 = "SELECT cuenta FROM compania_cuenta WHERE id = $banco;"; 
						$rs015 = mysqli_query($link, $sql015);
						if($row015 = mysqli_fetch_array($rs015)) {
							if($row015["cuenta"] > 0) { 
								$cuenta = $row015["cuenta"];

								$sql015 = "SELECT IFNULL(moneda, 'USD') AS moneda FROM cont_plancta WHERE id = $cuenta;";
								$rs015 = mysqli_query($link, $sql015);
								$row015 = mysqli_fetch_array($rs015);
								$moneda = $row015["moneda"];
								if(trim($moneda) == "USD" or trim($this->moneda_current) == "USD") {
									$sql015 = "SELECT tasa FROM tasa_usd WHERE fecha <= '" . $this->fecha_usd . "' ORDER BY id DESC LIMIT 0, 1;"; 
									$rs015 = mysqli_query($link, $sql015);
									$row015 = mysqli_fetch_array($rs015); 
									$tasa = floatval($row015["tasa"]);

									$monto_recibido = $monto_recibido * $tasa;
								}
							}
						}
						//////////////////////////////

						$diferencia = $monto_recibido - $monto_facturas;
						$monto_recibido = $monto_recibido_pivot;
						$sw2 = false;
					}
					else {
						$monto_recibido = 0.00;
						$monto_facturas = 0.00;
						$diferencia = 0.00;
					}

					$ref_iva = $row["retiva"]; 
					$ref_islr = $row["retislr"]; 

					$tipo_trasn = ""; 

					if($sw) { 
						$sql = "SELECT nombre FROM cliente WHERE id = $cliente";
						$rs010 = mysqli_query($link, $sql);
						$row010 = mysqli_fetch_array($rs010);
						$nombre_cliente = $row010["nombre"];
						// Se crea el comprobante
						$sql = "INSERT INTO cont_comprobante
									(id, tipo, fecha, contabilizacion, 
									descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
								VALUES 
									(NULL, '$tipo_documento', '$fecha_documento', NULL, 
									'$descripcion - $nombre_cliente - # REF $referencia', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
						mysqli_query($link, $sql);

						$sql = "SELECT LAST_INSERT_ID() AS id;"; 
						$rs2 = mysqli_query($link, $sql);
						$row2 = mysqli_fetch_array($rs2);
						$comprobante = $row2["id"];

						$sw = false;
					}

					// Se crea el asiento
					$montos = ["tipo_trasn" => "$tipo_trasn", 
								"banco" => "$banco", 
								"monto_exento" => "0.00", 
								"monto_gravado" => "0.00", 
								"subtotal" => "0.00", 
								"monto_iva" => "0.00", 
								"monto_total" => "$monto",  
								"ret_iva" => "$retivamonto", 
								"ret_islr" => "$retislrmonto",
								"monto_pagar" => "$monto_pagar", 
								"ref_iva" => "$ref_iva", 
								"ref_islr" => "$ref_islr", "documento" => "$documento", 
								"monto_caja" => "$monto_recibido",
								"diferencia" => "$diferencia"];

					$this->Asiento($comprobante, $aplica_retencion, $cliente, $montos, $documento, $referencia);

					$sql = "UPDATE cobros_cliente_factura SET comprobante = $comprobante WHERE id = " . $id_pago; 
					mysqli_query($link, $sql);

					$_Compr++;
					$this->NroComprobante .= "$comprobante - ";
				}
				$_Pagos++;
			}

			if($this->NroComprobante != "") { 
				$this->NroComprobante .= "Comprobantes creados $_Compr de $_Pagos (" . $this->NroComprobante . "). !!! De no genrenarse todos los comprobantes debe verificar que los periodos contables esten abiertos !!! VERIFIQUE.";

				$sql = "UPDATE cobros_cliente SET comprobante = 'S' WHERE id = " . $this->id; 
				mysqli_query($link, $sql);
			}

			break;		
		}

		include "desconnect.php";
	}
	
	function Asiento($comprobante, $aplica_retencion, $auxiliar, $montos, $xreferencia, $xreferencia2) {
		include "connect.php";

		$arr = ["100", "200", "300", "400", "500", "600", "700", "800", "900"];

		$sw = false;
		foreach ($arr as $key => $value) { 
			$sql = "SELECT cuenta, cargo FROM cont_reglas WHERE regla = " . $this->regla . " AND codigo = '$value';"; 

			$rs = mysqli_query($link, $sql);
			if($row = mysqli_fetch_array($rs)) {
				$cuenta = $row["cuenta"];
				$cargo = $row["cargo"];

				$debe = 0;
				$haber = 0;

				if($montos["tipo_trasn"] == "NC") {
					if($cargo == "DEBE") $cargo = "HABER";
					else $cargo = "DEBE";
				}
				
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
						if($cargo == "DEBE") $debe = $montos["subtotal"];
						if($cargo == "HABER") $haber = $montos["subtotal"];
						break;
					case 3:
						if($cargo == "DEBE") $debe = $montos["diferencia"];
						if($cargo == "HABER") $haber = $montos["diferencia"];
						break;
					case 5:
						if($cargo == "DEBE") $debe = $montos["diferencia"];
						if($cargo == "HABER") $haber = $montos["diferencia"];
						break;
					}
					break;
				case "200": // IVA crédito y débito
					if($cargo == "DEBE") $debe = $montos["monto_iva"];
					if($cargo == "HABER") $haber = $montos["monto_iva"];
					break;
				case "300": // Ret IVA por Pagar / Cobrar
					if($cargo == "DEBE") $debe = $montos["ret_iva"];
					if($cargo == "HABER") $haber = $montos["ret_iva"];

					$xreferencia = $montos["ref_iva"];
					break;
				case "400": // Ret ISLR por Pagar / Cobrar
					if($cargo == "DEBE") $debe = $montos["ret_islr"];
					if($cargo == "HABER") $haber = $montos["ret_islr"];

					$xreferencia = $montos["ref_islr"];
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

					if($cargo == "DEBE") $debe = $montos["monto_caja"];
					if($cargo == "HABER") $haber = $montos["monto_caja"];
					$sw = true;
					break;
				case "700":  // Cuentas por Cobrar
					$sql = "SELECT cuenta FROM cliente WHERE id = $auxiliar;"; 
					$rs = mysqli_query($link, $sql);
					if($row = mysqli_fetch_array($rs)) {
						if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
					}
					
					switch ($this->regla) {
					case 4:
						if($cargo == "DEBE") $debe = $montos["monto_pagar"];
						if($cargo == "HABER") $haber = $montos["monto_pagar"];
						break;
					case 5:
						if($cargo == "DEBE") $debe = $montos["monto_total"];
						if($cargo == "HABER") $haber = $montos["monto_total"];
						break;
					}
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

				switch ($this->regla) {
				case 1:
					if($value == "300" or $value == "400") {
						$xref1 = $xreferencia;
						$xref2 = "";
					} 
					else {
						$xref1 = "";
						$xref2 = "";
					}
					break;
				case 2:
					if($value == "300" or $value == "400") {
						$xref1 = $xreferencia;
						$xref2 = "";
					} 
					else {
						$xref1 = "";
						$xref2 = "";
					}
					break;
				case 3:
					$xref2 = $xreferencia2;
					if($value == "600") {
						$xref1 = $xreferencia2;
					} else $xref1 = $xreferencia;
					break;
				case 4:
					$xref1 = $xreferencia;
					$xref2 = "";
					break;
				case 5:
					$xref2 = $xreferencia2;
					if($value == "600") {
						$xref1 = $xreferencia2;
					} 
					elseif($value == "700") {
						$xref1 = $montos["documento"];
					}
					elseif($value == "100") {
						$xref1 = "";
					} 
					else $xref1 = $xreferencia;
					break;
				default:
				}

				
				if($debe != 0 or $haber != 0) {
					/*
					if($this->regla == 3 or $this->regla == 5) {
						$sql = "SELECT cuenta FROM cont_asiento WHERE comprobante = $comprobante AND cuenta = $cuenta;";
						$rs = mysqli_query($link, $sql);
						if(!$row = mysqli_fetch_array($rs)) $sw = false;
					}
					*/

					/* Si la Cuenta Contable está configurada en moneda USD se procede a tomar la tasa de cambio del día y hacer el cálculo cambiario */
					$sql = "SELECT IFNULL(moneda, 'USD') AS moneda FROM cont_plancta WHERE id = $cuenta;";
					$rs = mysqli_query($link, $sql);
					$row = mysqli_fetch_array($rs);
					$moneda = $row["moneda"];
					if(trim($moneda) == "USD") {
						$sql = "SELECT tasa FROM tasa_usd WHERE fecha <= '" . $this->fecha_usd . "' ORDER BY id DESC LIMIT 0, 1;"; 
						$rs = mysqli_query($link, $sql);
						$row = mysqli_fetch_array($rs); 
						$tasa = floatval($row["tasa"]);

						$xref1 .= " USD " . number_format(($debe==0 ? $haber : $debe), 2, ",", ".");
						$xref2 .= " Tasa Bs " . number_format($tasa, 2, ",", ".");
						$debe = $debe * floatval($row["tasa"]);
						$haber = $haber * floatval($row["tasa"]);
					}
					//////////////////////////////

					if($debe < 0) { 
						$pivote = $haber; 
						$haber = (-1) * $debe;
						$debe = $pivote;
					}

					if($haber < 0) { 
						$pivote = $debe; 
						$debe = (-1) * $haber;
						$haber = $pivote;
					}

					/*
					if($sw) {
						$sql = "UPDATE cont_asiento SET debe = debe + $debe, haber = haber + $haber 
								WHERE comprobante = $comprobante AND cuenta = $cuenta;";
					}
					else {
					*/
						$sql = "INSERT INTO cont_asiento
									(id, comprobante, cuenta, referencia, nota, debe, haber)
								VALUES 
									(NULL, $comprobante, $cuenta, '$xref1', '$xref2', $debe, $haber)"; 
					/*
					}
					$sw = false;
					*/
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
