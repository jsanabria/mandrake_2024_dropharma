<?php

namespace PHPMaker2024\mandrake;

// Page object
$Transferencistas = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// Pantalla de mantenimiento de grupos de transferencistas.
// Requiere las funciones Execute(), ExecuteRow(), ExecuteRows() de PHPMaker 2024.
function transfEscape($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function transfSqlText($value)
{
    return "'" . AdjustSql((string)$value) . "'";
}
function transfExists($sql)
{
    return (bool)ExecuteRow($sql);
}

$mensaje = '';
$error = '';
$grupoId = isset($_REQUEST['grupo']) ? (int)$_REQUEST['grupo'] : 0;
$accion = isset($_POST['accion']) ? (string)$_POST['accion'] : '';

// PHPMaker 2024 expone estas cuatro variables en sus vistas personalizadas.
// No utilizar $Page->TokenName ni CheckToken() sin comprobar su contrato.
$transfTokenNameKey = $TokenNameKey ?? 'token_name';
$transfTokenValueKey = $TokenValueKey ?? 'token_value';
$transfTokenName = $TokenName ?? '';
$transfTokenValue = $TokenValue ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion !== '') {
    // Los campos CSRF se envían con los nombres oficiales de PHPMaker.
    // No comparar contra el token regenerado para esta petición.
    // La validación CSRF debe permanecer habilitada en PHPMaker.
        try {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $grupoPost = isset($_POST['grupo']) ? (int)$_POST['grupo'] : 0;
            if ($grupoPost > 0) {
                $grupoId = $grupoPost;
            }
            if ($accion === 'crear_grupo') {
                $nombre = trim((string)($_POST['nombre'] ?? ''));
                if ($nombre === '' || mb_strlen($nombre) > 50) {
                    throw new \Exception('Indique un nombre de grupo de hasta 50 caracteres.');
                }
                Execute('INSERT INTO transferencia_grupo (nombre) VALUES (' . transfSqlText($nombre) . ')');
                $grupoId = (int)ExecuteScalar('SELECT LAST_INSERT_ID()');
                $mensaje = 'Grupo creado correctamente.';
            } elseif ($accion === 'editar_grupo') {
                $nombre = trim((string)($_POST['nombre'] ?? ''));
                if ($id <= 0 || $nombre === '' || mb_strlen($nombre) > 50) {
                    throw new \Exception('Datos del grupo no válidos.');
                }
                Execute('UPDATE transferencia_grupo SET nombre = ' . transfSqlText($nombre) . ' WHERE id = ' . $id);
                $grupoId = $id;
                $mensaje = 'Grupo actualizado.';
            } elseif ($accion === 'eliminar_grupo') {
                if ($id <= 0) {
                    throw new \Exception('Grupo no válido.');
                }
                // MyISAM no admite transacciones. Impedimos borrar grupos con relaciones.
                if (transfExists('SELECT id FROM transferencia_grupo_cliente WHERE transferencia_grupo = ' . $id . ' LIMIT 1') ||
                    transfExists('SELECT id FROM transferencia_grupo_fabricante WHERE transferencia_grupo = ' . $id . ' LIMIT 1')) {
                    throw new \Exception('Desasocie primero los clientes y fabricantes del grupo.');
                }
                Execute('DELETE FROM transferencia_grupo WHERE id = ' . $id);
                $grupoId = 0;
                $mensaje = 'Grupo eliminado.';
            } elseif ($accion === 'agregar_cliente') {
                $cliente = (int)($_POST['cliente'] ?? 0);
                if ($grupoId <= 0 || $cliente <= 0 || !transfExists('SELECT id FROM cliente WHERE id = ' . $cliente . ' LIMIT 1') ||
                    !transfExists('SELECT id FROM transferencia_grupo WHERE id = ' . $grupoId . ' LIMIT 1')) {
                    throw new \Exception('Seleccione un grupo y un cliente válidos.');
                }
                if (transfExists('SELECT id FROM transferencia_grupo_cliente WHERE cliente = ' . $cliente . ' LIMIT 1')) {
                    throw new \Exception('Este cliente ya está asignado a un grupo de transferencistas.');
                }
                Execute('INSERT INTO transferencia_grupo_cliente (transferencia_grupo, cliente) VALUES (' . $grupoId . ', ' . $cliente . ')');
                $mensaje = 'Cliente asociado.';
            } elseif ($accion === 'editar_cliente') {
                $cliente = (int)($_POST['cliente'] ?? 0);
                if ($id <= 0 || $grupoId <= 0 || $cliente <= 0 || !transfExists('SELECT id FROM cliente WHERE id = ' . $cliente . ' LIMIT 1') ||
                    !transfExists('SELECT id FROM transferencia_grupo_cliente WHERE id = ' . $id . ' AND transferencia_grupo = ' . $grupoId . ' LIMIT 1')) {
                    throw new \Exception('Relación o cliente no válido.');
                }
                if (transfExists('SELECT id FROM transferencia_grupo_cliente WHERE cliente = ' . $cliente . ' AND id <> ' . $id . ' LIMIT 1')) {
                    throw new \Exception('Este cliente ya pertenece a otro grupo de transferencistas.');
                }
                Execute('UPDATE transferencia_grupo_cliente SET cliente = ' . $cliente . ' WHERE id = ' . $id . ' AND transferencia_grupo = ' . $grupoId);
                $mensaje = 'Cliente actualizado.';
            } elseif ($accion === 'eliminar_cliente') {
                Execute('DELETE FROM transferencia_grupo_cliente WHERE id = ' . $id . ' AND transferencia_grupo = ' . $grupoId);
                $mensaje = 'Cliente desasociado.';
            } elseif ($accion === 'agregar_fabricante' || $accion === 'editar_fabricante') {
                $fabricante = (int)($_POST['fabricante'] ?? 0);
                $descuentoTexto = trim((string)($_POST['descuento'] ?? ''));
                if (!preg_match('/^\d{1,3}(?:[\.,]\d{1,2})?$/', $descuentoTexto)) {
                    throw new \Exception('Indique un descuento numérico válido (0 a 100).');
                }
                $descuento = (float)str_replace(',', '.', $descuentoTexto);
                if ($descuento > 100 || $grupoId <= 0 || $fabricante <= 0 ||
                    !transfExists('SELECT Id FROM fabricante WHERE Id = ' . $fabricante . ' LIMIT 1') ||
                    !transfExists('SELECT id FROM transferencia_grupo WHERE id = ' . $grupoId . ' LIMIT 1')) {
                    throw new \Exception('Grupo, fabricante o descuento no válido.');
                }
                if (transfExists('SELECT id FROM transferencia_grupo_fabricante WHERE transferencia_grupo = ' . $grupoId . ' AND fabricante = ' . $fabricante . ' AND id <> ' . ($accion === 'editar_fabricante' ? $id : 0) . ' LIMIT 1')) {
                    throw new \Exception('Este fabricante ya tiene un descuento en el grupo.');
                }
                $descuentoSql = number_format($descuento, 2, '.', '');
                if ($accion === 'agregar_fabricante') {
                    Execute('INSERT INTO transferencia_grupo_fabricante (transferencia_grupo, fabricante, descuento) VALUES (' . $grupoId . ', ' . $fabricante . ', ' . $descuentoSql . ')');
                } else {
                    if ($id <= 0 || !transfExists('SELECT id FROM transferencia_grupo_fabricante WHERE id = ' . $id . ' AND transferencia_grupo = ' . $grupoId . ' LIMIT 1')) {
                        throw new \Exception('Relación de fabricante no válida.');
                    }
                    Execute('UPDATE transferencia_grupo_fabricante SET fabricante = ' . $fabricante . ', descuento = ' . $descuentoSql . ' WHERE id = ' . $id . ' AND transferencia_grupo = ' . $grupoId);
                }
                $mensaje = 'Fabricante y descuento guardados.';
            } elseif ($accion === 'eliminar_fabricante') {
                Execute('DELETE FROM transferencia_grupo_fabricante WHERE id = ' . $id . ' AND transferencia_grupo = ' . $grupoId);
                $mensaje = 'Fabricante desasociado.';
            }
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }
}

