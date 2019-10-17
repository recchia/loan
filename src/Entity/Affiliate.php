<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\AffiliateRepository")
 */
class Affiliate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="affiliate")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="affiliates")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contactPerson;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $contactPhone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalContactPerson;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $additionalContactPhone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commission", inversedBy="affiliates")
     */
    private $commission;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank", inversedBy="affiliates")
     */
    private $bank;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accountClientName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ruc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $legalRepresentative;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAffiliate($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getAffiliate() === $this) {
                $user->setAffiliate(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?string $contactPerson): self
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): self
    {
        $this->contactPhone = $contactPhone;

        return $this;
    }

    public function getAdditionalContactPerson(): ?string
    {
        return $this->additionalContactPerson;
    }

    public function setAdditionalContactPerson(?string $additionalContactPerson): self
    {
        $this->additionalContactPerson = $additionalContactPerson;

        return $this;
    }

    public function getAdditionalContactPhone(): ?string
    {
        return $this->additionalContactPhone;
    }

    public function setAdditionalContactPhone(?string $additionalContactPhone): self
    {
        $this->additionalContactPhone = $additionalContactPhone;

        return $this;
    }

    public function getCommission(): ?Commission
    {
        return $this->commission;
    }

    public function setCommission(?Commission $commission): self
    {
        $this->commission = $commission;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getAccountClientName(): ?string
    {
        return $this->accountClientName;
    }

    public function setAccountClientName(string $accountClientName): self
    {
        $this->accountClientName = $accountClientName;

        return $this;
    }

    public function getRuc(): ?string
    {
        return $this->ruc;
    }

    public function setRuc(string $ruc): self
    {
        $this->ruc = $ruc;

        return $this;
    }

    public function getLegalRepresentative(): ?string
    {
        return $this->legalRepresentative;
    }

    public function setLegalRepresentative(string $legalRepresentative): self
    {
        $this->legalRepresentative = $legalRepresentative;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
