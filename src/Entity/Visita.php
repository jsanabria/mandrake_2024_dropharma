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
 * Entity class for "visitas" table
 */
#[Entity]
#[Table(name: "visitas")]
class Visita extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $apellido;

    #[Column(type: "string", nullable: true)]
    private ?string $correo;

    #[Column(type: "string", nullable: true)]
    private ?string $telefono;

    #[Column(type: "string", nullable: true)]
    private ?string $producto;

    #[Column(type: "string", nullable: true)]
    private ?string $referencia;

    #[Column(type: "string", nullable: true)]
    private ?string $comentario;

    #[Column(type: "text", nullable: true)]
    private ?string $seguimiento;

    #[Column(type: "string", nullable: true)]
    private ?string $fecha;

    #[Column(name: "fecha_registro", type: "datetime")]
    private DateTime $fechaRegistro;

    #[Column(type: "string", nullable: true)]
    private ?string $usuario;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getNombre(): ?string
    {
        return HtmlDecode($this->nombre);
    }

    public function setNombre(?string $value): static
    {
        $this->nombre = RemoveXss($value);
        return $this;
    }

    public function getApellido(): ?string
    {
        return HtmlDecode($this->apellido);
    }

    public function setApellido(?string $value): static
    {
        $this->apellido = RemoveXss($value);
        return $this;
    }

    public function getCorreo(): ?string
    {
        return HtmlDecode($this->correo);
    }

    public function setCorreo(?string $value): static
    {
        $this->correo = RemoveXss($value);
        return $this;
    }

    public function getTelefono(): ?string
    {
        return HtmlDecode($this->telefono);
    }

    public function setTelefono(?string $value): static
    {
        $this->telefono = RemoveXss($value);
        return $this;
    }

    public function getProducto(): ?string
    {
        return HtmlDecode($this->producto);
    }

    public function setProducto(?string $value): static
    {
        $this->producto = RemoveXss($value);
        return $this;
    }

    public function getReferencia(): ?string
    {
        return HtmlDecode($this->referencia);
    }

    public function setReferencia(?string $value): static
    {
        $this->referencia = RemoveXss($value);
        return $this;
    }

    public function getComentario(): ?string
    {
        return HtmlDecode($this->comentario);
    }

    public function setComentario(?string $value): static
    {
        $this->comentario = RemoveXss($value);
        return $this;
    }

    public function getSeguimiento(): ?string
    {
        return HtmlDecode($this->seguimiento);
    }

    public function setSeguimiento(?string $value): static
    {
        $this->seguimiento = RemoveXss($value);
        return $this;
    }

    public function getFecha(): ?string
    {
        return HtmlDecode($this->fecha);
    }

    public function setFecha(?string $value): static
    {
        $this->fecha = RemoveXss($value);
        return $this;
    }

    public function getFechaRegistro(): DateTime
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(DateTime $value): static
    {
        $this->fechaRegistro = $value;
        return $this;
    }

    public function getUsuario(): ?string
    {
        return HtmlDecode($this->usuario);
    }

    public function setUsuario(?string $value): static
    {
        $this->usuario = RemoveXss($value);
        return $this;
    }
}
