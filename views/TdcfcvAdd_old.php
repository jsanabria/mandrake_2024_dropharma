<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcfcvAdd = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$codcli = isset($_REQUEST["codcli"]) ? $_REQUEST["codcli"] : 0;
$tipo_documento = $_REQUEST["tipo_documento"];

$sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$row = ExecuteRow($sql);
$moneda = $row["moneda"];

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$row = ExecuteRow($sql); 
$tasa = floatval($row["tasa"]);

/// Pregunto si el sistema factura en Bs ///
$sql = "SELECT valor1 AS fact_bs FROM parametro WHERE codigo = '053';";
$fact_bs = ExecuteScalar($sql);

if($fact_bs == "S") $moneda = "Bs.";

$pedido = 0;
$consignacion = "";
$nota = "";
$doc_afectado = "";
$nro_documento = "";
$descTransferencista = 0.00;
$descFabricante = 0.00;
$id_documento_padre = 0;
if(isset($_REQUEST["pedido"])) {
    $pedido = $_REQUEST["pedido"];
    $sql = "SELECT cliente, tipo_documento, nota, tasa_dia, moneda, documento, IFNULL(doc_afectado, '') AS doc_afectado, nro_documento, IFNULL(descuento2, 0) AS descuento2, IFNULL(descuento3, 0) AS descuento3, IFNULL(id_documento_padre, 0) AS id_documento_padre FROM salidas WHERE id = $pedido;";
    if($row = ExecuteRow($sql)) {
      $codcli = $row["cliente"];
      $tipo_documento = $_REQUEST["tipo_documento"];
      $nota = $row["nota"];
      $tasa = floatval($row["tasa_dia"]);
      $moneda = $row["moneda"]; 
      $consignacion = $row["documento"]; 
      $doc_afectado = $row["doc_afectado"]; 
      $nro_documento = $row["nro_documento"]; 
      $descTransferencista = floatval($row["descuento2"]);
      $descFabricante = floatval($row["descuento3"]);
      $id_documento_padre = intval($row["id_documento_padre"]);
    } 
    else {
      header("Location: ViewOutTdcfcvList");
      die();
    }
} 

$sql = "SELECT ci_rif, nombre FROM cliente WHERE id = $codcli;";
$row = ExecuteRow($sql);
$cliente = $row["nombre"];

$PorDesMin = 0;
$PorDesMax = 100;
$PorDesAct = intval((isset($_REQUEST["PorDesAct"]) ? $_REQUEST["PorDesAct"] : 0));

$puedeModificarPrecioDescuento = false;
if (function_exists(__NAMESPACE__ . "\\VerificaFuncion")) {
    $puedeModificarPrecioDescuento = VerificaFuncion("040");
}

$urlImprimir = "#";
if (intval($pedido) > 0) {
    $urlImprimir = "reportes/factura_de_venta.php?id=" . intval($pedido) . "&tipo=TDCFCV";
}

$puedeEditarCodigoBarra = false;

if (CurrentUserLevel() == -1) {
    $puedeEditarCodigoBarra = true;
} else {
    $nivel = intval(CurrentUserLevel());

    $sql_permiso = "
        SELECT permission
        FROM userlevelpermissions
        WHERE userlevelid = $nivel
          AND tablename LIKE '%}articulo'
        LIMIT 1
    ";

    $row_permiso = ExecuteRow($sql_permiso);

    if ($row_permiso) {
        $permiso = intval($row_permiso["permission"]);

        $puedeAgregar = (($permiso & 1) == 1);
        $puedeModificar = (($permiso & 4) == 4);

        if ($puedeAgregar || $puedeModificar) {
            $puedeEditarCodigoBarra = true;
        }
    }
}

$fabricantesNuevo = ExecuteRows("SELECT Id, nombre FROM fabricante WHERE activo = 'S' ORDER BY nombre");
$alicuotasNuevo = ExecuteRows("SELECT codigo, nombre FROM alicuota WHERE activo = 'S' ORDER BY nombre");
$categoriasNuevo = ExecuteRows("SELECT campo_codigo AS id, campo_descripcion AS nombre FROM tabla WHERE tabla = 'CATEGORIA' ORDER BY campo_descripcion");

$preguntarNE = false;

$rowParametro111 = ExecuteRow("SELECT valor1 FROM parametro WHERE codigo = '111' LIMIT 1");
if ($rowParametro111 && strtoupper(trim($rowParametro111["valor1"])) == "S") {
    $preguntarNE = true;
}
?>


<div class="container border border-primary border-top rounded p-3">
  <div class="row">
    <div class="col-sm-8">
      <h5>Cliente: <?= $cliente ?></h5>
      <div id="nroPedido">
          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Documento Id.: <?= $pedido ?> </button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:vaciar(' . $pedido . ')"' : ''); ?> class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:getCodigos3(' . $pedido . ')"' : ''); ?> class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace('ViewOutTdcfcvList');"><i class="fa-solid fa-list"></i> Facturas </button>
      </div>
      <input name="pedido" id="pedido" type="hidden" value="<?= $pedido ?>" />
      <input name="tipo_documento" id="tipo_documento" type="hidden" value="<?= $tipo_documento ?>" />
      <input name="codcli" id="codcli" type="hidden" value="<?= $codcli ?>" />
        <input name="nro_documento" id="nro_documento" type="hidden" value="<?= $nro_documento ?>" />
      <!--<input name="tasa_usd" id="tasa_usd" type="hidden" value="<?= $tasa ?>" />-->
      <input name="username" id="username" type="hidden" class="form-control form-control-sm" value="<?= CurrentUserName() ?>" />
    </div>
    <div class="col-sm-4">
    <h2 class="text-end"><i class="fa-solid fa-comments-dollar"></i> <span class="badge text-bg-secondary"><?= number_format($tasa, 2, ".", ",") ?> Bs.</span></h2>
    </div>
  </div>

<hr class="border border-primary" />

  <input type="hidden" id="PorDesMin" name="PorDesMin" value="<?= $PorDesMin ?>">
  <input type="hidden" id="PorDesMax" name="PorDesMax" value="<?= $PorDesMax ?>">
  <input type="hidden" id="PorDesAct" name="PorDesAct" value="<?= $PorDesAct ?>">

<div class="row g-3 align-items-end">

    <div class="col-12 col-md-4">
        <div class="border rounded p-2 bg-light h-100">
            <label for="rangoDescuentoCliente" class="form-label small fw-bold mb-1">
                Descuento cliente:
                <span id="lblDescuentoCliente" class="text-primary"><?= intval($PorDesAct) ?>%</span>
            </label>

            <input type="range"
                   class="form-range"
                   min="<?= $PorDesMin ?>"
                   max="99"
                   step="1"
                   id="rangoDescuentoCliente"
                   value="<?= intval($PorDesAct) ?>">
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="border rounded p-2 bg-light h-100">
            <input name="doc_afectado" id="doc_afectado" type="hidden" value="<?= $doc_afectado ?>" />

            <?php if (trim($doc_afectado) != "") { ?>
                <small class="d-block text-muted mb-1">
                    Doc Afect.: <?= $doc_afectado ?>
                </small>
            <?php } ?>

            <label for="rangoTransferencista" class="form-label small fw-bold mb-1">
                % Transferencista:
                <span id="lblTransferencista" class="text-primary"><?= intval($descTransferencista) ?>%</span>
            </label>

            <input type="hidden"
                   id="descTransferencista"
                   name="descTransferencista"
                   value="<?= $descTransferencista ?>">

            <input type="range"
                   class="form-range"
                   min="0"
                   max="99"
                   step="1"
                   id="rangoTransferencista"
                   value="<?= intval($descTransferencista) ?>">
        </div>
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label small fw-bold mb-1">Moneda</label>

        <select id="moneda" name="moneda" class="form-select form-select-sm"
            <?php if (intval($pedido) == 0) { ?>
                onchange="js:RefreshMonedaTasa()"
            <?php } else { ?>
                onfocus="this.defaultIndex=this.selectedIndex;"
                onchange="this.selectedIndex=this.defaultIndex;"
            <?php } ?>
        >
            <?php
            $sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006';";
            $rows = ExecuteRows($sql);

            foreach ($rows as $value) {
                echo '<option value="' . $value["moneda"] . '"' . ($value["moneda"] == $moneda ? ' selected="selected"' : '') . '>' . $value["moneda"] . '</option>';
            }
            ?>
        </select>
    </div>

    <div class="col-6 col-md-2">
        <label class="form-label small fw-bold mb-1">Tasa B.C.V.</label>

        <input name="tasa_usd"
               id="tasa_usd"
               type="number"
               class="form-control form-control-sm bg-light text-end"
               value="<?= $tasa ?>"
               onkeyup="js:RefreshMonedaTasa()"
               readonly>
    </div>

    <div style="display:none;">
        <input type="text"
               id="descFabricante"
               name="descFabricante"
               value="<?= ($descFabricante ?? '') ?>"
               class="form-control form-control-sm"
               readonly>
    </div>

