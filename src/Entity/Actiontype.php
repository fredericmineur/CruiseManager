<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actiontype
 *
 * @ORM\Table(name="ActionType", indexes={@ORM\Index(name="IDX_13207C7D51C95720", columns={"Equipment"}), @ORM\Index(name="IDX_13207C7DB323AD87", columns={"RoscopCode"})})
 * @ORM\Entity
 */
class Actiontype
{
    /**
     * @var int
     *
     * @ORM\Column(name="ActionTypeID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $actiontypeid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="Research", type="integer", nullable=true)
     */
    private $research = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="string", length=50, nullable=true)
     */
    private $description;

    /**
     * @var \Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Equipment", referencedColumnName="EquipmentID")
     * })
     */
    private $equipment;

    /**
     * @var \Roscopcode
     *
     * @ORM\ManyToOne(targetEntity="Roscopcode")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RoscopCode", referencedColumnName="RoscopCode")
     * })
     */
    private $roscopcode;

    public function getActiontypeid(): ?int
    {
        return $this->actiontypeid;
    }

    public function getResearch(): ?int
    {
        return $this->research;
    }

    public function setResearch(?int $research): self
    {
        $this->research = $research;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    public function getRoscopcode(): ?Roscopcode
    {
        return $this->roscopcode;
    }

    public function setRoscopcode(?Roscopcode $roscopcode): self
    {
        $this->roscopcode = $roscopcode;

        return $this;
    }


}
