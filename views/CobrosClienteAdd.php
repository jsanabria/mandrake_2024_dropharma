<?php

namespace PHPMaker2024\mandrake;

// Page object
$CobrosClienteAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { cobros_cliente: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fcobros_clienteadd;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fcobros_clienteadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["cliente", [fields.cliente.visible && fields.cliente.required ? ew.Validators.required(fields.cliente.caption) : null], fields.cliente.isInvalid],
            ["pivote", [fields.pivote.visible && fields.pivote.required ? ew.Validators.required(fields.pivote.caption) : null], fields.pivote.isInvalid],
            ["moneda", [fields.moneda.visible && fields.moneda.required ? ew.Validators.required(fields.moneda.caption) : null], fields.moneda.isInvalid],
            ["pago", [fields.pago.visible && fields.pago.required ? ew.Validators.required(fields.pago.caption) : null, ew.Validators.float], fields.pago.isInvalid],
            ["pivote2", [fields.pivote2.visible && fields.pivote2.required ? ew.Validators.required(fields.pivote2.caption) : null], fields.pivote2.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code in JAVASCRIPT here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "cliente": <?= $Page->cliente->toClientList($Page) ?>,
            "moneda": <?= $Page->moneda->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fcobros_clienteadd" id="fcobros_clienteadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="cobros_cliente">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->cliente->Visible) { // cliente ?>
    <div id="r_cliente"<?= $Page->cliente->rowAttributes() ?>>
        <label id="elh_cobros_cliente_cliente" for="x_cliente" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cliente->caption() ?><?= $Page->cliente->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cliente->cellAttributes() ?>>
<span id="el_cobros_cliente_cliente">
    <select
        id="x_cliente"
        name="x_cliente"
        class="form-control ew-select<?= $Page->cliente->isInvalidClass() ?>"
        data-select2-id="fcobros_clienteadd_x_cliente"
        data-table="cobros_cliente"
        data-field="x_cliente"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->cliente->caption())) ?>"
        data-modal-lookup="true"
        data-value-separator="<?= $Page->cliente->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cliente->getPlaceHolder()) ?>"
        <?= $Page->cliente->editAttributes() ?>>
        <?= $Page->cliente->selectOptionListHtml("x_cliente") ?>
    </select>
    <?= $Page->cliente->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cliente->getErrorMessage() ?></div>
