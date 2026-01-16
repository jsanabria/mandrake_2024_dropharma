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
 * Entity class for "compania_cuenta" table
 */
#[Entity]
#[Table(name: "compania_cuenta")]
class CompaniaCuenta extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $banco;

    #[Column(type: "string", nullable: true)]
    private ?string $titular;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(type: "string", nullable: true)]
    private ?string $numero;

    #[Column(type: "string", nullable: true)]
    private ?string $mostrar;

    #[Column(type: "integer")]
    private int $cuenta;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    #[Column(type: "integer")]
    private int $compania;

    public function __construct()
    {
        $this->mostrar = "S";
        $this->cuenta = 0;
        $this->activo = "S";
        $this->compania = 0;
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

    public function getBanco(): ?string
    {
        return HtmlDecode($this->banco);
    }

    public function setBanco(?string $value): static
    {
        $this->banco = RemoveXss($value);
        return $this;
    }

    public function getTitular(): ?string
    {
        return HtmlDecode($this->titular);
    }

    public function setTitular(?string $value): static
    {
        $this->titular = RemoveXss($value);
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

    public function getNumero(): ?string
    {
        return HtmlDecode($this->numero);
    }

    public function setNumero(?string $value): static
    {
        $this->numero = RemoveXss($value);
        return $this;
    }

    public function getMostrar(): ?string
    {
        return $this->mostrar;
    }

    public function setMostrar(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'mostrar' value");
        }
        $this->mostrar = $value;
        return $this;
    }

    public function getCuenta(): int
    {
        return $this->cuenta;
    }

    public function setCuenta(int $value): static
    {
        $this->cuenta = $value;
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

    public function getCompania(): int
    {
        return $this->compania;
    }

    public function setCompania(int $value): static
    {
        $this->compania = $value;
        return $this;
    }
}
