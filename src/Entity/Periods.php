<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Periods
 *
 * @ORM\Table(name="Periods")
 * @ORM\Entity
 */
class Periods
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="Startdate", type="datetime", nullable=true)
     */
    private $startdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Enddate", type="datetime", nullable=true)
     */
    private $enddate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Short", type="string", length=40, nullable=true, options={"fixed"=true})
     */
    private $short;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Description", type="text", length=8, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ColorCode", type="string", length=15, nullable=true, options={"fixed"=true})
     */
    private $colorcode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(?\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(?string $short): self
    {
        $this->short = $short;

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

    public function getColorcode(): ?string
    {
        return $this->colorcode;
    }

    public function setColorcode(?string $colorcode): self
    {
        $this->colorcode = $colorcode;

        return $this;
    }


}
