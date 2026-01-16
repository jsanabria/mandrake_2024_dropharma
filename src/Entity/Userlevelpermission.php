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
 * Entity class for "userlevelpermissions" table
 */
#[Entity]
#[Table(name: "userlevelpermissions")]
class Userlevelpermission extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer")]
    private int $userlevelid;

    #[Id]
    #[Column(type: "string")]
    private string $tablename;

    #[Column(type: "integer")]
    private int $permission;

    public function __construct(int $userlevelid, string $tablename)
    {
        $this->userlevelid = $userlevelid;
        $this->tablename = $tablename;
    }

    public function getUserlevelid(): int
    {
        return $this->userlevelid;
    }

    public function setUserlevelid(int $value): static
    {
        $this->userlevelid = $value;
        return $this;
    }

    public function getTablename(): string
    {
        return $this->tablename;
    }

    public function setTablename(string $value): static
    {
        $this->tablename = $value;
        return $this;
    }

    public function getPermission(): int
    {
        return $this->permission;
    }

    public function setPermission(int $value): static
    {
        $this->permission = $value;
        return $this;
    }
}
