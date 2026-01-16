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
 * Entity class for "usuario" table
 */
#[Entity]
#[Table(name: "usuario")]
class Usuario extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "string", nullable: true)]
    private ?string $username;

    #[Column(type: "string", nullable: true)]
    private ?string $password;

    #[Column(type: "string", nullable: true)]
    private ?string $nombre;

    #[Column(type: "string", nullable: true)]
    private ?string $telefono;

    #[Column(type: "string", nullable: true)]
    private ?string $email;

    #[Column(type: "integer", nullable: true)]
    private ?int $userlevelid;

    #[Column(type: "integer", nullable: true)]
    private ?int $asesor;

    #[Column(type: "integer", nullable: true)]
    private ?int $cliente;

    #[Column(type: "integer", nullable: true)]
    private ?int $proveedor;

    #[Column(type: "string", nullable: true)]
    private ?string $foto;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    #[Column(type: "integer", nullable: true)]
    private ?int $userlevelid2;

    public function __construct()
    {
        $this->activo = "S";
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $value): static
    {
        $this->username = $value;
        return $this;
    }

    public function getPassword(): ?string
    {
        return HtmlDecode($this->password);
    }

    public function setPassword(?string $value): static
    {
        $this->password = RemoveXss($value);
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

    public function getTelefono(): ?string
    {
        return HtmlDecode($this->telefono);
    }

    public function setTelefono(?string $value): static
    {
        $this->telefono = RemoveXss($value);
        return $this;
    }

    public function getEmail(): ?string
    {
        return HtmlDecode($this->email);
    }

    public function setEmail(?string $value): static
    {
        $this->email = RemoveXss($value);
        return $this;
    }

    public function getUserlevelid(): ?int
    {
        return $this->userlevelid;
    }

    public function setUserlevelid(?int $value): static
    {
        $this->userlevelid = $value;
        return $this;
    }

    public function getAsesor(): ?int
    {
        return $this->asesor;
    }

    public function setAsesor(?int $value): static
    {
        $this->asesor = $value;
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

    public function getProveedor(): ?int
    {
        return $this->proveedor;
    }

    public function setProveedor(?int $value): static
    {
        $this->proveedor = $value;
        return $this;
    }

    public function getFoto(): ?string
    {
        return HtmlDecode($this->foto);
    }

    public function setFoto(?string $value): static
    {
        $this->foto = RemoveXss($value);
        return $this;
    }

    public function getActivo(): ?string
    {
        return $this->activo;
    }

    public function setActivo(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'activo' value");
        }
        $this->activo = $value;
        return $this;
    }

    public function getUserlevelid2(): ?int
    {
        return $this->userlevelid2;
    }

    public function setUserlevelid2(?int $value): static
    {
        $this->userlevelid2 = $value;
        return $this;
    }

    // Get login arguments
    public function getLoginArguments(): array
    {
        return [
            "userName" => $this->get('username'),
            "userId" => null,
            "parentUserId" => null,
            "userLevel" => $this->get('userlevelid') ?? AdvancedSecurity::ANONYMOUS_USER_LEVEL_ID,
            "userPrimaryKey" => $this->get('id'),
        ];
    }
}
