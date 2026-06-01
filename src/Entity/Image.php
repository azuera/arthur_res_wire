<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['image:read']]),
        new Get(normalizationContext: ['groups' => ['image:read']]),
    ],
)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['image:read', 'product:read'])]
    private ?int $id = null;

    // ✅ Ce champ stocke le chemin ou le nom du fichier en BDD
    #[ORM\Column(length: 255)]
    #[Groups(['image:read', 'image:write', 'product:read'])]
    private ?string $url = null;

    // ✅ Ce champ est le fichier uploadé (non persisté en BDD)
    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'url')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['image:read', 'image:write', 'product:read'])]
    private ?string $altText = null;

    #[ORM\Column]
    #[Groups(['image:read', 'image:write', 'product:read'])]
    private ?int $displayOrder = 0;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[Groups(['image:read'])]
    private ?Product $product = null;

    // 👉 OBLIGATOIRE pour que VichUploader fonctionne
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;
        return $this;
    }

    // ✅ Getter et Setter pour le fichier uploadé
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): static
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(?string $altText): static
    {
        $this->altText = $altText;
        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function __toString(): string
    {
        return $this->altText ?? 'Image #' . $this->id;
    }
}
