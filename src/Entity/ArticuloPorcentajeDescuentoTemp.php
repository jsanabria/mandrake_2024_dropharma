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
 * Entity class for "articulo_porcentaje_descuento_temp" table
 */
#[Entity]
#[Table(name: "articulo_porcentaje_descuento_temp")]
class ArticuloPorcentajeDescuentoTemp extends AbstractEntity
{
    #[Id]
    #[Column(type: "string", unique: true)]
    private string $codigo;

    #[Column(type: "string", nullable: true)]
    private ?string $nombre;

    #[Column(type: "decimal", nullable: true)]
    private ?string $porcentaje;

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function setCodigo(string $value): static
    {
        $this->codigo = $value;
        return $this;
    }

    public function getNombre(): ?string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(?string $value): static
    {
        $this->nombre = RemoveXss($value);
        return $this;
    }

    public function getPorcentaje(): ?string
    {
        return $this->porcentaje;
    }

    public function setPorcentaje(?string $value): static
    {
        $this->porcentaje = $value;
        return $this;
    }
}
