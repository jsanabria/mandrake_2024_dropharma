<?php

namespace PHPMaker2024\mandrake;

// Page object
$HomeOutAdd = &$Page;
?>
<?php
$Page->showMessage();
?>

<?php
$tipo_documento = $_REQUEST["tipo_documento"];
$tipo_documento_nombre = ExecuteScalar("SELECT descripcion FROM tipo_documento WHERE codigo = '$tipo_documento';");
$asesor = "";
$cliente = "";
$proveedor = "";

$myform = "";
$linkform = '';
switch ($tipo_documento) {
case 'TDCPDV':
	$myform = "TdcpdvAdd";
	$linkform = '';
	break;
case 'TDCNET':
	//$myform = "TdcnetAdd";
    $myform = "AjusteSalida?id=0&tipo_documento=TDCNET";
	$linkform = '<a href="ViewSalidasList?crear=TDCNET" class="btn btn-primary">Crear Nota de Entrega desde Pedido de Venta o Factura</a>';
	break;
case 'TDCFCV':
	$myform = "TdcfcvAdd";
	$linkform = '<a href="ViewSalidasList?crear=TDCFCV&consig=0" class="btn btn-primary">Crear Factura desde Nota de Entrega</a>&nbsp;&nbsp;';
	$linkform .= '<a href="ViewSalidasList?crear=TDCFCV&consig=1" class="btn btn-primary">Nota de Entrega a Consignaci&oacute;n</a>&nbsp;&nbsp;';
	$linkform .= '<a href="ClienteConsignacionLista" class="btn btn-primary">Clientes a Consignaci&oacute;n</a>';
	break;
case 'TDCASA':
	$myform = "AjusteSalida?id=0&tipo_documento=TDCASA";
	$linkform = '';
	break;
}

$sql = "SELECT 
          IFNULL(asesor, '') AS asesor, 
          IFNULL(cliente, '') AS cliente, 
          IFNULL(proveedor, '') AS proveedor 
        FROM usuario WHERE username = '" . trim(CurrentUserName()) . "';"; 
if($row = ExecuteRow($sql)) {
  $asesor = $row["asesor"];
  $cliente = $row["cliente"];
  $proveedor = $row["proveedor"];
}

$clienteNombre = "";
$clienteRif = "";
$clienteTelefono = "";
$clienteTarifa = "";
$clienteDias = 0;
$CodigoSicm = "";
$EstatusCliente = "";
$tipo_cliente = "";
if(intval($cliente) > 0) {
  $sql = "SELECT 
            a.id, a.nombre, a.ci_rif, IFNULL(dias_credito, 0) AS dias_credito, 
            CONCAT(IFNULL(a.telefono1, ''), IF(IFNULL(a.telefono2, '') = '', '', ' / '), IFNULL(a.telefono2, '')) AS telefono, 
            a.tarifa, a.ciudad, a.codigo_ims, IF(a.activo='S', 'ACTIVO', 'INACTIVO') AS activo, 
            (SELECT campo_descripcion AS ciudad FROM tabla WHERE tabla = 'TIPO_CLIENTE' AND campo_codigo = a.tipo_cliente) AS tipo_cliente    
          FROM 
            cliente AS a 
          WHERE a.id = $cliente;"; 
  $row = ExecuteRow($sql);
  $clienteNombre = $row["nombre"];
  $clienteRif = $row["ci_rif"];
  $clienteTelefono = $row["telefono"];
  $clienteTarifa = $row["tarifa"];
  $clienteDias = $row["dias_credito"];
  $CodigoSicm = $row["codigo_ims"];
  $EstatusCliente = $row["activo"];
  $tipo_cliente = $row["tipo_cliente"];

  $sql = "SELECT nombre FROM tarifa WHERE id = $clienteTarifa;"; 
  $row = ExecuteRow($sql);
  $clienteTarifa = $row["nombre"];
}
?>


