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
 * Entity class for "cobros_cliente" table
 */
#[Entity]
#[Table(name: "cobros_cliente")]
class CobrosCliente extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer")]
    private int $cliente;

    #[Column(name: "id_documento", type: "integer")]
    private int $idDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $pivote;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "decimal", nullable: true)]
    private ?string $pago;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(name: "fecha_registro", type: "date", nullable: true)]
    private ?DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "string", nullable: true)]
    private ?string $comprobante;

    #[Column(name: "tipo_pago", type: "string", nullable: true)]
    private ?string $tipoPago;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "integer", nullable: true)]
    private ?int $banco;

    #[Column(name: "banco_origen", type: "string", nullable: true)]
    private ?string $bancoOrigen;

    #[Column(name: "monto_recibido", type: "decimal", nullable: true)]
    private ?string $montoRecibido;

    #[Column(type: "decimal", nullable: true)]
    private ?string $monto;

    #[Column(name: "tasa_cambio", type: "decimal", nullable: true)]
    private ?string $tasaCambio;

    #[Column(type: "string", nullable: true)]
    private ?string $pivote2;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getCliente(): int
    {
        return $this->cliente;
    }

    public function setCliente(int $value): static
    {
        $this->cliente = $value;
        return $this;
    }

    public function getIdDocumento(): int
    {
        return $this->idDocumento;
    }

    public function setIdDocumento(int $value): static
    {
        $this->idDocumento = $value;
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

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?DateTime $value): static
    {
        $this->fecha = $value;
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

    public function getPago(): ?string
    {
        return $this->pago;
    }

    public function setPago(?string $value): static
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

    public function getFechaRegistro(): ?DateTime
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(?DateTime $value): static
    {
        $this->fechaRegistro = $value;
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

    public function getComprobante(): ?string
    {
        return HtmlDecode($this->comprobante);
    }

    public function setComprobante(?string $value): static
    {
        $this->comprobante = RemoveXss($value);
        return $this;
    }

    public function getTipoPago(): ?string
    {
        return HtmlDecode($this->tipoPago);
    }

    public function setTipoPago(?string $value): static
    {
        $this->tipoPago = RemoveXss($value);
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

    public function getBanco(): ?int
    {
        return $this->banco;
    }

    public function setBanco(?int $value): static
    {
        $this->banco = $value;
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

    public function getMontoRecibido(): ?string
    {
        return $this->montoRecibido;
    }

    public function setMontoRecibido(?string $value): static
    {
        $this->montoRecibido = $value;
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

    public function getTasaCambio(): ?string
    {
        return $this->tasaCambio;
    }

    public function setTasaCambio(?string $value): static
    {
        $this->tasaCambio = $value;
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
