<?php

namespace PHPMaker2021\mandrake;

// Page object
$HistoriaArticuloAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fhistoria_articuloadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fhistoria_articuloadd = currentForm = new ew.Form("fhistoria_articuloadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "historia_articulo")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.historia_articulo)
        ew.vars.tables.historia_articulo = currentTable;
    fhistoria_articuloadd.addFields([
        ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null, ew.Validators.integer], fields.id.isInvalid],
        ["fabricante", [fields.fabricante.visible && fields.fabricante.required ? ew.Validators.required(fields.fabricante.caption) : null], fields.fabricante.isInvalid],
        ["articulo", [fields.articulo.visible && fields.articulo.required ? ew.Validators.required(fields.articulo.caption) : null], fields.articulo.isInvalid],
        ["proveedor", [fields.proveedor.visible && fields.proveedor.required ? ew.Validators.required(fields.proveedor.caption) : null], fields.proveedor.isInvalid],
        ["almacen", [fields.almacen.visible && fields.almacen.required ? ew.Validators.required(fields.almacen.caption) : null], fields.almacen.isInvalid],
        ["tipo_documento", [fields.tipo_documento.visible && fields.tipo_documento.required ? ew.Validators.required(fields.tipo_documento.caption) : null], fields.tipo_documento.isInvalid],
        ["nro_documento", [fields.nro_documento.visible && fields.nro_documento.required ? ew.Validators.required(fields.nro_documento.caption) : null], fields.nro_documento.isInvalid],
        ["fecha", [fields.fecha.visible && fields.fecha.required ? ew.Validators.required(fields.fecha.caption) : null, ew.Validators.datetime(7)], fields.fecha.isInvalid],
        ["lote", [fields.lote.visible && fields.lote.required ? ew.Validators.required(fields.lote.caption) : null], fields.lote.isInvalid],
        ["fecha_vencimiento", [fields.fecha_vencimiento.visible && fields.fecha_vencimiento.required ? ew.Validators.required(fields.fecha_vencimiento.caption) : null, ew.Validators.datetime(0)], fields.fecha_vencimiento.isInvalid],
        ["usuario", [fields.usuario.visible && fields.usuario.required ? ew.Validators.required(fields.usuario.caption) : null], fields.usuario.isInvalid],
        ["entradas", [fields.entradas.visible && fields.entradas.required ? ew.Validators.required(fields.entradas.caption) : null], fields.entradas.isInvalid],
        ["salidas", [fields.salidas.visible && fields.salidas.required ? ew.Validators.required(fields.salidas.caption) : null], fields.salidas.isInvalid],
        ["existencia", [fields.existencia.visible && fields.existencia.required ? ew.Validators.required(fields.existencia.caption) : null], fields.existencia.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fhistoria_articuloadd,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fhistoria_articuloadd.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fhistoria_articuloadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fhistoria_articuloadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fhistoria_articuloadd.lists.fabricante = <?= $Page->fabricante->toClientList($Page) ?>;
    fhistoria_articuloadd.lists.articulo = <?= $Page->articulo->toClientList($Page) ?>;
    fhistoria_articuloadd.lists.proveedor = <?= $Page->proveedor->toClientList($Page) ?>;
    fhistoria_articuloadd.lists.almacen = <?= $Page->almacen->toClientList($Page) ?>;
    fhistoria_articuloadd.lists.tipo_documento = <?= $Page->tipo_documento->toClientList($Page) ?>;
    fhistoria_articuloadd.lists.usuario = <?= $Page->usuario->toClientList($Page) ?>;
    loadjs.done("fhistoria_articuloadd");
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
<form name="fhistoria_articuloadd" id="fhistoria_articuloadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="historia_articulo">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id" class="form-group row">
        <label id="elh_historia_articulo_id" for="x_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id->cellAttributes() ?>>
<span id="el_historia_articulo_id">
<input type="<?= $Page->id->getInputTextType() ?>" data-table="historia_articulo" data-field="x_id" name="x_id" id="x_id" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->id->getPlaceHolder()) ?>" value="<?= $Page->id->EditValue ?>"<?= $Page->id->editAttributes() ?> aria-describedby="x_id_help">
<?= $Page->id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fabricante->Visible) { // fabricante ?>
    <div id="r_fabricante" class="form-group row">
        <label id="elh_historia_articulo_fabricante" for="x_fabricante" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fabricante->caption() ?><?= $Page->fabricante->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fabricante->cellAttributes() ?>>
<span id="el_historia_articulo_fabricante">
<div class="input-group ew-lookup-list" aria-describedby="x_fabricante_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_fabricante"><?= EmptyValue(strval($Page->fabricante->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->fabricante->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->fabricante->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->fabricante->ReadOnly || $Page->fabricante->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_fabricante',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->fabricante->getErrorMessage() ?></div>
<?= $Page->fabricante->getCustomMessage() ?>
<?= $Page->fabricante->Lookup->getParamTag($Page, "p_x_fabricante") ?>
<input type="hidden" is="selection-list" data-table="historia_articulo" data-field="x_fabricante" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->fabricante->displayValueSeparatorAttribute() ?>" name="x_fabricante" id="x_fabricante" value="<?= $Page->fabricante->CurrentValue ?>"<?= $Page->fabricante->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->articulo->Visible) { // articulo ?>
    <div id="r_articulo" class="form-group row">
        <label id="elh_historia_articulo_articulo" for="x_articulo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->articulo->caption() ?><?= $Page->articulo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->articulo->cellAttributes() ?>>
<span id="el_historia_articulo_articulo">
<div class="input-group ew-lookup-list" aria-describedby="x_articulo_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_articulo"><?= EmptyValue(strval($Page->articulo->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->articulo->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->articulo->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->articulo->ReadOnly || $Page->articulo->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_articulo',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->articulo->getErrorMessage() ?></div>
<?= $Page->articulo->getCustomMessage() ?>
<?= $Page->articulo->Lookup->getParamTag($Page, "p_x_articulo") ?>
<input type="hidden" is="selection-list" data-table="historia_articulo" data-field="x_articulo" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->articulo->displayValueSeparatorAttribute() ?>" name="x_articulo" id="x_articulo" value="<?= $Page->articulo->CurrentValue ?>"<?= $Page->articulo->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->proveedor->Visible) { // proveedor ?>
    <div id="r_proveedor" class="form-group row">
        <label id="elh_historia_articulo_proveedor" for="x_proveedor" class="<?= $Page->LeftColumnClass ?>"><?= $Page->proveedor->caption() ?><?= $Page->proveedor->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->proveedor->cellAttributes() ?>>
<span id="el_historia_articulo_proveedor">
<div class="input-group ew-lookup-list" aria-describedby="x_proveedor_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_proveedor"><?= EmptyValue(strval($Page->proveedor->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->proveedor->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->proveedor->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->proveedor->ReadOnly || $Page->proveedor->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_proveedor',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->proveedor->getErrorMessage() ?></div>
<?= $Page->proveedor->getCustomMessage() ?>
<?= $Page->proveedor->Lookup->getParamTag($Page, "p_x_proveedor") ?>
<input type="hidden" is="selection-list" data-table="historia_articulo" data-field="x_proveedor" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->proveedor->displayValueSeparatorAttribute() ?>" name="x_proveedor" id="x_proveedor" value="<?= $Page->proveedor->CurrentValue ?>"<?= $Page->proveedor->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->almacen->Visible) { // almacen ?>
    <div id="r_almacen" class="form-group row">
        <label id="elh_historia_articulo_almacen" for="x_almacen" class="<?= $Page->LeftColumnClass ?>"><?= $Page->almacen->caption() ?><?= $Page->almacen->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->almacen->cellAttributes() ?>>
<span id="el_historia_articulo_almacen">
<div class="input-group ew-lookup-list" aria-describedby="x_almacen_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_almacen"><?= EmptyValue(strval($Page->almacen->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->almacen->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->almacen->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->almacen->ReadOnly || $Page->almacen->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_almacen',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->almacen->getErrorMessage() ?></div>
<?= $Page->almacen->getCustomMessage() ?>
<?= $Page->almacen->Lookup->getParamTag($Page, "p_x_almacen") ?>
<input type="hidden" is="selection-list" data-table="historia_articulo" data-field="x_almacen" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->almacen->displayValueSeparatorAttribute() ?>" name="x_almacen" id="x_almacen" value="<?= $Page->almacen->CurrentValue ?>"<?= $Page->almacen->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo_documento->Visible) { // tipo_documento ?>
    <div id="r_tipo_documento" class="form-group row">
        <label id="elh_historia_articulo_tipo_documento" for="x_tipo_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo_documento->caption() ?><?= $Page->tipo_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tipo_documento->cellAttributes() ?>>
<span id="el_historia_articulo_tipo_documento">
    <select
        id="x_tipo_documento"
        name="x_tipo_documento"
        class="form-control ew-select<?= $Page->tipo_documento->isInvalidClass() ?>"
        data-select2-id="historia_articulo_x_tipo_documento"
        data-table="historia_articulo"
        data-field="x_tipo_documento"
        data-value-separator="<?= $Page->tipo_documento->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->tipo_documento->getPlaceHolder()) ?>"
        <?= $Page->tipo_documento->editAttributes() ?>>
        <?= $Page->tipo_documento->selectOptionListHtml("x_tipo_documento") ?>
    </select>
    <?= $Page->tipo_documento->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->tipo_documento->getErrorMessage() ?></div>
<?= $Page->tipo_documento->Lookup->getParamTag($Page, "p_x_tipo_documento") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='historia_articulo_x_tipo_documento']"),
        options = { name: "x_tipo_documento", selectId: "historia_articulo_x_tipo_documento", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.historia_articulo.fields.tipo_documento.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nro_documento->Visible) { // nro_documento ?>
    <div id="r_nro_documento" class="form-group row">
        <label id="elh_historia_articulo_nro_documento" for="x_nro_documento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nro_documento->caption() ?><?= $Page->nro_documento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nro_documento->cellAttributes() ?>>
<span id="el_historia_articulo_nro_documento">
<input type="<?= $Page->nro_documento->getInputTextType() ?>" data-table="historia_articulo" data-field="x_nro_documento" name="x_nro_documento" id="x_nro_documento" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->nro_documento->getPlaceHolder()) ?>" value="<?= $Page->nro_documento->EditValue ?>"<?= $Page->nro_documento->editAttributes() ?> aria-describedby="x_nro_documento_help">
<?= $Page->nro_documento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nro_documento->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha->Visible) { // fecha ?>
    <div id="r_fecha" class="form-group row">
        <label id="elh_historia_articulo_fecha" for="x_fecha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha->caption() ?><?= $Page->fecha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha->cellAttributes() ?>>
<span id="el_historia_articulo_fecha">
<input type="<?= $Page->fecha->getInputTextType() ?>" data-table="historia_articulo" data-field="x_fecha" data-format="7" name="x_fecha" id="x_fecha" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha->getPlaceHolder()) ?>" value="<?= $Page->fecha->EditValue ?>"<?= $Page->fecha->editAttributes() ?> aria-describedby="x_fecha_help">
<?= $Page->fecha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha->getErrorMessage() ?></div>
<?php if (!$Page->fecha->ReadOnly && !$Page->fecha->Disabled && !isset($Page->fecha->EditAttrs["readonly"]) && !isset($Page->fecha->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fhistoria_articuloadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fhistoria_articuloadd", "x_fecha", {"ignoreReadonly":true,"useCurrent":false,"format":7});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->lote->Visible) { // lote ?>
    <div id="r_lote" class="form-group row">
        <label id="elh_historia_articulo_lote" for="x_lote" class="<?= $Page->LeftColumnClass ?>"><?= $Page->lote->caption() ?><?= $Page->lote->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->lote->cellAttributes() ?>>
<span id="el_historia_articulo_lote">
<input type="<?= $Page->lote->getInputTextType() ?>" data-table="historia_articulo" data-field="x_lote" name="x_lote" id="x_lote" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->lote->getPlaceHolder()) ?>" value="<?= $Page->lote->EditValue ?>"<?= $Page->lote->editAttributes() ?> aria-describedby="x_lote_help">
<?= $Page->lote->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->lote->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->fecha_vencimiento->Visible) { // fecha_vencimiento ?>
    <div id="r_fecha_vencimiento" class="form-group row">
        <label id="elh_historia_articulo_fecha_vencimiento" for="x_fecha_vencimiento" class="<?= $Page->LeftColumnClass ?>"><?= $Page->fecha_vencimiento->caption() ?><?= $Page->fecha_vencimiento->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->fecha_vencimiento->cellAttributes() ?>>
<span id="el_historia_articulo_fecha_vencimiento">
<input type="<?= $Page->fecha_vencimiento->getInputTextType() ?>" data-table="historia_articulo" data-field="x_fecha_vencimiento" name="x_fecha_vencimiento" id="x_fecha_vencimiento" maxlength="10" placeholder="<?= HtmlEncode($Page->fecha_vencimiento->getPlaceHolder()) ?>" value="<?= $Page->fecha_vencimiento->EditValue ?>"<?= $Page->fecha_vencimiento->editAttributes() ?> aria-describedby="x_fecha_vencimiento_help">
<?= $Page->fecha_vencimiento->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->fecha_vencimiento->getErrorMessage() ?></div>
<?php if (!$Page->fecha_vencimiento->ReadOnly && !$Page->fecha_vencimiento->Disabled && !isset($Page->fecha_vencimiento->EditAttrs["readonly"]) && !isset($Page->fecha_vencimiento->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fhistoria_articuloadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fhistoria_articuloadd", "x_fecha_vencimiento", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->usuario->Visible) { // usuario ?>
    <div id="r_usuario" class="form-group row">
        <label id="elh_historia_articulo_usuario" for="x_usuario" class="<?= $Page->LeftColumnClass ?>"><?= $Page->usuario->caption() ?><?= $Page->usuario->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->usuario->cellAttributes() ?>>
<span id="el_historia_articulo_usuario">
<div class="input-group ew-lookup-list" aria-describedby="x_usuario_help">
    <div class="form-control ew-lookup-text" tabindex="-1" id="lu_x_usuario"><?= EmptyValue(strval($Page->usuario->ViewValue)) ? $Language->phrase("PleaseSelect") : $Page->usuario->ViewValue ?></div>
    <div class="input-group-append">
        <button type="button" title="<?= HtmlEncode(str_replace("%s", RemoveHtml($Page->usuario->caption()), $Language->phrase("LookupLink", true))) ?>" class="ew-lookup-btn btn btn-default"<?= ($Page->usuario->ReadOnly || $Page->usuario->Disabled) ? " disabled" : "" ?> onclick="ew.modalLookupShow({lnk:this,el:'x_usuario',m:0,n:10});"><i class="fas fa-search ew-icon"></i></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->usuario->getErrorMessage() ?></div>
<?= $Page->usuario->getCustomMessage() ?>
<?= $Page->usuario->Lookup->getParamTag($Page, "p_x_usuario") ?>
<input type="hidden" is="selection-list" data-table="historia_articulo" data-field="x_usuario" data-type="text" data-multiple="0" data-lookup="1" data-value-separator="<?= $Page->usuario->displayValueSeparatorAttribute() ?>" name="x_usuario" id="x_usuario" value="<?= $Page->usuario->CurrentValue ?>"<?= $Page->usuario->editAttributes() ?>>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->entradas->Visible) { // entradas ?>
    <div id="r_entradas" class="form-group row">
        <label id="elh_historia_articulo_entradas" for="x_entradas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->entradas->caption() ?><?= $Page->entradas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->entradas->cellAttributes() ?>>
<span id="el_historia_articulo_entradas">
<input type="<?= $Page->entradas->getInputTextType() ?>" data-table="historia_articulo" data-field="x_entradas" name="x_entradas" id="x_entradas" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->entradas->getPlaceHolder()) ?>" value="<?= $Page->entradas->EditValue ?>"<?= $Page->entradas->editAttributes() ?> aria-describedby="x_entradas_help">
<?= $Page->entradas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->entradas->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->salidas->Visible) { // salidas ?>
    <div id="r_salidas" class="form-group row">
        <label id="elh_historia_articulo_salidas" for="x_salidas" class="<?= $Page->LeftColumnClass ?>"><?= $Page->salidas->caption() ?><?= $Page->salidas->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->salidas->cellAttributes() ?>>
<span id="el_historia_articulo_salidas">
<input type="<?= $Page->salidas->getInputTextType() ?>" data-table="historia_articulo" data-field="x_salidas" name="x_salidas" id="x_salidas" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->salidas->getPlaceHolder()) ?>" value="<?= $Page->salidas->EditValue ?>"<?= $Page->salidas->editAttributes() ?> aria-describedby="x_salidas_help">
<?= $Page->salidas->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->salidas->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->existencia->Visible) { // existencia ?>
    <div id="r_existencia" class="form-group row">
        <label id="elh_historia_articulo_existencia" for="x_existencia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->existencia->caption() ?><?= $Page->existencia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->existencia->cellAttributes() ?>>
<span id="el_historia_articulo_existencia">
<input type="<?= $Page->existencia->getInputTextType() ?>" data-table="historia_articulo" data-field="x_existencia" name="x_existencia" id="x_existencia" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->existencia->getPlaceHolder()) ?>" value="<?= $Page->existencia->EditValue ?>"<?= $Page->existencia->editAttributes() ?> aria-describedby="x_existencia_help">
<?= $Page->existencia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->existencia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("historia_articulo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
