<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tripactions
 *
 * @ORM\Table(name="TripActions", indexes={@ORM\Index(name="NonClusteredIndex-20180716-133118", columns={"StartDate"}), @ORM\Index(name="NonClusteredIndex-20180718-StartDate_EndDate", columns={"ActionType", "StartDate", "EndDate"}), @ORM\Index(name="IDX_5A55FB8D5C8FDA67", columns={"TripNR"}), @ORM\Index(name="IDX_5A55FB8D53E08564", columns={"TripStationNR"})})
 * @ORM\Entity
 */
class Tripactions
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
     * @var string|null
     *
     * @ORM\Column(name="Writer", type="string", length=50, nullable=true)
     */
    private $writer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ActionType", type="string", length=50, nullable=true)
     */
    private $actiontype;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ServerDate", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $serverdate = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=4000, nullable=true)
     */
    private $memo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Station", type="string", length=50, nullable=true)
     */
    private $station;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ActionOrder", type="integer", nullable=true)
     */
    private $actionorder;

    /**
     * @var float|null
     *
     * @ORM\Column(name="StartLat", type="float", precision=53, scale=0, nullable=true)
     */
    private $startlat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="StartLong", type="float", precision=53, scale=0, nullable=true)
     */
    private $startlong;

    /**
     * @var float|null
     *
     * @ORM\Column(name="StopLat", type="float", precision=53, scale=0, nullable=true)
     */
    private $stoplat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="StopLong", type="float", precision=53, scale=0, nullable=true)
     */
    private $stoplong;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Status", type="string", length=10, nullable=true, options={"default"="Planned","fixed"=true})
     */
    private $status = 'Planned';

    /**
     * @var int|null
     *
     * @ORM\Column(name="ActionTypeNR", type="integer", nullable=true)
     */
    private $actiontypenr;

    /**
     * @var int|null
     *
     * @ORM\Column(name="TripInvestigatorNR", type="integer", nullable=true)
     */
    private $tripinvestigatornr;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="StartDate", type="datetime", nullable=true)
     */
    private $startdate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="EndDate", type="datetime", nullable=true)
     */
    private $enddate;

    /**
     * @var \Trip
     *
     * @ORM\ManyToOne(targetEntity="Trip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TripNR", referencedColumnName="TripID")
     * })
     */
    private $tripnr;

    /**
     * @var \Tripstations
     *
     * @ORM\ManyToOne(targetEntity="Tripstations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TripStationNR", referencedColumnName="ID")
     * })
     */
    private $tripstationnr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWriter(): ?string
    {
        return $this->writer;
    }

    public function setWriter(?string $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getActiontype(): ?string
    {
        return $this->actiontype;
    }

    public function setActiontype(?string $actiontype): self
    {
        $this->actiontype = $actiontype;

        return $this;
    }

    public function getServerdate(): ?\DateTimeInterface
    {
        return $this->serverdate;
    }

    public function setServerdate(?\DateTimeInterface $serverdate): self
    {
        $this->serverdate = $serverdate;

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

    public function getStation(): ?string
    {
        return $this->station;
    }

    public function setStation(?string $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getActionorder(): ?int
    {
        return $this->actionorder;
    }

    public function setActionorder(?int $actionorder): self
    {
        $this->actionorder = $actionorder;

        return $this;
    }

    public function getStartlat(): ?float
    {
        return $this->startlat;
    }

    public function setStartlat(?float $startlat): self
    {
        $this->startlat = $startlat;

        return $this;
    }

    public function getStartlong(): ?float
    {
        return $this->startlong;
    }

    public function setStartlong(?float $startlong): self
    {
        $this->startlong = $startlong;

        return $this;
    }

    public function getStoplat(): ?float
    {
        return $this->stoplat;
    }

    public function setStoplat(?float $stoplat): self
    {
        $this->stoplat = $stoplat;

        return $this;
    }

    public function getStoplong(): ?float
    {
        return $this->stoplong;
    }

    public function setStoplong(?float $stoplong): self
    {
        $this->stoplong = $stoplong;

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

    public function getActiontypenr(): ?int
    {
        return $this->actiontypenr;
    }

    public function setActiontypenr(?int $actiontypenr): self
    {
        $this->actiontypenr = $actiontypenr;

        return $this;
    }

    public function getTripinvestigatornr(): ?int
    {
        return $this->tripinvestigatornr;
    }

    public function setTripinvestigatornr(?int $tripinvestigatornr): self
    {
        $this->tripinvestigatornr = $tripinvestigatornr;

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

    public function getTripnr(): ?Trip
    {
        return $this->tripnr;
    }

    public function setTripnr(?Trip $tripnr): self
    {
        $this->tripnr = $tripnr;

        return $this;
    }

    public function getTripstationnr(): ?Tripstations
    {
        return $this->tripstationnr;
    }

    public function setTripstationnr(?Tripstations $tripstationnr): self
    {
        $this->tripstationnr = $tripstationnr;

        return $this;
    }


}
