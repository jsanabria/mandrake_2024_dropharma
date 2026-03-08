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
 * Entity class for "cont_metodo_cuenta_mdk" table
 */
#[Entity]
#[Table(name: "cont_metodo_cuenta_mdk")]
class ContMetodoCuentaMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(name: "metodo_pago", type: "string", unique: true)]
    private string $metodoPago;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(name: "tipo_destino", type: "string")]
    private string $tipoDestino;

    #[Column(name: "cuenta_id", type: "bigint", nullable: true)]
    private ?string $cuentaId;

    #[Column(name: "forzar_destino", type: "string")]
    private string $forzarDestino;

    #[Column(type: "boolean")]
    private bool $estado;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->tipoDestino = "CAJA_BANCO";
        $this->forzarDestino = "AUTO";
        $this->estado = true;
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

    public function getMetodoPago(): string
    {
        return HtmlDecode($this->metodoPago);
    }

    public function setMetodoPago(string $value): static
    {
        $this->metodoPago = RemoveXss($value);
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

    public function getTipoDestino(): string
    {
        return $this->tipoDestino;
    }

    public function setTipoDestino(string $value): static
    {
        if (!in_array($value, ["CAJA_BANCO", "PASIVO", "OTRO"])) {
            throw new \InvalidArgumentException("Invalid 'tipo_destino' value");
        }
        $this->tipoDestino = $value;
        return $this;
    }

    public function getCuentaId(): ?string
    {
        return $this->cuentaId;
    }

    public function setCuentaId(?string $value): static
    {
        $this->cuentaId = $value;
        return $this;
    }

    public function getForzarDestino(): string
    {
        return $this->forzarDestino;
    }

    public function setForzarDestino(string $value): static
    {
        if (!in_array($value, ["AUTO", "CAJA", "BANCO"])) {
            throw new \InvalidArgumentException("Invalid 'forzar_destino' value");
        }
        $this->forzarDestino = $value;
        return $this;
    }

    public function getEstado(): bool
    {
        return $this->estado;
    }

    public function setEstado(bool $value): static
    {
        $this->estado = $value;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $value): static
    {
        $this->createdAt = $value;
        return $this;
    }
}
