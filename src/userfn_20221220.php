<?php

namespace PHPMaker2021\mandrake;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

// Filter for 'Last Month' (example)
function GetLastMonthFilter($FldExpression, $dbid = 0)
{
    $today = getdate();
    $lastmonth = mktime(0, 0, 0, $today['mon'] - 1, 1, $today['year']);
    $val = date("Y|m", $lastmonth);
    $wrk = $FldExpression . " BETWEEN " .
        QuotedValue(DateValue("month", $val, 1, $dbid), DATATYPE_DATE, $dbid) .
        " AND " .
        QuotedValue(DateValue("month", $val, 2, $dbid), DATATYPE_DATE, $dbid);
    return $wrk;
}

// Filter for 'Starts With A' (example)
function GetStartsWithAFilter($FldExpression, $dbid = 0)
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
function Database_Connected(&$conn)
{
    // Example:
    //if ($conn->info["id"] == "DB") {
    //    $conn->executeQuery("Your SQL");
    //}
}

function MenuItem_Adding($item) {
	//var_dump($item);
	// Return FALSE if menu item not allowed
	if ($item->Text == "--") $item->Text = "<HR/>";
	return TRUE;
}

function Menu_Rendering($menu) {
	// Change menu items here
	$sql = "SELECT tipo_acceso FROM userlevels WHERE userlevelid = '" . CurrentUserLevel() . "';";
	$grupo = trim(ExecuteScalar($sql));
	if($grupo == "CLIENTE") {
		if ($menu->IsRoot) { // Root menu
			$menu->Clear(); // Clear all menu items
			$menu->AddMenuItem(1, "Pedido", "Pedido de Ventas", "SalidasList?tipo=TDCPDV");
			//$menu->AddMenuItem(2, "Articulo", "Consulta de Articulos", "ArticuloList");
			//$menu->AddMenuItem(2, "Nota", "Nota de Entrega", "salidaslist.php?tipo=TDCNET");
			//$menu->AddMenuItem(3, "Factura", "Factura de Venta", "salidaslist.php?tipo=TDCFCV");
			//$menu->AddMenuItem(4, "Entregados", "Pedidos Entregados", "view_facturas_a_entregarlist.php");
			//$menu->AddMenuItem(4, "Salir", "Salir", "Logout");
		}	
	}
	if($grupo == "PROVEEDOR") {
		$sql = "SELECT proveedor FROM usuario WHERE username = '" . CurrentUserName() . "';";
		$proveedor = trim(ExecuteScalar($sql));
		if ($menu->IsRoot) { // Root menu
			$menu->Clear(); // Clear all menu items
			$menu->AddMenuItem(1, "Proveedor", "Ficha Proveedor", "ProveedorView?showdetail=proveedor_articulo&id=$proveedor");
			$menu->AddMenuItem(2, "Facturas", "Facturas Proveedor", "EntradasList?tipo=TDCFCC");
			// $menu->AddMenuItem(3, "Ventas", "Ventas por Laboratorio", "ventas_por_laboratorio.php");
			//$menu->AddMenuItem(4, "Salir", "Salir", "Logout");
		}	
	}
	$sql = "SELECT DATABASE()";
	$database = ExecuteScalar($sql);
	$sql = "SELECT nombre FROM usuario WHERE username = '" . CurrentUserName() . "';";
	$usuario = trim(ExecuteScalar($sql));
	$row = ExecuteRow("SELECT nombre, logo FROM compania LIMIT 0,1;");
	$cia = $row["nombre"];
	$logo = $row["logo"];
	if ($menu->Id == "menu") { 
		if(trim($usuario) == "") 
			$menu->AddMenuItem(10000, "InfoSYSUser", "DB: $database<br>USR: " . CurrentUserName() . "<br>CIA: $cia", "#", -1, "", IsLoggedIn());
		else
			$menu->AddMenuItem(10000, "InfoSYSUser", "USR: $usuario" . "<br>CIA: $cia", "#", -1, "", IsLoggedIn());
		$menu->AddMenuItem(11000, "InfoLogo", "<center><img src='carpetacarga/$logo' width='150' class='img-rounded img-responsive center-block'></center>", "#", -1, "", IsLoggedIn());	
		$menu->moveItem("Download", $menu->Count() - 1); 
	}

	//$menu->MoveItem("Logout", $menu->Count() - 1);
}

function Menu_Rendered($menu)
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
function PersonalData_Downloading(&$row)
{
    //Log("PersonalData Downloading");
}

