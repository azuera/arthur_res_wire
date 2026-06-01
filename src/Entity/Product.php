<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['product:read']]),
        new Get(normalizationContext: ['groups' => ['product:read']]),

    ],
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private ?int $quantity = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $region = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?float $rating = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private array $tags = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $requiredConfiguration = null;

    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'products')]
    #[Groups(['product:read'])]
    private Collection $plateform;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class)]
    #[Groups(['product:read'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductKey::class)]
    private Collection $productKeys;

    public function __construct()
    {
        $this->plateform = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->productKeys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;
        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): static
    {
        $this->tags = $tags;
        return $this;
    }

    public function getRequiredConfiguration(): ?string
    {
        return $this->requiredConfiguration;
    }

    public function setRequiredConfiguration(?string $v): static
    {
        $this->requiredConfiguration = $v;
        return $this;
    }

    public function getPlateform(): Collection
    {
        return $this->plateform;
    }

    public function addPlateform(Platform $plateform): static
    {
        if (!$this->plateform->contains($plateform)) $this->plateform->add($plateform);
        return $this;
    }

    public function removePlateform(Platform $plateform): static
    {
        $this->plateform->removeElement($plateform);
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }
        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image) && $image->getProduct() === $this) $image->setProduct(null);
        return $this;
    }

    public function getProductKeys(): Collection
    {
        return $this->productKeys;
    }

    public function addProductKey(ProductKey $productKey): static
    {
        if (!$this->productKeys->contains($productKey)) {
            $this->productKeys->add($productKey);
            $productKey->setProduct($this);
        }
        return $this;
    }

    public function removeProductKey(ProductKey $productKey): static
    {
        if ($this->productKeys->removeElement($productKey) && $productKey->getProduct() === $this) $productKey->setProduct(null);
        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? 'Produit';
    }
}
