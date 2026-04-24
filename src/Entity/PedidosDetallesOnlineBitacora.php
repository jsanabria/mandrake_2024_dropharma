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
 * Entity class for "pedidos_detalles_online_bitacora" table
 */
#[Entity]
#[Table(name: "pedidos_detalles_online_bitacora")]
class PedidosDetallesOnlineBitacora extends AbstractEntity
{
    #[Id]
    #[Column(type: "bigint", unique: true)]
    #[GeneratedValue]
    private string $id;

    #[Column(name: "id_documento", type: "integer", nullable: true)]
    private ?int $idDocumento;

    #[Column(type: "integer", nullable: true)]
    private ?int $fabricante;

    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(name: "cantidad_solicitada", type: "decimal", nullable: true)]
    private ?string $cantidadSolicitada;

    #[Column(type: "decimal", nullable: true)]
    private ?string $cantidadasignada;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $value): static
    {
        $this->id = $value;
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

    public function getCantidadSolicitada(): ?string
    {
        return $this->cantidadSolicitada;
    }

    public function setCantidadSolicitada(?string $value): static
    {
        $this->cantidadSolicitada = $value;
        return $this;
    }

    public function getCantidadasignada(): ?string
    {
        return $this->cantidadasignada;
    }

    public function setCantidadasignada(?string $value): static
    {
        $this->cantidadasignada = $value;
        return $this;
    }
}