// Personal Data Deleted event
function PersonalData_Deleted($row)
{
    //Log("PersonalData Deleted");
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
	$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
	$almacen = ExecuteScalar($sql);
	$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
	$almacenconsig = ExecuteScalar($sql);
	$sql = "SELECT COUNT(id) AS cantidad FROM articulo WHERE articulo_inventario='S';";
	$cantidad = ExecuteScalar($sql);
	for($i = 0; $i < $cantidad; $i++) {
		$sql = "SELECT id AS articulo FROM articulo WHERE articulo_inventario='S' LIMIT $i, 1;";
		$articulo = ExecuteScalar($sql);
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
		  			a.tipo_documento IN ('TDCFCC') 
		  			AND b.estatus = 'NUEVO' AND a.articulo = '$articulo';"; 
		$transito = floatval(ExecuteScalar($sql));
		$sql = "SELECT 
				SUM(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad 
			FROM 
				entradas_salidas AS a 
				JOIN entradas AS b ON
					b.tipo_documento = a.tipo_documento
					AND b.id = a.id_documento 
				JOIN almacen AS c ON
					c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
				LEFT OUTER JOIN (
						SELECT 
							a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
						FROM 
							entradas_salidas AS a 
							JOIN salidas AS b ON
								b.tipo_documento = a.tipo_documento
								AND b.id = a.id_documento 
							LEFT OUTER JOIN almacen AS c ON
								c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
						WHERE
							a.tipo_documento IN ('TDCNET','TDCASA') 
							AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '$articulo' 
						GROUP BY a.id_compra
					) AS d ON d.id = a.id 
			WHERE
				((a.tipo_documento IN ('TDCNRP','TDCAEN') 
				AND b.estatus = 'PROCESADO')
				 OR
				(a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
				AND b.estatus = 'NUEVO')) AND a.articulo = '$articulo' 
				AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0";
		$cantidad_en_mano = floatval(ExecuteScalar($sql));				
		$sql = "UPDATE articulo
				SET
					cantidad_en_mano = $cantidad_en_mano,
					cantidad_en_pedido = IFNULL(ABS($pedido), 0),
					cantidad_en_transito = IFNULL(ABS($transito), 0) 
				WHERE id = '$articulo'";
		ExecuteScalar($sql);
	}
}

function ActualizarExitenciaArticulo($articulo) {
	$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '002';";
	$almacen = ExecuteScalar($sql);
	$sql = "SELECT valor1 AS ppal from parametro WHERE codigo = '014';";
	$almacenconsig = ExecuteScalar($sql);
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
		  			a.tipo_documento IN ('TDCFCC') 
		  			AND b.estatus = 'NUEVO' AND a.articulo = '$articulo';"; 
	$transito = floatval(ExecuteScalar($sql));
	$sql = "SELECT 
			SUM(IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) AS cantidad 
		FROM 
			entradas_salidas AS a 
			JOIN entradas AS b ON
				b.tipo_documento = a.tipo_documento
				AND b.id = a.id_documento 
			JOIN almacen AS c ON
				c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
			LEFT OUTER JOIN (
					SELECT 
						a.id_compra AS id, SUM(IFNULL(a.cantidad_movimiento, 0)) AS cantidad_movimiento 
					FROM 
						entradas_salidas AS a 
						JOIN salidas AS b ON
							b.tipo_documento = a.tipo_documento
							AND b.id = a.id_documento 
						LEFT OUTER JOIN almacen AS c ON
							c.codigo = a.almacen AND c.movimiento = 'S' AND c.codigo IN ('$almacen', '$almacenconsig') 
					WHERE
						a.tipo_documento IN ('TDCNET','TDCASA') 
						AND b.estatus IN ('NUEVO', 'PROCESADO') AND a.articulo = '$articulo' 
					GROUP BY a.id_compra
				) AS d ON d.id = a.id 
		WHERE
			((a.tipo_documento IN ('TDCNRP','TDCAEN') 
			AND b.estatus = 'PROCESADO')
			 OR
			(a.tipo_documento = 'TDCNRP' AND b.consignacion = 'S'
			AND b.estatus = 'NUEVO')) AND a.articulo = '$articulo' 
			AND (IFNULL(a.cantidad_movimiento, 0) + IFNULL(d.cantidad_movimiento, 0)) > 0";
	$cantidad_en_mano = floatval(ExecuteScalar($sql));				
	$sql = "UPDATE articulo
			SET
				cantidad_en_mano = $cantidad_en_mano,
				cantidad_en_pedido = IFNULL(ABS($pedido), 0),
				cantidad_en_transito = IFNULL(ABS($transito), 0) 
			WHERE id = '$articulo'";
	ExecuteScalar($sql);
}

function FiltraClientes() {
	$sql = "SELECT tipo_acceso FROM userlevels
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

function ActualizarUnidadesSalidas($id_documento, $tipo_documento) {
	/* Se actualizan las cantidades de unidades en el encabezado de la salida 21-01-2021 */
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
	$sql = "SELECT tipo_acceso FROM userlevels
			WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
	$grupo = trim(ExecuteScalar($sql));
	if($grupo == "CLIENTE") {
		$sql = "SELECT asesor FROM usuario
				WHERE username = '" . CurrentUserName() . "';";
		$row = ExecuteRow($sql);
		$asesor = intval($row["asesor"]);
		if($asesor > 0) {
			$sql = "SELECT COUNT(fabricante) AS cantidad FROM asesor_fabricante
					WHERE asesor = '$asesor';";
			$cantidad = ExecuteScalar($sql);
			if($cantidad == 0) return "";
			$fabricantes = "";
			for($i=0; $i<$cantidad; $i++) {
				$sql = "SELECT fabricante FROM asesor_fabricante
					WHERE asesor = '$asesor' LIMIT $i, 1;";
				$fabricantes .= ExecuteScalar($sql) . ",";
			}
			$fabricantes .= "0"; 
			return "id IN ($fabricantes)";
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
							'$nota', '" . $this->username . "', NOW(), NULL, NULL)";
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

/**** Fin ****/
