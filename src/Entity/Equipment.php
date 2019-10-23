<?php

namespace App\Entity;

use Cassandra\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Equipment
 *
 * @ORM\Table(name="Equipment", indexes={@ORM\Index(name="EquipmentEquipment", columns={"Equipment"}), @ORM\Index(name="EquipmentID", columns={"EquipmentID"})})
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

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripequipment", mappedBy="equipmentnr")
     */
    private $tripequipments;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Actiontype", mappedBy="equipment")
     */
    private $actiontypes;

    public function __construct()
    {
        $this->tripequipments = new ArrayCollection();
        $this->actiontypes = new ArrayCollection();
    }


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

    /**
     * @return \Doctrine\Common\Collections\Collection|Tripequipment[]
     */
    public function getTripequipments(): \Doctrine\Common\Collections\Collection
    {
        return $this->tripequipments;
    }

    public function addTripequipment(Tripequipment $tripequipment): self
    {
        if (!$this->tripequipments->contains($tripequipment)) {
            $this->tripequipments[] = $tripequipment;
            $tripequipment->setEquipmentnr($this);
        }

        return $this;
    }

    public function removeTripequipment(Tripequipment $tripequipment): self
    {
        if ($this->tripequipments->contains($tripequipment)) {
            $this->tripequipments->removeElement($tripequipment);
            // set the owning side to null (unless already changed)
            if ($tripequipment->getEquipmentnr() === $this) {
                $tripequipment->setEquipmentnr(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Actiontype[]
     */
    public function getActiontypes(): \Doctrine\Common\Collections\Collection
    {
        return $this->actiontypes;
    }

    public function addActiontype(Actiontype $actiontype): self
    {
        if (!$this->actiontypes->contains($actiontype)) {
            $this->actiontypes[] = $actiontype;
            $actiontype->setEquipment($this);
        }

        return $this;
    }

    public function removeActiontype(Actiontype $actiontype): self
    {
        if ($this->actiontypes->contains($actiontype)) {
            $this->actiontypes->removeElement($actiontype);
            // set the owning side to null (unless already changed)
            if ($actiontype->getEquipment() === $this) {
                $actiontype->setEquipment(null);
            }
        }

        return $this;
    }


}
