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
 * Entity class for "proveedor" table
 */
#[Entity]
#[Table(name: "proveedor")]
class Proveedor extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "ci_rif", type: "string", nullable: true)]
    private ?string $ciRif;

    #[Column(type: "string", nullable: true)]
    private ?string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $ciudad;

    #[Column(type: "string", nullable: true)]
    private ?string $direccion;

    #[Column(type: "string", nullable: true)]
    private ?string $telefono1;

    #[Column(type: "string", nullable: true)]
    private ?string $telefono2;

    #[Column(type: "string", nullable: true)]
    private ?string $email1;

    #[Column(type: "string", nullable: true)]
    private ?string $email2;

    #[Column(name: "cuenta_auxiliar", type: "integer", nullable: true)]
    private ?int $cuentaAuxiliar;

    #[Column(name: "cuenta_gasto", type: "integer", nullable: true)]
    private ?int $cuentaGasto;

    #[Column(name: "tipo_iva", type: "string", nullable: true)]
    private ?string $tipoIva;

    #[Column(name: "tipo_islr", type: "string", nullable: true)]
    private ?string $tipoIslr;

    #[Column(type: "decimal", nullable: true)]
    private ?string $sustraendo;

    #[Column(name: "tipo_impmun", type: "string", nullable: true)]
    private ?string $tipoImpmun;

    #[Column(name: "cta_bco", type: "string", nullable: true)]
    private ?string $ctaBco;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

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

    public function getCiRif(): ?string
    {
        return HtmlDecode($this->ciRif);
    }

    public function setCiRif(?string $value): static
    {
        $this->ciRif = RemoveXss($value);
        return $this;
    }

    public function getNombre(): ?string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(?string $value): static
    {
        $this->nombre = RemoveXss($value);
        return $this;
    }

    public function getCiudad(): ?string
    {
        return HtmlDecode($this->ciudad);
    }

    public function setCiudad(?string $value): static
    {
        $this->ciudad = RemoveXss($value);
        return $this;
    }

    public function getDireccion(): ?string
    {
        return HtmlDecode($this->direccion);
    }

    public function setDireccion(?string $value): static
    {
        $this->direccion = RemoveXss($value);
        return $this;
    }

    public function getTelefono1(): ?string
    {
        return HtmlDecode($this->telefono1);
    }

    public function setTelefono1(?string $value): static
    {
        $this->telefono1 = RemoveXss($value);
        return $this;
    }

    public function getTelefono2(): ?string
    {
        return HtmlDecode($this->telefono2);
    }

    public function setTelefono2(?string $value): static
    {
        $this->telefono2 = RemoveXss($value);
        return $this;
    }

    public function getEmail1(): ?string
    {
        return HtmlDecode($this->email1);
    }

    public function setEmail1(?string $value): static
    {
        $this->email1 = RemoveXss($value);
        return $this;
    }

    public function getEmail2(): ?string
    {
        return HtmlDecode($this->email2);
    }

    public function setEmail2(?string $value): static
    {
        $this->email2 = RemoveXss($value);
        return $this;
    }

    public function getCuentaAuxiliar(): ?int
    {
        return $this->cuentaAuxiliar;
    }

    public function setCuentaAuxiliar(?int $value): static
    {
        $this->cuentaAuxiliar = $value;
        return $this;
    }

    public function getCuentaGasto(): ?int
    {
        return $this->cuentaGasto;
    }

    public function setCuentaGasto(?int $value): static
    {
        $this->cuentaGasto = $value;
        return $this;
    }

    public function getTipoIva(): ?string
    {
        return HtmlDecode($this->tipoIva);
    }

    public function setTipoIva(?string $value): static
    {
        $this->tipoIva = RemoveXss($value);
        return $this;
    }

    public function getTipoIslr(): ?string
    {
        return HtmlDecode($this->tipoIslr);
    }

    public function setTipoIslr(?string $value): static
    {
        $this->tipoIslr = RemoveXss($value);
        return $this;
    }

    public function getSustraendo(): ?string
    {
        return $this->sustraendo;
    }

    public function setSustraendo(?string $value): static
    {
        $this->sustraendo = $value;
        return $this;
    }

    public function getTipoImpmun(): ?string
    {
        return HtmlDecode($this->tipoImpmun);
    }

    public function setTipoImpmun(?string $value): static
    {
        $this->tipoImpmun = RemoveXss($value);
        return $this;
    }

    public function getCtaBco(): ?string
    {
        return HtmlDecode($this->ctaBco);
    }

    public function setCtaBco(?string $value): static
    {
        $this->ctaBco = RemoveXss($value);
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

    public function getFabricante(): ?int
    {
        return $this->fabricante;
    }

    public function setFabricante(?int $value): static
    {
        $this->fabricante = $value;
        return $this;
    }
}
