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
 * Entity class for "conteo_detalle" table
 */
#[Entity]
#[Table(name: "conteo_detalle")]
class ConteoDetalle extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $conteo;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(type: "integer", nullable: true)]
    private ?int $cantidad;

    #[Column(type: "integer", nullable: true)]
    private ?int $switch;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getConteo(): ?int
    {
        return $this->conteo;
    }

    public function setConteo(?int $value): static
    {
        $this->conteo = $value;
        return $this;
    }

    public function getArticulo(): ?int
    {
        return $this->articulo;
    }

    public function setArticulo(?int $value): static
    {
        $this->articulo = $value;
        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $value): static
    {
        $this->cantidad = $value;
        return $this;
    }

    public function getSwitch(): ?int
    {
        return $this->switch;
    }

    public function setSwitch(?int $value): static
    {
        $this->switch = $value;
        return $this;
    }
}
