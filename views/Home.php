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
$grupo = trim(ExecuteScalar($sql));

$sql = "SELECT nombre, telefono, email, foto, asesor, cliente 
		FROM usuario
		WHERE username = '" . CurrentUserName() . "';";
if($row = ExecuteRow($sql)) {
	$nombre = $row["nombre"];
	$telefono = $row["telefono"];
	$email = $row["email"];
	$asesor = intval(trim($row["asesor"]));
	$cliente = intval(trim($row["cliente"]));
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
if($asesor > 0) {
	$sql = "SELECT 
				COUNT(f.tarifa) AS cantidad 
			FROM 
				(SELECT 
					DISTINCT b.tarifa, c.nombre 
				FROM 
					asesor_cliente AS a 
					JOIN cliente AS b ON b.id = a.cliente 
					JOIN tarifa AS c ON c.id = b.tarifa 
				WHERE a.asesor = $asesor) AS f;";
	$cantidad = ExecuteScalar($sql);

	for($i=0; $i<$cantidad; $i++) {
		$sql = "SELECT 
					DISTINCT b.tarifa, c.nombre 
				FROM 
					asesor_cliente AS a 
					JOIN cliente AS b ON b.id = a.cliente 
					JOIN tarifa AS c ON c.id = b.tarifa 
				WHERE a.asesor = $asesor LIMIT $i, 1;";
		$row = ExecuteRow($sql);
		// href="reportes/listado_articulos_por_tarifa.php?codcliente=&tarifa=' . $row["tarifa"] . '"
		$tarifas .= '<hr><a class="btn btn-info" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');" >Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';
	}

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
	$where = "codcli IN ($clientes)";
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

if($levelid == -1 or $levelid == 12) {
	$sql = "SELECT count(id) AS cantidad FROM tarifa WHERE activo = 'S';";
	$cantidad = ExecuteScalar($sql);

	for($i=0; $i<$cantidad; $i++) {
		$sql = "SELECT 
					id AS tarifa, nombre 
				FROM 
					tarifa WHERE activo = 'S' LIMIT $i, 1;";
		$row = ExecuteRow($sql);
		
		$tarifas .= '<hr><a class="btn btn-info" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');" >Articulos Tarifa ' . $row["nombre"] . '</a><hr> ';
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
										for($i=0; $i<7; $i++) {
											$sql = "SELECT 
														IFNULL(b.nombre, a.user) AS usuario, 
														DATE_FORMAT(a.datetime, '%h:%i:%s %p') AS fecha, 
														a.action 
													FROM 
														audittrail AS a  
														LEFT OUTER JOIN usuario AS b ON b.username = a.user 
													WHERE 
														DATE_FORMAT(a.datetime, '%d/%m/%Y') = DATE_FORMAT('" . date("Y-m-d H:i:s") . "', '%d/%m/%Y') 
														AND a.action IN ('login', 'logout') AND a.user <> '-1' 
													ORDER BY a.datetime DESC LIMIT $i, 1;";
											if($row = ExecuteRow($sql)) {
												echo '<tr>';
													echo '<td>' . $row["usuario"] . '</td>';
													echo '<td>' . $row["fecha"] . '</td>';
													echo '<td>' . $row["action"] . '</td>';
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
$sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
$bloquea = ExecuteScalar($sql);

if($bloquea == "NO") { 
    $rutas = [
        "/home4/drophqsc/dropharmadm.com/ftpexportar/pedidos/",
        "/home4/drophqsc/dropharmadm.com/ftpexportar2/salidas/"
    ];

    $total_archivos = 0;

    foreach ($rutas as $path) {
        // En Windows esto dará falso y saltará al siguiente sin dar error
        if (is_dir($path)) {
            $files = @scandir($path);
            if (is_array($files)) {
                // Filtramos '.' y '..' para contar solo archivos reales
                $archivos_reales = array_diff($files, array('.', '..'));
                $total_archivos += count($archivos_reales);
            }
        }
    }

    // Si hay archivos en cualquiera de las carpetas de producción
    if($total_archivos > 0) {
        if(isset($levelid) && $levelid == -1) {
            // Usamos redirección JS porque en el Home de PHPMaker 
            // a veces ya se envió HTML y el header() fallaría
            echo "<script>window.location.href='FtpSubirPedidos';</script>";
            exit();
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
