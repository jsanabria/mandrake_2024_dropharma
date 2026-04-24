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
 * Entity class for "cliente_dias_tmp" table
 */
#[Entity]
#[Table(name: "cliente_dias_tmp")]
class ClienteDiasTmp extends AbstractEntity
{
    #[Column(name: "ci_rif", type: "string", nullable: true)]
    private ?string $ciRif;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    #[Column(type: "decimal", nullable: true)]
    private ?string $descuento;

    public function getCiRif(): ?string
    {
        return HtmlDecode($this->ciRif);
    }

    public function setCiRif(?string $value): static
    {
        $this->ciRif = RemoveXss($value);
        return $this;
    }

    public function getDiasCredito(): ?int
    {
        return $this->diasCredito;
    }

    public function setDiasCredito(?int $value): static
    {
        $this->diasCredito = $value;
        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $value): static
    {
        $this->descuento = $value;
        return $this;
    }
}
