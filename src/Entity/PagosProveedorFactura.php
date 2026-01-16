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
 * Entity class for "pagos_proveedor_factura" table
 */
#[Entity]
#[Table(name: "pagos_proveedor_factura")]
class PagosProveedorFactura extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "pagos_proveedor", type: "integer", nullable: true)]
    private ?int $pagosProveedor;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "id_documento", type: "integer", nullable: true)]
    private ?int $idDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $abono;

    #[Column(type: "decimal", nullable: true)]
    private ?string $monto;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(type: "string", nullable: true)]
    private ?string $retiva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $retivamonto;

    #[Column(type: "string", nullable: true)]
    private ?string $retislr;

    #[Column(type: "decimal", nullable: true)]
    private ?string $retislrmonto;

    public function __construct()
    {
        $this->abono = "N";
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

    public function getPagosProveedor(): ?int
    {
        return $this->pagosProveedor;
    }

    public function setPagosProveedor(?int $value): static
    {
        $this->pagosProveedor = $value;
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

    public function getAbono(): ?string
    {
        return $this->abono;
    }

    public function setAbono(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'abono' value");
        }
        $this->abono = $value;
        return $this;
    }

    public function getMonto(): ?string
    {
        return $this->monto;
    }

    public function setMonto(?string $value): static
    {
        $this->monto = $value;
        return $this;
    }

    public function getComprobante(): ?int
    {
        return $this->comprobante;
    }

    public function setComprobante(?int $value): static
    {
        $this->comprobante = $value;
        return $this;
    }

    public function getRetiva(): ?string
    {
        return HtmlDecode($this->retiva);
    }

    public function setRetiva(?string $value): static
    {
        $this->retiva = RemoveXss($value);
        return $this;
    }

    public function getRetivamonto(): ?string
    {
        return $this->retivamonto;
    }

    public function setRetivamonto(?string $value): static
    {
        $this->retivamonto = $value;
        return $this;
    }

    public function getRetislr(): ?string
    {
        return HtmlDecode($this->retislr);
    }

    public function setRetislr(?string $value): static
    {
        $this->retislr = RemoveXss($value);
        return $this;
    }

    public function getRetislrmonto(): ?string
    {
        return $this->retislrmonto;
    }

    public function setRetislrmonto(?string $value): static
    {
        $this->retislrmonto = $value;
        return $this;
    }
}
