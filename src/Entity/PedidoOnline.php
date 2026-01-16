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
 * Entity class for "pedido_online" table
 */
#[Entity]
#[Table(name: "pedido_online")]
class PedidoOnline extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_documento", type: "string", nullable: true)]
    private ?string $tipoDocumento;

    #[Column(type: "string", nullable: true)]
    private ?string $asesor;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(name: "nro_documento", type: "string", nullable: true)]
    private ?string $nroDocumento;

    #[Column(name: "nro_control", type: "string", nullable: true)]
    private ?string $nroControl;

    #[Column(name: "monto_total", type: "decimal", nullable: true)]
    private ?string $montoTotal;

    #[Column(name: "alicuota_iva", type: "decimal", nullable: true)]
    private ?string $alicuotaIva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $iva;

    #[Column(type: "decimal", nullable: true)]
    private ?string $total;

    #[Column(type: "string", nullable: true)]
    private ?string $nota;

    #[Column(type: "string", nullable: true)]
    private ?string $estatus;

    #[Column(name: "id_documento_padre", type: "integer", nullable: true)]
    private ?int $idDocumentoPadre;

    #[Column(type: "string", nullable: true)]
    private ?string $moneda;

    #[Column(type: "string", nullable: true)]
    private ?string $documento;

    #[Column(name: "tasa_dia", type: "decimal", nullable: true)]
    private ?string $tasaDia;

    #[Column(name: "monto_usd", type: "decimal", nullable: true)]
    private ?string $montoUsd;

    #[Column(name: "dias_credito", type: "integer", nullable: true)]
    private ?int $diasCredito;

    #[Column(type: "string", nullable: true)]
    private ?string $entregado;

    #[Column(name: "fecha_entrega", type: "date", nullable: true)]
    private ?DateTime $fechaEntrega;

    #[Column(type: "string", nullable: true)]
    private ?string $pagado;

    public function __construct()
    {
        $this->entregado = "N";
        $this->pagado = "N";
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

    public function getTipoDocumento(): ?string
    {
        return HtmlDecode($this->tipoDocumento);
    }

    public function setTipoDocumento(?string $value): static
    {
        $this->tipoDocumento = RemoveXss($value);
        return $this;
    }

    public function getAsesor(): ?string
    {
        return HtmlDecode($this->asesor);
    }

    public function setAsesor(?string $value): static
    {
        $this->asesor = RemoveXss($value);
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

    public function getMontoTotal(): ?string
    {
        return $this->montoTotal;
    }

    public function setMontoTotal(?string $value): static
    {
        $this->montoTotal = $value;
        return $this;
    }

    public function getAlicuotaIva(): ?string
    {
        return $this->alicuotaIva;
    }

    public function setAlicuotaIva(?string $value): static
    {
        $this->alicuotaIva = $value;
        return $this;
    }

    public function getIva(): ?string
    {
        return $this->iva;
    }

    public function setIva(?string $value): static
    {
        $this->iva = $value;
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

    public function getIdDocumentoPadre(): ?int
    {
        return $this->idDocumentoPadre;
    }

    public function setIdDocumentoPadre(?int $value): static
    {
        $this->idDocumentoPadre = $value;
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

    public function getDocumento(): ?string
    {
        return HtmlDecode($this->documento);
    }

    public function setDocumento(?string $value): static
    {
        $this->documento = RemoveXss($value);
        return $this;
    }

    public function getTasaDia(): ?string
    {
        return $this->tasaDia;
    }

    public function setTasaDia(?string $value): static
    {
        $this->tasaDia = $value;
        return $this;
    }

    public function getMontoUsd(): ?string
    {
        return $this->montoUsd;
    }

    public function setMontoUsd(?string $value): static
    {
        $this->montoUsd = $value;
        return $this;
    }

    public function getDiasCredito(): ?int
    {
        return $this->diasCredito;
    }

    public function setDiasCredito(?int $value): static
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

    public function getFechaEntrega(): ?DateTime
    {
        return $this->fechaEntrega;
    }

    public function setFechaEntrega(?DateTime $value): static
    {
        $this->fechaEntrega = $value;
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
}
