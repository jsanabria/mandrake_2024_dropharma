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
 * Entity class for "articulo_anterior" table
 */
#[Entity]
#[Table(name: "articulo_anterior")]
class ArticuloAnterior extends AbstractEntity
{
    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Id]
    #[Column(type: "integer", unique: true)]
    private int $articulo;

    #[Column(type: "string", unique: true, nullable: true)]
    private ?string $codigo;

    #[Column(name: "costo_anterior", type: "decimal")]
    private string $costoAnterior;

    #[Column(name: "costo_nuevo", type: "decimal")]
    private string $costoNuevo;

    public function __construct()
    {
        $this->costoAnterior = "0.00";
        $this->costoNuevo = "0.00";
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

    public function getArticulo(): int
    {
        return $this->articulo;
    }

    public function setArticulo(int $value): static
    {
        $this->articulo = $value;
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

    public function getCostoAnterior(): string
    {
        return $this->costoAnterior;
    }

    public function setCostoAnterior(string $value): static
    {
        $this->costoAnterior = $value;
        return $this;
    }

    public function getCostoNuevo(): string
    {
        return $this->costoNuevo;
    }

    public function setCostoNuevo(string $value): static
    {
        $this->costoNuevo = $value;
        return $this;
    }
}
