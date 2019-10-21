<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trip
 *
 * @ORM\Table(name="Trip", indexes={@ORM\Index(name="IDX_D6645A0511962557", columns={"CruiseID"})})
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
     *   @ORM\JoinColumn(name="CruiseID", referencedColumnName="CruiseID")
     * })
     */
    private $cruiseid;

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
        $this->cruiseid = $cruiseid;

        return $this;
    }


}
