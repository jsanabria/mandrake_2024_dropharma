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
 * Entity class for "documento_consecutivo" table
 */
#[Entity]
#[Table(name: "documento_consecutivo")]
class DocumentoConsecutivo extends AbstractEntity
{
    #[Id]
    #[Column(name: "tipo_documento", type: "string")]
    private string $tipoDocumento;

    #[Id]
    #[Column(type: "string")]
    private string $serie;

    #[Column(name: "ultimo_numero", type: "integer")]
    private int $ultimoNumero;

    #[Column(name: "updated_at", type: "datetime", nullable: true)]
    private ?DateTime $updatedAt;

    public function __construct(string $tipoDocumento, string $serie)
    {
        $this->tipoDocumento = $tipoDocumento;
        $this->serie = $serie;
        $this->ultimoNumero = 0;
    }

    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento;
    }

    public function setTipoDocumento(string $value): static
    {
        $this->tipoDocumento = $value;
        return $this;
    }

    public function getSerie(): string
    {
        return $this->serie;
    }

    public function setSerie(string $value): static
    {
        $this->serie = $value;
        return $this;
    }

    public function getUltimoNumero(): int
    {
        return $this->ultimoNumero;
    }

    public function setUltimoNumero(int $value): static
    {
        $this->ultimoNumero = $value;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $value): static
    {
        $this->updatedAt = $value;
        return $this;
    }
}
