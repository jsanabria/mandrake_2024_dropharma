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
 * Entity class for "notificaciones" table
 */
#[Entity]
#[Table(name: "notificaciones")]
class Notificacione extends AbstractEntity
{
    #[Id]
    #[Column(name: "Nnotificaciones", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $nnotificaciones;

    #[Column(type: "string", nullable: true)]
    private ?string $tipo;

    #[Column(type: "string", nullable: true)]
    private ?string $notificar;

    #[Column(type: "string", nullable: true)]
    private ?string $asunto;

    #[Column(type: "text", nullable: true)]
    private ?string $notificacion;

    #[Column(type: "text", nullable: true)]
    private ?string $notificados;

    #[Column(name: "notificados_efectivos", type: "text", nullable: true)]
    private ?string $notificadosEfectivos;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "datetime", nullable: true)]
    private ?DateTime $fecha;

    #[Column(type: "string", nullable: true)]
    private ?string $enviado;

    #[Column(type: "string", nullable: true)]
    private ?string $adjunto;

    public function __construct()
    {
        $this->enviado = "0";
    }

    public function getNnotificaciones(): int
    {
        return $this->nnotificaciones;
    }

    public function setNnotificaciones(int $value): static
    {
        $this->nnotificaciones = $value;
        return $this;
    }

    public function getTipo(): ?string
    {
        return HtmlDecode($this->tipo);
    }

    public function setTipo(?string $value): static
    {
        $this->tipo = RemoveXss($value);
        return $this;
    }

    public function getNotificar(): ?string
    {
        return HtmlDecode($this->notificar);
    }

    public function setNotificar(?string $value): static
    {
        $this->notificar = RemoveXss($value);
        return $this;
    }

    public function getAsunto(): ?string
    {
        return HtmlDecode($this->asunto);
    }

    public function setAsunto(?string $value): static
    {
        $this->asunto = RemoveXss($value);
        return $this;
    }

    public function getNotificacion(): ?string
    {
        return HtmlDecode($this->notificacion);
    }

    public function setNotificacion(?string $value): static
    {
        $this->notificacion = RemoveXss($value);
        return $this;
    }

    public function getNotificados(): ?string
    {
        return HtmlDecode($this->notificados);
    }

    public function setNotificados(?string $value): static
    {
        $this->notificados = RemoveXss($value);
        return $this;
    }

    public function getNotificadosEfectivos(): ?string
    {
        return HtmlDecode($this->notificadosEfectivos);
    }

    public function setNotificadosEfectivos(?string $value): static
    {
        $this->notificadosEfectivos = RemoveXss($value);
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

    public function getEnviado(): ?string
    {
        return HtmlDecode($this->enviado);
    }

    public function setEnviado(?string $value): static
    {
        $this->enviado = RemoveXss($value);
        return $this;
    }

    public function getAdjunto(): ?string
    {
        return HtmlDecode($this->adjunto);
    }

    public function setAdjunto(?string $value): static
    {
        $this->adjunto = RemoveXss($value);
        return $this;
    }
}
