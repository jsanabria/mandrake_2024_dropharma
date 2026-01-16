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
 * Entity class for "tarifa_anterior" table
 */
#[Entity]
#[Table(name: "tarifa_anterior")]
class TarifaAnterior extends AbstractEntity
{
    #[Column(type: "integer")]
    private int $id;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "integer", nullable: true)]
    private ?int $tarifa;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(name: "precio_anterior", type: "decimal", nullable: true)]
    private ?string $precioAnterior;

    #[Column(name: "precio_nuevo", type: "decimal", nullable: true)]
    private ?string $precioNuevo;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    public function __construct()
    {
        $this->activo = "S";
    }

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

    public function getTarifa(): ?int
    {
        return $this->tarifa;
    }

    public function setTarifa(?int $value): static
    {
        $this->tarifa = $value;
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

    public function getFabricante(): ?int
    {
        return $this->fabricante;
    }

    public function setFabricante(?int $value): static
    {
        $this->fabricante = $value;
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

    public function getPrecioAnterior(): ?string
    {
        return $this->precioAnterior;
    }

    public function setPrecioAnterior(?string $value): static
    {
        $this->precioAnterior = $value;
        return $this;
    }

    public function getPrecioNuevo(): ?string
    {
        return $this->precioNuevo;
    }

    public function setPrecioNuevo(?string $value): static
    {
        $this->precioNuevo = $value;
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
