<?php

namespace PHPMaker2024\mandrake;

// Page object
$TipoDocumentoEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<?php if (!$Page->IsModal) { ?>
<?= $Page->Pager->render() ?>
<?php } ?>
<form name="ftipo_documentoedit" id="ftipo_documentoedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { tipo_documento: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var ftipo_documentoedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("ftipo_documentoedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["tipo", [fields.tipo.visible && fields.tipo.required ? ew.Validators.required(fields.tipo.caption) : null], fields.tipo.isInvalid],
            ["M01", [fields.M01.visible && fields.M01.required ? ew.Validators.required(fields.M01.caption) : null], fields.M01.isInvalid],
            ["M02", [fields.M02.visible && fields.M02.required ? ew.Validators.required(fields.M02.caption) : null], fields.M02.isInvalid],
            ["M03", [fields.M03.visible && fields.M03.required ? ew.Validators.required(fields.M03.caption) : null], fields.M03.isInvalid],
            ["M04", [fields.M04.visible && fields.M04.required ? ew.Validators.required(fields.M04.caption) : null], fields.M04.isInvalid],
            ["M05", [fields.M05.visible && fields.M05.required ? ew.Validators.required(fields.M05.caption) : null], fields.M05.isInvalid],
            ["M06", [fields.M06.visible && fields.M06.required ? ew.Validators.required(fields.M06.caption) : null], fields.M06.isInvalid],
            ["M07", [fields.M07.visible && fields.M07.required ? ew.Validators.required(fields.M07.caption) : null], fields.M07.isInvalid],
            ["M08", [fields.M08.visible && fields.M08.required ? ew.Validators.required(fields.M08.caption) : null], fields.M08.isInvalid],
            ["M09", [fields.M09.visible && fields.M09.required ? ew.Validators.required(fields.M09.caption) : null], fields.M09.isInvalid],
            ["M10", [fields.M10.visible && fields.M10.required ? ew.Validators.required(fields.M10.caption) : null], fields.M10.isInvalid],
            ["M11", [fields.M11.visible && fields.M11.required ? ew.Validators.required(fields.M11.caption) : null], fields.M11.isInvalid],
            ["M12", [fields.M12.visible && fields.M12.required ? ew.Validators.required(fields.M12.caption) : null], fields.M12.isInvalid]
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
            "M01": <?= $Page->M01->toClientList($Page) ?>,
            "M02": <?= $Page->M02->toClientList($Page) ?>,
            "M03": <?= $Page->M03->toClientList($Page) ?>,
            "M04": <?= $Page->M04->toClientList($Page) ?>,
            "M05": <?= $Page->M05->toClientList($Page) ?>,
            "M06": <?= $Page->M06->toClientList($Page) ?>,
            "M07": <?= $Page->M07->toClientList($Page) ?>,
            "M08": <?= $Page->M08->toClientList($Page) ?>,
            "M09": <?= $Page->M09->toClientList($Page) ?>,
            "M10": <?= $Page->M10->toClientList($Page) ?>,
            "M11": <?= $Page->M11->toClientList($Page) ?>,
            "M12": <?= $Page->M12->toClientList($Page) ?>,
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
<input type="hidden" name="t" value="tipo_documento">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_tipo_documento_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_tipo_documento_descripcion">
<span<?= $Page->descripcion->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->descripcion->getDisplayValue($Page->descripcion->EditValue))) ?>"></span>
<input type="hidden" data-table="tipo_documento" data-field="x_descripcion" data-hidden="1" name="x_descripcion" id="x_descripcion" value="<?= HtmlEncode($Page->descripcion->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tipo->Visible) { // tipo ?>
    <div id="r_tipo"<?= $Page->tipo->rowAttributes() ?>>
        <label id="elh_tipo_documento_tipo" for="x_tipo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tipo->caption() ?><?= $Page->tipo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->tipo->cellAttributes() ?>>
<span id="el_tipo_documento_tipo">
<span<?= $Page->tipo->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->tipo->getDisplayValue($Page->tipo->EditValue) ?></span></span>
<input type="hidden" data-table="tipo_documento" data-field="x_tipo" data-hidden="1" name="x_tipo" id="x_tipo" value="<?= HtmlEncode($Page->tipo->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M01->Visible) { // M01 ?>
    <div id="r_M01"<?= $Page->M01->rowAttributes() ?>>
        <label id="elh_tipo_documento_M01" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M01->caption() ?><?= $Page->M01->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M01->cellAttributes() ?>>
<span id="el_tipo_documento_M01">
<template id="tp_x_M01">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M01" name="x_M01" id="x_M01"<?= $Page->M01->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M01" class="ew-item-list"></div>
<selection-list hidden
    id="x_M01"
    name="x_M01"
    value="<?= HtmlEncode($Page->M01->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M01"
    data-target="dsl_x_M01"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M01->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M01"
    data-value-separator="<?= $Page->M01->displayValueSeparatorAttribute() ?>"
    <?= $Page->M01->editAttributes() ?>></selection-list>
