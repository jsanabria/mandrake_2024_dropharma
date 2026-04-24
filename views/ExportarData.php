<?php

namespace PHPMaker2024\mandrake;

// Page object
$ExportarData = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// 1. LÓGICA DEL PROCESADOR (BACKEND)
if (isset($_GET['ejecutar']) && $_GET['ejecutar'] == 1) {
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json');

    if (!IsLoggedIn()) {
        echo json_encode(["status" => "error", "message" => "Acceso denegado"]);
        exit;
    }

    $comando = '/usr/local/bin/php /home2/dropharm/public_html/mandrake.dropharmadm.com.ve/tareas/ExportDataCronTab.php 2>&1';
    
    if (function_exists('shell_exec')) {
        $salida = shell_exec($comando);
        $status = "ok";
    } else {
        $salida = "Error: shell_exec está desactivada.";
        $status = "error";
    }
    
    echo json_encode([
        "status" => $status, 
        "output" => $salida,
        "fecha" => date("Y-m-d H:i:s")
    ]);
    exit; 
}
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-exchange"></i> Exportación Manual de Datos</h3>
        </div>
        <div class="card-body">
            <p>Este proceso ejecutará el script de exportación fuera del horario programado del Cron.</p>
            <button type="button" id="btnExportar" class="btn btn-primary btn-lg">
                <i class="fa fa-play-circle"></i> Ejecutar Proceso Ahora
            </button>
        </div>
        <div id="resultadoLog" class="card-footer" style="display:none;">
            <strong>Consola del Servidor:</strong>
            <pre id="outputConsole" style="background: #222; color: #0f0; padding: 15px; margin-top: 10px; border-radius: 5px; font-size: 12px; overflow: auto; max-height: 400px;"></pre>
        </div>
    </div>
</div>

<script>
// Usamos una función autoejecutable para asegurar que jQuery ($) esté disponible
// sin interferir con las rutas internas de PHPMaker
(function($) {
    $(function() {
        // Delegación de evento directa al document para evitar problemas de carga
        $(document).off("click", "#btnExportar").on("click", "#btnExportar", function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Confirmar ejecución?',
                text: "Se lanzará el script en el servidor.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Sí, ejecutar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Procesando...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    // Construimos la URL de forma limpia
                    var urlEjecucion = ew.currentPage() + '?ejecutar=1';

                    $.getJSON(urlEjecucion)
                        .done(function(data) {
                            $("#resultadoLog").show();
                            if(data.status == "ok") {
                                Swal.fire('¡Éxito!', 'Proceso finalizado', 'success');
                                $("#outputConsole").css("color", "#0f0").text(data.output);
                            } else {
                                Swal.fire('Error', 'Problema en el servidor', 'error');
                                $("#outputConsole").css("color", "#ff9900").text(data.output || data.message);
                            }
                        })
                        .fail(function() {
                            Swal.fire('Error', 'No se pudo comunicar con el servidor.', 'error');
                        });
                }
            });
        });
    });
})(jQuery);
</script>
<?= GetDebugMessage() ?>
