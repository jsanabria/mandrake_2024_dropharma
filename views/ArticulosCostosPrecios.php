<?php

namespace PHPMaker2024\mandrake;

// Page object
$ArticulosCostosPrecios = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// Este script asume que las funciones Execute(), ExecuteRow(), ExecuteScalar() y la conexión 
// a la base de datos están disponibles en el entorno PHPMaker.

// Variables de búsqueda
$search_code = $_POST['search_code'] ?? '';
$search_principio = $_POST['search_principio'] ?? '';
$message = '';

## ----------------------------------------------------
## 1. Lógica de ACTUALIZACIÓN (Guardar costos y precios)
## ----------------------------------------------------
if (isset($_POST['action']) && $_POST['action'] === 'update_costs') {
    $ids = $_POST['articulo_id'] ?? [];
    $costos = $_POST['ultimo_costo'] ?? [];
    $precios = $_POST['precio'] ?? [];
    $updated_count = 0;

    // Si estamos actualizando, las variables de búsqueda vienen de los campos ocultos del formulario
    $search_code = $_POST['hidden_search_code'] ?? '';
    $search_principio = $_POST['hidden_search_principio'] ?? '';

    // Buscar la tarifa patrón (lógica original)
    // Es CRÍTICO usar sentencias preparadas o sanitización extrema, aquí asumo que ExecuteScalar es seguro.
    $sql_tarifa_patron = "SELECT id FROM tarifa WHERE patron = 'S'";
    $tarifa_patron = ExecuteScalar($sql_tarifa_patron);

    if (count($ids) > 0 && $tarifa_patron !== false) {
        foreach ($ids as $key => $articulo_id) {
            $articulo_id = intval($articulo_id);
            // Sanitización y conversión de valores
            $costo = floatval(str_replace(',', '.', $costos[$key])); // Asegura formato numérico
            $precio = floatval(str_replace(',', '.', $precios[$key]));

            if ($articulo_id > 0) {
                // 1. Actualizar la tabla ARTICULO (SQL inyectado por simplicidad, se recomienda PREPARE/BIND)
                // Se utiliza una sintaxis SQL directa con los valores ya sanitizados a float/int
                $sql_update_articulo = "
                    UPDATE articulo 
                    SET ultimo_costo = $costo, precio = $precio 
                    WHERE id = $articulo_id
                ";
                if (Execute($sql_update_articulo)) {
                    $updated_count++;

                    // 2. Lógica para actualizar/insertar en TARIFA_ARTICULO
                    
                    // a) Verificar si ya existe en tarifa_articulo
                    $sql_check_tarifa = "
                        SELECT articulo 
                        FROM tarifa_articulo 
                        WHERE tarifa = $tarifa_patron AND articulo = $articulo_id
                    ";
                    
                    if (ExecuteRow($sql_check_tarifa)) {
                        // b) Si existe: UPDATE
                        $sql_update_tarifa = "
                            UPDATE tarifa_articulo 
                            SET precio = $precio 
                            WHERE tarifa = $tarifa_patron AND articulo = $articulo_id
                        ";
                        Execute($sql_update_tarifa);
                    } else {
                        // c) Si no existe: INSERT
                        $sql_insert_tarifa = "
                            INSERT INTO tarifa_articulo (id, tarifa, fabricante, articulo, precio)
                            SELECT NULL, $tarifa_patron, fabricante, id, $precio 
                            FROM articulo 
                            WHERE id = $articulo_id
                        ";
                        Execute($sql_insert_tarifa);
                    }
                }
            }
        }
        $message = '<div class="alert alert-success" role="alert">✅ Se actualizaron **' . $updated_count . '** artículos con éxito.</div>';
    } else {
        $message = '<div class="alert alert-warning" role="alert">⚠️ No se encontró la tarifa patrón o no se enviaron datos.</div>';
    }
}

## ----------------------------------------------------
## 2. Configuración y Lógica de Búsqueda
## ----------------------------------------------------

$where = '1';
$params = []; // En caso de usar parámetros para ExecuteRows/ExecuteQuery

if (!empty($search_code)) {
    // Busca por código exacto o similar
    $where .= ' AND a.codigo LIKE \'%'. adjustSql($search_code) . '%\'';
}