</div>

<hr class="border border-primary" />

  <div class="row">
    <div class="col-sm-3">
      <div>
        <div class="row">
          <div class="col-sm-3 text-center">
            <h1 class="px-3 py-3">R</h1>
          </div>
          <div class="col-sm-9 alert alert-info" role="alert">
            <h6>Renglones</h6>
            <span id="xReglones">0</span>
          </div>
        </div>
      </div>      
    </div>

    <div class="col-sm-3">
      <div>
        <div class="row">
          <div class="col-sm-3">
            <h1 class="px-3 py-3">U</h1>
          </div>
          <div class="col-sm-9 alert alert-info" role="alert">
            <h6>Unidades</h6>
            <span id="xUnidades">0</span>
          </div>
        </div>
      </div>      
    </div>

    <div class="col-sm-3">
      <div>
        <div class="row">
          <div class="col-sm-3">
            <h1 class="px-3 py-3">$</h1>
          </div>
          <div class="col-sm-9 alert alert-info" role="alert">
            <h6>Monto en <?= (strtoupper($moneda)=="BS."?"USD":$moneda) ?> </h6>
            <span id="xTotalBs">0.00</span>
          </div>
        </div>
      </div>      
    </div>

    <div class="col-sm-3">
      <div>
        <div class="row">
          <div class="col-sm-3">
            <h1 class="px-3 py-3">$</h1>
          </div>
          <div class="col-sm-9 alert alert-info" role="alert">
            <h6>Monto en Bs.</h6>
            <span id="xTotalUSD">0.00</span>
          </div>
        </div>
      </div>      
    </div>

  </div>

<hr class="border border-primary" />

  <div class="row align-items-end g-3">

      <div class="col-md-2">
          <label class="form-label mb-1 fw-bold">Tipo Documento:</label>
          <select id="consignacion" name="consignacion" class="form-select form-select-sm" disabled="disabled">
              <option value="">TIPO DOCUMENTO</option>
              <option value="FC"<?= ($consignacion=="FC" ? ' selected="selected"' : '') ?>>FACTURA</option>
              <option value="NC"<?= ($consignacion=="NC" ? ' selected="selected"' : '') ?>>NOTA DE CREDITO</option>
              <option value="ND"<?= ($consignacion=="ND" ? ' selected="selected"' : '') ?>>NOTA DE DEBITO</option>
          </select>
      </div>

      <div class="col-md-1 text-center">
          <label class="form-label d-block mb-1">Rep. Art.</label>
          <input type="checkbox" id="hubb" name="hubb" value="SI" checked>
      </div>

      <div class="col-md-3">
          <label class="form-label mb-1 fw-bold">Fabricante:</label>
          <input name="laboratorio" id="laboratorio" type="text"
                 class="form-control form-control-sm w-100"
                 placeholder="Buscar Laboratorio" />
          <input name="codlab" id="codlab" type="hidden" class="form-control form-control-sm" />
          <ul id="lista" class="list-group"></ul>
      </div>

        <div class="col-md-3">
            <label class="form-label mb-1 fw-bold">Artículo:</label>
            <input name="articulo" id="articulo" type="text"
                class="form-control form-control-sm w-100"
                placeholder="Buscar Artículo" />
        </div>

        <div class="col-md-1">
            <label class="form-label mb-1">&nbsp;</label>
            <button type="button"
                    id="btnBuscarArticulo"
                    class="btn btn-primary btn-sm w-100">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div class="col-md-2 text-end">
            <label class="form-label mb-1">&nbsp;</label>
            <button type="button"
                    id="btnNuevoArticulo"
                    class="btn btn-success btn-sm w-100"
                    <?= (!$puedeEditarCodigoBarra ? "disabled" : "") ?>
                    onclick="abrirModalNuevoArticulo();">
                <i class="fa-solid fa-plus"></i> Nuevo Artículo
            </button>
        </div>

  </div>

<hr class="border border-primary" />

  <div class="row">
    <div class="col-sm-12">

      <div class="table-responsive" id="lista2">
        <table class="table table-bordered table-hover table-striped table-sm">
          <thead>
            <tr>
              <td colspan="10">
                <div class="col-12 d-flex justify-content-center" id="Paginacion1">
                </div>
              </td>
            </tr>          
            <tr>
              <th width="10%">&nbsp</th>
              <th width="20%">At&iacute;culo</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="10%" class="text-center">Lote</th>
              <th width="10%" class="text-center">Vence</th>
              <th width="10%" class="text-center">Precio Full</th>
              <th width="10%" class="text-center">% Desc.</th>
              <th width="10%" class="text-center">Precio</th>
              <th width="10%" class="text-center">Total</th>
              <th width="10%" class="text-center">Agr/Eli</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </div>
      <strong>Observaciones:</strong>
      <textarea cols="35" rows="3" placeholder="Observaciones" class="form-control form-control-sm" id="nota" onchange="js:guardar_nota();"><?= $nota ?></textarea>
  </div>
</div>

