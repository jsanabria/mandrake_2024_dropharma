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
 * Entity class for "view_pagos" table
 */
#[Entity]
#[Table(name: "view_pagos")]
class ViewPago extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint")]
    private string $id;

    #[Column(type: "bigint", nullable: true)]
    private ?string $cliente;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "fecha_documento", type: "datetime", nullable: true)]
    private ?DateTime $fechaDocumento;

    #[Column(name: "base_imponible", type: "decimal", nullable: true)]
    private ?string $baseImponible;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(name: "tipo_pago", type: "string", nullable: true)]
    private ?string $tipoPago;

    #[Column(name: "fecha_pago", type: "date", nullable: true)]
    private ?DateTime $fechaPago;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "string", nullable: true)]
    private ?string $banco;

    #[Column(name: "banco_destino", type: "bigint", nullable: true)]
    private ?string $bancoDestino;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "decimal", nullable: true)]
    private ?string $monto;

    #[Column(name: "monto_bs", type: "decimal", nullable: true)]
    private ?string $montoBs;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(name: "tasa_usd", type: "decimal", nullable: true)]
    private ?string $tasaUsd;

    #[Column(name: "asesor_asignado", type: "string", nullable: true)]
    private ?string $asesorAsignado;

    #[Column(type: "string", nullable: true)]
    private ?string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "fecha_resgistro", type: "date", nullable: true)]
    private ?DateTime $fechaResgistro;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getCliente(): ?string
    {
        return $this->cliente;
    }

    public function setCliente(?string $value): static
    {
        $this->cliente = $value;
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

    public function getFechaDocumento(): ?DateTime
    {
        return $this->fechaDocumento;
    }

    public function setFechaDocumento(?DateTime $value): static
    {
        $this->fechaDocumento = $value;
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

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $value): static
    {
        $this->total = $value;
        return $this;
    }

    public function getTipoPago(): ?string
    {
        return HtmlDecode($this->tipoPago);
    }

    public function setTipoPago(?string $value): static
    {
        $this->tipoPago = RemoveXss($value);
        return $this;
    }

    public function getFechaPago(): ?DateTime
    {
        return $this->fechaPago;
    }

    public function setFechaPago(?DateTime $value): static
    {
        $this->fechaPago = $value;
        return $this;
    }

    public function getReferencia(): ?string
    {
        return HtmlDecode($this->referencia);
    }

    public function setReferencia(?string $value): static
    {
        $this->referencia = RemoveXss($value);
        return $this;
    }

    public function getBanco(): ?string
    {
        return HtmlDecode($this->banco);
    }

    public function setBanco(?string $value): static
    {
        $this->banco = RemoveXss($value);
        return $this;
    }

    public function getBancoDestino(): ?string
    {
        return $this->bancoDestino;
    }

    public function setBancoDestino(?string $value): static
    {
        $this->bancoDestino = $value;
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

    public function getMonto(): ?string
    {
        return $this->monto;
    }

    public function setMonto(?string $value): static
    {
        $this->monto = $value;
        return $this;
    }

    public function getMontoBs(): ?string
    {
        return $this->montoBs;
    }

    public function setMontoBs(?string $value): static
    {
        $this->montoBs = $value;
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

    public function getTasaUsd(): ?string
    {
        return $this->tasaUsd;
    }

    public function setTasaUsd(?string $value): static
    {
        $this->tasaUsd = $value;
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

    public function getNombre(): ?string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(?string $value): static
    {
        $this->nombre = RemoveXss($value);
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

    public function getFechaResgistro(): ?DateTime
    {
        return $this->fechaResgistro;
    }

    public function setFechaResgistro(?DateTime $value): static
    {
        $this->fechaResgistro = $value;
        return $this;
    }
}
