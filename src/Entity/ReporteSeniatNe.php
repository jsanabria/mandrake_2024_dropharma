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
 * Entity class for "reporte_seniat_ne" table
 */
#[Entity]
#[Table(name: "reporte_seniat_ne")]
class ReporteSeniatNe extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", unique: true)]
    private string $periodo;

    #[Column(type: "integer")]
    private int $cantidad;

    #[Column(name: "monto_total", type: "decimal")]
    private string $montoTotal;

    #[Column(name: "email_destino", type: "string")]
    private string $emailDestino;

    #[Column(name: "enviado_en", type: "datetime")]
    private DateTime $enviadoEn;

    #[Column(type: "string", nullable: true)]
    private ?string $usuario;

    public function __construct()
    {
        $this->cantidad = 0;
        $this->montoTotal = "0.00";
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

    public function getPeriodo(): string
    {
        return HtmlDecode($this->periodo);
    }

    public function setPeriodo(string $value): static
    {
        $this->periodo = RemoveXss($value);
        return $this;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $value): static
    {
        $this->cantidad = $value;
        return $this;
    }

    public function getMontoTotal(): string
    {
        return $this->montoTotal;
    }

    public function setMontoTotal(string $value): static
    {
        $this->montoTotal = $value;
        return $this;
    }

    public function getEmailDestino(): string
    {
        return HtmlDecode($this->emailDestino);
    }

    public function setEmailDestino(string $value): static
    {
        $this->emailDestino = RemoveXss($value);
        return $this;
    }

    public function getEnviadoEn(): DateTime
    {
        return $this->enviadoEn;
    }

    public function setEnviadoEn(DateTime $value): static
    {
        $this->enviadoEn = $value;
        return $this;
    }

    public function getUsuario(): ?string
    {
        return HtmlDecode($this->usuario);
    }

    public function setUsuario(?string $value): static
    {
        $this->usuario = RemoveXss($value);
        return $this;
    }
}
