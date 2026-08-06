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
 * Entity class for "view_cuentas_por_cobrar_resumen" table
 */
#[Entity]
#[Table(name: "view_cuentas_por_cobrar_resumen")]
class ViewCuentasPorCobrarResuman extends AbstractEntity
{
    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(name: "cliente_rif", type: "string", nullable: true)]
    private ?string $clienteRif;

    #[Column(name: "cliente_nombre", type: "string", nullable: true)]
    private ?string $clienteNombre;

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

    #[Column(name: "total_cobrado_bs", type: "decimal", nullable: true)]
    private ?string $totalCobradoBs;

    #[Column(name: "total_cobrado_usd", type: "decimal", nullable: true)]
    private ?string $totalCobradoUsd;

    #[Column(name: "saldo_bs", type: "decimal", nullable: true)]
    private ?string $saldoBs;

    #[Column(name: "saldo_usd", type: "decimal", nullable: true)]
    private ?string $saldoUsd;

    public function __construct()
    {
        $this->cantidadDocumentos = "0";
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

    public function getClienteRif(): ?string
    {
        return HtmlDecode($this->clienteRif);
    }

    public function setClienteRif(?string $value): static
    {
        $this->clienteRif = RemoveXss($value);
        return $this;
    }

    public function getClienteNombre(): ?string
    {
        return HtmlDecode($this->clienteNombre);
    }

    public function setClienteNombre(?string $value): static
    {
        $this->clienteNombre = RemoveXss($value);
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

    public function getTotalCobradoBs(): ?string
    {
        return $this->totalCobradoBs;
    }

    public function setTotalCobradoBs(?string $value): static
    {
        $this->totalCobradoBs = $value;
        return $this;
    }

    public function getTotalCobradoUsd(): ?string
    {
        return $this->totalCobradoUsd;
    }

    public function setTotalCobradoUsd(?string $value): static
    {
        $this->totalCobradoUsd = $value;
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
