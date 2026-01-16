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
 * Entity class for "cont_lotes_pagos_detalle" table
 */
#[Entity]
#[Table(name: "cont_lotes_pagos_detalle")]
class ContLotesPagosDetalle extends AbstractEntity
{
    #[Id]
    #[Column(name: "Id", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "cont_lotes_pago", type: "integer", nullable: true)]
    private ?int $contLotesPago;

    #[Column(name: "id_documento", type: "integer", nullable: true)]
    private ?int $idDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $tipodoc;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "monto_a_pagar", type: "decimal", nullable: true)]
    private ?string $montoAPagar;

    #[Column(name: "monto_pagdo", type: "decimal", nullable: true)]
    private ?string $montoPagdo;

    #[Column(type: "decimal", nullable: true)]
    private ?string $saldo;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getContLotesPago(): ?int
    {
        return $this->contLotesPago;
    }

    public function setContLotesPago(?int $value): static
    {
        $this->contLotesPago = $value;
        return $this;
    }

    public function getIdDocumento(): ?int
    {
        return $this->idDocumento;
    }

    public function setIdDocumento(?int $value): static
    {
        $this->idDocumento = $value;
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

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
        return $this;
    }

    public function getTipodoc(): ?string
    {
        return HtmlDecode($this->tipodoc);
    }

    public function setTipodoc(?string $value): static
    {
        $this->tipodoc = RemoveXss($value);
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

    public function getMontoAPagar(): ?string
    {
        return $this->montoAPagar;
    }

    public function setMontoAPagar(?string $value): static
    {
        $this->montoAPagar = $value;
        return $this;
    }

    public function getMontoPagdo(): ?string
    {
        return $this->montoPagdo;
    }

    public function setMontoPagdo(?string $value): static
    {
        $this->montoPagdo = $value;
        return $this;
    }

    public function getSaldo(): ?string
    {
        return $this->saldo;
    }

    public function setSaldo(?string $value): static
    {
        $this->saldo = $value;
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
}
