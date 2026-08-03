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
    "fin_resumen"                 => "RESUMEN FINANCIERO",
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
    "sal_articulo_neas"           => "SALIDAS GENERALES POR ARTICULO (NOTAS + AJUSTE SALIDAS)",
    "REPX"                        => "REPORTE X",
    "REPZ"                        => "REPORTE Z",
    "INFO"                        => "REPORTE INFO",
    "INFOJSON"                    => "REPORTE INFOJSON",
    "CLOSE"                       => "RECUPERAR IMPRESORA"
];

$titulo = isset($titulos_reportes[$id]) ? $titulos_reportes[$id] : $id;

$reportesFiscales = ["REPX","REPZ","INFO","INFOJSON","CLOSE"];
$esReporteFiscal = in_array($id, $reportesFiscales);
?>

<!-- IMPORTANTE: jQuery SIEMPRE debe cargarse de primero antes que cualquier otra librería dependiente -->
<!-- <script type="text/javascript" src="jquery/jquery-3.6.0.js"></script> -->

<!-- Carga de Bootstrap Icons y recursos necesarios -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Hojas de estilo y scripts de Select2 para mejorar la experiencia de búsqueda en desplegables -->
<?php if (!$esReporteFiscal): ?>
<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
/>
<?php endif; ?>

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
                    <div class="col-12 col-md-3 <?= $esReporteFiscal ? "d-none" : "" ?>">
                        <label for="fecha_desde" class="form-label text-muted fw-semibold small">Desde:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar3"></i></span>
                            <input type="date" class="form-control" id="fecha_desde" name="fecha_desde">
                        </div>
                    </div>
                    
                    <!-- Rango de Fechas Hasta -->
                    <div class="col-12 col-md-3 <?= $esReporteFiscal ? "d-none" : "" ?>">
                        <label for="fecha_hasta" class="form-label text-muted fw-semibold small">Hasta:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar3"></i></span>
                            <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                        </div>
                    </div>
                    
                    <!-- Botón de acción principal -->
                    <div class="<?= $esReporteFiscal ? "col-12" : "col-12 col-md-2" ?>">
                        <button type="button" class="btn btn-primary w-100 py-2 shadow-sm" id="buscar" name="buscar">
                            <i class="bi bi-search me-1"></i> Consultar
                        </button>
                    </div>

                    <!-- Selector Dinámico Principal (Agregada clase select2-enable para Categorías, Laboratorios, etc.) -->
                    <?php if (!$esReporteFiscal): ?>
                    <div class="col-12 col-md-4">
                        <label for="tipo" class="form-label text-muted fw-semibold small">
                            <?= $id == "fin_resumen"
                                ? "Tipo de movimiento:"
                                : "Filtrar Almacén / Cliente / Fabricante / Artículo:" ?>
                        </label>
                        <select id="tipo" name="tipo" class="form-select fw-normal select2-enable">
                            <option value="">-- TODOS --</option>
                            <?php
                            if ($id == "fin_resumen") {
                                echo '<option value="COMPRAS">COMPRAS</option>';
                                echo '<option value="VENTAS">VENTAS</option>';
                                echo '<option value="GIROS">GIROS</option>';
                            }
                            elseif (in_array($id, ["ims_clientes", "ims_articulos", "ims_facturas"])) {
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
                <?php endif; ?>

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
                                        . htmlspecialchars(substr($row["nombre"], 0, 300))
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
(function () {

    var esReporteFiscal = <?= $esReporteFiscal ? "true" : "false" ?>;
    var select2Cargando = false;

    /**
     * Espera hasta que PHPMaker haya cargado jQuery.
     */
    function esperarJQueryListadoMaster(callback) {
        if (
            typeof window.jQuery !== "undefined" &&
            typeof window.jQuery.fn !== "undefined"
        ) {
            callback(window.jQuery);
            return;
        }

        setTimeout(function () {
            esperarJQueryListadoMaster(callback);
        }, 50);
    }

    /**
     * Carga Select2 solamente después de que jQuery esté disponible.
     */
    function cargarSelect2ListadoMaster($, callback) {

        if (esReporteFiscal) {
            callback();
            return;
        }

        // Select2 ya está cargado.
        if (typeof $.fn.select2 === "function") {
            callback();
            return;
        }

        // Evita insertar varias veces el mismo archivo.
        if (select2Cargando) {
            setTimeout(function () {
                cargarSelect2ListadoMaster($, callback);
            }, 50);

            return;
        }

        select2Cargando = true;

        var script = document.createElement("script");

        script.src =
            "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js";

        script.onload = function () {
            select2Cargando = false;
            callback();
        };

        script.onerror = function () {
            select2Cargando = false;

            console.error(
                "No fue posible cargar la librería Select2."
            );

            // El reporte continuará funcionando sin Select2.
            callback();
        };

        document.head.appendChild(script);
    }

    /**
     * Inicializa todos los select marcados para utilizar Select2.
     */
    function inicializarSelect2ListadoMaster($) {

        if (
            esReporteFiscal ||
            typeof $.fn.select2 !== "function"
        ) {
            return;
        }

        $(".select2-enable").each(function () {

            var $select = $(this);

            // Evita inicializar dos veces el mismo elemento.
            if ($select.hasClass("select2-hidden-accessible")) {
                return;
            }

            var textoInicial =
                $select.find('option[value=""]').first().text();

            $select.select2({
                width: "100%",
                placeholder: textoInicial || "Seleccione una opción",
                allowClear: true
            });
        });
    }

    /**
     * Registra el evento del botón Consultar.
     */
    function iniciarEventosListadoMaster($) {

        $("#buscar")
            .off("click.listadoMaster")
            .on("click.listadoMaster", function () {

                var fecha_desde = $("#fecha_desde").val() || "";
                var fecha_hasta = $("#fecha_hasta").val() || "";

                var tipo = $("#tipo").length
                    ? $("#tipo").val()
                    : "";

                var articulo = $("#articulo").length
                    ? $("#articulo").val()
                    : "";

                var cliente = $("#cliente").length
                    ? $("#cliente").val()
                    : "";

                var asesor = $("#asesor").length
                    ? $("#asesor").val()
                    : "";

                if (!esReporteFiscal) {
                    if (fecha_desde === "" || fecha_hasta === "") {

                        if (
                            typeof window.ew !== "undefined" &&
                            typeof window.ew.alert === "function"
                        ) {
                            ew.alert(
                                "¡Por favor, elija un rango de fechas válido!"
                            );
                        } else {
                            alert(
                                "¡Por favor, elija un rango de fechas válido!"
                            );
                        }

                        return false;
                    }
                }

                $.ajax({
                    url: esReporteFiscal
                        ? "reportes/factura_fiscal_reportes.php"
                        : <?= json_encode($url) ?>,

                    type: "GET",

                    data: {
                        id: <?= json_encode($id) ?>,
                        documento: <?= json_encode($id) ?>,
                        fecha_desde: fecha_desde,
                        fecha_hasta: fecha_hasta,
                        tipo: tipo,
                        articulo: articulo,
                        proveedor: 0,
                        cliente: cliente,
                        asesor: asesor
                    },

                    beforeSend: function () {
                        $("#result").html(`
                            <div class="d-flex align-items-center justify-content-center p-5 text-secondary">
                                <div class="spinner-border text-primary me-3"
                                     role="status">
                                </div>

                                <span class="fs-5 fw-semibold">
                                    Procesando y organizando el reporte...
                                </span>
                            </div>
                        `);
                    }
                })
                .done(function (data) {
                    $("#result").html(data);
                })
                .fail(function (xhr, status, error) {

                    var mensaje = error || status || "Error desconocido";

                    $("#result").html(`
                        <div class="alert alert-danger d-flex align-items-center"
                             role="alert">

                            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>

                            <div>
                                Ocurrió un error inesperado al procesar los datos:
                                <strong></strong>
                            </div>
                        </div>
                    `);

                    $("#result strong").text(mensaje);
                });

                return false;
            });
    }

    /**
     * Punto único de inicio.
     */
    esperarJQueryListadoMaster(function ($) {

        iniciarEventosListadoMaster($);

        cargarSelect2ListadoMaster($, function () {
            inicializarSelect2ListadoMaster($);
        });
    });

})();
</script>
<?= GetDebugMessage() ?>
