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
 * Entity class for "cliente" table
 */
#[Entity]
#[Table(name: "cliente")]
class Cliente extends AbstractEntity
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
    private ?string $sucursal;

    #[Column(type: "string", nullable: true)]
    private ?string $contacto;

    #[Column(type: "string", nullable: true)]
    private ?string $ciudad;

    #[Column(type: "string", nullable: true)]
    private ?string $zona;

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

    #[Column(name: "codigo_ims", type: "string", nullable: true)]
    private ?string $codigoIms;

    #[Column(type: "string", nullable: true)]
    private ?string $web;

    #[Column(name: "tipo_cliente", type: "string", nullable: true)]
    private ?string $tipoCliente;

    #[Column(type: "integer", nullable: true)]
    private ?int $tarifa;

    #[Column(type: "string", nullable: true)]
    private ?string $consignacion;

    #[Column(name: "limite_credito", type: "decimal", nullable: true)]
    private ?string $limiteCredito;

    #[Column(type: "string", nullable: true)]
    private ?string $condicion;

    #[Column(type: "integer", nullable: true)]
    private ?int $cuenta;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    #[Column(type: "string", nullable: true)]
    private ?string $foto1;

    #[Column(type: "string", nullable: true)]
    private ?string $foto2;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    #[Column(type: "integer", nullable: true)]
    private ?int $descuento;

    public function __construct()
    {
        $this->consignacion = "N";
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

    public function getSucursal(): ?string
    {
        return HtmlDecode($this->sucursal);
    }

    public function setSucursal(?string $value): static
    {
        $this->sucursal = RemoveXss($value);
        return $this;
    }

    public function getContacto(): ?string
    {
        return HtmlDecode($this->contacto);
    }

    public function setContacto(?string $value): static
    {
        $this->contacto = RemoveXss($value);
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

    public function getZona(): ?string
    {
        return HtmlDecode($this->zona);
    }

    public function setZona(?string $value): static
    {
        $this->zona = RemoveXss($value);
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

    public function getCodigoIms(): ?string
    {
        return HtmlDecode($this->codigoIms);
    }

    public function setCodigoIms(?string $value): static
    {
        $this->codigoIms = RemoveXss($value);
        return $this;
    }

    public function getWeb(): ?string
    {
        return HtmlDecode($this->web);
    }

    public function setWeb(?string $value): static
    {
        $this->web = RemoveXss($value);
        return $this;
    }

    public function getTipoCliente(): ?string
    {
        return HtmlDecode($this->tipoCliente);
    }

    public function setTipoCliente(?string $value): static
    {
        $this->tipoCliente = RemoveXss($value);
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

    public function getConsignacion(): ?string
    {
        return $this->consignacion;
    }

    public function setConsignacion(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'consignacion' value");
        }
        $this->consignacion = $value;
        return $this;
    }

    public function getLimiteCredito(): ?string
    {
        return $this->limiteCredito;
    }

    public function setLimiteCredito(?string $value): static
    {
        $this->limiteCredito = $value;
        return $this;
    }

    public function getCondicion(): ?string
    {
        return $this->condicion;
    }

    public function setCondicion(?string $value): static
    {
        if (!in_array($value, ["CREDITO", "PREPAGO", "CONTADO"])) {
            throw new \InvalidArgumentException("Invalid 'condicion' value");
        }
        $this->condicion = $value;
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

    public function getFoto1(): ?string
    {
        return HtmlDecode($this->foto1);
    }

    public function setFoto1(?string $value): static
    {
        $this->foto1 = RemoveXss($value);
        return $this;
    }

    public function getFoto2(): ?string
    {
        return HtmlDecode($this->foto2);
    }

    public function setFoto2(?string $value): static
    {
        $this->foto2 = RemoveXss($value);
        return $this;
    }

    public function getDiasCredito(): ?int
    {
        return $this->diasCredito;
    }

    public function setDiasCredito(?int $value): static
    {
        $this->diasCredito = $value;
        return $this;
    }

    public function getDescuento(): ?int
    {
        return $this->descuento;
    }

    public function setDescuento(?int $value): static
    {
        $this->descuento = $value;
        return $this;
    }
}
