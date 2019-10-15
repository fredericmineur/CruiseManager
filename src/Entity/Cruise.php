<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cruise
 *
 * @ORM\Table(name="Cruise", indexes={@ORM\Index(name="CruizeID", columns={"CruiseID"})})
 * @ORM\Entity
 */
class Cruise
{
    /**
     * @var int
     *
     * @ORM\Column(name="CruiseID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cruiseid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="PrincipalInvestigator", type="integer", nullable=true)
     */
    private $principalinvestigator = '0';

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
     * @ORM\Column(name="Destination", type="string", length=50, nullable=true)
     */
    private $destination;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=4000, nullable=true)
     */
    private $memo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Plancode", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $plancode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Ship", type="string", length=25, nullable=true, options={"fixed"=true})
     */
    private $ship;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Purpose", type="string", length=50, nullable=true, options={"fixed"=true})
     */
    private $purpose;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Campaign", mappedBy="cruise")
     */
    private $campaign;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="cruiseid")
     */
    private $trips;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->campaign = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trips = new ArrayCollection();
    }

    public function getCruiseid(): ?int
    {
        return $this->cruiseid;
    }

    public function getPrincipalinvestigator(): ?int
    {
        return $this->principalinvestigator;
    }

    public function setPrincipalinvestigator(?int $principalinvestigator): self
    {
        $this->principalinvestigator = $principalinvestigator;

        return $this;
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

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

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

    public function getPlancode(): ?string
    {
        return $this->plancode;
    }

    public function setPlancode(?string $plancode): self
    {
        $this->plancode = $plancode;

        return $this;
    }

    public function getShip(): ?string
    {
        return $this->ship;
    }

    public function setShip(?string $ship): self
    {
        $this->ship = $ship;

        return $this;
    }

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(?string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    /**
     * @return Collection|Campaign[]
     */
    public function getCampaign(): Collection
    {
        return $this->campaign;
    }

    public function addCampaign(Campaign $campaign): self
    {
        if (!$this->campaign->contains($campaign)) {
            $this->campaign[] = $campaign;
            $campaign->addCruise($this);
        }

        return $this;
    }

    public function removeCampaign(Campaign $campaign): self
    {
        if ($this->campaign->contains($campaign)) {
            $this->campaign->removeElement($campaign);
            $campaign->removeCruise($this);
        }

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->setCruiseid($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->contains($trip)) {
            $this->trips->removeElement($trip);
            // set the owning side to null (unless already changed)
            if ($trip->getCruiseid() === $this) {
                $trip->setCruiseid(null);
            }
        }

        return $this;
    }






}
