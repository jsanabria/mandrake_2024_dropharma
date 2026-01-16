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
 * Entity class for "compra" table
 */
#[Entity]
#[Table(name: "compra")]
class Compra extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(name: "doc_afectado", type: "string", nullable: true)]
    private ?string $docAfectado;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(name: "nro_control", type: "string", nullable: true)]
    private ?string $nroControl;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(name: "aplica_retencion", type: "string", nullable: true)]
    private ?string $aplicaRetencion;

    #[Column(name: "monto_exento", type: "decimal", nullable: true)]
    private ?string $montoExento;

    #[Column(name: "monto_gravado", type: "decimal", nullable: true)]
    private ?string $montoGravado;

    #[Column(type: "decimal", nullable: true)]
    private ?string $alicuota;

    #[Column(name: "monto_iva", type: "decimal", nullable: true)]
    private ?string $montoIva;

    #[Column(name: "monto_total", type: "decimal", nullable: true)]
    private ?string $montoTotal;

    #[Column(name: "monto_pagar", type: "decimal", nullable: true)]
    private ?string $montoPagar;

    #[Column(name: "ret_iva", type: "decimal", nullable: true)]
    private ?string $retIva;

    #[Column(name: "ref_iva", type: "string", nullable: true)]
    private ?string $refIva;

    #[Column(name: "ret_islr", type: "decimal", nullable: true)]
    private ?string $retIslr;

    #[Column(name: "ref_islr", type: "string", nullable: true)]
    private ?string $refIslr;

    #[Column(name: "ret_municipal", type: "decimal", nullable: true)]
    private ?string $retMunicipal;

    #[Column(name: "ref_municipal", type: "string", nullable: true)]
    private ?string $refMunicipal;

    #[Column(name: "fecha_registro", type: "date", nullable: true)]
    private ?DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "integer", nullable: true)]
    private ?int $comprobante;

    #[Column(name: "tipo_iva", type: "string", nullable: true)]
    private ?string $tipoIva;

    #[Column(name: "tipo_islr", type: "string", nullable: true)]
    private ?string $tipoIslr;

    #[Column(type: "decimal", nullable: true)]
    private ?string $sustraendo;

    #[Column(name: "tipo_municipal", type: "string", nullable: true)]
    private ?string $tipoMunicipal;

    #[Column(type: "string", nullable: true)]
    private ?string $anulado;

    public function __construct()
    {
        $this->anulado = "N";
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

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
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

    public function getDocAfectado(): ?string
    {
        return HtmlDecode($this->docAfectado);
    }

    public function setDocAfectado(?string $value): static
    {
        $this->docAfectado = RemoveXss($value);
        return $this;
    }

    public function getDocumento(): ?string
    {
        return HtmlDecode($this->documento);
    }

    public function setDocumento(?string $value): static
    {
        $this->documento = RemoveXss($value);
        return $this;
    }

    public function getNroControl(): ?string
    {
        return HtmlDecode($this->nroControl);
    }

    public function setNroControl(?string $value): static
    {
        $this->nroControl = RemoveXss($value);
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

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
        return $this;
    }

    public function getAplicaRetencion(): ?string
    {
        return $this->aplicaRetencion;
    }

    public function setAplicaRetencion(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'aplica_retencion' value");
        }
        $this->aplicaRetencion = $value;
        return $this;
    }

    public function getMontoExento(): ?string
    {
        return $this->montoExento;
    }

    public function setMontoExento(?string $value): static
    {
        $this->montoExento = $value;
        return $this;
    }

    public function getMontoGravado(): ?string
    {
        return $this->montoGravado;
    }

    public function setMontoGravado(?string $value): static
    {
        $this->montoGravado = $value;
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

    public function getMontoIva(): ?string
    {
        return $this->montoIva;
    }

    public function setMontoIva(?string $value): static
    {
        $this->montoIva = $value;
        return $this;
    }

    public function getMontoTotal(): ?string
    {
        return $this->montoTotal;
    }

    public function setMontoTotal(?string $value): static
    {
        $this->montoTotal = $value;
        return $this;
    }

    public function getMontoPagar(): ?string
    {
        return $this->montoPagar;
    }

    public function setMontoPagar(?string $value): static
    {
        $this->montoPagar = $value;
        return $this;
    }

    public function getRetIva(): ?string
    {
        return $this->retIva;
    }

    public function setRetIva(?string $value): static
    {
        $this->retIva = $value;
        return $this;
    }

    public function getRefIva(): ?string
    {
        return HtmlDecode($this->refIva);
    }

    public function setRefIva(?string $value): static
    {
        $this->refIva = RemoveXss($value);
        return $this;
    }

    public function getRetIslr(): ?string
    {
        return $this->retIslr;
    }

    public function setRetIslr(?string $value): static
    {
        $this->retIslr = $value;
        return $this;
    }

    public function getRefIslr(): ?string
    {
        return HtmlDecode($this->refIslr);
    }

    public function setRefIslr(?string $value): static
    {
        $this->refIslr = RemoveXss($value);
        return $this;
    }

    public function getRetMunicipal(): ?string
    {
        return $this->retMunicipal;
    }

    public function setRetMunicipal(?string $value): static
    {
        $this->retMunicipal = $value;
        return $this;
    }

    public function getRefMunicipal(): ?string
    {
        return HtmlDecode($this->refMunicipal);
    }

    public function setRefMunicipal(?string $value): static
    {
        $this->refMunicipal = RemoveXss($value);
        return $this;
    }

    public function getFechaRegistro(): ?DateTime
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(?DateTime $value): static
    {
        $this->fechaRegistro = $value;
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

    public function getComprobante(): ?int
    {
        return $this->comprobante;
    }

    public function setComprobante(?int $value): static
    {
        $this->comprobante = $value;
        return $this;
    }

    public function getTipoIva(): ?string
    {
        return HtmlDecode($this->tipoIva);
    }

    public function setTipoIva(?string $value): static
    {
        $this->tipoIva = RemoveXss($value);
        return $this;
    }

    public function getTipoIslr(): ?string
    {
        return HtmlDecode($this->tipoIslr);
    }

    public function setTipoIslr(?string $value): static
    {
        $this->tipoIslr = RemoveXss($value);
        return $this;
    }

    public function getSustraendo(): ?string
    {
        return $this->sustraendo;
    }

    public function setSustraendo(?string $value): static
    {
        $this->sustraendo = $value;
        return $this;
    }

    public function getTipoMunicipal(): ?string
    {
        return HtmlDecode($this->tipoMunicipal);
    }

    public function setTipoMunicipal(?string $value): static
    {
        $this->tipoMunicipal = RemoveXss($value);
        return $this;
    }

    public function getAnulado(): ?string
    {
        return $this->anulado;
    }

    public function setAnulado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'anulado' value");
        }
        $this->anulado = $value;
        return $this;
    }
}
