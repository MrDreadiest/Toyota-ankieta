<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DealerAddressRepository")
 */
class DealerAddress
{
    /**
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="App\Entity\Dealer", inversedBy="dealerAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $dealer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $buildingNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apartmentNumber;

    /**
     * @return Dealer|null
     */
    public function getDealer(): ?Dealer
    {
        return $this->dealer;
    }

    /**
     * @param Dealer $dealer
     * @return DealerAddress
     */
    public function setDealer(Dealer $dealer): self
    {
        $this->dealer = $dealer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return DealerAddress
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * @param string|null $district
     * @return DealerAddress
     */
    public function setDistrict(?string $district): self
    {
        $this->district = $district;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return DealerAddress
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBuildingNumber(): ?string
    {
        return $this->buildingNumber;
    }

    /**
     * @param string $buildingNumber
     * @return DealerAddress
     */
    public function setBuildingNumber(string $buildingNumber): self
    {
        $this->buildingNumber = $buildingNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    /**
     * @param string|null $apartmentNumber
     * @return DealerAddress
     */
    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber;

        return $this;
    }
}
