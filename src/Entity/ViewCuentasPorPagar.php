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
 * Entity class for "view_cuentas_por_pagar" table
 */
#[Entity]
#[Table(name: "view_cuentas_por_pagar")]
class ViewCuentasPorPagar extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(name: "proveedor_rif", type: "string", nullable: true)]
    private ?string $proveedorRif;

    #[Column(name: "proveedor_nombre", type: "string", nullable: true)]
    private ?string $proveedorNombre;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(name: "nro_control", type: "string", nullable: true)]
    private ?string $nroControl;

    #[Column(type: "date", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "fecha_ultimo_pago", type: "date", nullable: true)]
    private ?DateTime $fechaUltimoPago;

    #[Column(name: "fecha_registro", type: "date", nullable: true)]
    private ?DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(name: "doc_afectado", type: "string", nullable: true)]
    private ?string $docAfectado;

    #[Column(type: "string", nullable: true)]
    private ?string $anulado;

    #[Column(type: "string", nullable: true)]
    private ?string $pagado;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(name: "tasa_dia", type: "decimal")]
    private string $tasaDia;

    #[Column(name: "signo_documento", type: "integer")]
    private int $signoDocumento;

    #[Column(name: "monto_documento_moneda", type: "decimal", nullable: true)]
    private ?string $montoDocumentoMoneda;

    #[Column(name: "monto_documento_bs", type: "decimal", nullable: true)]
    private ?string $montoDocumentoBs;

    #[Column(name: "monto_documento_usd", type: "decimal", nullable: true)]
    private ?string $montoDocumentoUsd;

    #[Column(name: "monto_aplicado_bs", type: "decimal", nullable: true)]
    private ?string $montoAplicadoBs;

    #[Column(name: "monto_aplicado_usd", type: "decimal", nullable: true)]
    private ?string $montoAplicadoUsd;

    #[Column(name: "total_pagado_bs", type: "decimal")]
    private string $totalPagadoBs;

    #[Column(name: "total_pagado_usd", type: "decimal")]
    private string $totalPagadoUsd;

    #[Column(name: "cantidad_pagos", type: "bigint")]
    private string $cantidadPagos;

    #[Column(name: "saldo_bs", type: "decimal", nullable: true)]
    private ?string $saldoBs;

    #[Column(name: "saldo_usd", type: "decimal", nullable: true)]
    private ?string $saldoUsd;

    #[Column(name: "estado_cuenta", type: "string")]
    private string $estadoCuenta;

    #[Column(name: "dias_transcurridos", type: "integer", nullable: true)]
    private ?int $diasTranscurridos;

    #[Column(type: "string")]
    private string $antiguedad;

    public function __construct()
    {
        $this->anulado = "N";
        $this->pagado = "N";
        $this->tasaDia = "0.00";
        $this->signoDocumento = 0;
        $this->totalPagadoBs = "0.00";
        $this->totalPagadoUsd = "0.00";
        $this->cantidadPagos = "0";
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

    public function getProveedorRif(): ?string
    {
        return HtmlDecode($this->proveedorRif);
    }

    public function setProveedorRif(?string $value): static
    {
        $this->proveedorRif = RemoveXss($value);
        return $this;
    }

    public function getProveedorNombre(): ?string
    {
        return HtmlDecode($this->proveedorNombre);
    }

    public function setProveedorNombre(?string $value): static
    {
        $this->proveedorNombre = RemoveXss($value);
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

    public function getFechaUltimoPago(): ?DateTime
    {
        return $this->fechaUltimoPago;
    }

    public function setFechaUltimoPago(?DateTime $value): static
    {
        $this->fechaUltimoPago = $value;
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

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
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

    public function getPagado(): ?string
    {
        return $this->pagado;
    }

    public function setPagado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'pagado' value");
        }
        $this->pagado = $value;
        return $this;
    }

    public function getMoneda(): ?string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(?string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }

    public function getTasaDia(): string
    {
        return $this->tasaDia;
    }

    public function setTasaDia(string $value): static
    {
        $this->tasaDia = $value;
        return $this;
    }

    public function getSignoDocumento(): int
    {
        return $this->signoDocumento;
    }

    public function setSignoDocumento(int $value): static
    {
        $this->signoDocumento = $value;
        return $this;
    }

    public function getMontoDocumentoMoneda(): ?string
    {
        return $this->montoDocumentoMoneda;
    }

    public function setMontoDocumentoMoneda(?string $value): static
    {
        $this->montoDocumentoMoneda = $value;
        return $this;
    }

    public function getMontoDocumentoBs(): ?string
    {
        return $this->montoDocumentoBs;
    }

    public function setMontoDocumentoBs(?string $value): static
    {
        $this->montoDocumentoBs = $value;
        return $this;
    }

    public function getMontoDocumentoUsd(): ?string
    {
        return $this->montoDocumentoUsd;
    }

    public function setMontoDocumentoUsd(?string $value): static
    {
        $this->montoDocumentoUsd = $value;
        return $this;
    }

    public function getMontoAplicadoBs(): ?string
    {
        return $this->montoAplicadoBs;
    }

    public function setMontoAplicadoBs(?string $value): static
    {
        $this->montoAplicadoBs = $value;
        return $this;
    }

    public function getMontoAplicadoUsd(): ?string
    {
        return $this->montoAplicadoUsd;
    }

    public function setMontoAplicadoUsd(?string $value): static
    {
        $this->montoAplicadoUsd = $value;
        return $this;
    }

    public function getTotalPagadoBs(): string
    {
        return $this->totalPagadoBs;
    }

    public function setTotalPagadoBs(string $value): static
    {
        $this->totalPagadoBs = $value;
        return $this;
    }

    public function getTotalPagadoUsd(): string
    {
        return $this->totalPagadoUsd;
    }

    public function setTotalPagadoUsd(string $value): static
    {
        $this->totalPagadoUsd = $value;
        return $this;
    }

    public function getCantidadPagos(): string
    {
        return $this->cantidadPagos;
    }

    public function setCantidadPagos(string $value): static
    {
        $this->cantidadPagos = $value;
        return $this;
    }

    public function getSaldoBs(): ?string
    {
        return $this->saldoBs;
    }

    public function setSaldoBs(?string $value): static
    {
        $this->saldoBs = $value;
        return $this;
    }

    public function getSaldoUsd(): ?string
    {
        return $this->saldoUsd;
    }

    public function setSaldoUsd(?string $value): static
    {
        $this->saldoUsd = $value;
        return $this;
    }

    public function getEstadoCuenta(): string
    {
        return HtmlDecode($this->estadoCuenta);
    }

    public function setEstadoCuenta(string $value): static
    {
        $this->estadoCuenta = RemoveXss($value);
        return $this;
    }

    public function getDiasTranscurridos(): ?int
    {
        return $this->diasTranscurridos;
    }

    public function setDiasTranscurridos(?int $value): static
    {
        $this->diasTranscurridos = $value;
        return $this;
    }

    public function getAntiguedad(): string
    {
        return HtmlDecode($this->antiguedad);
    }

    public function setAntiguedad(string $value): static
    {
        $this->antiguedad = RemoveXss($value);
        return $this;
    }
}