if (!empty($search_principio)) {
    // Busca por principio activo similar
    $where .= ' AND a.principio_activo LIKE \'%'. adjustSql($search_principio) . '%\'';
}

// Query base para listar
$sql_list = "
    SELECT 
        a.id, a.codigo, b.nombre AS fabricante, 
        a.principio_activo AS articulo_nombre, a.cantidad_en_mano, 
        a.ultimo_costo AS costo, a.precio
    FROM 
        articulo AS a 
        LEFT OUTER JOIN fabricante AS b ON b.Id = a.fabricante 
    WHERE 
        $where
    ORDER BY a.principio_activo LIMIT 0, 100
";

// Ejecutar la consulta y obtener los resultados
$rs = ExecuteRows($sql_list); 
$total_articulos = count($rs);
?>

<div class="container-fluid">
    <h2>📋 Actualización Masiva de Costos y Precios</h2>
    <hr>
    
    <?php echo $message; ?>

    <div class="card card-default mb-4">
        <div class="card-header">
            <h3 class="card-title">Filtros de Búsqueda</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="search_code">Código:</label>
                        <input type="text" class="form-control form-control-sm" id="search_code" name="search_code" value="<?php echo htmlspecialchars($search_code ?? ""); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="search_principio">Principio Activo / Nombre Artículo:</label>
                        <input type="text" class="form-control form-control-sm" id="search_principio" name="search_principio" value="<?php echo htmlspecialchars($search_principio ?? ""); ?>">
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm btn-block">🔎 Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form method="POST" action="">
        <input type="hidden" name="action" value="update_costs">

        <input type="hidden" name="hidden_search_code" value="<?php echo htmlspecialchars($search_code ?? ""); ?>">
        <input type="hidden" name="hidden_search_principio" value="<?php echo htmlspecialchars($search_principio ?? ""); ?>">

        <?php if ($total_articulos > 0) { ?>
            <div class="mb-3 text-right">
                <p class="text-muted small">Mostrando **<?php echo $total_articulos; ?>** resultados. Edite los campos y presione **Guardar**.</p>
                <button type="submit" class="btn btn-success" name="save_changes">💾 Guardar Costos y Precios</button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Código</th>
                            <th scope="col">Fabricante</th>
                            <th scope="col">Artículo / P. Activo</th>
                            <th scope="col" class="text-center">Stock</th>
                            <th scope="col" class="text-center" style="width: 15%;">Último Costo</th>
                            <th scope="col" class="text-center" style="width: 15%;">Precio Venta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rs as $row) { 
                            $articulo_id = htmlspecialchars($row['id'] ?? "");
                        ?>
                            <tr>
                                <td><?php echo $articulo_id; ?>
                                    <input type="hidden" name="articulo_id[]" value="<?php echo $articulo_id; ?>">
                                </td>
                                <td><?php echo htmlspecialchars($row['codigo'] ?? ""); ?></td>
                                <td><?php echo htmlspecialchars($row['fabricante'] ?? ""); ?></td>
                                <td><?php echo htmlspecialchars($row['articulo_nombre'] ?? ""); ?></td>
                                <td class="text-center"><?php echo number_format($row['cantidad_en_mano'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm text-right" 
                                           name="ultimo_costo[]" value="<?php echo number_format($row['costo'], 2, '.', ''); ?>" required>
                                </td>
                                <td class="text-center">
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm text-right" 
                                           name="precio[]" value="<?php echo number_format($row['precio'], 2, '.', ''); ?>" required>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-right">
                <button type="submit" class="btn btn-success" name="save_changes">💾 Guardar Costos y Precios</button>
            </div>

        <?php } elseif (!empty($search_code) || !empty($search_principio)) { ?>
            <div class="alert alert-info mt-4" role="alert">
                ℹ️ No se encontraron artículos que coincidan con los criterios de búsqueda.
            </div>
        <?php } else { ?>
            <div class="alert alert-info mt-4" role="alert">
                Por favor, utilice los filtros de búsqueda para listar los artículos que desea actualizar.
            </div>
        <?php } ?>
    </form>
</div>
<?= GetDebugMessage() ?>
