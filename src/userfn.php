<?php

namespace PHPMaker2024\mandrake;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
use Closure;

// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid = "")
{
    $today = getdate();
    $lastmonth = mktime(0, 0, 0, $today['mon'] - 1, 1, $today['year']);
    $val = date("Y|m", $lastmonth);
    $wrk = $FldExpression . " BETWEEN " .
        QuotedValue(DateValue("month", $val, 1, $dbid), DataType::DATE, $dbid) .
        " AND " .
        QuotedValue(DateValue("month", $val, 2, $dbid), DataType::DATE, $dbid);
    return $wrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid = "")
{
    return $FldExpression . Like("'A%'", $dbid);
}

// Global user functions
// Database Connecting event
function Database_Connecting(&$info) {
	// Example:
	//var_dump($info);
	//echo "<br><br>" . CurrentUserIP() .  "<br><br>";
	date_default_timezone_set('America/La_Paz');
	if (!IsLocal()) {
		$info["host"] = "localhost";
		$info["user"] = "drophqsc_drake";
		$info["pass"] = "Tomj@vas001";
		$info["db"] = "drophqsc_mandrake";
	}
	if(isset($_COOKIE["strcon"]) and trim($_COOKIE["strcon"]) != "") {
		$info["db"] = $_COOKIE["strcon"];
	}
}

// Database Connected event
function Database_Connected($conn)
{
    // Example:
    //if ($conn->info["id"] == "DB") {
    //    $conn->executeQuery("Your SQL");
    //}
}

// Language Load event
function Language_Load()
{
    // Example:
    //$this->setPhrase("MyID", "MyValue"); // Refer to language file for the actual phrase id
    //$this->setPhraseClass("MyID", "fa-solid fa-xxx ew-icon"); // Refer to https://fontawesome.com/icons?d=gallery&m=free [^] for icon name
}

function MenuItem_Adding($item) {
	//var_dump($item);
	// Return FALSE if menu item not allowed

    /*
	if ($item->Text == "--") $item->Text = "<HR/>";
	$variable = array(
					'031'=>'Pedido de Compra',
					'032'=>'Nota de Recepción',
					'033'=>'Factura de Compra',
					'034'=>'Ajuste de Entrada',
					'035'=>'Pedido de Ventas',
					'036'=>'Nota de Entrega',
					'037'=>'Factura de Venta',
					'038'=>'Ajuste de Salida'
					);
	foreach ($variable as $key => $value) {
		if ($item->Text == $value) {
			if(VerificaFuncion($key))
				$item->Allowed = FALSE;
		}
	}
	$item->Allowed = TRUE;
    */
}

function Menu_Rendering() {
    /*
	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels WHERE userlevelid = '" . CurrentUserLevel() . "';";
	$grupo = trim(ExecuteScalar($sql));
	if($grupo == "CLIENTE") {
		if ($this->IsRoot) { // Root menu
			$this->Clear(); // Clear all menu items
			$this->AddMenuItem(1, "Pedido", "Pedido de Ventas", "SalidasList?tipo=TDCPDV");
			//$this->AddMenuItem(2, "Articulo", "Consulta de Articulos", "ArticuloList");
			//$this->AddMenuItem(2, "Nota", "Nota de Entrega", "salidaslist.php?tipo=TDCNET");
			//$this->AddMenuItem(3, "Factura", "Factura de Venta", "salidaslist.php?tipo=TDCFCV");
			//$this->AddMenuItem(4, "Entregados", "Pedidos Entregados", "view_facturas_a_entregarlist.php");
			//$this->AddMenuItem(4, "Salir", "Salir", "Logout");
		}	
	}
	if($grupo == "PROVEEDOR") {
		$sql = "SELECT proveedor FROM usuario WHERE username = '" . CurrentUserName() . "';";
		$proveedor = trim(ExecuteScalar($sql));
		if ($this->IsRoot) { // Root menu
			$this->Clear(); // Clear all menu items
			$this->AddMenuItem(1, "Proveedor", "Ficha Proveedor", "ProveedorView?showdetail=proveedor_articulo&id=$proveedor");
			$this->AddMenuItem(2, "Facturas", "Facturas Proveedor", "EntradasList?tipo=TDCFCC");
			// $this->AddMenuItem(3, "Ventas", "Ventas por Laboratorio", "ventas_por_laboratorio.php");
			//$this->AddMenuItem(4, "Salir", "Salir", "Logout");
		}	
	}
    */
	$sql = "SELECT DATABASE()";
	$database = ExecuteScalar($sql);
	$sql = "SELECT nombre FROM usuario WHERE username = '" . CurrentUserName() . "';";
	$usuario = trim(ExecuteScalar($sql));
	$row = ExecuteRow("SELECT nombre, logo FROM compania LIMIT 0,1;");
	$cia = $row["nombre"];
	$logo = $row["logo"];
	if ($this->Id == "menu") { 
		if(trim($usuario) == "") 
			$this->AddMenuItem(10000, "InfoSYSUser", "DB: $database<br>USR: " . CurrentUserName() . "<br>CIA: $cia", "#", -1, "", IsLoggedIn());
		else
			$this->AddMenuItem(10000, "InfoSYSUser", "USR: $usuario" . "<br>CIA: $cia", "#", -1, "", IsLoggedIn());
		$this->AddMenuItem(11000, "InfoLogo", "<center><img src='carpetacarga/$logo' width='150' class='img-rounded img-responsive center-block'></center>", "#", -1, "", IsLoggedIn());	
		$this->moveItem("Download", $this->Count() - 1); 
	}
	//$this->MoveItem("Logout", $this->Count() - 1);
}

function Menu_Rendered()
{
    // Clean up here
}

// Page Loading event
function Page_Loading()
{
    //Log("Page Loading");
}

// Page Rendering event
function Page_Rendering()
{
    //Log("Page Rendering");
}

// Page Unloaded event
function Page_Unloaded()
{
    //Log("Page Unloaded");
}

// AuditTrail Inserting event
function AuditTrail_Inserting(&$rsnew)
{
    //var_dump($rsnew);
    return true;
}

// Personal Data Downloading event
function PersonalData_Downloading($row)
{
    //Log("PersonalData Downloading");
}

// Personal Data Deleted event
function PersonalData_Deleted($row)
{
    //Log("PersonalData Deleted");
}

// One Time Password Sending event
function Otp_Sending($usr, $client)
{
    // Example:
    // var_dump($usr, $client); // View user and client (Email or SMS object)
    // if (SameText(Config("TWO_FACTOR_AUTHENTICATION_TYPE"), "email")) { // Possible values, email or SMS
    //     $client->Content = ...; // Change content
    //     $client->Recipient = ...; // Change recipient
    //     // return false; // Return false to cancel
    // }
    return true;
}

// Route Action event
function Route_Action($app)
{
    // Example:
    // $app->get('/myaction', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
    // $app->get('/myaction2', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction2"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
}

// API Action event
function Api_Action($app)
{
    // Example:
    // $app->get('/myaction', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
    // $app->get('/myaction2', function ($request, $response, $args) {
    //    return $response->withJson(["name" => "myaction2"]); // Note: Always return Psr\Http\Message\ResponseInterface object
    // });
}

// Container Build event
function Container_Build($builder)
{
    // Example:
    // $builder->addDefinitions([
    //    "myservice" => function (ContainerInterface $c) {
    //        // your code to provide the service, e.g.
    //        return new MyService();
    //    },
    //    "myservice2" => function (ContainerInterface $c) {
    //        // your code to provide the service, e.g.
    //        return new MyService2();
    //    }
    // ]);
}

