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
 * Entity class for "conceptos_bancarios" table
 */
#[Entity]
#[Table(name: "conceptos_bancarios")]
class ConceptosBancario extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "nombre_concepto", type: "string", nullable: true)]
    private ?string $nombreConcepto;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(type: "integer", nullable: true)]
    private ?int $cuenta;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getNombreConcepto(): ?string
    {
        return HtmlDecode($this->nombreConcepto);
    }

    public function setNombreConcepto(?string $value): static
    {
        $this->nombreConcepto = RemoveXss($value);
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $value): static
    {
        if (!in_array($value, ["A", "D"])) {
            throw new \InvalidArgumentException("Invalid 'tipo' value");
        }
        $this->tipo = $value;
        return $this;
    }

    public function getCuenta(): ?int
    {
        return $this->cuenta;
    }

    public function setCuenta(?int $value): static
    {
        $this->cuenta = $value;
        return $this;
    }
}
