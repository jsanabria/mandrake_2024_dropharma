<?php

namespace PHPMaker2024\mandrake;

// Page object
$Home = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

//ActualizarExitencia();
$sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
$bloquea = ExecuteScalar($sql);
if($bloquea == "SI") {
	$msbloquea = '<div class="alert alert-danger" role="alert">PROCESO DE PEDIDO DE VENTAS BLOQUEADO TEMPORALMENTE POR MANTENIMIENTO</div>';
}

$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels
		WHERE userlevelid = '" . CurrentUserLevel() . "';"; 
$grupo = trim(ExecuteScalar($sql) ?? '');

$sql = "SELECT nombre, telefono, email, foto, asesor, cliente 
		FROM usuario
		WHERE username = '" . CurrentUserName() . "';";
if($row = ExecuteRow($sql)) {
	$nombre = $row["nombre"];
	$telefono = $row["telefono"];
	$email = $row["email"];
	$asesor = intval(trim($row["asesor"] ?? ''));
	$cliente = intval(trim($row["cliente"] ?? ''));
}
else {
	$nombre = "";
	$telefono = "";
	$email = "";
	$asesor = 0;
	$cliente = 0;
}
$foto = "carpetacarga/" . (!isset($row["foto"]) ? "silueta.jpg" : $row["foto"]);

$tarifas = "";
$where = "0=0";

if ($asesor > 0) {
    // OPTIMIZACIÓN 1: Obtener todas las tarifas distintas en una sola consulta
    $sqlTarifas = "SELECT DISTINCT b.tarifa, c.nombre 
                   FROM asesor_cliente AS a 
                   JOIN cliente AS b ON b.id = a.cliente 
                   JOIN tarifa AS c ON c.id = b.tarifa 
                   WHERE a.asesor = $asesor";
    
    $rsTarifas = ExecuteQuery($sqlTarifas);
    if ($rsTarifas) {
        while ($row = $rsTarifas->fetch()) {
            $tarifas .= '<hr><a class="btn btn-info" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');" >Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';
        }
    }

    // OPTIMIZACIÓN 2: Generar la lista de clientes para el WHERE usando GROUP_CONCAT
    // Esto reemplaza el segundo ciclo for y las múltiples consultas a asesor_cliente
    $sqlClientes = "SELECT GROUP_CONCAT(cliente) FROM asesor_cliente WHERE asesor = '$asesor'";
    $listaClientes = ExecuteScalar($sqlClientes);

    if (!empty($listaClientes)) {
        $where = "codcli IN ($listaClientes)";
    } else {
        $where = "codcli IN (0)";
    }
}

if($cliente > 0) {
	$sql = "SELECT 
				a.tarifa, b.nombre 
			FROM 
				cliente AS a  
				JOIN tarifa AS b ON b.id = a.tarifa 
			WHERE a.id = $cliente";
		$row = ExecuteRow($sql);
		$tarifas .= '<hr><a class="btn btn-info" target="_blank" href="reportes/listado_articulos_por_tarifa.php?username=' . CurrentUserName() . '&codcliente=&tarifa=' . $row["tarifa"] . '">Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';

	$where = "codcli=$cliente";
} 

$levelid = CurrentUserLevel();

if ($levelid == -1 || $levelid == 12) {
    // OPTIMIZACIÓN: Consultamos directamente todas las tarifas activas
    $sql = "SELECT id AS tarifa, nombre 
            FROM tarifa 
            WHERE activo = 'S' 
            ORDER BY nombre ASC"; // Añadido un orden para mejor visualización

    $rs = ExecuteQuery($sql);

    if ($rs) {
        // Recorremos el set de resultados directamente
        while ($row = $rs->fetch()) {
            $tarifas .= '<hr><a class="btn btn-info" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');" >Articulos Tarifa ' . ($row["nombre"] ?? '') . '</a><hr> ';
        }
    }
}

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$tasa = '<hr><b>TASA DEL DIA <br>1 USD <br>' . number_format(ExecuteScalar($sql), 2, ",", ".") . " Bs.<br><hr></b>";

//////////// Activo alerta ////////////
$sql = "SELECT 
			COUNT(nro_documento) AS dias 
		FROM 
			view_facturas_a_entregar 
		WHERE 
			$where;";
$facturas_a_entregar = intval(ExecuteScalar($sql));

$sql = "SELECT 
			COUNT(nro_documento) AS dias 
		FROM 
			view_facturas_vencidas  
		WHERE 
			$where;";
$facturas_vencidas = intval(ExecuteScalar($sql));
//////////// ------------- ////////////

?>
<div class="card">
	<div class="card-header text-center">
		<?php if(!VerificaFuncion("039")) { ?>
		<!-- <a href="SalidasList?tipo=TDCFCV" class="btn btn-primary"><i class="fa fa-view"></i> Consulta de Facturas</a> -->
		<a href="ArticuloList" class="btn btn-primary"><i class="fa fa-view"></i> Consulta de Art&iacute;culos</a> 
		<?php } ?>
		Sistema de Facturaci&oacute;n y Control de Inventarios
		<?php
		if($grupo != "PROVEEDOR" and !VerificaFuncion("039")) {
		?>
		  <?php if($facturas_a_entregar>0) { ?><a href="ViewFacturasAEntregarList" class="btn btn-primary"><i class="fa fa-clock"></i> <?php echo $facturas_a_entregar; ?></a><?php } ?>
		  <?php if($facturas_vencidas>0) { ?><a href="ViewFacturasVencidasList" class="btn btn-primary"><i class="fa fa-bell"></i> <?php echo $facturas_vencidas; ?></a><?php } ?>
		<?php
		}
		?>
	</div>

	<div class="container">
		<div class="card-body">
			<?php
			$row = ExecuteRow("SELECT nombre, logo FROM compania LIMIT 0,1;");
			$cia = $row["nombre"];
			$logo = $row["logo"];
			?>
			<h1 class="text-center"><?php echo $cia; ?></h1>
			<center><img src="carpetacarga/<?php echo $logo; ?>" width="350" class="img-rounded img-responsive center-block" alt="DroPharma"></center>
			<?php
			$db = ExecuteScalar("SELECT DATABASE();");
			?>
			<h4 class="text-center"><strong><i><?php echo "Base de Datos: " . $db; ?><i></strong></h4>
		</div>
		<?php
		if($bloquea == "SI") echo $msbloquea;
		?>
	</div>
