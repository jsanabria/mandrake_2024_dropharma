<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcfccAdd = &$Page;
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

// Valor predeterminado según la configuración de la compañía.
$aplica_retencion = strtoupper(trim(
    ExecuteScalar("
        SELECT IFNULL(agente_retencion, 'N')
        FROM compania
        WHERE id = 1
        LIMIT 1
    ") ?? "N"
));

/*
|--------------------------------------------------------------------------
| REGLA DE NEGOCIO - MANDRAKE
|--------------------------------------------------------------------------
| Autor : Junior Enrique Sanabria Rubio
| Fecha : 05/08/2026
|
| A partir de esta versión, todo el control tributario de las compras se
| centraliza en el módulo de Compras Administrativas (tabla compra).
|
| Esto incluye:
|   • Libro de Compras.
|   • Retenciones de IVA.
|   • Retenciones de ISLR.
|   • Retenciones Municipales.
|   • Generación de comprobantes de retención.
|   • Consecutivos de comprobantes.
|   • Cálculo del monto a pagar al proveedor.
|
| El módulo TDCFCC (Compras de Inventario) tendrá únicamente la finalidad
| de registrar el detalle de los artículos adquiridos para:
|
|   • Actualizar existencias.
|   • Registrar costos.
|   • Calcular costo promedio.
|   • Mantener el historial del inventario.
|
| Cuando una compra corresponda a inventario, la factura deberá registrarse
| primero en Compras Administrativas para cumplir con las obligaciones
| fiscales y posteriormente podrá registrarse en Compras de Inventario para
| efectos exclusivos del control de costos.
|
| En consecuencia, este módulo NO genera retenciones ni comprobantes de
| retención, aun cuando técnicamente existe la lógica para hacerlo.
| Esta decisión evita duplicar el control tributario entre dos módulos y
| garantiza que toda la información fiscal provenga de una única fuente.
|--------------------------------------------------------------------------
*/

// if ($aplica_retencion !== "S") {
    $aplica_retencion = "N";
// }

if(isset($_REQUEST["pedido"])) {
    $pedido = $_REQUEST["pedido"];
    $sql = "
        SELECT
            proveedor,
            tipo_documento,
            nota,
            tasa_dia,
            moneda,
            documento,
            IFNULL(aplica_retencion, 'N') AS aplica_retencion
        FROM entradas
        WHERE id = " . intval($pedido) . "
        LIMIT 1;
    ";
    if($row = ExecuteRow($sql)) {
      $codpro = $row["proveedor"];
      $tipo_documento = $_REQUEST["tipo_documento"];
      $nota = $row["nota"];
      $tasa = floatval($row["tasa_dia"]);
      $moneda = $row["moneda"]; 
      $consignacion = $row["documento"]; 
      $aplica_retencion = (
            strtoupper(trim($row["aplica_retencion"] ?? "N")) === "S"
        ) ? "S" : "N";
    } 
    else {
      header("Location: ViewInTdcfccList");
      die();
    }
} 

$sql = "SELECT ci_rif, nombre FROM proveedor WHERE id = $codpro;";
$row = ExecuteRow($sql);
$proveedor = $row["nombre"];

$PorDesMin = 0;
$PorDesMax = 100;
$PorDesAct = intval((isset($_REQUEST["PorDesAct"]) ? $_REQUEST["PorDesAct"] : 0));

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

?>

<script type="text/javascript">
loadjs.ready(["jquery"], function () {
    const $ = jQuery;

  function insertar(i) { 
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
    var lote = $("#x" + i + "_lote").val();
    var vence = $("#x" + i + "_vence").val();
    var aplica_retencion = $("#aplica_retencion").is(":checked")
    ? "S"
    : "N";
    // alert(pedido + " - " + proveedor + " - " + costoFull + " - " + descuento + " - " + costo + " - " + moneda + " - " + total + " - " + cantidad + " - " + username);

    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    $.ajax({
      // The URL for the request
      url: "include/tdcfcc/insertar_linea_pedido_tdcfcc.php",
      // The data to send tdcfcc/(will be converted to a query string)
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
              consignacion: consignacion, 
              lote: lote, 
              vence: vence, 
              aplica_retencion: aplica_retencion,
            },
      // Whether this is a POST or GET request
      type: "POST",
      // The type of data we expect back
      dataType : "json",
    })
    // Code to run if the request succeeds (is done); The response is passed to the function
    .done(function( json ) {
        // alert(json);
        json = jQuery.parseJSON(json);

        if(json.estatus == 1) {
          // document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ')"></i>';

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcfccList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
          $("#xTotalBs").html(formatter.format(json.total));
          $("#xTotalUSD").html(formatter.format(json.total_usd));
          /*
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          */
          $("#x" + i + "_cantidad").prop('disabled', true);
          $("#x" + i + "_costoFull").prop('disabled', true);
          $("#x" + i + "_descuento").prop('disabled', true);
          $("#x" + i + "_lote").prop('disabled', true);
          $("#x" + i + "_vence").prop('disabled', true);

          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ', ' + json.id_item + ')"></i>';
        } 
        else {
          alert("Error: !!! " + json.mensaje + " !!!");
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-cart-shopping" onclick="js:insertar(' + i + ')"></i>';
          $("#x" + i + "_cantidad").val("");
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

  function eliminar(i, id_item) {
    var pedido = $("#pedido").val();
    var articulo = $("#x" + i + "_articulo").val();
    var moneda = $("#moneda").val();
    var tasa_usd = $("#tasa_usd").val();
    var username = '<?= CurrentUserName() ?>';
    var descuento = $("#PorDesAct").val();
    var nota = $("#nota").val();

    // alert(pedido + " - " + proveedor + " - " + precio + " - " + descuento + " - " + precioFull + " - " + moneda + " - " + onhand + " - " + cantidad + " - " + username);
    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    $.ajax({
      // The URL for the request
      url: "include/tdcfcc/eliminar_linea_pedido_tdcfcc.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: pedido, 
              articulo: articulo, 
              moneda: moneda, 
              tasa_usd: tasa_usd, 
              username: username, 
              descuento: descuento,
              id_item: id_item, 
              nota: nota, 
            },
      // Whether this is a POST or GET request
      type: "POST",
      // The type of data we expect back
      dataType : "json",
    })
    // Code to run if the request succeeds (is done); The response is passed to the function
    .done(function( json ) {
        // console.log( json );
        json = jQuery.parseJSON(json);
        // alert(json.pedido + " -- " + json.mensaje);

        if(json.estatus == 1) {
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-cart-shopping" onclick="js:insertar(' + i + ')"></i>';

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcfccList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          /*
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          */
          // $("#x" + i + "_cantidad").val("0");
          // myCalc(i);
          $("#x" + i + "_cantidad").prop('disabled', false);
          $("#x" + i + "_costoFull").prop('disabled', false);
          $("#x" + i + "_descuento").prop('disabled', false);
          $("#x" + i + "_lote").prop('disabled', false);
          $("#x" + i + "_vence").prop('disabled', false);
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

  function vaciar(i) {
    var username = '<?= CurrentUserName() ?>';
    var borrarCabecera = 'S';

    if(i > 0) {
      if(!confirm("Seguro que quiere vaciar la cesta de pedidos?")) return false;

      // if(confirm("Vaciar la cabecera del documento?")) borrarCabecera = 'S';

      var pedido = $("#pedido").val();
      // Using the core $.ajax() method
      $.ajax({
        // The URL for the request
        url: "include/tdcfcc/vaciar_tdcfcc.php",
        // The data to send (will be converted to a query string)
        data: { 
                pedido: pedido, 
                username: username, 
                borrarCabecera: borrarCabecera
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcfccList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          $("#pedido").val(json.pedido);
          // $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: 0 (0000000)</button> <button type="button" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button>');
          // $("#pedido").val(0);
          $("#xReglones").html(0);
          $("#xUnidades").html(0);
          $("#xTotalBs").html(0.00);
          $("#xTotalUSD").html(0.00);

          $("#consignacion").prop('disabled', false);
          $("#consignacion").prop("selectedIndex", 0);
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
    var PorDesMin = parseInt($("#PorDesMin").val(), 10);
    var PorDesMax = parseInt($("#PorDesMax").val(), 10);
    var PorDesAct = parseInt($("#PorDesAct").val(), 10);

    $.ajax({
      // The URL for the request
      url: "include/tdcfcc/listar_tdcfcc_totales.php",
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
          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:sendProccess(' + json.pedido + ')" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-microchip"></i> Procesar Documento </button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcfccList\');"><i class="fa-solid fa-list"></i> Facturas </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          /*
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          */
          
          PorDesAct = parseInt(json.descuento || 0, 10);

          $("#PorDesAct").val(PorDesAct);
          $("#rangoDescuentoProveedor").val(PorDesAct);
          $("#lblDescuentoProveedor").text(PorDesAct + "%");
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
    var PorDesAct = $("#PorDesAct").val();
    window.location.href = "TdcfccProcess?pedido=" + i + "&PorDesAct=" + PorDesAct;
  }

  function ProgLess() {
    var i = $("#pedido").val();
    var PorDesMin = parseInt($("#PorDesMin").val(), 10);
    var PorDesMax = parseInt($("#PorDesMax").val(), 10);
    var PorDesAct = parseInt($("#PorDesAct").val(), 10);

    if(i == 0) return false;

    if(PorDesAct>PorDesMin && PorDesAct<=PorDesMax) {
      PorDesAct -= 1;
      $("#PorDesAct").val(PorDesAct);
      $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
      RefreshDescuento(i, PorDesAct);
    }
  }

  function ProgPlus() {
    var i = $("#pedido").val();
    var PorDesMin = parseInt($("#PorDesMin").val(), 10);
    var PorDesMax = parseInt($("#PorDesMax").val(), 10);
    var PorDesAct = parseInt($("#PorDesAct").val(), 10);

    if(i == 0) return false;

    if(PorDesAct>=PorDesMin && PorDesAct<PorDesMax) {
      PorDesAct += 1;
      $("#PorDesAct").val(PorDesAct);
      $("#xProgress").html('<div class="progress"><div class="progress-bar" role="progressbar" style="width: ' + PorDesAct + '%" aria-valuenow="' + PorDesAct + '" aria-valuemin="' + PorDesMin + '" aria-valuemax="' + PorDesMax + '">' + PorDesAct + '%</div></div>');
      RefreshDescuento(i, PorDesAct);
    }
  }

  function DiasCred() {
    var i = $("#pedido").val();
    var PorDesAct = parseInt($("#PorDesAct").val(), 10);

    if(i == 0) return false;
    RefreshDescuento(i, PorDesAct);
  }

  function RefreshDescuento(i, j) {
    var moneda = $("#moneda").val();
    var tasa_usd = $("#tasa_usd").val();
    var username = '<?= CurrentUserName() ?>';
   

    $.ajax({
      // The URL for the request
      url: "include/tdcfcc/descuento_tdcfcc_totales.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: i, 
              descuentoG: j, 
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
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          /*
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          */
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
    var i = $("#pedido").val();
    var moneda = $("#moneda").val();
    var tasa_usd = $("#tasa_usd").val();
    var username = '<?= CurrentUserName() ?>';

    if(i == 0) return false;

    $.ajax({
      // The URL for the request
      url: "include/tdcfcc/moneda_tdcfcc_totales.php",
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
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          /*
          if(json.total == json.monto_sin_descuento) {
            $("#xTotalBs").html(formatter.format(json.total));
            $("#xTotalUSD").html(formatter.format(json.total_usd));
          }
          else {
            $("#xTotalBs").html(formatter.format(json.total) + "<br><del>" + formatter.format(json.monto_sin_descuento) + "</del>");
            $("#xTotalUSD").html(formatter.format(json.total_usd) + "<br><del>" + formatter.format(json.total_usd_sin_descuento) + "</del>");
          }
          */
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

  function abrirModalNuevoArticulo() {
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
  }

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

  function guardarNuevoArticulo() {
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
  }

  // Exponer al scope global las funciones que se invocan desde atributos onclick="js:..." u onclick/onchange directos
  window.insertar = insertar;
  window.eliminar = eliminar;
  window.vaciar = vaciar;
  window.listar_pedido = listar_pedido;
  window.sendProccess = sendProccess;
  window.ProgLess = ProgLess;
  window.ProgPlus = ProgPlus;
  window.DiasCred = DiasCred;
  window.RefreshDescuento = RefreshDescuento;
  window.RefreshMonedaTasa = RefreshMonedaTasa;
  window.abrirModalNuevoArticulo = abrirModalNuevoArticulo;
  window.guardarNuevoArticulo = guardarNuevoArticulo;
});
</script>

<div class="container border border-primary border-top rounded p-3">
  <div class="row">
    <div class="col-sm-8">
		  <h5>Proveedor: <?= $proveedor ?></h5>
      <div id="nroPedido">
          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: <?= $pedido ?> </button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:vaciar(' . $pedido . ')"' : ''); ?> class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:getCodigos3(' . $pedido . ')"' : ''); ?> class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace('ViewInTdcfccList');"><i class="fa-solid fa-list"></i> Facturas </button>
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

<div class="row g-3 align-items-end">

    <!-- Descuento proveedor -->
    <div class="col-12 col-md-6">
        <div class="border rounded p-2 bg-light h-100">
            <label for="rangoDescuentoProveedor"
                   class="form-label small fw-bold mb-1">
                Descuento proveedor:
                <span id="lblDescuentoProveedor"
                      class="text-primary">
                    <?= intval($PorDesAct) ?>%
                </span>
            </label>

            <input type="range"
                   class="form-range"
                   min="<?= intval($PorDesMin) ?>"
                   max="80"
                   step="1"
                   id="rangoDescuentoProveedor"
                   value="<?= intval($PorDesAct) ?>">
        </div>
    </div>

    <!-- Aplicar retención -->
    <div class="col-12 col-md-2">
        <div class="border rounded p-2 bg-light h-100 d-flex align-items-center">
            <div class="form-check form-switch mb-0">
                <input class="form-check-input"
                       type="checkbox"
                       role="switch"
                       id="aplica_retencion"
                       disabled
                       name="aplica_retencion"
                       value="S"
                       <?= ($aplica_retencion === "S") ? "checked" : "" ?>>

                <label class="form-check-label fw-bold"
                       for="aplica_retencion">
                    Aplicar Retención
                </label>
            </div>
        </div>
        <small class="text-muted">
        Las retenciones se gestionan únicamente desde Compras Administrativas.
        </small>
    </div>

    <!-- Moneda -->
    <div class="col-6 col-md-2">
        <label for="moneda"
               class="form-label small fw-bold mb-1">
            Moneda
        </label>

        <select id="moneda"
                name="moneda"
                class="form-select form-select-sm"
                onchange="js:RefreshMonedaTasa()">
            <?php
            $rows = ExecuteRows("
                SELECT SUBSTRING(valor1, 1, 3) AS moneda
                FROM parametro
                WHERE codigo = '006'
            ");

            foreach ($rows as $value) {
                echo '<option value="' .
                    HtmlEncode($value["moneda"]) . '"' .
                    ($value["moneda"] == $moneda
                        ? ' selected="selected"'
                        : '') .
                    '>' .
                    HtmlEncode($value["moneda"]) .
                    '</option>';
            }
            ?>
        </select>
    </div>

    <!-- Tasa BCV -->
    <div class="col-6 col-md-2">
        <label for="tasa_usd"
               class="form-label small fw-bold mb-1">
            Tasa B.C.V.
        </label>

        <input name="tasa_usd"
               id="tasa_usd"
               type="number"
               class="form-control form-control-sm text-end"
               value="<?= $tasa ?>"
               onkeyup="js:RefreshMonedaTasa()">
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
          <select id="consignacion" name="consignacion" class="form-select form-select-sm">
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

      <div class="col-md-3 text-end">
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
      let inputCP4 = document.getElementById("codpro").value
      let inputCP6 = document.getElementById("pedido").value
      let inputCP7 = document.getElementById("username").value
      let inputCP8 = document.getElementById("tipo_documento").value
      let inputCP9 = document.getElementById("PorDesAct").value
      let inputCP10 = (document.getElementById('hubb').checked ? "SI" : "NO")

      document.getElementById("lista2").innerHTML = ""

      if (inputCP3.length >= 0) {

          let url = "include/tdcfcc/buscar_articulos_tdcfcc.php"
          let formData = new FormData()
          formData.append("consignacion", inputCP)
          formData.append("fabricante", inputCP2)
          formData.append("articulo", inputCP3)
          formData.append("proveedor", inputCP4)
          formData.append("pedido", inputCP6)
          formData.append("username", inputCP7)
          formData.append("tipo_documento", inputCP8)
          formData.append("descuentoG", inputCP9)
          formData.append("hubb", inputCP10)

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
      if(i != 0) {
        $("#laboratorio").prop("disabled", false);
        $("#articulo").prop("disabled", false);
        $("#consignacion").prop("disabled", true);
      } 
      else {
        if($("#consignacion").val() != "") {
          $("#laboratorio").prop("disabled", false);
          $("#articulo").prop("disabled", false);
          $("#consignacion").prop("disabled", true);
        } 
      }

      let inputCP = i
      let lista = document.getElementById("lista2")
      document.getElementById("lista2").innerHTML = ""

      if (inputCP > 0) {
          let url = "include/tdcfcc/listar_tdcfcc.php"
          let formData = new FormData()
          formData.append("pedido", inputCP)

          fetch(url, {
              method: "POST",
              body: formData,
              mode: "cors" //Default cors, no-cors, same-origin
          }).then(response => response.json()) 
            .then(data => {
                lista.style.display = "block";
                lista.innerHTML = data;

                if (typeof window.listar_pedido === "function") {
                    window.listar_pedido(i);
                } else {
                    console.error(
                        "La función listar_pedido no está disponible."
                    );
                }
            })
            .catch(err => console.log(err))
      } else {
          lista.style.display = 'none'
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
          let url = "include/tdcfcc/guardar_nota.php"
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
    var costoFull = $("#x" + i + "_costoFull").val();
    var descuento = $("#x" + i + "_descuento").val();
    var costo = 0;
    var total = 0;

    costo = redondearDecimales(costoFull - (costoFull*(descuento/100)), 2);
    total = redondearDecimales(cantidad*costo, 2);

    $("#x" + i + "_cantidad").val(cantidad);
    $("#x" + i + "_costoFull").val(costoFull);
    $("#x" + i + "_descuento").val(descuento);

    $("#x" + i + "_costo").val(costo);
    $("#x" + i + "_total").val(total);
  }

  function redondearDecimales(numero, decimales) {
      numeroRegexp = new RegExp('\\d\\.(\\d){' + decimales + ',}'); // Expresion regular para numeros con un cierto numero de decimales o mas
      if (numeroRegexp.test(numero)) { // Ya que el numero tiene el numero de decimales requeridos o mas, se realiza el redondeo
          return Number(numero.toFixed(decimales));
      } else {
          return Number(numero.toFixed(decimales)) === 0 ? 0 : numero; // En valores muy bajos, se comprueba si el numero es 0 (con el redondeo deseado), si no lo es se devuelve el numero otra vez.
      }
  }

  function iniciarTdcfccAdd() {
      getCodigos3(<?= intval($pedido) ?>);
  }

    $(document).on("input", "#rangoDescuentoProveedor", function () {
        $("#lblDescuentoProveedor").text($(this).val() + "%");
    });

    $(document).on("change", "#rangoDescuentoProveedor", function () {
        const pedido = parseInt($("#pedido").val() || 0, 10);
        const descuento = parseInt($(this).val() || 0, 10);

        $("#PorDesAct").val(descuento);
        $("#lblDescuentoProveedor").text(descuento + "%");

        if (pedido <= 0) {
            return;
        }

        window.RefreshDescuento(pedido, descuento);
    });

    $(document).on("change", "#aplica_retencion", function () {
        const pedido = parseInt($("#pedido").val() || 0, 10);
        const aplicaRetencion = $(this).is(":checked") ? "S" : "N";
        const $checkbox = $(this);

        /*
        * Si todavía no existe la cabecera, el valor viajará cuando se agregue
        * el primer artículo. No hay registro de entradas que actualizar aún.
        */
        if (pedido <= 0) {
            return;
        }

        $checkbox.prop("disabled", true);

        $.ajax({
            url: "include/tdcfcc/actualizar_aplica_retencion.php",
            type: "POST",
            dataType: "json",
            data: {
                pedido: pedido,
                aplica_retencion: aplicaRetencion
            }
        })
        .done(function (response) {
            if (typeof response === "string") {
                response = JSON.parse(response);
            }

            if (response.estatus != 1) {
                $checkbox.prop(
                    "checked",
                    aplicaRetencion !== "S"
                );

                if (typeof ew !== "undefined" && ew.alert) {
                    ew.alert(
                        response.mensaje ||
                        "No se pudo actualizar la retención."
                    );
                }
            }
        })
        .fail(function (xhr) {
            console.log(xhr.responseText);

            // Revertir el cambio visual si falla la actualización.
            $checkbox.prop(
                "checked",
                aplicaRetencion !== "S"
            );

            if (typeof ew !== "undefined" && ew.alert) {
                ew.alert(
                    "Error actualizando la condición de retención."
                );
            }
        })
        .always(function () {
            $checkbox.prop("disabled", false);
        });
    });

  // Exponer al scope global las funciones que se invocan desde atributos onclick="js:..." o addEventListener
  window.getCodigos3 = getCodigos3;
  window.getCodigos = getCodigos;
  window.getCodigos2 = getCodigos2;
  window.mostrar = mostrar;
  window.limpiar = limpiar;
  window.guardar_nota = guardar_nota;
  window.myCalc = myCalc;

  iniciarTdcfccAdd();
});
</script>

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
