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
 * Entity class for "presupuesto" table
 */
#[Entity]
#[Table(name: "presupuesto")]
class Presupuesto extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "cliente_potencial", type: "string", nullable: true)]
    private ?string $clientePotencial;

    #[Column(type: "string", nullable: true)]
    private ?string $rif;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(type: "string", nullable: true)]
    private ?string $proyecto;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

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

    public function getClientePotencial(): ?string
    {
        return HtmlDecode($this->clientePotencial);
    }

    public function setClientePotencial(?string $value): static
    {
        $this->clientePotencial = RemoveXss($value);
        return $this;
    }

    public function getRif(): ?string
    {
        return HtmlDecode($this->rif);
    }

    public function setRif(?string $value): static
    {
        $this->rif = RemoveXss($value);
        return $this;
    }

    public function getCliente(): ?int
    {
        return $this->cliente;
    }

    public function setCliente(?int $value): static
    {
        $this->cliente = $value;
        return $this;
    }

    public function getProyecto(): ?string
    {
        return HtmlDecode($this->proyecto);
    }

    public function setProyecto(?string $value): static
    {
        $this->proyecto = RemoveXss($value);
        return $this;
    }

    public function getEstatus(): ?string
    {
        return $this->estatus;
    }

    public function setEstatus(?string $value): static
    {
        if (!in_array($value, ["NUEVO", "APROBADO", "EJECUTADO", "ANULADO"])) {
            throw new \InvalidArgumentException("Invalid 'estatus' value");
        }
        $this->estatus = $value;
        return $this;
    }
}
