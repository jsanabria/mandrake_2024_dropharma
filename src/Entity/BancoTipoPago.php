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
 * Entity class for "banco_tipo_pago" table
 */
#[Entity]
#[Table(name: "banco_tipo_pago")]
class BancoTipoPago extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $banco;

    #[Column(name: "tipo_pago", type: "string", nullable: true)]
    private ?string $tipoPago;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    public function __construct()
    {
        $this->banco = 0;
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

    public function getBanco(): ?int
    {
        return $this->banco;
    }

    public function setBanco(?int $value): static
    {
        $this->banco = $value;
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

    public function getMoneda(): ?string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(?string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }
}
