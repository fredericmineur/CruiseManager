<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Campaign
 *
 * @ORM\Table(name="Campaign")
 * @ORM\Entity(repositoryClass="App\Repository\CampaignRepository")
 * @UniqueEntity(fields="imisprojectnr", message="IMIS number already in use", ignoreNull=true)
 * @UniqueEntity(fields="campaign", message="Title of the campaign already in use")
 */
class Campaign
{
    /**
     * @var int
     *
     * @ORM\Column(name="CampaignID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("get_cruises_for_table")
     */
    private $campaignid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Campaign", type="string", length=50, nullable=true)
     * @Groups("get_cruises_for_table")
     */
    private $campaign;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=4000, nullable=true)
     */
    private $memo;

    /**
     * @param int $campaignid
     */
    public function setCampaignid(int $campaignid): void
    {
        $this->campaignid = $campaignid;
    }

    /**
     * @var int|null
     * @ORM\Column(name="ImisProjectnr", type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]{1,6}$/", message="IMIS number only contains digit")
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
     * @ORM\OrderBy({"plancode"="ASC"})
     */
    private $cruise;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Sample", mappedBy="campaign")
     */
    private $samples;

//    /**
//     * @var string|null
//     */
//    private $imisPlusName;

//    /**
//     * @return string|null
//     */
//    public function getImisPlusName(): ?string
//    {
//        return $this->imisPlusName;
//    }
//
//    /**
//     * @param string|null $imisPlusName
//     */
//    public function setImisPlusName(?string $imisPlusName): void
//    {
//        $this->imisPlusName = $imisPlusName;
//    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cruise = new \Doctrine\Common\Collections\ArrayCollection();
        $this->samples = new ArrayCollection();
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

    /**
     * @return Collection|Sample[]
     */
    public function getSamples(): Collection
    {
        return $this->samples;
    }

    public function addSample(Sample $sample): self
    {
        if (!$this->samples->contains($sample)) {
            $this->samples[] = $sample;
            $sample->setCampaign($this);
        }

        return $this;
    }

    public function removeSample(Sample $sample): self
    {
        if ($this->samples->contains($sample)) {
            $this->samples->removeElement($sample);
            // set the owning side to null (unless already changed)
            if ($sample->getCampaign() === $this) {
                $sample->setCampaign(null);
            }
        }

        return $this;
    }

}