function ActualizarExitencia() {
    $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
    $tipo_documento = 'TDCNET';
    if($row = ExecuteRow($sql)) $tipo_documento = $row["tipo_documento"];
	$sql = "SELECT COUNT(id) AS cantidad FROM articulo WHERE articulo_inventario='S';";
	$cantidad = ExecuteScalar($sql);
    $sql  = "SELECT id AS articulo FROM articulo WHERE articulo_inventario='S';";
    $rows = ExecuteRows($sql);
	foreach($rows as $clave => $valor) {
		$articulo = $valor["articulo"];
		$sql = "SELECT 
				   IFNULL(SUM(a.cantidad_movimiento), 0) AS pedidos_nuevos 
				FROM 
				  entradas_salidas AS a 
				  JOIN salidas AS b ON
					b.tipo_documento = a.tipo_documento
					AND b.id = a.id_documento 
				  JOIN almacen AS c ON
					c.codigo = a.almacen AND c.movimiento = 'S'
				WHERE
				  a.tipo_documento IN ('TDCPDV')
				  AND a.articulo = $articulo AND b.estatus = 'NUEVO';";
		$pedido = floatval(ExecuteScalar($sql));
		$sql = "SELECT 
		  			IFNULL(SUM(a.cantidad_movimiento), 0) AS entrada 
		  		FROM 
		  			entradas_salidas AS a 
		  			JOIN entradas AS b ON
		  			b.tipo_documento = a.tipo_documento
		  			AND b.id = a.id_documento 
		  			JOIN almacen AS c ON
		  			c.codigo = a.almacen AND c.movimiento = 'S'
		  		WHERE
		  			a.tipo_documento IN ('TDCPDC') 
		  			AND b.estatus = 'NUEVO' AND a.articulo = '$articulo';"; 
		$transito = floatval(ExecuteScalar($sql));
		$sql = "SELECT 
                    SUM(x.cantidad_movimiento) AS cantidad 
                FROM 
                    (
                        SELECT 
                            a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                            a.fecha_vencimiento, 
                            a.cantidad_movimiento 
                        FROM 
                            entradas_salidas AS a 
                            JOIN entradas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                            JOIN almacen AS c ON
                                c.codigo = a.almacen AND c.movimiento = 'S'
                        WHERE 
                            (
                                (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                                (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                            ) AND a.articulo = $articulo AND a.newdata = 'S' 
                        UNION ALL SELECT 
                            a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(a.fecha_vencimiento, '%d/%m/%Y') AS fecha, 
                            a.fecha_vencimiento, 
                            a.cantidad_movimiento  
                        FROM 
                            entradas_salidas AS a 
                            JOIN salidas AS b ON
                                b.tipo_documento = a.tipo_documento
                                AND b.id = a.id_documento 
                            JOIN almacen AS c ON
                                c.codigo = a.almacen AND c.movimiento = 'S'
                        WHERE 
                            (
                                (a.tipo_documento IN ('TDCPDV') AND b.estatus = 'NUEVO') OR 
                                (a.tipo_documento IN ('$tipo_documento', 'TDCASA') AND b.estatus <> 'ANULADO') 
                            ) AND a.articulo = $articulo AND a.newdata = 'S' 
                    ) AS x 
                WHERE 1;"; 
		$cantidad_en_mano = floatval(ExecuteScalar($sql));
        $cantidad_en_mano = ($cantidad_en_mano < 0 ? 0 : $cantidad_en_mano);
		$sql = "UPDATE articulo
				SET
					cantidad_en_mano = IFNULL($cantidad_en_mano, 0),
					cantidad_en_pedido = IFNULL(ABS($pedido), 0),
					cantidad_en_transito = IFNULL(ABS($transito), 0) 
				WHERE id = '$articulo'"; 
		ExecuteScalar($sql);
	}
    $sql = "UPDATE 
                articulo AS a 
                JOIN (SELECT 
                        a.articulo, a.precio 
                    FROM 
                        tarifa_articulo AS a 
                        JOIN tarifa AS b ON b.id = a.tarifa 
                    WHERE b.patron = 'S') AS b ON b.articulo = a.id 
                SET 
                    a.precio = b.precio;";
    Execute($sql);
}

function FiltraClientes() {
	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels
			WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
	$grupo = trim(ExecuteScalar($sql));
	if($grupo == "CLIENTE") {
		$sql = "SELECT asesor, cliente FROM usuario
				WHERE username = '" . CurrentUserName() . "';";
		$row = ExecuteRow($sql);
		$asesor = intval($row["asesor"]);
		$cliente = intval($row["cliente"]);
		if($asesor > 0) {
			$sql = "SELECT COUNT(cliente) AS cantidad FROM asesor_cliente
					WHERE asesor = '$asesor';";
			$cantidad = ExecuteScalar($sql);
			$clientes = "";
			for($i=0; $i<$cantidad; $i++) {
				$sql = "SELECT cliente FROM asesor_cliente
					WHERE asesor = '$asesor' LIMIT $i, 1;";
				$clientes .= ExecuteScalar($sql) . ",";
			}
			$clientes .= "0"; 
			return "id IN ($clientes)";
		}
		else if($cliente > 0) {
			return "id = '$cliente'";
		}
	}
	else {
		return "";
	}
}

function VerificaFuncion($xFunc) {
	$sql = "SELECT 
				a.funcion 
			FROM 
				grupo_funciones AS a 
				JOIN funciones AS b ON b.id = a.funcion  
			WHERE
				a.grupo = '" . CurrentUserLevel() . "' AND  
				b.codigo = '$xFunc';"; 
	if($row = ExecuteRow($sql)) 
		return true;
	else 
		return false;
}

/*
function ActualizarUnidadesSalidas($id_documento, $tipo_documento) {
	// Se actualizan las cantidades de unidades en el encabezado de la salida 21-01-2021 
	if($tipo_documento=="TDCPDV" or $tipo_documento=="TDCNET" or $tipo_documento=="TDCFCV" or $tipo_documento=="TDCASA") {
		$sql = "SELECT 
					cantidad_articulo, cantidad_movimiento 
				FROM 
					entradas_salidas 
				WHERE
					id_documento = $id_documento
					AND tipo_documento = '$tipo_documento' 
					AND cantidad_movimiento IS NULL;";
		if($row = ExecuteRow($sql)) {
			$sql = "UPDATE entradas_salidas
						SET cantidad_movimiento = (-1)*cantidad_articulo 
					WHERE
						id_documento = $id_documento
						AND tipo_documento = '$tipo_documento' 
						AND cantidad_movimiento IS NULL;";
			Execute($sql);
		}
		$sql = "UPDATE 
					salidas AS a 
					JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
				SET 
					a.unidades = b.cantidad 
				WHERE a.id = $id_documento
					AND a.tipo_documento = '$tipo_documento';";
		Execute($sql);
	}
}
*/
function ActualizarTotalFacturaVenta($id_documento, $tipo_documento) {
	/* Se actualizan el total del monto con o sin descuento en la factura de venta 01-02-2021 */
	if($tipo_documento=="TDCFCV") {
		$sql = "SELECT 
			COUNT(DISTINCT alicuota ) AS cantidad  
		FROM 
			entradas_salidas
		WHERE 
			tipo_documento = '$tipo_documento' 
			AND id_documento = $id_documento;";
		if(ExecuteScalar($sql) > 1) $alicuota = 0;
		else {
			$sql = "SELECT 
				DISTINCT alicuota 
			FROM 
				entradas_salidas
			WHERE 
				tipo_documento = '$tipo_documento' 
				AND id_documento = '$id_documento';";
			$alicuota = floatval(ExecuteScalar($sql));
		}
		$sql = "SELECT descuento, tasa_dia FROM salidas WHERE tipo_documento = '$tipo_documento' AND id = $id_documento;";
		$row = ExecuteRow($sql);
		$descuento = floatval($row["descuento"]);
		$tasa = floatval($row["tasa_dia"]);
		$sql = "SELECT
					SUM(precio) AS precio, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) AS exento, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) AS gravado, 
					SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100)) AS iva, 
					SUM(IF(IFNULL(alicuota,0)=0, precio - (precio * ($descuento/100)), 0)) + SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100)))) + (SUM(IF(IFNULL(alicuota,0)=0, 0, precio - (precio * ($descuento/100))) * (IFNULL(alicuota,0)/100))) AS total 
				FROM entradas_salidas
				WHERE tipo_documento = '$tipo_documento' AND 
					id_documento = '$id_documento'"; 
		$row = ExecuteRow($sql);
		$monto_sin_descuento = floatval($row["precio"]);
		$precio = floatval($row["exento"]) + floatval($row["gravado"]);
		$iva = floatval($row["iva"]);
		$total = floatval($row["total"]);
		if($tasa == 0) $xtasa = 1;
		else $xtasa = $tasa;

		/*$sql = "SELECT
				SUM(precio) AS precio, 
				SUM((precio * (alicuota/100))) AS iva, 
				SUM(precio) + SUM((precio * (alicuota/100))) AS total
			FROM 
				entradas_salidas
			WHERE tipo_documento = '$tipo_documento' AND 
				id_documento = $id_documento";*/
		$sql = "UPDATE salidas 
			SET
				monto_total = $precio, 
				alicuota_iva = $alicuota, 
				iva = $iva,
				total = $total,
				tasa_dia = $tasa, 
				monto_usd = total/$xtasa,
				monto_sin_descuento = $monto_sin_descuento 
			WHERE tipo_documento = '$tipo_documento' AND 
				id = $id_documento";
		Execute($sql);
	}
}
function FiltraFabricantes() {
	$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels
			WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
	$grupo = trim(ExecuteScalar($sql));
	//if($grupo == "CLIENTE") {
	if(true) {
		$sql = "SELECT asesor FROM usuario
				WHERE username = '" . CurrentUserName() . "';"; 
		if($row = ExecuteRow($sql)) {
			$asesor = intval($row["asesor"]);
			if($asesor > 0) {
				$fabricantes = "";
				$sql = "SELECT fabricante FROM asesor_fabricante WHERE asesor = '$asesor';"; 
				$rows = ExecuteRows($sql);
				foreach ($rows as $key => $value) {
					$fabricantes .= $value["fabricante"] . ",";
				}
				if($fabricantes == "") return "";
				$fabricantes .= "0"; 
				return "id IN ($fabricantes)";
			}
			else return "";
		}
		else return "";
	}
	else return "";
}
function CuadraComprobante($comprobante) {
	/*
	if(isset($_REQUEST["fk_id"]))
		$comprobante = intval($_REQUEST["fk_id"]);
	elseif(isset($_REQUEST["id"]))
		$comprobante = intval($_REQUEST["id"]);
	else
		$comprobante = intval(CurrentTable()->id->CurrentValue);
	*/
	$sql = "SELECT SUM(debe) AS debe, SUM(haber) AS haber 
			FROM cont_asiento 
			WHERE comprobante = " . $comprobante; 
	$row = ExecuteRow($sql);
	$debe = $row["debe"];
	$haber = $row["haber"];
	if($debe == $haber and $debe != 0 and $haber != 0) return TRUE;
	else return FALSE;
}

