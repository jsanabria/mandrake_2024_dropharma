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
 * Entity class for "view_facturas_cobranza" table
 */
#[Entity]
#[Table(name: "view_facturas_cobranza")]
class ViewFacturasCobranza extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", nullable: true)]
    private ?int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(type: "integer", nullable: true)]
    private ?int $codcli;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "nro_nota", type: "string", nullable: true)]
    private ?string $nroNota;

    #[Column(type: "string", nullable: true)]
    private ?string $ciudad;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(name: "base_imponible", type: "decimal", nullable: true)]
    private ?string $baseImponible;

    #[Column(name: "alicuota_iva", type: "decimal", nullable: true)]
    private ?string $alicuotaIva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $iva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(name: "monto_pagado", type: "decimal", nullable: true)]
    private ?string $montoPagado;

    #[Column(type: "decimal", nullable: true)]
    private ?string $pendiente;

    #[Column(type: "decimal", nullable: true)]
    private ?string $pendiente2;

    #[Column(type: "decimal", nullable: true)]
    private ?string $pendiente3;

    #[Column(name: "tasa_dia", type: "decimal", nullable: true)]
    private ?string $tasaDia;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(name: "fecha_despacho", type: "datetime", nullable: true)]
    private ?DateTime $fechaDespacho;

    #[Column(name: "fecha_entrega", type: "date", nullable: true)]
    private ?DateTime $fechaEntrega;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    #[Column(name: "dias_transcurridos", type: "bigint", nullable: true)]
    private ?string $diasTranscurridos;

    #[Column(name: "dias_vencidos", type: "bigint", nullable: true)]
    private ?string $diasVencidos;

    #[Column(type: "string", nullable: true)]
    private ?string $pagado;

    #[Column(type: "integer", nullable: true)]
    private ?int $bultos;

    #[Column(name: "asesor_asignado", type: "string", nullable: true)]
    private ?string $asesorAsignado;

    #[Column(name: "id_documento_padre", type: "integer", nullable: true)]
    private ?int $idDocumentoPadre;

    public function __construct()
    {
        $this->pagado = "N";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $value): static
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

    public function getCodcli(): ?int
    {
        return $this->codcli;
    }

    public function setCodcli(?int $value): static
    {
        $this->codcli = $value;
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

    public function getNroDocumento(): ?string
    {
        return HtmlDecode($this->nroDocumento);
    }

    public function setNroDocumento(?string $value): static
    {
        $this->nroDocumento = RemoveXss($value);
        return $this;
    }

    public function getNroNota(): ?string
    {
        return HtmlDecode($this->nroNota);
    }

    public function setNroNota(?string $value): static
    {
        $this->nroNota = RemoveXss($value);
        return $this;
    }

    public function getCiudad(): ?string
    {
        return HtmlDecode($this->ciudad);
    }

    public function setCiudad(?string $value): static
    {
        $this->ciudad = RemoveXss($value);
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

    public function getBaseImponible(): ?string
    {
        return $this->baseImponible;
    }

    public function setBaseImponible(?string $value): static
    {
        $this->baseImponible = $value;
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

    public function getMontoPagado(): ?string
    {
        return $this->montoPagado;
    }

    public function setMontoPagado(?string $value): static
    {
        $this->montoPagado = $value;
        return $this;
    }

    public function getPendiente(): ?string
    {
        return $this->pendiente;
    }

    public function setPendiente(?string $value): static
    {
        $this->pendiente = $value;
        return $this;
    }

    public function getPendiente2(): ?string
    {
        return $this->pendiente2;
    }

    public function setPendiente2(?string $value): static
    {
        $this->pendiente2 = $value;
        return $this;
    }

    public function getPendiente3(): ?string
    {
        return $this->pendiente3;
    }

    public function setPendiente3(?string $value): static
    {
        $this->pendiente3 = $value;
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

    public function getFechaDespacho(): ?DateTime
    {
        return $this->fechaDespacho;
    }

    public function setFechaDespacho(?DateTime $value): static
    {
        $this->fechaDespacho = $value;
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

    public function getDiasCredito(): ?int
    {
        return $this->diasCredito;
    }

    public function setDiasCredito(?int $value): static
    {
        $this->diasCredito = $value;
        return $this;
    }

    public function getDiasTranscurridos(): ?string
    {
        return $this->diasTranscurridos;
    }

    public function setDiasTranscurridos(?string $value): static
    {
        $this->diasTranscurridos = $value;
        return $this;
    }

    public function getDiasVencidos(): ?string
    {
        return $this->diasVencidos;
    }

    public function setDiasVencidos(?string $value): static
    {
        $this->diasVencidos = $value;
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

    public function getAsesorAsignado(): ?string
    {
        return HtmlDecode($this->asesorAsignado);
    }

    public function setAsesorAsignado(?string $value): static
    {
        $this->asesorAsignado = RemoveXss($value);
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
}
