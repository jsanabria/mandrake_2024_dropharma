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
 * Entity class for "view_facturas_vencidas" table
 */
#[Entity]
#[Table(name: "view_facturas_vencidas")]
class ViewFacturasVencida extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $codcli;

    #[Column(type: "string", nullable: true)]
    private ?string $cliente;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(name: "fecha_entrega", type: "date", nullable: true)]
    private ?DateTime $fechaEntrega;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    #[Column(name: "dias_vencidos", type: "bigint", nullable: true)]
    private ?string $diasVencidos;

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

    public function getCodcli(): ?int
    {
        return $this->codcli;
    }

    public function setCodcli(?int $value): static
    {
        $this->codcli = $value;
        return $this;
    }

    public function getCliente(): ?string
    {
        return HtmlDecode($this->cliente);
    }

    public function setCliente(?string $value): static
    {
        $this->cliente = RemoveXss($value);
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

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $value): static
    {
        $this->total = $value;
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

    public function getDiasVencidos(): ?string
    {
        return $this->diasVencidos;
    }

    public function setDiasVencidos(?string $value): static
    {
        $this->diasVencidos = $value;
        return $this;
    }
}
