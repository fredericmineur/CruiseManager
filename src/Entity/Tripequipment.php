<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tripequipment
 *
 * @ORM\Table(name="TripEquipment", indexes={@ORM\Index(name="IDX_64266AA5CACDF190", columns={"EquipmentNR"}), @ORM\Index(name="IDX_64266AA55C8FDA67", columns={"TripNR"})})
 * @ORM\Entity
 */
class Tripequipment
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="Amount", type="integer", nullable=false, options={"default"="1"})
     */
    private $amount = '1';

    /**
     * @var int|null
     *
     * @ORM\Column(name="CruiseNR", type="integer", nullable=true)
     */
    private $cruisenr;

    /**
     * @var \Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="EquipmentNR", referencedColumnName="EquipmentID")
     * })
     */
    private $equipmentnr;

    /**
     * @var \Trip
     *
     * @ORM\ManyToOne(targetEntity="Trip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TripNR", referencedColumnName="TripID")
     * })
     */
    private $tripnr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCruisenr(): ?int
    {
        return $this->cruisenr;
    }

    public function setCruisenr(?int $cruisenr): self
    {
        $this->cruisenr = $cruisenr;

        return $this;
    }

    public function getEquipmentnr(): ?Equipment
    {
        return $this->equipmentnr;
    }

    public function setEquipmentnr(?Equipment $equipmentnr): self
    {
        $this->equipmentnr = $equipmentnr;

        return $this;
    }

    public function getTripnr(): ?Trip
    {
        return $this->tripnr;
    }

    public function setTripnr(?Trip $tripnr): self
    {
        $this->tripnr = $tripnr;

        return $this;
    }


}
