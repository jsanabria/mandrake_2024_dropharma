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
 * Entity class for "temp_consignacion" table
 */
#[Entity]
#[Table(name: "temp_consignacion")]
class TempConsignacion extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "id_documento", type: "integer", nullable: true)]
    private ?int $idDocumento;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(name: "cantidad_movimiento", type: "decimal", nullable: true)]
    private ?string $cantidadMovimiento;

    #[Column(name: "cantidad_entre_fechas", type: "decimal", nullable: true)]
    private ?string $cantidadEntreFechas;

    #[Column(name: "cantidad_acumulada", type: "decimal", nullable: true)]
    private ?string $cantidadAcumulada;

    #[Column(name: "cantidad_ajuste", type: "decimal", nullable: true)]
    private ?string $cantidadAjuste;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
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

    public function getNroDocumento(): ?string
    {
        return HtmlDecode($this->nroDocumento);
    }

    public function setNroDocumento(?string $value): static
    {
        $this->nroDocumento = RemoveXss($value);
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

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
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

    public function getCantidadMovimiento(): ?string
    {
        return $this->cantidadMovimiento;
    }

    public function setCantidadMovimiento(?string $value): static
    {
        $this->cantidadMovimiento = $value;
        return $this;
    }

    public function getCantidadEntreFechas(): ?string
    {
        return $this->cantidadEntreFechas;
    }

    public function setCantidadEntreFechas(?string $value): static
    {
        $this->cantidadEntreFechas = $value;
        return $this;
    }

    public function getCantidadAcumulada(): ?string
    {
        return $this->cantidadAcumulada;
    }

    public function setCantidadAcumulada(?string $value): static
    {
        $this->cantidadAcumulada = $value;
        return $this;
    }

    public function getCantidadAjuste(): ?string
    {
        return $this->cantidadAjuste;
    }

    public function setCantidadAjuste(?string $value): static
    {
        $this->cantidadAjuste = $value;
        return $this;
    }
}
