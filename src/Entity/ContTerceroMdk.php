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
 * Entity class for "cont_tercero_mdk" table
 */
#[Entity]
#[Table(name: "cont_tercero_mdk")]
class ContTerceroMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "string")]
    private string $tipo;

    #[Column(name: "origen_tabla", type: "string")]
    private string $origenTabla;

    #[Column(name: "origen_id", type: "bigint")]
    private string $origenId;

    #[Column(type: "string")]
    private string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $rif;

    #[Column(type: "boolean")]
    private bool $estado;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->estado = true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $value): static
    {
        if (!in_array($value, ["CLIENTE", "PROVEEDOR", "AMBOS", "OTRO"])) {
            throw new \InvalidArgumentException("Invalid 'tipo' value");
        }
        $this->tipo = $value;
        return $this;
    }

    public function getOrigenTabla(): string
    {
        return HtmlDecode($this->origenTabla);
    }

    public function setOrigenTabla(string $value): static
    {
        $this->origenTabla = RemoveXss($value);
        return $this;
    }

    public function getOrigenId(): string
    {
        return $this->origenId;
    }

    public function setOrigenId(string $value): static
    {
        $this->origenId = $value;
        return $this;
    }

    public function getNombre(): string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(string $value): static
    {
        $this->nombre = RemoveXss($value);
        return $this;
    }

    public function getRif(): ?string
    {
        return HtmlDecode($this->rif);
    }

    public function setRif(?string $value): static
    {
        $this->rif = RemoveXss($value);
        return $this;
    }

    public function getEstado(): bool
    {
        return $this->estado;
    }

    public function setEstado(bool $value): static
    {
        $this->estado = $value;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $value): static
    {
        $this->createdAt = $value;
        return $this;
    }
}
