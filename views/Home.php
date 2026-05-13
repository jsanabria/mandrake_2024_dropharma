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
$bloquea = strtoupper(trim(ExecuteScalar($sql) ?? "NO"));
$msbloquea = "";
if ($bloquea == "SI") {
    $msbloquea = '<div class="alert alert-danger d-flex align-items-center gap-2 mt-3 mb-0" role="alert"><i class="fa-solid fa-screwdriver-wrench"></i><div><strong>Proceso bloqueado:</strong> pedido de ventas bloqueado temporalmente por mantenimiento.</div></div>';
}

$sql = "SELECT IFNULL(tipo_acceso, '') AS tipo_acceso FROM userlevels
        WHERE userlevelid = '" . CurrentUserLevel() . "';";
$grupo = trim(ExecuteScalar($sql) ?? '');

$sql = "SELECT nombre, telefono, email, foto, asesor, cliente
        FROM usuario
        WHERE username = '" . CurrentUserName() . "';";
if ($row = ExecuteRow($sql)) {
    $nombre = $row["nombre"];
    $telefono = $row["telefono"];
    $email = $row["email"];
    $asesor = intval(trim($row["asesor"] ?? ''));
    $cliente = intval(trim($row["cliente"] ?? ''));
    $fotoUsuario = $row["foto"] ?? "";
} else {
    $nombre = "";
    $telefono = "";
    $email = "";
    $asesor = 0;
    $cliente = 0;
    $fotoUsuario = "";
}
$foto = "carpetacarga/" . (trim($fotoUsuario) == "" ? "silueta.jpg" : $fotoUsuario);

$tarifas = "";
$where = "0=0";

if ($asesor > 0) {
    $sqlTarifas = "SELECT DISTINCT b.tarifa, c.nombre
                   FROM asesor_cliente AS a
                   JOIN cliente AS b ON b.id = a.cliente
                   JOIN tarifa AS c ON c.id = b.tarifa
                   WHERE a.asesor = $asesor
                   ORDER BY c.nombre ASC";

    $rsTarifas = ExecuteQuery($sqlTarifas);
    if ($rsTarifas) {
        while ($row = $rsTarifas->fetch()) {
            $tarifas .= '<a class="btn btn-outline-primary btn-sm rounded-pill m-1" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');"><i class="fa-solid fa-file-excel"></i> Artículos Tarifa ' . ($row["nombre"] ?? '') . '</a>';
        }
    }

    $sqlClientes = "SELECT GROUP_CONCAT(cliente) FROM asesor_cliente WHERE asesor = '$asesor'";
    $listaClientes = ExecuteScalar($sqlClientes);

    if (!empty($listaClientes)) {
        $where = "codcli IN ($listaClientes)";
    } else {
        $where = "codcli IN (0)";
    }
}

if ($cliente > 0) {
    $sql = "SELECT a.tarifa, b.nombre
            FROM cliente AS a
            JOIN tarifa AS b ON b.id = a.tarifa
            WHERE a.id = $cliente";
    $row = ExecuteRow($sql);
    if ($row) {
        $tarifas .= '<a class="btn btn-outline-primary btn-sm rounded-pill m-1" target="_blank" href="reportes/listado_articulos_por_tarifa.php?username=' . CurrentUserName() . '&codcliente=&tarifa=' . $row["tarifa"] . '"><i class="fa-solid fa-file-lines"></i> Artículos Tarifa ' . ($row["nombre"] ?? '') . '</a>';
    }
    $where = "codcli=$cliente";
}

$levelid = CurrentUserLevel();

if ($levelid == -1 || $levelid == 12) {
    $sql = "SELECT id AS tarifa, nombre
            FROM tarifa
            WHERE activo = 'S'
            ORDER BY nombre ASC";

    $rs = ExecuteQuery($sql);
    if ($rs) {
        while ($row = $rs->fetch()) {
            $tarifas .= '<a class="btn btn-outline-primary btn-sm rounded-pill m-1" target="_blank" onclick="js:print_to(' . $row["tarifa"] . ');"><i class="fa-solid fa-file-excel"></i> Artículos Tarifa ' . ($row["nombre"] ?? '') . '</a>';
        }
    }
}

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;";
$tasaDia = floatval(ExecuteScalar($sql));

$sql = "SELECT COUNT(nro_documento) AS dias
        FROM view_facturas_a_entregar
        WHERE $where;";
$facturas_a_entregar = intval(ExecuteScalar($sql));

$sql = "SELECT COUNT(nro_documento) AS dias
        FROM view_facturas_vencidas
        WHERE $where;";
$facturas_vencidas = intval(ExecuteScalar($sql));

