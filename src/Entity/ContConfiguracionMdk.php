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
 * Entity class for "cont_configuracion_mdk" table
 */
#[Entity]
#[Table(name: "cont_configuracion_mdk")]
class ContConfiguracionMdk extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "string", unique: true)]
    private string $clave;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(name: "cuenta_id", type: "bigint", nullable: true)]
    private ?string $cuentaId;

    #[Column(name: "valor_texto", type: "string", nullable: true)]
    private ?string $valorTexto;

    #[Column(name: "valor_numero", type: "decimal", nullable: true)]
    private ?string $valorNumero;

    #[Column(type: "boolean")]
    private bool $estado;

    #[Column(name: "created_at", type: "datetime")]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->estado = true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getClave(): string
    {
        return HtmlDecode($this->clave);
    }

    public function setClave(string $value): static
    {
        $this->clave = RemoveXss($value);
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

    public function getCuentaId(): ?string
    {
        return $this->cuentaId;
    }

    public function setCuentaId(?string $value): static
    {
        $this->cuentaId = $value;
        return $this;
    }

    public function getValorTexto(): ?string
    {
        return HtmlDecode($this->valorTexto);
    }

    public function setValorTexto(?string $value): static
    {
        $this->valorTexto = RemoveXss($value);
        return $this;
    }

    public function getValorNumero(): ?string
    {
        return $this->valorNumero;
    }

    public function setValorNumero(?string $value): static
    {
        $this->valorNumero = $value;
        return $this;
    }

    public function getEstado(): bool
    {
        return $this->estado;
    }

    public function setEstado(bool $value): static
    {
        $this->estado = $value;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $value): static
    {
        $this->createdAt = $value;
        return $this;
    }
}
