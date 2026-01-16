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
 * Entity class for "tarifa_descuento_utilidad" table
 */
#[Entity]
#[Table(name: "tarifa_descuento_utilidad")]
class TarifaDescuentoUtilidad extends AbstractEntity
{
    #[Id]
    #[Column(name: "Id", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer")]
    private int $fabricante;

    #[Column(type: "decimal", nullable: true)]
    private ?string $descuento;

    #[Column(type: "decimal", nullable: true)]
    private ?string $utilidad;

    #[Column(type: "integer")]
    private int $tarifa;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getFabricante(): int
    {
        return $this->fabricante;
    }

    public function setFabricante(int $value): static
    {
        $this->fabricante = $value;
        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $value): static
    {
        $this->descuento = $value;
        return $this;
    }

    public function getUtilidad(): ?string
    {
        return $this->utilidad;
    }

    public function setUtilidad(?string $value): static
    {
        $this->utilidad = $value;
        return $this;
    }

    public function getTarifa(): int
    {
        return $this->tarifa;
    }

    public function setTarifa(int $value): static
    {
        $this->tarifa = $value;
        return $this;
    }
}
