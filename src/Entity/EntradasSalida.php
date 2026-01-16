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
 * Entity class for "entradas_salidas" table
 */
#[Entity]
#[Table(name: "entradas_salidas")]
class EntradasSalida extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "id_documento", type: "integer", nullable: true)]
    private ?int $idDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(type: "string", nullable: true)]
    private ?string $lote;

    #[Column(name: "fecha_vencimiento", type: "date", nullable: true)]
    private ?DateTime $fechaVencimiento;

    #[Column(type: "string", nullable: true)]
    private ?string $almacen;

    #[Column(name: "id_compra", type: "bigint", nullable: true)]
    private ?string $idCompra;

    #[Column(name: "cantidad_articulo", type: "decimal", nullable: true)]
    private ?string $cantidadArticulo;

    #[Column(name: "articulo_unidad_medida", type: "string", nullable: true)]
    private ?string $articuloUnidadMedida;

    #[Column(name: "cantidad_unidad_medida", type: "decimal", nullable: true)]
    private ?string $cantidadUnidadMedida;

    #[Column(name: "cantidad_movimiento", type: "decimal", nullable: true)]
    private ?string $cantidadMovimiento;

    #[Column(name: "precio_unidad_sin_desc", type: "decimal", nullable: true)]
    private ?string $precioUnidadSinDesc;

    #[Column(type: "decimal", nullable: true)]
    private ?string $descuento;

    #[Column(name: "costo_unidad", type: "decimal", nullable: true)]
    private ?string $costoUnidad;

    #[Column(type: "decimal", nullable: true)]
    private ?string $costo;

    #[Column(name: "precio_unidad", type: "decimal", nullable: true)]
    private ?string $precioUnidad;

    #[Column(type: "decimal", nullable: true)]
    private ?string $precio;

    #[Column(type: "decimal", nullable: true)]
    private ?string $alicuota;

    #[Column(name: "cantidad_movimiento_consignacion", type: "decimal", nullable: true)]
    private ?string $cantidadMovimientoConsignacion;

    #[Column(name: "id_consignacion", type: "bigint", nullable: true)]
    private ?string $idConsignacion;

    #[Column(name: "check_ne", type: "string", nullable: true)]
    private ?string $checkNe;

    #[Column(name: "packer_cantidad", type: "decimal", nullable: true)]
    private ?string $packerCantidad;

    #[Column(type: "string", nullable: true)]
    private ?string $newdata;

    public function __construct()
    {
        $this->checkNe = "N";
        $this->newdata = "S";
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

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
        return $this;
    }

    public function getIdDocumento(): ?int
    {
        return $this->idDocumento;
    }

    public function setIdDocumento(?int $value): static
    {
        $this->idDocumento = $value;
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

    public function getLote(): ?string
    {
        return HtmlDecode($this->lote);
    }

    public function setLote(?string $value): static
    {
        $this->lote = RemoveXss($value);
        return $this;
    }

    public function getFechaVencimiento(): ?DateTime
    {
        return $this->fechaVencimiento;
    }

    public function setFechaVencimiento(?DateTime $value): static
    {
        $this->fechaVencimiento = $value;
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

    public function getIdCompra(): ?string
    {
        return $this->idCompra;
    }

    public function setIdCompra(?string $value): static
    {
        $this->idCompra = $value;
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

    public function getArticuloUnidadMedida(): ?string
    {
        return HtmlDecode($this->articuloUnidadMedida);
    }

    public function setArticuloUnidadMedida(?string $value): static
    {
        $this->articuloUnidadMedida = RemoveXss($value);
        return $this;
    }

    public function getCantidadUnidadMedida(): ?string
    {
        return $this->cantidadUnidadMedida;
    }

    public function setCantidadUnidadMedida(?string $value): static
    {
        $this->cantidadUnidadMedida = $value;
        return $this;
    }

    public function getCantidadMovimiento(): ?string
    {
        return $this->cantidadMovimiento;
    }

    public function setCantidadMovimiento(?string $value): static
    {
        $this->cantidadMovimiento = $value;
        return $this;
    }

    public function getPrecioUnidadSinDesc(): ?string
    {
        return $this->precioUnidadSinDesc;
    }

    public function setPrecioUnidadSinDesc(?string $value): static
    {
        $this->precioUnidadSinDesc = $value;
        return $this;
    }

    public function getDescuento(): ?string
    {
        return $this->descuento;
    }

    public function setDescuento(?string $value): static
    {
        $this->descuento = $value;
        return $this;
    }

    public function getCostoUnidad(): ?string
    {
        return $this->costoUnidad;
    }

    public function setCostoUnidad(?string $value): static
    {
        $this->costoUnidad = $value;
        return $this;
    }

    public function getCosto(): ?string
    {
        return $this->costo;
    }

    public function setCosto(?string $value): static
    {
        $this->costo = $value;
        return $this;
    }

    public function getPrecioUnidad(): ?string
    {
        return $this->precioUnidad;
    }

    public function setPrecioUnidad(?string $value): static
    {
        $this->precioUnidad = $value;
        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(?string $value): static
    {
        $this->precio = $value;
        return $this;
    }

    public function getAlicuota(): ?string
    {
        return $this->alicuota;
    }

    public function setAlicuota(?string $value): static
    {
        $this->alicuota = $value;
        return $this;
    }

    public function getCantidadMovimientoConsignacion(): ?string
    {
        return $this->cantidadMovimientoConsignacion;
    }

    public function setCantidadMovimientoConsignacion(?string $value): static
    {
        $this->cantidadMovimientoConsignacion = $value;
        return $this;
    }

    public function getIdConsignacion(): ?string
    {
        return $this->idConsignacion;
    }

    public function setIdConsignacion(?string $value): static
    {
        $this->idConsignacion = $value;
        return $this;
    }

    public function getCheckNe(): ?string
    {
        return $this->checkNe;
    }

    public function setCheckNe(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'check_ne' value");
        }
        $this->checkNe = $value;
        return $this;
    }

    public function getPackerCantidad(): ?string
    {
        return $this->packerCantidad;
    }

    public function setPackerCantidad(?string $value): static
    {
        $this->packerCantidad = $value;
        return $this;
    }

    public function getNewdata(): ?string
    {
        return $this->newdata;
    }

    public function setNewdata(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'newdata' value");
        }
        $this->newdata = $value;
        return $this;
    }
}
