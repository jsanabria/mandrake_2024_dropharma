<?php

namespace PHPMaker2024\mandrake;

// Page object
$ListadoMaster = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = $_GET["id"] ?? '';
$url = "listado_master_buscar.php";

// Mapeo para mantener los títulos descriptivos en la interfaz de forma limpia
$titulos_reportes = [
    "ims_clientes"                => "EXPORTAR CLIENTES IMS",
    "ims_articulos"               => "EXPORTAR ARTICULOS IMS",
    "ims_facturas"                => "EXPORTAR FACTURAS IMS",
    "tax_libro_compra"            => "LIBRO DE COMPRAS",
    "tax_libro_venta"             => "LIBRO DE VENTAS",
    "aud_costo_vs_precio"         => "FACTURAS COSTOS VS PRECIO",
    "inv_kardex"                  => "KARDEX DE INVENTARIO",
    "det_entradas_general"        => "ENTRADAS GENERALES POR ARTICULO DETALLADO",
    "det_pedidos_venta"           => "PEDIDOS DE VENTAS DETALLADO",
    "det_notas_entrega"           => "ORDENES DE ENTREGA DETALLADO",
    "vta_laboratorio"             => "VENTAS POR FABRICANTE (FACTURAS)",
    "vta_articulo"                => "VENTAS POR ARTICULO (FACTURAS)",
    "vta_articulo_utilidad"       => "VENTAS POR ARTICULO (FACTURAS UTILIDAD NETA)",
    "sal_laboratorio"             => "SALIDAS GENERALES POR FABRICANTE (FACTURAS + AJUSTE SALIDAS)",
    "sal_articulo"                => "SALIDAS GENERALES POR ARTICULO (FACTURAS + AJUSTE SALIDAS)",
    "vta_cliente"                 => "VENTAS POR CLIENTE (FACTURAS SIN IVA Y CANTIDAD DE UNIDADES)",
    "sal_articulo_detallado"      => "SALIDAS GENERALES POR ARTICULO DETALLADO",
    "cng_cliente"                 => "CONSIGNACIONES POR CLIENTE",
    "cng_facturas"                => "FACTURAS POR CONSIGNACION",
    "cli_compras_recientes"       => "CLIENTES CON COMPRAS RECIENTES",
    "cli_sin_compras"             => "CLIENTES SIN COMPRAS RECIENTES",
    "inv_entre_fechas"            => "INVENTARIO ENTRE FECHA",
    "cng_descarga_entradas"       => "DESCARGA ENTRADAS A CONSIGNACION",
    "sal_articulo_neas"           => "SALIDAS GENERALES POR ARTICULO (NOTAS + AJUSTE SALIDAS)"
];

$titulo = isset($titulos_reportes[$id]) ? $titulos_reportes[$id] : $id;
?>

<!-- IMPORTANTE: jQuery SIEMPRE debe cargarse de primero antes que cualquier otra librería dependiente -->
<script type="text/javascript" src="jquery/jquery-3.6.0.js"></script>

<!-- Carga de Bootstrap Icons y recursos necesarios -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Hojas de estilo y scripts de Select2 para mejorar la experiencia de búsqueda en desplegables -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Estilo personalizado para adaptar Select2 perfectamente al diseño nativo de Bootstrap 5 -->
<style type="text/css">
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 0.375rem !important;
        padding: 5px 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px !important;
        color: #212529 !important;
        padding-left: 0px !important;
    }
</style>

