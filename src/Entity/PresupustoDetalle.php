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
 * Entity class for "presupusto_detalle" table
 */
#[Entity]
#[Table(name: "presupusto_detalle")]
class PresupustoDetalle extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $presupuesto;

    #[Column(type: "string", nullable: true)]
    private ?string $grupo1;

    #[Column(type: "string", nullable: true)]
    private ?string $grupo2;

    #[Column(type: "integer", nullable: true)]
    private ?int $numero;

    #[Column(type: "string", nullable: true)]
    private ?string $articulo;

    #[Column(type: "string", nullable: true)]
    private ?string $linea;

    #[Column(type: "string", nullable: true)]
    private ?string $imagen;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(type: "integer", nullable: true)]
    private ?int $cantidad;

    #[Column(type: "decimal", nullable: true)]
    private ?string $precio;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getPresupuesto(): ?int
    {
        return $this->presupuesto;
    }

    public function setPresupuesto(?int $value): static
    {
        $this->presupuesto = $value;
        return $this;
    }

    public function getGrupo1(): ?string
    {
        return HtmlDecode($this->grupo1);
    }

    public function setGrupo1(?string $value): static
    {
        $this->grupo1 = RemoveXss($value);
        return $this;
    }

    public function getGrupo2(): ?string
    {
        return HtmlDecode($this->grupo2);
    }

    public function setGrupo2(?string $value): static
    {
        $this->grupo2 = RemoveXss($value);
        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $value): static
    {
        $this->numero = $value;
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

    public function getLinea(): ?string
    {
        return HtmlDecode($this->linea);
    }

    public function setLinea(?string $value): static
    {
        $this->linea = RemoveXss($value);
        return $this;
    }

    public function getImagen(): ?string
    {
        return HtmlDecode($this->imagen);
    }

    public function setImagen(?string $value): static
    {
        $this->imagen = RemoveXss($value);
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

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $value): static
    {
        $this->cantidad = $value;
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

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $value): static
    {
        $this->total = $value;
        return $this;
    }
}