$rowCia = ExecuteRow("SELECT nombre, logo FROM compania LIMIT 0,1;");
$cia = $rowCia["nombre"] ?? "";
$logo = $rowCia["logo"] ?? "";
$db = ExecuteScalar("SELECT DATABASE();");
$fechaMysql = ExecuteScalar("SELECT date_format(now(), '%d/%m/%Y %H:%i:%s') AS fecha;");

// Revisar archivos pendientes antes de emitir HTML para que la redirección funcione.
if ($bloquea == "NO") {
    $ruta_pedidos = "/home2/dropharm/dropharmadm/ftpexportar/pedidos/";
    $ruta_salidas = "/home2/dropharm/dropharmadm/ftpexportar2/salidas/";

    $contarArchivos = function ($path) {
        if (!is_dir($path)) {
            return 0;
        }
        $files = scandir($path);
        return count(array_diff($files, ['.', '..']));
    };

    $total_archivos = 0;
    $total_archivos += $contarArchivos($ruta_pedidos);
    $total_archivos += $contarArchivos($ruta_salidas);

    if ($total_archivos > 0 && isset($levelid) && $levelid == -1) {
        header("Location: FtpSubirPedidos");
        exit();
    }
}
?>

<style>
    .mdk-dashboard {
        max-width: 1240px;
        margin: 0 auto;
    }
    .mdk-card {
        border: 0;
        border-radius: 1.25rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
        overflow: hidden;
    }
    .mdk-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 55%, #084298 100%);
        color: #fff;
        padding: 1.25rem 1.5rem;
    }
    .mdk-hero .btn {
        border-radius: 999px;
        font-weight: 600;
    }
    .mdk-logo-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 1.25rem;
        padding: 1rem 1.5rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .14);
    }
    .mdk-logo-box img {
        max-width: 320px;
        max-height: 160px;
        object-fit: contain;
    }
    .mdk-user-photo {
        width: 116px;
        height: 116px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .16);
        background: #f8f9fa;
    }
    .mdk-metric {
        border: 1px solid rgba(13, 110, 253, .08);
        border-radius: 1.1rem;
        background: linear-gradient(180deg, #fff, #f8fbff);
        padding: 1.25rem;
        height: 100%;
    }
    .mdk-metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(13, 110, 253, .12);
        color: #0d6efd;
        font-size: 1.3rem;
        margin-bottom: .75rem;
    }
    .mdk-section-title {
        font-weight: 700;
        color: #263238;
        margin-bottom: .9rem;
    }
    .mdk-action-card {
        border: 1px dashed rgba(13, 110, 253, .28);
        background: #f8fbff;
        border-radius: 1rem;
        padding: 1rem;
    }
    .mdk-session-table td {
        vertical-align: middle;
        font-size: .92rem;
    }
    .mdk-tarifas .btn {
        text-align: left;
    }
</style>

