<?php

namespace App\Entity;

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


}