<script type="text/javascript">
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

    let descuentoRangeOriginal = parseInt($("#PorDesAct").val() || 0, 10);

    $(document).on("mousedown touchstart focus", "#rangoDescuentoCliente", function () {
        descuentoRangeOriginal = parseInt($("#PorDesAct").val() || 0, 10);
    });

    $(document).on("input", "#rangoDescuentoCliente", function () {
        $("#lblDescuentoCliente").text($(this).val() + "%");
    });

    $(document).on("input", "#rangoTransferencista", function () {
        $("#lblTransferencista").text($(this).val() + "%");
    });

    $(document).on("change", "#rangoDescuentoCliente", function () {
        const pedido = getVal("pedido");
        const nuevo = parseInt($(this).val(), 10);

        if (pedido == 0) {
            $(this).val(descuentoRangeOriginal);
            $("#lblDescuentoCliente").text(descuentoRangeOriginal + "%");
            return false;
        }

        descuentoGlobalPendiente = {
            pedido: pedido,
            original: descuentoRangeOriginal,
            nuevo: nuevo
        };

        if (puedeModificarPrecioDescuento) {
            AplicarDescuentoGlobalPendiente();
            return;
        }

        $("#auth_user_tdcfcv").val("");
        $("#auth_pass_tdcfcv").val("");
        showModalAutorizarTdcfcv();
    });

    let transferenciaOriginal = parseInt(
        $("#descTransferencista").val() || 0,
        10
    );

    $(document).on(
        "mousedown touchstart focus",
        "#rangoTransferencista",
        function () {

            transferenciaOriginal = parseInt(
                $("#descTransferencista").val() || 0,
                10
            );
        }
    ); 

    $(document).on("change", "#rangoTransferencista", function () {

        const nuevo = parseInt($(this).val(), 10);

        transferenciaPendiente = {
            original: transferenciaOriginal,
            nuevo: nuevo
        };

        if (puedeModificarPrecioDescuento) {

            $("#descTransferencista").val(nuevo);

            DiasCred();

            return;
        }

        $("#auth_user_tdcfcv").val("");
        $("#auth_pass_tdcfcv").val("");

        showModalAutorizarTdcfcv();
    });

    const formatter = new Intl.NumberFormat("es-PE", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function alertMsg(msg) {
        if (typeof ew !== "undefined" && ew.alert) {
            ew.alert(msg);
        } else {
            ew.alert(msg);
        }
    }

    function normalizeJson(data) {
        if (typeof data === "string") {
            try {
                return JSON.parse(data);
            } catch (e) {
                return data;
            }
        }
        return data;
    }

    function getVal(id) {
        return $("#" + id).val() ?? "";
    }

    const puedeModificarPrecioDescuento = <?= ($puedeModificarPrecioDescuento ? "true" : "false") ?>;

    let revirtiendoAutorizacionTdcfcv = false;

    let cambioAutorizacionTdcfcv = {
        tipo: "",
        inputId: "",
        valorOriginal: "",
        valorNuevo: "",
        fila: 0
    };

    function limpiarCambioAutorizacionTdcfcv() {
        cambioAutorizacionTdcfcv = {
            tipo: "",
            inputId: "",
            valorOriginal: "",
            valorNuevo: "",
            fila: 0
        };
    }

    function obtenerFilaDesdeInputId(inputId) {
        const match = String(inputId || "").match(/^x(\d+)_/);
        return match ? parseInt(match[1], 10) : 0;
    }

    function showModalAutorizarTdcfcv() {
        const modalEl = document.getElementById("modalAutorizarTdcfcv");
        if (!modalEl) return;

        if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (typeof jQuery !== "undefined" && jQuery.fn.modal) {
            jQuery(modalEl).modal("show");
        }
    }

    function hideModalAutorizarTdcfcv() {
        const modalEl = document.getElementById("modalAutorizarTdcfcv");
        if (!modalEl) return;

        if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        } else if (typeof jQuery !== "undefined" && jQuery.fn.modal) {
            jQuery(modalEl).modal("hide");
        }
    }

    function restaurarCambioAutorizacionTdcfcv() {
        if (cambioAutorizacionTdcfcv.inputId !== "") {
            revirtiendoAutorizacionTdcfcv = true;
            const $input = $("#" + cambioAutorizacionTdcfcv.inputId);
            $input.val(cambioAutorizacionTdcfcv.valorOriginal);
            $input.attr("data-original", cambioAutorizacionTdcfcv.valorOriginal);

            if (cambioAutorizacionTdcfcv.tipo === "item" && cambioAutorizacionTdcfcv.fila > 0) {
                myCalc(cambioAutorizacionTdcfcv.fila);
            }

            setTimeout(function () {
                revirtiendoAutorizacionTdcfcv = false;
            }, 250);
        }

        limpiarCambioAutorizacionTdcfcv();
    }

    function validarCambioAutorizacionTdcfcv($input) {
        const id = $input.attr("id") || "";
        const valor = parseFloat($input.val() || 0);

        if (id === "descTransferencista" && (valor < 0 || valor >= 100)) {
            alertMsg("El descuento de transferencista debe estar entre 0 y 99.");
            return false;
        }

        if (id.indexOf("_precioFull") >= 0 && valor < 0) {
            alertMsg("El precio full no puede ser menor a 0.");
            return false;
        }

        if ((id.indexOf("_descuento") >= 0 || id.indexOf("_descuento2") >= 0) && (valor < 0 || valor >= 100)) {
            alertMsg("El descuento debe estar entre 0 y 99.");
            return false;
        }

        return true;
    }

    function aplicarCambioAutorizadoTdcfcv() {
        if (cambioAutorizacionTdcfcv.inputId === "") return;

        const $input = $("#" + cambioAutorizacionTdcfcv.inputId);
        $input.attr("data-original", cambioAutorizacionTdcfcv.valorNuevo);

        if (cambioAutorizacionTdcfcv.tipo === "transferencista") {
            DiasCred();
        } else if (cambioAutorizacionTdcfcv.tipo === "item" && cambioAutorizacionTdcfcv.fila > 0) {
            myCalc(cambioAutorizacionTdcfcv.fila);
        }

        limpiarCambioAutorizacionTdcfcv();
    }

    function prepararAutorizacionTdcfcv($input, tipo) {
        const inputId = $input.attr("id");
        const valorOriginal = $input.attr("data-original") ?? "";
        const valorNuevo = $input.val() ?? "";
        const fila = obtenerFilaDesdeInputId(inputId);

        if (String(valorOriginal) === String(valorNuevo)) return false;

        cambioAutorizacionTdcfcv.tipo = tipo;
        cambioAutorizacionTdcfcv.inputId = inputId;
        cambioAutorizacionTdcfcv.valorOriginal = valorOriginal;
        cambioAutorizacionTdcfcv.valorNuevo = valorNuevo;
        cambioAutorizacionTdcfcv.fila = fila;

        if (!validarCambioAutorizacionTdcfcv($input)) {
            restaurarCambioAutorizacionTdcfcv();
            return false;
        }

        if (puedeModificarPrecioDescuento) {
            aplicarCambioAutorizadoTdcfcv();
            return false;
        }

        $("#auth_user_tdcfcv").val("");
        $("#auth_pass_tdcfcv").val("");
        showModalAutorizarTdcfcv();
        return true;
    }

    $(document).on("focus", ".tdcfcv-autorizado, #descTransferencista", function () {
        $(this).attr("data-original", $(this).val());
    });

    $(document).on("change", ".tdcfcv-autorizado", function () {
        if (revirtiendoAutorizacionTdcfcv) return;
        prepararAutorizacionTdcfcv($(this), "item");
    });

    $(document).on("change", "#descTransferencista", function () {
        if (revirtiendoAutorizacionTdcfcv) return;
        prepararAutorizacionTdcfcv($(this), "transferencista");
    });

    $(document).on("click", "#btnCancelarAutorizarTdcfcv", function () {
        if (transferenciaPendiente.original !== "") {
            $("#descTransferencista").val(
                transferenciaPendiente.original
            );

            $("#rangoTransferencista").val(
                transferenciaPendiente.original
            );

            $("#lblTransferencista").text(
                transferenciaPendiente.original + "%"
            );

            transferenciaPendiente = {
                original: "",
                nuevo: ""
            };

            hideModalAutorizarTdcfcv();
            return;
        }

        if (descuentoGlobalPendiente.pedido !== "") {
            PintarProgressBar(descuentoGlobalPendiente.original);

            descuentoGlobalPendiente = {
                pedido: "",
                original: "",
                nuevo: ""
            };

            hideModalAutorizarTdcfcv();
            return;
        }

        restaurarCambioAutorizacionTdcfcv();
        hideModalAutorizarTdcfcv();
    });

    $(document).on("click", "#btnAceptarAutorizarTdcfcv", function () {
        const xuser = $("#auth_user_tdcfcv").val().trim();
        const xpass = $("#auth_pass_tdcfcv").val().trim();

        const tipo_documento = $("#tipo_documento").val();
        const nro_documento  = $("#nro_documento").val();
        const usercaja       = $("#username").val();
        const pedido         = $("#pedido").val();

        if (xuser === "" || xpass === "") {
            alertMsg("Debe indicar usuario autorizador y clave.");
            return false;
        }

        $("#btnAceptarAutorizarTdcfcv")
            .prop("disabled", true)
            .html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Validando...');

        $.ajax({
            url: "include/Validar_Usuario_desc_precio.php",
            type: "GET",
            data: {
                usernama: xuser,
                password: xpass,
                contexto: "TDCFCV_PRECIO_DESCUENTO",
                tipo_documento: tipo_documento,
                nro_documento: nro_documento,
                usercaja: usercaja,
                idPurga: pedido
            }
        })
        .done(function (result) {
            if (String(result).trim() === "S") {
                if (descuentoGlobalPendiente.pedido !== "") {
                    AplicarDescuentoGlobalPendiente();
                    hideModalAutorizarTdcfcv();
                    return;
                }

                if (transferenciaPendiente.original !== "") {
                    $("#descTransferencista").val(
                        transferenciaPendiente.nuevo
                    );

                    $("#rangoTransferencista").val(
                        transferenciaPendiente.nuevo
                    );

                    $("#lblTransferencista").text(
                        transferenciaPendiente.nuevo + "%"
                    );

                    DiasCred();

                    transferenciaPendiente = {
                        original: "",
                        nuevo: ""
                    };

                    hideModalAutorizarTdcfcv();
                    return;
                }


                aplicarCambioAutorizadoTdcfcv();
                hideModalAutorizarTdcfcv();
            } else {
                alertMsg("!!! NO AUTORIZADO !!!");

                if (transferenciaPendiente.original !== "") {
                    $("#descTransferencista").val(transferenciaPendiente.original);
                    $("#rangoTransferencista").val(transferenciaPendiente.original);
                    $("#lblTransferencista").text(transferenciaPendiente.original + "%");

                    transferenciaPendiente = {
                        original: "",
                        nuevo: ""
                    };

                    hideModalAutorizarTdcfcv();
                    return;
                }

                if (descuentoGlobalPendiente.pedido !== "") {
                    PintarProgressBar(descuentoGlobalPendiente.original);

                    descuentoGlobalPendiente = {
                        pedido: "",
                        original: "",
                        nuevo: ""
                    };

                    hideModalAutorizarTdcfcv();
                    return;
                }

                restaurarCambioAutorizacionTdcfcv();
                hideModalAutorizarTdcfcv();
            }
        })
        .fail(function () {
            alertMsg("Error de comunicación con el servidor.");
            
            if (transferenciaPendiente.original !== "") {
                $("#descTransferencista").val(transferenciaPendiente.original);
                $("#rangoTransferencista").val(transferenciaPendiente.original);
                $("#lblTransferencista").text(transferenciaPendiente.original + "%");

                transferenciaPendiente = {
                    original: "",
                    nuevo: ""
                };

                hideModalAutorizarTdcfcv();
                return;
            }

            if (descuentoGlobalPendiente.pedido !== "") {
                PintarProgressBar(descuentoGlobalPendiente.original);

                descuentoGlobalPendiente = {
                    pedido: "",
                    original: "",
                    nuevo: ""
                };

                hideModalAutorizarTdcfcv();
                return;
            }

            restaurarCambioAutorizacionTdcfcv();
            hideModalAutorizarTdcfcv();
        })
        .always(function () {
            $("#btnAceptarAutorizarTdcfcv")
                .prop("disabled", false)
                .html('<i class="fa-solid fa-check me-1"></i> Autorizar');
        });
    });

    function setPedidoButtons(json) {
        $("#nroPedido").html(
            '<button type="button" class="btn btn-outline-primary btn-sm">' +
            '<i class="fa-solid fa-hashtag"></i> Documento Id.: ' + json.pedido + ' (' + (json.nro_documento ?? "") + ')</button> ' +

            '<button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm">' +
            '<i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> ' +

            '<button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm">' +
            '<i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> ' +

            '<button type="button" onclick="js:sendProccess(' + json.pedido + ')" class="btn btn-outline-success btn-sm">' +
            '<i class="fa-solid fa-microchip"></i> Procesar Documento </button> ' +

            '<button type="button" class="btn btn-outline-primary btn-sm" onclick="js:window.location.replace(\'ViewOutTdcfcvList\');">' +
            '<i class="fa-solid fa-list"></i> Facturas </button>'
        );
    }

    function updateTotals(json) {
        json = normalizeJson(json);

        $("#xReglones").html(json.renglones ?? 0);
        $("#xUnidades").html(json.unidades ?? 0);

        const total = parseFloat(json.total ?? 0);
        const totalUsd = parseFloat(json.total_usd ?? 0);
        const montoSinDescuento = parseFloat(json.monto_sin_descuento ?? 0);
        const totalUsdSinDescuento = parseFloat(json.total_usd_sin_descuento ?? 0);

        if (total === montoSinDescuento) {
            $("#xTotalBs").html(formatter.format(total));
            $("#xTotalUSD").html(formatter.format(totalUsd));
        } else {
            $("#xTotalBs").html(formatter.format(total) + "<br><del>" + formatter.format(montoSinDescuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(totalUsd) + "<br><del>" + formatter.format(totalUsdSinDescuento) + "</del>");
        }
    }

    window.insertar = function (i) {
        const precioFull = $("#x" + i + "_precioFull").val();

        if (isNaN(parseFloat(precioFull))) {
            alertMsg("Debe indicar precio full.");
            return false;
        }

        $("#x" + i + "_boton").html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: "include/tdcfcv/insertar_linea_pedido_tdcfcv.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: getVal("pedido"),
                cliente: getVal("codcli"),
                precioFull: precioFull,
                descuento: $("#x" + i + "_descuento").val(),
                descuento2: $("#x" + i + "_descuento2").val(),
                precio: $("#x" + i + "_precio").val(),
                moneda: getVal("moneda"),
                total: $("#x" + i + "_total").val(),
                cantidad: $("#x" + i + "_cantidad").val(),
                articulo: $("#x" + i + "_articulo").val(),
                tasa_usd: getVal("tasa_usd"),
                username: getVal("username"),
                descuentoG: getVal("PorDesAct"),
                descTransferencista: getVal("descTransferencista"),
                descFabricante: getVal("descFabricante"),
                nota: getVal("nota"),
                consignacion: getVal("consignacion"),
                lote: $("#x" + i + "_lote").val(),
                vence: $("#x" + i + "_vence").val(),
                doc_afectado: getVal("doc_afectado")
            }
        }).done(function (json) {
            json = normalizeJson(json);

            if (json.estatus == 1) {
                setPedidoButtons(json);
                $("#pedido").val(json.pedido);
                updateTotals(json);
                ActualizarVistaPrevia(json.pedido);

                $("#x" + i + "_cantidad, #x" + i + "_precioFull, #x" + i + "_descuento, #x" + i + "_lote, #x" + i + "_vence")
                    .prop("disabled", true);

                $("#x" + i + "_boton").html(
                    '<i class="fa-solid fa-trash text-danger" style="cursor:pointer;" onclick="js:eliminar(' + i + ', ' + json.id_item + ')"></i>'
                );
            } else {
                alertMsg("Error: " + (json.mensaje ?? "No se pudo insertar."));
                $("#x" + i + "_boton").html(
                    '<i class="fa-solid fa-cart-shopping text-primary" style="cursor:pointer;" onclick="js:insertar(' + i + ')"></i>'
                );
            }
            controlarMoneda();
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión al insertar: " + errorThrown);
            $("#x" + i + "_boton").html(
                '<i class="fa-solid fa-cart-shopping text-primary" style="cursor:pointer;" onclick="js:insertar(' + i + ')"></i>'
            );
        });
    };

    window.eliminar = function (i, id_item) {
        if (!confirm("¿ESTÁ SEGURO DE ELIMINAR EL ITEM?")) return false;

        $("#x" + i + "_boton, #x" + i + "_boton_delete").html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: "include/tdcfcv/eliminar_linea_pedido_tdcfcv.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: getVal("pedido"),
                articulo: $("#x" + i + "_articulo").val(),
                moneda: getVal("moneda"),
                tasa_usd: getVal("tasa_usd"),
                username: getVal("username"),
                descuento: getVal("PorDesAct"),
                descTransferencista: getVal("descTransferencista"),
                descFabricante: getVal("descFabricante"),
                id_item: id_item,
                nota: getVal("nota"),
                consignacion: getVal("consignacion"),
                doc_afectado: getVal("doc_afectado")
            }
        }).done(function (json) {
            json = normalizeJson(json);

            if (json.estatus == 1) {
                if (parseInt(json.renglones ?? 0) <= 0 || json.pedido == "0" || json.pedido == "") {
                    window.location.replace("ViewOutTdcfcvList");
                    return;
                }

                getCodigos3(json.pedido);
            } else {
                alertMsg("Error: " + (json.mensaje ?? "No se pudo eliminar."));
                $("#x" + i + "_boton_delete").html(
                    '<i class="fa-solid fa-trash text-danger" style="cursor:pointer;" onclick="js:eliminar(' + i + ', ' + id_item + ')"></i>'
                );
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión al eliminar: " + errorThrown);
        });
    };

    window.habilitarEdicion = function (i, id_item) {
        $("#x" + i + "_cantidad").prop("disabled", false).focus();
        $("#x" + i + "_precioFull").prop("disabled", false);
        $("#x" + i + "_descuento").prop("disabled", false);
        $("#x" + i + "_lote").prop("disabled", false);
        $("#x" + i + "_vence").prop("disabled", false);

        $("#x" + i + "_boton_edit").html(
            '<i class="fa-solid fa-floppy-disk text-success" style="cursor:pointer;" title="Guardar Cambios" onclick="js:guardarModificacion(' + i + ', ' + id_item + ')"></i>'
        );

        $("#x" + i + "_cantidad").closest("tr").addClass("table-warning");
    };

    window.guardarModificacion = function (i, id_item) {
        if (!confirm("¿DESEA GUARDAR LOS CAMBIOS EN ESTE ARTÍCULO?")) return false;

        $("#x" + i + "_boton_edit").html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: "include/tdcfcv/modificar_linea_pedido_tdcfcv.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: getVal("pedido"),
                articulo: $("#x" + i + "_articulo").val(),
                id_item: id_item,
                cantidad: $("#x" + i + "_cantidad").val(),
                precio_full: $("#x" + i + "_precioFull").val(),
                descuento_item: $("#x" + i + "_descuento").val(),
                descuento2_item: $("#x" + i + "_descuento2").val(),
                lote: $("#x" + i + "_lote").val(),
                vence: $("#x" + i + "_vence").val(),
                moneda: getVal("moneda"),
                tasa_usd: getVal("tasa_usd"),
                username: getVal("username"),
                descuento_global: getVal("PorDesAct"),
                descTransferencista: getVal("descTransferencista"),
                descFabricante: getVal("descFabricante"),
                nota: getVal("nota")
            }
        }).done(function (json) {
            json = normalizeJson(json);

            if (json.estatus == 1) {
                $("#x" + i + "_cantidad, #x" + i + "_precioFull, #x" + i + "_descuento, #x" + i + "_lote, #x" + i + "_vence")
                    .prop("disabled", true);

                $("#x" + i + "_cantidad").closest("tr").removeClass("table-warning");

                $("#x" + i + "_boton_edit").html(
                    '<i class="fa-solid fa-pencil text-warning" style="cursor:pointer;" onclick="js:habilitarEdicion(' + i + ', ' + id_item + ')"></i>'
                );

                updateTotals(json);
                alertMsg("¡Cambios guardados correctamente!");
            } else {
                alertMsg("Error: " + (json.mensaje ?? "No se pudo modificar."));
                $("#x" + i + "_boton_edit").html(
                    '<i class="fa-solid fa-floppy-disk text-success" style="cursor:pointer;" onclick="js:guardarModificacion(' + i + ', ' + id_item + ')"></i>'
                );
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión al modificar: " + errorThrown);
        });
    };

    window.vaciar = function (i) {
        if (i <= 0) return false;
        if (!confirm("¿Seguro que quiere vaciar la cesta de pedidos?")) return false;

        $.ajax({
            url: "include/tdcfcv/vaciar_tdcfcv.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: getVal("pedido"),
                username: getVal("username"),
                consignacion: getVal("consignacion"),
                doc_afectado: getVal("doc_afectado")
            }
        }).done(function (json) {
            json = normalizeJson(json);

            $("#pedido").val(json.pedido ?? 0);
            $("#doc_afectado").val(json.doc_afectado ?? "");
            $("#nro_documento").val(json.nro_documento ?? "");

            $("#xReglones").html(0);
            $("#xUnidades").html(0);
            $("#xTotalBs").html("0.00");
            $("#xTotalUSD").html("0.00");
            $("#lista2").html("");
            $("#articulo").val("");

            window.location.replace("ViewOutTdcfcvList");
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión al vaciar: " + errorThrown);
        });
    };

    window.listar_pedido = function (i) {
        const PorDesMin = parseInt(getVal("PorDesMin"), 10);
        const PorDesMax = parseInt(getVal("PorDesMax"), 10);

        $.ajax({
            url: "include/tdcfcv/listar_tdcfcv_totales.php",
            type: "POST",
            dataType: "json",
            data: { pedido: i }
        }).done(function (json) {
            json = normalizeJson(json);

            if (json.estatus == 1) {
                setPedidoButtons(json);
                $("#pedido").val(json.pedido);
                updateTotals(json);
                ActualizarVistaPrevia(json.pedido);

                const PorDesAct = json.descuento ?? 0;
                PintarProgressBar(PorDesAct);

                $("#descTransferencista").val(json.descTransferencista ?? 0);
                $("#descFabricante").val(json.descFabricante ?? 0);
            } else {
                alertMsg("Error: " + (json.mensaje ?? "No se pudo listar el pedido."));
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión al listar totales: " + errorThrown);
        });
    };

    /// Busqueda Laboratorios ///
    window.getLaboratorios = function () {
        const laboratorio = getVal("laboratorio");
        const lista = document.getElementById("lista");

        if (!lista) {
            console.error("No existe el contenedor lista");
            return false;
        }

        if (laboratorio.trim() === "") {
            $("#codlab").val("");
            lista.innerHTML = "";
            lista.style.display = "none";
            getCodigos2();
            return false;
        }

        $.ajax({
            url: "include/tdcfcv/buscar_laboratorios_tdcfcv.php",
            type: "POST",
            dataType: "json",
            data: {
                laboratorio: laboratorio
            }
        })
        .done(function (html) {
            lista.style.display = "block";
            lista.innerHTML = normalizeJson(html);
        })
        .fail(function (xhr, status, errorThrown) {
            console.log("ERROR buscar_laboratorios:", xhr.responseText);
            alertMsg("Error buscando laboratorios: " + errorThrown);
        });
    };

    window.seleccionarLaboratorio = function (id, nombre) {
        $("#codlab").val(id);
        $("#laboratorio").val(nombre);
        $("#lista").html("").hide();
        getCodigos2();
    };
    /////////////////////////////

    window.getCodigos2 = function () {
        const articulo = getVal("articulo");
        const lista = document.getElementById("lista2");

        if (!lista) {
            console.error("No existe el contenedor lista2");
            return false;
        }

        if (articulo.trim() === "") {
            return false;
        }

        lista.innerHTML = '<div class="text-center p-3"><i class="fa-solid fa-spinner fa-spin"></i> Buscando artículos...</div>';

        const formData = new FormData();
        formData.append("consignacion", getVal("consignacion"));
        formData.append("fabricante", getVal("codlab"));
        formData.append("articulo", articulo);
        formData.append("cliente", getVal("codcli"));
        formData.append("pedido", getVal("pedido"));
        formData.append("username", getVal("username"));
        formData.append("tipo_documento", getVal("tipo_documento"));
        formData.append("descuentoG", getVal("PorDesAct"));
        formData.append("hubb", $("#hubb").is(":checked") ? "SI" : "NO");
        formData.append("moneda", getVal("moneda"));
        

        console.log("Enviando a buscar_articulos_tdcfcv.php");

        $.ajax({
            url: "include/tdcfcv/buscar_articulos_tdcfcv.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json"
        })
        .done(function (html) {
            console.log("Respuesta buscar_articulos:", html);
            lista.style.display = "block";
            lista.innerHTML = normalizeJson(html);
        })
        .fail(function (xhr, status, errorThrown) {
            console.log("ERROR buscar_articulos:", xhr.responseText);
            alertMsg("Error buscando artículos: " + errorThrown);
        });
    };

    window.getCodigos3 = function (i) {
        const lista = document.getElementById("lista2");

        if (!lista) return false;

        if (i != 0) {
            $("#laboratorio").prop("disabled", false);
            $("#articulo").prop("disabled", false);
            $("#consignacion").prop("disabled", true);
        } else {
            if ($("#consignacion").val() != "") {
                $("#laboratorio").prop("disabled", false);
                $("#articulo").prop("disabled", false);
                $("#consignacion").prop("disabled", true);
            } else {
                $("#consignacion").val("FC").trigger("change");
                limpiar();
            }
        }

        lista.innerHTML = "";

        if (i > 0) {
            const formData = new FormData();
            formData.append("pedido", i);
            formData.append("username", getVal("username"));

            fetch("include/tdcfcv/listar_tdcfcv.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (response.status === 401) {
                    alertMsg("Su sesión ha expirado. Será redireccionado al inicio.");
                    window.location.href = "logout";
                    return null;
                }

                if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }

                return response.json();
            })
            .then(data => {
                if (data !== null) {
                    data = normalizeJson(data);
                    lista.style.display = "block";
                    lista.innerHTML = data;
                    listar_pedido(i);
                }
            })
            .catch(err => {
                console.error("Error crítico en getCodigos3:", err);
                alertMsg("Error listando la cesta.");
            });
        } else {
            lista.style.display = "none";
        }
    };

    window.limpiar = function () {
        $("#codlab").val("");
        $("#laboratorio").val("");
        $("#articulo").val("");
        getCodigos2();
    };

    window.guardar_nota = function () {
        const pedido = getVal("pedido");

        if (parseInt(pedido) <= 0) return false;

        const formData = new FormData();
        formData.append("pedido", pedido);
        formData.append("nota", getVal("nota"));

        fetch("include/tdcfcv/guardar_nota.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.estatus === "1") {
                alertMsg(data.mensaje);
            } else {
                alertMsg(data.mensaje || "Error guardando la nota");
            }
        })
        .catch(err => console.error(err));
    };

    window.myCalc = function (i) {
        const cantidad = parseFloat($("#x" + i + "_cantidad").val() || 0);
        const precioFull = parseFloat($("#x" + i + "_precioFull").val() || 0);
        const descuento = parseFloat($("#x" + i + "_descuento").val() || 0);
        const descuento2 = parseFloat($("#x" + i + "_descuento2").val() || 0);

        let precio = precioFull - (precioFull * (descuento / 100));
        precio = precio - (precio * (descuento2 / 100));
        precio = redondearDecimales(precio, 2);

        const total = redondearDecimales(cantidad * precio, 2);

        $("#x" + i + "_precio").val(precio);
        $("#x" + i + "_total").val(total);

        if (cantidad <= 0) {
            alertMsg("La cantidad debe ser mayor a cero");
            return false;
        }
    };

    window.redondearDecimales = function (numero, decimales) {
        numero = parseFloat(numero || 0);
        return Number(numero.toFixed(decimales));
    };

    window.buscarItem2 = function (i, j) {
        const pedido = <?= intval($pedido) ?>;
        $("#pagina").val(i);

        switch (j) {
            case 0:
                getCodigos2();
                break;
            case 1:
                getCodigos3(pedido);
                break;
        }
    };

    window.sendProccess = function (i) {
        if (i <= 0) return false;

        // Mostrar un spinner o deshabilitar temporalmente para evitar doble clic antes de cargar modal
        const PorDesAct = getVal("PorDesAct");

        $.ajax({
            url: "include/tdcfcv/get_next_fiscal.php",
            type: "POST",
            dataType: "json",
            data: { pedido: i }
        }).done(function (response) {
            response = normalizeJson(response);

            if (response.estatus == 1) {
                // Inyectar datos calculados en el modal
                $("#modal_nro_factura").val(response.factura);
                $("#modal_nro_control").val(response.control);
                $("#modal_fecha").val(response.fecha);

                // Cambiar dinámicamente el título según el tipo de documento para mayor claridad
                let tipoDocTxt = "Factura Fiscal";
                if (response.tipo_doc === "NC") tipoDocTxt = "Nota de Crédito";
                if (response.tipo_doc === "ND") tipoDocTxt = "Nota de Débito";
                $("#modalConfirmarFiscalLabel").html('<i class="fa-solid fa-print me-2"></i> Confirmación de Emisión (' + tipoDocTxt + ')');

                // Inicializar y levantar el Modal de Bootstrap 5 de manera nativa
                const miModal = new bootstrap.Modal(document.getElementById('modalConfirmarFiscal'));
                miModal.show();

                // Desenlazar eventos anteriores del botón de confirmación para evitar ejecuciones múltiples
                $("#btnConfirmarProcesar").off("click").on("click", function() {
                    const btn = $(this);
                    const idDocumentoPadre = <?= intval($id_documento_padre) ?>;
                    const preguntarNE = <?= $preguntarNE ? "true" : "false" ?>;

                    // Procede con el redireccionamiento final una vez decidido si se genera o no la Orden de Entrega.
                    const continuarProceso = function (generarNE) {
                        btn.prop("disabled", true)
                        .html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Procesando...');

                        window.location.href =
                            "TdcfcvProcess?pedido=" + i +
                            "&PorDesAct=" + PorDesAct +
                            "&generar_ne=" + generarNE;
                    };

                    if (idDocumentoPadre == 0 && preguntarNE) {
                        // Cerramos el modal de confirmación fiscal y, apenas termine de ocultarse,
                        // abrimos el modal para preguntar por la Orden de Entrega.
                        const modalFiscalEl = document.getElementById('modalConfirmarFiscal');
                        const modalFiscal = bootstrap.Modal.getInstance(modalFiscalEl);
                        const modalNE = new bootstrap.Modal(document.getElementById('modalConfirmarNE'));

                        modalFiscalEl.addEventListener('hidden.bs.modal', function alHacerseInvisible() {
                            modalFiscalEl.removeEventListener('hidden.bs.modal', alHacerseInvisible);
                            modalNE.show();
                        });

                        modalFiscal.hide();

                        $("#btnGenerarNESi").off("click").on("click", function () {
                            modalNE.hide();
                            continuarProceso("S");
                        });

                        $("#btnGenerarNENo").off("click").on("click", function () {
                            modalNE.hide();
                            continuarProceso("N");
                        });
                    } else {
                        continuarProceso("N");
                    }
                });                
            } else {
                alertMsg("Error al recuperar correlativos fiscales: " + (response.mensaje ?? "Intente de nuevo."));
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de red al consultar parámetros fiscales: " + errorThrown);
        });
    };    

    var descuentoGlobalPendiente = {
        pedido: "",
        original: "",
        nuevo: ""
    };

    var transferenciaPendiente = {
        original: "",
        nuevo: ""
    };

    window.PintarProgressBar = function (valor) {
        $("#PorDesAct").val(valor);
        $("#rangoDescuentoCliente").val(valor);
        $("#lblDescuentoCliente").text(valor + "%");
    }

    window.AplicarDescuentoGlobalPendiente = function () {
        if (descuentoGlobalPendiente.pedido === "") return;

        PintarProgressBar(descuentoGlobalPendiente.nuevo);

        RefreshDescuento(
            descuentoGlobalPendiente.pedido,
            descuentoGlobalPendiente.nuevo
        );

        descuentoGlobalPendiente = {
            pedido: "",
            original: "",
            nuevo: ""
        };
    }

    window.ProgLess = function () {
        const pedido = getVal("pedido");
        const PorDesMin = parseInt(getVal("PorDesMin"), 10);
        const PorDesMax = parseInt(getVal("PorDesMax"), 10);
        const PorDesActOriginal = parseInt(getVal("PorDesAct"), 10);

        if (pedido == 0) return false;

        if (PorDesActOriginal > PorDesMin && PorDesActOriginal <= PorDesMax) {
            const PorDesActNuevo = PorDesActOriginal - 1;

            descuentoGlobalPendiente = {
                pedido: pedido,
                original: PorDesActOriginal,
                nuevo: PorDesActNuevo
            };

            if (puedeModificarPrecioDescuento) {
                AplicarDescuentoGlobalPendiente();
                return;
            }

            $("#auth_user_tdcfcv").val("");
            $("#auth_pass_tdcfcv").val("");
            showModalAutorizarTdcfcv();
        }
    };

    window.ProgPlus = function () {
        const pedido = getVal("pedido");
        const PorDesMin = parseInt(getVal("PorDesMin"), 10);
        const PorDesMax = parseInt(getVal("PorDesMax"), 10);
        const PorDesActOriginal = parseInt(getVal("PorDesAct"), 10);

        if (pedido == 0) return false;

        if (PorDesActOriginal >= PorDesMin && PorDesActOriginal < PorDesMax) {

            const PorDesActNuevo = PorDesActOriginal + 1;

            descuentoGlobalPendiente = {
                pedido: pedido,
                original: PorDesActOriginal,
                nuevo: PorDesActNuevo
            };

            if (puedeModificarPrecioDescuento) {
                AplicarDescuentoGlobalPendiente();
                return;
            }

            $("#auth_user_tdcfcv").val("");
            $("#auth_pass_tdcfcv").val("");
            showModalAutorizarTdcfcv();
        }
    };

    window.DiasCred = function () {
        const pedido = getVal("pedido");
        const PorDesAct = parseInt(getVal("PorDesAct"), 10);

        if (pedido == 0) return false;
        RefreshDescuento(pedido, PorDesAct);
    };

    window.RefreshDescuento = function (i, j) {
        const descTransferencista = parseFloat(getVal("descTransferencista") || 0);
        const descFabricante = parseFloat(getVal("descFabricante") || 0);

        if (descTransferencista == 100) {
            alertMsg("El descuento de transferencista no puede ser 100%");
            $("#descTransferencista").val("0.00");
            return false;
        }

        if (descFabricante == 100) {
            alertMsg("El descuento de fabricante no puede ser 100%");
            $("#descFabricante").val("0.00");
            return false;
        }

        $.ajax({
            url: "include/tdcfcv/descuento_tdcfcv_totales.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: i,
                descuentoG: j,
                moneda: getVal("moneda"),
                tasa_usd: getVal("tasa_usd"),
                username: getVal("username"),
                descTransferencista: getVal("descTransferencista"),
                descFabricante: getVal("descFabricante")
            }
        }).done(function (json) {
            json = normalizeJson(json);

            if (json.estatus == 1) {
                updateTotals(json);
            } else {
                alertMsg("Error: " + (json.mensaje ?? "No se pudo actualizar descuento."));
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión actualizando descuento: " + errorThrown);
        });
    };

    window.RefreshMonedaTasa = function () {
        const pedido = getVal("pedido");
        let tasa_usd = parseFloat(getVal("tasa_usd") || 0);

        if (pedido == 0) return false;

        if (tasa_usd <= 0) {
            alertMsg("La Tasa B.C.V. debe ser mayor a 0. Por defecto se pondrá 1");
            $("#tasa_usd").val(1);
            tasa_usd = 1;
        }

        $.ajax({
            url: "include/tdcfcv/moneda_tdcfcv_totales.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: pedido,
                moneda: getVal("moneda"),
                tasa_usd: tasa_usd,
                username: getVal("username")
            }
        }).done(function (json) {
            json = normalizeJson(json);

            if (json.estatus == 1) {
                updateTotals(json);
            } else {
                alertMsg("Error: " + (json.mensaje ?? "No se pudo actualizar moneda/tasa."));
            }
        }).fail(function (xhr, status, errorThrown) {
            console.log(xhr.responseText);
            alertMsg("Error de conexión actualizando moneda/tasa: " + errorThrown);
        });
    };

    window.setDateToLote = function (xlote, i) {
        const myArr = String(xlote || "").split("|");
        const fecha = myArr[1] || "0000-00-00";

        $("#x" + i + "_vence").val(fecha !== "" ? fecha : "0000-00-00");
        validarCantidadLote(i);
    };

    window.validarCantidadLote = function (i) {
        const loteVal = $("#x" + i + "_lote").val() || "";
        const myArr = loteVal.split("|");

        if (isNaN(myArr[2]) || parseInt(myArr[2]) == 0) {
            myCalc(i);
            return true;
        }

        const cantidad_lote = parseInt(myArr[2]);
        const cantidad = parseInt($("#x" + i + "_cantidad").val() || 0);

        if (cantidad > cantidad_lote) {
            alertMsg("La cantidad solicitada es mayor a la existencia del lote");
            $("#x" + i + "_cantidad").val("");
        }

        myCalc(i);
    };

    window.controlarMoneda = function() {
        const pedido = parseInt(document.getElementById("pedido")?.value || 0);
        const moneda = document.getElementById("moneda");

        if (!moneda) return;

        moneda.onchange = null; // limpiar eventos anteriores

        if (pedido > 0) {
            moneda.dataset.original = moneda.value;

            moneda.onchange = function () {
                this.value = this.dataset.original;
            };
        } else {
            moneda.onchange = function () {
                RefreshMonedaTasa();
            };
        }
    };   

    function ActualizarVistaPrevia(pedido) {

        if (parseInt(pedido) > 0) {

            $("#btnVistaPrevia")
                .attr(
                    "href",
                    "reportes/factura_de_venta.php?id=" +
                    pedido +
                    "&tipo=TDCFCV"
                )
                .removeClass("disabled");

        } else {

            $("#btnVistaPrevia")
                .attr("href", "#")
                .addClass("disabled");
        }
    }

    // $(document).on("keyup change", "#articulo", function () {
    /*
    $(document).on("keyup", "#articulo", function () {
        console.log("Buscando artículo:", $(this).val());
        getCodigos2();
    });
    */

    $(document).on("keydown", "#articulo", function(e) {

        if (e.which == 13) {
            // ENTER
            e.preventDefault();
            getCodigos2();
        }
        else if (e.which == 9) {
            // TAB
            getCodigos2();
        }
        else if (e.which == 46) {
            // DELETE
            getCodigos2();
        }

    });

    $(document).on("click", "#btnBuscarArticulo", function() {

        if ($.trim($("#articulo").val()) === "") {
            $("#articulo").focus();
            return;
        }

        getCodigos2();

    });


    /// Laboratorios ///
    $(document).on("keyup change", "#laboratorio", function () {
        getLaboratorios();
    });
    ////////////////////

    window.abrirModalNuevoArticulo = function () {
        $("#nuevoArticuloMsg").addClass("d-none").html("");

        $("#nuevo_codigo").val("");
        $("#nuevo_nombre_comercial").val("");
        $("#nuevo_principio_activo").val("");
        $("#nuevo_presentacion").val("");
        $("#nuevo_codigo_de_barra").val("");
        $("#nuevo_alicuota").val("");
        $("#nuevo_articulo_inventario").val("S");

        const modalEl = document.getElementById("modalNuevoArticulo");
        bootstrap.Modal.getOrCreateInstance(modalEl).show();

        setTimeout(function () {
            inicializarSelect2NuevoArticulo();
        }, 250);
    };

    function inicializarSelect2NuevoArticulo() {
        if (typeof jQuery === "undefined" || !jQuery.fn.select2) {
            return;
        }

        const $modal = $("#modalNuevoArticulo .modal-content");

        if ($("#nuevo_fabricante").hasClass("select2-hidden-accessible")) {
            $("#nuevo_fabricante").select2("destroy");
        }

        if ($("#nuevo_categoria").hasClass("select2-hidden-accessible")) {
            $("#nuevo_categoria").select2("destroy");
        }

        $("#nuevo_fabricante").select2({
            dropdownParent: $modal,
            width: "100%",
            placeholder: "Seleccione...",
            allowClear: true
        });

        $("#nuevo_categoria").select2({
            dropdownParent: $modal,
            width: "100%",
            placeholder: "Seleccione...",
            allowClear: true
        });
    }

    window.guardarNuevoArticulo = function () {
        const data = {
            codigo: $.trim($("#nuevo_codigo").val()),
            nombre_comercial: $.trim($("#nuevo_nombre_comercial").val()),
            principio_activo: $.trim($("#nuevo_principio_activo").val()),
            presentacion: $.trim($("#nuevo_presentacion").val()),
            fabricante: $("#nuevo_fabricante").val() || "",
            codigo_de_barra: $.trim($("#nuevo_codigo_de_barra").val()),
            categoria: $("#nuevo_categoria").val() || "",
            alicuota: $("#nuevo_alicuota").val() || "",
            articulo_inventario: $("#nuevo_articulo_inventario").val() || "S",
            username: $("#username").val()
        };

        $.ajax({
            url: "include/crear_articulo_rapido.php",
            type: "POST",
            dataType: "json",
            data: data
        }).done(function(resp) {
            if (resp && resp.success === true) {
                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById("modalNuevoArticulo")
                ).hide();

                $("#articulo").val(data.nombre_comercial);
                getCodigos2();
            } else {
                $("#nuevoArticuloMsg")
                    .removeClass("d-none")
                    .html((resp && resp.error) ? resp.error : "No se pudo crear el artículo.");
            }
        }).fail(function(xhr) {
            console.log(xhr.responseText);
            $("#nuevoArticuloMsg")
                .removeClass("d-none")
                .html("Error creando el artículo.");
        });
    };

    getCodigos3(<?= intval($pedido) ?>);
});
</script>


