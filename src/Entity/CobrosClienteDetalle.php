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
 * Entity class for "cobros_cliente_detalle" table
 */
#[Entity]
#[Table(name: "cobros_cliente_detalle")]
class CobrosClienteDetalle extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "cobros_cliente", type: "integer")]
    private int $cobrosCliente;

    #[Column(name: "metodo_pago", type: "string", nullable: true)]
    private ?string $metodoPago;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(name: "monto_moneda", type: "decimal", nullable: true)]
    private ?string $montoMoneda;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(name: "tasa_moneda", type: "decimal", nullable: true)]
    private ?string $tasaMoneda;

    #[Column(name: "monto_bs", type: "decimal", nullable: true)]
    private ?string $montoBs;

    #[Column(name: "tasa_usd", type: "decimal", nullable: true)]
    private ?string $tasaUsd;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(name: "banco_origen", type: "string", nullable: true)]
    private ?string $bancoOrigen;

    #[Column(type: "integer", nullable: true)]
    private ?int $banco;

    #[Column(name: "anticipo_id", type: "integer", nullable: true)]
    private ?int $anticipoId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getCobrosCliente(): int
    {
        return $this->cobrosCliente;
    }

    public function setCobrosCliente(int $value): static
    {
        $this->cobrosCliente = $value;
        return $this;
    }

    public function getMetodoPago(): ?string
    {
        return HtmlDecode($this->metodoPago);
    }

    public function setMetodoPago(?string $value): static
    {
        $this->metodoPago = RemoveXss($value);
        return $this;
    }

    public function getReferencia(): ?string
    {
        return HtmlDecode($this->referencia);
    }

    public function setReferencia(?string $value): static
    {
        $this->referencia = RemoveXss($value);
        return $this;
    }

    public function getMontoMoneda(): ?string
    {
        return $this->montoMoneda;
    }

    public function setMontoMoneda(?string $value): static
    {
        $this->montoMoneda = $value;
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

    public function getTasaMoneda(): ?string
    {
        return $this->tasaMoneda;
    }

    public function setTasaMoneda(?string $value): static
    {
        $this->tasaMoneda = $value;
        return $this;
    }

    public function getMontoBs(): ?string
    {
        return $this->montoBs;
    }

    public function setMontoBs(?string $value): static
    {
        $this->montoBs = $value;
        return $this;
    }

    public function getTasaUsd(): ?string
    {
        return $this->tasaUsd;
    }

    public function setTasaUsd(?string $value): static
    {
        $this->tasaUsd = $value;
        return $this;
    }

    public function getMontoUsd(): ?string
    {
        return $this->montoUsd;
    }

    public function setMontoUsd(?string $value): static
    {
        $this->montoUsd = $value;
        return $this;
    }

    public function getBancoOrigen(): ?string
    {
        return HtmlDecode($this->bancoOrigen);
    }

    public function setBancoOrigen(?string $value): static
    {
        $this->bancoOrigen = RemoveXss($value);
        return $this;
    }

    public function getBanco(): ?int
    {
        return $this->banco;
    }

    public function setBanco(?int $value): static
    {
        $this->banco = $value;
        return $this;
    }

    public function getAnticipoId(): ?int
    {
        return $this->anticipoId;
    }

    public function setAnticipoId(?int $value): static
    {
        $this->anticipoId = $value;
        return $this;
    }
}
