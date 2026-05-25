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
 * Entity class for "items_deco" table
 */
#[Entity]
#[Table(name: "items_deco")]
class ItemsDeco extends AbstractEntity
{
    #[Column(name: "ITEM", type: "string", nullable: true)]
    private ?string $item;

    #[Column(name: "ITEM_NAME", type: "string", nullable: true)]
    private ?string $itemName;

    #[Column(name: "CANTIDAD", type: "integer", nullable: true)]
    private ?int $cantidad;

    #[Column(name: "COSTO", type: "decimal", nullable: true)]
    private ?string $costo;

    #[Column(name: "BARCODE", type: "string", nullable: true)]
    private ?string $barcode;

    public function getItem(): ?string
    {
        return HtmlDecode($this->item);
    }

    public function setItem(?string $value): static
    {
        $this->item = RemoveXss($value);
        return $this;
    }

    public function getItemName(): ?string
    {
        return HtmlDecode($this->itemName);
    }

    public function setItemName(?string $value): static
    {
        $this->itemName = RemoveXss($value);
        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(?int $value): static
    {
        $this->cantidad = $value;
        return $this;
    }

    public function getCosto(): ?string
    {
        return $this->costo;
    }

    public function setCosto(?string $value): static
    {
        $this->costo = $value;
        return $this;
    }

    public function getBarcode(): ?string
    {
        return HtmlDecode($this->barcode);
    }

    public function setBarcode(?string $value): static
    {
        $this->barcode = RemoveXss($value);
        return $this;
    }
}
