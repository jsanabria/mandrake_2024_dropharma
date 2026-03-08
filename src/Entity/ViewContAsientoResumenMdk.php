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
 * Entity class for "view_cont_asiento_resumen_mdk" table
 */
#[Entity]
#[Table(name: "view_cont_asiento_resumen_mdk")]
class ViewContAsientoResumenMdk extends AbstractEntity
{
    #[Column(type: "bigint")]
    private string $id;

    #[Column(type: "date")]
    private DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(name: "modulo_origen", type: "string")]
    private string $moduloOrigen;

    #[Column(name: "origen_id", type: "bigint", nullable: true)]
    private ?string $origenId;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "boolean")]
    private bool $anulado;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    #[Column(name: "total_debe_bs", type: "decimal")]
    private string $totalDebeBs;

    #[Column(name: "total_haber_bs", type: "decimal")]
    private string $totalHaberBs;

    #[Column(name: "diferencia_bs", type: "decimal")]
    private string $diferenciaBs;

    #[Column(name: "estado_cuadre", type: "string")]
    private string $estadoCuadre;

    public function __construct()
    {
        $this->id = "0";
        $this->anulado = false;
        $this->totalDebeBs = "0.00";
        $this->totalHaberBs = "0.00";
        $this->diferenciaBs = "0.00";
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

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function setFecha(DateTime $value): static
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

    public function getModuloOrigen(): string
    {
        return $this->moduloOrigen;
    }

    public function setModuloOrigen(string $value): static
    {
        if (!in_array($value, ["VENTA", "COBRO", "ANTICIPO_CLI", "APL_ANT_CLI", "COMPRA_GASTO", "COMPRA_INV", "PAGO_PROV", "ANTICIPO_PROV", "APL_ANT_PROV", "RETENCION", "AJUSTE"])) {
            throw new \InvalidArgumentException("Invalid 'modulo_origen' value");
        }
        $this->moduloOrigen = $value;
        return $this;
    }

    public function getOrigenId(): ?string
    {
        return $this->origenId;
    }

    public function setOrigenId(?string $value): static
    {
        $this->origenId = $value;
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

    public function getAnulado(): bool
    {
        return $this->anulado;
    }

    public function setAnulado(bool $value): static
    {
        $this->anulado = $value;
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

    public function getTotalDebeBs(): string
    {
        return $this->totalDebeBs;
    }

    public function setTotalDebeBs(string $value): static
    {
        $this->totalDebeBs = $value;
        return $this;
    }

    public function getTotalHaberBs(): string
    {
        return $this->totalHaberBs;
    }

    public function setTotalHaberBs(string $value): static
    {
        $this->totalHaberBs = $value;
        return $this;
    }

    public function getDiferenciaBs(): string
    {
        return $this->diferenciaBs;
    }

    public function setDiferenciaBs(string $value): static
    {
        $this->diferenciaBs = $value;
        return $this;
    }

    public function getEstadoCuadre(): string
    {
        return HtmlDecode($this->estadoCuadre);
    }

    public function setEstadoCuadre(string $value): static
    {
        $this->estadoCuadre = RemoveXss($value);
        return $this;
    }
}
