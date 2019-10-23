<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sample
 *
 * @ORM\Table(name="Sample", indexes={@ORM\Index(name="SampleID", columns={"SampleID"}), @ORM\Index(name="IDX_F6A773F513207C7D", columns={"ActionType"}), @ORM\Index(name="IDX_F6A773F5E663708B", columns={"Campaign"})})
 * @ORM\Entity
 */
class Sample
{
    /**
     * @var int
     *
     * @ORM\Column(name="SampleID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sampleid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="Stop", type="datetime", nullable=true)
     */
    private $stop;

    /**
     * @var int|null
     *
     * @ORM\Column(name="SamplePeriod", type="integer", nullable=true)
     */
    private $sampleperiod = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="Keep", type="boolean", nullable=true, options={"default"="1"})
     */
    private $keep = '1';

    /**
     * @var string|null
     *
     * @ORM\Column(name="Memo", type="string", length=50, nullable=true)
     */
    private $memo;

    /**
     * @var \Actiontype
     *
     * @ORM\ManyToOne(targetEntity="Actiontype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ActionType", referencedColumnName="ActionTypeID")
     * })
     */
    private $actiontype;

    /**
     * @var \Campaign
     *
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Campaign", referencedColumnName="CampaignID")
     * })
     */
    private $campaign;

    public function getSampleid(): ?int
    {
        return $this->sampleid;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getStop(): ?\DateTimeInterface
    {
        return $this->stop;
    }

    public function setStop(?\DateTimeInterface $stop): self
    {
        $this->stop = $stop;

        return $this;
    }

    public function getSampleperiod(): ?int
    {
        return $this->sampleperiod;
    }

    public function setSampleperiod(?int $sampleperiod): self
    {
        $this->sampleperiod = $sampleperiod;

        return $this;
    }

    public function getKeep(): ?bool
    {
        return $this->keep;
    }

    public function setKeep(?bool $keep): self
    {
        $this->keep = $keep;

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

    public function getActiontype(): ?Actiontype
    {
        return $this->actiontype;
    }

    public function setActiontype(?Actiontype $actiontype): self
    {
        $this->actiontype = $actiontype;

        return $this;
    }

    public function getCampaign(): ?Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(?Campaign $campaign): self
    {
        $this->campaign = $campaign;

        return $this;
    }


}
