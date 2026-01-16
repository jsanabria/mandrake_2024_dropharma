<?php

namespace PHPMaker2024\mandrake;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Slim\HttpCache\CacheProvider;
use Slim\Flash\Messages;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;
use Doctrine\DBAL\Platforms;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Mime\MimeTypes;
use FastRoute\RouteParser\Std;
use Illuminate\Encryption\Encrypter;
use HTMLPurifier_Config;
use HTMLPurifier;

// Connections and entity managers
$definitions = [];
$dbids = array_keys(Config("Databases"));
foreach ($dbids as $dbid) {
    $definitions["connection." . $dbid] = \DI\factory(function (string $dbid) {
        return ConnectDb(Db($dbid));
    })->parameter("dbid", $dbid);
    $definitions["entitymanager." . $dbid] = \DI\factory(function (ContainerInterface $c, string $dbid) {
        $cache = IsDevelopment()
            ? DoctrineProvider::wrap(new ArrayAdapter())
            : DoctrineProvider::wrap(new FilesystemAdapter(directory: Config("DOCTRINE.CACHE_DIR")));
        $config = Setup::createAttributeMetadataConfiguration(
            Config("DOCTRINE.METADATA_DIRS"),
            IsDevelopment(),
            null,
            $cache
        );
        $conn = $c->get("connection." . $dbid);
        return new EntityManager($conn, $config);
    })->parameter("dbid", $dbid);
}

