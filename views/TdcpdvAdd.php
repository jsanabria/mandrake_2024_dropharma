<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcpdvAdd = &$Page;
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
$tasa_usd = $tasa;

$pedido = 0;
$lista_pedido = "";
$nota = "";
$nro_documento = "";
$dias_credito = 0;
$descTransferencista = 0.00;
$lista_pedido = "";
$descuento_lineal = 0;
if(isset($_REQUEST["pedido"])) { 
    $pedido = $_REQUEST["pedido"];
    $sql = "SELECT cliente, tipo_documento, nota, tasa_dia, moneda, IFNULL(dias_credito, 0) AS dias_credito, lista_pedido, documento, nro_documento, IFNULL(descuento, 0) AS descuento, IFNULL(descuento2, 0) AS descuento2 FROM salidas WHERE id = $pedido;";
    if($row = ExecuteRow($sql)) {
      $codcli = $row["cliente"];
      $tipo_documento = $_REQUEST["tipo_documento"];
      $nota = $row["nota"];
      $tasa = floatval($row["tasa_dia"]);
      $tasa_usd = $tasa;
      $moneda = $row["moneda"]; 
      $nro_documento = $row["nro_documento"];  
      $dias_credito = $row["dias_credito"];
  	  $lista_pedido = $row["lista_pedido"];
      $descuento_lineal = floatval($row["descuento"]);
      $descTransferencista = floatval($row["descuento2"]);
    } 
    else {
      header("Location: ViewOutTdcpdvList");
      die();
    }
} 
else {
	$codcli = $_REQUEST["codcli"];
  // ****** Se define si al pedido se le va aplicar descuento comercial o descuento por proveedor ****** //
  $tipo_descuento = 1;
  /// ///
}

$sql = "SELECT ci_rif, nombre, IFNULL(dias_credito, 0) AS dias_credito, IFNULL(descuento, 0) AS descuento FROM cliente WHERE id = $codcli;"; 
$row = ExecuteRow($sql);
$cliente = $row["nombre"];
if($dias_credito == 0) $dias_credito = $row["dias_credito"];
if($descuento_lineal == 0)
  $descuento_lineal = floatval($row["descuento"]);

$PorDesMin = 0;
$PorDesMax = 100;
$PorDesAct = intval((isset($_REQUEST["PorDesAct"]) ? $_REQUEST["PorDesAct"] : $descuento_lineal));
?>

