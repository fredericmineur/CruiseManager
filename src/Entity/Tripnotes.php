<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tripnotes
 *
 * @ORM\Table(name="TripNotes", indexes={@ORM\Index(name="IDX_3436E85B67E1FAAF", columns={"TripNr"})})
 * @ORM\Entity
 */
class Tripnotes
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
     * @ORM\Column(name="ServerDate", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $serverdate = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Note", type="string", length=4000, nullable=true)
     */
    private $note;

    /**
     * @var string|null
     *
     * @ORM\Column(name="NoteWriter", type="string", length=25, nullable=true, options={"fixed"=true})
     */
    private $notewriter;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Lat", type="float", precision=53, scale=0, nullable=true)
     */
    private $lat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="Long", type="float", precision=53, scale=0, nullable=true)
     */
    private $long;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var int|null
     *
     * @ORM\Column(name="InvestigatorID", type="integer", nullable=true)
     */
    private $investigatorid;

    /**
     * @var \Trip
     *
     * @ORM\ManyToOne(targetEntity="Trip")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TripNr", referencedColumnName="TripID")
     * })
     */
    private $tripnr;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getNotewriter(): ?string
    {
        return $this->notewriter;
    }

    public function setNotewriter(?string $notewriter): self
    {
        $this->notewriter = $notewriter;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLong(): ?float
    {
        return $this->long;
    }

    public function setLong(?float $long): self
    {
        $this->long = $long;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getInvestigatorid(): ?int
    {
        return $this->investigatorid;
    }

    public function setInvestigatorid(?int $investigatorid): self
    {
        $this->investigatorid = $investigatorid;

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


}
