<?php

namespace PHPMaker2024\mandrake;

// Page object
$ArticuloEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="farticuloedit" id="farticuloedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { articulo: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var farticuloedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("farticuloedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["foto", [fields.foto.visible && fields.foto.required ? ew.Validators.fileRequired(fields.foto.caption) : null], fields.foto.isInvalid],
            ["codigo", [fields.codigo.visible && fields.codigo.required ? ew.Validators.required(fields.codigo.caption) : null], fields.codigo.isInvalid],
            ["nombre_comercial", [fields.nombre_comercial.visible && fields.nombre_comercial.required ? ew.Validators.required(fields.nombre_comercial.caption) : null], fields.nombre_comercial.isInvalid],
            ["principio_activo", [fields.principio_activo.visible && fields.principio_activo.required ? ew.Validators.required(fields.principio_activo.caption) : null], fields.principio_activo.isInvalid],
            ["presentacion", [fields.presentacion.visible && fields.presentacion.required ? ew.Validators.required(fields.presentacion.caption) : null], fields.presentacion.isInvalid],
            ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
            ["codigo_de_barra", [fields.codigo_de_barra.visible && fields.codigo_de_barra.required ? ew.Validators.required(fields.codigo_de_barra.caption) : null], fields.codigo_de_barra.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["unidad_medida_defecto", [fields.unidad_medida_defecto.visible && fields.unidad_medida_defecto.required ? ew.Validators.required(fields.unidad_medida_defecto.caption) : null], fields.unidad_medida_defecto.isInvalid],
            ["cantidad_por_unidad_medida", [fields.cantidad_por_unidad_medida.visible && fields.cantidad_por_unidad_medida.required ? ew.Validators.required(fields.cantidad_por_unidad_medida.caption) : null], fields.cantidad_por_unidad_medida.isInvalid],
            ["cantidad_minima", [fields.cantidad_minima.visible && fields.cantidad_minima.required ? ew.Validators.required(fields.cantidad_minima.caption) : null, ew.Validators.float], fields.cantidad_minima.isInvalid],
            ["cantidad_maxima", [fields.cantidad_maxima.visible && fields.cantidad_maxima.required ? ew.Validators.required(fields.cantidad_maxima.caption) : null, ew.Validators.float], fields.cantidad_maxima.isInvalid],
            ["ultimo_costo", [fields.ultimo_costo.visible && fields.ultimo_costo.required ? ew.Validators.required(fields.ultimo_costo.caption) : null, ew.Validators.float], fields.ultimo_costo.isInvalid],
            ["descuento", [fields.descuento.visible && fields.descuento.required ? ew.Validators.required(fields.descuento.caption) : null, ew.Validators.float], fields.descuento.isInvalid],
            ["categoria", [fields.categoria.visible && fields.categoria.required ? ew.Validators.required(fields.categoria.caption) : null], fields.categoria.isInvalid],
            ["lista_pedido", [fields.lista_pedido.visible && fields.lista_pedido.required ? ew.Validators.required(fields.lista_pedido.caption) : null], fields.lista_pedido.isInvalid],
            ["categoria_madre", [fields.categoria_madre.visible && fields.categoria_madre.required ? ew.Validators.required(fields.categoria_madre.caption) : null], fields.categoria_madre.isInvalid],
            ["sub_categoria", [fields.sub_categoria.visible && fields.sub_categoria.required ? ew.Validators.required(fields.sub_categoria.caption) : null], fields.sub_categoria.isInvalid],
            ["alicuota", [fields.alicuota.visible && fields.alicuota.required ? ew.Validators.required(fields.alicuota.caption) : null], fields.alicuota.isInvalid],
            ["articulo_inventario", [fields.articulo_inventario.visible && fields.articulo_inventario.required ? ew.Validators.required(fields.articulo_inventario.caption) : null], fields.articulo_inventario.isInvalid],
            ["indexado", [fields.indexado.visible && fields.indexado.required ? ew.Validators.required(fields.indexado.caption) : null], fields.indexado.isInvalid],
            ["pies_cubico", [fields.pies_cubico.visible && fields.pies_cubico.required ? ew.Validators.required(fields.pies_cubico.caption) : null, ew.Validators.float], fields.pies_cubico.isInvalid],
            ["color", [fields.color.visible && fields.color.required ? ew.Validators.required(fields.color.caption) : null], fields.color.isInvalid],
            ["cojin", [fields.cojin.visible && fields.cojin.required ? ew.Validators.required(fields.cojin.caption) : null], fields.cojin.isInvalid],
            ["brazo", [fields.brazo.visible && fields.brazo.required ? ew.Validators.required(fields.brazo.caption) : null], fields.brazo.isInvalid],
            ["respaldo", [fields.respaldo.visible && fields.respaldo.required ? ew.Validators.required(fields.respaldo.caption) : null], fields.respaldo.isInvalid],
            ["talla", [fields.talla.visible && fields.talla.required ? ew.Validators.required(fields.talla.caption) : null], fields.talla.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
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

        // Multi-Page
        .setMultiPage(true)

        // Dynamic selection lists
        .setLists({
            "presentacion": <?= $Page->presentacion->toClientList($Page) ?>,
            "fabricante": <?= $Page->fabricante->toClientList($Page) ?>,
            "tipo": <?= $Page->tipo->toClientList($Page) ?>,
            "unidad_medida_defecto": <?= $Page->unidad_medida_defecto->toClientList($Page) ?>,
            "cantidad_por_unidad_medida": <?= $Page->cantidad_por_unidad_medida->toClientList($Page) ?>,
            "categoria": <?= $Page->categoria->toClientList($Page) ?>,
            "lista_pedido": <?= $Page->lista_pedido->toClientList($Page) ?>,
            "categoria_madre": <?= $Page->categoria_madre->toClientList($Page) ?>,
            "sub_categoria": <?= $Page->sub_categoria->toClientList($Page) ?>,
            "alicuota": <?= $Page->alicuota->toClientList($Page) ?>,
            "articulo_inventario": <?= $Page->articulo_inventario->toClientList($Page) ?>,
            "indexado": <?= $Page->indexado->toClientList($Page) ?>,
            "activo": <?= $Page->activo->toClientList($Page) ?>,
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
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="articulo">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-multi-page"><!-- multi-page -->
<div class="ew-nav<?= $Page->MultiPages->containerClasses() ?>" id="pages_ArticuloEdit"><!-- multi-page tabs -->
    <ul class="<?= $Page->MultiPages->navClasses() ?>" role="tablist">
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(1) ?>" data-bs-target="#tab_articulo1" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_articulo1" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(1)) ?>"><?= $Page->pageCaption(1) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(2) ?>" data-bs-target="#tab_articulo2" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_articulo2" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(2)) ?>"><?= $Page->pageCaption(2) ?></button></li>
        <li class="nav-item"><button class="<?= $Page->MultiPages->navLinkClasses(3) ?>" data-bs-target="#tab_articulo3" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_articulo3" aria-selected="<?= JsonEncode($Page->MultiPages->isActive(3)) ?>"><?= $Page->pageCaption(3) ?></button></li>
    </ul>
    <div class="<?= $Page->MultiPages->tabContentClasses() ?>"><!-- multi-page tabs .tab-content -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(1) ?>" id="tab_articulo1" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codigo->Visible) { // codigo ?>
    <div id="r_codigo"<?= $Page->codigo->rowAttributes() ?>>
        <label id="elh_articulo_codigo" for="x_codigo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo->caption() ?><?= $Page->codigo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo->cellAttributes() ?>>
<span id="el_articulo_codigo">
<input type="<?= $Page->codigo->getInputTextType() ?>" name="x_codigo" id="x_codigo" data-table="articulo" data-field="x_codigo" value="<?= $Page->codigo->EditValue ?>" data-page="1" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo->formatPattern()) ?>"<?= $Page->codigo->editAttributes() ?> aria-describedby="x_codigo_help">
<?= $Page->codigo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre_comercial->Visible) { // nombre_comercial ?>
    <div id="r_nombre_comercial"<?= $Page->nombre_comercial->rowAttributes() ?>>
        <label id="elh_articulo_nombre_comercial" for="x_nombre_comercial" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre_comercial->caption() ?><?= $Page->nombre_comercial->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre_comercial->cellAttributes() ?>>
<span id="el_articulo_nombre_comercial">
<input type="<?= $Page->nombre_comercial->getInputTextType() ?>" name="x_nombre_comercial" id="x_nombre_comercial" data-table="articulo" data-field="x_nombre_comercial" value="<?= $Page->nombre_comercial->EditValue ?>" data-page="1" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->nombre_comercial->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->nombre_comercial->formatPattern()) ?>"<?= $Page->nombre_comercial->editAttributes() ?> aria-describedby="x_nombre_comercial_help">
<?= $Page->nombre_comercial->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre_comercial->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->principio_activo->Visible) { // principio_activo ?>
    <div id="r_principio_activo"<?= $Page->principio_activo->rowAttributes() ?>>
        <label id="elh_articulo_principio_activo" for="x_principio_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->principio_activo->caption() ?><?= $Page->principio_activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->principio_activo->cellAttributes() ?>>
<span id="el_articulo_principio_activo">
<input type="<?= $Page->principio_activo->getInputTextType() ?>" name="x_principio_activo" id="x_principio_activo" data-table="articulo" data-field="x_principio_activo" value="<?= $Page->principio_activo->EditValue ?>" data-page="1" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->principio_activo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->principio_activo->formatPattern()) ?>"<?= $Page->principio_activo->editAttributes() ?> aria-describedby="x_principio_activo_help">
<?= $Page->principio_activo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->principio_activo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->presentacion->Visible) { // presentacion ?>
    <div id="r_presentacion"<?= $Page->presentacion->rowAttributes() ?>>
        <label id="elh_articulo_presentacion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->presentacion->caption() ?><?= $Page->presentacion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->presentacion->cellAttributes() ?>>
<span id="el_articulo_presentacion">
<?php
if (IsRTL()) {
    $Page->presentacion->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_presentacion" class="ew-auto-suggest">
    <input type="<?= $Page->presentacion->getInputTextType() ?>" class="form-control" name="sv_x_presentacion" id="sv_x_presentacion" value="<?= RemoveHtml($Page->presentacion->EditValue) ?>" autocomplete="off" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->presentacion->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->presentacion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->presentacion->formatPattern()) ?>"<?= $Page->presentacion->editAttributes() ?> aria-describedby="x_presentacion_help">
</span>
<selection-list hidden class="form-control" data-table="articulo" data-field="x_presentacion" data-input="sv_x_presentacion" data-page="1" data-value-separator="<?= $Page->presentacion->displayValueSeparatorAttribute() ?>" name="x_presentacion" id="x_presentacion" value="<?= HtmlEncode($Page->presentacion->CurrentValue) ?>"></selection-list>
<?= $Page->presentacion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->presentacion->getErrorMessage() ?></div>
<script>
loadjs.ready("farticuloedit", function() {
    farticuloedit.createAutoSuggest(Object.assign({"id":"x_presentacion","forceSelect":false}, { lookupAllDisplayFields: <?= $Page->presentacion->Lookup->LookupAllDisplayFields ? "true" : "false" ?> }, ew.vars.tables.articulo.fields.presentacion.autoSuggestOptions));
});
</script>
<?= $Page->presentacion->Lookup->getParamTag($Page, "p_x_presentacion") ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante"<?= $Page->fabricante->rowAttributes() ?>>
        <label id="elh_articulo_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->fabricante->cellAttributes() ?>>
<span id="el_articulo_fabricante">
    <select
        id="x_fabricante"
        name="x_fabricante"
        class="form-control ew-select<?= $Page->fabricante->isInvalidClass() ?>"
        data-select2-id="farticuloedit_x_fabricante"
        data-table="articulo"
        data-field="x_fabricante"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->fabricante->caption())) ?>"
        data-modal-lookup="true"
        data-page="1"
        data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->fabricante->getPlaceHolder()) ?>"
        <?= $Page->fabricante->editAttributes() ?>>
        <?= $Page->fabricante->selectOptionListHtml("x_fabricante") ?>
    </select>
    <?= $Page->fabricante->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_fabricante", selectId: "farticuloedit_x_fabricante" };
    if (farticuloedit.lists.fabricante?.lookupOptions.length) {
        options.data = { id: "x_fabricante", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_fabricante", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.articulo.fields.fabricante.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->codigo_de_barra->Visible) { // codigo_de_barra ?>
    <div id="r_codigo_de_barra"<?= $Page->codigo_de_barra->rowAttributes() ?>>
        <label id="elh_articulo_codigo_de_barra" for="x_codigo_de_barra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codigo_de_barra->caption() ?><?= $Page->codigo_de_barra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codigo_de_barra->cellAttributes() ?>>
<span id="el_articulo_codigo_de_barra">
<input type="<?= $Page->codigo_de_barra->getInputTextType() ?>" name="x_codigo_de_barra" id="x_codigo_de_barra" data-table="articulo" data-field="x_codigo_de_barra" value="<?= $Page->codigo_de_barra->EditValue ?>" data-page="1" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->codigo_de_barra->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->codigo_de_barra->formatPattern()) ?>"<?= $Page->codigo_de_barra->editAttributes() ?> aria-describedby="x_codigo_de_barra_help">
<?= $Page->codigo_de_barra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->codigo_de_barra->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo_inventario->Visible) { // articulo_inventario ?>
    <div id="r_articulo_inventario"<?= $Page->articulo_inventario->rowAttributes() ?>>
        <label id="elh_articulo_articulo_inventario" for="x_articulo_inventario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo_inventario->caption() ?><?= $Page->articulo_inventario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->articulo_inventario->cellAttributes() ?>>
<span id="el_articulo_articulo_inventario">
    <select
        id="x_articulo_inventario"
        name="x_articulo_inventario"
        class="form-select ew-select<?= $Page->articulo_inventario->isInvalidClass() ?>"
        <?php if (!$Page->articulo_inventario->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_articulo_inventario"
        <?php } ?>
        data-table="articulo"
        data-field="x_articulo_inventario"
        data-page="1"
        data-value-separator="<?= $Page->articulo_inventario->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->articulo_inventario->getPlaceHolder()) ?>"
        <?= $Page->articulo_inventario->editAttributes() ?>>
        <?= $Page->articulo_inventario->selectOptionListHtml("x_articulo_inventario") ?>
    </select>
    <?= $Page->articulo_inventario->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->articulo_inventario->getErrorMessage() ?></div>
<?php if (!$Page->articulo_inventario->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_articulo_inventario", selectId: "farticuloedit_x_articulo_inventario" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.articulo_inventario?.lookupOptions.length) {
        options.data = { id: "x_articulo_inventario", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_articulo_inventario", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.articulo_inventario.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_articulo_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_articulo_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_activo"
        <?php } ?>
        data-table="articulo"
        data-field="x_activo"
        data-page="1"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<?php if (!$Page->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_activo", selectId: "farticuloedit_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_activo", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(2) ?>" id="tab_articulo2" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->cantidad_minima->Visible) { // cantidad_minima ?>
    <div id="r_cantidad_minima"<?= $Page->cantidad_minima->rowAttributes() ?>>
        <label id="elh_articulo_cantidad_minima" for="x_cantidad_minima" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_minima->caption() ?><?= $Page->cantidad_minima->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_minima->cellAttributes() ?>>
<span id="el_articulo_cantidad_minima">
<input type="<?= $Page->cantidad_minima->getInputTextType() ?>" name="x_cantidad_minima" id="x_cantidad_minima" data-table="articulo" data-field="x_cantidad_minima" value="<?= $Page->cantidad_minima->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->cantidad_minima->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_minima->formatPattern()) ?>"<?= $Page->cantidad_minima->editAttributes() ?> aria-describedby="x_cantidad_minima_help">
<?= $Page->cantidad_minima->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_minima->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_maxima->Visible) { // cantidad_maxima ?>
    <div id="r_cantidad_maxima"<?= $Page->cantidad_maxima->rowAttributes() ?>>
        <label id="elh_articulo_cantidad_maxima" for="x_cantidad_maxima" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_maxima->caption() ?><?= $Page->cantidad_maxima->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_maxima->cellAttributes() ?>>
<span id="el_articulo_cantidad_maxima">
<input type="<?= $Page->cantidad_maxima->getInputTextType() ?>" name="x_cantidad_maxima" id="x_cantidad_maxima" data-table="articulo" data-field="x_cantidad_maxima" value="<?= $Page->cantidad_maxima->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->cantidad_maxima->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cantidad_maxima->formatPattern()) ?>"<?= $Page->cantidad_maxima->editAttributes() ?> aria-describedby="x_cantidad_maxima_help">
<?= $Page->cantidad_maxima->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cantidad_maxima->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ultimo_costo->Visible) { // ultimo_costo ?>
    <div id="r_ultimo_costo"<?= $Page->ultimo_costo->rowAttributes() ?>>
        <label id="elh_articulo_ultimo_costo" for="x_ultimo_costo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ultimo_costo->caption() ?><?= $Page->ultimo_costo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ultimo_costo->cellAttributes() ?>>
<span id="el_articulo_ultimo_costo">
<input type="<?= $Page->ultimo_costo->getInputTextType() ?>" name="x_ultimo_costo" id="x_ultimo_costo" data-table="articulo" data-field="x_ultimo_costo" value="<?= $Page->ultimo_costo->EditValue ?>" data-page="2" size="30" placeholder="<?= HtmlEncode($Page->ultimo_costo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->ultimo_costo->formatPattern()) ?>"<?= $Page->ultimo_costo->editAttributes() ?> aria-describedby="x_ultimo_costo_help">
<?= $Page->ultimo_costo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ultimo_costo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descuento->Visible) { // descuento ?>
    <div id="r_descuento"<?= $Page->descuento->rowAttributes() ?>>
        <label id="elh_articulo_descuento" for="x_descuento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descuento->caption() ?><?= $Page->descuento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descuento->cellAttributes() ?>>
<span id="el_articulo_descuento">
<input type="<?= $Page->descuento->getInputTextType() ?>" name="x_descuento" id="x_descuento" data-table="articulo" data-field="x_descuento" value="<?= $Page->descuento->EditValue ?>" data-page="2" size="6" placeholder="<?= HtmlEncode($Page->descuento->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descuento->formatPattern()) ?>"<?= $Page->descuento->editAttributes() ?> aria-describedby="x_descuento_help">
<?= $Page->descuento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descuento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alicuota->Visible) { // alicuota ?>
    <div id="r_alicuota"<?= $Page->alicuota->rowAttributes() ?>>
        <label id="elh_articulo_alicuota" for="x_alicuota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alicuota->caption() ?><?= $Page->alicuota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->alicuota->cellAttributes() ?>>
<span id="el_articulo_alicuota">
    <select
        id="x_alicuota"
        name="x_alicuota"
        class="form-select ew-select<?= $Page->alicuota->isInvalidClass() ?>"
        <?php if (!$Page->alicuota->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_alicuota"
        <?php } ?>
        data-table="articulo"
        data-field="x_alicuota"
        data-page="2"
        data-value-separator="<?= $Page->alicuota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->alicuota->getPlaceHolder()) ?>"
        <?= $Page->alicuota->editAttributes() ?>>
        <?= $Page->alicuota->selectOptionListHtml("x_alicuota") ?>
    </select>
    <?= $Page->alicuota->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->alicuota->getErrorMessage() ?></div>
<?= $Page->alicuota->Lookup->getParamTag($Page, "p_x_alicuota") ?>
<?php if (!$Page->alicuota->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_alicuota", selectId: "farticuloedit_x_alicuota" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.alicuota?.lookupOptions.length) {
        options.data = { id: "x_alicuota", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_alicuota", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.alicuota.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
        <div class="<?= $Page->MultiPages->tabPaneClasses(3) ?>" id="tab_articulo3" role="tabpanel"><!-- multi-page .tab-pane -->
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->foto->Visible) { // foto ?>
    <div id="r_foto"<?= $Page->foto->rowAttributes() ?>>
        <label id="elh_articulo_foto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->foto->caption() ?><?= $Page->foto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->foto->cellAttributes() ?>>
<span id="el_articulo_foto">
<div id="fd_x_foto" class="fileinput-button ew-file-drop-zone">
    <input
        type="file"
        id="x_foto"
        name="x_foto"
        class="form-control ew-file-input"
        title="<?= $Page->foto->title() ?>"
        lang="<?= CurrentLanguageID() ?>"
        data-table="articulo"
        data-field="x_foto"
        data-size="250"
        data-accept-file-types="<?= $Page->foto->acceptFileTypes() ?>"
        data-max-file-size="<?= $Page->foto->UploadMaxFileSize ?>"
        data-max-number-of-files="null"
        data-disable-image-crop="<?= $Page->foto->ImageCropper ? 0 : 1 ?>"
        data-page="3"
        aria-describedby="x_foto_help"
        <?= ($Page->foto->ReadOnly || $Page->foto->Disabled) ? " disabled" : "" ?>
        <?= $Page->foto->editAttributes() ?>
    >
    <div class="text-body-secondary ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
    <?= $Page->foto->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->foto->getErrorMessage() ?></div>
</div>
<input type="hidden" name="fn_x_foto" id= "fn_x_foto" value="<?= $Page->foto->Upload->FileName ?>">
<input type="hidden" name="fa_x_foto" id= "fa_x_foto" value="<?= (Post("fa_x_foto") == "0") ? "0" : "1" ?>">
<table id="ft_x_foto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_articulo_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_articulo_tipo">
    <select
        id="x_tipo"
        name="x_tipo"
        class="form-select ew-select<?= $Page->tipo->isInvalidClass() ?>"
        <?php if (!$Page->tipo->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_tipo"
        <?php } ?>
        data-table="articulo"
        data-field="x_tipo"
        data-page="3"
        data-value-separator="<?= $Page->tipo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo->getPlaceHolder()) ?>"
        <?= $Page->tipo->editAttributes() ?>>
        <?= $Page->tipo->selectOptionListHtml("x_tipo") ?>
    </select>
    <?= $Page->tipo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo->getErrorMessage() ?></div>
<?php if (!$Page->tipo->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_tipo", selectId: "farticuloedit_x_tipo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.tipo?.lookupOptions.length) {
        options.data = { id: "x_tipo", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_tipo", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.tipo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->unidad_medida_defecto->Visible) { // unidad_medida_defecto ?>
    <div id="r_unidad_medida_defecto"<?= $Page->unidad_medida_defecto->rowAttributes() ?>>
        <label id="elh_articulo_unidad_medida_defecto" for="x_unidad_medida_defecto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->unidad_medida_defecto->caption() ?><?= $Page->unidad_medida_defecto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->unidad_medida_defecto->cellAttributes() ?>>
<span id="el_articulo_unidad_medida_defecto">
    <select
        id="x_unidad_medida_defecto"
        name="x_unidad_medida_defecto"
        class="form-select ew-select<?= $Page->unidad_medida_defecto->isInvalidClass() ?>"
        <?php if (!$Page->unidad_medida_defecto->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_unidad_medida_defecto"
        <?php } ?>
        data-table="articulo"
        data-field="x_unidad_medida_defecto"
        data-page="3"
        data-value-separator="<?= $Page->unidad_medida_defecto->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->unidad_medida_defecto->getPlaceHolder()) ?>"
        data-ew-action="update-options"
        <?= $Page->unidad_medida_defecto->editAttributes() ?>>
        <?= $Page->unidad_medida_defecto->selectOptionListHtml("x_unidad_medida_defecto") ?>
    </select>
    <?= $Page->unidad_medida_defecto->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->unidad_medida_defecto->getErrorMessage() ?></div>
<?= $Page->unidad_medida_defecto->Lookup->getParamTag($Page, "p_x_unidad_medida_defecto") ?>
<?php if (!$Page->unidad_medida_defecto->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_unidad_medida_defecto", selectId: "farticuloedit_x_unidad_medida_defecto" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.unidad_medida_defecto?.lookupOptions.length) {
        options.data = { id: "x_unidad_medida_defecto", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_unidad_medida_defecto", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.unidad_medida_defecto.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cantidad_por_unidad_medida->Visible) { // cantidad_por_unidad_medida ?>
    <div id="r_cantidad_por_unidad_medida"<?= $Page->cantidad_por_unidad_medida->rowAttributes() ?>>
        <label id="elh_articulo_cantidad_por_unidad_medida" for="x_cantidad_por_unidad_medida" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cantidad_por_unidad_medida->caption() ?><?= $Page->cantidad_por_unidad_medida->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cantidad_por_unidad_medida->cellAttributes() ?>>
<span id="el_articulo_cantidad_por_unidad_medida">
    <select
        id="x_cantidad_por_unidad_medida"
        name="x_cantidad_por_unidad_medida"
        class="form-select ew-select<?= $Page->cantidad_por_unidad_medida->isInvalidClass() ?>"
        <?php if (!$Page->cantidad_por_unidad_medida->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_cantidad_por_unidad_medida"
        <?php } ?>
        data-table="articulo"
        data-field="x_cantidad_por_unidad_medida"
        data-page="3"
        data-value-separator="<?= $Page->cantidad_por_unidad_medida->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->cantidad_por_unidad_medida->getPlaceHolder()) ?>"
        <?= $Page->cantidad_por_unidad_medida->editAttributes() ?>>
        <?= $Page->cantidad_por_unidad_medida->selectOptionListHtml("x_cantidad_por_unidad_medida") ?>
    </select>
    <?= $Page->cantidad_por_unidad_medida->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->cantidad_por_unidad_medida->getErrorMessage() ?></div>
<?= $Page->cantidad_por_unidad_medida->Lookup->getParamTag($Page, "p_x_cantidad_por_unidad_medida") ?>
<?php if (!$Page->cantidad_por_unidad_medida->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_cantidad_por_unidad_medida", selectId: "farticuloedit_x_cantidad_por_unidad_medida" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.cantidad_por_unidad_medida?.lookupOptions.length) {
        options.data = { id: "x_cantidad_por_unidad_medida", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_cantidad_por_unidad_medida", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.cantidad_por_unidad_medida.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->categoria->Visible) { // categoria ?>
    <div id="r_categoria"<?= $Page->categoria->rowAttributes() ?>>
        <label id="elh_articulo_categoria" for="x_categoria" class="<?= $Page->LeftColumnClass ?>"><?= $Page->categoria->caption() ?><?= $Page->categoria->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->categoria->cellAttributes() ?>>
<span id="el_articulo_categoria">
    <select
        id="x_categoria"
        name="x_categoria"
        class="form-select ew-select<?= $Page->categoria->isInvalidClass() ?>"
        <?php if (!$Page->categoria->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_categoria"
        <?php } ?>
        data-table="articulo"
        data-field="x_categoria"
        data-page="3"
        data-value-separator="<?= $Page->categoria->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->categoria->getPlaceHolder()) ?>"
        <?= $Page->categoria->editAttributes() ?>>
        <?= $Page->categoria->selectOptionListHtml("x_categoria") ?>
    </select>
    <?= $Page->categoria->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->categoria->getErrorMessage() ?></div>
<?= $Page->categoria->Lookup->getParamTag($Page, "p_x_categoria") ?>
<?php if (!$Page->categoria->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_categoria", selectId: "farticuloedit_x_categoria" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.categoria?.lookupOptions.length) {
        options.data = { id: "x_categoria", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_categoria", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.categoria.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lista_pedido->Visible) { // lista_pedido ?>
    <div id="r_lista_pedido"<?= $Page->lista_pedido->rowAttributes() ?>>
        <label id="elh_articulo_lista_pedido" for="x_lista_pedido" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lista_pedido->caption() ?><?= $Page->lista_pedido->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->lista_pedido->cellAttributes() ?>>
<span id="el_articulo_lista_pedido">
    <select
        id="x_lista_pedido"
        name="x_lista_pedido"
        class="form-select ew-select<?= $Page->lista_pedido->isInvalidClass() ?>"
        <?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_lista_pedido"
        <?php } ?>
        data-table="articulo"
        data-field="x_lista_pedido"
        data-page="3"
        data-value-separator="<?= $Page->lista_pedido->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->lista_pedido->getPlaceHolder()) ?>"
        <?= $Page->lista_pedido->editAttributes() ?>>
        <?= $Page->lista_pedido->selectOptionListHtml("x_lista_pedido") ?>
    </select>
    <?= $Page->lista_pedido->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->lista_pedido->getErrorMessage() ?></div>
<?= $Page->lista_pedido->Lookup->getParamTag($Page, "p_x_lista_pedido") ?>
<?php if (!$Page->lista_pedido->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_lista_pedido", selectId: "farticuloedit_x_lista_pedido" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.lista_pedido?.lookupOptions.length) {
        options.data = { id: "x_lista_pedido", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_lista_pedido", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.lista_pedido.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->categoria_madre->Visible) { // categoria_madre ?>
    <div id="r_categoria_madre"<?= $Page->categoria_madre->rowAttributes() ?>>
        <label id="elh_articulo_categoria_madre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->categoria_madre->caption() ?><?= $Page->categoria_madre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->categoria_madre->cellAttributes() ?>>
<span id="el_articulo_categoria_madre">
    <select
        id="x_categoria_madre"
        name="x_categoria_madre"
        class="form-control ew-select<?= $Page->categoria_madre->isInvalidClass() ?>"
        data-select2-id="farticuloedit_x_categoria_madre"
        data-table="articulo"
        data-field="x_categoria_madre"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->categoria_madre->caption())) ?>"
        data-modal-lookup="true"
        data-page="3"
        data-value-separator="<?= $Page->categoria_madre->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->categoria_madre->getPlaceHolder()) ?>"
        <?= $Page->categoria_madre->editAttributes() ?>>
        <?= $Page->categoria_madre->selectOptionListHtml("x_categoria_madre") ?>
    </select>
    <?= $Page->categoria_madre->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->categoria_madre->getErrorMessage() ?></div>
<?= $Page->categoria_madre->Lookup->getParamTag($Page, "p_x_categoria_madre") ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_categoria_madre", selectId: "farticuloedit_x_categoria_madre" };
    if (farticuloedit.lists.categoria_madre?.lookupOptions.length) {
        options.data = { id: "x_categoria_madre", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_categoria_madre", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.articulo.fields.categoria_madre.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sub_categoria->Visible) { // sub_categoria ?>
    <div id="r_sub_categoria"<?= $Page->sub_categoria->rowAttributes() ?>>
        <label id="elh_articulo_sub_categoria" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sub_categoria->caption() ?><?= $Page->sub_categoria->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sub_categoria->cellAttributes() ?>>
<span id="el_articulo_sub_categoria">
    <select
        id="x_sub_categoria"
        name="x_sub_categoria"
        class="form-control ew-select<?= $Page->sub_categoria->isInvalidClass() ?>"
        data-select2-id="farticuloedit_x_sub_categoria"
        data-table="articulo"
        data-field="x_sub_categoria"
        data-caption="<?= HtmlEncode(RemoveHtml($Page->sub_categoria->caption())) ?>"
        data-modal-lookup="true"
        data-page="3"
        data-value-separator="<?= $Page->sub_categoria->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->sub_categoria->getPlaceHolder()) ?>"
        <?= $Page->sub_categoria->editAttributes() ?>>
        <?= $Page->sub_categoria->selectOptionListHtml("x_sub_categoria") ?>
    </select>
    <?= $Page->sub_categoria->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->sub_categoria->getErrorMessage() ?></div>
<?= $Page->sub_categoria->Lookup->getParamTag($Page, "p_x_sub_categoria") ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_sub_categoria", selectId: "farticuloedit_x_sub_categoria" };
    if (farticuloedit.lists.sub_categoria?.lookupOptions.length) {
        options.data = { id: "x_sub_categoria", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_sub_categoria", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options = Object.assign({}, ew.modalLookupOptions, options, ew.vars.tables.articulo.fields.sub_categoria.modalLookupOptions);
    ew.createModalLookup(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->indexado->Visible) { // indexado ?>
    <div id="r_indexado"<?= $Page->indexado->rowAttributes() ?>>
        <label id="elh_articulo_indexado" for="x_indexado" class="<?= $Page->LeftColumnClass ?>"><?= $Page->indexado->caption() ?><?= $Page->indexado->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->indexado->cellAttributes() ?>>
<span id="el_articulo_indexado">
    <select
        id="x_indexado"
        name="x_indexado"
        class="form-select ew-select<?= $Page->indexado->isInvalidClass() ?>"
        <?php if (!$Page->indexado->IsNativeSelect) { ?>
        data-select2-id="farticuloedit_x_indexado"
        <?php } ?>
        data-table="articulo"
        data-field="x_indexado"
        data-page="3"
        data-value-separator="<?= $Page->indexado->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->indexado->getPlaceHolder()) ?>"
        <?= $Page->indexado->editAttributes() ?>>
        <?= $Page->indexado->selectOptionListHtml("x_indexado") ?>
    </select>
    <?= $Page->indexado->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->indexado->getErrorMessage() ?></div>
<?php if (!$Page->indexado->IsNativeSelect) { ?>
<script>
loadjs.ready("farticuloedit", function() {
    var options = { name: "x_indexado", selectId: "farticuloedit_x_indexado" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (farticuloedit.lists.indexado?.lookupOptions.length) {
        options.data = { id: "x_indexado", form: "farticuloedit" };
    } else {
        options.ajax = { id: "x_indexado", form: "farticuloedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.articulo.fields.indexado.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pies_cubico->Visible) { // pies_cubico ?>
    <div id="r_pies_cubico"<?= $Page->pies_cubico->rowAttributes() ?>>
        <label id="elh_articulo_pies_cubico" for="x_pies_cubico" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pies_cubico->caption() ?><?= $Page->pies_cubico->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pies_cubico->cellAttributes() ?>>
<span id="el_articulo_pies_cubico">
<input type="<?= $Page->pies_cubico->getInputTextType() ?>" name="x_pies_cubico" id="x_pies_cubico" data-table="articulo" data-field="x_pies_cubico" value="<?= $Page->pies_cubico->EditValue ?>" data-page="3" size="30" placeholder="<?= HtmlEncode($Page->pies_cubico->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->pies_cubico->formatPattern()) ?>"<?= $Page->pies_cubico->editAttributes() ?> aria-describedby="x_pies_cubico_help">
<?= $Page->pies_cubico->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pies_cubico->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->color->Visible) { // color ?>
    <div id="r_color"<?= $Page->color->rowAttributes() ?>>
        <label id="elh_articulo_color" for="x_color" class="<?= $Page->LeftColumnClass ?>"><?= $Page->color->caption() ?><?= $Page->color->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->color->cellAttributes() ?>>
<span id="el_articulo_color">
<input type="<?= $Page->color->getInputTextType() ?>" name="x_color" id="x_color" data-table="articulo" data-field="x_color" value="<?= $Page->color->EditValue ?>" data-page="3" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->color->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->color->formatPattern()) ?>"<?= $Page->color->editAttributes() ?> aria-describedby="x_color_help">
<?= $Page->color->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->color->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->cojin->Visible) { // cojin ?>
    <div id="r_cojin"<?= $Page->cojin->rowAttributes() ?>>
        <label id="elh_articulo_cojin" for="x_cojin" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cojin->caption() ?><?= $Page->cojin->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->cojin->cellAttributes() ?>>
<span id="el_articulo_cojin">
<input type="<?= $Page->cojin->getInputTextType() ?>" name="x_cojin" id="x_cojin" data-table="articulo" data-field="x_cojin" value="<?= $Page->cojin->EditValue ?>" data-page="3" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->cojin->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->cojin->formatPattern()) ?>"<?= $Page->cojin->editAttributes() ?> aria-describedby="x_cojin_help">
<?= $Page->cojin->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cojin->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->brazo->Visible) { // brazo ?>
    <div id="r_brazo"<?= $Page->brazo->rowAttributes() ?>>
        <label id="elh_articulo_brazo" for="x_brazo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->brazo->caption() ?><?= $Page->brazo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->brazo->cellAttributes() ?>>
<span id="el_articulo_brazo">
<input type="<?= $Page->brazo->getInputTextType() ?>" name="x_brazo" id="x_brazo" data-table="articulo" data-field="x_brazo" value="<?= $Page->brazo->EditValue ?>" data-page="3" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->brazo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->brazo->formatPattern()) ?>"<?= $Page->brazo->editAttributes() ?> aria-describedby="x_brazo_help">
<?= $Page->brazo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->brazo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->respaldo->Visible) { // respaldo ?>
    <div id="r_respaldo"<?= $Page->respaldo->rowAttributes() ?>>
        <label id="elh_articulo_respaldo" for="x_respaldo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->respaldo->caption() ?><?= $Page->respaldo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->respaldo->cellAttributes() ?>>
<span id="el_articulo_respaldo">
<input type="<?= $Page->respaldo->getInputTextType() ?>" name="x_respaldo" id="x_respaldo" data-table="articulo" data-field="x_respaldo" value="<?= $Page->respaldo->EditValue ?>" data-page="3" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->respaldo->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->respaldo->formatPattern()) ?>"<?= $Page->respaldo->editAttributes() ?> aria-describedby="x_respaldo_help">
<?= $Page->respaldo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->respaldo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->talla->Visible) { // talla ?>
    <div id="r_talla"<?= $Page->talla->rowAttributes() ?>>
        <label id="elh_articulo_talla" for="x_talla" class="<?= $Page->LeftColumnClass ?>"><?= $Page->talla->caption() ?><?= $Page->talla->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->talla->cellAttributes() ?>>
<span id="el_articulo_talla">
<input type="<?= $Page->talla->getInputTextType() ?>" name="x_talla" id="x_talla" data-table="articulo" data-field="x_talla" value="<?= $Page->talla->EditValue ?>" data-page="3" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->talla->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->talla->formatPattern()) ?>"<?= $Page->talla->editAttributes() ?> aria-describedby="x_talla_help">
<?= $Page->talla->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->talla->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
        </div><!-- /multi-page .tab-pane -->
    </div><!-- /multi-page tabs .tab-content -->
</div><!-- /multi-page tabs -->
</div><!-- /multi-page -->
    <input type="hidden" data-table="articulo" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?php
    if (in_array("articulo_unidad_medida", explode(",", $Page->getCurrentDetailTable())) && $articulo_unidad_medida->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("articulo_unidad_medida", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "ArticuloUnidadMedidaGrid.php" ?>
<?php } ?>
<?php
    if (in_array("adjunto", explode(",", $Page->getCurrentDetailTable())) && $adjunto->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("adjunto", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "AdjuntoGrid.php" ?>
<?php } ?>
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="farticuloedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="farticuloedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here
    // document.write("page loaded");
    $("#x_codigo").change(function(){
    	if($("#x_codigo").val().trim() == '') {
    		return true;
    	}
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"codigo": $("#x_codigo").val(),
    		"accion": 'U'
    	};
    	var url = "codigo_buscar.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = $(response).find('#outtext').text();
    			if(content == "1") {
    				alert("Código \"" + $("#x_codigo").val() + "\" ya existe.");
    				$("#x_codigo").val("");
    				$("#x_codigo").focus();
    				return false;
    			}
    			else {
    				return true;
    			}
    		}
    	});	
    });
    $("#x_codigo_proveedor").change(function(){
    	validad_codigo_proveedor();
    });
    $("#x_laboratorio").change(function(){
    	validad_codigo_proveedor();
    });

    function validad_codigo_proveedor() {
    	if($("#x_codigo_proveedor").val().trim() == '') {
    		return true;
    	}
    	var parametros = { //cada parámetro se pasa con un nombre en un array asociativo
    		"codigo": $("#x_codigo_proveedor").val(),
    		"laboratorio": $("#x_laboratorio").val(),
    		"accion": 'U'
    	};
    	var url = "codigo_proveedor_buscar.php";
    	$.ajax({
    		data: parametros,
    		url: url,
    		type: 'get',
    		beforeSend: function () {//elemento que queramos poner mientras ajax carga
    			//$("#message").html('<img src="images/ajax.gif" width="60" />');
    		},
    		success: function (response) {//resultado de la función
    			var content = $(response).find('#outtext').text();
    			if(content == "1") {
    				alert("Código Proveedor \"" + $("#x_codigo_proveedor").val() + "\" ya existe para el laboratorio \"" + $("#x_laboratorio").val() + "\"");
    				$("#x_codigo_proveedor").val("");
    				$("#x_codigo_proveedor").focus();
    				return false;
    			}
    			else {
    				return true;
    			}
    		}
    	});
    }
});
</script>
