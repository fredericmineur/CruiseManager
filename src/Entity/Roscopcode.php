<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Roscopcode
 *
 * @ORM\Table(name="RoscopCode", indexes={@ORM\Index(name="RoscopCode", columns={"RoscopCode"})})
 * @ORM\Entity
 */
class Roscopcode
{
    /**
     * @var string
     *
     * @ORM\Column(name="RoscopCode", type="string", length=50, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $roscopcode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="RoscopDescription", type="string", length=50, nullable=true)
     */
    private $roscopdescription;

    /**
     * @var string|null
     *
     * @ORM\Column(name="RoscopDivision", type="string", length=50, nullable=true)
     */
    private $roscopdivision;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Actiontype", mappedBy="roscopcode")
     */
    private $actiontypes;

    public function __construct()
    {
        $this->actiontypes = new ArrayCollection();
    }

    public function getRoscopcode(): ?string
    {
        return $this->roscopcode;
    }

    public function getRoscopdescription(): ?string
    {
        return $this->roscopdescription;
    }

    public function setRoscopdescription(?string $roscopdescription): self
    {
        $this->roscopdescription = $roscopdescription;

        return $this;
    }

    public function getRoscopdivision(): ?string
    {
        return $this->roscopdivision;
    }

    public function setRoscopdivision(?string $roscopdivision): self
    {
        $this->roscopdivision = $roscopdivision;

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
            $actiontype->setRoscopcode($this);
        }

        return $this;
    }

    public function removeActiontype(Actiontype $actiontype): self
    {
        if ($this->actiontypes->contains($actiontype)) {
            $this->actiontypes->removeElement($actiontype);
            // set the owning side to null (unless already changed)
            if ($actiontype->getRoscopcode() === $this) {
                $actiontype->setRoscopcode(null);
            }
        }

        return $this;
    }


}
