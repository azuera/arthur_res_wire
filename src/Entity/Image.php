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

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['image:read']]),
        new Get(normalizationContext: ['groups' => ['image:read']]),
        new Post(security: "is_granted('ROLE_USER')", denormalizationContext: ['groups' => ['image:write']]),
        new Put(security: "is_granted('ROLE_USER')", denormalizationContext: ['groups' => ['image:write']]),
        new Delete(security: "is_granted('ROLE_USER')"),
    ],
)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['image:read', 'product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['image:read', 'image:write', 'product:read'])]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['image:read', 'image:write', 'product:read'])]
    private ?string $altText = null;

    #[ORM\Column]
    #[Groups(['image:read', 'image:write', 'product:read'])]
    private ?int $displayOrder = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[Groups(['image:read'])]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
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

    public function __toString(): string
    {
        return $this->altText ?? 'Image #' . $this->id;
    }
}
