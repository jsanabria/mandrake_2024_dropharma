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
 * Entity class for "pago_destino_regla" table
 */
#[Entity]
#[Table(name: "pago_destino_regla")]
class PagoDestinoRegla extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer")]
    private int $compania;

    #[Column(type: "string")]
    private string $metodo;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(name: "cuenta_destino_id", type: "integer")]
    private int $cuentaDestinoId;

    #[Column(type: "integer")]
    private int $prioridad;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string")]
    private string $activo;

    public function __construct()
    {
        $this->prioridad = 0;
        $this->activo = "S";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getCompania(): int
    {
        return $this->compania;
    }

    public function setCompania(int $value): static
    {
        $this->compania = $value;
        return $this;
    }

    public function getMetodo(): string
    {
        return HtmlDecode($this->metodo);
    }

    public function setMetodo(string $value): static
    {
        $this->metodo = RemoveXss($value);
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

    public function getCuentaDestinoId(): int
    {
        return $this->cuentaDestinoId;
    }

    public function setCuentaDestinoId(int $value): static
    {
        $this->cuentaDestinoId = $value;
        return $this;
    }

    public function getPrioridad(): int
    {
        return $this->prioridad;
    }

    public function setPrioridad(int $value): static
    {
        $this->prioridad = $value;
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

    public function getActivo(): string
    {
        return HtmlDecode($this->activo);
    }

    public function setActivo(string $value): static
    {
        $this->activo = RemoveXss($value);
        return $this;
    }
}