function CalcularRetenciones($id_documento, $tipo_documento) {
	$sql = "SELECT agente_retencion FROM compania WHERE id = 1;";
	$aplica_retencion = ExecuteScalar($sql);
	$sql = "SELECT
				proveedor, alicuota_iva, iva, total,
				IFNULL(aplica_retencion, '$aplica_retencion') AS aplica_retencion 
			FROM entradas
			WHERE tipo_documento = '$tipo_documento' AND id = $id_documento;";
	$row = ExecuteRow($sql);
	$proveedor = $row["proveedor"];
	$alicuota = floatval($row["alicuota_iva"]);
	$monto_iva = floatval($row["iva"]);
	$monto_total = floatval($row["total"]);
	$monto_pagar = 0.00;
	$aplica_retencion = $row["aplica_retencion"];
	$sql = "SELECT
				SUM(costo) AS precio, 
				SUM(IF(IFNULL(alicuota,0)=0, costo, 0)) AS exento, 
				SUM(IF(IFNULL(alicuota,0)=0, 0, costo)) AS gravado 
			FROM entradas_salidas
			WHERE
				tipo_documento = '$tipo_documento' AND 
				id_documento = '$id_documento';";
	$row = ExecuteRow($sql);
	$monto_exento = floatval($row["exento"]);
	$monto_gravado = floatval($row["gravado"]);
	if($aplica_retencion == "S") {
		$sql = "SELECT ci_rif AS rif, tipo_iva, tipo_islr, sustraendo, tipo_impmun FROM proveedor WHERE id = $proveedor;";
		$row = ExecuteRow($sql);
		$retIVA = floatval($row["tipo_iva"]);
		$retISLR = floatval($row["tipo_islr"]);
		$sustraendo = floatval($row["sustraendo"]);
		$retMuni = floatval($row["tipo_impmun"]);
		$rif = trim($row["rif"]);
		$MretIVA = $monto_iva * ($retIVA/100);
		$MretSLR = (($monto_gravado) * ($retISLR/100)) - $sustraendo;
		$MretMUNI = $monto_gravado * ($retMuni/100);
		if($MretSLR < 0) $MretSLR = 0;
		if($MretMUNI < 0) $MretMUNI = 0;
		$monto_pagar = $monto_total - ($MretIVA+$MretSLR+$MretMUNI);
	}
	else {
		$MretIVA = 0;
		$MretSLR = 0;
		$MretMUNI = 0;
		$retIVA = 0;
		$retISLR = 0;
		$sustraendo = 0;
		$retMuni = 0;
		$monto_pagar = $monto_total;
	}
	$sql = "UPDATE entradas 
			SET
				ret_iva=$MretIVA, ret_islr=$MretSLR, monto_pagar=$monto_pagar,
				tipo_iva = '$retIVA', tipo_islr = '$retISLR',
				sustraendo = $sustraendo,
				ret_municipal=$MretMUNI, tipo_municipal='$retMuni' 
			WHERE
				tipo_documento = '$tipo_documento' AND 
				id = '$id_documento';";
	Execute($sql);
}

