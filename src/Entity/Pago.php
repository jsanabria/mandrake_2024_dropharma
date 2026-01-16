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
 * Entity class for "pagos" table
 */
#[Entity]
#[Table(name: "pagos")]
class Pago extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "id_documento", type: "integer", nullable: true)]
    private ?int $idDocumento;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "tipo_pago", type: "string", nullable: true)]
    private ?string $tipoPago;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $banco;

    #[Column(name: "banco_destino", type: "integer", nullable: true)]
    private ?int $bancoDestino;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "decimal", nullable: true)]
    private ?string $monto;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(name: "comprobante_pago", type: "string", nullable: true)]
    private ?string $comprobantePago;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "fecha_resgistro", type: "date", nullable: true)]
    private ?DateTime $fechaResgistro;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getIdDocumento(): ?int
    {
        return $this->idDocumento;
    }

    public function setIdDocumento(?int $value): static
    {
        $this->idDocumento = $value;
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

    public function getTipoPago(): ?string
    {
        return HtmlDecode($this->tipoPago);
    }

    public function setTipoPago(?string $value): static
    {
        $this->tipoPago = RemoveXss($value);
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

    public function getBanco(): ?string
    {
        return HtmlDecode($this->banco);
    }

    public function setBanco(?string $value): static
    {
        $this->banco = RemoveXss($value);
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

    public function getComprobantePago(): ?string
    {
        return HtmlDecode($this->comprobantePago);
    }

    public function setComprobantePago(?string $value): static
    {
        $this->comprobantePago = RemoveXss($value);
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

    public function getFechaResgistro(): ?DateTime
    {
        return $this->fechaResgistro;
    }

    public function setFechaResgistro(?DateTime $value): static
    {
        $this->fechaResgistro = $value;
        return $this;
    }
}
