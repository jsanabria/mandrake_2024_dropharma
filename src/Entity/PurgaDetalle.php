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
 * Entity class for "purga_detalle" table
 */
#[Entity]
#[Table(name: "purga_detalle")]
class PurgaDetalle extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $purga;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(name: "id_entradas_salidas", type: "bigint", nullable: true)]
    private ?string $idEntradasSalidas;

    #[Column(type: "string", nullable: true)]
    private ?string $almacen;

    #[Column(type: "string", nullable: true)]
    private ?string $lote;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "cantidad_articulo", type: "decimal", nullable: true)]
    private ?string $cantidadArticulo;

    #[Column(type: "string", nullable: true)]
    private ?string $procesado;

    public function __construct()
    {
        $this->procesado = "N";
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

    public function getPurga(): ?int
    {
        return $this->purga;
    }

    public function setPurga(?int $value): static
    {
        $this->purga = $value;
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

    public function getIdEntradasSalidas(): ?string
    {
        return $this->idEntradasSalidas;
    }

    public function setIdEntradasSalidas(?string $value): static
    {
        $this->idEntradasSalidas = $value;
        return $this;
    }

    public function getAlmacen(): ?string
    {
        return HtmlDecode($this->almacen);
    }

    public function setAlmacen(?string $value): static
    {
        $this->almacen = RemoveXss($value);
        return $this;
    }

    public function getLote(): ?string
    {
        return HtmlDecode($this->lote);
    }

    public function setLote(?string $value): static
    {
        $this->lote = RemoveXss($value);
        return $this;
    }

    public function getFecha(): ?DateTime
    {
        return $this->fecha;
    }

    public function setFecha(?DateTime $value): static
    {
        $this->fecha = $value;
        return $this;
    }

    public function getCantidadArticulo(): ?string
    {
        return $this->cantidadArticulo;
    }

    public function setCantidadArticulo(?string $value): static
    {
        $this->cantidadArticulo = $value;
        return $this;
    }

    public function getProcesado(): ?string
    {
        return $this->procesado;
    }

    public function setProcesado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'procesado' value");
        }
        $this->procesado = $value;
        return $this;
    }
}