<div class="modal fade" id="modalAutorizarTdcfcv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAutorizarTdcfcvLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-warning shadow-lg">
      <div class="modal-header bg-dark text-white py-2">
        <h5 class="modal-title" id="modalAutorizarTdcfcvLabel">
          <i class="fa-solid fa-key me-2"></i> Autorización Requerida
        </h5>
      </div>
      <div class="modal-body bg-light text-dark">
        <p class="text-muted small border-bottom pb-2">
          Para modificar precios o descuentos debe ingresar un usuario autorizador.
        </p>
        <div class="mb-3">
          <input type="text" class="form-control form-control-sm" id="auth_user_tdcfcv" autocomplete="off" placeholder="Usuario Autorizador">
        </div>
        <div class="mb-0">
          <input type="password" class="form-control form-control-sm" id="auth_pass_tdcfcv" placeholder="Password">
        </div>
      </div>
      <div class="modal-footer bg-white border-top-0 pt-0 justify-content-between">
        <button type="button" class="btn btn-sm btn-secondary" id="btnCancelarAutorizarTdcfcv">
          <i class="fa-solid fa-xmark me-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-sm btn-success" id="btnAceptarAutorizarTdcfcv">
          <i class="fa-solid fa-check me-1"></i> Autorizar
        </button>
      </div>
    </div>
  </div>
