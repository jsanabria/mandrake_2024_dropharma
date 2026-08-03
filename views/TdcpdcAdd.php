<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcpdcAdd = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$codpro = $_REQUEST["codpro"];
$tipo_documento = $_REQUEST["tipo_documento"];

$sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006' AND valor2 = 'default';";
$row = ExecuteRow($sql);
$moneda = $row["moneda"];

$sql = "SELECT tasa FROM tasa_usd WHERE moneda = 'USD' ORDER BY id DESC LIMIT 0, 1;"; 
$row = ExecuteRow($sql); 
$tasa = floatval($row["tasa"]);

$pedido = 0;
$consignacion = "";
$nota = "";
if(isset($_REQUEST["pedido"])) {
    $pedido = $_REQUEST["pedido"];
    $sql = "SELECT proveedor, tipo_documento, nota, tasa_dia, moneda, consignacion FROM entradas WHERE id = $pedido;";
    if($row = ExecuteRow($sql)) {
      $codpro = $row["proveedor"];
      $tipo_documento = $_REQUEST["tipo_documento"];
      $nota = $row["nota"];
      $tasa = floatval($row["tasa_dia"]);
      $moneda = $row["moneda"]; 
      $consignacion = $row["consignacion"];    
    } 
    else {
      header("Location: ViewInTdcpdcList");
      die();
    }
} 

$sql = "SELECT ci_rif, nombre FROM proveedor WHERE id = $codpro;";
$row = ExecuteRow($sql);
$proveedor = $row["nombre"];

$PorDesMin = 0;
$PorDesMax = 100;
$PorDesAct = intval((isset($_REQUEST["PorDesAct"]) ? $_REQUEST["PorDesAct"] : 0));
?>

<div class="container border border-primary border-top rounded p-3">
  <div class="row">
    <div class="col-sm-8">
		  <h5>Proveedor: <?= $proveedor ?></h5>
      <div id="nroPedido">
          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: <?= $pedido ?> </button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:vaciar(' . $pedido . ')"' : ''); ?> class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace('ViewInTdcpdcList');"><i class="fa-solid fa-list"></i> Pedidos </button>
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:getCodigos3(' . $pedido . ')"' : ''); ?> class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button>
      </div>
	    <input name="pedido" id="pedido" type="hidden" value="<?= $pedido ?>" />
	    <input name="tipo_documento" id="tipo_documento" type="hidden" value="<?= $tipo_documento ?>" />
	    <input name="codpro" id="codpro" type="hidden" value="<?= $codpro ?>" />
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
    <strong>Descuento proveedor</strong>
  </div>  
  <div class="col-sm-1" style="text-align: right; vertical-align: middle;">
    <span><a onclick="js:ProgLess();"><i class="fa-solid fa-minus"></i></a></span>
  </div>
  <div class="col-sm-4" id="xProgress">
    <div class="progress">
      <div class="progress-bar" role="progressbar" style="width: <?= $PorDesAct ?>%" aria-valuenow="<?= $PorDesAct ?>" aria-valuemin="<?= $PorDesMin ?>" aria-valuemax="<?= $PorDesMax ?>"><?= intval($PorDesAct) ?>%</div>
    </div>
  </div>
  <div class="col-sm-1" style="text-align: left; vertical-align: middle;">
    <span><a onclick="js:ProgPlus();"><i class="fa-solid fa-plus"></i></a></span>
  </div>

  <div class="col-sm-2">
      <select id="moneda" name="moneda" class="form-select form-select-sm" onchange="js:RefreshMonedaTasa()">
        <?php
        $sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006';";
        $rows = ExecuteRows($sql);
        foreach ($rows as $key => $value) {
          echo '<option value="' . $value["moneda"] . '"' . ($value["moneda"]==$moneda ? ' selected="selected"' : '') . '>' . $value["moneda"] . '</option>';
        }
        ?>
      </select>
  </div>

  <div class="col-sm-2">
    Tasa B.C.V.:<input name="tasa_usd" id="tasa_usd" type="number" class="form-control" value="<?= $tasa ?>" style="width: 90px;" onkeyup="js:RefreshMonedaTasa()" />
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
    <div class="col-sm-4">
      <select id="consignacion" name="consignacion" class="form-select form-select-sm">
        <option value="">Consignaci&oacute;n</option>
        <option value="N"<?= ($consignacion=="N" ? ' selected="selected"' : '') ?>>Consignaci&oacute;n (NO)</option>
        <option value="S"<?= ($consignacion=="S" ? ' selected="selected"' : '') ?>>Consignaci&oacute;n (SI)</option>
      </select>
    </div>
    <div class="col-sm-4">
      Fabricante:
		<input name="laboratorio" id="laboratorio" type="text" class="form-control form-control-sm" placeholder="Buscar Laboratorio" />
		<input name="codlab" id="codlab" type="hidden" class="form-control form-control-sm" />
      <ul id="lista" class="list-group"></ul>
    </div>
    <div class="col-sm-4">
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
              <td colspan="8">
                <div class="col-12 d-flex justify-content-center" id="Paginacion1">
                </div>
              </td>
            </tr>          
            <tr>
              <th width="10%">&nbsp</th>
              <th width="40%">At&iacute;culo</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="10%" class="text-center">Costo Full</th>
              <th width="10%" class="text-center">% Desc.</th>
              <th width="10%" class="text-center">Costo</th>
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
      <textarea cols="35" rows="3" placeholder="Observaciones" class="form-control form-control-sm" id="nota"><?= $nota ?></textarea>
  </div>