/**** Clase para crear comprobante contable desde PhpMaker ****/
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
		if($this->VerificaPeriodoContable($fecha, $tipo_documento)) {
			$sql = "SELECT COUNT(id) AS cantidad 
					FROM salidas 
					WHERE tipo_documento = '$tipo_documento' 
						AND estatus = 'PROCESADO' 
						AND fecha = '$fecha' AND comprobante IS NULL;"; 
			$cantidad = intval(ExecuteScalar($sql)); 
			if($cantidad > 0) {
				// Se crea el comprobante
				$sql = "INSERT INTO cont_comprobante
							(id, tipo, fecha, contabilizacion, 
							descripcion, registra, fecha_registro, contabiliza, fecha_contabiliza)
						VALUES 
							(NULL, '$tipo_documento', '$fecha', NULL, 
							'$nota', '" . $this->username . "', '" . date("Y-m-d H:i:s") . "', NULL, NULL)";
				Execute($sql);
				$sql = "SELECT LAST_INSERT_ID() AS id;"; 
				$this->NroComprobante = ExecuteScalar($sql);
			} else $this->NoExite=true;
		} 
		else $this->NoExite=true;
	}	
	function Comprobante($id) {
		switch($this->regla) {
		case 3:
			break;
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
						a.id = $id;"; 
			$row = ExecuteRow($sql);
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
			Execute($sql);
			break;	
		}
	}
	function Asiento($comprobante, $aplica_retencion, $auxiliar, $montos, $ref, $xreferencia) {
		$arr = ["100", "200", "300", "400", "500", "600", "700", "800", "900"];
		foreach ($arr as $key => $value) { 
			$sql = "SELECT cuenta, cargo FROM cont_reglas WHERE regla = " . $this->regla . " AND codigo = '$value';"; 
			if($row = ExecuteRow($sql)) {
				$cuenta = $row["cuenta"];
				$cargo = $row["cargo"];
				$debe = 0;
				$haber = 0;
				switch($value) {
				case "100": // Compra y Ventas
					switch ($this->regla) {
					case 1:
						$sql = "SELECT IFNULL(cuenta_gasto, 0) AS cuenta FROM proveedor WHERE id = $auxiliar;"; 
						if($row = ExecuteRow($sql)) {
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
					if($row = ExecuteRow($sql)) {
						if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
					}
					if($cargo == "DEBE") $debe = $montos["monto_pagar"];
					if($cargo == "HABER") $haber = $montos["monto_pagar"];
					break;
				case "600": // Caja y Banco
					$sql = "SELECT cuenta FROM compania_cuenta WHERE id = " . $montos["banco"] . ";"; 
					if($row = ExecuteRow($sql)) {
						if($row["cuenta"] > 0) $cuenta = $row["cuenta"];
					}
					if($cargo == "DEBE") $debe = $montos["monto_pagar"];
					if($cargo == "HABER") $haber = $montos["monto_pagar"];
					break;
				case "700":  // Cuentas por Cobrar
					$sql = "SELECT cuenta FROM cliente WHERE id = $auxiliar;"; 
					if($row = ExecuteRow($sql)) {
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
					Execute($sql);			
				}
			}
		}
	}
	function VerificaPeriodoContable($fecha, $tipo) {
		$sql = "SELECT 
					cerrado 
				FROM 
					cont_periodo_contable 
				WHERE 
					'$fecha' BETWEEN fecha_inicio AND fecha_fin;"; 
		if(!$row = ExecuteRow($sql)) {
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
		if($row = ExecuteRow($sql)) {
			//$this->CancelMessage = "El mes contable est&aacute; cerrado para el tipo de comprobante; verifique.";
			return FALSE;
		}
		return TRUE;
	}
}

//// Clases para agregar artículos a pedido de venta ////
class PdvLineaGuardar {
  var $tipo_documento;
  var $salida;
  var $cliente;
  var $cod_articulo;
  var $lista_pedido;
  var $articulo;
  var $cantidad;
  var $unidad_medida;
  var $resultado;
  var $fabricante;
  var $total; 
  var $cantidad_movimiento;
  var $tarifa;
  var $descuento;
  var $cantidad_en_mano;
  var $cantidad_unidad;
  var $articulo_inventario;
  var $alicuota;
  var $almacen;
  var $precio;
  var $precio_ful;
  var $link;
  function __construct()
  {
  }
  function pedido_abierto()
  {
    $sql = "SELECT estatus FROM salidas WHERE tipo_documento = '" . $this->tipo_documento . "' AND id = '" . $this->salida . "';"; 
    $status_doc = ExecuteScalar($sql);
    if($status_doc == "NUEVO") 
      return true;
    else 
      return false;
  }
  function datos_articulo($x_tarifa_default)
  {
  	if($x_tarifa_default == 0) {
    	$sql = "SELECT 
              b.tarifa 
            FROM 
              salidas AS a 
              LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
            WHERE 
              a.id = " . $this->salida . ";";
        $this->tarifa = ExecuteScalar($sql);
    }
    else $this->tarifa = $x_tarifa_default;
    $sql = "SELECT 
              IFNULL(descuento, 0) AS descuento, 
              (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano, 
              unidad_medida_defecto AS unidad_medida, cantidad_por_unidad_medida, articulo_inventario, id, fabricante 
            FROM 
              articulo WHERE codigo = '" . $this->cod_articulo . "' AND lista_pedido = '" . $this->lista_pedido . "';"; 
    if($row = ExecuteRow($sql)) 
    {
      $this->descuento = floatval($row["descuento"]);
      $this->cantidad_en_mano = floatval($row["cantidad_en_mano"]);
      $this->cantidad_unidad = floatval($row["cantidad_por_unidad_medida"]);
      $this->articulo_inventario = $row["articulo_inventario"];
      $this->unidad_medida = $row["unidad_medida"];
      $this->articulo = $row["id"];
      $this->fabricante = $row["fabricante"];
      $sql = "SELECT alicuota FROM articulo WHERE id = '" . $this->articulo . "';"; 
      $codigo_alicuota = ExecuteScalar($sql);
      $sql = "SELECT alicuota FROM alicuota
          WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
      $this->alicuota = floatval(ExecuteScalar($sql));
      $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
      $this->almacen = ExecuteScalar($sql);
      $this->cantidad = $this->cantidad_unidad * $this->cantidad;
      if($this->articulo_inventario == "S") {
        if($this->cantidad > 0) {
          $sql = "SELECT 
                      SUM(a.cantidad_movimiento) AS pedidos_nuevos 
                    FROM 
                      entradas_salidas AS a 
                      JOIN salidas AS b ON
                        b.tipo_documento = a.tipo_documento
                      AND b.id = a.id_documento 
                      JOIN almacen AS c ON
                        c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo = '" . $this->almacen . "' 
                    WHERE
                      a.tipo_documento IN ('TDCPDV')
                      AND a.articulo = " . $this->articulo . " AND b.estatus = 'NUEVO';";
          $pedidos_nuevos = floatval(ExecuteScalar($sql));
          if((($this->cantidad_en_mano - $this->cantidad) + $pedidos_nuevos) < 0) $this->resultado = 0; 
          else $this->resultado = 1;
        }
      } 
      else $this->resultado = 1;
    }
    else $this->resultado = 0;
  }
  function precio_articulo() 
  {
    $sql = "SELECT
              precio AS precio_ful,
              (precio - (precio * ($this->descuento/100))) AS precio 
            FROM tarifa_articulo
            WHERE tarifa = $this->tarifa AND articulo = '" . $this->articulo . "';";
    $row = ExecuteRow($sql);
    $this->precio = floatval($row["precio"]);
    $this->precio_ful = floatval($row["precio_ful"]);
    $this->total = $this->precio * $this->cantidad;
    $this->cantidad_movimiento = $this->cantidad * (-1);
  }
  function insertar_articulo($tipo_documento, $salida, $cliente, $cod_articulo, $lista_pedido, $cantidad, $tarifa_default) 
  { 
    $tipo_documento="TDCPDV";
    $this->tipo_documento = $tipo_documento;
    $this->salida = $salida;
    $this->cliente = $cliente;
    $this->cod_articulo = $cod_articulo;
    $this->lista_pedido = $lista_pedido;
    $this->cantidad = $cantidad;
    $this->resultado = 1;
    if($this->pedido_abierto()) 
    {
      $this->datos_articulo($tarifa_default);
      if($this->resultado == 0) return false;
    } 
    else return false;
    $this->precio_articulo(); 
    $sql = "INSERT INTO entradas_salidas
          (id, tipo_documento, id_documento, 
          fabricante, articulo, almacen, 
          cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
          precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc)
        VALUES 
          (NULL, '$this->tipo_documento', $this->salida, 
          $this->fabricante, $this->articulo, '$this->almacen', 
          $this->cantidad, '$this->unidad_medida', $this->cantidad_unidad, $this->cantidad_movimiento, 
          $this->precio, $this->total, $this->alicuota, $this->descuento, $this->precio_ful);
        ";
    Execute($sql);

    // ActualizarExitenciaArticulo($this->articulo);
    return true;
  }
  function ActualizarCabecera() {
    $sql = "SELECT COUNT(DISTINCT alicuota ) AS cantidad FROM entradas_salidas
            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
    if(intval(ExecuteScalar($sql)) > 1) $alicuota = 0;
    else {
      $sql = "SELECT DISTINCT alicuota FROM entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
      $alicuota = floatval(ExecuteScalar($sql));
    }

    // Se actualiza el encabezado del pedido de venta //
    $sql = "SELECT
              SUM(precio) AS precio, 
              SUM((precio * (alicuota/100))) AS iva, 
              SUM(precio) + SUM((precio * (alicuota/100))) AS total 
            FROM 
              entradas_salidas
            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida'";
    $row = ExecuteRow($sql);
    $precio = floatval($row["precio"]);
    $iva = floatval($row["iva"]);
    $total = floatval($row["total"]);
    $sql = "UPDATE salidas 
            SET
              monto_total = $precio,
              alicuota_iva = $alicuota, 
              iva = $iva,
              total = $total
            WHERE tipo_documento = '$this->tipo_documento' AND id = '$this->salida'";
    Execute($sql);

    /* Se actualizan las cantidades de unidades en el encabezado de la salida */
    $sql = "UPDATE 
              salidas AS a 
              JOIN (SELECT id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad FROM entradas_salidas GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
            SET 
              a.unidades = b.cantidad 
            WHERE a.id = $this->salida;";
    Execute($sql);
  }
} 
function pagosCobranzaInfo($id, $tipo_documento, $nc) {
    $sql = "SELECT total, moneda, IF(IFNULL(tasa_dia, 0) = 0, 1, tasa_dia) AS tasa_dia, nro_documento FROM salidas WHERE id = $id;";
    $row = ExecuteRow($sql);
    $monto_factura = $row["total"];
    $moneda_factura = $row["moneda"];
    $tasa = floatval($row["tasa_dia"]);
    $nro_documento = $row["nro_documento"];
    $header = '<div class="col-9"><div class="table-responsive-sm  table-hover">
                <table class="table table-sm">
                  <thead>
                    <tr class="table-active">
                      <th scope="col">Tipo</th>
                      <th scope="col">Fecha</th>
                      <th scope="col">Banco</th>
                      <th scope="col">Ref.</th>
                      <th scope="col" class="text-right">Monto$</th>
                      <th scope="col" class="text-right">MontoBs.</th>
                      <th scope="col" class="text-right">Tasa</th>
                      <th scope="col" class="text-right">Equiv. $</th>
                      <th scope="col" class="text-right">Equiv. Bs.</th>
                    </tr>
                  </thead>
                  <tbody>';
    $sql = "SELECT 
                a.tipo_pago, 
                (SELECT valor2 FROM parametro WHERE codigo = '009' AND valor1 = a.tipo_pago) AS tipo, 
                date_format(a.fecha, '%d/%m/%Y') AS fecha, 
                a.banco, 
                a.referencia, 
                IF(a.moneda = 'USD', a.monto, 0) AS monto_usd, 
                IF(a.moneda = 'USD', 0, a.monto) AS monto_bss, 
                a.moneda  
            FROM 
                pagos AS a 
            WHERE 
                a.id_documento = $id AND a.tipo_documento = '$tipo_documento'
            ORDER BY a.tipo_pago"; 
    $rows = ExecuteRows($sql);
    $monto_usd = 0;
    $monto_bss = 0;
    $moneda = ""; 
    $mont_usd = 0;
    $mont_bss = 0;
    $mont_usd_igtf = 0;
    $mont_bss_igtf = 0;
    $tot_usd = 0;
    $tot_bss = 0;
    $tot_usd_igtf = 0;
    $tot_bss_igtf = 0;
    foreach ($rows as $key => $value) {
        $monto_usd = floatval($value["monto_usd"]);
        $monto_bss = floatval($value["monto_bss"]);
        $moneda = $value["moneda"];
        if($value["tipo_pago"] == "IG") { 
            $mont_usd_igtf += ($moneda == 'USD' ? $monto_usd : 0);
            $mont_bss_igtf += ($moneda == 'USD' ? 0 : $monto_bss);
            $tot_usd_igtf += ($moneda == 'USD' ? $monto_usd : $monto_bss / $tasa);
            $tot_bss_igtf += ($moneda == 'USD' ? $monto_usd * $tasa : $monto_bss);
        } 
        else {
            $mont_usd += ($moneda == 'USD' ? $monto_usd : 0);
            $mont_bss += ($moneda == 'USD' ? 0 : $monto_bss);
            $tot_usd += ($moneda == 'USD' ? $monto_usd : $monto_bss / $tasa);
            $tot_bss += ($moneda == 'USD' ? $monto_usd * $tasa : $monto_bss);
        }
        $header .= '<tr>';
          $header .= '<th scope="row">' . $value["tipo"] . '</th>';
          $header .= '<td>' . $value["fecha"] . '</td>';
          $header .= '<td>' . $value["banco"] . '</td>';
          $header .= '<td>' . $value["referencia"] . '</td>';
          $header .= '<td align="right">' . number_format($monto_usd, 2, ".", ",") . '</td>';
          $header .= '<td align="right">' . number_format($monto_bss, 2, ".", ",") . '</td>';
          $header .= '<td align="right">' . number_format($tasa, 2, ".", ",") . '</td>';
          $header .= '<td align="right">' . number_format(($moneda == 'USD' ? $monto_usd : $monto_bss / $tasa), 2, ".", ",") . '</td>';
          $header .= '<td align="right">' . number_format(($moneda == 'USD' ? $monto_usd * $tasa : $monto_bss), 2, ".", ",") . '</td>';
       $header .= ' </tr>';
    }
    $header .= '<tr class="table-success">';
      $header .= '<th scope="row" colspan="4" class="text-right">Total Cancelado Sin IGTF</th>';
      $header .= '<td align="right">' . number_format($mont_usd, 2, ".", ",") . '</td>';
      $header .= '<td align="right">' . number_format($mont_bss, 2, ".", ",") . '</td>';
      $header .= '<td align="right"> -- </td>';
      $header .= '<td align="right">' . number_format($tot_usd, 2, ".", ",") . '</td>';
      $header .= '<td align="right">' . number_format($tot_bss, 2, ".", ",") . '</td>';
    $header .= ' </tr>';
    $header .= '<tr class="table-primary">';
      $header .= '<th scope="row" colspan="4" class="text-right">Total IGTF</th>';
      $header .= '<td align="right">' . number_format($mont_usd_igtf, 2, ".", ",") . '</td>';
      $header .= '<td align="right">' . number_format($mont_bss_igtf, 2, ".", ",") . '</td>';
      $header .= '<td align="right"> -- </td>';
      $header .= '<td align="right">' . number_format($tot_usd_igtf, 2, ".", ",") . '</td>';
      $header .= '<td align="right">' . number_format($tot_bss_igtf, 2, ".", ",") . '</td>';
    $header .= ' </tr>';
    $header .= '</tbody>
            </table>
           </div></div>';
    $header .= '<div class="col-6"><table class="table table-sm">
                  <tbody>
                    <tr>
                      <th scope="row" class="text-right">Monto Factura Bs</th>
                      <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura*$tasa : $monto_factura), 2, ".", ",") . '</td>
                      <th class="text-right">Monto Pendiente Bs</th>
                      <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura*$tasa : $monto_factura)-$tot_bss, 2, ".", ",") . '</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-right">Monto Factura $ Sin IGTF</th>
                      <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura : $monto_factura/$tasa), 2, ".", ",") . '</td>
                      <th class="text-right">Monto Pendiente $</th>
                      <td align="right">' . number_format(($moneda_factura == "USD" ? $monto_factura : $monto_factura/$tasa)-$tot_usd, 2, ".", ",") . '</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-right">IGTF Bs</th>
                      <td align="right">' . number_format(($mont_usd*$tasa)*(3/100), 2, ".", ",") . '</td>
                      <th class="text-right">Monto Pendiente IGTF Bs</th>
                      <td align="right">' . number_format((($mont_usd*$tasa)*(3/100))-$tot_bss_igtf, 2, ".", ",") . '</td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-right">IGTF $</th>
                      <td align="right">' . number_format($mont_usd*(3/100), 2, ".", ",") . '</td>
                      <th class="text-right">Monto Pendiente IGTF $</th>
                      <td align="right">' . number_format(($mont_usd*(3/100))-$tot_usd_igtf, 2, ".", ",") . '</td>
                    </tr>
                  </tbody>
                </table></div>';

