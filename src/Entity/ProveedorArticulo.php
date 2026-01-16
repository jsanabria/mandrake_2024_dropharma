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
 * Entity class for "proveedor_articulo" table
 */
#[Entity]
#[Table(name: "proveedor_articulo")]
class ProveedorArticulo extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(name: "codigo_proveedor", type: "string", nullable: true)]
    private ?string $codigoProveedor;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
        return $this;
    }

    public function getFabricante(): ?int
    {
        return $this->fabricante;
    }

    public function setFabricante(?int $value): static
    {
        $this->fabricante = $value;
        return $this;
    }

    public function getArticulo(): ?int
    {
        return $this->articulo;
    }

    public function setArticulo(?int $value): static
    {
        $this->articulo = $value;
        return $this;
    }

    public function getCodigoProveedor(): ?string
    {
        return HtmlDecode($this->codigoProveedor);
    }

    public function setCodigoProveedor(?string $value): static
    {
        $this->codigoProveedor = RemoveXss($value);
        return $this;
    }
}
