<?php

namespace PHPMaker2024\mandrake;

// Page object
$TdcaenAdd = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php
$codpro = $_REQUEST["codpro"];
$tipo_documento = $_REQUEST["tipo_documento"];

$pedido = 0;
$nota = "";
if(isset($_REQUEST["pedido"])) {
    $pedido = $_REQUEST["pedido"];
    $sql = "SELECT proveedor, tipo_documento, nota FROM entradas WHERE id = $pedido;";
    if($row = ExecuteRow($sql)) {
      $codpro = $row["proveedor"];
      $tipo_documento = $_REQUEST["tipo_documento"];
      $nota = $row["nota"];
    } 
    else {
      header("Location: ViewInTdcaenList");
      die();
    }
} 

$sql = "SELECT ci_rif, nombre FROM proveedor WHERE id = $codpro;";
$row = ExecuteRow($sql);
$proveedor = $row["nombre"];

$sql = "SELECT codigo, descripcion FROM almacen WHERE movimiento = 'S';";
$rs_alm = ExecuteRows($sql);

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
document.addEventListener("DOMContentLoaded", function () {
(function ($) {
    var $ = window.jQuery;
    
  function insertar(i) { 
    var pedido = $("#pedido").val();
    var proveedor = $("#codpro").val();
    var cantidad = $("#x" + i + "_cantidad").val();
    var articulo = $("#x" + i + "_articulo").val();
    var nota = $("#nota").val();
    var lote = $("#x" + i + "_lote").val();
    var vence = $("#x" + i + "_vence").val();
    var almacen = $("#almacen").val();
    var username = '<?= CurrentUserName() ?>';
    // alert(pedido + " - " + proveedor + " - " + costoFull + " - " + descuento + " - " + costo + " - " + moneda + " - " + total + " - " + cantidad + " - " + username);

    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    $.ajax({
      // The URL for the request
      url: "include/tdcaen/insertar_linea_pedido_tdcaen.php",
      // The data to send tdcaen/(will be converted to a query string)
      data: { 
              pedido: pedido, 
              proveedor: proveedor, 
              cantidad: cantidad, 
              articulo: articulo, 
              username: username, 
              nota: nota,
              lote: lote, 
              vence: vence,
              almacen: almacen, 
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcaenList\');"><i class="fa-solid fa-list"></i> Ajustes </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
          $("#x" + i + "_cantidad").prop('disabled', true);
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
    var nota = $("#nota").val();
    var username = '<?= CurrentUserName() ?>';

    // alert(pedido + " - " + proveedor + " - " + precio + " - " + descuento + " - " + precioFull + " - " + moneda + " - " + onhand + " - " + cantidad + " - " + username);
    // Using the core $.ajax() method
    document.getElementById("x" + i + "_boton").innerHTML = '<i class="fa-solid fa-spinner"></i>';
    $.ajax({
      // The URL for the request
      url: "include/tdcaen/eliminar_linea_pedido_tdcaen.php",
      // The data to send (will be converted to a query string)
      data: { 
              pedido: pedido, 
              articulo: articulo, 
              username: username, 
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcaenList\');"><i class="fa-solid fa-list"></i> Ajustes </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
          $("#x" + i + "_cantidad").val("0");
          $("#x" + i + "_cantidad").prop('disabled', false);
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
    if(i > 0) {
      if(!confirm("Seguro que quiere vaciar la cesta de pedidos?")) return false;

      var pedido = $("#pedido").val();
      // Using the core $.ajax() method
      $.ajax({
        // The URL for the request
        url: "include/tdcaen/vaciar_tdcaen.php",
        // The data to send (will be converted to a query string)
        data: { 
                pedido: pedido, 
                username: username 
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

          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: 0 (0000000)</button> <button type="button" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcaenList\');"><i class="fa-solid fa-list"></i> Ajustes </button>');
          $("#pedido").val(0);
          $("#xReglones").html(0);
          $("#xUnidades").html(0);
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

    $.ajax({
      // The URL for the request
      url: "include/tdcaen/listar_tdcaen_totales.php",
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
          $("#nroPedido").html('<button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: ' + json.pedido + ' (' + json.nro_documento + ')</button> <button type="button" onclick="js:vaciar(' + json.pedido + ')" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:getCodigos3(' + json.pedido + ')" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button> <button type="button" onclick="js:sendProccess(' + json.pedido + ')" class="btn btn-outline-success btn-sm"><i class="fa-solid fa-microchip"></i> Procesar Documento </button> <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace(\'ViewInTdcaenList\');"><i class="fa-solid fa-list"></i> Ajustes </button>');
          $("#pedido").val(json.pedido);
          $("#xReglones").html(json.renglones);
          $("#xUnidades").html(json.unidades);
          
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
    window.location.href = "TdcaenProcess?pedido=" + i;
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

  // Exponer al scope global las funciones que se invocan desde atributos onclick="js:..."
  window.insertar = insertar;
  window.eliminar = eliminar;
  window.vaciar = vaciar;
  window.sendProccess = sendProccess;
  window.abrirModalNuevoArticulo = abrirModalNuevoArticulo;
  window.guardarNuevoArticulo = guardarNuevoArticulo;
})(jQuery);
});
</script>

<div class="container border border-primary border-top rounded p-3">
  <div class="row">
    <div class="col-sm-8">
		  <h5>Proveedor: <?= $proveedor ?></h5>
      <div id="nroPedido">
          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-hashtag"></i> Pedido Nro.: <?= $pedido ?> </button> 
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:vaciar(' . $pedido . ')"' : ''); ?> class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i> Vaciar Toda la Cesta <i class="fa-solid fa-exclamation"></i></button>  <button type="button" class="btn btn-outline-primary btn-sm" onclick="js: window.location.replace('ViewInTdcaenList');"><i class="fa-solid fa-list"></i> Ajustes </button>
          <button type="button" <?php echo ( $pedido != 0 ? 'onclick="js:getCodigos3(' . $pedido . ')"' : ''); ?> class="btn btn-outline-info btn-sm"><i class="fa-solid fa-cart-shopping"></i> Listar la Cesta <i class="fa-solid fa-exclamation"></i></button>
      </div>
	    <input name="pedido" id="pedido" type="hidden" value="<?= $pedido ?>" />
	    <input name="tipo_documento" id="tipo_documento" type="hidden" value="<?= $tipo_documento ?>" />
	    <input name="codpro" id="codpro" type="hidden" value="<?= $codpro ?>" />
      <input name="username" id="username" type="hidden" class="form-control form-control-sm" value="<?= CurrentUserName() ?>" />
    </div>
    <div class="col-sm-4">
    </div>
  </div>

<hr class="border border-primary" />

  <div class="row">
    <div class="col-sm-6">
      <div>
        <div class="row">
          <div class="col-sm-6 text-center">
            <h1 class="px-3 py-3 text-center">Renglones</h1>
          </div>
          <div class="col-sm-6 alert alert-info" role="alert">
            <h6>Renglones</h6>
            <span id="xReglones">0</span>
          </div>
        </div>
      </div>      
    </div>

    <div class="col-sm-6">
      <div>
        <div class="row">
          <div class="col-sm-6">
            <h1 class="px-3 py-3 text-center">Unidades</h1>
          </div>
          <div class="col-sm-6 alert alert-info" role="alert">
            <h6>Unidades</h6>
            <span id="xUnidades">0</span>
          </div>
        </div>
      </div>      
    </div>

  </div>

<hr class="border border-primary" />

  <div class="row align-items-end g-3">

      <div class="col-md-1 text-center">
          <label class="form-label d-block mb-1">Rep. Art.</label>
          <input type="checkbox" id="hubb" name="hubb" value="SI" checked>
      </div>

      <div class="col-md-2">
          <label class="form-label mb-1 fw-bold">Almacén:</label>
          <select name="almacen" id="almacen" class="form-select form-select-sm w-100">
              <?php foreach($rs_alm as $alm) { ?>
                  <option value="<?= $alm["codigo"] ?>" <?= ($alm["codigo"] == "ALM001") ? "selected" : "" ?>>
                      <?= $alm["descripcion"] ?>
                  </option>
              <?php } ?>
          </select>
      </div>

      <div class="col-md-3">
          <label class="form-label mb-1 fw-bold">Fabricante:</label>
          <input name="laboratorio" id="laboratorio" type="text"
                 class="form-control form-control-sm w-100"
                 placeholder="Buscar Laboratorio" />
          <input name="codlab" id="codlab" type="hidden" />
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
              <th width="10%">&nbsp</th>
              <th width="60%">At&iacute;culo</th>
              <th width="10%" class="text-center">Cant.</th>
              <th width="20%" class="text-center">Lote</th>
              <th width="20%" class="text-center">Vence</th>
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
document.addEventListener("DOMContentLoaded", function () {
(function ($) {
    var $ = window.jQuery;
    
  $("#laboratorio").prop("disabled", true);
  $("#articulo").prop("disabled", true);

  document.getElementById("laboratorio").addEventListener("keyup", getCodigos)
  document.getElementById("laboratorio").addEventListener("click", getCodigos)
  document.getElementById("articulo").addEventListener("keyup", getCodigos2)

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

      let inputCP2 = document.getElementById("codlab").value
      let inputCP3 = document.getElementById("articulo").value
      let lista = document.getElementById("lista2")
      let inputCP4 = document.getElementById("codpro").value
      let inputCP6 = document.getElementById("pedido").value
      let inputCP7 = document.getElementById("username").value
      let inputCP8 = document.getElementById("tipo_documento").value
      let inputCP9 = (document.getElementById('hubb').checked ? "SI" : "NO")

      document.getElementById("lista2").innerHTML = ""

      if (inputCP3.length >= 0) {

          let url = "include/tdcaen/buscar_articulos_tdcaen.php"
          let formData = new FormData()
          formData.append("fabricante", inputCP2)
          formData.append("articulo", inputCP3)
          formData.append("proveedor", inputCP4)
          formData.append("pedido", inputCP6)
          formData.append("username", inputCP7)
          formData.append("tipo_documento", inputCP8)
          formData.append("hubb", inputCP9)

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
      $("#laboratorio").prop("disabled", false);
      $("#articulo").prop("disabled", false);

      let inputCP = i
      let lista = document.getElementById("lista2")
      document.getElementById("lista2").innerHTML = ""

      if (inputCP > 0) {
          let url = "include/tdcaen/listar_tdcaen.php"
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
  }

  function guardar_nota() {
      let inputCP = document.getElementById("pedido").value
      let inputCP2 = document.getElementById("nota").value
      if (inputCP > 0) {
          let url = "include/tdcaen/guardar_nota.php"
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

  // Exponer al scope global las funciones que se invocan desde atributos onclick="js:..."
  window.getCodigos3 = getCodigos3;
  window.getCodigos = getCodigos;
  window.getCodigos2 = getCodigos2;
  window.limpiar = limpiar;
  window.guardar_nota = guardar_nota;

  getCodigos3( <?= $pedido ?> )
})(jQuery);
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
