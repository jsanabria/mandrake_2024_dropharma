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

<script type="text/javascript">
  function insertar(i) { 
    var pedido = $("#pedido").value();
    var cliente = $("#codcli").value();
    var precioFull = $("#x" + i + "_precioFull").value();
    var descuento = $("#x" + i + "_descuento").value();
    var precio = $("#x" + i + "_precio").value();
    var moneda = $("#moneda").value();
    var total = $("#x" + i + "_total").value();
    var cantidad = $("#x" + i + "_cantidad").value();
    var articulo = $("#x" + i + "_articulo").value();
    var tasa_usd = $("#tasa_usd").value();
    var username = '<?= CurrentUserName() ?>';
    var descuentoG = $("#PorDesAct").value();
    var descTransferencista = $("#descTransferencista").val();
    var descFabricante = $("#descFabricante").val();
    var nota = $("#nota").value();
    var consignacion = $("#consignacion").value();
    var lote = $("#x" + i + "_lote").value();
    var vence = $("#x" + i + "_vence").value();
    var doc_afectado = $("#doc_afectado").value();
    // alert(pedido + " - " + cliente + " - " + precioFull + " - " + descuento + " - " + precio + " - " + moneda + " - " + total + " - " + cantidad + " - " + username);

    if(isNaN(parseFloat(precioFull))) {
        ew.alert("Debe indicar precio full.");
        return false;
    }
    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    
    $.ajax({
      // The URL for the request
      url: "include/tdcfcv/insertar_linea_pedido_tdcfcv.php",
      // The data to send tdcfcv/(will be converted to a query string)
      data: { 
              pedido: pedido, 
              cliente: cliente, 
              precioFull: precioFull, 
              descuento: descuento, 
              precio: precio, 
              moneda: moneda, 
              total: total, 
              cantidad: cantidad, 
              articulo: articulo, 
              tasa_usd: tasa_usd, 
              username: username, 
              descuentoG: descuentoG,
              descTransferencista: descTransferencista, 
              descFabricante: descFabricante, 
              nota: nota,
              consignacion: consignacion, 
              lote: lote, 
              vence: vence, 
              doc_afectado: doc_afectado, 
            },
      // Whether this is a POST or GET request
      type: "POST",
      // The type of data we expect back
      dataType : "json",
    })
    // Code to run if the request succeeds (is done); The response is passed to the function
    .done(function( json ) {
        // alert(json);
        // json = jQuery.parseJSON(json);

        if(json.estatus == 1) {
          // document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Documento Id.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcfcvList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
          $("#xTotalBs").html(formatter.format(json.total));
          $("#xTotalUSD").html(formatter.format(json.total_usd));
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          $("#x" + i + "_cantidad").prop('disabled', true);
          $("#x" + i + "_precioFull").prop('disabled', true);
          $("#x" + i + "_descuento").prop('disabled', true);
          $("#x" + i + "_lote").prop('disabled', true);
          $("#x" + i + "_vence").prop('disabled', true);

          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ', ' + json.id_item + ')"></i>';
        } 
        else {
          ew.alert("Error: !!! " + json.mensaje + " !!!");
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-cart-shopping" onclick="js:insertar(' + i + ')"></i>';
        }
    })
    // Code to run if the request fails; the raw request and status codes are passed to the function
    .fail(function( xhr, status, errorThrown ) {
      ew.alert( "Sorry, there was a problem!" );
      console.log( "Error: " + errorThrown );
      console.log( "Status: " + status );
      console.dir( xhr );
    })
    // Code to run regardless of success or failure;
    .always(function( xhr, status ) {
        // document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    });  
  }

function eliminar(i, id_item) {
    var pedido = $("#pedido").val();
    var articulo = $("#x" + i + "_articulo").val();
    var moneda = $("#moneda").val();
    var tasa_usd = $("#tasa_usd").val();
    var username = '<?= CurrentUserName() ?>';
    var descuento = $("#PorDesAct").val();
    var descTransferencista = $("#descTransferencista").val();
    var descFabricante = $("#descFabricante").val();
    var nota = $("#nota").val();
    var consignacion = $("#consignacion").val();
    var doc_afectado = $("#doc_afectado").val();

    if (!confirm("ESTA SEGURO DE ELIMINAR EL ITEM?")) return false;

    var btnRef = document.getElementById("x" + i + "_boton");
    if (btnRef) {
        btnRef.innerHTML = '<i class="fa-solid fa-spinner"></i>';
    }

    $.ajax({
    url: "include/tdcfcv/eliminar_linea_pedido_tdcfcv.php",
    data: { 
        pedido: pedido, 
        articulo: articulo, 
        moneda: moneda, 
        tasa_usd: tasa_usd, 
        username: username, 
        descuento: descuento,
        descTransferencista: descTransferencista, 
        descFabricante: descFabricante, 
        id_item: id_item, 
        nota: nota,
        consignacion: consignacion,
        doc_afectado: doc_afectado
    },
    type: "POST",
    dataType: "json"
    })
    .done(function(json) {
        if (json.estatus == 1) {
            if (parseInt(json.renglones) <= 0 || json.pedido == "0" || json.pedido == "") {
                window.location.replace("ViewOutTdcfcvList");
                return; 
            } 
            else {
                getCodigos3(json.pedido);
            }
        } else {
        alert("Error: !!! " + json.mensaje + " !!!");
        document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ', ' + id_item + ')"></i>';
        }
    })
    .fail(function(xhr, status, errorThrown) {
    ew.alert("Sorry, there was a problem!");
    console.log("Error: " + errorThrown);
    console.log("Status: " + status);
    console.dir(xhr);
    });
}

function guardarModificacion(i, id_item) {
    var pedido = $("#pedido").val();
    var articulo = $("#x" + i + "_articulo").val();
    var moneda = $("#moneda").val();
    var tasa_usd = $("#tasa_usd").val();
    var username = '<?= CurrentUserName() ?>';
    var descuento = $("#PorDesAct").val(); // Descuento general de la factura
    var descTransferencista = $("#descTransferencista").val();
    var descFabricante = $("#descFabricante").val();
    var nota = $("#nota").val();
    
    // Valores específicos de la línea a modificar
    var nueva_cantidad = $("#x" + i + "_cantidad").val();
    var nuevo_precio_full = $("#x" + i + "_precioFull").val();
    var nuevo_descuento_item = $("#x" + i + "_descuento").val();
    var nuevo_lote = $("#x" + i + "_lote").val();
    var nuevo_vence = $("#x" + i + "_vence").val();

    if (!confirm("¿DESEA GUARDAR LOS CAMBIOS EN ESTE ARTÍCULO?")) return false;

    // Cambiamos el icono a spinner para indicar progreso
    $("#x" + i + "_boton_edit").html('<i class="fa-solid fa-spinner fa-spin"></i>');

    $.ajax({
        url: "include/tdcfcv/modificar_linea_pedido_tdcfcv.php",
        data: { 
            pedido: pedido, 
            articulo: articulo, 
            id_item: id_item,
            cantidad: nueva_cantidad,
            precio_full: nuevo_precio_full,
            descuento_item: nuevo_descuento_item,
            lote: nuevo_lote,
            vence: nuevo_vence,
            moneda: moneda, 
            tasa_usd: tasa_usd, 
            username: username, 
            descuento_global: descuento, // El % de la cabecera
            descTransferencista: descTransferencista, 
            descFabricante: descFabricante, 
            nota: nota
        },
        type: "POST",
        dataType: "json"
    })
    .done(function(json) {
        if (json.estatus == 1) {
            // 1. Deshabilitar campos nuevamente
            $("#x" + i + "_cantidad, #x" + i + "_precioFull, #x" + i + "_descuento, #x" + i + "_lote, #x" + i + "_vence").prop('disabled', true);
            $("#x" + i + "_cantidad").closest('tr').removeClass('table-warning');

            // 2. Restaurar botón de edición (Lápiz)
            $("#x" + i + "_boton_edit").html('<i class="fa-solid fa-pencil text-warning" style="cursor:pointer;" onclick="js:habilitarEdicion(' + i + ', ' + id_item + ')"></i>');

            // 3. Actualizar totales globales en la interfaz
            $("#xReglones").html(json.renglones);
            $("#xUnidades").html(json.unidades);
            
            if (json.total == json.monto_sin_descuento) {
                $("#xTotalBs").html(formatter.format(json.total));
                $("#xTotalUSD").html(formatter.format(json.total_usd));
            } else {
                $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
                $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
            }
            
            ew.alert("¡Cambios guardados correctamente!");
        } else {
            ew.alert("Error: " + json.mensaje);
            // Si falla, volvemos a poner el botón de guardar para reintentar
            $("#x" + i + "_boton_edit").html('<i class="fa-solid fa-floppy-disk text-success" style="cursor:pointer;" onclick="js:guardarModificacion(' + i + ', ' + id_item + ')"></i>');
        }
    })
    .fail(function() {
        ew.alert("Error de conexión al intentar modificar.");
    });
}

  function vaciar(i) {
    var username = '<?= CurrentUserName() ?>';
    var consignacion = $("#consignacion").value();
    var doc_afectado = $("#doc_afectado").value();

    if(i > 0) {
      if(!confirm("Seguro que quiere vaciar la cesta de pedidos?")) return false;

      var pedido = $("#pedido").value();
      // Using the core $.ajax() method
      $.ajax({
        // The URL for the request
        url: "include/tdcfcv/vaciar_tdcfcv.php",
        // The data to send (will be converted to a query string)
        data: { 
                pedido: pedido, 
                username: username, 
                consignacion: consignacion,
                doc_afectado: doc_afectado,
              },
        // Whether this is a POST or GET request
        type: "POST",
        // The type of data we expect back
        dataType : "json",
      })
      // Code to run if the request succeeds (is done); The response is passed to the function
      .done(function( json ) {
          json = jQuery.parseJSON(json);
          // alert(json.pedido + " -- " + json.mensaje);

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Documento Id.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcfcvList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          // $("#consignacion").html(json.consignacion); 
          $("#doc_afectado").html(json.doc_afectado); 
          $("#nro_documento").html(json.nro_documento); 
          $("#pedido").val(json.pedido);
          $("#xReglones").html(0);
          $("#xUnidades").html(0);
          $("#xTotalBs").html(0.00);
          $("#xTotalUSD").html(0.00);

          // $("#consignacion").prop('disabled', false);
          // $("#consignacion").prop("selectedIndex", 0);
      })
      // Code to run if the request fails; the raw request and status codes are passed to the function
      .fail(function( xhr, status, errorThrown ) {
        alert( "Sorry, there was a problem!" );
        console.log( "Error: " + errorThrown );
        console.log( "Status: " + status );
        console.dir( xhr );
      })
      // Code to run regardless of success or failure;
      .always(function( xhr, status ) {
          document.getElementById("lista2").innerHTML = ""
          document.getElementById("articulo").value = "";
      });  
    }
  }

  function listar_pedido(i) {
    var PorDesMin = parseInt($("#PorDesMin").value(), 10);
    var PorDesMax = parseInt($("#PorDesMax").value(), 10);
    var PorDesAct = parseInt($("#PorDesAct").value(), 10);

    $.ajax({
      // The URL for the request
      url: "include/tdcfcv/listar_tdcfcv_totales.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: i 
            },
      // Whether this is a POST or GET request
      type: "POST",
      // The type of data we expect back
      dataType : "json",
    })
    // Code to run if the request succeeds (is done); The response is passed to the function
    .done(function( json ) {
        // alert(json);
        // $("#xReglones").html(json);
        json = jQuery.parseJSON(json);
        // alert(json.pedido + " -- " + json.mensaje);

        if(json.estatus == 1) {
          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Documento Id.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:sendProccess(' + json.pedido + ')" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-microchip"></i> Procesar Documento </button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcfcvList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          
          PorDesAct = json.descuento;
          $("#PorDesAct").val(PorDesAct);
          $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
          // $("#x" + i + "_cantidad").val("");
          $("#descTransferencista").val(json.descTransferencista);
        } 
        else {
          alert("Error: !!! " + json.mensaje + " !!!");
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
        }
    })
    // Code to run if the request fails; the raw request and status codes are passed to the function
    .fail(function( xhr, status, errorThrown ) {
      alert( "Sorry, there was a problem!" );
      console.log( "Error: " + errorThrown );
      console.log( "Status: " + status );
      console.dir( xhr );
    })
    // Code to run regardless of success or failure;
    .always(function( xhr, status ) {
        // document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    });  
  }

  const formatter = new Intl.NumberFormat('es-PE', {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  });


  function buscarItem2(i, j) {
    var pedido = <?= $pedido ?>;
    $("#pagina").val(i);

    switch(j) {
    case 0:
      getCodigos2();
      break;
    case 1:
      getCodigos3(pedido);
      break;
    }
  }

  function sendProccess(i) {
    var PorDesAct = $("#PorDesAct").value();
    window.location.href = "TdcfcvProcess?pedido=" + i + "&PorDesAct=" + PorDesAct;
  }

  function ProgLess() {
    var i = $("#pedido").value();
    var PorDesMin = parseInt($("#PorDesMin").value(), 10);
    var PorDesMax = parseInt($("#PorDesMax").value(), 10);
    var PorDesAct = parseInt($("#PorDesAct").value(), 10);

    if(i == 0) return false;

    if(PorDesAct>PorDesMin && PorDesAct<=PorDesMax) {
      PorDesAct -= 1;
      $("#PorDesAct").val(PorDesAct);
      $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
      RefreshDescuento(i, PorDesAct);
    }
  }

  function ProgPlus() {
    var i = $("#pedido").value();
    var PorDesMin = parseInt($("#PorDesMin").value(), 10);
    var PorDesMax = parseInt($("#PorDesMax").value(), 10);
    var PorDesAct = parseInt($("#PorDesAct").value(), 10);

    if(i == 0) return false;

    if(PorDesAct>=PorDesMin && PorDesAct<PorDesMax) {
      PorDesAct += 1;
      $("#PorDesAct").val(PorDesAct);
      $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
      RefreshDescuento(i, PorDesAct);
    }
  }

  function DiasCred() {
    var i = $("#pedido").value();
    var PorDesAct = parseInt($("#PorDesAct").value(), 10);

    if(i == 0) return false;
    RefreshDescuento(i, PorDesAct);
  }

  function RefreshDescuento(i, j) {
    var moneda = $("#moneda").value();
    var tasa_usd = $("#tasa_usd").value();
    var username = '<?= CurrentUserName() ?>';
    var descTransferencista = $("#descTransferencista").val();
    var descFabricante = $("#descFabricante").val();
   
    if(descTransferencista == 100) {
      ew.alert("El descuento de transferencista no puede ser 100%");
      $("#descTransferencista").val(0.00);
      return false
    }

    if(descFabricante == 100) {
      ew.alert("El descuento de fabricante no puede ser 100%");
      $("#descFabricante").val(0.00);
      return false
    }

    $.ajax({
      // The URL for the request
      url: "include/tdcfcv/descuento_tdcfcv_totales.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: i, 
              descuentoG: j, 
              moneda: moneda, 
              tasa_usd: tasa_usd, 
              username: username, 
              descTransferencista: descTransferencista, 
              descFabricante: descFabricante 
            },
      // Whether this is a POST or GET request
      type: "POST",
      // The type of data we expect back
      dataType : "json",
    })
    // Code to run if the request succeeds (is done); The response is passed to the function
    .done(function( json ) {
        // alert(json);
        // $("#xReglones").html(json);
        json = jQuery.parseJSON(json);
        // alert(json.pedido + " -- " + json.mensaje);

        if(json.estatus == 1) {
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
        } 
        else {
          alert("Error: !!! " + json.mensaje + " !!!");
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
        }
    })
    // Code to run if the request fails; the raw request and status codes are passed to the function
    .fail(function( xhr, status, errorThrown ) {
      alert( "Sorry, there was a problem!" );
      console.log( "Error: " + errorThrown );
      console.log( "Status: " + status );
      console.dir( xhr );
    })
    // Code to run regardless of success or failure;
    .always(function( xhr, status ) {
        // document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    });  
  }

  function RefreshMonedaTasa() {
    var i = $("#pedido").value();
    var moneda = $("#moneda").value();
    var tasa_usd = $("#tasa_usd").value();
    var username = '<?= CurrentUserName() ?>';

    if(i == 0) return false;

    if(tasa_usd <= 0) { 
        ew.alert("La Tasa B.C.V. debe ser mayor a 0. Por defecto se pondrá 1");
        $("#tasa_usd").val(1);
        tasa_usd = 1;
    }
    

    $.ajax({
      // The URL for the request
      url: "include/tdcfcv/moneda_tdcfcv_totales.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: i, 
              moneda: moneda, 
              tasa_usd: tasa_usd, 
              username: username, 
            },
      // Whether this is a POST or GET request
      type: "POST",
      // The type of data we expect back
      dataType : "json",
    })
    // Code to run if the request succeeds (is done); The response is passed to the function
    .done(function( json ) {
        // alert(json);
        // $("#xReglones").html(json);
        json = jQuery.parseJSON(json);
        // alert(json.pedido + " -- " + json.mensaje);

        if(json.estatus == 1) {
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
        } 
        else {
          alert("Error: !!! " + json.mensaje + " !!!");
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';
        }
    })
    // Code to run if the request fails; the raw request and status codes are passed to the function
    .fail(function( xhr, status, errorThrown ) {
      alert( "Sorry, there was a problem!" );
      console.log( "Error: " + errorThrown );
      console.log( "Status: " + status );
      console.dir( xhr );
    })
    // Code to run regardless of success or failure;
    .always(function( xhr, status ) {
        // document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    });  
  }

  function setDateToLote(xlote, i) {
    var myArr = xlote.split("|");
    var lote = myArr[0];
    var fecha = myArr[1];
    var cantidad = myArr[2]; 
    if(fecha != "")
      $("#x" + i + "_vence").value(fecha); 
    else 
      $("#x" + i + "_vence").value("0000-00-00"); 

    validarCantidadLote(i);
  } 

  function validarCantidadLote(i) {
    var myArr = $("#x" + i + "_lote").value().split("|");
 
    if(isNaN(myArr[2]) || parseInt(myArr[2]) == 0) return true;
    if(isNaN($("#x" + i + "_cantidad").value())) {
        $("#x" + i + "_cantidad").value(cantidad);
    }

    var cantidad_lote = parseInt(myArr[2]); 
    var cantidad = parseInt($("#x" + i + "_cantidad").value());
    if(cantidad > cantidad_lote) {
      ew.alert("La cantidad solicitada es mayor a la existencia del lote");
      $("#x" + i + "_cantidad").val("");
    } 
    myCalc(i);
  }

    function habilitarEdicion(i, id_item) {
        // 1. Habilitamos los inputs de la fila
        $("#x" + i + "_cantidad").prop('disabled', false).focus();
        $("#x" + i + "_precioFull").prop('disabled', false);
        $("#x" + i + "_descuento").prop('disabled', false);
        $("#x" + i + "_lote").prop('disabled', false);
        $("#x" + i + "_vence").prop('disabled', false);

        // 2. Cambiamos el botón de lápiz por uno de "Guardar"
        var btnGuardar = '<i class="fa-solid fa-floppy-disk text-success" style="cursor:pointer;" title="Guardar Cambios" onclick="js:guardarModificacion(' + i + ', ' + id_item + ')"></i>';
        $("#x" + i + "_boton_edit").html(btnGuardar);

        // Opcional: Resaltar la fila para saber que está en edición
        $("#x" + i + "_cantidad").closest('tr').addClass('table-warning');
    }  


</script>

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
  <div class="col-sm-2">
    <?php
    echo '<input type="text" id="descFabricante" name="descFabricante" value="' . ($descFabricante ?? '') . '" class="form-control form-control-sm" size="4" onchange="js: DiasCred();">
    <small>% Fabricante</small>';
    ?>   
  </div>

  <!-- Moneda -->
  <div class="col-sm-1">
    <select id="moneda" name="moneda" class="form-select form-select-sm" onchange="js:RefreshMonedaTasa()">
      <?php
      $sql = "SELECT SUBSTRING(valor1, 1, 3) AS moneda FROM parametro WHERE codigo = '006';";
      $rows = ExecuteRows($sql);
      foreach ($rows as $value) {
        echo '<option value="' . $value["moneda"] . '"' . ($value["moneda"]==$moneda ? ' selected="selected"' : '') . '>' . $value["moneda"] . '</option>';
      }
      ?>
    </select>
  </div>

  <!-- Tasa (ahora con label abajo) -->
  <div class="col-sm-1">
    <?php
    echo '<input name="tasa_usd" id="tasa_usd" type="number" class="form-control form-control-sm" value="' . $tasa . '" style="width: 90px;" onkeyup="js:RefreshMonedaTasa()">
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
      <select id="consignacion" name="consignacion" class="form-select form-select-sm">
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
      <textarea cols="35" rows="3" placeholder="Observaciones" class="form-control form-control-sm" id="nota"><?= $nota ?></textarea>
  </div>
</div>

<script type="text/javascript">
  $("#laboratorio").prop("disabled", true);
  $("#articulo").prop("disabled", true);

  document.getElementById("laboratorio").addEventListener("keyup", getCodigos)
  document.getElementById("laboratorio").addEventListener("click", getCodigos)
  document.getElementById("articulo").addEventListener("keyup", getCodigos2)
  document.getElementById("consignacion").addEventListener("change", limpiar)

  document.getElementById("nota").addEventListener("change", guardar_nota)

  function getCodigos() {
      let inputCP = document.getElementById("laboratorio").value
      let lista = document.getElementById("lista")
      let inputCP3 = document.getElementById("username").value
      document.getElementById("codlab").value = ""

      if (inputCP.length >= 0) {
          let url = "include/buscar_laboratorios.php"
          let formData = new FormData()
          formData.append("fabricante", inputCP)
          formData.append("username", inputCP3)

          fetch(url, {
              method: "POST",
              body: formData,
              mode: "cors" //Default cors, no-cors, same-origin
          }).then(response => response.json()) 
              .then(data => {
                  lista.style.display = 'block'
                  lista.innerHTML = data
              })
              .catch(err => console.log(err))
      } else {
          lista.style.display = 'none'
      }
  }

  function mostrar(cp) {
      lista.style.display = 'none'
      // alert("CP: " + cp)

      document.getElementById("lista2").innerHTML = ""
      document.getElementById("articulo").value = "";

      let url = "include/buscar_laboratorio_codigo_nombre.php"
      let formData = new FormData()
      formData.append("fabricante", cp)

      fetch(url, {
          method: "POST",
          body: formData,
          mode: "cors" //Default cors, no-cors, same-origin
      }).then(response => response.json()) 
          .then(data => { 
              datos = data.split("|")
              document.getElementById("codlab").value = datos[0]
              document.getElementById("laboratorio").value = datos[1]
              getCodigos2()
          })
        .catch(err => console.log(err))
  }

  function getCodigos2() {
      document.getElementById("lista").style.display = 'none'

      let inputCP = document.getElementById("consignacion").value
      let inputCP2 = document.getElementById("codlab").value
      let inputCP3 = document.getElementById("articulo").value
      let lista = document.getElementById("lista2")
      let inputCP4 = document.getElementById("codcli").value
      let inputCP6 = document.getElementById("pedido").value
      let inputCP7 = document.getElementById("username").value
      let inputCP8 = document.getElementById("tipo_documento").value
      let inputCP9 = document.getElementById("PorDesAct").value
      let inputCP10 = (document.getElementById('hubb').checked ? "SI" : "NO")
      let inputCP11 = document.getElementById("codcli").value

      document.getElementById("lista2").innerHTML = ""

      if (inputCP3.length >= 0) {

          let url = "include/tdcfcv/buscar_articulos_tdcfcv.php"
          let formData = new FormData()
          formData.append("consignacion", inputCP)
          formData.append("fabricante", inputCP2)
          formData.append("articulo", inputCP3)
          formData.append("cliente", inputCP4)
          formData.append("pedido", inputCP6)
          formData.append("username", inputCP7)
          formData.append("tipo_documento", inputCP8)
          formData.append("descuentoG", inputCP9)
          formData.append("hubb", inputCP10)
          formData.append("cliente", inputCP11)

          fetch(url, {
              method: "POST",
              body: formData,
              mode: "cors" //Default cors, no-cors, same-origin
          }).then(response => response.json()) 
              .then(data => {
                 // alert(inputCP4 + " | " + data)
                  lista.style.display = 'block'
                  lista.innerHTML = data
              })
              .catch(err => console.log(err))
      } else {
          lista.style.display = 'none'
      }
  }

    function getCodigos3(i) {
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
            else {
                $("#consignacion").val("FC").trigger("change");
                limpiar();
            }
        }

        let inputCP = i;
        let lista = document.getElementById("lista2");
        document.getElementById("lista2").innerHTML = "";

        if (inputCP > 0) {
            let url = "include/tdcfcv/listar_tdcfcv.php";
            let formData = new FormData();
            formData.append("pedido", inputCP);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            })
            .then(response => {
                // Validación de sesión expirada (Respuesta del nuevo connect.php)
                if (response.status === 401) {
                    ew.alert("Su sesión ha expirado. Será redireccionado al inicio.");
                    window.location.href = "logout"; // Ajusta la ruta según tu estructura (ej: ../logout o logout)
                    return;
                }
                
                // Si la respuesta no es exitosa por otra razón
                if (!response.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }

                return response.json();
            })
            .then(data => {
                // Solo procedemos si 'data' existe (evita errores si el 401 cortó el flujo)
                if (data) {
                    lista.style.display = 'block';
                    lista.innerHTML = data;
                    listar_pedido(i);
                }
            })
            .catch(err => {
                console.error("Error crítico en getCodigos3:", err);
            });
        } else {
            lista.style.display = 'none';
        }
    }


  function limpiar() {
    document.getElementById("codlab").value = ""
    document.getElementById("laboratorio").value = ""
    document.getElementById("articulo").value = ""
    // document.getElementById("lista2").innerHTML = ""
    getCodigos2()

    $("#laboratorio").prop("disabled", false);
    $("#articulo").prop("disabled", false);
    $("#consignacion").prop("disabled", true);
  }

  function guardar_nota() {
      let inputCP = document.getElementById("pedido").value
      let inputCP2 = document.getElementById("nota").value
      if (inputCP > 0) {
          let url = "include/tdcfcv/guardar_nota.php"
          let formData = new FormData()
          formData.append("pedido", inputCP)
          formData.append("nota", inputCP2)

          fetch(url, {
              method: "POST",
              body: formData,
              mode: "cors" //Default cors, no-cors, same-origin
          }).then(response => response.json()) 
              .then(data => {
                alert(data)
              })
              .catch(err => console.log(err))
      } 
  }

  function myCalc(i) {
    var cantidad = parseInt($("#x" + i + "_cantidad").val());
    var precioFull = $("#x" + i + "_precioFull").val();
    var descuento = $("#x" + i + "_descuento").val();
    var precio = 0;
    var total = 0;

    precio = redondearDecimales((precioFull - (precioFull*(descuento/100))), 2);
    total = redondearDecimales(cantidad*precio, 2);

    $("#x" + i + "_cantidad").val(cantidad);
    $("#x" + i + "_precioFull").val(precioFull);
    $("#x" + i + "_descuento").val(descuento);

    $("#x" + i + "_precio").val(precio);
    $("#x" + i + "_total").val(total);

    if (parseFloat($("#x" + i + "_cantidad").val()) <= 0) {
        ew.alert("La cantidad debe ser mayor a cero");
        return false;
    }    
  }

  function redondearDecimales(numero, decimales) {
      numeroRegexp = new RegExp('\\d\\.(\\d){' + decimales + ',}'); // Expresion regular para numeros con un cierto numero de decimales o mas
      if (numeroRegexp.test(numero)) { // Ya que el numero tiene el numero de decimales requeridos o mas, se realiza el redondeo
          return Number(numero.toFixed(decimales));
      } else {
          return Number(numero.toFixed(decimales)) === 0 ? 0 : numero; // En valores muy bajos, se comprueba si el numero es 0 (con el redondeo deseado), si no lo es se devuelve el numero otra vez.
      }
  }


  getCodigos3( <?= $pedido ?> )
</script>

<?= GetDebugMessage() ?>
