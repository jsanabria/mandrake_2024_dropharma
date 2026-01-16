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
 * Entity class for "audittrail" table
 */
#[Entity]
#[Table(name: "audittrail")]
class Audittrail extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(type: "datetime")]
    private DateTime $datetime;

    #[Column(type: "string", nullable: true)]
    private ?string $script;

    #[Column(type: "string", nullable: true)]
    private ?string $user;

    #[Column(type: "string", nullable: true)]
    private ?string $action;

    #[Column(name: "`table`", options: ["name" => "table"], type: "string", nullable: true)]
    private ?string $table;

    #[Column(type: "string", nullable: true)]
    private ?string $field;

    #[Column(type: "text", nullable: true)]
    private ?string $keyvalue;

    #[Column(type: "text", nullable: true)]
    private ?string $oldvalue;

    #[Column(type: "text", nullable: true)]
    private ?string $newvalue;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(DateTime $value): static
    {
        $this->datetime = $value;
        return $this;
    }

    public function getScript(): ?string
    {
        return HtmlDecode($this->script);
    }

    public function setScript(?string $value): static
    {
        $this->script = RemoveXss($value);
        return $this;
    }

    public function getUser(): ?string
    {
        return HtmlDecode($this->user);
    }

    public function setUser(?string $value): static
    {
        $this->user = RemoveXss($value);
        return $this;
    }

    public function getAction(): ?string
    {
        return HtmlDecode($this->action);
    }

    public function setAction(?string $value): static
    {
        $this->action = RemoveXss($value);
        return $this;
    }

    public function getTable(): ?string
    {
        return HtmlDecode($this->table);
    }

    public function setTable(?string $value): static
    {
        $this->table = RemoveXss($value);
        return $this;
    }

    public function getField(): ?string
    {
        return HtmlDecode($this->field);
    }

    public function setField(?string $value): static
    {
        $this->field = RemoveXss($value);
        return $this;
    }

    public function getKeyvalue(): ?string
    {
        return HtmlDecode($this->keyvalue);
    }

    public function setKeyvalue(?string $value): static
    {
        $this->keyvalue = RemoveXss($value);
        return $this;
    }

    public function getOldvalue(): ?string
    {
        return HtmlDecode($this->oldvalue);
    }

    public function setOldvalue(?string $value): static
    {
        $this->oldvalue = RemoveXss($value);
        return $this;
    }

    public function getNewvalue(): ?string
    {
        return HtmlDecode($this->newvalue);
    }

    public function setNewvalue(?string $value): static
    {
        $this->newvalue = RemoveXss($value);
        return $this;
    }
}
