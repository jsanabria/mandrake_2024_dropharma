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
 * Entity class for "transferencia_banco" table
 */
#[Entity]
#[Table(name: "transferencia_banco")]
class TransferenciaBanco extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "banco_origen", type: "integer", nullable: true)]
    private ?int $bancoOrigen;

    #[Column(name: "banco_destino", type: "integer", nullable: true)]
    private ?int $bancoDestino;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "decimal", nullable: true)]
    private ?string $monto;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getBancoOrigen(): ?int
    {
        return $this->bancoOrigen;
    }

    public function setBancoOrigen(?int $value): static
    {
        $this->bancoOrigen = $value;
        return $this;
    }

    public function getBancoDestino(): ?int
    {
        return $this->bancoDestino;
    }

    public function setBancoDestino(?int $value): static
    {
        $this->bancoDestino = $value;
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

    public function getMonto(): ?string
    {
        return $this->monto;
    }

    public function setMonto(?string $value): static
    {
        $this->monto = $value;
        return $this;
    }

    public function getComprobante(): ?int
    {
        return $this->comprobante;
    }

    public function setComprobante(?int $value): static
    {
        $this->comprobante = $value;
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
}
