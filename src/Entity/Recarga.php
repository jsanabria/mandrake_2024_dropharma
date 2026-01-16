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
 * Entity class for "recarga" table
 */
#[Entity]
#[Table(name: "recarga")]
class Recarga extends AbstractEntity
{
    #[Column(name: "nro_recibo", type: "integer")]
    private int $nroRecibo;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "metodo_pago", type: "string", nullable: true)]
    private ?string $metodoPago;

    #[Column(type: "integer", nullable: true)]
    private ?int $banco;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "string", nullable: true)]
    private ?string $reverso;

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

    #[Column(type: "decimal", nullable: true)]
    private ?string $saldo;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(name: "cobro_cliente_reverso", type: "integer")]
    private int $cobroClienteReverso;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "nota_recepcion", type: "integer", nullable: true)]
    private ?int $notaRecepcion;

    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "bigint")]
    private string $abono;

    public function __construct()
    {
        $this->nroRecibo = 0;
        $this->reverso = "N";
        $this->cobroClienteReverso = 0;
        $this->abono = "0";
    }

    public function getNroRecibo(): int
    {
        return $this->nroRecibo;
    }

    public function setNroRecibo(int $value): static
    {
        $this->nroRecibo = $value;
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

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?DateTime $value): static
    {
        $this->fecha = $value;
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

    public function getBanco(): ?int
    {
        return $this->banco;
    }

    public function setBanco(?int $value): static
    {
        $this->banco = $value;
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

    public function getReverso(): ?string
    {
        return $this->reverso;
    }

    public function setReverso(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'reverso' value");
        }
        $this->reverso = $value;
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

    public function getSaldo(): ?string
    {
        return $this->saldo;
    }

    public function setSaldo(?string $value): static
    {
        $this->saldo = $value;
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

    public function getCobroClienteReverso(): int
    {
        return $this->cobroClienteReverso;
    }

    public function setCobroClienteReverso(int $value): static
    {
        $this->cobroClienteReverso = $value;
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

    public function getNotaRecepcion(): ?int
    {
        return $this->notaRecepcion;
    }

    public function setNotaRecepcion(?int $value): static
    {
        $this->notaRecepcion = $value;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getAbono(): string
    {
        return $this->abono;
    }

    public function setAbono(string $value): static
    {
        $this->abono = $value;
        return $this;
    }
}
