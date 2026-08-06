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
 * Entity class for "view_cuentas_por_pagar_resumen" table
 */
#[Entity]
#[Table(name: "view_cuentas_por_pagar_resumen")]
class ViewCuentasPorPagarResuman extends AbstractEntity
{
    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "proveedor_rif", type: "string", nullable: true)]
    private ?string $proveedorRif;

    #[Column(name: "proveedor_nombre", type: "string", nullable: true)]
    private ?string $proveedorNombre;

    #[Column(name: "cantidad_documentos", type: "bigint")]
    private string $cantidadDocumentos;

    #[Column(name: "documentos_pendientes", type: "decimal", nullable: true)]
    private ?string $documentosPendientes;

    #[Column(name: "documentos_parciales", type: "decimal", nullable: true)]
    private ?string $documentosParciales;

    #[Column(name: "monto_documentos_bs", type: "decimal", nullable: true)]
    private ?string $montoDocumentosBs;

    #[Column(name: "monto_documentos_usd", type: "decimal", nullable: true)]
    private ?string $montoDocumentosUsd;

    #[Column(name: "total_pagado_bs", type: "decimal", nullable: true)]
    private ?string $totalPagadoBs;

    #[Column(name: "total_pagado_usd", type: "decimal", nullable: true)]
    private ?string $totalPagadoUsd;

    #[Column(name: "saldo_bs", type: "decimal", nullable: true)]
    private ?string $saldoBs;

    #[Column(name: "saldo_usd", type: "decimal", nullable: true)]
    private ?string $saldoUsd;

    public function __construct()
    {
        $this->cantidadDocumentos = "0";
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

    public function getProveedorRif(): ?string
    {
        return HtmlDecode($this->proveedorRif);
    }

    public function setProveedorRif(?string $value): static
    {
        $this->proveedorRif = RemoveXss($value);
        return $this;
    }

    public function getProveedorNombre(): ?string
    {
        return HtmlDecode($this->proveedorNombre);
    }

    public function setProveedorNombre(?string $value): static
    {
        $this->proveedorNombre = RemoveXss($value);
        return $this;
    }

    public function getCantidadDocumentos(): string
    {
        return $this->cantidadDocumentos;
    }

    public function setCantidadDocumentos(string $value): static
    {
        $this->cantidadDocumentos = $value;
        return $this;
    }

    public function getDocumentosPendientes(): ?string
    {
        return $this->documentosPendientes;
    }

    public function setDocumentosPendientes(?string $value): static
    {
        $this->documentosPendientes = $value;
        return $this;
    }

    public function getDocumentosParciales(): ?string
    {
        return $this->documentosParciales;
    }

    public function setDocumentosParciales(?string $value): static
    {
        $this->documentosParciales = $value;
        return $this;
    }

    public function getMontoDocumentosBs(): ?string
    {
        return $this->montoDocumentosBs;
    }

    public function setMontoDocumentosBs(?string $value): static
    {
        $this->montoDocumentosBs = $value;
        return $this;
    }

    public function getMontoDocumentosUsd(): ?string
    {
        return $this->montoDocumentosUsd;
    }

    public function setMontoDocumentosUsd(?string $value): static
    {
        $this->montoDocumentosUsd = $value;
        return $this;
    }

    public function getTotalPagadoBs(): ?string
    {
        return $this->totalPagadoBs;
    }

    public function setTotalPagadoBs(?string $value): static
    {
        $this->totalPagadoBs = $value;
        return $this;
    }

    public function getTotalPagadoUsd(): ?string
    {
        return $this->totalPagadoUsd;
    }

    public function setTotalPagadoUsd(?string $value): static
    {
        $this->totalPagadoUsd = $value;
        return $this;
    }

    public function getSaldoBs(): ?string
    {
        return $this->saldoBs;
    }

    public function setSaldoBs(?string $value): static
    {
        $this->saldoBs = $value;
        return $this;
    }

    public function getSaldoUsd(): ?string
    {
        return $this->saldoUsd;
    }

    public function setSaldoUsd(?string $value): static
    {
        $this->saldoUsd = $value;
        return $this;
    }
}
