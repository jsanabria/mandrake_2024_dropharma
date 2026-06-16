<?php

namespace PHPMaker2024\mandrake;

// Page object
$CrearNotaEntregaAutomaticaWait = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$return = $_GET["return"] ?? "ViewOutTdcfcvView/" . $id . "?showdetail=";

$idDocumentoPadre = 0;

if ($id > 0) {
    $idDocumentoPadre = intval(ExecuteScalar("
        SELECT IFNULL(id_documento_padre, 0)
        FROM salidas
        WHERE id = $id
        LIMIT 1
    "));
}
?>

<div class="container py-4">
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">
            <i class="fa fa-truck"></i> Generando Orden de Entrega
        </div>

        <div class="card-body text-center">
            <div id="estadoNE">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <h5>Procesando artículos de inventario...</h5>
                <p class="text-muted mb-0">Por favor espere un momento.</p>
            </div>
        </div>
    </div>
</div>

<script>
loadjs.ready(["jquery"], function () {
    const $ = jQuery;
    const idDocumentoPadre = <?= intval($idDocumentoPadre) ?>;

    $(document).on("click", "#btnContinuarNE", function () {
        window.location.href = "<?= htmlspecialchars($return, ENT_QUOTES) ?>";
    });

    if (idDocumentoPadre > 0) {
        $("#estadoNE").html(
            '<div class="alert alert-info text-start">' +
                '<h5><i class="fa fa-info-circle"></i> Orden de Entrega ya asociada</h5>' +
                '<div>Este documento ya tiene una Orden de Entrega asociada. No se generará una nueva.</div>' +
            '</div>' +
            '<button type="button" id="btnContinuarNE" class="btn btn-primary">' +
                '<i class="fa fa-arrow-right"></i> Continuar' +
            '</button>'
        );

        $(document).on("click", "#btnContinuarNE", function () {
            window.location.href = "ViewOutTdcfcvView/<?= intval($id) ?>?showdetail=";
        });

        return;

    }

    $.ajax({
        url: "CrearNotaEntregaAutomatica",
        type: "POST",
        dataType: "json",
        data: {
            id: <?= intval($id) ?>,
            ajax: "S"
        },
        success: function(resp) {
            let tipo = resp.success ? "success" : "warning";
            let icono = resp.success ? "fa-check-circle" : "fa-triangle-exclamation";
            let titulo = resp.success ? "Orden de Entrega creada" : "Aviso";

            $("#estadoNE").html(
                '<div class="alert alert-' + tipo + ' text-start">' +
                    '<h5><i class="fa ' + icono + '"></i> ' + titulo + '</h5>' +
                    '<div>' + (resp.message || "Proceso finalizado.") + '</div>' +
                    (resp.nro_documento ? '<div class="mt-2"><strong>Nro:</strong> ' + resp.nro_documento + '</div>' : '') +
                '</div>' +
                '<button type="button" id="btnContinuarNE" class="btn btn-primary">' +
                    '<i class="fa fa-arrow-right"></i> Continuar' +
                '</button>'
            );
        },
        error: function(xhr) {
            console.log(xhr.responseText);

            $("#estadoNE").html(
                '<div class="alert alert-danger text-start">' +
                    '<h5><i class="fa fa-times-circle"></i> Error creando Orden de Entrega</h5>' +
                    '<div>No se pudo completar el proceso automático.</div>' +
                '</div>' +
                '<button type="button" id="btnContinuarNE" class="btn btn-primary">' +
                    '<i class="fa fa-arrow-right"></i> Continuar' +
                '</button>'
            );
        }
    });

    $(document).on("click", "#btnContinuarNE", function () {
        window.location.href = "<?= htmlspecialchars($return, ENT_QUOTES) ?>";
    });
});
</script>
<?= GetDebugMessage() ?>