<script type="text/javascript">
  function insertar(i) { 
    var pedido = $("#pedido").value();
    var cliente = $("#codcli").value();
    var precioFull = $("#x" + i + "_precioFull").value();
    var descuento = $("#x" + i + "_descuento").value();
    var precio = $("#x" + i + "_precio").value();
    var moneda = $("#moneda").value();
    var cantidad = $("#x" + i + "_cantidad").value();
    var articulo = $("#x" + i + "_articulo").value();
    var tasa_usd = $("#tasa_usd").value();
    var username = '<?= CurrentUserName() ?>';
    var descuentoG = $("#PorDesAct").value();
    var descTransferencista = $("#descTransferencista").val();
    var nota = $("#nota").value();
    var lista_pedido = $("#lista_pedido").value();
    var dias_credito = $("#dias_credito").value();
    // alert(pedido + " - " + cliente + " - " + precioFull + " - " + descuento + " - " + precio + " - " + moneda + " - " + cantidad + " - " + username);

    if(isNaN(parseFloat(precioFull))) {
        alert("Debe indicar precio full.");
        return false;
    }
    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    
    $.ajax({
      // The URL for the request
      url: "include/tdcpdv/insertar_linea_pedido_tdcpdv.php",
      // The data to send tdcpdv/(will be converted to a query string)
      data: { 
              pedido: pedido, 
              cliente: cliente, 
              precioFull: precioFull, 
              descuento: descuento, 
              precio: precio, 
              moneda: moneda, 
              cantidad: cantidad, 
              articulo: articulo, 
              tasa_usd: tasa_usd, 
              username: username, 
              descuentoG: descuentoG,
              descTransferencista: descTransferencista, 
              nota: nota,
              lista_pedido: lista_pedido, 
              dias_credito: dias_credito
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcpdvList\');"><i class="fa-solid fa-list"></i> Pedidos </button>');
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

          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-trash" onclick="js:eliminar(' + i + ', ' + json.id_item + ')"></i>';
        } 
        else {
          alert("Error: !!! " + json.mensaje + " !!!");
          document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-cart-shopping" onclick="js:insertar(' + i + ')"></i>';
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
    var pedido = $("#pedido").value();
    var articulo = $("#x" + i + "_articulo").value();
    var moneda = $("#moneda").value();
    var tasa_usd = $("#tasa_usd").value();
    var username = '<?= CurrentUserName() ?>';
    var descuento = $("#PorDesAct").value();
    var descTransferencista = $("#descTransferencista").val();
    var nota = $("#nota").value();
    var lista_pedido = $("#lista_pedido").value();

    // alert(pedido + " - " + cliente + " - " + precio + " - " + descuento + " - " + precioFull + " - " + moneda + " - " + onhand + " - " + cantidad + " - " + username);
    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    $.ajax({
      // The URL for the request
      url: "include/tdcpdv/eliminar_linea_pedido_tdcpdv.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: pedido, 
              articulo: articulo, 
              moneda: moneda, 
              tasa_usd: tasa_usd, 
              username: username, 
              descuento: descuento,
              descTransferencista: descTransferencista, 
              id_item: id_item, 
              nota: nota,
              lista_pedido: lista_pedido,
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcpdvList\');"><i class="fa-solid fa-list"></i> Pedidos </button>');
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
          $("#x" + i + "_cantidad").val("0");
          $("#x" + i + "_cantidad").prop('disabled', false);

          // $("#lista_pedido").html(json.lista_pedido); 
          $("#nro_documento").html(json.nro_documento); 
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
    var lista_pedido = $("#lista_pedido").value();

    if(i > 0) {
      if(!confirm("Seguro que quiere vaciar la cesta de pedidos?")) return false;

      var pedido = $("#pedido").value();
      // Using the core $.ajax() method
      $.ajax({
        // The URL for the request
        url: "include/tdcpdv/vaciar_tdcpdv.php",
        // The data to send (will be converted to a query string)
        data: { 
                pedido: pedido, 
                username: username, 
                lista_pedido: lista_pedido,
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcpdvList\');"><i class="fa-solid fa-list"></i> Pedidos </button>');
          // $("#lista_pedido").html(json.lista_pedido); 
          $("#nro_documento").html(json.nro_documento); 
          $("#pedido").val(json.pedido);
          $("#xReglones").html(0);
          $("#xUnidades").html(0);
          $("#xTotalBs").html(0.00);
          $("#xTotalUSD").html(0.00);

          // $("#lista_pedido").prop('disabled', false);
          // $("#lista_pedido").prop("selectedIndex", 0);
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
      url: "include/tdcpdv/listar_tdcpdv_totales.php",
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
          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:sendProccess(' + json.pedido + ')" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-microchip"></i> Procesar Documento </button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewOutTdcpdvList\');"><i class="fa-solid fa-list"></i> Pedidos </button>');
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
    window.location.href = "TdcpdvProcess?pedido=" + i + "&PorDesAct=" + PorDesAct;
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

    $.ajax({
      // The URL for the request
      url: "include/tdcpdv/descuento_tdcpdv_totales.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: i, 
              descuentoG: j, 
              moneda: moneda, 
              tasa_usd: tasa_usd, 
              username: username, 
              descTransferencista: descTransferencista 
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

</script>

<div class="container border border-primary border-top rounded p-3">
  <div class="row">
    <div class="col-sm-8">
		  <h5>Cliente: <?= $cliente ?></h5>
      <div id="nroPedido">
          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: <?= $pedido ?> </button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:vaciar(' . $pedido . ')"' : ''); ?> class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar la Cesta <i class="fa-solid fa-exclamation"></i></button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:getCodigos3(' . $pedido . ')"' : ''); ?> class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace('ViewOutTdcpdvList');"><i class="fa-solid fa-list"></i> Pedidos </button>
      </div>
	    <input name="pedido" id="pedido" type="hidden" value="<?= $pedido ?>" />
	    <input name="tipo_documento" id="tipo_documento" type="hidden" value="<?= $tipo_documento ?>" />
	    <input name="codcli" id="codcli" type="hidden" value="<?= $codcli ?>" />
      <input name="moneda" id="moneda" type="hidden" value="<?= $moneda ?>" />
      <input name="tasa_usd" id="tasa_usd" type="hidden" value="<?= $tasa_usd ?>" />
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

  <div class="col-sm-2">
      <?php
      echo '<input type="text" id="descTransferencista" name="descTransferencista" value="' . $descTransferencista . '" class="form-control form-control-sm" size="4" onchange="js: DiasCred();"> % Transferencista';
      ?>
  </div>

  <div class="col-sm-2">
    <select id="dias_credito" name="dias_credito" class="form-select form-select-sm" onchange="js: guardar_nota();">
      <option value"">d&iacute;as de cr&eacute;dito</option>
      <?php
      $sql = "SELECT CAST(valor1 AS SIGNED) AS dias FROM parametro WHERE codigo = '007' ORDER BY dias;";
      $rows = ExecuteRows($sql); 
      foreach ($rows as $key => $value) {
        echo '<option value"' . $value["dias"] . '" ' . (intval($value["dias"]) == $dias_credito ? 'selected="selected"' : '') . '>' . $value["dias"] . ' d&iacute;as de cr&eacute;dito</option>';
      }
      ?>
    </select>
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
      <select id="lista_pedido" name="lista_pedido" class="form-select form-select-sm">
        <option value="">Seleccione una Categor&iacute;a</option>
        <?php
        $sql = "SELECT campo_codigo, campo_descripcion FROM tabla WHERE tabla = 'LISTA_PEDIDO';";
        $rows = ExecuteRows($sql); 
        foreach ($rows as $key => $value) {
          echo '<option value="' . $value["campo_codigo"] . '" ' . (($value["campo_codigo"] == $lista_pedido) ? 'selected="selected"' : '') . '>' . $value["campo_descripcion"] . '</option>';
        }
        ?>
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
  document.getElementById("lista_pedido").addEventListener("change", limpiar)

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

      let inputCP = document.getElementById("lista_pedido").value
      let inputCP2 = document.getElementById("codlab").value
      let inputCP3 = document.getElementById("articulo").value
      let lista = document.getElementById("lista2")
      let inputCP4 = document.getElementById("codcli").value
      let inputCP6 = document.getElementById("pedido").value
      let inputCP7 = document.getElementById("username").value
      let inputCP8 = document.getElementById("tipo_documento").value
      let inputCP9 = document.getElementById("PorDesAct").value
      let inputCP11 = document.getElementById("codcli").value

      document.getElementById("lista2").innerHTML = ""

      if (inputCP3.length >= 0) {

          let url = "include/tdcpdv/buscar_articulos_tdcpdv.php"
          let formData = new FormData()
          formData.append("lista_pedido", inputCP)
          formData.append("fabricante", inputCP2)
          formData.append("articulo", inputCP3)
          formData.append("cliente", inputCP4)
          formData.append("pedido", inputCP6)
          formData.append("username", inputCP7)
          formData.append("tipo_documento", inputCP8)
          formData.append("descuentoG", inputCP9)
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
      if(i != 0) {
        $("#laboratorio").prop("disabled", false);
        $("#articulo").prop("disabled", false);
        $("#lista_pedido").prop("disabled", true);
      } 
      else {
        if($("#lista_pedido").val() != "") {
          $("#laboratorio").prop("disabled", false);
          $("#articulo").prop("disabled", false);
          $("#lista_pedido").prop("disabled", true);
        } 
      }

      let inputCP = i
      let lista = document.getElementById("lista2")
      document.getElementById("lista2").innerHTML = ""

      if (inputCP > 0) {
          let url = "include/tdcpdv/listar_tdcpdv.php"
          let formData = new FormData()
          formData.append("pedido", inputCP)

          fetch(url, {
              method: "POST",
              body: formData,
              mode: "cors" //Default cors, no-cors, same-origin
          }).then(response => response.json()) 
              .then(data => {
                  // alert(inputCP + " | " + data)
                  lista.style.display = 'block'
                  lista.innerHTML = data
                  listar_pedido(i)
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
    $("#lista_pedido").prop("disabled", true);
  }

  function guardar_nota() {
      let inputCP = document.getElementById("pedido").value
      let inputCP2 = document.getElementById("nota").value
      let inputCP3 = document.getElementById("dias_credito").value
      if (inputCP > 0) {
          let url = "include/tdcpdv/guardar_nota.php"
          let formData = new FormData()
          formData.append("pedido", inputCP)
          formData.append("nota", inputCP2)
          formData.append("dias_credito", inputCP3)

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

  getCodigos3( <?= $pedido ?> )
</script>

<?= GetDebugMessage() ?>
