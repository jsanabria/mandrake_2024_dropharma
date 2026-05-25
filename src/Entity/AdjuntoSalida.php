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
 * Entity class for "adjunto_salida" table
 */
#[Entity]
#[Table(name: "adjunto_salida")]
class AdjuntoSalida extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $salida;

    #[Column(type: "string", nullable: true)]
    private ?string $foto;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "datetime")]
    private DateTime $fecha;

    #[Column(type: "string")]
    private string $usuario;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getSalida(): ?int
    {
        return $this->salida;
    }

    public function setSalida(?int $value): static
    {
        $this->salida = $value;
        return $this;
    }

    public function getFoto(): ?string
    {
        return HtmlDecode($this->foto);
    }

    public function setFoto(?string $value): static
    {
        $this->foto = RemoveXss($value);
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

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function setFecha(DateTime $value): static
    {
        $this->fecha = $value;
        return $this;
    }

    public function getUsuario(): string
    {
        return HtmlDecode($this->usuario);
    }

    public function setUsuario(string $value): static
    {
        $this->usuario = RemoveXss($value);
        return $this;
    }
}
