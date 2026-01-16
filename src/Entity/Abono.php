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
 * Entity class for "abono" table
 */
#[Entity]
#[Table(name: "abono")]
class Abono extends AbstractEntity
{
    #[Column(name: "nro_recibo", type: "integer")]
    private int $nroRecibo;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "decimal")]
    private string $pago;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(name: "tasa_usd", type: "decimal", nullable: true)]
    private ?string $tasaUsd;

    #[Column(name: "metodo_pago", type: "string", nullable: true)]
    private ?string $metodoPago;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "string", nullable: true)]
    private ?string $pivote;

    #[Column(type: "string", nullable: true)]
    private ?string $pivote2;

    public function __construct()
    {
        $this->nroRecibo = 0;
        $this->pago = "0.00";
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

    public function getPago(): string
    {
        return $this->pago;
    }

    public function setPago(string $value): static
    {
        $this->pago = $value;
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

    public function getTasaUsd(): ?string
    {
        return $this->tasaUsd;
    }

    public function setTasaUsd(?string $value): static
    {
        $this->tasaUsd = $value;
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

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
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

    public function getPivote(): ?string
    {
        return HtmlDecode($this->pivote);
    }

    public function setPivote(?string $value): static
    {
        $this->pivote = RemoveXss($value);
        return $this;
    }

    public function getPivote2(): ?string
    {
        return HtmlDecode($this->pivote2);
    }

    public function setPivote2(?string $value): static
    {
        $this->pivote2 = RemoveXss($value);
        return $this;
    }
}