<div class="container my-4">
    <!-- Fila de Navegación superior -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button type="button" class="btn btn-outline-secondary" id="regresar" name="regresar" onClick="window.history.back();">
            <i class="bi bi-arrow-left"></i> Volver al menú
        </button>
        <span class="badge bg-primary px-3 py-2 fs-6">Módulo de Reportes</span>
    </div>

    <!-- Tarjeta Principal del Reporte -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white p-3">
            <h4 class="card-title mb-0 d-flex align-items-center">
                <i class="bi bi-file-earmark-bar-graph me-2"></i> 
                <span><?php echo htmlspecialchars($titulo); ?></span>
            </h4>
        </div>
        
        <div class="card-body p-4">
            <form id="formReporte">
                <h5 class="text-secondary border-bottom pb-2 mb-3">Parámetros de Búsqueda</h5>
                
                <div class="row g-3 align-items-end">
                    <!-- Rango de Fechas Desde -->
                    <div class="col-12 col-md-3">
                        <label for="fecha_desde" class="form-label text-muted fw-semibold small">Desde:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar3"></i></span>
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                        </div>
                    </div>
                    
                    <!-- Rango de Fechas Hasta -->
                    <div class="col-12 col-md-3">
                        <label for="fecha_hasta" class="form-label text-muted fw-semibold small">Hasta:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar3"></i></span>
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                        </div>
                    </div>
                    
                    <!-- Botón de acción principal -->
                    <div class="col-12 col-md-2">
                        <button type="button" class="btn btn-primary w-100 py-2 shadow-sm" id="buscar" name="buscar">
                            <i class="bi bi-search me-1"></i> Consultar
                        </button>
                    </div>

                    <!-- Selector Dinámico Principal (Agregada clase select2-enable para Categorías, Laboratorios, etc.) -->
                    <div class="col-12 col-md-4">
                        <label for="tipo" class="form-label text-muted fw-semibold small">Filtrar Almacen / Cliente / Fabricante /Art&iacute;culo:</label>
                        <select id="tipo" name="tipo" class="form-select fw-normal select2-enable">
                            <option value="">-- TODOS --</option>
                            <?php
                            if (in_array($id, ["ims_clientes", "ims_articulos", "ims_facturas"])) {
                                $sql = "SELECT id, nombre FROM tarifa WHERE activo = 'S';";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
                                }
                            }
                            elseif ($id == "tax_libro_venta") {
                                echo '<option value="FC">FACTURA</option>';
                                echo '<option value="NC">NOTA DE CREDITO</option>';
                                echo '<option value="ND">NOTA DE DEBITO</option>';
                            }
                            elseif (in_array($id, ["vta_laboratorio", "sal_laboratorio", "cng_descarga_entradas"])) {
                                $sql = "SELECT id, nombre FROM fabricante ORDER BY nombre;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
                                }
                            }
                            elseif (in_array($id, ["vta_articulo", "sal_articulo", "sal_articulo_detallado", "det_entradas_general", "det_notas_entrega", "det_pedidos_venta", "vta_articulo_utilidad", "sal_articulo_neas"])) {
                                $sql = "SELECT 
                                            a.id, 
                                            CONCAT(IFNULL(a.nombre_comercial, ''), ' ', IFNULL(a.principio_activo, ''), ' ', IFNULL(a.presentacion, ''), ' - ', IFNULL(a.codigo, '')) AS nombre 
                                        FROM articulo AS a 
                                        ORDER BY a.nombre_comercial, a.principio_activo, a.presentacion;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
                                }
                            }
                            elseif (in_array($id, ["cng_cliente", "cng_facturas"])) {
                                $sql = "SELECT DISTINCT a.cliente, b.nombre 
                                        FROM salidas AS a 
                                        JOIN cliente AS b ON b.id = a.cliente 
                                        WHERE a.consignacion = 'S' AND a.tipo_documento IN ('TDCNET', 'TDCFCV') AND a.estatus <> 'ANULADO' 
                                        ORDER BY b.nombre;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["cliente"] . '">' . $row["nombre"] . '</option>';
                                }
                            }
                            elseif ($id == "inv_entre_fechas") {
                                $sql = "SELECT codigo, descripcion FROM almacen WHERE movimiento = 'S' ORDER BY descripcion;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["codigo"] . '">' . $row["descripcion"] . '</option>';
                                }
                            }
                            elseif (in_array($id, ["cli_compras_recientes", "cli_sin_compras", "vta_cliente"])) {
                                $sql = "SELECT id, CONCAT(IFNULL(nombre, ''), ' - ', IFNULL(ci_rif, '')) AS nombre FROM cliente ORDER BY nombre;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">' . $row["nombre"] . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                </div>

                <!-- Bloques condicionales secundarios -->
                <?php if ($id == "tax_libro_venta"): ?>
                    <div class="row g-3 mt-2 pt-3 border-top border-light" id="xCliente">
                        <!-- Selector de Clientes con Select2 -->
                        <div class="col-12 col-md-6">
                            <label for="cliente" class="form-label text-muted fw-semibold small">Cliente específico:</label>
                            <select id="cliente" name="cliente" class="form-select select2-enable">
                                <option value="">-- Todos los Clientes --</option>
                                <?php
                                $sql = "SELECT id, nombre FROM cliente ORDER BY nombre;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">' . htmlspecialchars(substr($row["nombre"], 0, 45)) . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Selector de Asesores con Select2 -->
                        <div class="col-12 col-md-6">
                            <label for="asesor" class="form-label text-muted fw-semibold small">Asesor comercial:</label>
                            <select id="asesor" name="asesor" class="form-select select2-enable">
                                <option value="">-- Todos los Asesores --</option>
                                <?php
                                // $sql = "SELECT id, nombre FROM asesor ORDER BY nombre;";
                                $sql = "SELECT 
                                            a.username AS id, CONCAT(IFNULL(username, ''), ' - ', IFNULL(a.nombre, '')) AS nombre 
                                        FROM 
                                            usuario AS a 
                                        ORDER BY a.nombre;";
                                $rows = ExecuteRows($sql);
                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">' . htmlspecialchars(substr($row["nombre"], 0, 45)) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($id == "inv_entre_fechas"): ?>
                    <div class="row g-3 mt-2 pt-3 border-top border-light" id="xArticulo">

                        <!-- Selector de Artículos con Select2 -->
                        <div class="col-12">
                            <label for="articulo" class="form-label text-muted fw-semibold small">
                                Artículo específico:
                            </label>

                            <select id="articulo" name="articulo" class="form-select select2-enable">
                                <option value="">-- Todos los Artículos --</option>

                                <?php
                                $sql = "SELECT  
                                            a.id, 
                                            CONCAT(
                                                IFNULL(a.nombre_comercial, ''), ' ',
                                                IFNULL(a.principio_activo, ''), ' ',
                                                IFNULL(a.presentacion, ''), ' - ',
                                                IFNULL(a.codigo, '')
                                            ) AS nombre 
                                        FROM articulo AS a 
                                        ORDER BY 
                                            a.nombre_comercial,
                                            a.principio_activo,
                                            a.presentacion;";

                                $rows = ExecuteRows($sql);

                                foreach ($rows as $row) {
                                    echo '<option value="' . $row["id"] . '">'
                                        . htmlspecialchars(substr($row["nombre"], 0, 120))
                                        . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                <?php endif; ?>

            </form>
        </div>
    </div>

    <!-- Zona de Resultados -->
    <div class="mt-4">
        <div id="result"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Función asíncrona segura para inicializar Select2 únicamente cuando esté cargada la librería en jQuery
        function inicializarSelect2Seguro() {
            if (typeof $.fn.select2 !== 'undefined') {
                if ($('.select2-enable').length > 0) {
                    $('.select2-enable').select2({
                        width: '100%',
                        placeholder: function() {
                            return $(this).find('option[value=""]').text() || 'Seleccione una opción';
                        },
                        allowClear: true
                    });
                }
            } else {
                // Si aún no está cargada, reintentamos en 50 milisegundos de forma transparente
                setTimeout(inicializarSelect2Seguro, 50);
            }
        }

        // Ejecutar inicialización protectora
        inicializarSelect2Seguro();
    });

    $("#buscar").click(function(){
        var fecha_desde = $("#fecha_desde").val();
        var fecha_hasta = $("#fecha_hasta").val();
        var tipo        = $("#tipo").val();
        var articulo    = $("#articulo").val();
        var cliente     = $("#cliente").val() || "";
        var asesor      = $("#asesor").val() || "";

        if(fecha_desde == "" || fecha_hasta == "") {
            ew.alert("¡Por favor, elija un rango de fechas válido!");
            return false;
        }

        $.ajax({
            url : "<?php echo $url; ?>",
            type: "GET",
            data : {
                id: '<?php echo $id; ?>', 
                fecha_desde: fecha_desde, 
                fecha_hasta: fecha_hasta, 
                tipo: tipo, 
                articulo: articulo, 
                proveedor: 0, 
                cliente: cliente, 
                asesor: asesor
            },
            beforeSend: function(){
                // Renderizado de un Spinner elegante de Bootstrap en lugar de un texto plano
                $("#result").html(`
                    <div class="d-flex align-items-center justify-content-center p-5 text-secondary">
                        <div class="spinner-border text-primary me-3" role="status"></div>
                        <span class="fs-5 fw-semibold">Procesando y organizando el reporte...</span>
                    </div>
                `);
            }
        })
        .done(function(data) {
            $("#result").html(data);
        })
        .fail(function(xhr, status, error) {
            $("#result").html(`
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
                    <div>Ocurrió un error inesperado al procesar los datos: <strong>${error}</strong></div>
                </div>
            `);
        });
    });
</script>
<?= GetDebugMessage() ?>