////////////////////////////////////////
	if($nc == "S") {
       $sql = "SELECT 
       				IF(TIMESTAMPDIFF(DAY, b.fecha_entrega, CURDATE()) > IFNULL(a.dias_credito, 0), 
       				TIMESTAMPDIFF(DAY, b.fecha_entrega, CURDATE()) - a.dias_credito, 
       				0) AS dias_vencidos 
       			FROM 
       				salidas AS a 
       				LEFT OUTER JOIN salidas AS b ON b.id = a.id_documento_padre  
       			WHERE a.id = '$id' AND a.tipo_documento = '$tipo_documento';";
       if(intval(ExecuteScalar($sql)) > 0) {
   		$sql = "SELECT nro_documento FROM salidas WHERE id_documento_padre_nd = '$id';";
   		if($row = ExecuteRow($sql)) {
       		$header .= '<div class="col-6"><table class="table table-sm">
       						<tbody>
       							<tr>
       								<th scope="row" class="text-right">Nota de D&eacute;dito: ' . $row["nro_documento"] . '</th>
       							</tr>
       						</tbody>
       					</table></div>';
       	}
       	else {
       		$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
       		$tasa_indexada = floatval(ExecuteScalar($sql));
       		$sql = "UPDATE salidas SET tasa_indexada = $tasa WHERE id = '$id' AND tipo_documento = '$tipo_documento'";
       		Execute($sql);
       		$header .= '<div class="col-6"><table class="table table-sm">
       						<tbody>
       							<tr>
       								<th scope="row" class="text-right">Tasa USD para N/D</th>
       								<td align="right"><input class="form-control" type="text" id="tasa_indexada" value="' . $tasa_indexada . '"></td>
       								<th class="text-right">Sobre Monto Pendiente $</th>
       								<td align="right"><input class="form-control" type="text" id="monto_pendiente" value="' . number_format(($moneda_factura == "USD" ? $monto_factura : $monto_factura/$tasa)-$tot_usd, 2, ".", ",") . '" readonly="yes"></td>
       							</tr>
       							<tr>
       								<th class="text-center" colspan="4"><a class="btn btn-primary" id="CrearND">Crear Nota de D&eacute;bito</a></th>
       							</tr>
       						</tbody>
       					</table></div>';
       	}
       }
   }