$grupos = ExecuteRows('SELECT id, nombre FROM transferencia_grupo ORDER BY nombre, id');
if ($grupoId <= 0 && count($grupos) > 0) {
    $grupoId = (int)$grupos[0]['id'];
}
$grupoActual = null;
foreach ($grupos as $grupo) {
    if ((int)$grupo['id'] === $grupoId) {
        $grupoActual = $grupo;
        break;
    }
}
if (!$grupoActual) {
    $grupoId = 0;
}
$clientes = ExecuteRows('SELECT id, nombre FROM cliente ORDER BY nombre');
$fabricantes = ExecuteRows('SELECT Id, nombre FROM fabricante ORDER BY nombre');
$grupoClientes = $grupoId ? ExecuteRows('SELECT t.id, t.cliente, c.nombre FROM transferencia_grupo_cliente t INNER JOIN cliente c ON c.id = t.cliente WHERE t.transferencia_grupo = ' . $grupoId . ' ORDER BY c.nombre') : [];
$grupoFabricantes = $grupoId ? ExecuteRows('SELECT t.id, t.fabricante, t.descuento, f.nombre FROM transferencia_grupo_fabricante t INNER JOIN fabricante f ON f.Id = t.fabricante WHERE t.transferencia_grupo = ' . $grupoId . ' ORDER BY f.nombre') : [];
// Campos CSRF generados por PHPMaker 2024.

