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
 * Entity class for "conteo_encabezado" table
 */
#[Entity]
#[Table(name: "conteo_encabezado")]
class ConteoEncabezado extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string", nullable: true)]
    private ?string $procesado;

    #[Column(type: "string", nullable: true)]
    private ?string $totalizar;

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

    public function getNota(): ?string
    {
        return HtmlDecode($this->nota);
    }

    public function setNota(?string $value): static
    {
        $this->nota = RemoveXss($value);
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

    public function getTotalizar(): ?string
    {
        return $this->totalizar;
    }

    public function setTotalizar(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'totalizar' value");
        }
        $this->totalizar = $value;
        return $this;
    }
}
