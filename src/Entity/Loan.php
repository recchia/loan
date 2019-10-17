<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use function GuzzleHttp\Psr7\str;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\LoanRepository")
 * @ORM\EntityListeners({"App\EventListener\LoanListener"})
 */
class Loan
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $documentId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $workingAge;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $salary;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $haveDiscounts;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $amountRequest;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $amountDiscount;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $delivery;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="loans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="loans")
     */
    private $status;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    private $commissionPercentage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $commissionAmount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legacyClientId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $legacyLoanId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DocumentType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $documentType;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AttachedFile", mappedBy="loan", cascade={"persist"})
     */
    private $attachedFiles;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sent;

    public function __construct()
    {
        $this->attachedFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDocumentId(): ?string
    {
        return $this->documentId;
    }

    public function setDocumentId(string $documentId): self
    {
        $this->documentId = $documentId;

        return $this;
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getWorkingAge(): ?string
    {
        return $this->workingAge;
    }

    public function setWorkingAge(?string $workingAge): self
    {
        $this->workingAge = $workingAge;

        return $this;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function setSalary($salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getHaveDiscounts(): ?bool
    {
        return $this->haveDiscounts;
    }

    public function setHaveDiscounts(?bool $haveDiscounts): self
    {
        $this->haveDiscounts = $haveDiscounts;

        return $this;
    }

    public function getAmountRequest()
    {
        return $this->amountRequest;
    }

    public function setAmountRequest($amountRequest): self
    {
        $this->amountRequest = $amountRequest;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getAmountDiscount()
    {
        return $this->amountDiscount;
    }

    public function setAmountDiscount($amountDiscount): self
    {
        $this->amountDiscount = $amountDiscount;

        return $this;
    }

    public function getDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDelivery(?bool $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCommissionPercentage(): ?string
    {
        return $this->commissionPercentage;
    }

    public function setCommissionPercentage(?string $commissionPercentage): self
    {
        $this->commissionPercentage = $commissionPercentage;

        return $this;
    }

    public function getCommissionAmount(): ?string
    {
        return $this->commissionAmount;
    }

    public function setCommissionAmount(?string $commissionAmount): self
    {
        $this->commissionAmount = $commissionAmount;

        return $this;
    }

    public function getName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getLegacyClientId(): ?int
    {
        return $this->legacyClientId;
    }

    public function setLegacyClientId(?int $legacyClientId): self
    {
        $this->legacyClientId = $legacyClientId;

        return $this;
    }

    public function getLegacyLoanId(): ?int
    {
        return $this->legacyLoanId;
    }

    public function setLegacyLoanId(?int $legacyLoanId): self
    {
        $this->legacyLoanId = $legacyLoanId;

        return $this;
    }

    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    public function setDocumentType(?DocumentType $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|AttachedFile[]
     */
    public function getAttachedFiles(): Collection
    {
        return $this->attachedFiles;
    }

    public function addAttachedFile(AttachedFile $attachedFile): self
    {
        if (!$this->attachedFiles->contains($attachedFile)) {
            $this->attachedFiles[] = $attachedFile;
            $attachedFile->setLoan($this);
        }

        return $this;
    }

    public function removeAttachedFile(AttachedFile $attachedFile): self
    {
        if ($this->attachedFiles->contains($attachedFile)) {
            $this->attachedFiles->removeElement($attachedFile);
            // set the owning side to null (unless already changed)
            if ($attachedFile->getLoan() === $this) {
                $attachedFile->setLoan(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getSent(): ?bool
    {
        return $this->sent;
    }

    public function setSent(?bool $sent): self
    {
        $this->sent = $sent;

        return $this;
    }
}