////////////////////////////////////
    $sql = "SELECT
          nro_documento, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha,
          total, moneda, tasa_dia, monto_usd
        FROM
          salidas
        WHERE
          tipo_documento = 'TDCFCV' AND documento = 'NC'
          AND doc_afectado = '$nro_documento' ";
    if($row = ExecuteRow($sql)) {
      $header .= '<div class="alert alert-warning">
              <strong>N.C.: ' .$row["nro_documento"] . ' | Fecha: ' . $row["fecha"] . '</strong> | Monto Bs.: ' . number_format($row["total"], 2, ",", ".") . ' | Monto USD.: ' . number_format($row["monto_usd"], 2, ",", ".") . '
            </div>';
    }
    if($nc == "S") {
    	$header .= '<script>
       				$("#CrearND").click(function() {
    					if(confirm("Seguro de crear la N.C.?")) {
       						var id = ' . $id . ';
       						var username = "' . CurrentUserName() . '";
       						var tasa = $("#tasa_indexada").val();
       						var monto = $("#monto_pendiente").val()
       						var url = "include/crear_nota_debito.php?id=" + id + "&tasa=" + tasa + "&monto=" + monto + "&username=" + username;
       						window.location.href = url; 
       					}
       				});
       				</script>';
    }
    return $header;
}

