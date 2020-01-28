<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ships
 *
 * @ORM\Table(name="Ships")
 * @ORM\Entity
 */
class Ships
{
    /**
     * @var string
     *
     * @ORM\Column(name="ShipName", type="string", length=25, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shipname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Requesttype", type="string", length=10, nullable=true, options={"fixed"=true})
     */
    private $requesttype;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Requestdescription", type="string", length=100, nullable=true)
     */
    private $requestdescription;

    /**
     * @return string
     */
    public function getShipname(): string
    {
        return $this->shipname;
    }

    /**
     * @param string $shipname
     */
    public function setShipname(string $shipname): void
    {
        $this->shipname = $shipname;
    }

    /**
     * @return string|null
     */
    public function getRequesttype(): ?string
    {
        return $this->requesttype;
    }

    /**
     * @param string|null $requesttype
     */
    public function setRequesttype(?string $requesttype): void
    {
        $this->requesttype = $requesttype;
    }

    /**
     * @return string|null
     */
    public function getRequestdescription(): ?string
    {
        return $this->requestdescription;
    }

    /**
     * @param string|null $requestdescription
     */
    public function setRequestdescription(?string $requestdescription): void
    {
        $this->requestdescription = $requestdescription;
    }




}