<div class="container border border-primary border-top rounded p-3">

    <?php
    $sql = "SELECT valor1 FROM parametro WHERE codigo = '013';";
    $bloquea = ExecuteScalar($sql);
    if($tipo_documento != "TDCPDV") $bloquea = "N";
    if($bloquea == "SI") { 
        ?>
        <div class="alert alert-danger" role="alert">PROCESO DE PEDIDO DE VENTAS BLOQUEADO TEMPORALMENTE POR MANTENIMIENTO</div>
        <?php
    } 
    else { 
        ?>
        <div class="row">
            <div class="col-sm-2">
            <strong>Cliente</strong> 
            </div>
            <div class="col-sm-10">
            <form id="frm" name="frm" method="post" action="<?= $myform  ?>">
                <input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
                <input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->

                <input type="hidden" name="tipo_documento" value="<?= $tipo_documento ?>">

                <input name="cliente" id="cliente" type="text" class="form-control form-control-sm" placeholder="Buscar Cliente" size="50" value="<?= $clienteNombre ?>" />
                <input name="codcli" id="codcli" type="hidden" class="form-control form-control-sm" value="<?= $cliente ?>" />
                <input name="username" id="username" type="hidden" class="form-control form-control-sm" value="<?= CurrentUserName() ?>" />
                <button id="btnCrear" class="btn btn-sm btn-primary form-control" type="submit" disabled>Crear Pedido</button>
            </form>

            <ul id="lista" class="list-group"></ul>
            </div>
        </div>
        <?php
    }
  ?>

<hr class="border border-primary" />

  <div class="row">
    <div class="col-sm-6">
      <div class="container border border-primary border-top rounded p-4">
        <div class="row">
            <div class="container border border-primary border-top rounded p-3">
                <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                    <?php 
                    $sql = "SELECT titulo, subtitulo, imagen FROM banner WHERE activo = 1 AND tipo = '1';";
                    $rows = ExecuteRows($sql);
                    $sw=true;
                    $i=0;
                    foreach ($rows as $key => $value) {
                        echo '<button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="' . $i . '" ' . ($sw ? 'class="active" aria-current="true"' : '') . ' aria-label="Slide ' . $i . '"></button>';
                        $sw=false;
                        $i++;
                    }
                    ?>
                    </div>

                    <div class="carousel-inner">
                    <?php 
                    $sql = "SELECT titulo, subtitulo, imagen FROM banner WHERE activo = 1 AND tipo = '1';";
                    $rows = ExecuteRows($sql);
                    $sw=true;
                    foreach ($rows as $key => $value) {
                        echo '<div class="carousel-item' . ($sw ? " active" : "") . '" data-bs-interval="3000">
                        <img src="files/' . $value["imagen"] . '" class="d-block w-100" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>' . $value["titulo"] . '</h5>
                            <p>' . $value["subtitulo"] . '</p>
                        </div>
                        </div>';
                        $sw=false;
                    }
                    ?>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>          
          <!--
          <div class="col-sm-12">
            <img src="images/Logo_grande.png" alt="Logo" class="img-thumbnail">
          </div>
          -->
        </div>
        <div class="row">
          <div class="col-sm-12 p-3">
            <div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Cliente:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="clienteNombre"><?= $clienteNombre ?></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>RIF:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="clienteRif"><?= $clienteRif ?></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Tel&eacute;fono:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="clienteTelefono"><?= $clienteTelefono ?></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Tarifa:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="clienteTarifa"><?= $clienteTarifa ?></span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>Cr&eacute;dito:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="clienteDias"><?= $clienteDias ?> Día(s)</span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                  <h6>C&oacute;digo SICM:</h6>
                </div>
                <div class="col-sm-9">
                  <h6><span id="CodigoSicm"><?= $CodigoSicm ?></span></h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="container p-1">

        <table class="table table-lg">
          <thead>
            <tr class="table-active">
              <th scope="col" colspan="2">Datos Financieros</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="table-success" width="30%">Estatus:</td>
              <td class="table-success" width="70%"><span id="EstatusCliente"><?= $EstatusCliente ?></span></td>
            </tr>
            <tr>
              <td class="table-success" width="30%">Causa:</td>
              <td class="table-success" width="70%"><span id="clienteTipoCliente"><?= $tipo_cliente ?></span></td>
            </tr>
            <!--
            <tr>
              <td class="table-primary" width="30%">Disponibilidad:</td>
              <td class="table-primary" width="70%"></td>
            </tr>
            -->
          </tbody>
        </table>

