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
 * Entity class for "cont_mes_contable" table
 */
#[Entity]
#[Table(name: "cont_mes_contable")]
class ContMesContable extends AbstractEntity
{
    #[Id]
    #[Column(type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "tipo_comprobante", type: "string", unique: true, nullable: true)]
    private ?string $tipoComprobante;

    #[Column(type: "string", nullable: true)]
    private ?string $descripcion;

    #[Column(name: "M01", type: "string", nullable: true)]
    private ?string $m01;

    #[Column(name: "M02", type: "string", nullable: true)]
    private ?string $m02;

    #[Column(name: "M03", type: "string", nullable: true)]
    private ?string $m03;

    #[Column(name: "M04", type: "string", nullable: true)]
    private ?string $m04;

    #[Column(name: "M05", type: "string", nullable: true)]
    private ?string $m05;

    #[Column(name: "M06", type: "string", nullable: true)]
    private ?string $m06;

    #[Column(name: "M07", type: "string", nullable: true)]
    private ?string $m07;

    #[Column(name: "M08", type: "string", nullable: true)]
    private ?string $m08;

    #[Column(name: "M09", type: "string", nullable: true)]
    private ?string $m09;

    #[Column(name: "M10", type: "string", nullable: true)]
    private ?string $m10;

    #[Column(name: "M11", type: "string", nullable: true)]
    private ?string $m11;

    #[Column(name: "M12", type: "string", nullable: true)]
    private ?string $m12;

    #[Column(type: "string", nullable: true)]
    private ?string $activo;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getTipoComprobante(): ?string
    {
        return HtmlDecode($this->tipoComprobante);
    }

    public function setTipoComprobante(?string $value): static
    {
        $this->tipoComprobante = RemoveXss($value);
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return HtmlDecode($this->descripcion);
    }

    public function setDescripcion(?string $value): static
    {
        $this->descripcion = RemoveXss($value);
        return $this;
    }

    public function getM01(): ?string
    {
        return $this->m01;
    }

    public function setM01(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M01' value");
        }
        $this->m01 = $value;
        return $this;
    }

    public function getM02(): ?string
    {
        return $this->m02;
    }

    public function setM02(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M02' value");
        }
        $this->m02 = $value;
        return $this;
    }

    public function getM03(): ?string
    {
        return $this->m03;
    }

    public function setM03(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M03' value");
        }
        $this->m03 = $value;
        return $this;
    }

    public function getM04(): ?string
    {
        return $this->m04;
    }

    public function setM04(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M04' value");
        }
        $this->m04 = $value;
        return $this;
    }

    public function getM05(): ?string
    {
        return $this->m05;
    }

    public function setM05(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M05' value");
        }
        $this->m05 = $value;
        return $this;
    }

    public function getM06(): ?string
    {
        return $this->m06;
    }

    public function setM06(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M06' value");
        }
        $this->m06 = $value;
        return $this;
    }

    public function getM07(): ?string
    {
        return $this->m07;
    }

    public function setM07(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M07' value");
        }
        $this->m07 = $value;
        return $this;
    }

    public function getM08(): ?string
    {
        return $this->m08;
    }

    public function setM08(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M08' value");
        }
        $this->m08 = $value;
        return $this;
    }

    public function getM09(): ?string
    {
        return $this->m09;
    }

    public function setM09(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M09' value");
        }
        $this->m09 = $value;
        return $this;
    }

    public function getM10(): ?string
    {
        return $this->m10;
    }

    public function setM10(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M10' value");
        }
        $this->m10 = $value;
        return $this;
    }

    public function getM11(): ?string
    {
        return $this->m11;
    }

    public function setM11(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M11' value");
        }
        $this->m11 = $value;
        return $this;
    }

    public function getM12(): ?string
    {
        return $this->m12;
    }

    public function setM12(?string $value): static
    {
        if (!in_array($value, ["S", "N"])) {
            throw new \InvalidArgumentException("Invalid 'M12' value");
        }
        $this->m12 = $value;
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
}
