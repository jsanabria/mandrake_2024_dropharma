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
 * Entity class for "view_cuentas_por_cobrar" table
 */
#[Entity]
#[Table(name: "view_cuentas_por_cobrar")]
class ViewCuentasPorCobrar extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(name: "cliente_rif", type: "string", nullable: true)]
    private ?string $clienteRif;

    #[Column(name: "cliente_nombre", type: "string", nullable: true)]
    private ?string $clienteNombre;

    #[Column(name: "tipo_documento_fiscal", type: "string", nullable: true)]
    private ?string $tipoDocumentoFiscal;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "nro_control", type: "string", nullable: true)]
    private ?string $nroControl;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "fecha_documento", type: "date", nullable: true)]
    private ?DateTime $fechaDocumento;

    #[Column(name: "fecha_vencimiento", type: "date", nullable: true)]
    private ?DateTime $fechaVencimiento;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(name: "tasa_dia", type: "decimal")]
    private string $tasaDia;

    #[Column(name: "dias_credito", type: "integer")]
    private int $diasCredito;

    #[Column(type: "string", nullable: true)]
    private ?string $entregado;

    #[Column(type: "string", nullable: true)]
    private ?string $pagado;

    #[Column(name: "doc_afectado", type: "string", nullable: true)]
    private ?string $docAfectado;

    #[Column(name: "doc_afe", type: "integer", nullable: true)]
    private ?int $docAfe;

    #[Column(type: "string", nullable: true)]
    private ?string $igtf;

    #[Column(name: "monto_igtf_bs", type: "decimal")]
    private string $montoIgtfBs;

    #[Column(name: "signo_documento", type: "integer")]
    private int $signoDocumento;

    #[Column(name: "monto_documento_moneda", type: "decimal", nullable: true)]
    private ?string $montoDocumentoMoneda;

    #[Column(name: "monto_documento_bs", type: "decimal")]
    private string $montoDocumentoBs;

    #[Column(name: "monto_documento_usd", type: "decimal", nullable: true)]
    private ?string $montoDocumentoUsd;

    #[Column(name: "monto_aplicado_bs", type: "decimal")]
    private string $montoAplicadoBs;

    #[Column(name: "monto_aplicado_usd", type: "decimal", nullable: true)]
    private ?string $montoAplicadoUsd;

    #[Column(name: "total_cobrado_bs", type: "decimal")]
    private string $totalCobradoBs;

    #[Column(name: "total_cobrado_usd", type: "decimal")]
    private string $totalCobradoUsd;

    #[Column(name: "cantidad_cobros", type: "bigint")]
    private string $cantidadCobros;

    #[Column(name: "fecha_ultimo_cobro", type: "date", nullable: true)]
    private ?DateTime $fechaUltimoCobro;

    #[Column(name: "saldo_bs", type: "decimal")]
    private string $saldoBs;

    #[Column(name: "saldo_usd", type: "decimal", nullable: true)]
    private ?string $saldoUsd;

    #[Column(name: "estado_cuenta", type: "string")]
    private string $estadoCuenta;

    #[Column(name: "dias_vencido", type: "integer", nullable: true)]
    private ?int $diasVencido;

    #[Column(type: "string")]
    private string $antiguedad;

    public function __construct()
    {
        $this->tasaDia = "0.00";
        $this->diasCredito = 0;
        $this->entregado = "N";
        $this->pagado = "N";
        $this->montoIgtfBs = "0.00";
        $this->signoDocumento = 0;
        $this->montoDocumentoBs = "0.0000";
        $this->montoAplicadoBs = "0.00";
        $this->totalCobradoBs = "0.00";
        $this->totalCobradoUsd = "0.00";
        $this->cantidadCobros = "0";
        $this->saldoBs = "0.00";
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

    public function getCliente(): ?int
    {
        return $this->cliente;
    }

    public function setCliente(?int $value): static
    {
        $this->cliente = $value;
        return $this;
    }

    public function getClienteRif(): ?string
    {
        return HtmlDecode($this->clienteRif);
    }

    public function setClienteRif(?string $value): static
    {
        $this->clienteRif = RemoveXss($value);
        return $this;
    }

    public function getClienteNombre(): ?string
    {
        return HtmlDecode($this->clienteNombre);
    }

    public function setClienteNombre(?string $value): static
    {
        $this->clienteNombre = RemoveXss($value);
        return $this;
    }

    public function getTipoDocumentoFiscal(): ?string
    {
        return HtmlDecode($this->tipoDocumentoFiscal);
    }

    public function setTipoDocumentoFiscal(?string $value): static
    {
        $this->tipoDocumentoFiscal = RemoveXss($value);
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

    public function getFechaDocumento(): ?DateTime
    {
        return $this->fechaDocumento;
    }

    public function setFechaDocumento(?DateTime $value): static
    {
        $this->fechaDocumento = $value;
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

    public function getDiasCredito(): int
    {
        return $this->diasCredito;
    }

    public function setDiasCredito(int $value): static
    {
        $this->diasCredito = $value;
        return $this;
    }

    public function getEntregado(): ?string
    {
        return $this->entregado;
    }

    public function setEntregado(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'entregado' value");
        }
        $this->entregado = $value;
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

    public function getDocAfectado(): ?string
    {
        return HtmlDecode($this->docAfectado);
    }

    public function setDocAfectado(?string $value): static
    {
        $this->docAfectado = RemoveXss($value);
        return $this;
    }

    public function getDocAfe(): ?int
    {
        return $this->docAfe;
    }

    public function setDocAfe(?int $value): static
    {
        $this->docAfe = $value;
        return $this;
    }

    public function getIgtf(): ?string
    {
        return $this->igtf;
    }

    public function setIgtf(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'igtf' value");
        }
        $this->igtf = $value;
        return $this;
    }

    public function getMontoIgtfBs(): string
    {
        return $this->montoIgtfBs;
    }

    public function setMontoIgtfBs(string $value): static
    {
        $this->montoIgtfBs = $value;
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

    public function getMontoDocumentoBs(): string
    {
        return $this->montoDocumentoBs;
    }

    public function setMontoDocumentoBs(string $value): static
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

    public function getMontoAplicadoBs(): string
    {
        return $this->montoAplicadoBs;
    }

    public function setMontoAplicadoBs(string $value): static
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

    public function getTotalCobradoBs(): string
    {
        return $this->totalCobradoBs;
    }

    public function setTotalCobradoBs(string $value): static
    {
        $this->totalCobradoBs = $value;
        return $this;
    }

    public function getTotalCobradoUsd(): string
    {
        return $this->totalCobradoUsd;
    }

    public function setTotalCobradoUsd(string $value): static
    {
        $this->totalCobradoUsd = $value;
        return $this;
    }

    public function getCantidadCobros(): string
    {
        return $this->cantidadCobros;
    }

    public function setCantidadCobros(string $value): static
    {
        $this->cantidadCobros = $value;
        return $this;
    }

    public function getFechaUltimoCobro(): ?DateTime
    {
        return $this->fechaUltimoCobro;
    }

    public function setFechaUltimoCobro(?DateTime $value): static
    {
        $this->fechaUltimoCobro = $value;
        return $this;
    }

    public function getSaldoBs(): string
    {
        return $this->saldoBs;
    }

    public function setSaldoBs(string $value): static
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

    public function getDiasVencido(): ?int
    {
        return $this->diasVencido;
    }

    public function setDiasVencido(?int $value): static
    {
        $this->diasVencido = $value;
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
