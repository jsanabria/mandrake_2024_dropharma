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
 * Entity class for "view_salidas" table
 */
#[Entity]
#[Table(name: "view_salidas")]
class ViewSalida extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "nombre_documento", type: "string", nullable: true)]
    private ?string $nombreDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(name: "nombre_cliente", type: "string", nullable: true)]
    private ?string $nombreCliente;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string", nullable: true)]
    private ?string $consignacion;

    #[Column(type: "string")]
    private string $cerrado;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(name: "tasa_dia", type: "decimal", nullable: true)]
    private ?string $tasaDia;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(type: "integer", nullable: true)]
    private ?int $unidades;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    public function __construct()
    {
        $this->consignacion = "N";
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

    public function getNombreDocumento(): ?string
    {
        return HtmlDecode($this->nombreDocumento);
    }

    public function setNombreDocumento(?string $value): static
    {
        $this->nombreDocumento = RemoveXss($value);
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

    public function getNombreCliente(): ?string
    {
        return HtmlDecode($this->nombreCliente);
    }

    public function setNombreCliente(?string $value): static
    {
        $this->nombreCliente = RemoveXss($value);
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

    public function getFecha(): ?string
    {
        return HtmlDecode($this->fecha);
    }

    public function setFecha(?string $value): static
    {
        $this->fecha = RemoveXss($value);
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

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
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

    public function getCerrado(): string
    {
        return HtmlDecode($this->cerrado);
    }

    public function setCerrado(string $value): static
    {
        $this->cerrado = RemoveXss($value);
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

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(?int $value): static
    {
        $this->unidades = $value;
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
}