// Clase para agregar Pedido de Compras o Ajustes de Entrada
class PdcLineaGuardar {
  var $tipo_documento;
  var $entrada;
  var $proveedor;
  var $cod_articulo;
  var $articulo;
  var $cantidad;
  var $descuento;
  var $cantidad_en_mano;
  var $cantidad_unidad;
  var $articulo_inventario;
  var $alicuota;
  var $almacen;
  var $costo;
  var $costo_full;
  var $link;
  var $existe_articulo;
  var $lote;
  var $fecha_vencimiento;
  function __construct()
  {
  }
  function pedido_abierto()
  {
    $sql = "SELECT estatus FROM entradas WHERE tipo_documento = '" . $this->tipo_documento . "' AND id = '" . $this->entrada . "';"; 
    $status_doc = ExecuteScalar($sql);
    if($status_doc == "NUEVO") 
      return true;
    else 
      return false;
  }
  function datos_articulo()
  {
  	$this->existe_articulo = false;
    $sql = "SELECT 
              IFNULL(descuento, 0) AS descuento, 
              (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano, 
              unidad_medida_defecto AS unidad_medida, cantidad_por_unidad_medida, articulo_inventario, id, fabricante, ultimo_costo  
            FROM 
              articulo WHERE codigo = '" . $this->cod_articulo . "';"; 
    if($row = ExecuteRow($sql)) 
    {
      $this->descuento = floatval($row["descuento"]);
      $this->cantidad_en_mano = floatval($row["cantidad_en_mano"]);
      $this->cantidad_unidad = floatval($row["cantidad_por_unidad_medida"]);
      $this->articulo_inventario = $row["articulo_inventario"];
      $this->unidad_medida = $row["unidad_medida"];
      $this->articulo = $row["id"];
      $this->fabricante = $row["fabricante"];
      $sql = "SELECT alicuota FROM articulo WHERE id = '" . $this->articulo . "';"; 
      $codigo_alicuota = ExecuteScalar($sql);
      $sql = "SELECT alicuota FROM alicuota
          WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
      $this->alicuota = floatval(ExecuteScalar($sql));
      $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
      $this->almacen = ExecuteScalar($sql);
      $this->cantidad = $this->cantidad_unidad * $this->cantidad;
      $this->costo = $row["ultimo_costo"];
      $this->total = $this->costo * $this->cantidad;
      $this->cantidad_movimiento = $this->cantidad; // * (-1);
      $this->costo_full = $this->costo/(1-($this->descuento/100));
      $this->existe_articulo = true;
    }
  }
  // function insertar_articulo($tipo_documento="TDCPDC", $entrada, $proveedor, $cod_articulo, $cantidad)
  function insertar_articulo($tipo_documento, $entrada, $proveedor, $cod_articulo, $cantidad, $lote, $fecha_vencimiento) 
  { 
    $this->tipo_documento = $tipo_documento;
    $this->entrada = $entrada;
    $this->proveedor = $proveedor;
    $this->cod_articulo = $cod_articulo;
    $this->cantidad = $cantidad;
    $this->lote = $lote;
    $this->fecha_vencimiento = $fecha_vencimiento;
    if($this->pedido_abierto()) 
    {
      $this->datos_articulo();
    } 
    else return false;
    $this->costo_full = round($this->costo_full, 2);
    if($this->existe_articulo) {
      $sql = "INSERT INTO entradas_salidas
            (id, tipo_documento, id_documento, 
            fabricante, articulo, almacen, 
            cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
            costo_unidad, costo, alicuota, descuento, precio_unidad_sin_desc, lote, fecha_vencimiento)
          VALUES 
            (NULL, '$this->tipo_documento', $this->entrada, 
            $this->fabricante, $this->articulo, '$this->almacen', 
            $this->cantidad, '$this->unidad_medida', $this->cantidad_unidad, $this->cantidad_movimiento, 
            $this->costo, $this->total, $this->alicuota, $this->descuento, $this->costo_full, 
            '$this->lote', '$this->fecha_vencimiento');
          ";
      Execute($sql);
      $sql = "SELECT COUNT(DISTINCT alicuota ) AS cantidad FROM entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->entrada';";
      if(intval(ExecuteScalar($sql)) > 1) $alicuota = 0;
      else {
        $sql = "SELECT DISTINCT alicuota FROM entradas_salidas
                WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->entrada';";
        $alicuota = floatval(ExecuteScalar($sql));
      }

      // Se actualiza el encabezado del pedido de venta //
      $sql = "SELECT
                SUM(costo) AS costo, 
                SUM((costo * (alicuota/100))) AS iva, 
                SUM(costo) + SUM((costo * (alicuota/100))) AS total 
              FROM 
                entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->entrada'";
      $row = ExecuteRow($sql);
      $costo = floatval($row["costo"]);
      $iva = floatval($row["iva"]);
      $total = floatval($row["total"]);
      $sql = "UPDATE entradas 
              SET
                monto_total = $costo,
                alicuota_iva = $alicuota, 
                iva = $iva,
                total = $total
              WHERE tipo_documento = '$this->tipo_documento' AND id = '$this->entrada'";
      Execute($sql);
        $sql = "UPDATE 
                    entradas AS a 
                    JOIN (SELECT 
                                id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                            FROM 
                                entradas_salidas 
                            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = $this->entrada 
                            GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
                SET 
                    a.unidades = b.cantidad 
                WHERE a.id = $this->entrada;";
        Execute($sql);
    }
    return true;
  }
}

//// ---- ////
/**** Fin ****/

//// Clases para agregar artículos a factura de venta ////
class FctLineaGuardar {
  var $tipo_documento;
  var $salida;
  var $cod_articulo;
  var $articulo;
  var $cantidad;
  var $precio;
  var $costo;
  var $descuento;
  var $cantidad_unidad;
  var $alicuota;
  var $almacen;
  var $cantidad_movimiento;
  var $precio_ful;
  var $total;
  var $total_costo;
  var $link;
  var $cliente;
  var $ArrNotas;
  function __construct($cliente)
  {
  	$this->cliente = $cliente;
  }
  function datos_articulo()
  {
    $sql = "SELECT 
              IFNULL(descuento, 0) AS descuento, 
              (IFNULL(cantidad_en_mano, 0)+IFNULL(cantidad_en_pedido, 0))-IFNULL(cantidad_en_transito, 0) AS cantidad_en_mano, 
              unidad_medida_defecto AS unidad_medida, cantidad_por_unidad_medida, articulo_inventario, id, fabricante,
              ultimo_costo AS costo 
            FROM 
              articulo WHERE codigo = '" . $this->cod_articulo . "';"; 
    if($row = ExecuteRow($sql)) 
    {
      // $this->descuento = floatval($row["descuento"]);
      $this->descuento = 0.00;
      $this->cantidad_en_mano = floatval($row["cantidad_en_mano"]);
      $this->cantidad_unidad = floatval($row["cantidad_por_unidad_medida"]);
      $this->articulo_inventario = $row["articulo_inventario"];
      $this->unidad_medida = $row["unidad_medida"];
      $this->articulo = $row["id"];
      $this->fabricante = $row["fabricante"];
      $this->costo = $row["costo"];
      $sql = "SELECT alicuota FROM articulo WHERE id = '" . $this->articulo . "';"; 
      $codigo_alicuota = ExecuteScalar($sql);
      $sql = "SELECT alicuota FROM alicuota
          WHERE codigo = '$codigo_alicuota' AND activo = 'S';";
      $this->alicuota = floatval(ExecuteScalar($sql));
      $sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
      $this->almacen = ExecuteScalar($sql);
      $this->cantidad = $this->cantidad_unidad * $this->cantidad;
      $this->resultado = 1;
    }
    else $this->resultado = 0;
  }
  function insertar_articulo($tipo_documento, $salida, $cod_articulo, $cantidad, $precio) 
  { 
    $this->tipo_documento = $tipo_documento;
    $this->salida = $salida;
    $this->cod_articulo = $cod_articulo;
    $this->cantidad = $cantidad;
    $this->precio = $precio;
    $this->precio_ful = $precio;
    $this->datos_articulo();
    if($this->resultado==0) {
    	echo 'El C&oacute;digo ' . $cod_articulo . ' no existe y no se puede continuar con el proceso. La factura qued&oacute; incompleta. ';
    	echo '<input type="button" onclick="js: window.location.replace(\'SalidasList\');" value="Regresar" />';
    	die();
    }

    // Lleno un arreglo con todas las notas que contengan ese artículo
    // y con las cantidades a descontar de cada una hasta llegar a la cantidad solicitada del csv
    $this->BuscarNotas($this->articulo, $cantidad);

    // var_dump($this->ArrNotas);
    // die();
    $sw = false; // Para validar que el artículo esté en alguna de las notas de entrega (27/01/2024)
	foreach ($this->ArrNotas as $key => $value) {
		$this->cantidad = $value["cantidad"];
    	$this->total = $this->precio * $this->cantidad;
    	$this->cantidad_movimiento = $this->cantidad * (-1);
    	$this->total_costo = $this->costo * $this->cantidad;
	    $sql = "INSERT INTO entradas_salidas
	          (id, tipo_documento, id_documento, 
	          fabricante, articulo, almacen, 
	          cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
	          precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc, costo_unidad, costo, lote, fecha_vencimiento, check_ne)
	        VALUES 
	          (NULL, '$this->tipo_documento', $this->salida, 
	          $this->fabricante, $this->articulo, '$this->almacen', 
	          $this->cantidad, '$this->unidad_medida', $this->cantidad_unidad, $this->cantidad_movimiento, 
	          $this->precio, $this->total, $this->alicuota, $this->descuento, $this->precio_ful, $this->costo, $this->total_costo, '" . $value["lote"] . "', '" . $value["fecha"] . "', 'S');";
	    Execute($sql);
	    $sw = true; // El artículo esta en alguna nota de entrega (27/01/2024)

	    // Se marca la cantidad tomada de la consignación del item de la nota seleccionada 
	    $sql = "UPDATE entradas_salidas 
				SET 
				cantidad_movimiento_consignacion = IFNULL(cantidad_movimiento_consignacion, 0) + ABS($this->cantidad_movimiento) 
				WHERE id = " . $value["id_entrada_salida"] . ";";
		Execute($sql);

		/// Marco como procesada la Nota de Entrega si no hay items con cantidades pendientes ///
		$sql = "SELECT 
				COUNT(id) AS cantidad 
			FROM 
				entradas_salidas 
			WHERE 
				id_documento = " . $value["id_documento"] . " AND tipo_documento = 'TDCNET' 
				AND (ABS(cantidad_movimiento) - IFNULL(cantidad_movimiento_consignacion, 0)) > 0;";
		$xCant = intval(ExecuteScalar($sql)); 
		if($xCant == 0) {
			$sql = "UPDATE salidas SET estatus = 'PROCESADO' WHERE id = '" . $value["id_documento"] . "'";
			Execute($sql);
		}
	}

	// Si el artícuo no está en alguna de las notas de entrega se egrega igualmente a la factura (27/01/2024)
	if($sw == false) { 
		$this->cantidad = $cantidad;
    	$this->total = $this->precio * $this->cantidad;
    	$this->cantidad_movimiento = $this->cantidad * (-1);
    	$this->total_costo = $this->costo * $this->cantidad;
	    $sql = "INSERT INTO entradas_salidas
	          (id, tipo_documento, id_documento, 
	          fabricante, articulo, almacen, 
	          cantidad_articulo, articulo_unidad_medida, cantidad_unidad_medida, cantidad_movimiento, 
	          precio_unidad, precio, alicuota, descuento, precio_unidad_sin_desc, costo_unidad, costo, lote, fecha_vencimiento, check_ne)
	        VALUES 
	          (NULL, '$this->tipo_documento', $this->salida, 
	          $this->fabricante, $this->articulo, '$this->almacen', 
	          $this->cantidad, '$this->unidad_medida', $this->cantidad_unidad, $this->cantidad_movimiento, 
	          $this->precio, $this->total, $this->alicuota, $this->descuento, $this->precio_ful, $this->costo, $this->total_costo, NULL, NULL, 'S');";
	    Execute($sql);
        $sql = "UPDATE 
                    salidas AS a 
                    JOIN (SELECT 
                                id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                            FROM 
                                entradas_salidas 
                            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = $this->salida 
                            GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
                SET 
                    a.unidades = b.cantidad 
                WHERE a.id = $this->salida;";
        Execute($sql);
	}
    return true;
  }
  function ActualizarCabecera() {
    $sql = "SELECT COUNT(DISTINCT alicuota ) AS cantidad FROM entradas_salidas
            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
    if(intval(ExecuteScalar($sql)) > 1) $alicuota = 0;
    else {
      $sql = "SELECT DISTINCT alicuota FROM entradas_salidas
              WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida';";
      $alicuota = floatval(ExecuteScalar($sql));
    }

    // Se actualiza el encabezado del pedido de venta //
    $sql = "SELECT
              SUM(precio) AS precio, 
              SUM((precio * (alicuota/100))) AS iva, 
              SUM(precio) + SUM((precio * (alicuota/100))) AS total 
            FROM 
              entradas_salidas
            WHERE tipo_documento = '$this->tipo_documento' AND id_documento = '$this->salida'";
    $row = ExecuteRow($sql);
    $precio = floatval($row["precio"]);
    $iva = floatval($row["iva"]);
    $total = floatval($row["total"]);
    $sql = "UPDATE salidas 
            SET
              monto_total = $precio,
              alicuota_iva = $alicuota, 
              iva = $iva,
              total = $total
            WHERE tipo_documento = '$this->tipo_documento' AND id = '$this->salida'";
    Execute($sql);

    /* Se actualizan las cantidades de unidades en el encabezado de la salida */
    $sql = "UPDATE 
                salidas AS a 
                JOIN (SELECT 
                            id_documento, tipo_documento, ABS(SUM(cantidad_movimiento)) AS cantidad 
                        FROM 
                            entradas_salidas 
                        WHERE tipo_documento = '$this->tipo_documento' AND id_documento = $this->salida 
                        GROUP BY id_documento, tipo_documento) AS b ON b.id_documento = a.id AND b.tipo_documento = a.tipo_documento 
            SET 
                a.unidades = b.cantidad 
            WHERE a.id = $this->salida;";
    Execute($sql);
  }
  function BuscarNotas($articulo, $cantidad) {
  	$xCant = $cantidad;
  	$this->ArrNotas = array();
  	$sql = "SELECT 
				b.id_documento, b.id AS id_entrada_salida, b.lote, b.fecha_vencimiento AS fecha, 
				(ABS(b.cantidad_movimiento) - IFNULL(b.cantidad_movimiento_consignacion, 0)) AS cantidad 
			FROM 
				salidas AS a 
				JOIN entradas_salidas AS b ON b.tipo_documento = a.tipo_documento AND b.id_documento = a.id 
			WHERE 
				a.tipo_documento = 'TDCNET' AND a.consignacion = 'S' 
					AND a.estatus = 'NUEVO' AND a.cliente = " . $this->cliente . " AND b.articulo = $articulo 
						AND (ABS(b.cantidad_movimiento) - IFNULL(b.cantidad_movimiento_consignacion, 0)) > 0 
			ORDER BY b.fecha_vencimiento ASC;";
	$rows = ExecuteRows($sql);
	foreach ($rows as $key => $value) {
		$this->ArrNotas[$key]["id_documento"] = $value["id_documento"];
		$this->ArrNotas[$key]["id_entrada_salida"] = $value["id_entrada_salida"];
		$this->ArrNotas[$key]["lote"] = $value["lote"];
		$this->ArrNotas[$key]["fecha"] = $value["fecha"];
		if($xCant > $value["cantidad"]) {
			$this->ArrNotas[$key]["cantidad"] = $value["cantidad"];
			$xCant -= $value["cantidad"];
		}
		else {
			// $this->ArrNotas[$key]["cantidad"] =  "" . number_format($xCant, 2, ".", "") . "";
			$this->ArrNotas[$key]["cantidad"] =  $xCant;
			break;
		}
	}
  }
}
function validar_fecha_vencimiento($fecha){
	$valores = explode('-', $fecha);
	if(intval($valores[2]) > 2000 and intval($valores[2]) < 3000 and intval($valores[1]) >= 1 and intval($valores[1]) <= 12) 
		return intval($valores[2]) . "-" . intval($valores[1]) . "-01";
	else 
		return "NULL";
}
function CalcularCostoPromedioPonderado($articulo, $costo, $cantidad, $recepcion) {
    $sql = "SELECT valor1 AS tipo_documento FROM parametro WHERE codigo = '050';";
    $tipo_documento_inventario = 'TDCNET';
    if($row = ExecuteRow($sql)) $tipo_documento_inventario = $row["tipo_documento"];
	$sql = "SELECT 
              x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen, 
						SUM(x.cantidad_movimiento) AS cantidad, MAX(x.costo_unidad) AS costo   
            FROM 
              (
                SELECT 
                   a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
                   IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
                   a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen, 
				    a.costo_unidad   
                FROM 
                   entradas_salidas AS a 
                   JOIN entradas AS b ON
                       b.tipo_documento = a.tipo_documento
                       AND b.id = a.id_documento 
                   JOIN almacen AS c ON
                       c.codigo = a.almacen AND c.movimiento = 'S'
                WHERE 
                   (
                       (a.tipo_documento = 'TDCAEN' AND b.estatus <> 'ANULADO') OR 
                       (a.tipo_documento = 'TDCNRP' AND a.check_ne = 'S' AND b.estatus <> 'ANULADO')
                   ) AND a.articulo = $articulo AND a.newdata = 'S' AND a.almacen IN('ALM001', 'CONSIG', 'RES001') AND b.id NOT IN ($recepcion)  
                UNION ALL SELECT 
                   a.articulo, IFNULL(a.lote, '') AS lote, DATE_FORMAT(IFNULL(a.fecha_vencimiento, '1990-01-01'), '%d/%m/%Y') AS fecha, 
                   IFNULL(a.fecha_vencimiento, '1990-01-01') AS fecha_vencimiento, 
                   a.cantidad_movimiento, a.almacen AS codalm, c.descripcion AS almacen,  
                   0 AS costo_unidad 
                FROM 
                   entradas_salidas AS a 
                   JOIN salidas AS b ON
                       b.tipo_documento = a.tipo_documento
                       AND b.id = a.id_documento 
                   JOIN almacen AS c ON
                       c.codigo = a.almacen AND c.movimiento = 'S'
                WHERE 
                   (
                       (a.tipo_documento IN ('$tipo_documento_inventario', 'TDCASA') AND b.estatus <> 'ANULADO') 
                   ) AND a.articulo = $articulo AND a.newdata = 'S' AND a.almacen IN('ALM001', 'CONSIG', 'RES001') 
              ) AS x 
            WHERE 1 
            GROUP BY x.articulo, x.lote, x.fecha, x.fecha_vencimiento, x.codalm, x.almacen 
            HAVING SUM(x.cantidad_movimiento) > 0 
            ORDER BY x.fecha_vencimiento ASC;";
	$rows = ExecuteRows($sql); 
	$xcosto = 0; 
	$xcantidad = 0;
	$ponderado = 0;
    $sw = 0;
	foreach ($rows as $key => $value) {
		$xcantidad += intval($value["cantidad"]);
		$xcosto += floatval($value["costo"])*intval($value["cantidad"]); 
        $sw = 1;
	} 
    if($sw == 1) {
        $ponderado = ($xcosto+($costo*$cantidad))/($xcantidad+$cantidad);
        $sql = "UPDATE articulo SET ultimo_costo = $ponderado WHERE id = $articulo;";
        Execute($sql);
    }
}

// Add listeners
AddListener(DatabaseConnectingEvent::NAME, fn(DatabaseConnectingEvent $event) => Database_Connecting($event));
AddListener(DatabaseConnectedEvent::NAME, fn(DatabaseConnectedEvent $event) => Database_Connected($event->getConnection()));
AddListener(LanguageLoadEvent::NAME, fn(LanguageLoadEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "Language_Load")->bindTo($event->getLanguage())());
AddListener(MenuItemAddingEvent::NAME, fn(MenuItemAddingEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "MenuItem_Adding")->bindTo($event->getMenu())($event->getMenuItem()));
AddListener(MenuRenderingEvent::NAME, fn(MenuRenderingEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "Menu_Rendering")->bindTo($event->getMenu())($event->getMenu()));
AddListener(MenuRenderedEvent::NAME, fn(MenuRenderedEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "Menu_Rendered")->bindTo($event->getMenu())($event->getMenu()));
AddListener(PageLoadingEvent::NAME, fn(PageLoadingEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "Page_Loading")->bindTo($event->getPage())());
AddListener(PageRenderingEvent::NAME, fn(PageRenderingEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "Page_Rendering")->bindTo($event->getPage())());
AddListener(PageUnloadedEvent::NAME, fn(PageUnloadedEvent $event) => Closure::fromCallable(PROJECT_NAMESPACE . "Page_Unloaded")->bindTo($event->getPage())());
AddListener(RouteActionEvent::NAME, fn(RouteActionEvent $event) => Route_Action($event->getApp()));
AddListener(ApiActionEvent::NAME, fn(ApiActionEvent $event) => Api_Action($event->getApp()));
AddListener(ContainerBuildEvent::NAME, fn(ContainerBuildEvent $event) => Container_Build($event->getBuilder()));

// PhpSpreadsheet
AddListener(ConfigurationEvent::NAME, function (ConfigurationEvent $event) {
    $exts = array_merge($event->get("PHP_EXTENSIONS"), [
        "zip" => "zip",
        "zlib" => "zlib",
    ]);
    $event->import([
        "USE_PHPEXCEL" => true,
        "EXPORT_EXCEL_FORMAT" => "Excel5",
        "PHP_EXTENSIONS" => $exts,
    ]);
});
