<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

//* @ORM\Table(name="Stations", uniqueConstraints={@ORM\UniqueConstraint(name="ST_codes", columns={"Code"})})
/**
 * Stations
 *
 * @ORM\Table(name="Stations")
 * @ORM\Entity(repositoryClass="App\Repository\StationRepository")
 * @UniqueEntity("code")
 */
class Stations
{
    /**
     * @var int
     *
     * @ORM\Column(name="NR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("read:all_stations")
     */
    private $nr;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Latitude", type="float", precision=53, scale=0, nullable=true)
     * @Groups("read:all_stations")
     */
    private $latitude;


    /**
     * @var float|null
     *
     * @ORM\Column(name="Longitude", type="float", precision=53, scale=0, nullable=true)
     * @Groups("read:all_stations")
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
     * @ORM\Column(name="Code", type="string", length=10, unique=true, nullable=true, options={"fixed"=true})
     * @Groups("read:all_stations")
     * @Assert\Length(min = 3, max=10, minMessage="Code must be at least {{ limit }} characters long",
     *     maxMessage="code cannot be longer than {{ limit }} characters")
     */
    private $code;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Tripstations", mappedBy="stationnr")
     * @Groups("read:all_stations")
     */
    private $tripstations;

    public function __construct()
    {
        $this->tripstations = new ArrayCollection();
    }

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

    /**
     * @return \Doctrine\Common\Collections\Collection|Tripstations[]
     */
    public function getTripstations(): \Doctrine\Common\Collections\Collection
    {
        return $this->tripstations;
    }

    public function addTripstation(Tripstations $tripstation): self
    {
        if (!$this->tripstations->contains($tripstation)) {
            $this->tripstations[] = $tripstation;
            $tripstation->setStationnr($this);
        }

        return $this;
    }

    public function removeTripstation(Tripstations $tripstation): self
    {
        if ($this->tripstations->contains($tripstation)) {
            $this->tripstations->removeElement($tripstation);
            // set the owning side to null (unless already changed)
            if ($tripstation->getStationnr() === $this) {
                $tripstation->setStationnr(null);
            }
        }

        return $this;
    }




}
