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
 * Entity class for "historia_articulo" table
 */
#[Entity]
#[Table(name: "historia_articulo")]
class HistoriaArticulo extends AbstractEntity
{
    #[Column(type: "integer", nullable: true)]
    private ?int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(type: "string", nullable: true)]
    private ?string $almacen;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $lote;

    #[Column(name: "fecha_vencimiento", type: "string", nullable: true)]
    private ?string $fechaVencimiento;

    #[Column(type: "string", nullable: true)]
    private ?string $usuario;

    #[Column(type: "decimal", nullable: true)]
    private ?string $entradas;

    #[Column(type: "decimal", nullable: true)]
    private ?string $salidas;

    #[Column(type: "decimal", nullable: true)]
    private ?string $existencia;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $idx;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $value): static
    {
        $this->id = $value;
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

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
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

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
        return $this;
    }

    public function getNroDocumento(): ?string
    {
        return HtmlDecode($this->nroDocumento);
    }

    public function setNroDocumento(?string $value): static
    {
        $this->nroDocumento = RemoveXss($value);
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

    public function getUsuario(): ?string
    {
        return HtmlDecode($this->usuario);
    }

    public function setUsuario(?string $value): static
    {
        $this->usuario = RemoveXss($value);
        return $this;
    }

    public function getEntradas(): ?string
    {
        return $this->entradas;
    }

    public function setEntradas(?string $value): static
    {
        $this->entradas = $value;
        return $this;
    }

    public function getSalidas(): ?string
    {
        return $this->salidas;
    }

    public function setSalidas(?string $value): static
    {
        $this->salidas = $value;
        return $this;
    }

    public function getExistencia(): ?string
    {
        return $this->existencia;
    }

    public function setExistencia(?string $value): static
    {
        $this->existencia = $value;
        return $this;
    }

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
        return $this;
    }

    public function getIdx(): string
    {
        return $this->idx;
    }

    public function setIdx(string $value): static
    {
        $this->idx = $value;
        return $this;
    }
}
