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
 * Entity class for "tabla_retenciones" table
 */
#[Entity]
#[Table(name: "tabla_retenciones")]
class TablaRetencione extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(name: "base_imponible", type: "float", nullable: true)]
    private ?float $baseImponible;

    #[Column(type: "float", nullable: true)]
    private ?float $tarifa;

    #[Column(type: "float", nullable: true)]
    private ?float $sustraendo;

    #[Column(name: "pagos_mayores", type: "float", nullable: true)]
    private ?float $pagosMayores;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getCodigo(): ?string
    {
        return HtmlDecode($this->codigo);
    }

    public function setCodigo(?string $value): static
    {
        $this->codigo = RemoveXss($value);
        return $this;
    }

    public function getTipo(): ?string
    {
        return HtmlDecode($this->tipo);
    }

    public function setTipo(?string $value): static
    {
        $this->tipo = RemoveXss($value);
        return $this;
    }

    public function getBaseImponible(): ?float
    {
        return $this->baseImponible;
    }

    public function setBaseImponible(?float $value): static
    {
        $this->baseImponible = $value;
        return $this;
    }

    public function getTarifa(): ?float
    {
        return $this->tarifa;
    }

    public function setTarifa(?float $value): static
    {
        $this->tarifa = $value;
        return $this;
    }

    public function getSustraendo(): ?float
    {
        return $this->sustraendo;
    }

    public function setSustraendo(?float $value): static
    {
        $this->sustraendo = $value;
        return $this;
    }

    public function getPagosMayores(): ?float
    {
        return $this->pagosMayores;
    }

    public function setPagosMayores(?float $value): static
    {
        $this->pagosMayores = $value;
        return $this;
    }

    public function getActivo(): ?string
    {
        return $this->activo;
    }

    public function setActivo(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'activo' value");
        }
        $this->activo = $value;
        return $this;
    }
}
