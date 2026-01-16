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
 * Entity class for "limite_cliente" table
 */
#[Entity]
#[Table(name: "limite_cliente")]
class LimiteCliente extends AbstractEntity
{
    #[Column(type: "string")]
    private string $rif;

    #[Column(type: "float")]
    private float $limite;

    #[Column(type: "string")]
    private string $condicion;

    public function getRif(): string
    {
        return HtmlDecode($this->rif);
    }

    public function setRif(string $value): static
    {
        $this->rif = RemoveXss($value);
        return $this;
    }

    public function getLimite(): float
    {
        return $this->limite;
    }

    public function setLimite(float $value): static
    {
        $this->limite = $value;
        return $this;
    }

    public function getCondicion(): string
    {
        return HtmlDecode($this->condicion);
    }

    public function setCondicion(string $value): static
    {
        $this->condicion = RemoveXss($value);
        return $this;
    }
}
