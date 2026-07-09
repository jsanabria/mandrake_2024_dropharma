<?php

namespace PHPMaker2024\mandrake;

// Page object
$MainReport = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><strong>Seleccione un Reporte</strong></h3>
    </div>
    <div class="panel-body">
        <form method="get" action="ListadoMaster">
            
            <!-- Grupo: Exportaciones IMS -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_ims_cli">
                        <input type="radio" id="rep_ims_cli" name="id" value="ims_clientes" checked="checked">
                        EXPORTAR CLIENTES IMS
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_ims_art">
                        <input type="radio" id="rep_ims_art" name="id" value="ims_articulos">
                        EXPORTAR ARTICULOS IMS
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_ims_fac">
                        <input type="radio" id="rep_ims_fac" name="id" value="ims_facturas">
                        EXPORTAR FACTURAS IMS
                    </label>
                </div>
            </div>
            <hr>

            <!-- Grupo: Libros y Auditoría -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_tax_com">
                        <input type="radio" id="rep_tax_com" name="id" value="tax_libro_compra">
                        LIBRO DE COMPRAS
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_tax_ven">
                        <input type="radio" id="rep_tax_ven" name="id" value="tax_libro_venta">
                        LIBRO DE VENTAS
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_aud_cos">
                        <input type="radio" id="rep_aud_cos" name="id" value="aud_costo_vs_precio">
                        FACTURAS COSTOS VS PRECIO
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_inv_kar">
                        <input type="radio" id="rep_inv_kar" name="id" value="inv_kardex">
                        KARDEX DE INVENTARIO
                    </label>
                </div>
            </div>
            <hr>

            <!-- Grupo: Detallados -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_det_ent">
                        <input type="radio" id="rep_det_ent" name="id" value="det_entradas_general">
                        ENTRADAS GENERALES POR ARTICULO DETALLADO
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_det_ped">
                        <input type="radio" id="rep_det_ped" name="id" value="det_pedidos_venta">
                        PEDIDOS DE VENTAS DETALLADO
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_det_not">
                        <input type="radio" id="rep_det_not" name="id" value="det_notas_entrega">
                        NOTAS DE ENTREGA DETALLADO
                    </label>
                </div>
            </div>
            <hr>

            <!-- Grupo: Ventas y Salidas -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_vta_lab">
                        <input type="radio" id="rep_vta_lab" name="id" value="vta_laboratorio">
                        VENTAS POR FABRICANTE (FACTURAS)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_vta_art">
                        <input type="radio" id="rep_vta_art" name="id" value="vta_articulo">
                        VENTAS POR ARTICULO (FACTURAS)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_vta_utl">
                        <input type="radio" id="rep_vta_utl" name="id" value="vta_articulo_utilidad">
                        VENTAS POR ARTICULO (FACTURAS UTILIDAD NETA)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_sal_lab">
                        <input type="radio" id="rep_sal_lab" name="id" value="sal_laboratorio">
                        SALIDAS GENERALES POR FABRICANTE (FACTURAS + AJUSTE SALIDAS)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_sal_art">
                        <input type="radio" id="rep_sal_art" name="id" value="sal_articulo">
                        SALIDAS GENERALES POR ARTICULO (FACTURAS + AJUSTE SALIDAS)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_sal_art">
                        <input type="radio" id="rep_sal_art" name="id" value="sal_articulo_neas">
                        SALIDAS GENERALES POR ARTICULO (NOTAS DE ENTRAGA + AJUSTE SALIDAS)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_vta_cli">
                        <input type="radio" id="rep_vta_cli" name="id" value="vta_cliente">
                        VENTAS POR CLIENTE (FACTURAS SIN IVA Y CANTIDAD DE UNIDADES)
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_sal_det">
                        <input type="radio" id="rep_sal_det" name="id" value="sal_articulo_detallado">
                        SALIDAS GENERALES POR ARTICULO DETALLADO
                    </label>
                </div>
            </div>
            <hr>

            <!-- Grupo: Consignaciones -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_cng_cli">
                        <input type="radio" id="rep_cng_cli" name="id" value="cng_cliente">
                        CONSIGNACIONES POR CLIENTE
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_cng_fac">
                        <input type="radio" id="rep_cng_fac" name="id" value="cng_facturas">
                        FACTURAS POR CONSIGNACION
                    </label>
                </div>
            </div>
            <hr>

            <!-- Grupo: Comportamiento de Clientes -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_cli_rec">
                        <input type="radio" id="rep_cli_rec" name="id" value="cli_compras_recientes">
                        CLIENTES CON COMPRAS RECIENTES
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_cli_sin">
                        <input type="radio" id="rep_cli_sin" name="id" value="cli_sin_compras">
                        CLIENTES SIN COMPRAS RECIENTES
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_cng_des">
                        <input type="radio" id="rep_cng_des" name="id" value="cng_descarga_entradas">
                        DESCARGA ENTRADAS A CONSIGNACION
                    </label>
                </div>
            </div>
            <hr>

            <!-- Grupo: Inventario Avanzado -->
            <div class="form-group">
                <div class="radio">
                    <label for="rep_inv_fec">
                        <input type="radio" id="rep_inv_fec" name="id" value="inv_entre_fechas">
                        INVENTARIO ENTRE FECHA
                    </label>
                </div>
            </div>

        <?php 
        ExecuteStatement("
            INSERT INTO parametro (codigo, descripcion, valor1)
            SELECT '112', 'USA IMPRESORA FISCAL', 'N'
            FROM DUAL
            WHERE NOT EXISTS (
                SELECT 1
                FROM parametro
                WHERE codigo = '112'
            )
        ");

        $impresoraFiscal = strtoupper(trim(
            ExecuteScalar("SELECT valor1 FROM parametro WHERE codigo = '112'")
        )); 

        if ($impresoraFiscal == "S") { 
        ?>

            <!-- Grupo: Cierres Fiscales -->
            <hr>
            <div class="form-group">
                <div class="radio">
                    <label for="rep_x">
                        <input type="radio" id="rep_x" name="id" value="REPX">
                        REPORTE X
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_z">
                        <input type="radio" id="rep_z" name="id" value="REPZ">
                        REPORTE Z
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_info">
                        <input type="radio" id="rep_info" name="id" value="INFO">
                        REPORTE INFO
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_infojson">
                        <input type="radio" id="rep_infojson" name="id" value="INFOJSON">
                        REPORTE INFOJSON
                    </label>
                </div>
                <div class="radio">
                    <label for="rep_close">
                        <input type="radio" id="rep_close" name="id" value="CLOSE">
                        RECUPERAR IMPRESORA
                    </label>
                </div>
            </div>
            <br>

        <?php } ?>

            <button type="submit" class="btn btn-primary btn-block-xs">
                <span class="glyphicon glyphicon-list-alt"></span> Generar Reporte
            </button>
        </form>
    </div>
</div>
<?= GetDebugMessage() ?>
