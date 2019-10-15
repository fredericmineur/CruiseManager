<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Campaign
 *
 * @ORM\Table(name="Campaign")
 * @ORM\Entity
 */
class Campaign
{
    /**
     * @var int
     *
     * @ORM\Column(name="CampaignID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $campaignid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Campaign", type="string", length=50, nullable=true)
     */
    private $campaign;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=4000, nullable=true)
     */
    private $memo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ImisProjectnr", type="integer", nullable=true)
     */
    private $imisprojectnr;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Cruise", inversedBy="campaign")
     * @ORM\JoinTable(name="camppercruise",
     *   joinColumns={
     *     @ORM\JoinColumn(name="Campaign", referencedColumnName="CampaignID")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Cruise", referencedColumnName="CruiseID")
     *   }
     * )
     */
    private $cruise;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cruise = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getCampaignid(): ?int
    {
        return $this->campaignid;
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

    public function getMemo(): ?string
    {
        return $this->memo;
    }

    public function setMemo(?string $memo): self
    {
        $this->memo = $memo;

        return $this;
    }

    public function getImisprojectnr(): ?int
    {
        return $this->imisprojectnr;
    }

    public function setImisprojectnr(?int $imisprojectnr): self
    {
        $this->imisprojectnr = $imisprojectnr;

        return $this;
    }

    /**
     * @return Collection|Cruise[]
     */
    public function getCruise(): Collection
    {
        return $this->cruise;
    }

    public function addCruise(Cruise $cruise): self
    {
        if (!$this->cruise->contains($cruise)) {
            $this->cruise[] = $cruise;
        }

        return $this;
    }

    public function removeCruise(Cruise $cruise): self
    {
        if ($this->cruise->contains($cruise)) {
            $this->cruise->removeElement($cruise);
        }

        return $this;
    }

}
