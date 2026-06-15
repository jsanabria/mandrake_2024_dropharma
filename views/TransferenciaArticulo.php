<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaArticulo = &$Page;
?>
<?php
$Page->showMessage();
?>
<?php

namespace PHPMaker2024\mandrake;

// Page object
$TransferenciaArticulo = &$Page;
?>

<?php
$Page->showMessage();
?>

<div class="container">

    <h3>Transferencia de Artículos entre Almacenes</h3>

    <p class="text-muted">
        Busque el artículo por código, código de Barra, nombre comercial, principio activo o presentación.
    </p>

    <form id="frm" name="frm" method="post" action="TransferenciaArticuloListar" onsubmit="return validarBusqueda();">

        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12">

                <div class="input-group">
                    <input 
                        name="articulo" 
                        id="articulo"
                        type="text" 
                        class="form-control" 
                        placeholder="Buscar artículo..."
                        autocomplete="off"
                        autofocus
                    >

                    <span class="input-group-btn">
                        <button type="submit" id="Buscar" class="btn btn-primary">
                            Buscar
                        </button>
                    </span>
                </div>

            </div>
        </div>

    </form>

</div>

<script>
function validarBusqueda() {
    const articulo = document.getElementById("articulo").value.trim();

    if (articulo === "") {
        ew.alert("Debe indicar el código o descripción del artículo a buscar.");
        document.getElementById("articulo").focus();
        return false;
    }

    if (articulo.length < 2) {
        ew.alert("Debe escribir al menos 2 caracteres para realizar la búsqueda.");
        document.getElementById("articulo").focus();
        return false;
    }

    return true;
}
</script>

<?= GetDebugMessage() ?>
<?= GetDebugMessage() ?>
