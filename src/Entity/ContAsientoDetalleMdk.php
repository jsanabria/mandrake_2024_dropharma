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
 * Entity class for "cont_asiento_detalle_mdk" table
 */
#[Entity]
#[Table(name: "cont_asiento_detalle_mdk")]
class ContAsientoDetalleMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(name: "asiento_id", type: "bigint")]
    private string $asientoId;

    #[Column(name: "cuenta_id", type: "bigint")]
    private string $cuentaId;

    #[Column(name: "centro_costo_id", type: "bigint", nullable: true)]
    private ?string $centroCostoId;

    #[Column(type: "string", nullable: true)]
    private ?string $concepto;

    #[Column(name: "moneda_trx", type: "string")]
    private string $monedaTrx;

    #[Column(name: "tasa_trx", type: "decimal")]
    private string $tasaTrx;

    #[Column(name: "monto_moneda_trx", type: "decimal")]
    private string $montoMonedaTrx;

    #[Column(name: "debe_bs", type: "decimal")]
    private string $debeBs;

    #[Column(name: "haber_bs", type: "decimal")]
    private string $haberBs;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->monedaTrx = "Bs";
        $this->tasaTrx = "1.000000";
        $this->montoMonedaTrx = "0.00";
        $this->debeBs = "0.00";
        $this->haberBs = "0.00";
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

    public function getAsientoId(): string
    {
        return $this->asientoId;
    }

    public function setAsientoId(string $value): static
    {
        $this->asientoId = $value;
        return $this;
    }

    public function getCuentaId(): string
    {
        return $this->cuentaId;
    }

    public function setCuentaId(string $value): static
    {
        $this->cuentaId = $value;
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

    public function getConcepto(): ?string
    {
        return HtmlDecode($this->concepto);
    }

    public function setConcepto(?string $value): static
    {
        $this->concepto = RemoveXss($value);
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

    public function getMontoMonedaTrx(): string
    {
        return $this->montoMonedaTrx;
    }

    public function setMontoMonedaTrx(string $value): static
    {
        $this->montoMonedaTrx = $value;
        return $this;
    }

    public function getDebeBs(): string
    {
        return $this->debeBs;
    }

    public function setDebeBs(string $value): static
    {
        $this->debeBs = $value;
        return $this;
    }

    public function getHaberBs(): string
    {
        return $this->haberBs;
    }

    public function setHaberBs(string $value): static
    {
        $this->haberBs = $value;
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
