<?php

namespace App\Entity;

use Cassandra\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trip
 *
 * @ORM\Table(name="Trip", indexes={@ORM\Index(name="CruiseID", columns={"CruiseID"}), @ORM\Index(name="EndDate", columns={"Enddate"}), @ORM\Index(name="GpsStart", columns={"GPSStart"}), @ORM\Index(name="GpsStop", columns={"GPSStop"}), @ORM\Index(name="StartDate", columns={"Startdate"}), @ORM\Index(name="TripID", columns={"TripID"})})
 * @ORM\Entity
 */
class Trip
{
    /**
     * @var int
     *
     * @ORM\Column(name="TripID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $tripid;

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
     * @ORM\Column(name="Startpoint", type="string", length=50, nullable=true, options={"default"="Oostende"})
     */
    private $startpoint = 'Oostende';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Endpoint", type="string", length=50, nullable=true, options={"default"="Oostende"})
     */
    private $endpoint = 'Oostende';

    /**
     * @var string|null
     *
     * @ORM\Column(name="DestinationArea", type="string", length=50, nullable=true)
     */
    private $destinationarea;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=4000, nullable=true)
     */
    private $memo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=10, nullable=true, options={"default"="Planned","fixed"=true})
     */
    private $status = 'Planned';

    /**
     * @var string|null
     *
     * @ORM\Column(name="LogText", type="string", length=4000, nullable=true)
     */
    private $logtext;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Ship", type="string", length=25, nullable=true, options={"default"="Zeeleeuw","fixed"=true})
     */
    private $ship = 'Zeeleeuw';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Insync", type="boolean", nullable=true)
     */
    private $insync = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="GPSStart", type="datetime", nullable=true)
     */
    private $gpsstart;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="GPSStop", type="datetime", nullable=true)
     */
    private $gpsstop;

    /**
     * @var float|null
     *
     * @ORM\Column(name="TravelDistance", type="float", precision=53, scale=0, nullable=true, options={"default"="-1"})
     */
    private $traveldistance = '-1';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="LeavePortDate", type="datetime", nullable=true)
     */
    private $leaveportdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ArrivePortDate", type="datetime", nullable=true)
     */
    private $arriveportdate;




    /**
     * @var \Cruise
     *
     * @ORM\ManyToOne(targetEntity="Cruise", inversedBy="trips")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="CruiseID", referencedColumnName="CruiseID", nullable=false)
     * })
     */
    private $cruiseid;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripinvestigators", mappedBy="tripnr", cascade={"all"}, orphanRemoval=true)
     */
    private $tripinvestigators;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripnotes", mappedBy="tripnr")
     */
    private $tripnotes;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripequipment", mappedBy="tripnr")
     */
    private $tripequipments;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripstations", mappedBy="tripnr")
     */
    private $tripstations;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripactions", mappedBy="tripnr")
     */
    private $tripactions;

    public function __construct()
    {
        $this->tripinvestigators = new ArrayCollection();
        $this->tripnotes = new ArrayCollection();
        $this->tripequipments = new ArrayCollection();
        $this->tripstations = new ArrayCollection();
        $this->tripactions = new ArrayCollection();
    }

    public function getTripid(): ?int
    {
        return $this->tripid;
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

    public function getStartpoint(): ?string
    {
        return $this->startpoint;
    }

    public function setStartpoint(?string $startpoint): self
    {
        $this->startpoint = $startpoint;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(?string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getDestinationarea(): ?string
    {
        return $this->destinationarea;
    }

    public function setDestinationarea(?string $destinationarea): self
    {
        $this->destinationarea = $destinationarea;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLogtext(): ?string
    {
        return $this->logtext;
    }

    public function setLogtext(?string $logtext): self
    {
        $this->logtext = $logtext;

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

    public function getInsync(): ?bool
    {
        return $this->insync;
    }

    public function setInsync(?bool $insync): self
    {
        $this->insync = $insync;

        return $this;
    }

    public function getGpsstart(): ?\DateTimeInterface
    {
        return $this->gpsstart;
    }

    public function setGpsstart(?\DateTimeInterface $gpsstart): self
    {
        $this->gpsstart = $gpsstart;

        return $this;
    }

    public function getGpsstop(): ?\DateTimeInterface
    {
        return $this->gpsstop;
    }

    public function setGpsstop(?\DateTimeInterface $gpsstop): self
    {
        $this->gpsstop = $gpsstop;

        return $this;
    }

    public function getTraveldistance(): ?float
    {
        return $this->traveldistance;
    }

    public function setTraveldistance(?float $traveldistance): self
    {
        $this->traveldistance = $traveldistance;

        return $this;
    }

    public function getLeaveportdate(): ?\DateTimeInterface
    {
        return $this->leaveportdate;
    }

    public function setLeaveportdate(?\DateTimeInterface $leaveportdate): self
    {
        $this->leaveportdate = $leaveportdate;

        return $this;
    }

    public function getArriveportdate(): ?\DateTimeInterface
    {
        return $this->arriveportdate;
    }

    public function setArriveportdate(?\DateTimeInterface $arriveportdate): self
    {
        $this->arriveportdate = $arriveportdate;

        return $this;
    }

    public function getCruiseid(): ?Cruise
    {

        return $this->cruiseid;
    }

    public function setCruiseid(?Cruise $cruiseid): self
    {
//        DUMP("setCruiseid");
        $this->cruiseid = $cruiseid;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Tripinvestigators[]
     */
    public function getTripinvestigators(): \Doctrine\Common\Collections\Collection
    {
        return $this->tripinvestigators;
    }

    public function addTripinvestigator(Tripinvestigators $tripinvestigator): self
    {
        if (!$this->tripinvestigators->contains($tripinvestigator)) {
            $this->tripinvestigators[] = $tripinvestigator;
            $tripinvestigator->setTripnr($this);
        }

        return $this;
    }

    public function removeTripinvestigator(Tripinvestigators $tripinvestigator): self
    {
        if ($this->tripinvestigators->contains($tripinvestigator)) {
            $this->tripinvestigators->removeElement($tripinvestigator);
            // set the owning side to null (unless already changed)
            if ($tripinvestigator->getTripnr() === $this) {
                $tripinvestigator->setTripnr(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Tripnotes[]
     */
    public function getTripnotes(): \Doctrine\Common\Collections\Collection
    {
        return $this->tripnotes;
    }

    public function addTripnote(Tripnotes $tripnote): self
    {
        if (!$this->tripnotes->contains($tripnote)) {
            $this->tripnotes[] = $tripnote;
            $tripnote->setTripnr($this);
        }

        return $this;
    }

    public function removeTripnote(Tripnotes $tripnote): self
    {
        if ($this->tripnotes->contains($tripnote)) {
            $this->tripnotes->removeElement($tripnote);
            // set the owning side to null (unless already changed)
            if ($tripnote->getTripnr() === $this) {
                $tripnote->setTripnr(null);
            }
        }

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
            $tripequipment->setTripnr($this);
        }

        return $this;
    }

    public function removeTripequipment(Tripequipment $tripequipment): self
    {
        if ($this->tripequipments->contains($tripequipment)) {
            $this->tripequipments->removeElement($tripequipment);
            // set the owning side to null (unless already changed)
            if ($tripequipment->getTripnr() === $this) {
                $tripequipment->setTripnr(null);
            }
        }

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
            $tripstation->setTripnr($this);
        }

        return $this;
    }

    public function removeTripstation(Tripstations $tripstation): self
    {
        if ($this->tripstations->contains($tripstation)) {
            $this->tripstations->removeElement($tripstation);
            // set the owning side to null (unless already changed)
            if ($tripstation->getTripnr() === $this) {
                $tripstation->setTripnr(null);
            }
        }

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Tripactions[]
     */
    public function getTripactions(): \Doctrine\Common\Collections\Collection
    {
        return $this->tripactions;
    }

    public function addTripaction(Tripactions $tripaction): self
    {
        if (!$this->tripactions->contains($tripaction)) {
            $this->tripactions[] = $tripaction;
            $tripaction->setTripnr($this);
        }

        return $this;
    }

    public function removeTripaction(Tripactions $tripaction): self
    {
        if ($this->tripactions->contains($tripaction)) {
            $this->tripactions->removeElement($tripaction);
            // set the owning side to null (unless already changed)
            if ($tripaction->getTripnr() === $this) {
                $tripaction->setTripnr(null);
            }
        }

        return $this;
    }


}
