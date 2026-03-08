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
 * Entity class for "cont_plan_cuentas_mdk" table
 */
#[Entity]
#[Table(name: "cont_plan_cuentas_mdk")]
class ContPlanCuentasMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "string", unique: true)]
    private string $codigo;

    #[Column(type: "string")]
    private string $nombre;

    #[Column(type: "string")]
    private string $tipo;

    #[Column(type: "string")]
    private string $naturaleza;

    #[Column(name: "acepta_movimiento", type: "boolean")]
    private bool $aceptaMovimiento;

    #[Column(name: "cuenta_padre_id", type: "bigint", nullable: true)]
    private ?string $cuentaPadreId;

    #[Column(type: "integer")]
    private int $nivel;

    #[Column(type: "boolean")]
    private bool $estado;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->aceptaMovimiento = true;
        $this->nivel = 1;
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

    public function getCodigo(): string
    {
        return HtmlDecode($this->codigo);
    }

    public function setCodigo(string $value): static
    {
        $this->codigo = RemoveXss($value);
        return $this;
    }

    public function getNombre(): string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(string $value): static
    {
        $this->nombre = RemoveXss($value);
        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $value): static
    {
        if (!in_array($value, ["ACTIVO", "PASIVO", "PATRIMONIO", "INGRESO", "GASTO"])) {
            throw new \InvalidArgumentException("Invalid 'tipo' value");
        }
        $this->tipo = $value;
        return $this;
    }

    public function getNaturaleza(): string
    {
        return $this->naturaleza;
    }

    public function setNaturaleza(string $value): static
    {
        if (!in_array($value, ["DEUDORA", "ACREEDORA"])) {
            throw new \InvalidArgumentException("Invalid 'naturaleza' value");
        }
        $this->naturaleza = $value;
        return $this;
    }

    public function getAceptaMovimiento(): bool
    {
        return $this->aceptaMovimiento;
    }

    public function setAceptaMovimiento(bool $value): static
    {
        $this->aceptaMovimiento = $value;
        return $this;
    }

    public function getCuentaPadreId(): ?string
    {
        return $this->cuentaPadreId;
    }

    public function setCuentaPadreId(?string $value): static
    {
        $this->cuentaPadreId = $value;
        return $this;
    }

    public function getNivel(): int
    {
        return $this->nivel;
    }

    public function setNivel(int $value): static
    {
        $this->nivel = $value;
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
