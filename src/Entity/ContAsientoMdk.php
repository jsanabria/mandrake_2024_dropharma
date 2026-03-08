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
 * Entity class for "cont_asiento_mdk" table
 */
#[Entity]
#[Table(name: "cont_asiento_mdk")]
class ContAsientoMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "date")]
    private DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(name: "modulo_origen", type: "string")]
    private string $moduloOrigen;

    #[Column(name: "origen_tabla", type: "string", nullable: true)]
    private ?string $origenTabla;

    #[Column(name: "origen_id", type: "bigint", nullable: true)]
    private ?string $origenId;

    #[Column(name: "tercero_id", type: "bigint", nullable: true)]
    private ?string $terceroId;

    #[Column(name: "moneda_trx", type: "string")]
    private string $monedaTrx;

    #[Column(name: "tasa_trx", type: "decimal")]
    private string $tasaTrx;

    #[Column(name: "total_moneda_trx", type: "decimal")]
    private string $totalMonedaTrx;

    #[Column(name: "total_bs", type: "decimal")]
    private string $totalBs;

    #[Column(name: "centro_costo_id", type: "bigint", nullable: true)]
    private ?string $centroCostoId;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    #[Column(type: "boolean")]
    private bool $anulado;

    #[Column(name: "anulado_at", type: "datetime", nullable: true)]
    private ?DateTime $anuladoAt;

    #[Column(name: "anulado_by", type: "string", nullable: true)]
    private ?string $anuladoBy;

    #[Column(name: "anulado_motivo", type: "string", nullable: true)]
    private ?string $anuladoMotivo;

    public function __construct()
    {
        $this->monedaTrx = "Bs";
        $this->tasaTrx = "1.000000";
        $this->totalMonedaTrx = "0.00";
        $this->totalBs = "0.00";
        $this->anulado = false;
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

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
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

    public function getOrigenTabla(): ?string
    {
        return HtmlDecode($this->origenTabla);
    }

    public function setOrigenTabla(?string $value): static
    {
        $this->origenTabla = RemoveXss($value);
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

    public function getTerceroId(): ?string
    {
        return $this->terceroId;
    }

    public function setTerceroId(?string $value): static
    {
        $this->terceroId = $value;
        return $this;
    }

    public function getMonedaTrx(): string
    {
        return HtmlDecode($this->monedaTrx);
    }

    public function setMonedaTrx(string $value): static
    {
        $this->monedaTrx = RemoveXss($value);
        return $this;
    }

    public function getTasaTrx(): string
    {
        return $this->tasaTrx;
    }

    public function setTasaTrx(string $value): static
    {
        $this->tasaTrx = $value;
        return $this;
    }

    public function getTotalMonedaTrx(): string
    {
        return $this->totalMonedaTrx;
    }

    public function setTotalMonedaTrx(string $value): static
    {
        $this->totalMonedaTrx = $value;
        return $this;
    }

    public function getTotalBs(): string
    {
        return $this->totalBs;
    }

    public function setTotalBs(string $value): static
    {
        $this->totalBs = $value;
        return $this;
    }

    public function getCentroCostoId(): ?string
    {
        return $this->centroCostoId;
    }

    public function setCentroCostoId(?string $value): static
    {
        $this->centroCostoId = $value;
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $value): static
    {
        $this->createdAt = $value;
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

    public function getAnuladoAt(): ?DateTime
    {
        return $this->anuladoAt;
    }

    public function setAnuladoAt(?DateTime $value): static
    {
        $this->anuladoAt = $value;
        return $this;
    }

    public function getAnuladoBy(): ?string
    {
        return HtmlDecode($this->anuladoBy);
    }

    public function setAnuladoBy(?string $value): static
    {
        $this->anuladoBy = RemoveXss($value);
        return $this;
    }

    public function getAnuladoMotivo(): ?string
    {
        return HtmlDecode($this->anuladoMotivo);
    }

    public function setAnuladoMotivo(?string $value): static
    {
        $this->anuladoMotivo = RemoveXss($value);
        return $this;
    }
}
