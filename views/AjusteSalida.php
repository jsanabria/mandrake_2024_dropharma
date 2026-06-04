<?php

namespace PHPMaker2024\mandrake;

// Page object
$AjusteSalida = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
$codcli = isset($_REQUEST["codcli"]) ? intval($_REQUEST["codcli"]) : 0;
$tipo_documento = isset($_REQUEST["tipo_documento"]) ? $_REQUEST["tipo_documento"] : "TDCASA";

// NOTA: Asegurar que ExecuteScalar escape internamente $tipo_documento
$titulo = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo_documento';");
$username = CurrentUserName();

if($id == 0) {
    $sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
    $row = ExecuteRow($sql); 
    $tasa = floatval($row["tasa"]);

    $sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
    $row = ExecuteRow($sql);
    $moneda = $row["moneda"];

    $sql = "SELECT MAX(CAST(IFNULL(nro_documento, 0) AS UNSIGNED)) AS cosecutivo FROM salidas WHERE tipo_documento = '$tipo_documento';";
    $consecutivo = intval(ExecuteScalar($sql)) + 1;
    $nro_documento = str_pad($consecutivo, 7, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO salidas
                (id, tipo_documento, username, fecha,
                cliente, nro_documento,
                nota, estatus, moneda, factura, tasa_dia)
            VALUES 
                (NULL, '$tipo_documento', '$username', '" . date("Y-m-d H:i:s") . "',
                $codcli, '$nro_documento',
                '', 'NUEVO', '$moneda', 'N', $tasa);"; 
    Execute($sql);

    $row = ExecuteRow("SELECT LAST_INSERT_ID() AS id;");
    $id = intval($row["id"]);
}

$sql = "SELECT 
    DATE_FORMAT(a.fecha, '%d/%m/%Y') AS fecha_factura, 
    c.descripcion AS tipo, b.nombre AS cliente, a.nro_documento, 
    a.fecha, a.tipo_documento, a.nota, a.factura, 
    a.ci_rif, a.nombre, a.direccion, a.telefono, a.email, a.tasa_dia, IFNULL(a.descuento, 0) AS descuento  
FROM 
    salidas AS a 
    LEFT OUTER JOIN cliente AS b ON b.id = a.cliente 
    LEFT OUTER JOIN tipo_documento AS c ON c.codigo = a.tipo_documento 
WHERE 
    a.id = '$id';"; 
$row = ExecuteRow($sql);

$fecha = $row["fecha_factura"];
$tipo = $row["tipo"];
$tipo_documento = $row["tipo_documento"];
$cliente = $row["cliente"];
$nro_documento = $row["nro_documento"];
$nota = $row["nota"];
$factura = $row["factura"]; 
$ci_rif = $row["ci_rif"]; 
$nombre = $row["nombre"]; 
$direccion = $row["direccion"]; 
$telefono = $row["telefono"]; 
$email = $row["email"];
$tasa_dia = floatval($row["tasa_dia"]); 
$descuento = floatval($row["descuento"]);
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" /> 

<div class="container-fluid py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <h1 class="h4 text-secondary mb-0"><?= htmlspecialchars($titulo) ?></h1>
        <div>
            <button type="button" class="btn btn-sm btn-primary" onclick="ActualizarCabecera();">
                <i class="fa fa-save"></i> Guardar Cabecera
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="VaciarCesta();">
                <i class="fa fa-trash"></i> Vaciar Cesta
            </button>
            <?php if($factura == "S"): ?>
                <a class="btn btn-sm btn-info text-white" target="_blank" href="reportes/nota_de_entrega.php?id=<?= $id ?>&tipo=<?= urlencode($tipo_documento) ?>&con_precio=S">
                    <i class="fa fa-print"></i> Imprimir Orden de Entrega
                </a>
            <?php endif; ?>
            <a class="btn btn-sm btn-info text-white" href="ViewOutTdcnetList">
                <i class="fa fa-list"></i> Listar Ordenes de Entrega
            </a>
        </div>
    </div>

    <!-- FILA 1 -->
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body p-3">
                    <!-- Primera línea -->
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Fecha</label>
                            <input type="text"
                                   class="form-control form-control-sm bg-light"
                                   value="<?= $fecha ?>"
                                   readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">Nro Documento</label>
                            <input type="text"
                                   class="form-control form-control-sm bg-light fw-bold text-primary"
                                   value="<?= $nro_documento ?>"
                                   readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold mb-1">¿Definir Datos de Env&iacute;o?</label>
                            <select id="factura"
                                    name="factura"
                                    class="form-select form-select-sm">
                                <option value="S" <?= $factura=="S"?"selected":""; ?>>SI</option>
                                <option value="N" <?= $factura=="N"?"selected":""; ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <!-- Segunda línea -->
                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold mb-1">Cliente</label>
                            <textarea class="form-control form-control-sm bg-light fw-bold text-primary" rows="1" readonly="yes"><?= htmlspecialchars($cliente) ?></textarea>
                        </div>
                    </div>

                    <!-- Tercera línea -->
                    <div class="row g-2 mt-1">
                        <div class="col-12">
                            <label class="form-label small fw-bold mb-1">Nota / Observación</label>
                            <textarea id="nota" name="nota" class="form-control form-control-sm" rows="1"><?= htmlspecialchars($nota ?? ""); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3" id="DatosFactura" style="display:none;">
                <div class="card-header bg-light py-2"><span class="small fw-bold text-secondary">Información de Facturación / Despacho</span></div>
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small mb-1">CI / RIF <span class="text-danger">*</span></label>
                            <input type="text" id="ci_rif" name="ci_rif" class="form-control form-control-sm" value="<?= htmlspecialchars($ci_rif ?? ""); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Teléfono <span class="text-danger">*</span></label>
                            <input type="text" id="telefono" name="telefono" class="form-control form-control-sm" value="<?= htmlspecialchars($telefono ?? ""); ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small mb-1">Nombre / Razón Social <span class="text-danger">*</span></label>
                            <textarea type="text" id="nombre" name="nombre" class="form-control form-control-sm" rows="1"><?= htmlspecialchars($nombre ?? ""); ?></textarea>
                        </div>
                            <label class="form-label small mb-1">Dirección Fiscal <span class="text-danger">*</span></label>
                        <div class="col-12">
                            <textarea type="text" id="direccion" name="direccion" class="form-control form-control-sm" rows="1"><?= htmlspecialchars($direccion ?? ""); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3 border-primary">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-primary mb-1">
                                <i class="fa fa-barcode"></i> Buscar Artículo por Nombre o Código
                            </label>
                            <select id="findme" name="findme" class="form-select form-select-sm" style="width: 100%;">
                                <option value="">Escriba para buscar...</option>
                            </select>
                        </div>
                    </div>
                    <div id="ResultadoBusqueda" class="mt-2 small"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4" id="ContenedorFinancieroDinamico">

        </div>
    </div>

    <!-- FILA 2 -->
    <div class="row mt-3">

        <div class="col-12">
            <div id="ResultadoLote" class="mb-3"></div>
            <div class="card shadow-sm" id="ContenedorCestaDinamica"></div>
        </div>

    </div>
</div>


<input type="hidden" id="id" value="<?= $id; ?>">
<input type="hidden" id="cantidad_items" value="<?= $cantidad; ?>">
<input type="hidden" id="username" value="<?= htmlspecialchars($username); ?>">

<div class="modal fade" id="modalAutorizarDescuento" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title">Autorización de Descuento</h5>
            </div>

            <div class="modal-body">
                <div class="alert alert-warning py-2 small">
                    Para modificar Precios y Descuentos debe ingresar usuario autorizador.
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control form-control-sm" id="auth_user_descuento" placeholder="Usuario Autorizador">
                </div>

                <div class="mb-3">
                    <input type="password" class="form-control form-control-sm" id="auth_pass_descuento" placeholder="Password">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" id="btnCancelarDescuento">Cancelar</button>
                <button type="button" class="btn btn-sm btn-success" id="btnAceptarDescuento">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        // Manejo inicial y cambio del panel de facturación
        toggleDatosFactura($("#factura").val());
        $("#factura").change(function() {
            toggleDatosFactura($(this).val());
        });

        // Inicialización de Select2 para búsqueda de artículos remota por AJAX
        $("#findme").select2({
            ajax: {
                url: "include/findme_item.php",
                dataType: 'json',
                delay: 250, // Evita saturar el servidor mientras el usuario escribe rápido
                data: function (params) {
                    return {
                        findme: params.term // Pasa el texto buscado al $_REQUEST["findme"]
                    };
                },
                processResults: function (response) {
                    // Evaluamos la estructura estándar de tu backend remanufacturado
                    if (response && response.success) {
                        return {
                            // Mapeamos los datos en tiempo de ejecución al estándar de Select2
                            results: $.map(response.data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.articulo // Select2 exige obligatoriamente la propiedad 'text'
                                };
                            })
                        };
                    } else {
                        return { results: [] };
                    }
                },
                cache: true
            },
            placeholder: "Buscar un artículo...",
            minimumInputLength: 1 // No busca hasta que haya al menos 1 carácter
        });

        // Evento al seleccionar un artículo de la lista de Select2
        $('#findme').on('select2:select', function (e) {
            // Convertimos explícitamente a entero el ID devuelto por Select2
            var idArticulo = parseInt(e.params.data.id, 10);
           
            // 1. Limpieza preventiva del área de resultados de lotes previos
            $("#ResultadoLote").html(''); 
            
            // 2. Validación robusta del ID
            if (!isNaN(idArticulo) && idArticulo > 0) {
                // Mostramos el feedback visual sobrio usando clases nativas de Bootstrap
                $("#ResultadoBusqueda").html(
                    '<div class="alert alert-success py-1 px-2 mb-0 mt-2 small">' +
                    '<i class="fa fa-check-circle"></i> Artículo Seleccionado con éxito.' +
                    '</div>'
                );
                
                // Disparamos la búsqueda de sus lotes y existencias en el backend
                BuscarLote(idArticulo, $("#id").val());
            } else {
                // En caso de una selección inválida o placeholder de limpieza
                $("#ResultadoBusqueda").html('');
            }
        });

        CargarDetalle();
        CargarFinanciero();
    });

    var puedeModificarPrecioDescuento = <?= VerificaFuncion("040") ? "true" : "false"; ?>;

    function toggleDatosFactura(val) {
        if(val.trim() === "S") {
            $("#DatosFactura").slideDown(200);
        } else {
            $("#DatosFactura").slideUp(200);
        }
    }

    function BuscarLote(id, idDocumento) {
        $.ajax({
            data: {
                "id": id,
                "id_documento": idDocumento
            },
            url: "include/findme_lote.php",
            type: 'post',
            dataType: 'json', // Procesa automáticamente el JSON estructurado
            beforeSend: function () {
                $("#ResultadoLote").html('<div class="text-center p-3 text-secondary"><i class="fa fa-spinner fa-spin"></i> Cargando lotes disponibles...</div>');
            },
            success: function (response) {
                if (response && response.success) {
                    
                    if (response.lotes.length === 0) {
                        $("#ResultadoLote").html('<div class="alert alert-warning py-2 px-3 small mb-0 mt-2"><i class="fa fa-exclamation-triangle"></i> No quedan existencias de este artículo en ningún lote activo.</div>');
                        return;
                    }

                    // Estructura de cabecera idéntica a tu diseño original (Bootstrap 5 adaptado)
                    var html = '<div class="table-responsive mt-2">';
                    html += '<table class="table table-sm table-bordered table-striped align-middle mb-0" style="font-size: 0.85rem;">';
                    html += '  <thead class="table-light text-secondary">';
                    html += '    <tr>';
                    html += '      <th>Fabricante</th>';
                    html += '      <th>Artículo / Descripción</th>';
                    html += '      <th>Lot, Venc y Exs UNIDAD</th>'; // Mismo texto de tu cabecera original
                    html += '      <th style="width: 100px;">Cantidad</th>';
                    html += '      <th style="width: 120px;">Precio U.</th>';
                    html += '      <th>Unidad Medida</th>';
                    html += '      <th class="text-center" style="width: 50px;"></th>'; // Columna vacía para el botón de agregar
                    html += '    </tr>';
                    html += '  </thead>';
                    html += '  <tbody>';
                    
                    // Iteramos los lotes del JSON para generar las filas independientes
                    $.each(response.lotes, function(index, loteRow) {
                        // Índices únicos por fila para no colisionar IDs de campos en el DOM
                        var idCantidad = 'cantidad_' + index;
                        var idUnidad   = 'unidad_' + index;
                        var idPrecio = 'precio_' + index;
                        var precioUnidad = parseFloat(loteRow.precio_unidad || 0).toFixed(2);
                        
                        html += '    <tr>';
                        // Datos base del artículo mapeados
                        html += '      <td class="fw-bold">' + (response.articulo.fabricante || 'N/A') + '</td>';
                        html += '      <td>' + response.articulo.nombre + '</td>';
                        
                        // Texto descriptivo del lote combinado (Lote, Fecha Venc y Existencia)
                        var infoLote = loteRow.lote;
                        if (loteRow.fecha_formateada) {
                            infoLote += ', ' + loteRow.fecha_formateada;
                        }
                        infoLote += ' | ' + loteRow.cantidad + ' | ' + loteRow.almacen;
                        
                        html += '      <td>' + infoLote + '</td>';
                        
                        // Input numérico individual de cantidad para esta fila
                        html += '      <td>';
                        html += '        <input type="number" id="' + idCantidad + '" class="form-control form-control-sm text-end" min="1" max="' + loteRow.cantidad + '" placeholder="0">';
                        html += '      </td>';
                        
                        html += '      <td>';
                        html += '        <input type="number" id="' + idPrecio + '" class="form-control form-control-sm text-end precio-autorizado" step="0.01" min="0" value="' + precioUnidad + '" data-original="' + precioUnidad + '">';
                        html += '      </td>';

                        // Dropdown de Unidad de Medida por fila
                        html += '      <td>';
                        html += '        <select id="' + idUnidad + '" class="form-select form-select-sm">';
                        html += '          <option value="UDM001">UNIDAD (1)</option>';
                        html += '        </select>';
                        html += '      </td>';
                        
                        // RESTAURACIÓN DEL BOTÓN VERDE ORIGINAL (Columna final vacía de la imagen)
                        // Pasa las variables necesarias para recolectar la data e insertarla
                        html += '      <td class="text-center">';
                        html += '        <button type="button" class="btn btn-success btn-sm px-2 py-1" title="Agregar este lote" onclick="ProcesarInsercionFila(\'' + loteRow.value_string + '\', \'' + idCantidad + '\', \'' + idUnidad + '\', \'' + idPrecio + '\');">';
                        html += '          <i class="fa fa-plus-circle"></i>';
                        html += '        </button>';
                        html += '      </td>';
                        
                        html += '    </tr>';
                    });
                    
                    html += '  </tbody>';
                    html += '</table>';
                    html += '</div>';

                    // Inyectamos la tabla armada en la UI
                    $("#ResultadoLote").html(html);

                } else {
                    $("#ResultadoLote").html('<div class="alert alert-danger py-2 px-3 small"><i class="fa fa-times-circle"></i> Error: ' + response.error + '</div>');
                }
            },
            error: function () {
                $("#ResultadoLote").html('<div class="alert alert-danger py-2 px-3 small"><i class="fa fa-exclamation-circle"></i> Error crítico de red.</div>');
            }
        });
    }

    function ProcesarInsercionFila(valueString, idCantidadInput, idUnidadSelect, idPrecioInput) {
        var cantidadIngresada = $("#" + idCantidadInput).val();
        var unidadSeleccionada = $("#" + idUnidadSelect).val();
        var precioIngresado = $("#" + idPrecioInput).val();

        if (cantidadIngresada <= 0 || cantidadIngresada == "") {
            ew.alert("Por favor, introduzca una cantidad válida mayor a cero.");
            $("#" + idCantidadInput).focus();
            return;
        }

        var partesLote = valueString.split('|');
        var loteReal        = partesLote[0];
        var fechaVenc       = partesLote[1];
        var stockDisponible = partesLote[2];
        var codigoAlmacen   = partesLote[3];

        if (parseInt(cantidadIngresada) > parseInt(stockDisponible)) {
            ew.alert("Error: La cantidad ingresada (" + cantidadIngresada + ") supera la existencia disponible en este lote (" + stockDisponible + ").");
            $("#" + idCantidadInput).focus();
            return;
        }

        var idDocumento = $("#id").val();

        $.ajax({
            url: "include/findme_agregar.php",
            type: "POST",
            dataType: "json", // Manejo correcto del estándar JSON de tu backend
            data: {
                "id": idDocumento, 
                "username": $("#username").val() ? $("#username").val().trim() : "",
                "nota": $("#nota").val() ? $("#nota").val() : "",
                "factura": $("#factura").val() ? $("#factura").val() : "",
                "ci_rif": $("#ci_rif").val() ? $("#ci_rif").val() : "",
                "nombre": $("#nombre").val() ? $("#nombre").val() : "",
                "direccion": $("#direccion").val() ? $("#direccion").val() : "",
                "telefono": $("#telefono").val() ? $("#telefono").val() : "",
                
                "articulo": $("#findme").val(), 
                "lote": loteReal,
                "fecha": fechaVenc,
                "existencia": stockDisponible,
                "almacen": codigoAlmacen,
                "cantidad": cantidadIngresada,
                "precio": precioIngresado,
                "unidad": unidadSeleccionada
            },
            beforeSend: function() {
                $("#" + idCantidadInput).attr('disabled', true);
            },
            success: function(response) {
                $("#" + idCantidadInput).attr('disabled', false);

                if (response && response.success) {
                    // Refresco asíncronico unificado inmediato
                    CargarDetalle();
                    CargarFinanciero();
                    limpiarBuscador();
                } else {
                    ew.alert("No se pudo agregar el artículo: " + (response.error || response.message));
                }
            },
            error: function(xhr, status, error) {
                $("#" + idCantidadInput).attr('disabled', false);
                console.error("Error en agregar:", xhr.responseText);
                ew.alert("Error crítico del servidor al intentar agregar el artículo.");
            }
        });
    }

    function EliminarItem(id) {
        if (confirm("¿Seguro de eliminar este ítem de la cesta?")) {
            $.ajax({
                url: "include/findme_eliminar.php",
                type: 'POST',
                dataType: "json", // Tu backend responde un JSON estricto
                data: { 
                    "id": id, 
                    "username": $("#username").val().trim() 
                },
                beforeSend: function () {
                    // Efecto visual limpio de carga parcial
                    $("#ContenedorCestaDinamica").css('opacity', '0.5');
                },
                success: function (response) {
                    $("#ContenedorCestaDinamica").css('opacity', '1');
                    if (response && response.success) {
                        // Refrescamos de manera dinámica la vista completa
                        CargarDetalle();
                        CargarFinanciero();
                    } else {
                        ew.alert("No se pudo eliminar el ítem: " + (response.error || response.message));
                    }
                },
                error: function (xhr) {
                    $("#ContenedorCestaDinamica").css('opacity', '1');
                    console.error("Error en eliminar:", xhr.responseText);
                    ew.alert("Error crítico al intentar eliminar el ítem.");
                }
            });
        }
    }

    function AgregarItem() {
        if(validar_existencia() === false) return false;

        var factura = $("#factura").val();
        var ci_rif = $("#ci_rif").val().trim();
        var nombre = $("#nombre").val().trim();
        var direccion = $("#direccion").val().trim();
        var telefono = $("#telefono").val().trim();

        if(factura === "S" && (ci_rif === "" || nombre === "" || direccion === "" || telefono === "")) {
            ew.alert("Faltan datos fiscales obligatorios; Verifique");
            if(ci_rif === "") { $("#ci_rif").focus(); return false; }
            if(nombre === "") { $("#nombre").focus(); return false; }
            if(direccion === "") { $("#direccion").focus(); return false; }
            if(telefono === "") { $("#telefono").focus(); return false; }
        }

        if (factura !== "S") {
            ci_rif = ""; nombre = ""; direccion = ""; telefono = "";
        }

        var xlote = $("#lote").val().split("|");
        
        // RECOMENDACIÓN backend: Si dejas el precio editable en el formulario de lotes,
        // puedes capturar el nuevo valor asignado mediante $("#precio_lote_input").val() e incluirlo en los parámetros post.

        $.ajax({
            data: {
                "id": $("#id").val(),
                "factura": factura,
                "ci_rif": ci_rif,
                "nombre": nombre,
                "direccion": direccion,
                "telefono": telefono,
                "articulo": $("#id_articulo").val(),
                "lote": xlote[0],
                "fecha": xlote[1],
                "existencia": xlote[2],
                "almacen": xlote[3],
                "cantidad": $("#cantidad").val(),
                "unidad": $("#unidad").val(),
                "nota": $("#nota").val(),
                "username": $("#username").val().trim()
            },
            url: "include/findme_agregar.php",
            type: 'post',
            beforeSend: function () {
                $("#ResultadoDetalle").html('<tr><td colspan="8" class="text-center"><i class="fa fa-spinner fa-spin"></i> Agregando...</td></tr>');
            },
            success: function (response) {
                $("#ResultadoDetalle").html(response);
                limpiarBuscador();
            }
        });
    }

    function validar_existencia() { 
        if($("#lote").val() === null || $("#lote").val() === "") {
            ew.alert("Lote sin existencia o no seleccionado.");
            $("#cantidad").val("");
            return false;
        }

        var xlote = $("#lote").val().split("|");
        var cantidad = parseInt($("#cantidad").val()) || 0;
        var existencia = parseInt(xlote[2]) || 0;

        if(cantidad <= 0) {
            ew.alert("Ingrese una cantidad válida.");
            return false;
        }

        if(cantidad > existencia) {
            ew.alert("La cantidad solicitada es mayor a la existencia física.");
            return false;
        } 
        return true;
    }

    function ActualizarCabecera() {
        var factura = $("#factura").val();
        var ci_rif = $("#ci_rif").val().trim();
        var nombre = $("#nombre").val().trim();
        var direccion = $("#direccion").val().trim();
        var telefono = $("#telefono").val().trim();

        if(factura === "S" && (ci_rif === "" || nombre === "" || direccion === "" || telefono === "")) {
            ew.alert("Faltan datos fiscales obligatorios; Verifique");
            return false;
        }

        $.ajax({
            data: {
                "id": $("#id").val(),
                "factura": factura,
                "ci_rif": (factura === "S") ? ci_rif : "",
                "nombre": (factura === "S") ? nombre : "",
                "direccion": (factura === "S") ? direccion : "",
                "telefono": (factura === "S") ? telefono : "",
                "username": $("#username").val().trim(),
                "nota": $("#nota").val(),
                "tasa": $("#tasa").val(),
                "descuento": $("#descuento").val(),
                "descuento2": $("#descuento2").val()
            },
            url: "include/findme_cabecera_update.php",
            type: 'post',
            success: function (response) {
                // Al guardar, refresca el DOM dinámico si es necesario o recarga parcial.
                ew.alert("Datos de la cabecera actualizados correctamente.");
                location.reload(); // Recarga recomendada para actualizar los bloques de cálculo PHP.
            }
        });
    }

    function VaciarCesta() {
        if (!confirm("¿Seguro de vaciar por completo la cesta de este documento?"))
            return false;

        $.ajax({
            url: "include/findme_eliminar_all.php",
            type: "POST",
            dataType: "json",
            data: {
                id: $("#id").val(),
                username: $("#username").val().trim()
            }
        })
        .done(function(resp) {
            if (resp.success) {

                ew.alert(resp.message);

                window.location.href = resp.redirect_to;

            } else {
                ew.alert(resp.error || "No fue posible eliminar el documento.");
            }
        })
        .fail(function(xhr) {
            console.log(xhr.responseText);
            ew.alert("Error de comunicación con el servidor.");
        });

        return false;
    }

    function limpiarBuscador() {
        $("#findme").val(null).trigger('change');
        $("#ResultadoBusqueda").html('');
        $("#ResultadoLote").html('');
    }

    function CargarDetalle() {
        var idDocumento = $("#id").val();
        var tipoDocumento = new URLSearchParams(window.location.search).get('tipo_documento') || 'TDCNET';

        if (!idDocumento || idDocumento == "0") return;

        $.ajax({
            type: "POST",
            url: "include/findme_detalle.php",
            data: { 
                "id_documento": idDocumento,
                "tipo_documento": tipoDocumento
            },
            success: function (htmlData) {
                $("#ContenedorCestaDinamica").html(htmlData);
            },
            error: function(xhr, status, error) {
                console.error("Error al refrescar componentes dinámicos:", error);
            }
        });
    }

    function CargarFinanciero() {
        var idDocumento = $("#id").val();
        var tipoDocumento = new URLSearchParams(window.location.search).get('tipo_documento') || 'TDCNET';
        var tasa_dia = <?= $tasa_dia ?>;
        
        if (!idDocumento || idDocumento == "0") return;

        $.ajax({
            type: "POST",
            url: "include/findme_financiero.php",
            data: { 
                "id_documento": idDocumento,
                "tipo_documento": tipoDocumento,
                "tasa_dia": tasa_dia
            },
            success: function (htmlData) {
                $("#ContenedorFinancieroDinamico").html(htmlData);
            },
            error: function(xhr, status, error) {
                console.error("Error al refrescar componentes dinámicos:", error);
            }
        });
    }

    function ActualizarDescuentos() {
        var idDocumento = $("#id").val();
        var tipoDocumento = new URLSearchParams(window.location.search).get('tipo_documento') || 'TDCNET';

        var descuento = parseFloat($("#descuento").val()) || 0;
        var descuento2 = parseFloat($("#descuento2").val()) || 0;

        if (descuento < 0 || descuento >= 100) {
            ew.alert("Desc 1 debe estar entre 0 y 99.");
            $("#descuento").val(0).focus();
            return false;
        }

        if (descuento2 < 0 || descuento2 >= 100) {
            ew.alert("Desc 2 debe estar entre 0 y 99.");
            $("#descuento2").val(0).focus();
            return false;
        }

        $.ajax({
            url: "include/findme_actualizar_descuentos.php",
            type: "POST",
            dataType: "json",
            data: {
                id_documento: idDocumento,
                tipo_documento: tipoDocumento,
                descuento: descuento,
                descuento2: descuento2
            },
            success: function(response) {
                if (response && response.success) {
                    CargarFinanciero();
                    CargarDetalle();
                } else {
                    ew.alert(response.error || "No se pudieron actualizar los descuentos.");
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                ew.alert("Error actualizando descuentos.");
            }
        });
    }

    var descuentoPendiente = {
        descuento: 0,
        descuento2: 0,
        descuentoOriginal: 0,
        descuento2Original: 0
    };

    var revertiendoDescuento = false;

    $(document).on("focus", "#descuento, #descuento2", function () {
        $(this).attr("data-original", $(this).val());
    });

    $(document).on("change", "#descuento, #descuento2", function () {
        if (revertiendoDescuento) return;

        descuentoPendiente.descuento = parseFloat($("#descuento").val()) || 0;
        descuentoPendiente.descuento2 = parseFloat($("#descuento2").val()) || 0;
        descuentoPendiente.descuentoOriginal = parseFloat($("#descuento").attr("data-original")) || 0;
        descuentoPendiente.descuento2Original = parseFloat($("#descuento2").attr("data-original")) || 0;

        if (puedeModificarPrecioDescuento) {
            ActualizarDescuentos();
            return;
        }

        $("#auth_user_descuento").val("");
        $("#auth_pass_descuento").val("");
        $("#modalAutorizarDescuento").modal("show");
    });

    $("#btnCancelarDescuento").on("click", function () {
        if (precioPendiente.inputId !== "") {
            $("#" + precioPendiente.inputId).val(precioPendiente.precioOriginal);
            $("#" + precioPendiente.inputId).attr("data-original", precioPendiente.precioOriginal);

            precioPendiente.inputId = "";
            precioPendiente.precioOriginal = "";
            precioPendiente.precioNuevo = "";
        } else {
            revertiendoDescuento = true;

            $("#descuento").val(descuentoPendiente.descuentoOriginal);
            $("#descuento2").val(descuentoPendiente.descuento2Original);

            setTimeout(function () {
                revertiendoDescuento = false;
            }, 300);
        }

        $("#modalAutorizarDescuento").modal("hide");
    });

    $("#btnAceptarDescuento").on("click", function () {
        var xuser = $("#auth_user_descuento").val().trim();
        var xpass = $("#auth_pass_descuento").val().trim();

        var tipo_documento = "<?= $tipo_documento ?>";
        var nro_documento  = "<?= $nro_documento ?>";
        var usercaja = "<?= CurrentUserName() ?>";
        var pedido = $("#id").val();

        if (xuser === "" || xpass === "") {
            ew.alert("Debe ingresar usuario y clave.");
            return false;
        }

        $.ajax({
            url: "include/Validar_Usuario_desc_precio.php",
            type: "GET",
            data: {
                usernama: xuser,
                password: xpass,
                contexto: "DESCUENTO_SALIDA",
                tipo_documento: tipo_documento,
                nro_documento: nro_documento,
                usercaja: usercaja,
                idPurga: pedido
            }
        })
        .done(function (MyResult) {
            if (MyResult.trim() === "S") {
                $("#modalAutorizarDescuento").modal("hide");

                if (precioPendiente.inputId !== "") {
                    $("#" + precioPendiente.inputId).attr("data-original", precioPendiente.precioNuevo);

                    precioPendiente.inputId = "";
                    precioPendiente.precioOriginal = "";
                    precioPendiente.precioNuevo = "";
                } else {
                    ActualizarDescuentos();
                }
            } else {
                RestaurarPrecioPendiente();

                revertiendoDescuento = true;

                $("#descuento").val(descuentoPendiente.descuentoOriginal);
                $("#descuento2").val(descuentoPendiente.descuento2Original);

                setTimeout(function () {
                    revertiendoDescuento = false;
                }, 300);

                $("#modalAutorizarDescuento").modal("hide");

                ew.alert("!!! NO AUTORIZADO !!!");
            }            
        })
        .fail(function () {
            ew.alert("Error de comunicación con el servidor.");
        });
    });

    var precioPendiente = {
        inputId: "",
        precioOriginal: "",
        precioNuevo: ""
    };

    $(document).on("focus", ".precio-autorizado", function () {
        if ($(this).attr("data-original") === undefined || $(this).attr("data-original") === "") {
            $(this).attr("data-original", $(this).val());
        }
    });

    function RestaurarPrecioPendiente() {
        if (precioPendiente.inputId !== "") {
            var $input = $("#" + precioPendiente.inputId);

            $input.val(precioPendiente.precioOriginal);
            $input.attr("data-original", precioPendiente.precioOriginal);

            precioPendiente.inputId = "";
            precioPendiente.precioOriginal = "";
            precioPendiente.precioNuevo = "";
        }
    }

    $(document).on("change", ".precio-autorizado", function () {
        var $input = $(this);

        precioPendiente.inputId = $input.attr("id");
        precioPendiente.precioOriginal = $input.attr("data-original");
        precioPendiente.precioNuevo = $input.val();

        if (puedeModificarPrecioDescuento) {
            precioPendiente.inputId = "";
            $input.attr("data-original", $input.val());
            return;
        }

        $("#auth_user_descuento").val("");
        $("#auth_pass_descuento").val("");
        $("#modalAutorizarDescuento").modal("show");
    });

</script>
<?= GetDebugMessage() ?>
