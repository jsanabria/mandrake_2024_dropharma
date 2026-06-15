<?php

namespace PHPMaker2024\mandrake;

// Page object
$ClienteRapidoAdd = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

$userLevelId = CurrentUserLevel();

$puedeAgregarCliente = false;

// Administrador PHPMaker
if ($userLevelId == -1) {

    $puedeAgregarCliente = true;

} else {

    $sql = "
        SELECT permission
        FROM userlevelpermissions
        WHERE userlevelid = " . intval($userLevelId) . "
          AND tablename = 'cliente'
        LIMIT 1
    ";

    $permiso = intval(ExecuteScalar($sql));

    $puedeAgregarCliente = (($permiso & 2) == 2);
}

if (!$puedeAgregarCliente) {

    if (ob_get_length()) {
        ob_end_clean();
    }

    header("Content-Type: application/json; charset=utf-8");

    echo json_encode([
        "success" => false,
        "message" => "No tiene permisos para agregar clientes."
    ]);

    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (ob_get_length()) {
        ob_end_clean();
    }

    header("Content-Type: application/json; charset=utf-8");

    $ci_rif = trim($_POST["ci_rif"] ?? "");
    $nombre = trim($_POST["nombre"] ?? "");
    $sucursal = trim($_POST["sucursal"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $telefono1 = trim($_POST["telefono1"] ?? "");
    $tarifa = intval($_POST["tarifa"] ?? 0);

    $rifLimpio = strtoupper(str_replace([".", "-", " "], "", $ci_rif));
    $primerCaracter = substr($rifLimpio, 0, 1);

    if (!in_array($primerCaracter, ["V", "E", "J", "G"])) {
        echo json_encode(["success" => false, "message" => "El RIF/CI debe comenzar con V, E, J o G."]);
        exit;
    }

    if ($ci_rif == "" || $nombre == "" || $direccion == "" || $telefono1 == "" || $tarifa <= 0) {
        echo json_encode(["success" => false, "message" => "Debe completar los campos obligatorios."]);
        exit;
    }

    $sql = "
        SELECT COUNT(*) 
        FROM cliente 
        WHERE REPLACE(REPLACE(REPLACE(ci_rif, '.', ''), '-', ''), ' ', '') = '" . AdjustSql($rifLimpio) . "'
    ";

    if (intval(ExecuteScalar($sql)) > 0) {
        echo json_encode(["success" => false, "message" => "El RIF/CI ya se encuentra registrado."]);
        exit;
    }

    $sql = "
        INSERT INTO cliente
            (id, ci_rif, nombre, sucursal, direccion, telefono1, tarifa, activo)
        VALUES
            (
                NULL,
                '" . AdjustSql($ci_rif) . "',
                '" . AdjustSql($nombre) . "',
                '" . AdjustSql($sucursal) . "',
                '" . AdjustSql($direccion) . "',
                '" . AdjustSql($telefono1) . "',
                " . $tarifa . ",
                'S'
            )
    ";

    ExecuteStatement($sql);

    $id = ExecuteScalar("SELECT LAST_INSERT_ID()");

    echo json_encode([
        "success" => true,
        "message" => "Cliente creado correctamente.",
        "id" => $id,
        "nombre" => $nombre,
        "ci_rif" => $ci_rif
    ]);
    exit;
}

$tarifas = ExecuteRows("SELECT id, nombre, patron FROM tarifa WHERE activo = 'S' ORDER BY nombre");
?>

<style>
.req {
    color: #dc3545;
    font-weight: bold;
}

.form-cliente-rapido {
    max-width: 820px;
    margin: 0 auto;
}

.form-cliente-rapido .form-label {
    margin-bottom: 4px;
}
</style>

<div class="container-fluid p-3 form-cliente-rapido">
    <form id="frmClienteRapido" method="post" action="ClienteRapidoAdd">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    CI / RIF <span class="req">*</span>
                </label>
                <input type="text"
                    name="ci_rif"
                    id="ci_rif"
                    class="form-control form-control-sm"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    Nombre <span class="req">*</span>
                </label>
                <input type="text"
                    name="nombre"
                    id="nombre"
                    class="form-control form-control-sm"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    Teléfono <span class="req">*</span>
                </label>
                <input type="text"
                    name="telefono1"
                    id="telefono1"
                    class="form-control form-control-sm"
                    required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    Sucursal
                </label>
                <input type="text"
                    name="sucursal"
                    id="sucursal"
                    class="form-control form-control-sm">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">
                    Dirección <span class="req">*</span>
                </label>
                <textarea name="direccion" id="direccion" class="form-control form-control-sm" rows="3" required></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">
                    Tarifa <span class="req">*</span>
                </label>
                <select name="tarifa" id="tarifa" class="form-select form-select-sm" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($tarifas as $t) { ?>
                        <option value="<?= $t["id"] ?>" <?= ($t["patron"] == "S" ? "selected" : "") ?>>
                            <?= HtmlEncode($t["nombre"]) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

        </div>

        <hr>

        <div class="text-end">
            <button type="submit" class="btn btn-primary btn-sm px-4">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Cliente
            </button>
        </div>

    </form>
</div>

<script>
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

    function formatearRIF(rif) {
        rif = rif.trim().toUpperCase().replace(/[.\-\s]/g, "");
        if (rif.length <= 1) return rif;
        return rif.substring(0, 1) + "-" + rif.substring(1);
    }

    $("#ci_rif").change(function() {
        let $input = $(this);
        let rifOriginal = $input.val().trim();

        if (rifOriginal == "") return;

        let primerCaracter = rifOriginal.substring(0, 1).toUpperCase();

        if (!["V", "E", "J", "G"].includes(primerCaracter)) {
            ew.alert("El RIF/CI debe comenzar con una letra válida: V, E, J o G.");
            $input.val("").focus();
            return false;
        }

        $.getJSON("RifBuscar", {
            ci_rif: rifOriginal,
            tipo: "CLIENTE",
            accion: "I"
        }).done(function(data) {
            if (data && data.existe) {
                ew.alert("El RIF/CI " + rifOriginal + " ya se encuentra registrado en clientes.");
                $input.val("").focus();
                return false;
            }

            $input.val(formatearRIF(rifOriginal));
        });
    });

    $("#frmClienteRapido").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "ClienteRapidoAdd",
            type: "POST",
            dataType: "json",
            data: $(this).serialize()
        }).done(function(resp) {
            if (resp.success) {
                window.parent.$("#codcli").val(resp.id);
                window.parent.$("#cliente").val(resp.nombre);

                const modalEl = window.parent.document.getElementById("modalClienteAdd");
                const modal = window.parent.bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                window.parent.$("#btnCrear").prop("disabled", false);

                setTimeout(function() {
                    window.parent.document.getElementById("frm").submit();
                }, 300);
            } else {
                ew.alert(resp.message);
            }
        }).fail(function(xhr) {
            console.log(xhr.responseText);
            ew.alert("Error creando cliente.");
        });
    });
});
</script>
<?= GetDebugMessage() ?>
