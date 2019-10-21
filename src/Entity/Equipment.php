<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipment
 *
 * @ORM\Table(name="Equipment")
 * @ORM\Entity
 */
class Equipment
{
    /**
     * @var int
     *
     * @ORM\Column(name="EquipmentID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $equipmentid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Equipment", type="string", length=50, nullable=true)
     */
    private $equipment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=2000, nullable=true)
     */
    private $memo;

    /**
     * @var bool
     *
     * @ORM\Column(name="ShowOnShip", type="boolean", nullable=false, options={"default"="1"})
     */
    private $showonship = '1';

    public function getEquipmentid(): ?int
    {
        return $this->equipmentid;
    }

    public function getEquipment(): ?string
    {
        return $this->equipment;
    }

    public function setEquipment(?string $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): self
    {
        $this->memo = $memo;

        return $this;
    }

    public function getShowonship(): ?bool
    {
        return $this->showonship;
    }

    public function setShowonship(bool $showonship): self
    {
        $this->showonship = $showonship;

        return $this;
    }


}
