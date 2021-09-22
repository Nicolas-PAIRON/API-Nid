<?php

namespace App\Entity;

use App\Repository\AddressBillRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=AddressBillRepository::class)
 */
class AddressBill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=58, nullable=true)
     */
    private $town;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\Column(type="string", length=33, nullable=true)
     */
    private $country;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="addressBill", cascade={"persist", "remove"})
     * @Ignore()
     */
    private $user;

    public function __toString()
    {
        $format = "%s <br> %s <br> %s <br> %s";
        return sprintf($format, $this->address, $this->town, $this->postcode, $this->country);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(?string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setAddressBill(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getAddressBill() !== $this) {
            $user->setAddressBill($this);
        }

        $this->user = $user;

        return $this;
    }
}
