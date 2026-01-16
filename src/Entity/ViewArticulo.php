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
 * Entity class for "view_articulos" table
 */
#[Entity]
#[Table(name: "view_articulos")]
class ViewArticulo extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(name: "nombre_comercial", type: "string", nullable: true)]
    private ?string $nombreComercial;

    #[Column(name: "principio_activo", type: "string", nullable: true)]
    private ?string $principioActivo;

    #[Column(type: "string", nullable: true)]
    private ?string $presentacion;

    #[Column(name: "cantidad_en_mano", type: "decimal")]
    private string $cantidadEnMano;

    #[Column(name: "ultimo_costo", type: "decimal")]
    private string $ultimoCosto;

    public function __construct()
    {
        $this->cantidadEnMano = "0";
        $this->ultimoCosto = "0.00";
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

    public function getNombreComercial(): ?string
    {
        return HtmlDecode($this->nombreComercial);
    }

    public function setNombreComercial(?string $value): static
    {
        $this->nombreComercial = RemoveXss($value);
        return $this;
    }

    public function getPrincipioActivo(): ?string
    {
        return HtmlDecode($this->principioActivo);
    }

    public function setPrincipioActivo(?string $value): static
    {
        $this->principioActivo = RemoveXss($value);
        return $this;
    }

    public function getPresentacion(): ?string
    {
        return HtmlDecode($this->presentacion);
    }

    public function setPresentacion(?string $value): static
    {
        $this->presentacion = RemoveXss($value);
        return $this;
    }

    public function getCantidadEnMano(): string
    {
        return $this->cantidadEnMano;
    }

    public function setCantidadEnMano(string $value): static
    {
        $this->cantidadEnMano = $value;
        return $this;
    }

    public function getUltimoCosto(): string
    {
        return $this->ultimoCosto;
    }

    public function setUltimoCosto(string $value): static
    {
        $this->ultimoCosto = $value;
        return $this;
    }
}
