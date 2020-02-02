<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DealerRepository")
 */
class Dealer
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\DealerAddress", mappedBy="dealer", cascade={"persist", "remove"})
     */
    private $dealerAddress;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DealerAddress|null
     */
    public function getDealerAddress(): ?DealerAddress
    {
        return $this->dealerAddress;
    }

    /**
     * @param DealerAddress $dealerAddress
     * @return Dealer
     */
    public function setDealerAddress(DealerAddress $dealerAddress): self
    {
        $this->dealerAddress = $dealerAddress;

        // set the owning side of the relation if necessary
        if ($this !== $dealerAddress->getDealer()) {
            $dealerAddress->setDealer($this);
        }

        return $this;
    }
}
