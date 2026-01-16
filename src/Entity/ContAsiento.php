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
 * Entity class for "cont_asiento" table
 */
#[Entity]
#[Table(name: "cont_asiento")]
class ContAsiento extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(type: "integer", nullable: true)]
    private ?int $cuenta;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "decimal", nullable: true)]
    private ?string $debe;

    #[Column(type: "decimal", nullable: true)]
    private ?string $haber;

    #[Column(name: "id_referencia", type: "integer")]
    private int $idReferencia;

    public function __construct()
    {
        $this->idReferencia = 0;
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

    public function getComprobante(): ?int
    {
        return $this->comprobante;
    }

    public function setComprobante(?int $value): static
    {
        $this->comprobante = $value;
        return $this;
    }

    public function getCuenta(): ?int
    {
        return $this->cuenta;
    }

    public function setCuenta(?int $value): static
    {
        $this->cuenta = $value;
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

    public function getReferencia(): ?string
    {
        return HtmlDecode($this->referencia);
    }

    public function setReferencia(?string $value): static
    {
        $this->referencia = RemoveXss($value);
        return $this;
    }

    public function getDebe(): ?string
    {
        return $this->debe;
    }

    public function setDebe(?string $value): static
    {
        $this->debe = $value;
        return $this;
    }

    public function getHaber(): ?string
    {
        return $this->haber;
    }

    public function setHaber(?string $value): static
    {
        $this->haber = $value;
        return $this;
    }

    public function getIdReferencia(): int
    {
        return $this->idReferencia;
    }

    public function setIdReferencia(int $value): static
    {
        $this->idReferencia = $value;
        return $this;
    }
}
