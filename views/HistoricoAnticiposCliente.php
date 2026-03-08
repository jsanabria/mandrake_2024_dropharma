<?php

namespace PHPMaker2024\mandrake;

// Page object
$HistoricoAnticiposCliente = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
// HistoricoAnticiposCliente.php (Custom File - PHPMaker 2024)
$accion = Param("accion") ?? "";
$compania_id = 1;

function jsonResponse($arr) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------
// AJAX: aplicaciones modal
if ($accion === "apps") {
    if (ob_get_length()) ob_end_clean();

    $anticipo_id = intval(Param("anticipo_id") ?? 0);
    if ($anticipo_id <= 0) {
        echo "<div class='alert alert-danger'>Anticipo inválido.</div>";
        exit;
    }

    $apps = ExecuteRows("
        SELECT
        a.id,
        a.fecha,
        a.salida_id,
        s.nro_documento,
        a.moneda,
        a.monto_moneda,
        IFNULL(a.tasa_factura, 1) AS tasa_factura,
        (a.monto_moneda * IFNULL(a.tasa_factura, 1)) AS monto_bs,
        a.username
        FROM anticipos_aplicaciones a
        LEFT JOIN salidas s ON s.id = a.salida_id
        WHERE a.anticipo_cobro_id = " . intval($anticipo_id) . "
        ORDER BY a.fecha DESC, a.id DESC
    ");

    if (!$apps) {
        echo "<div class='alert alert-info mb-0'>Este anticipo no tiene aplicaciones.</div>";
        exit;
    }
    ?>
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>Fecha</th>
            <th>Factura</th>
            <th class="text-end">Moneda</th>
            <th class="text-end">Monto</th>
            <th class="text-end">Tasa</th>
            <th class="text-end">Bs.</th>
            <th>Usuario</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($apps as $a): ?>
            <tr>
              <td><?= HtmlEncode($a["fecha"] ?? "") ?></td>
              <td>
                <?= HtmlEncode($a["nro_documento"] ?? "") ?>
                <div class="small text-muted">Salida ID: <?= intval($a["salida_id"] ?? 0) ?></div>
              </td>
              <td class="text-end"><?= HtmlEncode($a["moneda"] ?? "") ?></td>
              <td class="text-end"><?= number_format(floatval($a["monto_moneda"] ?? 0), 2, ".", ",") ?></td>
              <td class="text-end"><?= number_format(floatval($a["tasa_factura"] ?? 0), 2, ".", ",") ?></td>
              <td class="text-end fw-bold"><?= number_format(floatval($a["monto_bs"] ?? 0), 2, ".", ",") ?></td>
              <td><?= HtmlEncode($a["username"] ?? "") ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
    exit;
}

// -------------------------
// AJAX: detalle del anticipo (modal)
if ($accion === "det") {
    if (ob_get_length()) ob_end_clean();

    $anticipo_id = intval(Param("anticipo_id") ?? 0);
    if ($anticipo_id <= 0) {
        echo "<div class='alert alert-danger'>Anticipo inválido.</div>";
        exit;
    }

    // Cabecera anticipo (para mostrar cliente/fecha si quieres)
    $cab = ExecuteRow("
        SELECT cc.id, cc.fecha, cc.username, c.nombre AS cliente_nom
        FROM cobros_cliente cc
        LEFT JOIN cliente c ON c.id = cc.cliente
        WHERE cc.id = " . intval($anticipo_id) . " AND cc.id_documento = 0
        LIMIT 1
    ");

    $rows = ExecuteRows("
        SELECT
          d.id,
          d.metodo_pago,
          p.valor2 AS metodo_nom,
          d.referencia,
          d.moneda,
          d.monto_moneda,
          d.tasa_moneda,
          d.monto_bs,

          d.banco_origen,
          t_origen.campo_descripcion AS banco_origen_nom,

          d.banco AS cuenta_destino_id,
          CONCAT(TRIM(t_dest.campo_descripcion),
                 CASE WHEN IFNULL(cc.numero,'')<>'' THEN CONCAT(' · ', TRIM(cc.numero)) ELSE '' END
          ) AS cuenta_destino_nom

        FROM cobros_cliente_detalle d
        LEFT JOIN parametro p
          ON p.codigo='009' AND p.valor1 = d.metodo_pago

        LEFT JOIN tabla t_origen
          ON t_origen.tabla='BANCO' AND t_origen.campo_codigo = d.banco_origen

        LEFT JOIN compania_cuenta cc
          ON cc.id = d.banco

        LEFT JOIN tabla t_dest
          ON t_dest.tabla='BANCO' AND t_dest.campo_codigo = cc.banco

        WHERE d.cobros_cliente = " . intval($anticipo_id) . "
        ORDER BY d.id ASC
    ");

    if (!$rows) {
        echo "<div class='alert alert-info mb-0'>No hay detalle para este anticipo.</div>";
        exit;
    }
    ?>
    <div class="mb-2">
      <div class="fw-bold">Anticipo #<?= intval($anticipo_id) ?></div>
      <?php if ($cab): ?>
        <div class="small text-muted">
          Cliente: <b><?= HtmlEncode($cab["cliente_nom"] ?? "") ?></b>
          · Fecha: <b><?= HtmlEncode($cab["fecha"] ?? "") ?></b>
          · Usuario: <b><?= HtmlEncode($cab["username"] ?? "") ?></b>
        </div>
      <?php endif; ?>
    </div>

    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th>Método</th>
            <th>Referencia</th>
            <th class="text-end">Moneda</th>
            <th class="text-end">Monto</th>
            <th class="text-end">Tasa</th>
            <th class="text-end">Bs.</th>
            <th>Banco origen</th>
            <th>Cuenta destino</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td class="fw-bold"><?= HtmlEncode($r["metodo_nom"] ?? $r["metodo_pago"] ?? "") ?></td>
              <td><?= HtmlEncode($r["referencia"] ?? "") ?></td>
              <td class="text-end"><?= HtmlEncode($r["moneda"] ?? "") ?></td>
              <td class="text-end"><?= number_format(floatval($r["monto_moneda"] ?? 0), 2, ".", ",") ?></td>
              <td class="text-end"><?= number_format(floatval($r["tasa_moneda"] ?? 0), 2, ".", ",") ?></td>
              <td class="text-end fw-bold"><?= number_format(floatval($r["monto_bs"] ?? 0), 2, ".", ",") ?></td>
              <td>
                <?= HtmlEncode(trim((string)($r["banco_origen_nom"] ?? ""))) ?>
                <?php if (!empty($r["banco_origen"])): ?>
                  <div class="small text-muted"><?= HtmlEncode($r["banco_origen"]) ?></div>
                <?php endif; ?>
              </td>
              <td><?= HtmlEncode(trim((string)($r["cuenta_destino_nom"] ?? ""))) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php
    exit;
}

// -------------------------
// AJAX: refrescar vista
if ($accion === "refrescar") {
    if (ob_get_length()) ob_end_clean();

    $cliente_id = intval(Param("cliente_id") ?? 0);

    $clientes = ExecuteRows("
      SELECT id, nombre, ci_rif
      FROM cliente
      ORDER BY nombre
    ");

    $resumen = ["total_anticipos_bs" => 0, "total_aplicado_bs" => 0, "disponible_bs" => 0];
    $hist = [];

    if ($cliente_id > 0) {
        $total_anticipos_bs = floatval(ExecuteScalar("
            SELECT COALESCE(SUM(d.monto_bs),0)
            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle d ON d.cobros_cliente = cc.id
            WHERE cc.cliente = " . intval($cliente_id) . "
            AND cc.id_documento = 0
        ") ?: 0);

        $total_aplicado_bs = floatval(ExecuteScalar("
            SELECT COALESCE(SUM(a.monto_moneda * IFNULL(a.tasa_factura,1)),0)
            FROM anticipos_aplicaciones a
            JOIN cobros_cliente cc2 ON cc2.id = a.anticipo_cobro_id
            WHERE cc2.cliente = " . intval($cliente_id) . "
        ") ?: 0);

        $disponible_bs = $total_anticipos_bs - $total_aplicado_bs;

        $resumen = [
        "total_anticipos_bs" => $total_anticipos_bs,
        "total_aplicado_bs"  => $total_aplicado_bs,
        "disponible_bs"      => $disponible_bs
        ];

$disponible_bs = floatval($resumen["total_anticipos_bs"] ?? 0) - floatval($resumen["total_aplicado_bs"] ?? 0);

$disponible_bs = floatval($resumen["total_anticipos_bs"] ?? 0) - floatval($resumen["total_aplicado_bs"] ?? 0);

        $hist = ExecuteRows("
            SELECT
              cc.id AS anticipo_id,
              cc.fecha,
              d.moneda,
              SUM(d.monto_moneda) AS monto_moneda,
              SUM(d.monto_bs)     AS monto_bs,
              COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                  AND a.moneda = d.moneda
              ),0) AS aplicado_moneda,
              (SUM(d.monto_moneda) - COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                  AND a.moneda = d.moneda
              ),0)) AS saldo_moneda,
              COALESCE((
                SELECT SUM(a.monto_moneda * a.tasa_factura)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                  AND a.moneda = d.moneda
              ),0) AS aplicado_bs,
              (SUM(d.monto_bs) - COALESCE((
                SELECT SUM(a.monto_moneda * a.tasa_factura)
                FROM anticipos_aplicaciones a
                WHERE a.anticipo_cobro_id = cc.id
                  AND a.moneda = d.moneda
              ),0)) AS saldo_bs
            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle d ON d.cobros_cliente = cc.id
            WHERE cc.cliente = " . intval($cliente_id) . "
              AND cc.id_documento = 0
            GROUP BY cc.id, cc.fecha, d.moneda
            ORDER BY cc.fecha DESC, cc.id DESC, d.moneda
        ");

        $disponible_por_moneda = ExecuteRows("
            SELECT
            d.moneda,
            SUM(d.monto_moneda) AS total_moneda,
            COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                JOIN cobros_cliente cc2 ON cc2.id = a.anticipo_cobro_id
                WHERE cc2.cliente = cc.cliente
                AND a.moneda = d.moneda
            ),0) AS aplicado_moneda,
            (SUM(d.monto_moneda) - COALESCE((
                SELECT SUM(a.monto_moneda)
                FROM anticipos_aplicaciones a
                JOIN cobros_cliente cc2 ON cc2.id = a.anticipo_cobro_id
                WHERE cc2.cliente = cc.cliente
                AND a.moneda = d.moneda
            ),0)) AS saldo_moneda,

            -- saldo en Bs (para esa moneda)
            SUM(d.monto_bs) AS total_bs,
            COALESCE((
                SELECT SUM(a.monto_moneda * IFNULL(a.tasa_factura,1))
                FROM anticipos_aplicaciones a
                JOIN cobros_cliente cc2 ON cc2.id = a.anticipo_cobro_id
                WHERE cc2.cliente = cc.cliente
                AND a.moneda = d.moneda
            ),0) AS aplicado_bs,
            (SUM(d.monto_bs) - COALESCE((
                SELECT SUM(a.monto_moneda * IFNULL(a.tasa_factura,1))
                FROM anticipos_aplicaciones a
                JOIN cobros_cliente cc2 ON cc2.id = a.anticipo_cobro_id
                WHERE cc2.cliente = cc.cliente
                AND a.moneda = d.moneda
            ),0)) AS saldo_bs

            FROM cobros_cliente cc
            JOIN cobros_cliente_detalle d ON d.cobros_cliente = cc.id
            WHERE cc.cliente = " . intval($cliente_id) . "
            AND cc.id_documento = 0
            GROUP BY cc.cliente, d.moneda
            ORDER BY d.moneda
        ");

    }

    $total_anticipos_bs = floatval($resumen["total_anticipos_bs"] ?? 0);
    $total_aplicado_bs  = floatval($resumen["total_aplicado_bs"] ?? 0);
    $disponible_bs      = floatval($resumen["disponible_bs"] ?? 0);
    ?>

    <div class="card shadow-sm border-0 mb-3 bg-light">
      <div class="card-body p-3">
        <div class="row g-2 align-items-end">
          <div class="col-md-8">
            <label class="small fw-bold">CLIENTE</label>
            <select id="cliente_input" class="form-select form-select-sm">
              <option value="">Buscar cliente...</option>
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
            <div class="small text-muted">ANTICIPO DISPONIBLE (Bs.)</div>
            <div class="h5 m-0 text-success"><b><?= number_format($disponible_bs, 2, ".", ",") ?></b></div>
          </div>
        </div>

        <?php if ($cliente_id > 0): ?>
          <div class="row g-2 mt-3">
            <div class="col-md-4">
              <div class="p-2 bg-white rounded border">
                <div class="small text-muted">Total anticipos (Bs.)</div>
                <div class="fw-bold"><?= number_format($total_anticipos_bs, 2, ".", ",") ?></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-2 bg-white rounded border">
                <div class="small text-muted">Total aplicado (Bs.)</div>
                <div class="fw-bold text-danger"><?= number_format($total_aplicado_bs, 2, ".", ",") ?></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-2 bg-white rounded border">
                <div class="small text-muted">Disponible (Bs.)</div>
                <div class="fw-bold text-success"><?= number_format($disponible_bs, 2, ".", ",") ?></div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($cliente_id > 0): ?>
        <div class="row g-2 mt-2">
            <div class="col-12">
            <div class="p-2 bg-white rounded border">
                <div class="small text-muted mb-2">Disponible por moneda</div>

                <?php if (!$disponible_por_moneda): ?>
                <div class="text-muted small">Sin datos.</div>
                <?php else: ?>
                <div class="row g-2">
                    <?php foreach ($disponible_por_moneda as $m): ?>
                    <?php
                        $mon = trim((string)($m["moneda"] ?? ""));
                        $saldoMon = floatval($m["saldo_moneda"] ?? 0);
                        $saldoBsM = floatval($m["saldo_bs"] ?? 0);
                    ?>
                    <div class="col-md-4">
                        <div class="p-2 rounded border h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold"><?= HtmlEncode($mon) ?></div>
                            <div class="small text-muted">Saldo</div>
                        </div>
                        <div class="fs-5 fw-bold">
                            <?= number_format($saldoMon, 2, ".", ",") ?>
                        </div>
                        <div class="small text-muted">
                            Bs. <?= number_format($saldoBsM, 2, ".", ",") ?>
                        </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>
            </div>
        </div>
        <?php endif; ?>

      </div>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-header bg-dark text-white py-2 small fw-bold">
        HISTÓRICO DE ANTICIPOS (<?= count($hist) ?>)
      </div>
      <div class="card-body p-0">
        <?php if ($cliente_id <= 0): ?>
          <div class="p-3 text-muted">Seleccione un cliente para ver su histórico.</div>
        <?php elseif (!$hist): ?>
          <div class="p-3 text-muted">Este cliente no tiene anticipos registrados.</div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-sm table-striped align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Anticipo</th>
                  <th>Fecha</th>
                  <th class="text-end">Moneda</th>
                  <th class="text-end">Monto</th>
                  <th class="text-end">Aplicado</th>
                  <th class="text-end">Saldo</th>
                  <th class="text-end">Saldo (Bs.)</th>
                  <th class="text-center">Detalle</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($hist as $r): ?>
                  <?php
                    $aid = intval($r["anticipo_id"]);
                    $fecha = (string)($r["fecha"] ?? "");
                    $mon = trim((string)($r["moneda"] ?? ""));
                    $monto = floatval($r["monto_moneda"] ?? 0);
                    $aplicado = floatval($r["aplicado_moneda"] ?? 0);
                    $saldo = floatval($r["saldo_moneda"] ?? 0);
                    $saldoBs = floatval($r["saldo_bs"] ?? 0);
                  ?>
                  <tr>
                    <td class="fw-bold">#<?= $aid ?></td>
                    <td><?= HtmlEncode($fecha) ?></td>
                    <td class="text-end"><?= HtmlEncode($mon) ?></td>
                    <td class="text-end"><?= number_format($monto, 2, ".", ",") ?></td>
                    <td class="text-end text-danger"><?= number_format($aplicado, 2, ".", ",") ?></td>
                    <td class="text-end fw-bold"><?= number_format($saldo, 2, ".", ",") ?></td>
                    <td class="text-end fw-bold text-success"><?= number_format($saldoBs, 2, ".", ",") ?></td>
                    <td class="text-center">
                        <!-- Imprimir -->
                        <a href="reportes/ComprobanteAnticipo.php?anticipo_id=<?= $aid ?>"
                            target="_blank"
                            class="btn btn-sm btn-light border"
                            title="Imprimir comprobante">
                            <i class="fas fa-print"></i>
                        </a>

                        <!-- Ver detalle -->
                        <button type="button"
                                class="btn btn-sm btn-outline-secondary me-1"
                                onclick="verDet(<?= $aid ?>)">
                            Ver anticipo
                        </button>

                        <!-- Ver aplicaciones -->
                        <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                onclick="verApps(<?= $aid ?>)">
                            Ver aplicaciones
                        </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Modal aplicaciones -->
    <div class="modal fade" id="mdlApps" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Aplicaciones del anticipo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body" id="appsBody">
            <div class="text-center p-4"><div class="spinner-border text-primary"></div></div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="mdlDet" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del anticipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="detBody">
                <div class="text-center p-4"><div class="spinner-border text-primary"></div></div>
            </div>
            </div>
        </div>
    </div>

    <script>
      // Cliente select2 + refrescar
      function initClienteSelect2() {
        const $sel = $("#cliente_input");
        if (!$sel.length) return;
        if (!$.fn.select2) return;

        if ($sel.hasClass("select2-hidden-accessible")) $sel.select2("destroy");

        $sel.select2({
          width: "100%",
          placeholder: "Buscar cliente...",
          allowClear: true,
          dropdownParent: $("#div-ajax")
        });
      }

      function verApps(anticipoId) {
        const data = { accion: "apps", anticipo_id: anticipoId };

        if (window.ew) {
          if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
          if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
        }

        $("#appsBody").html("<div class='text-center p-4'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
          url: window.location.href,
          type: "POST",
          data: data,
          success: function(html){
            $("#appsBody").html(html);
            const el = document.getElementById("mdlApps");
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            modal.show();
          },
          error: function(xhr){
            $("#appsBody").html("<div class='alert alert-danger'>Error cargando detalle.</div>");
            console.log(xhr.responseText);
          }
        });
      }

    function verDet(anticipoId) {
        const data = { accion: "det", anticipo_id: anticipoId };

        if (window.ew) {
            if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
            if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
        }

        $("#detBody").html("<div class='text-center p-4'><div class='spinner-border text-primary'></div></div>");

        $.ajax({
            url: window.location.href,
            type: "POST",
            data: data,
            success: function(html){
            $("#detBody").html(html);
            const el = document.getElementById("mdlDet");
            const modal = bootstrap.Modal.getOrCreateInstance(el);
            modal.show();
            },
            error: function(xhr){
            $("#detBody").html("<div class='alert alert-danger'>Error cargando detalle del anticipo.</div>");
            console.log(xhr.responseText);
            }
        });
    }      

      // inicialización del bloque
      initClienteSelect2();
    </script>

    <?php
    exit;
}
?>

<div class="container-fluid py-3" style="max-width: 1100px;">
  <div id="div-ajax">
    <div class="text-center p-5"><div class="spinner-border text-primary"></div></div>
  </div>
</div>

<input type="hidden" id="cliente_input_out" value="">

<script>
  function refrescar() {
    const data = {
      accion: "refrescar",
      cliente_id: ($("#cliente_input_out").val() || "").toString().trim()
    };

    if (window.ew) {
      if (ew.TOKEN_NAME_KEY && ew.TOKEN_NAME) data[ew.TOKEN_NAME_KEY] = ew.TOKEN_NAME;
      if (ew.ANTIFORGERY_TOKEN_KEY && ew.ANTIFORGERY_TOKEN) data[ew.ANTIFORGERY_TOKEN_KEY] = ew.ANTIFORGERY_TOKEN;
    }

    $.ajax({
      url: window.location.href,
      type: "POST",
      data: data,
      success: function(r){ 
          $("#div-ajax").html(r);
          initClienteSelect2();
          bindClienteChange();
      },
      error: function(xhr){
        console.log(xhr.responseText);
        $("#div-ajax").html("<div class='alert alert-danger'>AJAX falló.</div>");
      }
    });
  }

    function bindClienteChange() {
    $(document).off("change", "#cliente_input").on("change", "#cliente_input", function () {
        const cid = ($(this).val() || "").toString().trim();
        $("#cliente_input_out").val(cid);
        refrescar();
    });
    }
  $(document).ready(function(){ refrescar(); });
</script>
<?= GetDebugMessage() ?>
