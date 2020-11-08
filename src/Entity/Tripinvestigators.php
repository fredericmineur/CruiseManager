<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// * @UniqueEntity(fields={"surname", "firstname", "tripnr"}, errorPath="firsname", message="duplicate")
/**
 * Tripinvestigators
 * @ORM\Entity(repositoryClass="App\Repository\TripinvestigatorsRepository")
 * @ORM\Table(name="TripInvestigators",
 *     indexes={@ORM\Index(name="IDX_915DA6EE1644F87", columns={"InvestigatorNR"}), @ORM\Index(name="IDX_915DA6EE67E1FAAF", columns={"TripNr"})}
 *     )
 */
class Tripinvestigators
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
     * @ORM\Column(name="ShortName", type="string", length=50, nullable=true)
     */
    private $shortname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Surname", type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Firstname", type="string", length=50, nullable=true)
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Initials", type="string", length=50, nullable=true)
     */
    private $initials = '';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ServerDate", type="datetime", nullable=true)
     */
    private $serverdate ;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CampaignNR", type="integer", nullable=true)
     */
    private $campaignnr;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ImisNR", type="integer", nullable=true)
     */
    private $imisnr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Campaign", type="string", length=50, nullable=true)
     */
    private $campaign;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nationality", type="string", length=35, nullable=true)
     */
    private $nationality;

    /**
     * @var string|null
     *
     * @ORM\Column(name="passengertype", type="string", length=35, nullable=true)
     */
    private $passengertype;

    /**
     * @var int|null
     *
     * @ORM\Column(name="EXPID", type="integer", nullable=true)
     */
    private $expid;

    /**
     * @var \Investigators
     *
     * @ORM\ManyToOne(targetEntity="Investigators", inversedBy="tripinvestigators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="InvestigatorNR", referencedColumnName="InvestigatorID")
     * })
     */
    private $investigatornr;

    /**
     * @var \Trip
     *
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="tripinvestigators")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TripNr", referencedColumnName="TripID")
     * })
     * @Assert\Valid
     */
    private $tripnr;

    /**
     * @var string|null
     *
     */
    private $fullname;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Tripactions", mappedBy="tripinvestigatornr")
     */
    private $tripactions;

    public function __construct()
    {
        $this->tripactions = new ArrayCollection();
    }


    /**
     * @param null|string $fullname
     */
    public function setFullname(?string $fullname): void
    {
        $this->fullname = $fullname;
    }

    public function getFullname() : ?string
    {

        return $this->fullname;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortname(): ?string
    {
        return $this->shortname;
    }

    public function setShortname(?string $shortname): self
    {
        $this->shortname = $shortname;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getInitials(): ?string
    {
        return $this->initials;
    }

    public function setInitials(?string $initials): self
    {
        $this->initials = $initials;

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

    public function getCampaignnr(): ?int
    {
        return $this->campaignnr;
    }

    public function setCampaignnr(?int $campaignnr): self
    {
        $this->campaignnr = $campaignnr;

        return $this;
    }

    public function getImisnr(): ?int
    {
        return $this->imisnr;
    }

    public function setImisnr(?int $imisnr): self
    {
        $this->imisnr = $imisnr;

        return $this;
    }

    public function getCampaign(): ?string
    {
        return $this->campaign;
    }

    public function setCampaign(?string $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getPassengertype(): ?string
    {
        return $this->passengertype;
    }

    public function setPassengertype(?string $passengertype): self
    {
        $this->passengertype = $passengertype;

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

    public function getInvestigatornr(): ?Investigators
    {
        return $this->investigatornr;
    }

    public function setInvestigatornr(?Investigators $investigatornr): self
    {
        $this->investigatornr = $investigatornr;

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
     * @return Collection|Tripactions[]
     */
    public function getTripactions(): Collection
    {
        return $this->tripactions;
    }

    public function addTripaction(Tripactions $tripaction): self
    {
        if (!$this->tripactions->contains($tripaction)) {
            $this->tripactions[] = $tripaction;
            $tripaction->setTripinvestigatornr($this);
        }

        return $this;
    }

    public function removeTripaction(Tripactions $tripaction): self
    {
        if ($this->tripactions->contains($tripaction)) {
            $this->tripactions->removeElement($tripaction);
            // set the owning side to null (unless already changed)
            if ($tripaction->getTripinvestigatornr() === $this) {
                $tripaction->setTripinvestigatornr(null);
            }
        }

        return $this;
    }




}
