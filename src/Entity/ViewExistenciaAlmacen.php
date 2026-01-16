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
 * Entity class for "view_existencia_almacen" table
 */
#[Entity]
#[Table(name: "view_existencia_almacen")]
class ViewExistenciaAlmacen extends AbstractEntity
{
    #[Column(type: "string", nullable: true)]
    private ?string $codalm;

    #[Column(type: "integer", nullable: true)]
    private ?int $codfab;

    #[Column(type: "integer", nullable: true)]
    private ?int $codart;

    #[Column(name: "nombre_comercial", type: "string", nullable: true)]
    private ?string $nombreComercial;

    #[Column(name: "principio_activo", type: "string", nullable: true)]
    private ?string $principioActivo;

    #[Column(type: "string", nullable: true)]
    private ?string $presentacion;

    #[Column(type: "string", nullable: true)]
    private ?string $lote;

    #[Column(name: "fecha_vencimiento", type: "string", nullable: true)]
    private ?string $fechaVencimiento;

    #[Column(type: "decimal")]
    private string $cantidad;

    #[Column(type: "string", nullable: true)]
    private ?string $fabricante;

    #[Column(name: "id_compra", type: "bigint")]
    private string $idCompra;

    #[Column(name: "nom_almacen", type: "string", nullable: true)]
    private ?string $nomAlmacen;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(name: "codigo_de_barra", type: "string", nullable: true)]
    private ?string $codigoDeBarra;

    #[Column(type: "string", nullable: true)]
    private ?string $articulo;

    public function __construct()
    {
        $this->cantidad = "0.00";
        $this->idCompra = "0";
    }

    public function getCodalm(): ?string
    {
        return HtmlDecode($this->codalm);
    }

    public function setCodalm(?string $value): static
    {
        $this->codalm = RemoveXss($value);
        return $this;
    }

    public function getCodfab(): ?int
    {
        return $this->codfab;
    }

    public function setCodfab(?int $value): static
    {
        $this->codfab = $value;
        return $this;
    }

    public function getCodart(): ?int
    {
        return $this->codart;
    }

    public function setCodart(?int $value): static
    {
        $this->codart = $value;
        return $this;
    }

    public function getNombreComercial(): ?string
    {
        return HtmlDecode($this->nombreComercial);
    }

    public function setNombreComercial(?string $value): static
    {
        $this->nombreComercial = RemoveXss($value);
        return $this;
    }

    public function getPrincipioActivo(): ?string
    {
        return HtmlDecode($this->principioActivo);
    }

    public function setPrincipioActivo(?string $value): static
    {
        $this->principioActivo = RemoveXss($value);
        return $this;
    }

    public function getPresentacion(): ?string
    {
        return HtmlDecode($this->presentacion);
    }

    public function setPresentacion(?string $value): static
    {
        $this->presentacion = RemoveXss($value);
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

    public function getFechaVencimiento(): ?string
    {
        return HtmlDecode($this->fechaVencimiento);
    }

    public function setFechaVencimiento(?string $value): static
    {
        $this->fechaVencimiento = RemoveXss($value);
        return $this;
    }

    public function getCantidad(): string
    {
        return $this->cantidad;
    }

    public function setCantidad(string $value): static
    {
        $this->cantidad = $value;
        return $this;
    }

    public function getFabricante(): ?string
    {
        return HtmlDecode($this->fabricante);
    }

    public function setFabricante(?string $value): static
    {
        $this->fabricante = RemoveXss($value);
        return $this;
    }

    public function getIdCompra(): string
    {
        return $this->idCompra;
    }

    public function setIdCompra(string $value): static
    {
        $this->idCompra = $value;
        return $this;
    }

    public function getNomAlmacen(): ?string
    {
        return HtmlDecode($this->nomAlmacen);
    }

    public function setNomAlmacen(?string $value): static
    {
        $this->nomAlmacen = RemoveXss($value);
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

    public function getCodigoDeBarra(): ?string
    {
        return HtmlDecode($this->codigoDeBarra);
    }

    public function setCodigoDeBarra(?string $value): static
    {
        $this->codigoDeBarra = RemoveXss($value);
        return $this;
    }

    public function getArticulo(): ?string
    {
        return HtmlDecode($this->articulo);
    }

    public function setArticulo(?string $value): static
    {
        $this->articulo = RemoveXss($value);
        return $this;
    }
}
