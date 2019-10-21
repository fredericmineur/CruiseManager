<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stations
 *
 * @ORM\Table(name="Stations")
 * @ORM\Entity
 */
class Stations
{
    /**
     * @var int
     *
     * @ORM\Column(name="NR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nr;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Latitude", type="float", precision=53, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Longitude", type="float", precision=53, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Name", type="string", length=35, nullable=true, options={"fixed"=true})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Type", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Code", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $code;

    public function getNr(): ?int
    {
        return $this->nr;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }


}
