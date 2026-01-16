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
 * Entity class for "tabla" table
 */
#[Entity]
#[Table(name: "tabla")]
class Tabla extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $tabla;

    #[Column(name: "campo_codigo", type: "string", unique: true, nullable: true)]
    private ?string $campoCodigo;

    #[Column(name: "campo_descripcion", type: "string", nullable: true)]
    private ?string $campoDescripcion;

    #[Column(name: "campo_dato", type: "string", nullable: true)]
    private ?string $campoDato;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTabla(): ?string
    {
        return HtmlDecode($this->tabla);
    }

    public function setTabla(?string $value): static
    {
        $this->tabla = RemoveXss($value);
        return $this;
    }

    public function getCampoCodigo(): ?string
    {
        return HtmlDecode($this->campoCodigo);
    }

    public function setCampoCodigo(?string $value): static
    {
        $this->campoCodigo = RemoveXss($value);
        return $this;
    }

    public function getCampoDescripcion(): ?string
    {
        return HtmlDecode($this->campoDescripcion);
    }

    public function setCampoDescripcion(?string $value): static
    {
        $this->campoDescripcion = RemoveXss($value);
        return $this;
    }

    public function getCampoDato(): ?string
    {
        return HtmlDecode($this->campoDato);
    }

    public function setCampoDato(?string $value): static
    {
        $this->campoDato = RemoveXss($value);
        return $this;
    }
}