return [
    "app.cache" => \DI\create(CacheProvider::class),
    "app.flash" => fn(ContainerInterface $c) => new Messages(),
    "app.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "views/"),
    "email.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "lang/"),
    "sms.view" => fn(ContainerInterface $c) => new PhpRenderer($GLOBALS["RELATIVE_PATH"] . "lang/"),
    "app.audit" => fn(ContainerInterface $c) => (new Logger("audit"))->pushHandler(new AuditTrailHandler($GLOBALS["RELATIVE_PATH"] . "log/audit.log")), // For audit trail
    "app.logger" => fn(ContainerInterface $c) => (new Logger("log"))->pushHandler(new RotatingFileHandler($GLOBALS["RELATIVE_PATH"] . "log/log.log")),
    "sql.logger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debug.stack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "app.csrf" => fn(ContainerInterface $c) => new Guard($GLOBALS["ResponseFactory"], Config("CSRF_PREFIX")),
    "html.purifier.config" => fn(ContainerInterface $c) => HTMLPurifier_Config::createDefault(),
    "html.purifier" => fn(ContainerInterface $c) => new HTMLPurifier($c->get("html.purifier.config")),
    "debug.stack" => \DI\create(DebugStack::class),
    "debug.sql.logger" => \DI\create(DebugSqlLogger::class),
    "debug.timer" => \DI\create(Timer::class),
    "app.security" => \DI\create(AdvancedSecurity::class),
    "user.profile" => \DI\create(UserProfile::class),
    "app.session" => \DI\create(HttpSession::class),
    "mime.types" => \DI\create(MimeTypes::class),
    "app.language" => \DI\create(Language::class),
    PermissionMiddleware::class => \DI\create(PermissionMiddleware::class),
    ApiPermissionMiddleware::class => \DI\create(ApiPermissionMiddleware::class),
    JwtMiddleware::class => \DI\create(JwtMiddleware::class),
    Std::class => \DI\create(Std::class),
    Encrypter::class => fn(ContainerInterface $c) => new Encrypter(AesEncryptionKey(base64_decode(Config("AES_ENCRYPTION_KEY"))), Config("AES_ENCRYPTION_CIPHER")),

    // Tables
    "abono" => \DI\create(Abono::class),
    "actualizar_nota_entrega" => \DI\create(ActualizarNotaEntrega::class),
    "Actualizar_tarifa_patron" => \DI\create(ActualizarTarifaPatron::class),
    "ActualizarExiste" => \DI\create(ActualizarExiste::class),
    "adjunto" => \DI\create(Adjunto::class),
    "ajustar_existencia_lotes" => \DI\create(AjustarExistenciaLotes::class),
    "ajuste_de_entrada_detalle_copia" => \DI\create(AjusteDeEntradaDetalleCopia::class),
    "ajuste_salida" => \DI\create(AjusteSalida::class),
    "alicuota" => \DI\create(Alicuota::class),
    "almacen" => \DI\create(Almacen::class),
    "almacenista" => \DI\create(Almacenista::class),
    "anular_venta" => \DI\create(AnularVenta::class),
    "articulo" => \DI\create(Articulo::class),
    "articulo_anterior" => \DI\create(ArticuloAnterior::class),
    "articulo_porcentaje_descuento_temp" => \DI\create(ArticuloPorcentajeDescuentoTemp::class),
    "articulo_unidad_medida" => \DI\create(ArticuloUnidadMedida::class),
    "asesor" => \DI\create(Asesor::class),
    "asesor_cliente" => \DI\create(AsesorCliente::class),
    "asesor_fabricante" => \DI\create(AsesorFabricante::class),
    "audittrail" => \DI\create(Audittrail::class),
    "balance_de_comprobacion" => \DI\create(BalanceDeComprobacion::class),
    "balance_general" => \DI\create(BalanceGeneral::class),
    "cliente" => \DI\create(Cliente::class),
    "cliente_consignacion_articulos" => \DI\create(ClienteConsignacionArticulos::class),
    "cliente_consignacion_lista" => \DI\create(ClienteConsignacionLista::class),
    "cliente_consignacion_procesar" => \DI\create(ClienteConsignacionProcesar::class),
    "cliente_consignacion_ver" => \DI\create(ClienteConsignacionVer::class),
    "cobros_cliente" => \DI\create(CobrosCliente::class),
    "cobros_cliente_factura" => \DI\create(CobrosClienteFactura::class),
    "codigo_buscar" => \DI\create(CodigoBuscar::class),
    "codigo_proveedor_buscar" => \DI\create(CodigoProveedorBuscar::class),
    "compania" => \DI\create(Compania::class),
    "compania_cuenta" => \DI\create(CompaniaCuenta::class),
    "compra" => \DI\create(Compra::class),
    "Confirm_Page" => \DI\create(ConfirmPage::class),
    "cont_asiento" => \DI\create(ContAsiento::class),
    "cont_comprobante" => \DI\create(ContComprobante::class),
    "cont_lotes" => \DI\create(ContLotes::class),
    "cont_lotes_pagos" => \DI\create(ContLotesPagos::class),
    "cont_lotes_pagos_detalle" => \DI\create(ContLotesPagosDetalle::class),
    "cont_mes_contable" => \DI\create(ContMesContable::class),
    "cont_periodo_contable" => \DI\create(ContPeriodoContable::class),
    "cont_plancta" => \DI\create(ContPlancta::class),
    "cont_reglas" => \DI\create(ContReglas::class),
    "cont_reglas_hr" => \DI\create(ContReglasHr::class),
    "convertir_a_factura" => \DI\create(ConvertirAFactura::class),
    "crear_factura_compra" => \DI\create(CrearFacturaCompra::class),
    "crear_factura_venta" => \DI\create(CrearFacturaVenta::class),
    "crear_factura_venta2" => \DI\create(CrearFacturaVenta2::class),
    "crear_nota_entrada_update" => \DI\create(CrearNotaEntradaUpdate::class),
    "crear_nota_entrega" => \DI\create(CrearNotaEntrega::class),
    "crear_nota_entrega_guardar" => \DI\create(CrearNotaEntregaGuardar::class),
    "crear_nota_recepcion" => \DI\create(CrearNotaRecepcion::class),
    "eliminar_linea" => \DI\create(EliminarLinea::class),
    "entradas" => \DI\create(Entradas::class),
    "entradas_salidas" => \DI\create(EntradasSalidas::class),
    "error_page" => \DI\create(ErrorPage::class),
    "estado_resultados" => \DI\create(EstadoResultados::class),
    "exportar_data" => \DI\create(ExportarData::class),
    "fabricante" => \DI\create(Fabricante::class),
    "factura_consignacion" => \DI\create(FacturaConsignacion::class),
    "factura_consignacion_guardar" => \DI\create(FacturaConsignacionGuardar::class),
    "factura_de_compra_detalle_copia" => \DI\create(FacturaDeCompraDetalleCopia::class),
    "factura_de_venta_copiar_como" => \DI\create(FacturaDeVentaCopiarComo::class),
    "factura_de_venta_detalle_copia" => \DI\create(FacturaDeVentaDetalleCopia::class),
    "ftp_fact_pedi_procesado" => \DI\create(FtpFactPediProcesado::class),
    "ftp_subir_pedidos" => \DI\create(FtpSubirPedidos::class),
    "funciones" => \DI\create(Funciones::class),
    "grupo_funciones" => \DI\create(GrupoFunciones::class),
    "historia_articulo" => \DI\create(HistoriaArticulo::class),
    "home" => \DI\create(Home::class),
    "imagenes_cliente" => \DI\create(ImagenesCliente::class),
    "indicadores" => \DI\create(Indicadores::class),
    "libro_diario" => \DI\create(LibroDiario::class),
    "libro_mayor" => \DI\create(LibroMayor::class),
    "limite_cliente" => \DI\create(LimiteCliente::class),
    "listado_master" => \DI\create(ListadoMaster::class),
    "listado_master_general" => \DI\create(ListadoMasterGeneral::class),
    "main_report" => \DI\create(MainReport::class),
    "nota_de_entrega_buscar" => \DI\create(NotaDeEntregaBuscar::class),
    "nota_de_entrega_buscar_listar" => \DI\create(NotaDeEntregaBuscarListar::class),
    "nota_de_entrega_ver" => \DI\create(NotaDeEntregaVer::class),
    "notificaciones" => \DI\create(Notificaciones::class),
    "pagos" => \DI\create(Pagos::class),
    "pagos_proveedor" => \DI\create(PagosProveedor::class),
    "pagos_proveedor_factura" => \DI\create(PagosProveedorFactura::class),
    "parametro" => \DI\create(Parametro::class),
    "pedidio_detalle_online" => \DI\create(PedidioDetalleOnline::class),
    "pedido_de_compra_detalle_copia" => \DI\create(PedidoDeCompraDetalleCopia::class),
    "pedido_de_venta_detalle" => \DI\create(PedidoDeVentaDetalle::class),
    "pedido_de_venta_detalle_agregar" => \DI\create(PedidoDeVentaDetalleAgregar::class),
    "pedido_de_venta_detalle_copia" => \DI\create(PedidoDeVentaDetalleCopia::class),
    "pedido_de_venta_detalle_guardar" => \DI\create(PedidoDeVentaDetalleGuardar::class),
    "pedido_online" => \DI\create(PedidoOnline::class),
    "proveedor" => \DI\create(Proveedor::class),
    "proveedor_articulo" => \DI\create(ProveedorArticulo::class),
    "purga" => \DI\create(Purga::class),
    "purga_detalle" => \DI\create(PurgaDetalle::class),
    "recarga" => \DI\create(Recarga::class),
    "rif_buscar" => \DI\create(RifBuscar::class),
    "salidas" => \DI\create(Salidas::class),
    "sesiones" => \DI\create(Sesiones::class),
    "subir_costo" => \DI\create(SubirCosto::class),
    "subir_costo_guardar" => \DI\create(SubirCostoGuardar::class),
    "subir_por_desc_articulo" => \DI\create(SubirPorDescArticulo::class),
    "subir_por_desc_articulo_guardar" => \DI\create(SubirPorDescArticuloGuardar::class),
    "subir_tarifa" => \DI\create(SubirTarifa::class),
    "subir_tarifa_guardar" => \DI\create(SubirTarifaGuardar::class),
    "SyncItem" => \DI\create(SyncItem::class),
    "tabla" => \DI\create(Tabla::class),
    "tarifa" => \DI\create(Tarifa::class),
    "tarifa_anterior" => \DI\create(TarifaAnterior::class),
    "tarifa_articulo" => \DI\create(TarifaArticulo::class),
    "tarifa_descuento_utilidad" => \DI\create(TarifaDescuentoUtilidad::class),
    "tasa_usd" => \DI\create(TasaUsd::class),
    "temp_consignacion" => \DI\create(TempConsignacion::class),
    "tipo_documento" => \DI\create(TipoDocumento::class),
    "transferencia_articulo" => \DI\create(TransferenciaArticulo::class),
    "transferencia_articulo_detalle" => \DI\create(TransferenciaArticuloDetalle::class),
    "transferencia_articulo_listar" => \DI\create(TransferenciaArticuloListar::class),
    "transferencia_guardar" => \DI\create(TransferenciaGuardar::class),
    "transferencia_resultado" => \DI\create(TransferenciaResultado::class),
    "unidad_medida" => \DI\create(UnidadMedida::class),
    "userlevelpermissions" => \DI\create(Userlevelpermissions::class),
    "userlevels" => \DI\create(Userlevels::class),
    "usuario" => \DI\create(Usuario::class),
    "ventas_por_laboratorio" => \DI\create(VentasPorLaboratorio::class),
    "verificar_existencia" => \DI\create(VerificarExistencia::class),
    "verificar_existencia_update2" => \DI\create(VerificarExistenciaUpdate2::class),
    "verificar_venta" => \DI\create(VerificarVenta::class),
    "view_articulos" => \DI\create(ViewArticulos::class),
    "view_banco" => \DI\create(ViewBanco::class),
    "view_bultos" => \DI\create(ViewBultos::class),
    "view_costo_articulos_no_encontrados" => \DI\create(ViewCostoArticulosNoEncontrados::class),
    "view_entradas" => \DI\create(ViewEntradas::class),
    "view_entradas_salidas" => \DI\create(ViewEntradasSalidas::class),
    "view_existencia_almacen" => \DI\create(ViewExistenciaAlmacen::class),
    "view_factura_asesor" => \DI\create(ViewFacturaAsesor::class),
    "view_facturas_a_entregar" => \DI\create(ViewFacturasAEntregar::class),
    "view_facturas_cobranza" => \DI\create(ViewFacturasCobranza::class),
    "view_facturas_vencidas" => \DI\create(ViewFacturasVencidas::class),
    "view_pagos" => \DI\create(ViewPagos::class),
    "view_plancta" => \DI\create(ViewPlancta::class),
    "view_salidas" => \DI\create(ViewSalidas::class),
    "view_tarifa" => \DI\create(ViewTarifa::class),
    "view_tarifa_articulos_no_encontrados" => \DI\create(ViewTarifaArticulosNoEncontrados::class),
    "view_transferencias" => \DI\create(ViewTransferencias::class),
    "view_unidad_medida" => \DI\create(ViewUnidadMedida::class),
    "ya_fue_procesado" => \DI\create(YaFueProcesado::class),
    "banner" => \DI\create(Banner::class),
    "view_in_tdcaen" => \DI\create(ViewInTdcaen::class),
    "view_in_tdcfcc" => \DI\create(ViewInTdcfcc::class),
    "view_in_tdcnrp" => \DI\create(ViewInTdcnrp::class),
    "view_in_tdcpdc" => \DI\create(ViewInTdcpdc::class),
    "view_out_tdcasa" => \DI\create(ViewOutTdcasa::class),
    "view_out_tdcfcv" => \DI\create(ViewOutTdcfcv::class),
    "view_out_tdcnet" => \DI\create(ViewOutTdcnet::class),
    "view_out_tdcpdv" => \DI\create(ViewOutTdcpdv::class),
    "view_in" => \DI\create(ViewIn::class),
    "view_out" => \DI\create(ViewOut::class),
    "tdcpdc_add" => \DI\create(TdcpdcAdd::class),
    "tdcpdc_process" => \DI\create(TdcpdcProcess::class),
    "tdcnrp_add" => \DI\create(TdcnrpAdd::class),
    "tdcnrp_process" => \DI\create(TdcnrpProcess::class),
    "tdcfcc_add" => \DI\create(TdcfccAdd::class),
    "tdcfcc_process" => \DI\create(TdcfccProcess::class),
    "tdcaen_add" => \DI\create(TdcaenAdd::class),
    "tdcaen_process" => \DI\create(TdcaenProcess::class),
    "tdcnet_add" => \DI\create(TdcnetAdd::class),
    "tdcnet_process" => \DI\create(TdcnetProcess::class),
    "tdcfcv_add" => \DI\create(TdcfcvAdd::class),
    "tdcfcv_process" => \DI\create(TdcfcvProcess::class),
    "tdcasa_add" => \DI\create(TdcasaAdd::class),
    "tdcasa_process" => \DI\create(TdcasaProcess::class),
    "home_in_add" => \DI\create(HomeInAdd::class),
    "home_out_add" => \DI\create(HomeOutAdd::class),
    "factura_de_compra_copiar_como" => \DI\create(FacturaDeCompraCopiarComo::class),
    "nota_de_recepcion_copiar_como" => \DI\create(NotaDeRecepcionCopiarComo::class),
    "nota_de_recepcion_copiar_como_Eng" => \DI\create(NotaDeRecepcionCopiarComoEng::class),
    "tarifa_art" => \DI\create(TarifaArt::class),
    "tmp_art" => \DI\create(TmpArt::class),
    "tdcpdv_add" => \DI\create(TdcpdvAdd::class),
    "tdcpdv_process" => \DI\create(TdcpdvProcess::class),
    "cliente_dias_tmp" => \DI\create(ClienteDiasTmp::class),
    "pedidos_detalles_online_bitacora" => \DI\create(PedidosDetallesOnlineBitacora::class),
    "actualizar_costo_poderado" => \DI\create(ActualizarCostoPoderado::class),
    "usuario_master2" => \DI\create(UsuarioMaster2::class),

    // User table
    "usertable" => \DI\get("usuario"),
] + $definitions;
