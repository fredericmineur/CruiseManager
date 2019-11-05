<?php

namespace App\Entity;

use Cassandra\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Investigators
 * @ORM\Entity(repositoryClass="App\Repository\InvestigatorsRepository")
 * @ORM\Table(name="Investigators", indexes={@ORM\Index(name="InvestigatorID", columns={"InvestigatorID"})})
 */
class Investigators
{
    /**
     * @var int
     *
     * @ORM\Column(name="InvestigatorID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $investigatorid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Shortname", type="string", length=30, nullable=true)
     */
    private $shortname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Surname", type="string", length=35, nullable=true)
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Firstname", type="string", length=35, nullable=true)
     */
    private $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Initials", type="string", length=10, nullable=true)
     */
    private $initials;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=2000, nullable=true)
     */
    private $memo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ImisNR", type="integer", nullable=true)
     */
    private $imisnr;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Birthdate", type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Nationality", type="string", length=35, nullable=true)
     */
    private $nationality;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Passengertype", type="string", length=35, nullable=true)
     */
    private $passengertype;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Cruise", mappedBy="principalinvestigator")
     */
    private $cruises;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Tripinvestigators", mappedBy="investigatornr")
     */
    private $tripinvestigators;

    public function __construct()
    {
        $this->cruises = new ArrayCollection();
        $this->tripinvestigators = new ArrayCollection();
    }

    public function getInvestigatorid(): ?int
    {
        return $this->investigatorid;
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

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): self
    {
        $this->memo = $memo;

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

    /**
     * @return \Doctrine\Common\Collections\Collection|Cruise[]
     */
    public function getCruises(): \Doctrine\Common\Collections\Collection
    {
        return $this->cruises;
    }

    public function addCruise(Cruise $cruise): self
    {
        if (!$this->cruises->contains($cruise)) {
            $this->cruises[] = $cruise;
            $cruise->setInvestigatorid($this);
        }

        return $this;
    }

    public function removeCruise(Cruise $cruise): self
    {
        if ($this->cruises->contains($cruise)) {
            $this->cruises->removeElement($cruise);
            // set the owning side to null (unless already changed)
            if ($cruise->getInvestigatorid() === $this) {
                $cruise->setInvestigatorid(null);
            }
        }

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
            $tripinvestigator->setInvestigatornr($this);
        }

        return $this;
    }

    public function removeTripinvestigator(Tripinvestigators $tripinvestigator): self
    {
        if ($this->tripinvestigators->contains($tripinvestigator)) {
            $this->tripinvestigators->removeElement($tripinvestigator);
            // set the owning side to null (unless already changed)
            if ($tripinvestigator->getInvestigatornr() === $this) {
                $tripinvestigator->setInvestigatornr(null);
            }
        }

        return $this;
    }


}