?>
<div class="container-fluid py-3">
    <h2><i class="fas fa-users"></i> Administración de Transferencistas</h2>
    <p class="text-muted">Configure grupos, clientes asociados y descuentos adicionales por fabricante.</p>
    <?php if ($mensaje !== '') { ?><div class="alert alert-success"><?= transfEscape($mensaje) ?></div><?php } ?>
    <?php if ($error !== '') { ?><div class="alert alert-danger"><?= transfEscape($error) ?></div><?php } ?>

    <div class="card mb-4">
        <div class="card-header"><strong>1. Grupos de transferencistas</strong></div>
        <div class="card-body">
            <form method="get" class="form-inline mb-3">
                <label class="mr-2" for="grupoSeleccionado">Grupo seleccionado:</label>
                <select id="grupoSeleccionado" name="grupo" class="form-control mr-2 transf-select2" onchange="this.form.submit()">
                    <?php foreach ($grupos as $grupo) { ?>
                    <option value="<?= (int)$grupo['id'] ?>" <?= (int)$grupo['id'] === $grupoId ? 'selected' : '' ?>><?= transfEscape($grupo['nombre']) ?></option>
                    <?php } ?>
                </select>
                <noscript><button class="btn btn-primary">Seleccionar</button></noscript>
            </form>
            <div class="row">
                <div class="col-lg-5 mb-3">
                    <form method="post" class="form-inline">
                        <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>">
                        <input type="hidden" name="accion" value="crear_grupo">
                        <input type="text" name="nombre" class="form-control mr-2 mb-2" maxlength="50" placeholder="Nombre del nuevo grupo" required>
                        <button class="btn btn-primary mb-2"><i class="fas fa-plus"></i> Crear grupo</button>
                    </form>
                </div>
                <?php if ($grupoActual) { ?>
                <div class="col-lg-7 mb-3">
                    <form method="post" class="form-inline d-inline-flex mr-2 mb-2">
                        <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>">
                        <input type="hidden" name="accion" value="editar_grupo">
                        <input type="hidden" name="id" value="<?= $grupoId ?>">
                        <input type="hidden" name="grupo" value="<?= $grupoId ?>">
                        <input type="text" name="nombre" class="form-control mr-2" maxlength="50" value="<?= transfEscape($grupoActual['nombre']) ?>" required>
                        <button class="btn btn-outline-primary"><i class="fas fa-save"></i> Renombrar</button>
                    </form>
                    <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar este grupo? Solo se permite si no tiene asociaciones.');">
                        <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>">
                        <input type="hidden" name="accion" value="eliminar_grupo">
                        <input type="hidden" name="id" value="<?= $grupoId ?>">
                        <input type="hidden" name="grupo" value="<?= $grupoId ?>">
                        <button class="btn btn-outline-danger mb-2"><i class="fas fa-trash"></i> Eliminar grupo</button>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if ($grupoActual) { ?>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><strong>2. Clientes asociados</strong> <span class="badge badge-secondary"><?= count($grupoClientes) ?></span></div>
                <div class="card-body">
                    <form method="post" class="form-row align-items-end mb-3">
                        <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>">
                        <input type="hidden" name="accion" value="agregar_cliente">
                        <input type="hidden" name="grupo" value="<?= $grupoId ?>">
                        <div class="col-md-9 mb-2"><label>Cliente</label><select name="cliente" class="form-control transf-select2" required><option value="">Seleccione un cliente...</option>
                            <?php foreach ($clientes as $cliente) { ?><option value="<?= (int)$cliente['id'] ?>"><?= transfEscape($cliente['nombre']) ?></option><?php } ?>
                        </select></div>
                        <div class="col-md-3 mb-2"><button class="btn btn-success btn-block"><i class="fas fa-plus"></i> Agregar</button></div>
                    </form>
                    <div class="table-responsive"><table class="table table-bordered table-striped table-sm"><thead class="thead-light"><tr><th>Cliente</th><th style="width:145px">Acciones</th></tr></thead><tbody>
                        <?php foreach ($grupoClientes as $item) { ?>
                        <tr><td>
                            <form method="post" id="cliente<?= (int)$item['id'] ?>" class="form-inline">
                                <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>">
                                <input type="hidden" name="accion" value="editar_cliente"><input type="hidden" name="grupo" value="<?= $grupoId ?>"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                <select name="cliente" class="form-control form-control-sm w-100 transf-select2">
                                    <?php foreach ($clientes as $cliente) { ?><option value="<?= (int)$cliente['id'] ?>" <?= (int)$cliente['id'] === (int)$item['cliente'] ? 'selected' : '' ?>><?= transfEscape($cliente['nombre']) ?></option><?php } ?>
                                </select>
                            </form>
                        </td><td class="text-nowrap">
                            <button type="submit" form="cliente<?= (int)$item['id'] ?>" class="btn btn-sm btn-outline-primary" title="Guardar"><i class="fas fa-save"></i></button>
                            <form method="post" class="d-inline" onsubmit="return confirm('¿Desasociar este cliente?');">
                                <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>"><input type="hidden" name="accion" value="eliminar_cliente"><input type="hidden" name="grupo" value="<?= $grupoId ?>"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger" title="Quitar"><i class="fas fa-trash"></i></button>
                            </form>
                        </td></tr>
                        <?php } ?>
                        <?php if (!$grupoClientes) { ?><tr><td colspan="2" class="text-muted">Este grupo no tiene clientes asociados.</td></tr><?php } ?>
                    </tbody></table></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><strong>3. Fabricantes y descuentos</strong> <span class="badge badge-secondary"><?= count($grupoFabricantes) ?></span></div>
                <div class="card-body">
                    <form method="post" class="form-row align-items-end mb-3">
                        <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>"><input type="hidden" name="accion" value="agregar_fabricante"><input type="hidden" name="grupo" value="<?= $grupoId ?>">
                        <div class="col-md-6 mb-2"><label>Fabricante</label><select name="fabricante" class="form-control transf-select2" required><option value="">Seleccione...</option>
                            <?php foreach ($fabricantes as $fabricante) { ?><option value="<?= (int)$fabricante['Id'] ?>"><?= transfEscape($fabricante['nombre']) ?></option><?php } ?>
                        </select></div>
                        <div class="col-md-3 mb-2"><label>Descuento %</label><input type="number" name="descuento" min="0" max="100" step="0.01" value="0.00" class="form-control" required></div>
                        <div class="col-md-3 mb-2"><button class="btn btn-success btn-block"><i class="fas fa-plus"></i> Agregar</button></div>
                    </form>
                    <div class="table-responsive"><table class="table table-bordered table-striped table-sm"><thead class="thead-light"><tr><th>Fabricante</th><th>Descuento %</th><th>Acciones</th></tr></thead><tbody>
                        <?php foreach ($grupoFabricantes as $item) { ?>
                        <tr><td>
                            <form method="post" id="fabricante<?= (int)$item['id'] ?>">
                                <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>"><input type="hidden" name="accion" value="editar_fabricante"><input type="hidden" name="grupo" value="<?= $grupoId ?>"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                <select name="fabricante" class="form-control form-control-sm transf-select2" required>
                                    <?php foreach ($fabricantes as $fabricante) { ?><option value="<?= (int)$fabricante['Id'] ?>" <?= (int)$fabricante['Id'] === (int)$item['fabricante'] ? 'selected' : '' ?>><?= transfEscape($fabricante['nombre']) ?></option><?php } ?>
                                </select>
                            </form>
                        </td><td><input form="fabricante<?= (int)$item['id'] ?>" type="number" name="descuento" class="form-control form-control-sm" min="0" max="100" step="0.01" value="<?= transfEscape(number_format((float)$item['descuento'], 2, '.', '')) ?>" required></td>
                        <td class="text-nowrap">
                            <button type="submit" form="fabricante<?= (int)$item['id'] ?>" class="btn btn-sm btn-outline-primary" title="Guardar"><i class="fas fa-save"></i></button>
                            <form method="post" class="d-inline" onsubmit="return confirm('¿Eliminar el descuento de este fabricante?');">
                                <input type="hidden" name="<?= transfEscape($transfTokenNameKey) ?>" value="<?= transfEscape($transfTokenName) ?>"><input type="hidden" name="<?= transfEscape($transfTokenValueKey) ?>" value="<?= transfEscape($transfTokenValue) ?>"><input type="hidden" name="accion" value="eliminar_fabricante"><input type="hidden" name="grupo" value="<?= $grupoId ?>"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger" title="Quitar"><i class="fas fa-trash"></i></button>
                            </form>
                        </td></tr>
                        <?php } ?>
                        <?php if (!$grupoFabricantes) { ?><tr><td colspan="3" class="text-muted">Este grupo no tiene descuentos por fabricante.</td></tr><?php } ?>
                    </tbody></table></div>
                </div>
            </div>
        </div>
    </div>
    <?php } else { ?><div class="alert alert-info">Cree un grupo para comenzar a asociar clientes y fabricantes.</div><?php } ?>
</div>

<style>
/* Select2: tamaño y alineación con Bootstrap. */
.transf-select2 + .select2-container { width: 100% !important; max-width: 100%; }
.select2-container .select2-selection--single { min-height: 38px; }
.select2-container .select2-selection--single .select2-selection__rendered { line-height: 36px; }
.select2-container .select2-selection--single .select2-selection__arrow { height: 36px; }
</style>
<script>
(function () {
    'use strict';
    var cdnBase = 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/';
    var cargando = false;

    function iniciar() {
        if (!window.jQuery || !jQuery.fn || !jQuery.fn.select2) return false;
        jQuery('.transf-select2').each(function () {
            var campo = jQuery(this);
            if (campo.hasClass('select2-hidden-accessible')) return;
            campo.select2({
                width: '100%',
                placeholder: campo.find('option[value=""]').first().text() || 'Buscar...',
                allowClear: campo.find('option[value=""]').length > 0,
                language: { noResults: function () { return 'No se encontraron resultados'; } }
            });
        });
        return true;
    }

    function cargar() {
        if (iniciar() || cargando) return;
        if (!window.jQuery || !jQuery.fn) {
            console.error('Transferencistas: jQuery no está disponible.');
            return;
        }
        cargando = true;
        if (!document.getElementById('transf-select2-css')) {
            var css = document.createElement('link');
            css.id = 'transf-select2-css';
            css.rel = 'stylesheet';
            css.href = cdnBase + 'css/select2.min.css';
            document.head.appendChild(css);
        }
        var js = document.createElement('script');
        js.src = cdnBase + 'js/select2.min.js';
        js.onload = function () { cargando = false; iniciar(); };
        js.onerror = function () {
            cargando = false;
            console.error('Transferencistas: no se pudo cargar Select2 desde el CDN. Revise la conexión o instale Select2 localmente.');
        };
        document.head.appendChild(js);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', cargar);
    } else {
        cargar();
    }
    // Compatible con páginas cuyo contenido PHPMaker se actualiza dinámicamente.
    if (window.jQuery) {
        jQuery(document).on('shown.bs.modal', iniciar);
    }
})();
</script>

<?= GetDebugMessage() ?>
