<?php

namespace PHPMaker2024\mandrake;

// Page object
$HomeInAdd = &$Page;
?>
<?php
$Page->showMessage();
?>

<?php
$Page->showMessage();
?>

<?php
$tipo_documento = $_REQUEST["tipo_documento"];
$tipo_documento_nombre = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo_documento';");
$proveedor = "";

$myform = "";
$linkform = '';
switch ($tipo_documento) {
case 'TDCPDC':
	$myform = "TdcpdcAdd";
	$linkform = '';
	break;
case 'TDCNRP':
	$myform = "TdcnrpAdd";
	$linkform = '<a href="ViewEntradasList?crear=TDCNRP" class="btn btn-primary">Crear Nota de Recepción desde Pedido de Compra o Factura</a>';
	break;
case 'TDCFCC':
	$myform = "TdcfccAdd";
	$linkform = '<a href="ViewEntradasList?crear=TDCFCC" class="btn btn-primary">Crear Factura desde Nota de Recepción</a>';
	break;
case 'TDCAEN':
	$myform = "TdcaenAdd";
	$linkform = '';
	break;
}
?>

<div class="container border border-primary border-top rounded p-3">
  <div class="row">
    <div class="col-sm-2">
      <strong>Proveedor</strong> 
    </div>
    <div class="col-sm-10">
      <form id="frm" name="frm" method="post" action="<?= $myform  ?>">
        <input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
        <input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->

        <input name="proveedor" id="proveedor" type="text" class="form-control form-control-sm" placeholder="Buscar Proveedor" size="50" value="" />
        <input name="codpro" id="codpro" type="hidden" class="form-control form-control-sm" value="<?= $proveedor ?>" />
        <input name="username" id="username" type="hidden" class="form-control form-control-sm" value="<?= CurrentUserName() ?>" />
        <input name="tipo_documento" id="tipo_documento" type="hidden" class="form-control form-control-sm" value="<?= $tipo_documento ?>" />
        <button id="btnCrear" class="btn btn-sm btn-primary form-control" type="submit" disabled>Crear Documento</button>
      </form>

      <ul id="lista" class="list-group"></ul>
    </div>
  </div>

<hr class="border border-primary" />

  <div class="row">
    <div class="col-sm-12">
      <div class="container border border-primary border-top rounded p-4">
        <div class="row">
          <div class="col-sm-12 p-3">
            <div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Proveedor:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="proveedorNombre"></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>RIF:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="proveedorRif"></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Tel&eacute;fono:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="proveedorTelefono"></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
					<div class="alert alert-info text-center" role="alert">
					  <h2><?= $tipo_documento_nombre ?></h2>
					</div>                  
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12 text-center">
				  <?= $linkform ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  document.getElementById("proveedor").addEventListener("keyup", getCodigos)
  document.getElementById("proveedor").addEventListener("click", getCodigos)
  document.getElementById("proveedor").addEventListener("change", getCodigos2)

  function getCodigos() {

      let inputCP = document.getElementById("proveedor").value
      let inputUN = document.getElementById("username").value
      let lista = document.getElementById("lista")
      document.getElementById('btnCrear').setAttribute("disabled","disabled")
      document.getElementById("codpro").value = ""

      if (inputCP.length > -1) {

          let url = "include/buscar_proveedores.php"
          let formData = new FormData()
          formData.append("proveedor", inputCP)
          formData.append("username", inputUN)

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
      //alert("CP: " + cp)

      let url = "include/buscar_proveedor_codigo_nombre.php"
      let formData = new FormData()
      formData.append("proveedor", cp)

      document.getElementById("proveedorNombre").innerHTML = ""
      document.getElementById("proveedorRif").innerHTML = ""
      document.getElementById("proveedorTelefono").innerHTML = ""

      fetch(url, {
          method: "POST",
          body: formData,
          mode: "cors" //Default cors, no-cors, same-origin
      }).then(response => response.json()) 
          .then(data => { 
              datos = data.split("|")
              document.getElementById('btnCrear').removeAttribute("disabled")
              document.getElementById("codpro").value = datos[0]
              document.getElementById("proveedor").value = datos[1]
              document.getElementById("proveedorNombre").innerHTML = datos[1]
              document.getElementById("proveedorRif").innerHTML = datos[2]
              document.getElementById("proveedorTelefono").innerHTML = datos[3]
          })
        .catch(err => console.log(err))
  }

  function getCodigos2() {

      let inputCP = document.getElementById("proveedor").value
      let lista = document.getElementById("lista")

      if (inputCP.length == 0) {

        document.getElementById('btnCrear').setAttribute("disabled","disabled")
        document.getElementById("codpro").value = ""
        lista.style.display = 'none'
      }
  }

  var codpro = document.getElementById("codpro").value
  if(codpro != "") {
    document.getElementById("proveedor").disabled = true
    document.getElementById("btnCrear").disabled = false
  }
</script>

<?= GetDebugMessage() ?>
