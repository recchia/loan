<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\AttachedFileRepository")
 * @Vich\Uploadable()
 * @ORM\EntityListeners({"App\EventListener\AttachedFileListener"})
 */
class AttachedFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FileType", inversedBy="attachedFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fileType;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="loans", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Loan", inversedBy="attachedFiles")
     */
    private $loan;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileType(): ?FileType
    {
        return $this->fileType;
    }

    public function setFileType(?FileType $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @throws Exception
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->updatedAt = new \DateTime('now');
        }
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

    public function getLoan(): ?Loan
    {
        return $this->loan;
    }

    public function setLoan(?Loan $loan): self
    {
        $this->loan = $loan;

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

    public function __toString()
    {
        return (string)$this->getDescription();
    }
}
