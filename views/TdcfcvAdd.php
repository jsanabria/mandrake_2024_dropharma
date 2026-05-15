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
if(isset($_REQUEST["pedido"])) {
    $pedido = $_REQUEST["pedido"];
    $sql = "SELECT cliente, tipo_documento, nota, tasa_dia, moneda, documento, IFNULL(doc_afectado, '') AS doc_afectado, nro_documento, IFNULL(descuento2, 0) AS descuento2, IFNULL(descuento3, 0) AS descuento3 FROM salidas WHERE id = $pedido;";
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

<div class="row">
  <div class="col-sm-2" style="text-align: right">
    <strong>Descuento cliente</strong>
  </div>  
  <div class="col-sm-1" style="text-align: right; vertical-align: middle;">
    <span><a onclick="js:ProgLess();"><i class="fa-solid fa-minus"></i></a></span>
  </div>
  <div class="col-sm-2" id="xProgress">
    <div class="progress">
      <div class="progress-bar" role="progressbar" style="width: <?= $PorDesAct ?>%" aria-valuenow="<?= $PorDesAct ?>" aria-valuemin="<?= $PorDesMin ?>" aria-valuemax="<?= $PorDesMax ?>"><?= intval($PorDesAct) ?>%</div>
    </div>
  </div>
  <div class="col-sm-1" style="text-align: left; vertical-align: middle;">
    <span><a onclick="js:ProgPlus();"><i class="fa-solid fa-plus"></i></a></span>
  </div>

  <!-- Transferencista (igual que lo tienes) -->
  <div class="col-sm-2">
    <input name="doc_afectado" id="doc_afectado" type="hidden" value="<?= $doc_afectado ?>" />
    <?php if(trim($doc_afectado) != "") echo "Doc Afect.:" . $doc_afectado; ?>
    <?php
    echo '<input type="text" id="descTransferencista" name="descTransferencista" value="' . $descTransferencista . '" class="form-control form-control-sm" size="4" onchange="js: DiasCred();">
    <small>% Transferencista</small>';
    ?>   
  </div>

  <!-- Fabricante (ajustado como Transferencista) -->
  <div class="col-sm-2" style="display:none;>
    <?php
    echo '<input type="text" id="descFabricante" name="descFabricante" value="' . ($descFabricante ?? '') . '" class="form-control form-control-sm" size="4" onchange="js: DiasCred();" readonly="yes">
    <small>% Fabricante</small>';
    ?>   
  </div>

  <!-- Moneda -->
    <div class="col-sm-1">
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

  <!-- Tasa (ahora con label abajo) -->
  <div class="col-sm-1">
    <?php
    echo '<input name="tasa_usd" id="tasa_usd" type="number" class="form-control form-control-sm" value="' . $tasa . '" style="width: 90px;" onkeyup="js:RefreshMonedaTasa()" readonly="yes">
    <small>Tasa B.C.V.</small>';
    ?>
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

  <div class="row">
    <div class="col-sm-3">
      <select id="consignacion" name="consignacion" class="form-select form-select-sm" disabled="disabled">
        <option value="">TIPO DOCUMENTO</option>
        <option value="FC"<?= ($consignacion=="FC" ? ' selected="selected"' : '') ?>>FACTURA</option>
        <option value="NC"<?= ($consignacion=="NC" ? ' selected="selected"' : '') ?>>NOTA DE CREDITO</option>
        <option value="ND"<?= ($consignacion=="ND" ? ' selected="selected"' : '') ?>>NOTA DE DEBITO</option>
      </select>
    </div>
    <div class="col-sm-3">
        Rep. Art. <input type="checkbox" id="hubb" name="hubb" value="SI" checked> 
    </div>
    <div class="col-sm-3">
      Fabricante:
		<input name="laboratorio" id="laboratorio" type="text" class="form-control form-control-sm" placeholder="Buscar Laboratorio" />
		<input name="codlab" id="codlab" type="hidden" class="form-control form-control-sm" />
      <ul id="lista" class="list-group"></ul>
    </div>
    <div class="col-sm-3">
      Art&iacute;culo:
      <input name="articulo" id="articulo" type="text" class="form-control form-control-sm" placeholder="Buscar Art&iacute;culo" />
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
      <textarea cols="35" rows="3" placeholder="Observaciones" class="form-control form-control-sm" id="nota" onblur="js:guardar_nota();"><?= $nota ?></textarea>
  </div>
</div>

<script type="text/javascript">
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

    const formatter = new Intl.NumberFormat("es-PE", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function alertMsg(msg) {
        if (typeof ew !== "undefined" && ew.alert) {
            ew.alert(msg);
        } else {
            alert(msg);
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

                const PorDesAct = json.descuento ?? 0;
                $("#PorDesAct").val(PorDesAct);
                $("#xProgress").html(
                    '<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>'
                );

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

        const precio = redondearDecimales(precioFull - (precioFull * (descuento / 100)), 2);
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
        const PorDesAct = getVal("PorDesAct");
        window.location.href = "TdcfcvProcess?pedido=" + i + "&PorDesAct=" + PorDesAct;
    };

    window.ProgLess = function () {
        const pedido = getVal("pedido");
        const PorDesMin = parseInt(getVal("PorDesMin"), 10);
        const PorDesMax = parseInt(getVal("PorDesMax"), 10);
        let PorDesAct = parseInt(getVal("PorDesAct"), 10);

        if (pedido == 0) return false;

        if (PorDesAct > PorDesMin && PorDesAct <= PorDesMax) {
            PorDesAct -= 1;
            $("#PorDesAct").val(PorDesAct);
            $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
            RefreshDescuento(pedido, PorDesAct);
        }
    };

    window.ProgPlus = function () {
        const pedido = getVal("pedido");
        const PorDesMin = parseInt(getVal("PorDesMin"), 10);
        const PorDesMax = parseInt(getVal("PorDesMax"), 10);
        let PorDesAct = parseInt(getVal("PorDesAct"), 10);

        if (pedido == 0) return false;

        if (PorDesAct >= PorDesMin && PorDesAct < PorDesMax) {
            PorDesAct += 1;
            $("#PorDesAct").val(PorDesAct);
            $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
            RefreshDescuento(pedido, PorDesAct);
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

    $(document).on("keyup change", "#articulo", function () {
        console.log("Buscando artículo:", $(this).val());
        getCodigos2();
    });

    /// Laboratorios ///
    $(document).on("keyup change", "#laboratorio", function () {
        getLaboratorios();
    });
    ////////////////////
    
    getCodigos3(<?= intval($pedido) ?>);
});
</script>

<?= GetDebugMessage() ?>
