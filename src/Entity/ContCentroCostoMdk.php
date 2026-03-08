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
 * Entity class for "cont_centro_costo_mdk" table
 */
#[Entity]
#[Table(name: "cont_centro_costo_mdk")]
class ContCentroCostoMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "string", unique: true)]
    private string $codigo;

    #[Column(type: "string")]
    private string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(name: "centro_padre_id", type: "bigint", nullable: true)]
    private ?string $centroPadreId;

    #[Column(type: "integer")]
    private int $nivel;

    #[Column(type: "boolean")]
    private bool $estado;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->nivel = 1;
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

    public function getCodigo(): string
    {
        return HtmlDecode($this->codigo);
    }

    public function setCodigo(string $value): static
    {
        $this->codigo = RemoveXss($value);
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

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
        return $this;
    }

    public function getCentroPadreId(): ?string
    {
        return $this->centroPadreId;
    }

    public function setCentroPadreId(?string $value): static
    {
        $this->centroPadreId = $value;
        return $this;
    }

    public function getNivel(): int
    {
        return $this->nivel;
    }

    public function setNivel(int $value): static
    {
        $this->nivel = $value;
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