</div>
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-2">
				<img src="<?php echo $foto; ?>" class="img-responsive img-thumbnail" alt="Cinque Terre" width="150">
			</div>
			<div class="col-md-3">
				<h4><?php echo $nombre; ?></h4>
				<h4><?php echo "$telefono / $email"; ?></h4>
			</div>
			<div class="col-md-4">
				<?php
					if(CurrentUserLevel() == -1) {
						?>
							<p><a href="Sesiones" target="_blank"><strong>Ultimos Inicios de Sesi&oacute;n <?php echo date("d/m/Y"); ?></strong></a></p>
							<table class="table table-condensed table-hover">
								<!--<thead>
									<tr>
										<th>Usuario</th>
										<th>Fecha</th>
									</tr>
								</thead>-->
								<tbody>
									<?php
										// 1. Definimos la consulta única para traer los últimos 7 registros de hoy
                                    $hoy = date("Y-m-d");
                                    $sql = "SELECT 
                                                IFNULL(b.nombre, a.user) AS usuario, 
                                                DATE_FORMAT(a.datetime, '%h:%i:%s %p') AS fecha, 
                                                a.action 
                                            FROM 
                                                audittrail AS a  
                                                LEFT OUTER JOIN usuario AS b ON b.username = a.user 
                                            WHERE 
                                                DATE(a.datetime) = '$hoy' 
                                                AND a.action IN ('login', 'logout') 
                                                AND a.user <> '-1' 
                                            ORDER BY a.datetime DESC 
                                            LIMIT 7;";

                                    // 2. Ejecutamos la consulta una sola vez
                                    $rs = ExecuteQuery($sql);

                                    // 3. Recorremos el set de resultados
                                    if ($rs) {
                                        while ($row = $rs->fetch()) {
                                            echo '<tr>';
                                            echo '<td>' . ($row["usuario"] ?? '') . '</td>';
                                            echo '<td>' . ($row["fecha"] ?? '') . '</td>';
                                            echo '<td>' . ($row["action"] ?? '') . '</td>';
                                            echo '</tr>';
                                        }
                                    }
									?>
								</tbody>
							</table>
						<?php
						echo $tarifas;
					}
					else echo $tarifas; ?>
			</div>
			<div class="col-md-3">
				<?php echo $tasa; ?>
				<?php
					//if(CurrentUserLevel() == -1) {
						echo '<h1><a href="Indicadores" target="_blank"><span class="fa fa-signal"></span></a></h1>';
					//}
				?>
				<?php 
					echo "<h6>Fecha php: " .  date("d/m/Y H:i:s") . "</h6>";
					echo "<h6>Fecha MySQL: " . ExecuteScalar("SELECT date_format(now(), '%d/%m/%Y %H:%i:%s') AS fecha;") . "</h6>";
				?>
			</div>
		</div>
	</div>
	<a class="btn btn-primary" href="include/entregar_facturas.php">Facturas de Pedidos Entregados (Carga Masiva...)</a>
    <br>
    <a class="btn btn-primary" href="include/ExportDataCronFTPFullTech360.php" target="_blank">Actualizar .txt Articulos y Clientes (Carga FTP 2)</a>
</div>

<?php
// 1. Consultar par谩metro de bloqueo
$sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
$bloquea = strtoupper(ExecuteScalar($sql));

if ($bloquea == "NO") { 
    
    // Definimos las rutas a monitorear
    $ruta_pedidos = "/home2/dropharm/dropharmadm/ftpexportar/pedidos/";
    $ruta_salidas = "/home2/dropharm/dropharmadm/ftpexportar2/salidas/";

    // Función interna para contar archivos reales (ignorando . y ..)
    $contarArchivos = function($path) {
        if (!is_dir($path)) return 0;
        $files = scandir($path);
        // Filtramos para contar solo archivos reales, no los directorios "." y ".."
        return count(array_diff($files, array('.', '..')));
    };

    // 2. Revisar archivos en ambas carpetas
    $total_archivos = 0;
    $total_archivos += $contarArchivos($ruta_pedidos);
    $total_archivos += $contarArchivos($ruta_salidas);

    // 3. Si hay archivos pendientes, redireccionamos al procesador FTP
    if ($total_archivos > 0) {
        // Asumo que $levelid viene del contexto de tu aplicaci贸n (Mandrake)
        if (isset($levelid) && $levelid == -1) {
            header("Location: FtpSubirPedidos");
            exit(); // Es preferible exit() a die() en flujos de cabecera
        }
    }
}
?>

<script>
	function print_to(tarifa) { 
        var username = "<?= CurrentUserName() ?>";
		if(confirm("Desea Enviar a Excel?")) {
			var url = "print_tarifa.php?username=" + username + "&codcliente=&tarifa=" + tarifa + "";
			window.open(url, '_blank');
		}
		else {
			var url = "reportes/listado_articulos_por_tarifa.php?username=" + username + "&codcliente=&tarifa=" + tarifa + "";
			window.open(url, '_blank');
		}
	}
</script>

<?= GetDebugMessage() ?>