<?= $Page->M01->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M01->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M02->Visible) { // M02 ?>
    <div id="r_M02"<?= $Page->M02->rowAttributes() ?>>
        <label id="elh_tipo_documento_M02" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M02->caption() ?><?= $Page->M02->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M02->cellAttributes() ?>>
<span id="el_tipo_documento_M02">
<template id="tp_x_M02">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M02" name="x_M02" id="x_M02"<?= $Page->M02->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M02" class="ew-item-list"></div>
<selection-list hidden
    id="x_M02"
    name="x_M02"
    value="<?= HtmlEncode($Page->M02->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M02"
    data-target="dsl_x_M02"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M02->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M02"
    data-value-separator="<?= $Page->M02->displayValueSeparatorAttribute() ?>"
    <?= $Page->M02->editAttributes() ?>></selection-list>
<?= $Page->M02->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M02->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M03->Visible) { // M03 ?>
    <div id="r_M03"<?= $Page->M03->rowAttributes() ?>>
        <label id="elh_tipo_documento_M03" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M03->caption() ?><?= $Page->M03->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M03->cellAttributes() ?>>
<span id="el_tipo_documento_M03">
<template id="tp_x_M03">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M03" name="x_M03" id="x_M03"<?= $Page->M03->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M03" class="ew-item-list"></div>
<selection-list hidden
    id="x_M03"
    name="x_M03"
    value="<?= HtmlEncode($Page->M03->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M03"
    data-target="dsl_x_M03"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M03->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M03"
    data-value-separator="<?= $Page->M03->displayValueSeparatorAttribute() ?>"
    <?= $Page->M03->editAttributes() ?>></selection-list>
<?= $Page->M03->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M03->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M04->Visible) { // M04 ?>
    <div id="r_M04"<?= $Page->M04->rowAttributes() ?>>
        <label id="elh_tipo_documento_M04" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M04->caption() ?><?= $Page->M04->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M04->cellAttributes() ?>>
<span id="el_tipo_documento_M04">
<template id="tp_x_M04">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M04" name="x_M04" id="x_M04"<?= $Page->M04->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M04" class="ew-item-list"></div>
<selection-list hidden
    id="x_M04"
    name="x_M04"
    value="<?= HtmlEncode($Page->M04->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M04"
    data-target="dsl_x_M04"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M04->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M04"
    data-value-separator="<?= $Page->M04->displayValueSeparatorAttribute() ?>"
    <?= $Page->M04->editAttributes() ?>></selection-list>
<?= $Page->M04->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M04->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M05->Visible) { // M05 ?>
    <div id="r_M05"<?= $Page->M05->rowAttributes() ?>>
        <label id="elh_tipo_documento_M05" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M05->caption() ?><?= $Page->M05->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M05->cellAttributes() ?>>
<span id="el_tipo_documento_M05">
<template id="tp_x_M05">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M05" name="x_M05" id="x_M05"<?= $Page->M05->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M05" class="ew-item-list"></div>
<selection-list hidden
    id="x_M05"
    name="x_M05"
    value="<?= HtmlEncode($Page->M05->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M05"
    data-target="dsl_x_M05"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M05->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M05"
    data-value-separator="<?= $Page->M05->displayValueSeparatorAttribute() ?>"
    <?= $Page->M05->editAttributes() ?>></selection-list>
<?= $Page->M05->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M05->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M06->Visible) { // M06 ?>
    <div id="r_M06"<?= $Page->M06->rowAttributes() ?>>
        <label id="elh_tipo_documento_M06" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M06->caption() ?><?= $Page->M06->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M06->cellAttributes() ?>>
<span id="el_tipo_documento_M06">
<template id="tp_x_M06">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M06" name="x_M06" id="x_M06"<?= $Page->M06->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M06" class="ew-item-list"></div>
<selection-list hidden
    id="x_M06"
    name="x_M06"
    value="<?= HtmlEncode($Page->M06->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M06"
    data-target="dsl_x_M06"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M06->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M06"
    data-value-separator="<?= $Page->M06->displayValueSeparatorAttribute() ?>"
    <?= $Page->M06->editAttributes() ?>></selection-list>
<?= $Page->M06->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M06->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M07->Visible) { // M07 ?>
    <div id="r_M07"<?= $Page->M07->rowAttributes() ?>>
        <label id="elh_tipo_documento_M07" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M07->caption() ?><?= $Page->M07->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M07->cellAttributes() ?>>
<span id="el_tipo_documento_M07">
<template id="tp_x_M07">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M07" name="x_M07" id="x_M07"<?= $Page->M07->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M07" class="ew-item-list"></div>
<selection-list hidden
    id="x_M07"
    name="x_M07"
    value="<?= HtmlEncode($Page->M07->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M07"
    data-target="dsl_x_M07"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M07->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M07"
    data-value-separator="<?= $Page->M07->displayValueSeparatorAttribute() ?>"
    <?= $Page->M07->editAttributes() ?>></selection-list>
<?= $Page->M07->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M07->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M08->Visible) { // M08 ?>
    <div id="r_M08"<?= $Page->M08->rowAttributes() ?>>
        <label id="elh_tipo_documento_M08" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M08->caption() ?><?= $Page->M08->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M08->cellAttributes() ?>>
<span id="el_tipo_documento_M08">
<template id="tp_x_M08">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M08" name="x_M08" id="x_M08"<?= $Page->M08->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M08" class="ew-item-list"></div>
<selection-list hidden
    id="x_M08"
    name="x_M08"
    value="<?= HtmlEncode($Page->M08->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M08"
    data-target="dsl_x_M08"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M08->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M08"
    data-value-separator="<?= $Page->M08->displayValueSeparatorAttribute() ?>"
    <?= $Page->M08->editAttributes() ?>></selection-list>
<?= $Page->M08->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M08->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M09->Visible) { // M09 ?>
    <div id="r_M09"<?= $Page->M09->rowAttributes() ?>>
        <label id="elh_tipo_documento_M09" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M09->caption() ?><?= $Page->M09->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M09->cellAttributes() ?>>
<span id="el_tipo_documento_M09">
<template id="tp_x_M09">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M09" name="x_M09" id="x_M09"<?= $Page->M09->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M09" class="ew-item-list"></div>
<selection-list hidden
    id="x_M09"
    name="x_M09"
    value="<?= HtmlEncode($Page->M09->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M09"
    data-target="dsl_x_M09"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M09->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M09"
    data-value-separator="<?= $Page->M09->displayValueSeparatorAttribute() ?>"
    <?= $Page->M09->editAttributes() ?>></selection-list>
<?= $Page->M09->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M09->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M10->Visible) { // M10 ?>
    <div id="r_M10"<?= $Page->M10->rowAttributes() ?>>
        <label id="elh_tipo_documento_M10" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M10->caption() ?><?= $Page->M10->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M10->cellAttributes() ?>>
<span id="el_tipo_documento_M10">
<template id="tp_x_M10">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M10" name="x_M10" id="x_M10"<?= $Page->M10->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M10" class="ew-item-list"></div>
<selection-list hidden
    id="x_M10"
    name="x_M10"
    value="<?= HtmlEncode($Page->M10->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M10"
    data-target="dsl_x_M10"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M10->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M10"
    data-value-separator="<?= $Page->M10->displayValueSeparatorAttribute() ?>"
    <?= $Page->M10->editAttributes() ?>></selection-list>
<?= $Page->M10->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M10->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M11->Visible) { // M11 ?>
    <div id="r_M11"<?= $Page->M11->rowAttributes() ?>>
        <label id="elh_tipo_documento_M11" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M11->caption() ?><?= $Page->M11->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M11->cellAttributes() ?>>
<span id="el_tipo_documento_M11">
<template id="tp_x_M11">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M11" name="x_M11" id="x_M11"<?= $Page->M11->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M11" class="ew-item-list"></div>
<selection-list hidden
    id="x_M11"
    name="x_M11"
    value="<?= HtmlEncode($Page->M11->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M11"
    data-target="dsl_x_M11"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M11->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M11"
    data-value-separator="<?= $Page->M11->displayValueSeparatorAttribute() ?>"
    <?= $Page->M11->editAttributes() ?>></selection-list>
<?= $Page->M11->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M11->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->M12->Visible) { // M12 ?>
    <div id="r_M12"<?= $Page->M12->rowAttributes() ?>>
        <label id="elh_tipo_documento_M12" class="<?= $Page->LeftColumnClass ?>"><?= $Page->M12->caption() ?><?= $Page->M12->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->M12->cellAttributes() ?>>
<span id="el_tipo_documento_M12">
<template id="tp_x_M12">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="tipo_documento" data-field="x_M12" name="x_M12" id="x_M12"<?= $Page->M12->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_M12" class="ew-item-list"></div>
<selection-list hidden
    id="x_M12"
    name="x_M12"
    value="<?= HtmlEncode($Page->M12->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_M12"
    data-target="dsl_x_M12"
    data-repeatcolumn="5"
    class="form-control<?= $Page->M12->isInvalidClass() ?>"
    data-table="tipo_documento"
    data-field="x_M12"
    data-value-separator="<?= $Page->M12->displayValueSeparatorAttribute() ?>"
    <?= $Page->M12->editAttributes() ?>></selection-list>
<?= $Page->M12->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->M12->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="tipo_documento" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode($Page->id->CurrentValue) ?>">
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="ftipo_documentoedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="ftipo_documentoedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
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
    ew.addEventHandlers("tipo_documento");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