<hr class="border border-primary" />

        <table class="table table-sm table-striped">
          <thead>
            <tr class="table-active">
              <th scope="col" colspan="2">Datos Comerciales</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="table-light" width="30%">Asesor:</td>
              <td class="table-light" width="70%"><span id="asesorNombre"></span></td>
            </tr>
            <tr>
              <td class="table-light" width="30%">Contacto:</td>
              <td class="table-light" width="70%"><span id="asesorCiudad"></span></td>
            </tr>
            <tr>
              <td class="table-light" width="30%">Tel&eacute;fono:</td>
              <td class="table-light" width="70%"><span id="asesorTelefono"></span></td>
            </tr>
            <tr>
              <td class="table-light" width="30%">Correo:</td>
              <td class="table-light" width="70%"><span id="asesorEmail"></span></td>
            </tr>
          </tbody>
        </table>
      </div>
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

<script type="text/javascript">
  document.getElementById("cliente").addEventListener("keyup", getCodigos)
  document.getElementById("cliente").addEventListener("click", getCodigos)
  document.getElementById("cliente").addEventListener("change", getCodigos2)

  function getCodigos() {

      let inputCP = document.getElementById("cliente").value
      let inputUN = document.getElementById("username").value
      let lista = document.getElementById("lista")
      document.getElementById('btnCrear').setAttribute("disabled","disabled")
      document.getElementById("codcli").value = ""

      if (inputCP.length > -1) {

          let url = "include/buscar_clientes.php"
          let formData = new FormData()
          formData.append("cliente", inputCP)
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

      let url = "include/buscar_cliente_codigo_nombre.php"
      let formData = new FormData()
      formData.append("cliente", cp)
      formData.append("username", document.getElementById("username").value)

      document.getElementById("clienteNombre").innerHTML = ""
      document.getElementById("clienteRif").innerHTML = ""
      document.getElementById("clienteTelefono").innerHTML = ""
      document.getElementById("clienteTarifa").innerHTML = ""
      document.getElementById("clienteDias").innerHTML = ""
      document.getElementById("asesorNombre").innerHTML = ""
      document.getElementById("asesorCiudad").innerHTML = ""
      document.getElementById("asesorTelefono").innerHTML = ""
      document.getElementById("asesorEmail").innerHTML = ""
      document.getElementById("clienteTipoCliente").innerHTML = ""

      fetch(url, {
          method: "POST",
          body: formData,
          mode: "cors" //Default cors, no-cors, same-origin
      }).then(response => response.json()) 
          .then(data => { 
              datos = data.split("|")
              document.getElementById('btnCrear').removeAttribute("disabled")
              document.getElementById("codcli").value = datos[0]
              document.getElementById("cliente").value = datos[1]
              document.getElementById("clienteNombre").innerHTML = datos[1]
              document.getElementById("clienteRif").innerHTML = datos[2]
              document.getElementById("clienteTarifa").innerHTML = datos[4]
              document.getElementById("clienteDias").innerHTML = datos[5] + " D&iacute;a(s)"
              document.getElementById("asesorNombre").innerHTML = datos[6]
              document.getElementById("asesorCiudad").innerHTML = datos[7]
              document.getElementById("asesorTelefono").innerHTML = datos[8]
              document.getElementById("asesorEmail").innerHTML = datos[9]
              document.getElementById("clienteTipoCliente").innerHTML = datos[10]
              document.getElementById("clienteTelefono").innerHTML = datos[3]
              // document.getElementById("idCliente").innerHTML = datos[11]
              document.getElementById("CodigoSicm").innerHTML = "<h6><span>" + datos[11] + "</span></h6>"
              document.getElementById("EstatusCliente").innerHTML = "<h6><span>" + datos[12] + "</span></h6>"
          })
        .catch(err => console.log(err))
  }

  function getCodigos2() {

      let inputCP = document.getElementById("cliente").value
      let lista = document.getElementById("lista")

      if (inputCP.length == 0) {

        document.getElementById('btnCrear').setAttribute("disabled","disabled")
        document.getElementById("codcli").value = ""
        lista.style.display = 'none'
      }
  }

  var codcli = document.getElementById("codcli").value
  if(codcli != "") {
    document.getElementById("cliente").disabled = true
    document.getElementById("btnCrear").disabled = false
  }
</script>
<?= GetDebugMessage() ?>
