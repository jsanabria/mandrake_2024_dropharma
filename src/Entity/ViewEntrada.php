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
 * Entity class for "view_entradas" table
 */
#[Entity]
#[Table(name: "view_entradas")]
class ViewEntrada extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "nombre_documento", type: "string", nullable: true)]
    private ?string $nombreDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "nombre_proveedor", type: "string", nullable: true)]
    private ?string $nombreProveedor;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string", nullable: true)]
    private ?string $consignacion;

    #[Column(name: "consignacion_reportada", type: "string", nullable: true)]
    private ?string $consignacionReportada;

    public function __construct()
    {
        $this->consignacion = "N";
        $this->consignacionReportada = "N";
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

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
        return $this;
    }

    public function getNombreDocumento(): ?string
    {
        return HtmlDecode($this->nombreDocumento);
    }

    public function setNombreDocumento(?string $value): static
    {
        $this->nombreDocumento = RemoveXss($value);
        return $this;
    }

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
        return $this;
    }

    public function getNombreProveedor(): ?string
    {
        return HtmlDecode($this->nombreProveedor);
    }

    public function setNombreProveedor(?string $value): static
    {
        $this->nombreProveedor = RemoveXss($value);
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

    public function getFecha(): ?string
    {
        return HtmlDecode($this->fecha);
    }

    public function setFecha(?string $value): static
    {
        $this->fecha = RemoveXss($value);
        return $this;
    }

    public function getEstatus(): ?string
    {
        return HtmlDecode($this->estatus);
    }

    public function setEstatus(?string $value): static
    {
        $this->estatus = RemoveXss($value);
        return $this;
    }

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
        return $this;
    }

    public function getNota(): ?string
    {
        return HtmlDecode($this->nota);
    }

    public function setNota(?string $value): static
    {
        $this->nota = RemoveXss($value);
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

    public function getConsignacionReportada(): ?string
    {
        return $this->consignacionReportada;
    }

    public function setConsignacionReportada(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'consignacion_reportada' value");
        }
        $this->consignacionReportada = $value;
        return $this;
    }
}
