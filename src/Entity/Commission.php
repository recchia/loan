<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CommissionRepository")
 */
class Commission
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $percentage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="commission")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affiliate", mappedBy="commission")
     */
    private $affiliates;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($percentage): self
    {
        $this->percentage = $percentage;

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
            $user->setCommission($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getCommission() === $this) {
                $user->setCommission(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getDescription();
    }

    /**
     * @return Collection|Affiliate[]
     */
    public function getAffiliates(): Collection
    {
        return $this->affiliates;
    }

    public function addAffiliate(Affiliate $affiliate): self
    {
        if (!$this->affiliates->contains($affiliate)) {
            $this->affiliates[] = $affiliate;
            $affiliate->setCommission($this);
        }

        return $this;
    }

    public function removeAffiliate(Affiliate $affiliate): self
    {
        if ($this->affiliates->contains($affiliate)) {
            $this->affiliates->removeElement($affiliate);
            // set the owning side to null (unless already changed)
            if ($affiliate->getCommission() === $this) {
                $affiliate->setCommission(null);
            }
        }

        return $this;
    }
}
