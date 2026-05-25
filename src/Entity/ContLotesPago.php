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
 * Entity class for "cont_lotes_pagos" table
 */
#[Entity]
#[Table(name: "cont_lotes_pagos")]
class ContLotesPago extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $procesado;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(name: "fecha_registro", type: "datetime", nullable: true)]
    private ?DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $usuario;

    #[Column(type: "integer", nullable: true)]
    private ?int $banco;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "string", nullable: true)]
    private ?string $comprobante;

    public function __construct()
    {
        $this->comprobante = "N";
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

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?DateTime $value): static
    {
        $this->fecha = $value;
        return $this;
    }

    public function getProcesado(): ?string
    {
        return $this->procesado;
    }

    public function setProcesado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'procesado' value");
        }
        $this->procesado = $value;
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

    public function getFechaRegistro(): ?DateTime
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(?DateTime $value): static
    {
        $this->fechaRegistro = $value;
        return $this;
    }

    public function getUsuario(): ?string
    {
        return HtmlDecode($this->usuario);
    }

    public function setUsuario(?string $value): static
    {
        $this->usuario = RemoveXss($value);
        return $this;
    }

    public function getBanco(): ?int
    {
        return $this->banco;
    }

    public function setBanco(?int $value): static
    {
        $this->banco = $value;
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

    public function getMoneda(): ?string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(?string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }

    public function getComprobante(): ?string
    {
        return $this->comprobante;
    }

    public function setComprobante(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'comprobante' value");
        }
        $this->comprobante = $value;
        return $this;
    }
}
