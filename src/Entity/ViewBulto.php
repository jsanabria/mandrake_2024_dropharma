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
 * Entity class for "view_bultos" table
 */
#[Entity]
#[Table(name: "view_bultos")]
class ViewBulto extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $cliente;

    #[Column(type: "string", nullable: true)]
    private ?string $ciudad;

    #[Column(type: "string", nullable: true)]
    private ?string $direccion;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $asesor;

    #[Column(type: "integer", nullable: true)]
    private ?int $bultos;

    #[Column(name: "fecha_despacho", type: "datetime", nullable: true)]
    private ?DateTime $fechaDespacho;

    #[Column(name: "user_despacho", type: "string", nullable: true)]
    private ?string $userDespacho;

    public function __construct()
    {
        $this->bultos = 0;
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

    public function getCliente(): ?string
    {
        return HtmlDecode($this->cliente);
    }

    public function setCliente(?string $value): static
    {
        $this->cliente = RemoveXss($value);
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

    public function getNroDocumento(): ?string
    {
        return HtmlDecode($this->nroDocumento);
    }

    public function setNroDocumento(?string $value): static
    {
        $this->nroDocumento = RemoveXss($value);
        return $this;
    }

    public function getAsesor(): ?string
    {
        return HtmlDecode($this->asesor);
    }

    public function setAsesor(?string $value): static
    {
        $this->asesor = RemoveXss($value);
        return $this;
    }

    public function getBultos(): ?int
    {
        return $this->bultos;
    }

    public function setBultos(?int $value): static
    {
        $this->bultos = $value;
        return $this;
    }

    public function getFechaDespacho(): ?DateTime
    {
        return $this->fechaDespacho;
    }

    public function setFechaDespacho(?DateTime $value): static
    {
        $this->fechaDespacho = $value;
        return $this;
    }

    public function getUserDespacho(): ?string
    {
        return HtmlDecode($this->userDespacho);
    }

    public function setUserDespacho(?string $value): static
    {
        $this->userDespacho = RemoveXss($value);
        return $this;
    }
}