</div>

<script type="text/javascript">
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

    const formatter = new Intl.NumberFormat('es-PE', {
        style: 'decimal',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    function htmlBotonesPedido(json, incluirProcesar) {
        var html = '<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> ' +
          '<button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> ' +
          '<button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> ';
        if (incluirProcesar) {
          html += '<button type="button" onclick="js:sendProccess(' + json.pedido + ')" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-microchip"></i> Procesar Documento </button> ';
        }
        html += '<button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcpdcList\');"><i class="fa-solid fa-list"></i> Pedidos </button>';
        return html;
    }

    window.insertar = function (i) {
        var pedido = $("#pedido").val();
        var proveedor = $("#codpro").val();
        var costoFull = $("#x" + i + "_costoFull").val();
        var descuento = $("#x" + i + "_descuento").val();
        var costo = $("#x" + i + "_costo").val();
        var moneda = $("#moneda").val();
        var total = $("#x" + i + "_total").val();
        var cantidad = $("#x" + i + "_cantidad").val();
        var articulo = $("#x" + i + "_articulo").val();
        var tasa_usd = $("#tasa_usd").val();
        var username = '<?= CurrentUserName() ?>';
        var descuentoG = $("#PorDesAct").val();
        var nota = $("#nota").val();
        var consignacion = $("#consignacion").val();

        document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';

        $.ajax({
            url: "include/tdcpdc/insertar_linea_pedido_tdcpdc.php",
            data: {
                pedido: pedido,
                proveedor: proveedor,
                costoFull: costoFull,
                descuento: descuento,
                costo: costo,
                moneda: moneda,
                total: total,
                cantidad: cantidad,
                articulo: articulo,
                tasa_usd: tasa_usd,
                username: username,
                descuentoG: descuentoG,
                nota: nota,
                consignacion: consignacion
            },
            type: "POST",
            dataType: "json"
        })
        .done(function (json) {
            json = jQuery.parseJSON(json);

            if (json.estatus == 1) {
                $("#nroPedido").html(htmlBotonesPedido(json, false));
                $("#pedido").val(json.pedido);
                $("#xReglones").html(json.renglones);
                $("#xUnidades").html(json.unidades);
                $("#xTotalBs").html(formatter.format(json.total));
                $("#xTotalUSD").html(formatter.format(json.total_usd));

                $("#x" + i + "_cantidad").prop('disabled', true);
                $("#x" + i + "_costoFull").prop('disabled', true);
                $("#x" + i + "_descuento").prop('disabled', true);

                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ', ' + json.id_item + ')"></i>';
            } else {
                alert("Error: !!! " + json.mensaje + " !!!");
                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-cart-shopping" onclick="js:insertar(' + i + ')"></i>';
                $("#x" + i + "_cantidad").val("");
            }
        })
        .fail(function (xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    };

    window.eliminar = function (i, id_item) {
        var pedido = $("#pedido").val();
        var articulo = $("#x" + i + "_articulo").val();
        var moneda = $("#moneda").val();
        var tasa_usd = $("#tasa_usd").val();
        var username = '<?= CurrentUserName() ?>';
        var descuento = $("#PorDesAct").val();
        var nota = $("#nota").val();

        document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';

        $.ajax({
            url: "include/tdcpdc/eliminar_linea_pedido_tdcpdc.php",
            data: {
                pedido: pedido,
                articulo: articulo,
                moneda: moneda,
                tasa_usd: tasa_usd,
                username: username,
                descuento: descuento,
                id_item: id_item,
                nota: nota
            },
            type: "POST",
            dataType: "json"
        })
        .done(function (json) {
            json = jQuery.parseJSON(json);

            if (json.estatus == 1) {
                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-cart-shopping" onclick="js:insertar(' + i + ')"></i>';

                $("#nroPedido").html(htmlBotonesPedido(json, false));
                $("#pedido").val(json.pedido);
                $("#xReglones").html(json.renglones);
                $("#xUnidades").html(json.unidades);
                $("#xTotalBs").html(formatter.format(json.total));
                $("#xTotalUSD").html(formatter.format(json.total_usd));

                $("#x" + i + "_cantidad").prop('disabled', false);
                $("#x" + i + "_costoFull").prop('disabled', false);
                $("#x" + i + "_descuento").prop('disabled', false);
            } else {
                alert("Error: !!! " + json.mensaje + " !!!");
                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
            }
        })
        .fail(function (xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    };

    window.vaciar = function (i) {
        var username = '<?= CurrentUserName() ?>';
        if (i > 0) {
            if (!confirm("Seguro que quiere vaciar la cesta de pedidos?")) return false;

            var pedido = $("#pedido").val();

            $.ajax({
                url: "include/tdcpdc/vaciar_tdcpdc.php",
                data: {
                    pedido: pedido,
                    username: username
                },
                type: "POST",
                dataType: "json"
            })
            .done(function (json) {
                json = jQuery.parseJSON(json);

                $("#nroPedido").html(
                  '<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: 0 (0000000)</button> ' +
                  '<button type="button" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> ' +
                  '<button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcpdcList\');"><i class="fa-solid fa-list"></i> Pedidos </button>'
                );
                $("#pedido").val(0);
                $("#xReglones").html(0);
                $("#xUnidades").html(0);
                $("#xTotalBs").html(0.00);
                $("#xTotalUSD").html(0.00);

                $("#consignacion").prop('disabled', false);
                $("#consignacion").prop("selectedIndex", 0);
            })
            .fail(function (xhr, status, errorThrown) {
                alert("Sorry, there was a problem!");
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            })
            .always(function (xhr, status) {
                document.getElementById("lista2").innerHTML = "";
                document.getElementById("articulo").value = "";
            });
        }
    };

    window.listar_pedido = function (i) {
        var PorDesMin = parseInt($("#PorDesMin").val(), 10);
        var PorDesMax = parseInt($("#PorDesMax").val(), 10);
        var PorDesAct = parseInt($("#PorDesAct").val(), 10);

        $.ajax({
            url: "include/tdcpdc/listar_tdcpdc_totales.php",
            data: {
                pedido: i
            },
            type: "POST",
            dataType: "json"
        })
        .done(function (json) {
            json = jQuery.parseJSON(json);

            if (json.estatus == 1) {
                $("#nroPedido").html(htmlBotonesPedido(json, true));
                $("#pedido").val(json.pedido);
                $("#xReglones").html(json.renglones);
                $("#xUnidades").html(json.unidades);
                $("#xTotalBs").html(formatter.format(json.total));
                $("#xTotalUSD").html(formatter.format(json.total_usd));

                PorDesAct = json.descuento;
                $("#PorDesAct").val(PorDesAct);
                $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
            } else {
                alert("Error: !!! " + json.mensaje + " !!!");
                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
            }
        })
        .fail(function (xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    };

    window.buscarItem2 = function (i, j) {
        var pedido = <?= $pedido ?>;
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
        var PorDesAct = $("#PorDesAct").val();
        window.location.href = "TdcpdcProcess?pedido=" + i + "&PorDesAct=" + PorDesAct;
    };

    window.ProgLess = function () {
        var i = $("#pedido").val();
        var PorDesMin = parseInt($("#PorDesMin").val(), 10);
        var PorDesMax = parseInt($("#PorDesMax").val(), 10);
        var PorDesAct = parseInt($("#PorDesAct").val(), 10);

        if (i == 0) return false;

        if (PorDesAct > PorDesMin && PorDesAct <= PorDesMax) {
            PorDesAct -= 1;
            $("#PorDesAct").val(PorDesAct);
            $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
            RefreshDescuento(i, PorDesAct);
        }
    };

    window.ProgPlus = function () {
        var i = $("#pedido").val();
        var PorDesMin = parseInt($("#PorDesMin").val(), 10);
        var PorDesMax = parseInt($("#PorDesMax").val(), 10);
        var PorDesAct = parseInt($("#PorDesAct").val(), 10);

        if (i == 0) return false;

        if (PorDesAct >= PorDesMin && PorDesAct < PorDesMax) {
            PorDesAct += 1;
            $("#PorDesAct").val(PorDesAct);
            $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
            RefreshDescuento(i, PorDesAct);
        }
    };

    window.DiasCred = function () {
        var i = $("#pedido").val();
        var PorDesAct = parseInt($("#PorDesAct").val(), 10);

        if (i == 0) return false;
        RefreshDescuento(i, PorDesAct);
    };

    window.RefreshDescuento = function (i, j) {
        var moneda = $("#moneda").val();
        var tasa_usd = $("#tasa_usd").val();
        var username = '<?= CurrentUserName() ?>';

        $.ajax({
            url: "include/tdcpdc/descuento_tdcpdc_totales.php",
            data: {
                pedido: i,
                descuentoG: j,
                moneda: moneda,
                tasa_usd: tasa_usd,
                username: username
            },
            type: "POST",
            dataType: "json"
        })
        .done(function (json) {
            json = jQuery.parseJSON(json);

            if (json.estatus == 1) {
                $("#xTotalBs").html(formatter.format(json.total));
                $("#xTotalUSD").html(formatter.format(json.total_usd));
            } else {
                alert("Error: !!! " + json.mensaje + " !!!");
                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
            }
        })
        .fail(function (xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    };

    window.RefreshMonedaTasa = function () {
        var i = $("#pedido").val();
        var moneda = $("#moneda").val();
        var tasa_usd = $("#tasa_usd").val();
        var username = '<?= CurrentUserName() ?>';

        if (i == 0) return false;

        $.ajax({
            url: "include/tdcpdc/moneda_tdcpdc_totales.php",
            data: {
                pedido: i,
                moneda: moneda,
                tasa_usd: tasa_usd,
                username: username
            },
            type: "POST",
            dataType: "json"
        })
        .done(function (json) {
            json = jQuery.parseJSON(json);

            if (json.estatus == 1) {
                $("#xTotalBs").html(formatter.format(json.total));
                $("#xTotalUSD").html(formatter.format(json.total_usd));
            } else {
                alert("Error: !!! " + json.mensaje + " !!!");
                document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
            }
        })
        .fail(function (xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        });
    };

    window.getCodigos = function () {
        let inputCP = document.getElementById("laboratorio").value;
        let lista = document.getElementById("lista");
        let inputCP3 = document.getElementById("username").value;
        document.getElementById("codlab").value = "";

        if (inputCP.length >= 0) {
            let url = "include/buscar_laboratorios.php";
            let formData = new FormData();
            formData.append("fabricante", inputCP);
            formData.append("username", inputCP3);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
                .then(data => {
                    lista.style.display = 'block';
                    lista.innerHTML = data;
                })
                .catch(err => console.log(err));
        } else {
            lista.style.display = 'none';
        }
    };

    window.mostrar = function (cp) {
        document.getElementById("lista").style.display = 'none';

        document.getElementById("lista2").innerHTML = "";
        document.getElementById("articulo").value = "";

        let url = "include/buscar_laboratorio_codigo_nombre.php";
        let formData = new FormData();
        formData.append("fabricante", cp);

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors"
        }).then(response => response.json())
            .then(data => {
                let datos = data.split("|");
                document.getElementById("codlab").value = datos[0];
                document.getElementById("laboratorio").value = datos[1];
                getCodigos2();
            })
            .catch(err => console.log(err));
    };

    window.getCodigos2 = function () {
        document.getElementById("lista").style.display = 'none';

        let inputCP = document.getElementById("consignacion").value;
        let inputCP2 = document.getElementById("codlab").value;
        let inputCP3 = document.getElementById("articulo").value;
        let lista = document.getElementById("lista2");
        let inputCP4 = document.getElementById("codpro").value;
        let inputCP6 = document.getElementById("pedido").value;
        let inputCP7 = document.getElementById("username").value;
        let inputCP8 = document.getElementById("tipo_documento").value;
        let inputCP9 = document.getElementById("PorDesAct").value;

        document.getElementById("lista2").innerHTML = "";

        if (inputCP3.length >= 0) {
            let url = "include/tdcpdc/buscar_articulos_tdcpdc.php";
            let formData = new FormData();
            formData.append("consignacion", inputCP);
            formData.append("fabricante", inputCP2);
            formData.append("articulo", inputCP3);
            formData.append("proveedor", inputCP4);
            formData.append("pedido", inputCP6);
            formData.append("username", inputCP7);
            formData.append("tipo_documento", inputCP8);
            formData.append("descuentoG", inputCP9);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
                .then(data => {
                    lista.style.display = 'block';
                    lista.innerHTML = data;
                })
                .catch(err => console.log(err));
        } else {
            lista.style.display = 'none';
        }
    };

    window.getCodigos3 = function (i) {
        if (i != 0) {
            $("#laboratorio").prop("disabled", false);
            $("#articulo").prop("disabled", false);
            $("#consignacion").prop("disabled", true);
        } else {
            if ($("#consignacion").val() != "") {
                $("#laboratorio").prop("disabled", false);
                $("#articulo").prop("disabled", false);
                $("#consignacion").prop("disabled", true);
            }
        }

        let inputCP = i;
        let lista = document.getElementById("lista2");
        document.getElementById("lista2").innerHTML = "";

        if (inputCP > 0) {
            let url = "include/tdcpdc/listar_tdcpdc.php";
            let formData = new FormData();
            formData.append("pedido", inputCP);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
                .then(data => {
                    lista.style.display = 'block';
                    lista.innerHTML = data;
                    listar_pedido(i);
                })
                .catch(err => console.log(err));
        } else {
            lista.style.display = 'none';
        }
    };

    window.limpiar = function () {
        document.getElementById("codlab").value = "";
        document.getElementById("laboratorio").value = "";
        document.getElementById("articulo").value = "";
        getCodigos2();

        $("#laboratorio").prop("disabled", false);
        $("#articulo").prop("disabled", false);
        $("#consignacion").prop("disabled", true);
    };

    window.guardar_nota = function () {
        let inputCP = document.getElementById("pedido").value;
        let inputCP2 = document.getElementById("nota").value;
        if (inputCP > 0) {
            let url = "include/tdcpdc/guardar_nota.php";
            let formData = new FormData();
            formData.append("pedido", inputCP);
            formData.append("nota", inputCP2);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
                .then(data => {
                    alert(data);
                })
                .catch(err => console.log(err));
        }
    };

    window.myCalc = function (i) {
        var cantidad = parseInt($("#x" + i + "_cantidad").val());
        var costoFull = $("#x" + i + "_costoFull").val();
        var descuento = $("#x" + i + "_descuento").val();
        var costo = 0;
        var total = 0;

        costo = redondearDecimales(costoFull - (costoFull * (descuento / 100)), 2);
        total = redondearDecimales(cantidad * costo, 2);

        $("#x" + i + "_cantidad").val(cantidad);
        $("#x" + i + "_costoFull").val(costoFull);
        $("#x" + i + "_descuento").val(descuento);

        $("#x" + i + "_costo").val(costo);
        $("#x" + i + "_total").val(total);
    };

    window.redondearDecimales = function (numero, decimales) {
        let numeroRegexp = new RegExp('\\d\\.(\\d){' + decimales + ',}');
        if (numeroRegexp.test(numero)) {
            return Number(numero.toFixed(decimales));
        } else {
            return Number(numero.toFixed(decimales)) === 0 ? 0 : numero;
        }
    };

    $("#laboratorio").prop("disabled", true);
    $("#articulo").prop("disabled", true);

    document.getElementById("laboratorio").addEventListener("keyup", getCodigos);
    document.getElementById("laboratorio").addEventListener("click", getCodigos);
    document.getElementById("articulo").addEventListener("keyup", getCodigos2);
    document.getElementById("consignacion").addEventListener("change", limpiar);

    document.getElementById("nota").addEventListener("change", guardar_nota);

    getCodigos3(<?= $pedido ?>);
});
</script>

<?= GetDebugMessage() ?>
