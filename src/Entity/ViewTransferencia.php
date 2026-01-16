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
 * Entity class for "view_transferencias" table
 */
#[Entity]
#[Table(name: "view_transferencias")]
class ViewTransferencia extends AbstractEntity
{
    #[Column(type: "bigint")]
    private string $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

    #[Column(type: "integer")]
    private int $iddoc;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(type: "string", nullable: true)]
    private ?string $lote;

    #[Column(name: "fecha_vencimiento", type: "date", nullable: true)]
    private ?DateTime $fechaVencimiento;

    #[Column(name: "cantidad_articulo", type: "decimal", nullable: true)]
    private ?string $cantidadArticulo;

    #[Column(type: "string", nullable: true)]
    private ?string $almacen;

    public function __construct()
    {
        $this->id = "0";
        $this->iddoc = 0;
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

    public function getUsername(): ?string
    {
        return HtmlDecode($this->username);
    }

    public function setUsername(?string $value): static
    {
        $this->username = RemoveXss($value);
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

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
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

    public function getNota(): ?string
    {
        return HtmlDecode($this->nota);
    }

    public function setNota(?string $value): static
    {
        $this->nota = RemoveXss($value);
        return $this;
    }

    public function getEstatus(): ?string
    {
        return HtmlDecode($this->estatus);
    }

    public function setEstatus(?string $value): static
    {
        $this->estatus = RemoveXss($value);
        return $this;
    }

    public function getIddoc(): int
    {
        return $this->iddoc;
    }

    public function setIddoc(int $value): static
    {
        $this->iddoc = $value;
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

    public function getCantidadArticulo(): ?string
    {
        return $this->cantidadArticulo;
    }

    public function setCantidadArticulo(?string $value): static
    {
        $this->cantidadArticulo = $value;
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
}