</div>

<?php
ExecuteStatement("
    INSERT INTO parametro (codigo, descripcion, valor1)
    SELECT '112', 'USA IMPRESORA FISCAL', 'N'
    FROM DUAL
    WHERE NOT EXISTS (
        SELECT 1
        FROM parametro
        WHERE codigo = '112'
    )
");

$impresoraFiscal = strtoupper(trim(
    ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '112'")
));
?>

<div class="modal fade" id="modalConfirmarFiscal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalConfirmarFiscalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-primary shadow-lg">
      <div class="modal-header bg-primary text-white py-2">
        <h5 class="modal-title" id="modalConfirmarFiscalLabel"><i class="fa-solid fa-print me-2"></i> Confirmación de Emisión Fiscal</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body bg-light text-dark">
        <p class="text-muted small border-bottom pb-2">Por favor, verifique los datos correlativos antes de proceder con el procesamiento definitivo del documento.</p>
        
        <div class="row g-3">
          <div class="col-6">
            <label class="form-label fw-bold text-secondary mb-1 small">Próximo Nro. Documento</label>
            <div class="input-group input-group-sm" style="min-width: 0; flex-wrap: nowrap;">
              <span class="input-group-text bg-white"><i class="fa-solid fa-file-invoice text-primary"></i></span>
              <input type="text" id="modal_nro_factura" class="form-control bg-white fw-bold text-center" readonly style="min-width: 0; flex-grow: 1; letter-spacing: 0.5px;">
            </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-bold text-secondary mb-1 small">Próximo Nro. Control</label>
            <div class="input-group input-group-sm" style="min-width: 0; flex-wrap: nowrap;">
              <span class="input-group-text bg-white"><i class="fa-solid fa-sliders text-danger"></i></span>
              <input type="text" id="modal_nro_control" class="form-control bg-white fw-bold text-center" readonly style="min-width: 0; flex-grow: 1; letter-spacing: 0.5px;">
            </div>
          </div>
          <div class="col-12">
            <label class="form-label fw-bold text-secondary mb-1 small">Fecha de Emisión</label>
            <div class="input-group input-group-sm" style="min-width: 0; flex-wrap: nowrap;">
              <span class="input-group-text bg-white"><i class="fa-solid fa-calendar-day text-success"></i></span>
              <input type="text" id="modal_fecha" class="form-control bg-white text-center" readonly style="min-width: 0; flex-grow: 1;">
            </div>
          </div>

          <?php if ($impresoraFiscal == "S") { ?>
              <div class="col-12">
                  <div class="alert alert-primary d-flex align-items-center mb-0 py-2 small" role="alert">
                      <i class="fa-solid fa-print flex-shrink-0 me-2 fs-5 text-primary"></i>
                      <div>
                          <strong>Impresora fiscal activa:</strong>
                          este documento será emitido e impreso en la impresora fiscal configurada.
                      </div>
                  </div>
              </div>
          <?php } ?>
        </div>

        <div class="alert alert-warning d-flex align-items-center mt-3 mb-0 py-2 small" role="alert">
          <i class="fa-solid fa-triangle-exclamation flex-shrink-0 me-2 fs-5 text-warning"></i>
          <div>
            <strong>¡Atención!</strong> Una vez procesado, no se podrán revertir los números asignados en los parámetros del sistema y mucho menos a la factura emitida.
          </div>
        </div>
      </div>
        <div class="modal-footer bg-white border-top-0 pt-0 justify-content-between">
            <button type="button"
                    class="btn btn-sm btn-outline-secondary px-3"
                    data-bs-dismiss="modal">
                <i class="fa-solid fa-xmark me-1"></i>
                Cancelar
            </button>

            <a id="btnVistaPrevia"
                href="<?= $urlImprimir ?>"
                target="_blank"
                class="btn btn-sm btn-outline-primary px-3 <?= ($urlImprimir == '#' ? 'disabled' : '') ?>">
                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                    Vista Previa
            </a>

            <button type="button"
                    id="btnConfirmarProcesar"
                    class="btn btn-sm btn-success px-4 fw-bold">
                <i class="fa-solid fa-check me-1"></i>
                Sí, Procesar
            </button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalConfirmarNE" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalConfirmarNELabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-info shadow-lg">
      <div class="modal-header bg-info text-white py-2">
        <h5 class="modal-title" id="modalConfirmarNELabel"><i class="fa-solid fa-truck-fast me-2"></i> Orden de Entrega</h5>
      </div>
      <div class="modal-body bg-light text-dark text-center py-4">
        <i class="fa-solid fa-boxes-stacked text-info d-block mb-3" style="font-size: 2.5rem;"></i>
        <p class="mb-0 fs-6">
          ¿Desea generar automáticamente una <strong>Orden de Entrega</strong> para los artículos de inventario facturados?
        </p>
      </div>
      <div class="modal-footer bg-white border-top-0 pt-0 justify-content-center gap-2">
        <button type="button" id="btnGenerarNENo" class="btn btn-sm btn-outline-secondary px-4">
          <i class="fa-solid fa-xmark me-1"></i>
          No, continuar sin generarla
        </button>
        <button type="button" id="btnGenerarNESi" class="btn btn-sm btn-info text-white px-4 fw-bold">
          <i class="fa-solid fa-check me-1"></i>
          Sí, generar
        </button>
      </div>
    </div>
  </div>
</div>


<style>
#modalNuevoArticulo .form-label{
    font-weight: 600;
    margin-bottom: 4px;
}

