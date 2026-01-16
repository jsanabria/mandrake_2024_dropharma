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
 * Entity class for "pagos_proveedor" table
 */
#[Entity]
#[Table(name: "pagos_proveedor")]
class PagosProveedor extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer")]
    private int $proveedor;

    #[Column(type: "string", nullable: true)]
    private ?string $pivote;

    #[Column(name: "tipo_pago", type: "string", nullable: true)]
    private ?string $tipoPago;

    #[Column(type: "integer")]
    private int $banco;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(name: "monto_dado", type: "decimal", nullable: true)]
    private ?string $montoDado;

    #[Column(type: "decimal", nullable: true)]
    private ?string $monto;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(name: "fecha_registro", type: "date", nullable: true)]
    private ?DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "string", nullable: true)]
    private ?string $comprobante;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getProveedor(): int
    {
        return $this->proveedor;
    }

    public function setProveedor(int $value): static
    {
        $this->proveedor = $value;
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

    public function getTipoPago(): ?string
    {
        return HtmlDecode($this->tipoPago);
    }

    public function setTipoPago(?string $value): static
    {
        $this->tipoPago = RemoveXss($value);
        return $this;
    }

    public function getBanco(): int
    {
        return $this->banco;
    }

    public function setBanco(int $value): static
    {
        $this->banco = $value;
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

    public function getReferencia(): ?string
    {
        return HtmlDecode($this->referencia);
    }

    public function setReferencia(?string $value): static
    {
        $this->referencia = RemoveXss($value);
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

    public function getMontoDado(): ?string
    {
        return $this->montoDado;
    }

    public function setMontoDado(?string $value): static
    {
        $this->montoDado = $value;
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
}