<div class="mdk-dashboard">
    <div class="card mdk-card mb-4">
        <div class="mdk-hero d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa-solid fa-boxes-stacked"></i> Sistema de Facturación y Control de Inventarios</h3>
                <div class="opacity-75"><i class="fa-solid fa-building"></i> <?= $cia ?></div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <?php if (!VerificaFuncion("039")) { ?>
                    <a href="ArticuloList" class="btn btn-light btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Consulta de Artículos</a>
                <?php } ?>

                <?php if ($grupo != "PROVEEDOR" && !VerificaFuncion("039")) { ?>
                    <?php if ($facturas_a_entregar > 0) { ?>
                        <a href="ViewFacturasAEntregarList" class="btn btn-warning btn-sm"><i class="fa-solid fa-clock"></i> <?= $facturas_a_entregar ?> por entregar</a>
                    <?php } ?>
                    <?php if ($facturas_vencidas > 0) { ?>
                        <a href="ViewFacturasVencidasList" class="btn btn-danger btn-sm"><i class="fa-solid fa-bell"></i> <?= $facturas_vencidas ?> vencidas</a>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <div class="card-body text-center p-4">
            <h2 class="fw-bold mb-3"><?= $cia ?></h2>
            <div class="mdk-logo-box mb-3">
                <img src="carpetacarga/<?= $logo ?>" alt="<?= $cia ?>">
            </div>
            <div class="mt-2">
                <span class="badge text-bg-secondary"><i class="fa-solid fa-database"></i> Base de datos: <?= $db ?></span>
            </div>
            <?= $msbloquea ?>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="card mdk-card h-100">
                <div class="card-body d-flex gap-3 align-items-center">
                    <img src="<?= $foto ?>" class="mdk-user-photo" alt="Usuario">
                    <div>
                        <h5 class="fw-bold mb-1"><?= $nombre ?></h5>
                        <div class="text-muted small">
                            <div><i class="fa-solid fa-phone"></i> <?= $telefono ?: 'Sin teléfono' ?></div>
                            <div><i class="fa-solid fa-envelope"></i> <?= $email ?: 'Sin email' ?></div>
                            <div><i class="fa-solid fa-user-shield"></i> Nivel: <?= CurrentUserLevel() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="mdk-metric text-center">
                <div class="mdk-metric-icon"><i class="fa-solid fa-dollar-sign"></i></div>
                <div class="text-muted small text-uppercase fw-bold">Tasa del día</div>
                <h2 class="fw-bold mb-0"><?= number_format($tasaDia, 2, ",", ".") ?></h2>
                <div class="text-muted">Bs. por 1 USD</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="mdk-metric text-center">
                <div class="mdk-metric-icon"><i class="fa-solid fa-chart-line"></i></div>
                <div class="text-muted small text-uppercase fw-bold">Indicadores</div>
                <a href="Indicadores" target="_blank" class="btn btn-primary btn-sm rounded-pill mt-2"><i class="fa-solid fa-signal"></i> Ver indicadores</a>
                <div class="small text-muted mt-3">
                    <div><i class="fa-regular fa-clock"></i> PHP: <?= date("d/m/Y H:i:s") ?></div>
                    <div><i class="fa-solid fa-database"></i> MySQL: <?= $fechaMysql ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mdk-card mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-lg-7">
                    <?php if (CurrentUserLevel() == -1) { ?>
                        <h5 class="mdk-section-title"><i class="fa-solid fa-right-to-bracket"></i> Últimos inicios de sesión <?= date("d/m/Y") ?></h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mdk-session-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Hora</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $hoy = date("Y-m-d");
                                    $sql = "SELECT IFNULL(b.nombre, a.user) AS usuario,
                                                   DATE_FORMAT(a.datetime, '%h:%i:%s %p') AS fecha,
                                                   a.action
                                            FROM audittrail AS a
                                            LEFT OUTER JOIN usuario AS b ON b.username = a.user
                                            WHERE DATE(a.datetime) = '$hoy'
                                              AND a.action IN ('login', 'logout')
                                              AND a.user <> '-1'
                                            ORDER BY a.datetime DESC
                                            LIMIT 7;";
                                    $rs = ExecuteQuery($sql);
                                    if ($rs) {
                                        while ($row = $rs->fetch()) {
                                            $badge = ($row["action"] ?? '') == 'login' ? 'text-bg-success' : 'text-bg-secondary';
                                            echo '<tr>';
                                            echo '<td>' . ($row["usuario"] ?? '') . '</td>';
                                            echo '<td>' . ($row["fecha"] ?? '') . '</td>';
                                            echo '<td><span class="badge ' . $badge . '">' . ($row["action"] ?? '') . '</span></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <h5 class="mdk-section-title"><i class="fa-solid fa-circle-info"></i> Panel de usuario</h5>
                        <div class="alert alert-light border mb-0">Bienvenido(a), <?= $nombre ?>.</div>
                    <?php } ?>
                </div>

                <div class="col-lg-5">
                    <h5 class="mdk-section-title"><i class="fa-solid fa-tags"></i> Tarifas disponibles</h5>
                    <div class="mdk-tarifas">
                        <?= $tarifas ?: '<div class="alert alert-light border mb-0">No hay tarifas disponibles para este usuario.</div>' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mdk-card mb-4">
        <div class="card-body p-4">
            <h5 class="mdk-section-title"><i class="fa-solid fa-bolt"></i> Acciones rápidas</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mdk-action-card h-100">
                        <div class="fw-bold mb-2"><i class="fa-solid fa-truck-fast"></i> Facturas de pedidos entregados</div>
                        <div class="text-muted small mb-3">Ejecuta la carga masiva de facturas de pedidos entregados.</div>
                        <a class="btn btn-primary btn-sm rounded-pill" href="include/entregar_facturas.php"><i class="fa-solid fa-play"></i> Ejecutar carga masiva</a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mdk-action-card h-100">
                        <div class="fw-bold mb-2"><i class="fa-solid fa-cloud-arrow-up"></i> Actualizar artículos y clientes</div>
                        <div class="text-muted small mb-3">Genera y sube archivos TXT de artículos y clientes vía FTP.</div>
                        <a class="btn btn-primary btn-sm rounded-pill" href="include/ExportDataCronFTPFullTech360.php" target="_blank"><i class="fa-solid fa-upload"></i> Actualizar TXT / FTP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function print_to(tarifa) {
        var username = "<?= CurrentUserName() ?>";
        if (confirm("Desea Enviar a Excel?")) {
            var url = "print_tarifa.php?username=" + username + "&codcliente=&tarifa=" + tarifa;
            window.open(url, '_blank');
        } else {
            var url = "reportes/listado_articulos_por_tarifa.php?username=" + username + "&codcliente=&tarifa=" + tarifa;
            window.open(url, '_blank');
        }
    }
</script>

<?= GetDebugMessage() ?>
