<?php

namespace PHPMaker2024\mandrake;

// Page object
$RegistraAnticiposClientes = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// RegistrarAnticiposClientes.php (Custom File - PHPMaker 2024)

// 1) Parámetros
$accion = Param("accion") ?? "";
$compania_id = 1;

function jsonResponse($arr) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

function getTasaHoy($moneda) {
    $moneda = trim((string)$moneda);
    if ($moneda === "" || $moneda === "Bs.") return 1;

    // 1) Tasa del día
    $sql = "
        SELECT tasa
        FROM tasa_usd
        WHERE moneda = '" . AdjustSql($moneda) . "'
          AND fecha = CURDATE()
        ORDER BY id DESC
        LIMIT 1
    ";
    $t = ExecuteScalar($sql);

    // 2) Fallback: última tasa registrada
    if (!$t) {
        $sql2 = "
            SELECT tasa
            FROM tasa_usd
            WHERE moneda = '" . AdjustSql($moneda) . "'
            ORDER BY fecha DESC, id DESC
            LIMIT 1
        ";
        $t = ExecuteScalar($sql2);
    }

    $t = floatval($t ?: 0);
    return ($t > 0) ? $t : 1;
}

// 2.B) AJAX: TASA (para autocompletar tasa por moneda)
if ($accion === "tasa") {
    if (ob_get_length()) ob_end_clean();

    $moneda = trim((string)(Param("moneda") ?? "Bs."));
    $tasa = getTasaHoy($moneda);

    jsonResponse([
        "success" => true,
        "moneda" => $moneda,
        "tasa" => $tasa
    ]);
}

// 2.C) AJAX: TASAS (últimas 15 tasas por moneda)
if ($accion === "tasas") {
    if (ob_get_length()) ob_end_clean();

    $moneda = trim((string)(Param("moneda") ?? "Bs."));
    $out = [];

    if ($moneda === "" || $moneda === "Bs.") {
        $out[] = ["tasa" => 1, "fecha" => date("Y-m-d"), "hora" => ""];
        jsonResponse(["success" => true, "moneda" => "Bs.", "items" => $out]);
    }

    $sql = "
        SELECT tasa, DATE_FORMAT(fecha, '$d/%m/%Y') AS fecha, hora
        FROM tasa_usd
        WHERE moneda = '" . AdjustSql($moneda) . "'
        ORDER BY fecha DESC, hora DESC, id DESC
        LIMIT 15
    ";
    $rows = ExecuteRows($sql);

    foreach ($rows as $r) {
        $out[] = [
            "tasa" => floatval($r["tasa"] ?? 0),
            "fecha" => (string)($r["fecha"] ?? ""),
            "hora" => (string)($r["hora"] ?? "")
        ];
    }

    if (count($out) === 0) {
        $out[] = ["tasa" => 1, "fecha" => "", "hora" => ""];
    }

    jsonResponse(["success" => true, "moneda" => $moneda, "items" => $out]);
}

