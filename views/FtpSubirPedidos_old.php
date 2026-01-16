<?php

namespace PHPMaker2024\mandrake;

// Page object
$FtpSubirPedidos = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
if(trim($_COOKIE["strcon"]) == "drophqsc_medrika") {
	header("Location: Home");
	die();
}

$sql = "SELECT valor1 AS almacen FROM parametro WHERE codigo = '002';";
$row = ExecuteRow($sql);
$almacen = $row["almacen"];

$tipo_documento = "TDCPDV";
$username = "FTP";
$lista_pedido = "PED000";

$path = "/home4/drophqsc/dropharmadm.com/ftpexportar/pedidos/";
// $path = "C:\\laragon\\www\\mandrake\\db\\Maquina_Fiscal\\pedidos\\";

$arrFiles = scandir($path);
$cnt = 0;
foreach ($arrFiles as $key => $value) { 
	if($value != "." and $value != "..") { 
		if(substr($value, 0, 6) == "PEDIDO") { 
			$sql = "SELECT factura FROM ftp_fact_pedi_procesado WHERE pedido = '$value';";
			if(!$row = ExecuteRow($sql)) {
				$myfile = str_replace("PEDIDO", "", $value);
				$myfile = str_replace(".txt", "", strtolower($myfile));
				$arrDatos = explode("_", $myfile);
				$cliente = $arrDatos[0];
				$pedido = $arrDatos[1];

				$nota = "CREADO VIA FTP DESDE ARCHVO $value Nro Pedido $pedido";
				$pedido = $value;

				$fp = fopen("$path$value", "r");
				$arrx = [];
				$lspd = "";
				$lista = [];
				while (!feof($fp)){ 
				    $linea = fgets($fp);
				    if(trim($linea) != "") { 
				    	$arry = [];
					    $detalle = explode(";", $linea);
					    $articulo = $detalle[3];
					    $sql = "SELECT codigo, lista_pedido FROM articulo WHERE id = $articulo;";
					    $row = ExecuteRow($sql);
					    $cod_articulo = $row["codigo"];
					    $lista_pedido = $row["lista_pedido"];
					    $nombre = $detalle[1];
					    $cantidad = $detalle[2];

   				    	$arry["articulo"] = $articulo;
   				    	$arry["cod_articulo"] = $cod_articulo;
   				    	$arry["lista_pedido"] = $lista_pedido;
   				    	$arry["nombre"] = $nombre;
   				    	$arry["cantidad"] = $cantidad;
   				    	$arrx[] = $arry;

   				    	if($lspd != $lista_pedido) { 
   				    		$sw = true;
   				    		foreach ($lista as $key => $value) {
   				    			if($value == $lista_pedido) $sw = false;
   				    		}
   				    		if($sw) $lista[] = $lista_pedido;
   				    		$lspd = $lista_pedido;
   				    	}
				    }
				} 
				fclose($fp);

				foreach ($lista as $key => $value) { 
					$sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS consecutivo FROM salidas WHERE tipo_documento = '$tipo_documento';";
					$row = ExecuteRow($sql);
					$consecutivo = intval($row["consecutivo"]) + 1; 
					$nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

					$sql = "INSERT INTO salidas
								(id, tipo_documento, username, fecha,
								cliente, nro_documento,
								nota, estatus,
								asesor, lista_pedido, asesor_asignado, moneda, descuento, nombre)
							VALUES  
								(NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "',
								$cliente, '$nro_documento',
								'$nota', 'NUEVO', 
								'$username', '$value', '$username', 'USD', 0, '$username');";
					Execute($sql);

					// Obtengo el id de la nueva factura
					$row = ExecuteRow("SELECT LAST_INSERT_ID() AS id;");
					$new_id = $row["id"];						
					$insart = new PdvLineaGuardar();
					foreach ($arrx as $key2 => $value2) {
						if($value2["lista_pedido"] == $value) {
	   				    	$articulo = $value2["articulo"];
	   				    	$cod_articulo = $value2["cod_articulo"];
	   				    	$lista_pedido = $value2["lista_pedido"];
	   				    	$nombre = $value2["nombre"];
	   				    	$cantidad = $value2["cantidad"];

						    $sql = "SELECT 
										b.activo, a.activo AS artact  
									FROM 
										articulo AS a 
										JOIN fabricante AS b ON b.Id = a.fabricante 
									WHERE 
										a.codigo = '$cod_articulo';";
							if($row101 = ExecuteRow($sql)) {
								if($row101["activo"] == "S" and $row101["artact"] == "S") {
									$insart->insertar_articulo($tipo_documento, $new_id, $cliente, $cod_articulo, $lista_pedido, $cantidad, 2);
								}
							}
						}
					}
					$insart->ActualizarCabecera();
					unset($insart);
					$cnt++;
				}


				$sql = "INSERT INTO ftp_fact_pedi_procesado	(id, factura, pedido, fecha_hora) VALUES (NULL, '', '" . $pedido . "', NOW())";
				Execute($sql);

				$path2 = "/home4/drophqsc/dropharmadm.com/ftpexportar/pedidos_old/";
				// $path2 = "C:\\laragon\\www\\mandrake\\db\\Maquina_Fiscal\\pedidos_old\\";
				if (!file_exists($path2)) {
					mkdir($path2, 0777, true);
				}
				$file = $path.$pedido;
				$file2 = $path2.$pedido;
				$moved = rename($file, $file2);
			}
		}
	}
}

echo '<div class="alert alert-primary" role="alert">
		  Proceso culminado, total de pedidos generados ' . $cnt . '
		</div>';
?>

<?= GetDebugMessage() ?>
