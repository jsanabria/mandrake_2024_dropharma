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
 * Entity class for "view_out_tdcnet" table
 */
#[Entity]
#[Table(name: "view_out_tdcnet")]
class ViewOutTdcnet extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "nro_control", type: "string", nullable: true)]
    private ?string $nroControl;

    #[Column(name: "monto_total", type: "decimal", nullable: true)]
    private ?string $montoTotal;

    #[Column(name: "alicuota_iva", type: "decimal", nullable: true)]
    private ?string $alicuotaIva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $iva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(name: "lista_pedido", type: "string", nullable: true)]
    private ?string $listaPedido;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "integer", nullable: true)]
    private ?int $unidades;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

    #[Column(name: "id_documento_padre", type: "integer", nullable: true)]
    private ?int $idDocumentoPadre;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "string", nullable: true)]
    private ?string $asesor;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(name: "tasa_dia", type: "decimal", nullable: true)]
    private ?string $tasaDia;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    #[Column(type: "string", nullable: true)]
    private ?string $entregado;

    #[Column(name: "fecha_entrega", type: "date", nullable: true)]
    private ?DateTime $fechaEntrega;

    #[Column(type: "string", nullable: true)]
    private ?string $pagado;

    #[Column(type: "integer", nullable: true)]
    private ?int $bultos;

    #[Column(name: "fecha_bultos", type: "datetime", nullable: true)]
    private ?DateTime $fechaBultos;

    #[Column(name: "user_bultos", type: "string", nullable: true)]
    private ?string $userBultos;

    #[Column(name: "fecha_despacho", type: "datetime", nullable: true)]
    private ?DateTime $fechaDespacho;

    #[Column(name: "user_despacho", type: "string", nullable: true)]
    private ?string $userDespacho;

    #[Column(type: "string", nullable: true)]
    private ?string $consignacion;

    #[Column(type: "decimal", nullable: true)]
    private ?string $descuento;

    #[Column(type: "decimal", nullable: true)]
    private ?string $descuento2;

    #[Column(name: "monto_sin_descuento", type: "decimal", nullable: true)]
    private ?string $montoSinDescuento;

    #[Column(type: "string", nullable: true)]
    private ?string $factura;

    #[Column(name: "ci_rif", type: "string", nullable: true)]
    private ?string $ciRif;

    #[Column(type: "string", nullable: true)]
    private ?string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $direccion;

    #[Column(type: "string", nullable: true)]
    private ?string $telefono;

    #[Column(type: "string", nullable: true)]
    private ?string $email;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(name: "doc_afectado", type: "string", nullable: true)]
    private ?string $docAfectado;

    #[Column(name: "nro_despacho", type: "string", nullable: true)]
    private ?string $nroDespacho;

    #[Column(type: "string", nullable: true)]
    private ?string $cerrado;

    #[Column(name: "asesor_asignado", type: "string", nullable: true)]
    private ?string $asesorAsignado;

    #[Column(name: "tasa_indexada", type: "decimal", nullable: true)]
    private ?string $tasaIndexada;

    #[Column(name: "id_documento_padre_nd", type: "integer", nullable: true)]
    private ?int $idDocumentoPadreNd;

    #[Column(name: "archivo_pedido", type: "string", nullable: true)]
    private ?string $archivoPedido;

    #[Column(type: "string", nullable: true)]
    private ?string $checker;

    #[Column(name: "checker_date", type: "datetime", nullable: true)]
    private ?DateTime $checkerDate;

    #[Column(type: "string", nullable: true)]
    private ?string $packer;

    #[Column(name: "packer_date", type: "datetime", nullable: true)]
    private ?DateTime $packerDate;

    #[Column(type: "string", nullable: true)]
    private ?string $fotos;

    public function __construct()
    {
        $this->entregado = "N";
        $this->pagado = "N";
        $this->bultos = 0;
        $this->consignacion = "N";
        $this->factura = "N";
        $this->activo = "S";
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

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
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

    public function getCliente(): ?int
    {
        return $this->cliente;
    }

    public function setCliente(?int $value): static
    {
        $this->cliente = $value;
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

    public function getNroControl(): ?string
    {
        return HtmlDecode($this->nroControl);
    }

    public function setNroControl(?string $value): static
    {
        $this->nroControl = RemoveXss($value);
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

    public function getListaPedido(): ?string
    {
        return HtmlDecode($this->listaPedido);
    }

    public function setListaPedido(?string $value): static
    {
        $this->listaPedido = RemoveXss($value);
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

    public function getMoneda(): ?string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(?string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }

    public function getAsesor(): ?string
    {
        return HtmlDecode($this->asesor);
    }

    public function setAsesor(?string $value): static
    {
        $this->asesor = RemoveXss($value);
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

    public function getDiasCredito(): ?int
    {
        return $this->diasCredito;
    }

    public function setDiasCredito(?int $value): static
    {
        $this->diasCredito = $value;
        return $this;
    }

    public function getEntregado(): ?string
    {
        return $this->entregado;
    }

    public function setEntregado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'entregado' value");
        }
        $this->entregado = $value;
        return $this;
    }

    public function getFechaEntrega(): ?DateTime
    {
        return $this->fechaEntrega;
    }

    public function setFechaEntrega(?DateTime $value): static
    {
        $this->fechaEntrega = $value;
        return $this;
    }

    public function getPagado(): ?string
    {
        return $this->pagado;
    }

    public function setPagado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'pagado' value");
        }
        $this->pagado = $value;
        return $this;
    }

    public function getBultos(): ?int
    {
        return $this->bultos;
    }

    public function setBultos(?int $value): static
    {
        $this->bultos = $value;
        return $this;
    }

    public function getFechaBultos(): ?DateTime
    {
        return $this->fechaBultos;
    }

    public function setFechaBultos(?DateTime $value): static
    {
        $this->fechaBultos = $value;
        return $this;
    }

    public function getUserBultos(): ?string
    {
        return HtmlDecode($this->userBultos);
    }

    public function setUserBultos(?string $value): static
    {
        $this->userBultos = RemoveXss($value);
        return $this;
    }

    public function getFechaDespacho(): ?DateTime
    {
        return $this->fechaDespacho;
    }

    public function setFechaDespacho(?DateTime $value): static
    {
        $this->fechaDespacho = $value;
        return $this;
    }

    public function getUserDespacho(): ?string
    {
        return HtmlDecode($this->userDespacho);
    }

    public function setUserDespacho(?string $value): static
    {
        $this->userDespacho = RemoveXss($value);
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

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $value): static
    {
        $this->descuento = $value;
        return $this;
    }

    public function getDescuento2(): ?string
    {
        return $this->descuento2;
    }

    public function setDescuento2(?string $value): static
    {
        $this->descuento2 = $value;
        return $this;
    }

    public function getMontoSinDescuento(): ?string
    {
        return $this->montoSinDescuento;
    }

    public function setMontoSinDescuento(?string $value): static
    {
        $this->montoSinDescuento = $value;
        return $this;
    }

    public function getFactura(): ?string
    {
        return $this->factura;
    }

    public function setFactura(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'factura' value");
        }
        $this->factura = $value;
        return $this;
    }

    public function getCiRif(): ?string
    {
        return HtmlDecode($this->ciRif);
    }

    public function setCiRif(?string $value): static
    {
        $this->ciRif = RemoveXss($value);
        return $this;
    }

    public function getNombre(): ?string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(?string $value): static
    {
        $this->nombre = RemoveXss($value);
        return $this;
    }

    public function getDireccion(): ?string
    {
        return HtmlDecode($this->direccion);
    }

    public function setDireccion(?string $value): static
    {
        $this->direccion = RemoveXss($value);
        return $this;
    }

    public function getTelefono(): ?string
    {
        return HtmlDecode($this->telefono);
    }

    public function setTelefono(?string $value): static
    {
        $this->telefono = RemoveXss($value);
        return $this;
    }

    public function getEmail(): ?string
    {
        return HtmlDecode($this->email);
    }

    public function setEmail(?string $value): static
    {
        $this->email = RemoveXss($value);
        return $this;
    }

    public function getActivo(): ?string
    {
        return $this->activo;
    }

    public function setActivo(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'activo' value");
        }
        $this->activo = $value;
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

    public function getDocAfectado(): ?string
    {
        return HtmlDecode($this->docAfectado);
    }

    public function setDocAfectado(?string $value): static
    {
        $this->docAfectado = RemoveXss($value);
        return $this;
    }

    public function getNroDespacho(): ?string
    {
        return HtmlDecode($this->nroDespacho);
    }

    public function setNroDespacho(?string $value): static
    {
        $this->nroDespacho = RemoveXss($value);
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

    public function getAsesorAsignado(): ?string
    {
        return HtmlDecode($this->asesorAsignado);
    }

    public function setAsesorAsignado(?string $value): static
    {
        $this->asesorAsignado = RemoveXss($value);
        return $this;
    }

    public function getTasaIndexada(): ?string
    {
        return $this->tasaIndexada;
    }

    public function setTasaIndexada(?string $value): static
    {
        $this->tasaIndexada = $value;
        return $this;
    }

    public function getIdDocumentoPadreNd(): ?int
    {
        return $this->idDocumentoPadreNd;
    }

    public function setIdDocumentoPadreNd(?int $value): static
    {
        $this->idDocumentoPadreNd = $value;
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

    public function getChecker(): ?string
    {
        return HtmlDecode($this->checker);
    }

    public function setChecker(?string $value): static
    {
        $this->checker = RemoveXss($value);
        return $this;
    }

    public function getCheckerDate(): ?DateTime
    {
        return $this->checkerDate;
    }

    public function setCheckerDate(?DateTime $value): static
    {
        $this->checkerDate = $value;
        return $this;
    }

    public function getPacker(): ?string
    {
        return HtmlDecode($this->packer);
    }

    public function setPacker(?string $value): static
    {
        $this->packer = RemoveXss($value);
        return $this;
    }

    public function getPackerDate(): ?DateTime
    {
        return $this->packerDate;
    }

    public function setPackerDate(?DateTime $value): static
    {
        $this->packerDate = $value;
        return $this;
    }

    public function getFotos(): ?string
    {
        return HtmlDecode($this->fotos);
    }

    public function setFotos(?string $value): static
    {
        $this->fotos = RemoveXss($value);
        return $this;
    }
}
