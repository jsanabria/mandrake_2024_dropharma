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
 * Entity class for "banner" table
 */
#[Entity]
#[Table(name: "banner")]
class Banner extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(type: "string", nullable: true)]
    private ?string $titulo;

    #[Column(type: "string", nullable: true)]
    private ?string $subtitulo;

    #[Column(type: "string", nullable: true)]
    private ?string $imagen;

    #[Column(type: "boolean", nullable: true)]
    private ?bool $activo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $value): static
    {
        if (!in_array($value, ["1", "2", "3"])) {
            throw new \InvalidArgumentException("Invalid 'tipo' value");
        }
        $this->tipo = $value;
        return $this;
    }

    public function getTitulo(): ?string
    {
        return HtmlDecode($this->titulo);
    }

    public function setTitulo(?string $value): static
    {
        $this->titulo = RemoveXss($value);
        return $this;
    }

    public function getSubtitulo(): ?string
    {
        return HtmlDecode($this->subtitulo);
    }

    public function setSubtitulo(?string $value): static
    {
        $this->subtitulo = RemoveXss($value);
        return $this;
    }

    public function getImagen(): ?string
    {
        return HtmlDecode($this->imagen);
    }

    public function setImagen(?string $value): static
    {
        $this->imagen = RemoveXss($value);
        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $value): static
    {
        $this->activo = $value;
        return $this;
    }
}
