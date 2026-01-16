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
 * Entity class for "userlevels" table
 */
#[Entity]
#[Table(name: "userlevels")]
class Userlevel extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    private int $userlevelid;

    #[Column(type: "string")]
    private string $userlevelname;

    #[Column(name: "tipo_acceso", type: "string", nullable: true)]
    private ?string $tipoAcceso;

    public function getUserlevelid(): int
    {
        return $this->userlevelid;
    }

    public function setUserlevelid(int $value): static
    {
        $this->userlevelid = $value;
        return $this;
    }

    public function getUserlevelname(): string
    {
        return HtmlDecode($this->userlevelname);
    }

    public function setUserlevelname(string $value): static
    {
        $this->userlevelname = RemoveXss($value);
        return $this;
    }

    public function getTipoAcceso(): ?string
    {
        return $this->tipoAcceso;
    }

    public function setTipoAcceso(?string $value): static
    {
        if (!in_array($value, ["USUARIO", "CLIENTE", "PROVEEDOR"])) {
            throw new \InvalidArgumentException("Invalid 'tipo_acceso' value");
        }
        $this->tipoAcceso = $value;
        return $this;
    }
}
