<?php

namespace PHPMaker2024\mandrake\Entity;

use DateTime;
use DateTimeImmutable;
use DateInterval;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\DBAL\Types\Types;
use PHPMaker2024\mandrake\AbstractEntity;
use PHPMaker2024\mandrake\AdvancedSecurity;
use PHPMaker2024\mandrake\UserProfile;
use function PHPMaker2024\mandrake\Config;
use function PHPMaker2024\mandrake\EntityManager;
use function PHPMaker2024\mandrake\RemoveXss;
use function PHPMaker2024\mandrake\HtmlDecode;
use function PHPMaker2024\mandrake\EncryptPassword;

/**
 * Entity class for "view_in_tdcfcc" table
 */
#[Entity]
#[Table(name: "view_in_tdcfcc")]
class ViewInTdcfcc extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "nro_control", type: "string", nullable: true)]
    private ?string $nroControl;

    #[Column(name: "fecha_libro_compra", type: "date", nullable: true)]
    private ?DateTime $fechaLibroCompra;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "doc_afectado", type: "string", nullable: true)]
    private ?string $docAfectado;

    #[Column(type: "string", nullable: true)]
    private ?string $almacen;

    #[Column(name: "monto_total", type: "decimal", nullable: true)]
    private ?string $montoTotal;

    #[Column(name: "alicuota_iva", type: "decimal", nullable: true)]
    private ?string $alicuotaIva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $iva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "integer", nullable: true)]
    private ?int $unidades;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

    #[Column(name: "id_documento_padre", type: "integer", nullable: true)]
    private ?int $idDocumentoPadre;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "decimal", nullable: true)]
    private ?string $descuento;

    #[Column(type: "string", nullable: true)]
    private ?string $consignacion;

    #[Column(name: "consignacion_reportada", type: "string", nullable: true)]
    private ?string $consignacionReportada;

    #[Column(name: "aplica_retencion", type: "string", nullable: true)]
    private ?string $aplicaRetencion;

    #[Column(name: "ret_iva", type: "decimal", nullable: true)]
    private ?string $retIva;

    #[Column(name: "ref_iva", type: "string", nullable: true)]
    private ?string $refIva;

    #[Column(name: "ret_islr", type: "decimal", nullable: true)]
    private ?string $retIslr;

    #[Column(name: "ref_islr", type: "string", nullable: true)]
    private ?string $refIslr;

    #[Column(name: "ret_municipal", type: "decimal", nullable: true)]
    private ?string $retMunicipal;

    #[Column(name: "ref_municipal", type: "string", nullable: true)]
    private ?string $refMunicipal;

    #[Column(name: "monto_pagar", type: "decimal", nullable: true)]
    private ?string $montoPagar;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(name: "tipo_iva", type: "string", nullable: true)]
    private ?string $tipoIva;

    #[Column(name: "tipo_islr", type: "string", nullable: true)]
    private ?string $tipoIslr;

    #[Column(name: "tipo_municipal", type: "string", nullable: true)]
    private ?string $tipoMunicipal;

    #[Column(type: "decimal", nullable: true)]
    private ?string $sustraendo;

    #[Column(name: "fecha_registro_retenciones", type: "date", nullable: true)]
    private ?DateTime $fechaRegistroRetenciones;

    #[Column(name: "tasa_dia", type: "decimal", nullable: true)]
    private ?string $tasaDia;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(type: "string", nullable: true)]
    private ?string $cerrado;

    #[Column(name: "archivo_pedido", type: "string", nullable: true)]
    private ?string $archivoPedido;

    public function __construct()
    {
        $this->consignacion = "N";
        $this->consignacionReportada = "N";
        $this->cerrado = "N";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
        return $this;
    }

    public function getDocumento(): ?string
    {
        return HtmlDecode($this->documento);
    }

    public function setDocumento(?string $value): static
    {
        $this->documento = RemoveXss($value);
        return $this;
    }

    public function getNroDocumento(): ?string
    {
        return HtmlDecode($this->nroDocumento);
    }

    public function setNroDocumento(?string $value): static
    {
        $this->nroDocumento = RemoveXss($value);
        return $this;
    }

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?DateTime $value): static
    {
        $this->fecha = $value;
        return $this;
    }

    public function getNroControl(): ?string
    {
        return HtmlDecode($this->nroControl);
    }

    public function setNroControl(?string $value): static
    {
        $this->nroControl = RemoveXss($value);
        return $this;
    }

    public function getFechaLibroCompra(): ?DateTime
    {
        return $this->fechaLibroCompra;
    }

    public function setFechaLibroCompra(?DateTime $value): static
    {
        $this->fechaLibroCompra = $value;
        return $this;
    }

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
        return $this;
    }

    public function getDocAfectado(): ?string
    {
        return HtmlDecode($this->docAfectado);
    }

    public function setDocAfectado(?string $value): static
    {
        $this->docAfectado = RemoveXss($value);
        return $this;
    }

    public function getAlmacen(): ?string
    {
        return HtmlDecode($this->almacen);
    }

    public function setAlmacen(?string $value): static
    {
        $this->almacen = RemoveXss($value);
        return $this;
    }

    public function getMontoTotal(): ?string
    {
        return $this->montoTotal;
    }

    public function setMontoTotal(?string $value): static
    {
        $this->montoTotal = $value;
        return $this;
    }

    public function getAlicuotaIva(): ?string
    {
        return $this->alicuotaIva;
    }

    public function setAlicuotaIva(?string $value): static
    {
        $this->alicuotaIva = $value;
        return $this;
    }

    public function getIva(): ?string
    {
        return $this->iva;
    }

    public function setIva(?string $value): static
    {
        $this->iva = $value;
        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $value): static
    {
        $this->total = $value;
        return $this;
    }

    public function getMoneda(): ?string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(?string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }

    public function getNota(): ?string
    {
        return HtmlDecode($this->nota);
    }

    public function setNota(?string $value): static
    {
        $this->nota = RemoveXss($value);
        return $this;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(?int $value): static
    {
        $this->unidades = $value;
        return $this;
    }

    public function getEstatus(): ?string
    {
        return HtmlDecode($this->estatus);
    }

    public function setEstatus(?string $value): static
    {
        $this->estatus = RemoveXss($value);
        return $this;
    }

    public function getIdDocumentoPadre(): ?int
    {
        return $this->idDocumentoPadre;
    }

    public function setIdDocumentoPadre(?int $value): static
    {
        $this->idDocumentoPadre = $value;
        return $this;
    }

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $value): static
    {
        $this->descuento = $value;
        return $this;
    }

    public function getConsignacion(): ?string
    {
        return $this->consignacion;
    }

    public function setConsignacion(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'consignacion' value");
        }
        $this->consignacion = $value;
        return $this;
    }

    public function getConsignacionReportada(): ?string
    {
        return $this->consignacionReportada;
    }

    public function setConsignacionReportada(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'consignacion_reportada' value");
        }
        $this->consignacionReportada = $value;
        return $this;
    }

    public function getAplicaRetencion(): ?string
    {
        return $this->aplicaRetencion;
    }

    public function setAplicaRetencion(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'aplica_retencion' value");
        }
        $this->aplicaRetencion = $value;
        return $this;
    }

    public function getRetIva(): ?string
    {
        return $this->retIva;
    }

    public function setRetIva(?string $value): static
    {
        $this->retIva = $value;
        return $this;
    }

    public function getRefIva(): ?string
    {
        return HtmlDecode($this->refIva);
    }

    public function setRefIva(?string $value): static
    {
        $this->refIva = RemoveXss($value);
        return $this;
    }

    public function getRetIslr(): ?string
    {
        return $this->retIslr;
    }

    public function setRetIslr(?string $value): static
    {
        $this->retIslr = $value;
        return $this;
    }

    public function getRefIslr(): ?string
    {
        return HtmlDecode($this->refIslr);
    }

    public function setRefIslr(?string $value): static
    {
        $this->refIslr = RemoveXss($value);
        return $this;
    }

    public function getRetMunicipal(): ?string
    {
        return $this->retMunicipal;
    }

    public function setRetMunicipal(?string $value): static
    {
        $this->retMunicipal = $value;
        return $this;
    }

    public function getRefMunicipal(): ?string
    {
        return HtmlDecode($this->refMunicipal);
    }

    public function setRefMunicipal(?string $value): static
    {
        $this->refMunicipal = RemoveXss($value);
        return $this;
    }

    public function getMontoPagar(): ?string
    {
        return $this->montoPagar;
    }

    public function setMontoPagar(?string $value): static
    {
        $this->montoPagar = $value;
        return $this;
    }

    public function getComprobante(): ?int
    {
        return $this->comprobante;
    }

    public function setComprobante(?int $value): static
    {
        $this->comprobante = $value;
        return $this;
    }

    public function getTipoIva(): ?string
    {
        return HtmlDecode($this->tipoIva);
    }

    public function setTipoIva(?string $value): static
    {
        $this->tipoIva = RemoveXss($value);
        return $this;
    }

    public function getTipoIslr(): ?string
    {
        return HtmlDecode($this->tipoIslr);
    }

    public function setTipoIslr(?string $value): static
    {
        $this->tipoIslr = RemoveXss($value);
        return $this;
    }

    public function getTipoMunicipal(): ?string
    {
        return HtmlDecode($this->tipoMunicipal);
    }

    public function setTipoMunicipal(?string $value): static
    {
        $this->tipoMunicipal = RemoveXss($value);
        return $this;
    }

    public function getSustraendo(): ?string
    {
        return $this->sustraendo;
    }

    public function setSustraendo(?string $value): static
    {
        $this->sustraendo = $value;
        return $this;
    }

    public function getFechaRegistroRetenciones(): ?DateTime
    {
        return $this->fechaRegistroRetenciones;
    }

    public function setFechaRegistroRetenciones(?DateTime $value): static
    {
        $this->fechaRegistroRetenciones = $value;
        return $this;
    }

    public function getTasaDia(): ?string
    {
        return $this->tasaDia;
    }

    public function setTasaDia(?string $value): static
    {
        $this->tasaDia = $value;
        return $this;
    }

    public function getMontoUsd(): ?string
    {
        return $this->montoUsd;
    }

    public function setMontoUsd(?string $value): static
    {
        $this->montoUsd = $value;
        return $this;
    }

    public function getCerrado(): ?string
    {
        return $this->cerrado;
    }

    public function setCerrado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'cerrado' value");
        }
        $this->cerrado = $value;
        return $this;
    }

    public function getArchivoPedido(): ?string
    {
        return HtmlDecode($this->archivoPedido);
    }

    public function setArchivoPedido(?string $value): static
    {
        $this->archivoPedido = RemoveXss($value);
        return $this;
    }
}
