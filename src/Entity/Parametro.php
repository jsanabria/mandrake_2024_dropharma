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
 * Entity class for "parametro" table
 */
#[Entity]
#[Table(name: "parametro")]
class Parametro extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "string", nullable: true)]
    private ?string $valor1;

    #[Column(type: "string", nullable: true)]
    private ?string $valor2;

    #[Column(type: "string", nullable: true)]
    private ?string $valor3;

    #[Column(type: "string", nullable: true)]
    private ?string $valor4;

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

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
        return $this;
    }

    public function getValor1(): ?string
    {
        return HtmlDecode($this->valor1);
    }

    public function setValor1(?string $value): static
    {
        $this->valor1 = RemoveXss($value);
        return $this;
    }

    public function getValor2(): ?string
    {
        return HtmlDecode($this->valor2);
    }

    public function setValor2(?string $value): static
    {
        $this->valor2 = RemoveXss($value);
        return $this;
    }

    public function getValor3(): ?string
    {
        return HtmlDecode($this->valor3);
    }

    public function setValor3(?string $value): static
    {
        $this->valor3 = RemoveXss($value);
        return $this;
    }

    public function getValor4(): ?string
    {
        return HtmlDecode($this->valor4);
    }

    public function setValor4(?string $value): static
    {
        $this->valor4 = RemoveXss($value);
        return $this;
    }
}