#modalNuevoArticulo .modal-header{
    padding-top: .75rem;
    padding-bottom: .75rem;
}

#modalNuevoArticulo .modal-body{
    padding: 1.25rem;
}

#modalNuevoArticulo .select2-container {
    width: 100% !important;
}

#modalNuevoArticulo .select2-dropdown {
    z-index: 2000 !important;
}

.select2-container--open {
    z-index: 2000 !important;
}

#modalNuevoArticulo .select2-selection--single {
    height: 38px !important;
    border: 1px solid #ced4da !important;
}

#modalNuevoArticulo .select2-selection__rendered {
    line-height: 36px !important;
}

#modalNuevoArticulo .select2-selection__arrow {
    height: 36px !important;
}

#modalNuevoArticulo .nuevo-articulo-campo {
    display: flex;
    flex-direction: column;
    width: 100%;
}

#modalNuevoArticulo .nuevo-articulo-campo label {
    display: block;
    width: 100%;
    margin-bottom: 4px;
}

#modalNuevoArticulo .nuevo-articulo-campo input,
#modalNuevoArticulo .nuevo-articulo-campo select {
    width: 100%;
}
</style>

<div class="modal fade" id="modalNuevoArticulo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-box-open"></i>
                    Nuevo Artículo
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <div class="nuevo-articulo-campo">
                            <label for="nuevo_codigo" class="form-label fw-bold">
                                Código <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   id="nuevo_codigo"
                                   class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Nombre Comercial <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="nuevo_nombre_comercial"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Nombre Art&iacute;culo <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="nuevo_principio_activo"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Presentación
                        </label>
                        <input type="text"
                               id="nuevo_presentacion"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Fabricante <span class="text-danger">*</span>
                        </label>
                        <select id="nuevo_fabricante" class="form-select form-select-sm">
                            <option value="">Seleccione...</option>
                            <?php foreach ($fabricantesNuevo as $f) { ?>
                                <option value="<?= $f["Id"] ?>">
                                    <?= HtmlEncode($f["nombre"]) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Código de Barra
                        </label>
                        <input type="text"
                               id="nuevo_codigo_de_barra"
                               class="form-control form-control-sm">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Categoría <span class="text-danger">*</span>
                        </label>
                        <select id="nuevo_categoria" class="form-select form-select-sm">
                            <option value="">Seleccione...</option>
                            <?php foreach ($categoriasNuevo as $c) { ?>
                                <option value="<?= $c["id"] ?>">
                                    <?= HtmlEncode($c["nombre"]) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Alícuota <span class="text-danger">*</span>
                        </label>
                        <select id="nuevo_alicuota" class="form-select form-select-sm">
                            <option value="">Seleccione...</option>
                            <?php foreach ($alicuotasNuevo as $a) { ?>
                                <option value="<?= $a["codigo"] ?>">
                                    <?= HtmlEncode($a["nombre"]) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            Inventario <span class="text-danger">*</span>
                        </label>
                        <select id="nuevo_articulo_inventario"
                                class="form-select form-select-sm">
                            <option value="S" selected>Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>

                </div>

                <div id="nuevoArticuloMsg"
                     class="alert alert-danger mt-3 d-none">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-success"
                        onclick="guardarNuevoArticulo();">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar Artículo
                </button>
            </div>

        </div>
    </div>
</div>

<?= GetDebugMessage() ?>