<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user_listin:read"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\EntityListeners({"App\EventListener\UserListener"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"user_listin:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_listin:read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     * @Groups({"user_listin:read"})
     */
    private $plainPassword;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Affiliate", inversedBy="users")
     */
    private $affiliate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commission", inversedBy="users")
     */
    private $commission;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loan", mappedBy="user")
     */
    private $loans;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $validationToken;

    public function __construct()
    {
        $this->loans = new ArrayCollection();
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getAffiliate(): ?Affiliate
    {
        return $this->affiliate;
    }

    public function setAffiliate(?Affiliate $affiliate): self
    {
        $this->affiliate = $affiliate;

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

    public function __toString()
    {
        return (string)$this->getEmail();
    }

    /**
     * @return Collection|Loan[]
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loan $loan): self
    {
        if (!$this->loans->contains($loan)) {
            $this->loans[] = $loan;
            $loan->setUser($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): self
    {
        if ($this->loans->contains($loan)) {
            $this->loans->removeElement($loan);
            // set the owning side to null (unless already changed)
            if ($loan->getUser() === $this) {
                $loan->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getValidationToken(): ?string
    {
        return $this->validationToken;
    }

    public function setValidationToken(?string $validationToken): self
    {
        $this->validationToken = $validationToken;

        return $this;
    }

    public function hasAffiliate(): bool
    {
        return $this->getAffiliate() !== null;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
