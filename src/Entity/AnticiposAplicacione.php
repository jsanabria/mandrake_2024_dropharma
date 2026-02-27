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
 * Entity class for "anticipos_aplicaciones" table
 */
#[Entity]
#[Table(name: "anticipos_aplicaciones")]
class AnticiposAplicacione extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "anticipo_cobro_id", type: "integer")]
    private int $anticipoCobroId;

    #[Column(name: "cobro_factura_id", type: "integer")]
    private int $cobroFacturaId;

    #[Column(name: "salida_id", type: "integer")]
    private int $salidaId;

    #[Column(type: "datetime")]
    private DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "monto_moneda", type: "decimal")]
    private string $montoMoneda;

    #[Column(type: "string")]
    private string $moneda;

    #[Column(name: "tasa_factura", type: "decimal", nullable: true)]
    private ?string $tasaFactura;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getAnticipoCobroId(): int
    {
        return $this->anticipoCobroId;
    }

    public function setAnticipoCobroId(int $value): static
    {
        $this->anticipoCobroId = $value;
        return $this;
    }

    public function getCobroFacturaId(): int
    {
        return $this->cobroFacturaId;
    }

    public function setCobroFacturaId(int $value): static
    {
        $this->cobroFacturaId = $value;
        return $this;
    }

    public function getSalidaId(): int
    {
        return $this->salidaId;
    }

    public function setSalidaId(int $value): static
    {
        $this->salidaId = $value;
        return $this;
    }

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function setFecha(DateTime $value): static
    {
        $this->fecha = $value;
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

    public function getMontoMoneda(): string
    {
        return $this->montoMoneda;
    }

    public function setMontoMoneda(string $value): static
    {
        $this->montoMoneda = $value;
        return $this;
    }

    public function getMoneda(): string
    {
        return HtmlDecode($this->moneda);
    }

    public function setMoneda(string $value): static
    {
        $this->moneda = RemoveXss($value);
        return $this;
    }

    public function getTasaFactura(): ?string
    {
        return $this->tasaFactura;
    }

    public function setTasaFactura(?string $value): static
    {
        $this->tasaFactura = $value;
        return $this;
    }
}
