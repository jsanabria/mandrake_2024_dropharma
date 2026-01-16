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
 * Entity class for "view_banco" table
 */
#[Entity]
#[Table(name: "view_banco")]
class ViewBanco extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(type: "string", nullable: true)]
    private ?string $banco;

    #[Column(type: "string", nullable: true)]
    private ?string $numero;

    #[Column(type: "integer")]
    private int $cuenta;

    public function __construct()
    {
        $this->cuenta = 0;
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

    public function getCodigo(): ?string
    {
        return HtmlDecode($this->codigo);
    }

    public function setCodigo(?string $value): static
    {
        $this->codigo = RemoveXss($value);
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

    public function getNumero(): ?string
    {
        return HtmlDecode($this->numero);
    }

    public function setNumero(?string $value): static
    {
        $this->numero = RemoveXss($value);
        return $this;
    }

    public function getCuenta(): int
    {
        return $this->cuenta;
    }

    public function setCuenta(int $value): static
    {
        $this->cuenta = $value;
        return $this;
    }
}