// ------------------------------------------------------------
// 2) AJAX: REFRESCAR
if ($accion === "refrescar") {
    if (ob_get_length()) ob_end_clean();

    $cliente_id = intval(Param("cliente_id") ?? 0);
    $json = Param("items") ?? "[]";
    $lista = json_decode($json, true) ?: [];

    // Clientes (si quieres filtrar activos, agrega AND activo='S')
    $clientes = ExecuteRows("
        SELECT id, nombre, ci_rif
        FROM cliente
        ORDER BY nombre
    ");

    // Métodos de pago
    $metodos = ExecuteRows("
        SELECT valor1, valor2
        FROM parametro
        WHERE codigo='009'
          AND valor1 NOT IN ('PC','PF','DV','NC','ND')
        ORDER BY valor2
    ");

    // Monedas permitidas (ajusta si quieres)
    // Si en parametro(006) tienes "Bs.", "USD", "EURO" etc, lo tomamos de allí.
    $monedas = ExecuteRows("
        SELECT valor1
        FROM parametro
        WHERE codigo='006'
          AND valor1 IN ('Bs.','USD','EURO')
        ORDER BY valor1
    ");

    // Bancos (origen) select2: tabla BANCO
    $bancos_origen = ExecuteRows("
        SELECT campo_codigo AS codigo, campo_descripcion AS descripcion
        FROM tabla
        WHERE tabla='BANCO'
        ORDER BY campo_descripcion
    ");

    // Cuentas destino (select simple): compania_cuenta + tabla(BANCO)
    $cuentas_destino = ExecuteRows("
        SELECT a.id,
               TRIM(b.campo_descripcion) AS banco,
               TRIM(IFNULL(a.tipo,'')) AS tipo,
               TRIM(IFNULL(a.numero,'')) AS numero
        FROM compania_cuenta a
        JOIN tabla b ON b.campo_codigo = a.banco AND b.tabla='BANCO'
        WHERE a.compania = " . intval($compania_id) . "
          AND a.mostrar = 'S'
          AND a.activo = 'S'
        ORDER BY b.campo_descripcion, a.numero
    ");

    // Reglas destino (auto)
    $reglas = ExecuteRows("
        SELECT compania, metodo, IFNULL(moneda,'') AS moneda, cuenta_destino_id, prioridad
        FROM pago_destino_regla
        WHERE compania = " . intval($compania_id) . " AND activo='S'
        ORDER BY prioridad DESC, id DESC
    ");
    $reglas_json = json_encode($reglas, JSON_UNESCAPED_UNICODE);

    // Para mostrar “Cuenta destino” en la lista sin JOIN, usamos lo que venga del JSON (destino_nom),
    // pero si no viene, hacemos un lookup local.
    $mapDestino = [];
    foreach ($cuentas_destino as $c) {
        $id = intval($c["id"]);
        $label = trim($c["banco"]);
        if (trim($c["numero"]) !== "") $label .= " · " . trim($c["numero"]);
        $mapDestino[$id] = $label;
    }

    // Calcula totales
    $total_bs = 0;
    foreach ($lista as $it) {
        $m = floatval($it["monto"] ?? 0);
        $mon = trim($it["moneda"] ?? "Bs.");
        $tasa = floatval($it["tasa"] ?? 1);
        if ($m <= 0) continue;

        $bs = ($mon === "Bs.") ? $m : ($m * ($tasa > 0 ? $tasa : 1));
        $total_bs += $bs;
    }
    ?>
    <input type="hidden" id="reglas_destino_json" value="<?= HtmlEncode($reglas_json) ?>">

    <div class="card shadow-sm border-0 mb-3 bg-light">
        <div class="card-body p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label class="small fw-bold">CLIENTE</label>
                    <select id="cliente_input" class="form-select form-select-sm">
                        <option value="">Seleccione...</option>
                        <?php foreach ($clientes as $c): ?>
                            <?php
                            $id = intval($c["id"]);
                            $nom = trim((string)$c["nombre"]);
                            $rif = trim((string)$c["ci_rif"]);
                            $txt = ($rif !== "") ? ($nom . " · " . $rif) : $nom;
                            $sel = ($cliente_id === $id) ? "selected" : "";
                            ?>
                            <option value="<?= $id ?>" <?= $sel ?>><?= HtmlEncode($txt) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4 text-end">
                    <div class="small text-muted">TOTAL ANTICIPO (Bs.)</div>
                    <div class="h5 m-0"><b><?= number_format($total_bs, 2, ".", ",") ?></b></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-3 border-top border-primary border-3">
        <div class="card-body">

            <!-- FILA 1 -->
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="small fw-bold">MÉTODO</label>
                    <select id="metodo_input" class="form-select form-select-sm">
                        <option value="">Seleccione...</option>
                        <?php foreach ($metodos as $m): ?>
                            <option value="<?= HtmlEncode($m["valor1"]) ?>"><?= HtmlEncode($m["valor2"]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="small fw-bold">MONTO</label>
                    <input type="number" id="monto_input" class="form-control form-control-sm fw-bold border-primary" step="0.01">
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold">MONEDA</label>
                    <select id="moneda_input" class="form-select form-select-sm">
                        <?php foreach ($monedas as $mm): ?>
                            <option value="<?= HtmlEncode($mm["valor1"]) ?>"><?= HtmlEncode($mm["valor1"]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold">TASA</label>
                    <select id="tasa_input" class="form-select form-select-sm"></select>
                </div>
            </div>

            <!-- FILA 2 -->
            <div class="row g-2 align-items-end mt-1">
                <div class="col-md-4">
                    <label class="small fw-bold">BANCO ORIGEN</label>
                    <select id="banco_origen_input" class="form-select form-select-sm" style="max-width: 240px;">
                        <option value="">Banco...</option>
                        <?php foreach ($bancos_origen as $b): ?>
                            <option value="<?= HtmlEncode($b["codigo"]) ?>" data-code="<?= HtmlEncode($b["codigo"]) ?>">
                                <?= HtmlEncode(trim($b["descripcion"])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="small fw-bold">REFERENCIA</label>
                    <input type="text" id="ref_input" class="form-control form-control-sm" placeholder="Referencia...">
                </div>

                <div class="col-md-4 d-flex flex-column justify-content-end">
                    <button type="button" class="btn btn-primary btn-sm w-100 fw-bold" onclick="return addItem(event)">
                        ADD
                    </button>
                    <small class="mt-2 text-muted" id="help_ref_banco"></small>
                </div>
            </div>

            <!-- FILA 3 -->
            <div class="row g-2 align-items-end mt-1">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="small fw-bold mb-1">CUENTA DESTINO</label>
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="destino_auto" checked>
                            <label class="form-check-label small" for="destino_auto">Auto</label>
                        </div>
                    </div>

                    <select id="destino_input" class="form-select form-select-sm" style="max-width: 420px;">
                        <option value="">Seleccione...</option>
                        <?php foreach ($cuentas_destino as $c): ?>
                            <?php
                            $label = trim((string)$c["banco"]);
                            $num = trim((string)$c["numero"]);
                            if ($num !== "") $label .= " · " . $num;
                            ?>
                            <option value="<?= intval($c["id"]) ?>"><?= HtmlEncode($label) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <small class="text-muted d-block mt-1" id="help_destino"></small>
                </div>
            </div>

        </div>
    </div>

    <div class="list-group shadow-sm">
        <div class="list-group-item bg-dark text-white py-1 small fw-bold">
            ANTICIPOS AGREGADOS (<?= count($lista) ?>)
        </div>

        <?php foreach ($lista as $idx => $p): ?>
            <?php
            $met = trim($p["metodo_nom"] ?? "");
            $mon = trim($p["moneda"] ?? "");
            $monto = floatval($p["monto"] ?? 0);
            $ref = trim($p["ref"] ?? "");
            $bnom = trim($p["banco_nom"] ?? "");
            $destId = intval($p["destino_id"] ?? 0);
            $destNom = trim($p["destino_nom"] ?? "");
            if ($destNom === "" && $destId > 0 && isset($mapDestino[$destId])) $destNom = $mapDestino[$destId];
            ?>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-3">
                <div>
                    <span class="fw-bold d-block small"><?= HtmlEncode($met) ?></span>
                    <small class="text-muted">
                        Ref: <?= HtmlEncode($ref) ?>
                        <?php if ($bnom !== ""): ?> · Banco: <?= HtmlEncode($bnom) ?><?php endif; ?>
                        <?php if ($destNom !== ""): ?> · Cuenta destino: <?= HtmlEncode($destNom) ?><?php endif; ?>
                    </small>
                </div>

                <div class="d-flex align-items-center">
                    <span class="fw-bold text-primary me-3">
                        <?= HtmlEncode($mon) ?> <?= number_format($monto, 2, ".", ",") ?>
                    </span>
                    <button type="button" class="btn btn-sm text-danger" onclick="delItem(<?= $idx ?>)">X</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        $("#btn-registrar-anticipo").prop("disabled", <?= (count($lista) > 0 && $cliente_id > 0) ? "false" : "true" ?>);
    </script>

    <?php
    exit;
}

// ------------------------------------------------------------
// 3) AJAX: FINALIZAR (GUARDAR ANTICIPO)
if ($accion === "finalizar") {
    if (ob_get_length()) ob_end_clean();

    $cliente_id = intval(Param("cliente_id") ?? 0);
    $json = Param("items") ?? "[]";
    $lista = json_decode($json, true);

    if ($cliente_id <= 0) jsonResponse(["success" => false, "message" => "Debe seleccionar un cliente."]);
    if (!is_array($lista) || count($lista) === 0) jsonResponse(["success" => false, "message" => "No hay anticipos para registrar."]);

    $username = CurrentUserName();
    $conn = Conn();

    try {
        $conn->beginTransaction();

        // Total Bs (cabecera)
        $total_bs = 0;
        foreach ($lista as $p) {
            $tipo = trim($p["tipo"] ?? "");
            $monto = floatval($p["monto"] ?? 0);
            $mon = trim($p["moneda"] ?? "Bs.");
            $tasa = floatval($p["tasa"] ?? 1);

            if ($tipo === "" || $monto <= 0) continue;

            if ($mon !== "Bs." && $tasa <= 0) {
                throw new \Exception("Tasa inválida para moneda " . $mon);
            }

            $bs = ($mon === "Bs.") ? $monto : ($monto * $tasa);
            $total_bs += $bs;
        }

        if ($total_bs <= 0.001) {
            throw new \Exception("El total Bs. del anticipo es 0. Verifique montos.");
        }

        // Tasa cabecera (referencia): USD de hoy si existe
        $tasa_usd_hoy = getTasaHoy("USD");

        $sqlCab = "
            INSERT INTO cobros_cliente
              (cliente, id_documento, fecha, fecha_registro, username, moneda, tasa_cambio, monto, nota)
            VALUES
              (" . intval($cliente_id) . ",
               0,
               '" . AdjustSql(CurrentDate(), 200) . "',
               '" . AdjustSql(CurrentDate(), 200) . "',
               '" . AdjustSql($username, 200) . "',
               'Bs.',
               " . floatval($tasa_usd_hoy) . ",
               " . floatval($total_bs) . ",
               'ANTICIPO')
        ";
        Execute($sqlCab);

        $anticipo_id = intval(ExecuteScalar("SELECT LAST_INSERT_ID()"));
        if ($anticipo_id <= 0) throw new \Exception("No se pudo obtener el ID del anticipo.");

        // Detalles
        foreach ($lista as $p) {
            $tipo  = trim($p["tipo"] ?? "");
            $monto = floatval($p["monto"] ?? 0);
            $mon   = trim($p["moneda"] ?? "Bs.");
            $tasa  = floatval($p["tasa"] ?? 1);

            if ($tipo === "" || $monto <= 0) continue;

            $ref = trim($p["ref"] ?? "");
            $banco_origen = trim($p["banco_cod"] ?? "");  // varchar BCOxxx
            $destino_id = intval($p["destino_id"] ?? 0);  // int compania_cuenta.id

            // Validaciones mínimas (puedes endurecerlas igual que en pagos)
            // Destino obligatorio cuando entra dinero
            $requiereDestino = in_array($tipo, ["EF","TD","TC","TR","CH","DP","PM"], true);
            if ($requiereDestino && $destino_id <= 0) {
                throw new \Exception("Debe seleccionar cuenta destino para el método " . $tipo);
            }

            // Cálculos
            $monto_bs = ($mon === "Bs.") ? $monto : ($monto * ($tasa > 0 ? $tasa : 1));
            $tasa_mon = ($mon === "Bs.") ? 1 : ($tasa > 0 ? $tasa : 1);

            // NULLs SQL
            $sqlRef = ($ref !== "") ? ("'" . AdjustSql($ref) . "'") : "NULL";
            $sqlBancoOrigen = ($banco_origen !== "") ? ("'" . AdjustSql($banco_origen) . "'") : "NULL";
            $sqlDestino = ($destino_id > 0) ? (string)$destino_id : "NULL";

            $sqlDet = "
                INSERT INTO cobros_cliente_detalle
                  (cobros_cliente, metodo_pago, referencia,
                   monto_moneda, moneda, tasa_moneda, monto_bs,
                   banco_origen, banco, anticipo_id)
                VALUES
                  (" . intval($anticipo_id) . ",
                   '" . AdjustSql($tipo) . "',
                   $sqlRef,
                   " . floatval($monto) . ",
                   '" . AdjustSql($mon) . "',
                   " . floatval($tasa_mon) . ",
                   " . floatval($monto_bs) . ",
                   $sqlBancoOrigen,
                   $sqlDestino,
                   NULL)
            ";
            Execute($sqlDet);
        }

        $conn->commit();

        jsonResponse([
            "success" => true,
            "message" => "Anticipo registrado correctamente.",
            "anticipo_id" => $anticipo_id
        ]);

    } catch (\Throwable $ex) {
        try { $conn->rollBack(); } catch (\Throwable $e2) {}
        jsonResponse(["success" => false, "message" => "Error guardando anticipo: " . $ex->getMessage()]);
    }
}
// ------------------------------------------------------------
?>

<div class="container-fluid py-3" style="max-width: 900px;">
    <div id="div-ajax">
        <div class="text-center p-5"><div class="spinner-border text-primary"></div></div>
    </div>

    <input type="hidden" id="json_items" value="[]">

    <div class="mt-4 text-center">
        <button type="button" id="btn-registrar-anticipo" class="btn btn-success btn-lg px-5 shadow fw-bold" disabled onclick="finalizarAnticipo()">
            REGISTRAR ANTICIPO
        </button>

        <button type="button" class="btn btn-outline-secondary btn-lg px-5 shadow-sm fw-bold" onclick="window.location.href='index'">
            CANCELAR
        </button>
    </div>
</div>

<script>
let ajaxInFlight = false;

function refrescar() {
    const data = {
        accion: "refrescar",
        cliente_id: ($("#cliente_input_out").val() || "").toString().trim(), // hidden en base? (fallback)
        items: $("#json_items").val()
    };

    // tokens PHPMaker
    if (window.ew) {
        if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
        if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
    }

    $.ajax({
        url: window.location.href,
        type: "POST",
        data: data,
        success: function (r) {
            $("#div-ajax").html(r);
            initBancoOrigenSelect2();
            wireCliente();
            wireMonedaYTasa();
            aplicarDestinoAuto(true);
            aplicarReglasRefBanco();
            initClienteSelect2();
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            $("#div-ajax").html("<div class='alert alert-danger'>AJAX falló (revisa Network/Console).</div>");
        }
    });
}

// Cliente persistente (para refrescar sin perder)
function wireCliente() {
    // guarda selección de cliente fuera del ajax (hidden global)
    if ($("#cliente_input_out").length === 0) {
        $("<input>").attr({type:"hidden", id:"cliente_input_out"}).appendTo("body");
    }
    const val = ($("#cliente_input").val() || "").toString().trim();
    $("#cliente_input_out").val(val);

    $("#cliente_input").off("change").on("change", function(){
        $("#cliente_input_out").val(($(this).val() || "").toString().trim());
        refrescar();
    });
}

function addItem(e) {
    e = e || window.event;
    if (e) { e.preventDefault(); e.stopPropagation(); }

    const clienteId = ($("#cliente_input").val() || $("#cliente_input_out").val() || "").toString().trim();
    if (!clienteId) { ew.alert("Seleccione un cliente."); return false; }

    const tipo = ($("#metodo_input").val() || "").trim();
    const metodoNom = ($("#metodo_input option:selected").text() || "").trim();
    const monSel = ($("#moneda_input").val() || "Bs.").trim();
    const monto = parseFloat($("#monto_input").val() || "0");
    const tasa = parseFloat($("#tasa_input").val() || "1");
    const ref = ($("#ref_input").val() || "").trim();

    if (monSel !== "Bs.") {
        if (tasaLoading) { ew.alert("Espere un momento: cargando tasas..."); return false; }
        if (isNaN(tasa) || tasa <= 0) { ew.alert("Seleccione una tasa válida."); return false; }
    }

    // banco origen (select2 o normal)
    let bancoCod = ($("#banco_origen_input").val() || "").toString().trim();
    if (!bancoCod && $("#banco_origen_input").hasClass("select2-hidden-accessible")) {
        const d = $("#banco_origen_input").select2("data") || [];
        if (d.length && d[0].id) bancoCod = (d[0].id || "").toString().trim();
    }
    const bancoNom = ($("#banco_origen_input option:selected").text() || "").trim();

    // destino
    const destinoId = ($("#destino_input").val() || "").toString().trim();
    const destinoNom = ($("#destino_input option:selected").text() || "").trim();

    if (!tipo) { ew.alert("Seleccione un método."); return false; }
    if (isNaN(monto) || monto <= 0) { ew.alert("Indique un monto válido."); return false; }
    if (monSel !== "Bs." && (isNaN(tasa) || tasa <= 0)) { ew.alert("Indique una tasa válida."); return false; }

    // Reglas ref/banco (mismas que pagos, adaptadas sin IG/AN aquí)
    // - RS/RI: solo referencia
    // - EF: nada
    // - otros: banco + referencia (excepto RC si lo usas)
    const requiereRef = !["EF","RC"].includes(tipo);
    const requiereBanco = !["EF","RI","RS","RC"].includes(tipo);

    if (["RS","RI"].includes(tipo)) {
        if (!ref) { ew.alert("Debe indicar el número de referencia."); return false; }
    } else {
        if (requiereRef && !ref) { ew.alert("Debe indicar el número de referencia."); return false; }
        if (requiereBanco && !bancoCod) { ew.alert("Debe seleccionar banco origen."); return false; }
    }

    // Destino obligatorio cuando entra dinero
    const requiereDestino = ["EF","TD","TC","TR","CH","DP","PM"].includes(tipo);
    if (requiereDestino && !destinoId) { ew.alert("Debe seleccionar la cuenta destino."); return false; }

    let lista = [];
    try {
        lista = JSON.parse($("#json_items").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch(e) { lista = []; }

    lista.push({
        gid: Date.now().toString(36) + Math.random().toString(36).slice(2,8),
        tipo: tipo,
        metodo_nom: metodoNom,
        moneda: monSel,
        monto: monto,
        tasa: (monSel === "Bs.") ? 1 : tasa,
        ref: ref || "N/A",
        banco_cod: bancoCod || "",
        banco_nom: (bancoCod ? bancoNom : ""),
        destino_id: parseInt(destinoId, 10) || 0,
        destino_nom: destinoNom || ""
    });

    $("#json_items").val(JSON.stringify(lista));
    refrescar();
    return false;
}

function delItem(i) {
    let lista = [];
    try {
        lista = JSON.parse($("#json_items").val() || "[]");
        if (!Array.isArray(lista)) lista = [];
    } catch(e) { lista = []; }

    lista.splice(i, 1);
    $("#json_items").val(JSON.stringify(lista));
    refrescar();
    return false;
}

function finalizarAnticipo() {
    const clienteId = ($("#cliente_input_out").val() || "").toString().trim();
    const items = $("#json_items").val() || "[]";

    let data = {
        accion: "finalizar",
        cliente_id: clienteId,
        items: items
    };

    if (window.ew) {
        if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
        if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
    }

    $.ajax({
        url: window.location.href,
        type: "POST",
        dataType: "text",
        data: data,
        success: function(txt){
            let resp = null;
            try { resp = JSON.parse(txt); } catch(e) {}

            if (resp && resp.success) {
                ew.alert("Anticipo #" + resp.anticipo_id + " registrado.");
                // Redirige a donde quieras
                // window.location.href = "CobrosClienteView/" + resp.anticipo_id;
                $("#json_items").val("[]");
                refrescar();
            } else {
                ew.alert((resp && resp.message) ? resp.message : (txt || "Respuesta vacía del servidor."));
            }
        },
        error: function(xhr, status, err){
            console.log("AJAX ERROR:", status, err);
            console.log("RESPTEXT:", xhr.responseText);
            ew.alert("Error servidor: " + status + " / " + err + "\n\n" + (xhr.responseText || ""));
        }
    });
}

// --- Select2 banco origen ---
function initBancoOrigenSelect2() {
    const $sel = $("#banco_origen_input");
    if ($sel.length === 0) return;
    if (!$.fn.select2) return;

    if ($sel.hasClass("select2-hidden-accessible")) $sel.select2("destroy");

    $sel.select2({
        width: "resolve",
        placeholder: "Banco...",
        allowClear: true,
        dropdownParent: $("#div-ajax"),
        matcher: function(params, data){
            const term = $.trim(params.term || "").toLowerCase();
            if (term === "") return data;

            const text = (data.text || "").toLowerCase();
            const code = ($(data.element).data("code") || "").toString().toLowerCase();

            if (text.indexOf(term) > -1 || code.indexOf(term) > -1) return data;
            return null;
        },
        templateResult: function(data){ return data.text; },
        templateSelection: function(data){ return data.text; }
    });
}

function fetchTasas(moneda, cb) {
    moneda = (moneda || "Bs.").trim();

    const data = { accion: "tasas", moneda: moneda };

    if (window.ew) {
        if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
        if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
    }

    $.ajax({
        url: window.location.href,
        type: "POST",
        dataType: "json",
        data: data,
        success: function (resp) {
            if (resp && resp.success) cb && cb(resp.items || []);
            else cb && cb([]);
        },
        error: function () { cb && cb([]); }
    });
}

let tasaLoading = false;

function fillTasaSelect(items) {
    const $t = $("#tasa_input");
    if ($t.length === 0) return;

    $t.empty();

    if (!items || !items.length) {
        $t.append($("<option>").val("1").text("1.00"));
        return;
    }

    items.forEach((it, idx) => {
        const tasa = parseFloat(it.tasa || 0);
        if (!tasa || isNaN(tasa) || tasa <= 0) return;

        const f = (it.fecha || "").toString().trim();
        const h = (it.hora || "").toString().trim();
        const label = (f ? (f + (h ? " " + h : "")) : "Tasa") + " · " + tasa.toFixed(2);

        const $opt = $("<option>").val(tasa.toFixed(2)).text(label);
        if (idx === 0) $opt.prop("selected", true); // por defecto la más reciente
        $t.append($opt);
    });

    if ($t.children().length === 0) {
        $t.append($("<option>").val("1").text("1.00"));
    }
}

function wireMonedaYTasa() {
    $("#moneda_input").off("change").on("change", function () {
        const mon = ($(this).val() || "Bs.").trim();

        tasaLoading = true;
        $("#tasa_input").prop("disabled", true).empty().append($("<option>").text("Cargando..."));

        if (mon === "Bs.") {
            fillTasaSelect([{ tasa: 1, fecha: "", hora: "" }]);
            $("#tasa_input").prop("disabled", true);
            tasaLoading = false;
            aplicarDestinoAuto(true);
            aplicarReglasRefBanco();
            return;
        }

        fetchTasas(mon, function (items) {
            fillTasaSelect(items);
            $("#tasa_input").prop("disabled", false);
            tasaLoading = false;

            aplicarDestinoAuto(true);
            aplicarReglasRefBanco();
        });
    });

    // inicial
    $("#moneda_input").trigger("change");
}

// --- Reglas ref/banco en UI ---
function aplicarReglasRefBanco() {
    const tipo = ($("#metodo_input").val() || "").trim();
    const requiereBanco = !["EF","RI","RS","RC"].includes(tipo);
    const requiereRef   = !["EF","RC"].includes(tipo);

    const $b = $("#banco_origen_input");
    $b.prop("disabled", !requiereBanco);
    if ($b.hasClass("select2-hidden-accessible")) $b.trigger("change.select2");

    $("#help_ref_banco").text(
        (["RS","RI"].includes(tipo)) ? "Requiere: referencia." :
        (requiereBanco && requiereRef) ? "Requiere: banco y referencia." :
        (requiereRef) ? "Requiere: referencia." : ""
    );
}

$(document).on("change", "#metodo_input", function(){
    aplicarDestinoAuto(true);
    aplicarReglasRefBanco();
});

// --- Destino auto (misma lógica pro que tienes) ---
function parseReglasDestino() {
    const raw = ($("#reglas_destino_json").val() || "").trim();
    if (!raw) return [];
    try { const arr = JSON.parse(raw); return Array.isArray(arr) ? arr : []; }
    catch(e){ return []; }
}

function sugerirDestinoId(metodo, moneda) {
    metodo = (metodo || "").trim();
    moneda = (moneda || "").trim();
    const reglas = parseReglasDestino();

    // 1) match exacto
    let r = reglas.find(x =>
        String(x.metodo || "").trim() === metodo &&
        String(x.moneda || "").trim() === moneda
    );
    if (r && r.cuenta_destino_id) return String(r.cuenta_destino_id);

    // 2) comodín moneda=''
    r = reglas.find(x =>
        String(x.metodo || "").trim() === metodo &&
        String(x.moneda || "").trim() === ""
    );
    if (r && r.cuenta_destino_id) return String(r.cuenta_destino_id);

    return "";
}

function aplicarDestinoAuto(force) {
    const auto = $("#destino_auto").is(":checked");
    if (!auto && !force) return;

    const metodoSel = ($("#metodo_input").val() || "").trim();
    const monedaSel = ($("#moneda_input").val() || "").trim();

    // Tipos que no llevan destino (en anticipos casi todos sí, pero dejamos regla)
    if (["RC","RI","RS","NC","IG"].includes(metodoSel)) {
        $("#destino_input").val("");
        $("#help_destino").text("No aplica cuenta destino.");
        return;
    }

    const sugerido = sugerirDestinoId(metodoSel, monedaSel);
    if (sugerido) {
        $("#destino_input").val(sugerido);
        $("#help_destino").text("Auto: cuenta sugerida según configuración.");
    } else {
        $("#help_destino").text("No hay regla configurada para este caso.");
    }
}

function initClienteSelect2() {
    const $sel = $("#cliente_input");
    if ($sel.length === 0) return;
    if (!$.fn.select2) return;

    if ($sel.hasClass("select2-hidden-accessible")) {
        $sel.select2("destroy");
    }

    $sel.select2({
        width: "100%",
        placeholder: "Buscar cliente...",
        allowClear: true,
        dropdownParent: $("#div-ajax")
    });
}

$(document).on("change", "#destino_auto", function(){
    if ($(this).is(":checked")) aplicarDestinoAuto(true);
});

$(document).ready(function(){
    refrescar();
});
</script>
<?= GetDebugMessage() ?>
