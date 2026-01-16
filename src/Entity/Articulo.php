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
 * Entity class for "articulo" table
 */
#[Entity]
#[Table(name: "articulo")]
class Articulo extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $foto;

    #[Column(type: "string", nullable: true)]
    private ?string $codigo;

    #[Column(name: "nombre_comercial", type: "string", nullable: true)]
    private ?string $nombreComercial;

    #[Column(name: "principio_activo", type: "string", nullable: true)]
    private ?string $principioActivo;

    #[Column(type: "string", nullable: true)]
    private ?string $presentacion;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(name: "codigo_ims", type: "string", nullable: true)]
    private ?string $codigoIms;

    #[Column(name: "codigo_de_barra", type: "string", nullable: true)]
    private ?string $codigoDeBarra;

    #[Column(type: "string", nullable: true)]
    private ?string $categoria;

    #[Column(name: "lista_pedido", type: "string", nullable: true)]
    private ?string $listaPedido;

    #[Column(name: "unidad_medida_defecto", type: "string", nullable: true)]
    private ?string $unidadMedidaDefecto;

    #[Column(name: "cantidad_por_unidad_medida", type: "decimal", nullable: true)]
    private ?string $cantidadPorUnidadMedida;

    #[Column(name: "cantidad_minima", type: "decimal")]
    private string $cantidadMinima;

    #[Column(name: "cantidad_maxima", type: "decimal")]
    private string $cantidadMaxima;

    #[Column(name: "cantidad_en_mano", type: "decimal")]
    private string $cantidadEnMano;

    #[Column(name: "cantidad_en_almacenes", type: "decimal")]
    private string $cantidadEnAlmacenes;

    #[Column(name: "cantidad_en_pedido", type: "decimal")]
    private string $cantidadEnPedido;

    #[Column(name: "cantidad_en_transito", type: "decimal")]
    private string $cantidadEnTransito;

    #[Column(name: "ultimo_costo", type: "decimal")]
    private string $ultimoCosto;

    #[Column(type: "decimal")]
    private string $descuento;

    #[Column(type: "decimal")]
    private string $precio;

    #[Column(type: "string", nullable: true)]
    private ?string $alicuota;

    #[Column(name: "articulo_inventario", type: "string", nullable: true)]
    private ?string $articuloInventario;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    #[Column(type: "string", nullable: true)]
    private ?string $lote;

    #[Column(name: "fecha_vencimiento", type: "date", nullable: true)]
    private ?DateTime $fechaVencimiento;

    public function __construct()
    {
        $this->cantidadMinima = "0";
        $this->cantidadMaxima = "0";
        $this->cantidadEnMano = "0.00";
        $this->cantidadEnAlmacenes = "0.00";
        $this->cantidadEnPedido = "0.00";
        $this->cantidadEnTransito = "0.00";
        $this->ultimoCosto = "0.00";
        $this->descuento = "0.00";
        $this->precio = "0.00";
        $this->alicuota = "0";
        $this->articuloInventario = "S";
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

    public function getFoto(): ?string
    {
        return HtmlDecode($this->foto);
    }

    public function setFoto(?string $value): static
    {
        $this->foto = RemoveXss($value);
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

    public function getFabricante(): ?int
    {
        return $this->fabricante;
    }

    public function setFabricante(?int $value): static
    {
        $this->fabricante = $value;
        return $this;
    }

    public function getCodigoIms(): ?string
    {
        return HtmlDecode($this->codigoIms);
    }

    public function setCodigoIms(?string $value): static
    {
        $this->codigoIms = RemoveXss($value);
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

    public function getCategoria(): ?string
    {
        return HtmlDecode($this->categoria);
    }

    public function setCategoria(?string $value): static
    {
        $this->categoria = RemoveXss($value);
        return $this;
    }

    public function getListaPedido(): ?string
    {
        return HtmlDecode($this->listaPedido);
    }

    public function setListaPedido(?string $value): static
    {
        $this->listaPedido = RemoveXss($value);
        return $this;
    }

    public function getUnidadMedidaDefecto(): ?string
    {
        return HtmlDecode($this->unidadMedidaDefecto);
    }

    public function setUnidadMedidaDefecto(?string $value): static
    {
        $this->unidadMedidaDefecto = RemoveXss($value);
        return $this;
    }

    public function getCantidadPorUnidadMedida(): ?string
    {
        return $this->cantidadPorUnidadMedida;
    }

    public function setCantidadPorUnidadMedida(?string $value): static
    {
        $this->cantidadPorUnidadMedida = $value;
        return $this;
    }

    public function getCantidadMinima(): string
    {
        return $this->cantidadMinima;
    }

    public function setCantidadMinima(string $value): static
    {
        $this->cantidadMinima = $value;
        return $this;
    }

    public function getCantidadMaxima(): string
    {
        return $this->cantidadMaxima;
    }

    public function setCantidadMaxima(string $value): static
    {
        $this->cantidadMaxima = $value;
        return $this;
    }

    public function getCantidadEnMano(): string
    {
        return $this->cantidadEnMano;
    }

    public function setCantidadEnMano(string $value): static
    {
        $this->cantidadEnMano = $value;
        return $this;
    }

    public function getCantidadEnAlmacenes(): string
    {
        return $this->cantidadEnAlmacenes;
    }

    public function setCantidadEnAlmacenes(string $value): static
    {
        $this->cantidadEnAlmacenes = $value;
        return $this;
    }

    public function getCantidadEnPedido(): string
    {
        return $this->cantidadEnPedido;
    }

    public function setCantidadEnPedido(string $value): static
    {
        $this->cantidadEnPedido = $value;
        return $this;
    }

    public function getCantidadEnTransito(): string
    {
        return $this->cantidadEnTransito;
    }

    public function setCantidadEnTransito(string $value): static
    {
        $this->cantidadEnTransito = $value;
        return $this;
    }

    public function getUltimoCosto(): string
    {
        return $this->ultimoCosto;
    }

    public function setUltimoCosto(string $value): static
    {
        $this->ultimoCosto = $value;
        return $this;
    }

    public function getDescuento(): string
    {
        return $this->descuento;
    }

    public function setDescuento(string $value): static
    {
        $this->descuento = $value;
        return $this;
    }

    public function getPrecio(): string
    {
        return $this->precio;
    }

    public function setPrecio(string $value): static
    {
        $this->precio = $value;
        return $this;
    }

    public function getAlicuota(): ?string
    {
        return HtmlDecode($this->alicuota);
    }

    public function setAlicuota(?string $value): static
    {
        $this->alicuota = RemoveXss($value);
        return $this;
    }

    public function getArticuloInventario(): ?string
    {
        return $this->articuloInventario;
    }

    public function setArticuloInventario(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'articulo_inventario' value");
        }
        $this->articuloInventario = $value;
        return $this;
    }

    public function getActivo(): ?string
    {
        return $this->activo;
    }

    public function setActivo(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'activo' value");
        }
        $this->activo = $value;
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
}
