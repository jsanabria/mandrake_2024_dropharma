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
 * Entity class for "view_tarifa" table
 */
#[Entity]
#[Table(name: "view_tarifa")]
class ViewTarifa extends AbstractEntity
{
    #[Column(type: "integer", nullable: true)]
    private ?int $articulo;

    #[Column(type: "decimal", nullable: true)]
    private ?string $precio;

    #[Column(type: "string", nullable: true)]
    private ?string $tarifa;

    public function getArticulo(): ?int
    {
        return $this->articulo;
    }

    public function setArticulo(?int $value): static
    {
        $this->articulo = $value;
        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(?string $value): static
    {
        $this->precio = $value;
        return $this;
    }

    public function getTarifa(): ?string
    {
        return HtmlDecode($this->tarifa);
    }

    public function setTarifa(?string $value): static
    {
        $this->tarifa = RemoveXss($value);
        return $this;
    }
}