<?= $Page->cliente->Lookup->getParamTag($Page, "p_x_cliente") ?>
<script>
loadjs.ready("fcobros_clienteadd", function() {
    var options = { name: "x_cliente", selectId: "fcobros_clienteadd_x_cliente" };
    if (fcobros_clienteadd.lists.cliente?.lookupOptions.length) {
        options.data = { id: "x_cliente", form: "fcobros_clienteadd" };
    } else {
        options.ajax = { id: "x_cliente", form: "fcobros_clienteadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.cobros_cliente.fields.cliente.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote->Visible) { // pivote ?>
    <div id="r_pivote"<?= $Page->pivote->rowAttributes() ?>>
        <label id="elh_cobros_cliente_pivote" for="x_pivote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote->caption() ?><?= $Page->pivote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pivote->cellAttributes() ?>>
<span id="el_cobros_cliente_pivote">
<input type="<?= $Page->pivote->getInputTextType() ?>" name="x_pivote" id="x_pivote" data-table="cobros_cliente" data-field="x_pivote" value="<?= $Page->pivote->EditValue ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pivote->formatPattern()) ?>"<?= $Page->pivote->editAttributes() ?> aria-describedby="x_pivote_help">
<?= $Page->pivote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->moneda->Visible) { // moneda ?>
    <div id="r_moneda"<?= $Page->moneda->rowAttributes() ?>>
        <label id="elh_cobros_cliente_moneda" for="x_moneda" class="<?= $Page->LeftColumnClass ?>"><?= $Page->moneda->caption() ?><?= $Page->moneda->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->moneda->cellAttributes() ?>>
<span id="el_cobros_cliente_moneda">
    <select
        id="x_moneda"
        name="x_moneda"
        class="form-select ew-select<?= $Page->moneda->isInvalidClass() ?>"
        <?php if (!$Page->moneda->IsNativeSelect) { ?>
        data-select2-id="fcobros_clienteadd_x_moneda"
        <?php } ?>
        data-table="cobros_cliente"
        data-field="x_moneda"
        data-value-separator="<?= $Page->moneda->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->moneda->getPlaceHolder()) ?>"
        <?= $Page->moneda->editAttributes() ?>>
        <?= $Page->moneda->selectOptionListHtml("x_moneda") ?>
    </select>
    <?= $Page->moneda->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->moneda->getErrorMessage() ?></div>
<?= $Page->moneda->Lookup->getParamTag($Page, "p_x_moneda") ?>
<?php if (!$Page->moneda->IsNativeSelect) { ?>
<script>
loadjs.ready("fcobros_clienteadd", function() {
    var options = { name: "x_moneda", selectId: "fcobros_clienteadd_x_moneda" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fcobros_clienteadd.lists.moneda?.lookupOptions.length) {
        options.data = { id: "x_moneda", form: "fcobros_clienteadd" };
    } else {
        options.ajax = { id: "x_moneda", form: "fcobros_clienteadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.cobros_cliente.fields.moneda.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pago->Visible) { // pago ?>
    <div id="r_pago"<?= $Page->pago->rowAttributes() ?>>
        <label id="elh_cobros_cliente_pago" for="x_pago" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pago->caption() ?><?= $Page->pago->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pago->cellAttributes() ?>>
<span id="el_cobros_cliente_pago">
<input type="<?= $Page->pago->getInputTextType() ?>" name="x_pago" id="x_pago" data-table="cobros_cliente" data-field="x_pago" value="<?= $Page->pago->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->pago->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pago->formatPattern()) ?>"<?= $Page->pago->editAttributes() ?> aria-describedby="x_pago_help">
<?= $Page->pago->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pago->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pivote2->Visible) { // pivote2 ?>
    <div id="r_pivote2"<?= $Page->pivote2->rowAttributes() ?>>
        <label id="elh_cobros_cliente_pivote2" for="x_pivote2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pivote2->caption() ?><?= $Page->pivote2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pivote2->cellAttributes() ?>>
<span id="el_cobros_cliente_pivote2">
<input type="<?= $Page->pivote2->getInputTextType() ?>" name="x_pivote2" id="x_pivote2" data-table="cobros_cliente" data-field="x_pivote2" value="<?= $Page->pivote2->EditValue ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->pivote2->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pivote2->formatPattern()) ?>"<?= $Page->pivote2->editAttributes() ?> aria-describedby="x_pivote2_help">
<?= $Page->pivote2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pivote2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("cobros_cliente_factura", explode(",", $Page->getCurrentDetailTable())) && $cobros_cliente_factura->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("cobros_cliente_factura", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "CobrosClienteFacturaGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fcobros_clienteadd"><?= $Language->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fcobros_clienteadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("cobros_cliente");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    $(document).ready(function() {
        // 1. Configuración Estética Inicial
        $("#x_pago").prop('readonly', true);
        $("#r_pivote, #r_moneda").hide();

        // 2. Función Maestra de Carga y Refresco
        window.cargarInterfazPagos = function(tipoMetodo = "") {
            // Recuperar el ID de compra de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const id_compra = urlParams.get('id_compra') || 0;
            const params = {
                id_compra: id_compra, // Esencial para que el PHP no pierda la factura
                cliente: $("#x_cliente").val(),
                nro_factura: $("#nro_fact_hidden").val(),
                monto_total: $("#monto_total_hidden").val(),
                tipo_pago: tipoMetodo,
                pagos: $("#pagos").val() || "[]" // Aquí viaja la lista acumulada
            };
            if (!params.cliente && id_compra == 0) return;
            $.ajax({
                url: "include/Cliente_Tipo_Pago.php",
                type: "POST",
                dataType: "json",
                data: params,
                success: function(res) {
                    $("#r_pivote2").html(res.html).show();
                    if(res.totales) {
                        // Actualizar inputs de PHPMaker
                        $("#x_pago").val(res.totales.total_usd.toFixed(2));
                        $("#abono").val(res.totales.abono_rc);
                        $("#abono2").val(res.totales.abono_rd);

                        // Bloquear botón si el saldo no es 0
                        const saldo = (parseFloat(res.monto_total) - res.totales.total_usd).toFixed(2);
                        $("#btn-action").prop('disabled', (saldo > 0.01));
                    }
                }
            });
        };

        // 3. LÓGICA DE CARGA AUTOMÁTICA AL INICIAR
        // Extraemos el ID de la compra de la URL (ej: ...?id_compra=123)
        const urlParams = new URLSearchParams(window.location.search);
        const id_url = urlParams.get('id_compra') || 0;
        if (id_url != 0) {
            $.ajax({
                url: "include/Cliente_Tipo_Pago.php",
                type: "GET",
                dataType: "json",
                data: { id_compra: id_url },
                success: function(res) {
                    if (res.status === "success") {
                        // Inyectamos los datos base y los inputs hidden necesarios
                        $("#r_cliente").html(`
                            <div class="card shadow-sm border-0 bg-light mb-3">
                                <div class="card-body py-2 px-3 d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3"><i class="bi bi-person-fill"></i></div>
                                    <div>
                                        <h6 class="text-muted small mb-0 fw-bold">CLIENTE</h6>
                                        <p class="mb-0 fw-bold text-dark">${res.cliente_nombre}</p>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="x_cliente" value="${res.cliente_id}">
                            <input type="hidden" id="nro_fact_hidden" value="${res.nro_factura}">
                            <input type="hidden" id="monto_total_hidden" value="${res.monto_total}">
                        `);

                        // Mostramos la tabla de pagos y el formulario
                        $("#r_pivote2").html(res.html).show();

                        // Sincronizamos el monto en el campo nativo de PHPMaker
                        $("#monto").val(res.monto_total);
                    }
                },
                error: function(e) {
                    console.error("Error en carga inicial", e);
                }
            });
        }

        // 4. Eventos Delegados
        $(document).on("change", "#tipo_pago", function() {
            cargarInterfazPagos($(this).val());
        });
        $(document).on("click", "#btn_agregar_pago", function(e) {
            e.preventDefault();

            // 1. Capturar valores de la interfaz
            const tipo    = $("#tipo_pago").val();
            const monto   = parseFloat($("#pagar_input").val());
            const moneda  = $("#moneda_input").val();
            const ref     = $("#referencia").val() || "N/A";

            // 2. Validaciones básicas
            if (!tipo) { alert("Seleccione un método de pago"); return; }
            if (isNaN(monto) || monto <= 0) { alert("Ingrese un monto válido"); return; }

            // 3. Obtener la lista actual del input oculto
            let lista_actual = [];
            try {
                const valor_input = $("#pagos").val();
                lista_actual = JSON.parse(valor_input || "[]");
            } catch (err) {
                lista_actual = [];
            }

            // 4. Agregar el nuevo pago al array
            lista_actual.push({
                tipo: tipo,
                ref: ref,
                monto: monto,
                moneda: moneda
            });

            // 5. Guardar el nuevo JSON en el input oculto
            $("#pagos").val(JSON.stringify(lista_actual));

            // 6. SOLICITAR AL SERVIDOR QUE RENDERICE LA LISTA NUEVA
            // Llamamos a la función que ya definimos antes
            if (typeof window.cargarInterfazPagos === "function") {
                window.cargarInterfazPagos(""); 
            }
        });
    });

    // Función global para borrar pagos
    window.EliminarPago = function(index) {
        let lista = JSON.parse($("#pagos").val() || "[]");
        lista.splice(index, 1);
        $("#pagos").val(JSON.stringify(lista));
        cargarInterfazPagos("");
    };
});
</script>
