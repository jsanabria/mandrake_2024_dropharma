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
 * Entity class for "cont_comprobante" table
 */
#[Entity]
#[Table(name: "cont_comprobante")]
class ContComprobante extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $contabilizacion;

    #[Column(type: "string", nullable: true)]
    private ?string $registra;

    #[Column(name: "fecha_registro", type: "date", nullable: true)]
    private ?DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $contabiliza;

    #[Column(name: "fecha_contabiliza", type: "date", nullable: true)]
    private ?DateTime $fechaContabiliza;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
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

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?DateTime $value): static
    {
        $this->fecha = $value;
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

    public function getContabilizacion(): ?DateTime
    {
        return $this->contabilizacion;
    }

    public function setContabilizacion(?DateTime $value): static
    {
        $this->contabilizacion = $value;
        return $this;
    }

    public function getRegistra(): ?string
    {
        return HtmlDecode($this->registra);
    }

    public function setRegistra(?string $value): static
    {
        $this->registra = RemoveXss($value);
        return $this;
    }

    public function getFechaRegistro(): ?DateTime
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(?DateTime $value): static
    {
        $this->fechaRegistro = $value;
        return $this;
    }

    public function getContabiliza(): ?string
    {
        return HtmlDecode($this->contabiliza);
    }

    public function setContabiliza(?string $value): static
    {
        $this->contabiliza = RemoveXss($value);
        return $this;
    }

    public function getFechaContabiliza(): ?DateTime
    {
        return $this->fechaContabiliza;
    }

    public function setFechaContabiliza(?DateTime $value): static
    {
        $this->fechaContabiliza = $value;
        return $this;
    }
}
