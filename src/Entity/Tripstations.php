<?php

namespace App\Entity;

use Cassandra\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tripstations
 *
 * @ORM\Table(name="TRIPSTATIONS", indexes={@ORM\Index(name="IDX_70619829660FB8E1", columns={"StationNR"}), @ORM\Index(name="IDX_7061982967E1FAAF", columns={"TripNr"})})
 * @ORM\Entity
 */
class Tripstations
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
     * @var int|null
     *
     * @ORM\Column(name="OrderNr", type="integer", nullable=true)
     */
    private $ordernr = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Code", type="string", length=15, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Name", type="string", length=50, nullable=true)
     */
    private $name;

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
     * @ORM\Column(name="EndLat", type="float", precision=53, scale=0, nullable=true)
     */
    private $endlat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="EndLong", type="float", precision=53, scale=0, nullable=true)
     */
    private $endlong;

    /**
     * @var float|null
     *
     * @ORM\Column(name="DefLatitude", type="float", precision=53, scale=0, nullable=true)
     */
    private $deflatitude;

    /**
     * @var float|null
     *
     * @ORM\Column(name="DefLongitude", type="float", precision=53, scale=0, nullable=true)
     */
    private $deflongitude;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Serverdate", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $serverdate ;

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
     * @var int|null
     *
     * @ORM\Column(name="EXPID", type="integer", nullable=true)
     */
    private $expid;

    /**
     * @var \Stations
     *
     * @ORM\ManyToOne(targetEntity="Stations", inversedBy="tripstations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="StationNR", referencedColumnName="NR")
     * })
     */
    private $stationnr;

    /**
     * @var \Trip
     *
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="tripstations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TripNr", referencedColumnName="TripID")
     * })
     */
    private $tripnr;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripactions", mappedBy="tripstationnr")
     */
    private $tripactions;

    /**
     * @var string
     */
    private $codeLatLong;

    /**
     * @return string
     */
    public function getCodeLatLong(): string
    {
        return $this->codeLatLong;
    }

    /**
     * @param string $codeLatLong
     */
    public function setCodeLatLong(string $codeLatLong): void
    {
        $this->codeLatLong = $codeLatLong;
    }

    public function __construct()
    {
        $this->tripactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdernr(): ?int
    {
        return $this->ordernr;
    }

    public function setOrdernr(?int $ordernr): self
    {
        $this->ordernr = $ordernr;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getEndlat(): ?float
    {
        return $this->endlat;
    }

    public function setEndlat(?float $endlat): self
    {
        $this->endlat = $endlat;

        return $this;
    }

    public function getEndlong(): ?float
    {
        return $this->endlong;
    }

    public function setEndlong(?float $endlong): self
    {
        $this->endlong = $endlong;

        return $this;
    }

    public function getDeflatitude(): ?float
    {
        return $this->deflatitude;
    }

    public function setDeflatitude(?float $deflatitude): self
    {
        $this->deflatitude = $deflatitude;

        return $this;
    }

    public function getDeflongitude(): ?float
    {
        return $this->deflongitude;
    }

    public function setDeflongitude(?float $deflongitude): self
    {
        $this->deflongitude = $deflongitude;

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

    public function getExpid(): ?int
    {
        return $this->expid;
    }

    public function setExpid(?int $expid): self
    {
        $this->expid = $expid;

        return $this;
    }

    public function getStationnr(): ?Stations
    {
        return $this->stationnr;
    }

    public function setStationnr(?Stations $stationnr): self
    {
        $this->stationnr = $stationnr;

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
            $tripaction->setTripstationnr($this);
        }

        return $this;
    }

    public function removeTripaction(Tripactions $tripaction): self
    {
        if ($this->tripactions->contains($tripaction)) {
            $this->tripactions->removeElement($tripaction);
            // set the owning side to null (unless already changed)
            if ($tripaction->getTripstationnr() === $this) {
                $tripaction->setTripstationnr(null);
            }
        }

        return $this;
    }


}
