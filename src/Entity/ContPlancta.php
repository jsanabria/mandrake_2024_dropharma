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
 * Entity class for "cont_plancta" table
 */
#[Entity]
#[Table(name: "cont_plancta")]
class ContPlancta extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $clase;

    #[Column(type: "string", nullable: true)]
    private ?string $grupo;

    #[Column(type: "string", nullable: true)]
    private ?string $cuenta;

    #[Column(type: "string", nullable: true)]
    private ?string $subcuenta;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "string", nullable: true)]
    private ?string $clasificacion;

    #[Column(type: "string", nullable: true)]
    private ?string $naturaleza;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "string", nullable: true)]
    private ?string $activa;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getClase(): ?string
    {
        return HtmlDecode($this->clase);
    }

    public function setClase(?string $value): static
    {
        $this->clase = RemoveXss($value);
        return $this;
    }

    public function getGrupo(): ?string
    {
        return HtmlDecode($this->grupo);
    }

    public function setGrupo(?string $value): static
    {
        $this->grupo = RemoveXss($value);
        return $this;
    }

    public function getCuenta(): ?string
    {
        return HtmlDecode($this->cuenta);
    }

    public function setCuenta(?string $value): static
    {
        $this->cuenta = RemoveXss($value);
        return $this;
    }

    public function getSubcuenta(): ?string
    {
        return HtmlDecode($this->subcuenta);
    }

    public function setSubcuenta(?string $value): static
    {
        $this->subcuenta = RemoveXss($value);
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
        return $this;
    }

    public function getClasificacion(): ?string
    {
        return $this->clasificacion;
    }

    public function setClasificacion(?string $value): static
    {
        if (!in_array($value, ["ACTIVO", "PASIVO", "CAPITAL", "VENTAS", "COSTOS", "GASTOS", "ORDEN"])) {
            throw new \InvalidArgumentException("Invalid 'clasificacion' value");
        }
        $this->clasificacion = $value;
        return $this;
    }

    public function getNaturaleza(): ?string
    {
        return $this->naturaleza;
    }

    public function setNaturaleza(?string $value): static
    {
        if (!in_array($value, ["DEBE", "HABER"])) {
            throw new \InvalidArgumentException("Invalid 'naturaleza' value");
        }
        $this->naturaleza = $value;
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $value): static
    {
        if (!in_array($value, ["BALANCE", "RESULTADO"])) {
            throw new \InvalidArgumentException("Invalid 'tipo' value");
        }
        $this->tipo = $value;
        return $this;
    }

    public function getMoneda(): ?string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(?string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }

    public function getActiva(): ?string
    {
        return $this->activa;
    }

    public function setActiva(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'activa' value");
        }
        $this->activa = $value;
        return $this;
    }
}
