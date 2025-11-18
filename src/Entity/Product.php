<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\Column]
    private array $tags = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $requiredConfiguration = null;

    /**
     * @var Collection<int, Platform>
     */
    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'products')]
    private Collection $plateform;

    public function __construct()
    {
        $this->plateform = new ArrayCollection();
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

    public function setRequiredConfiguration(?string $requiredConfiguration): static
    {
        $this->requiredConfiguration = $requiredConfiguration;

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getPlateform(): Collection
    {
        return $this->plateform;
    }

    public function addPlateform(Platform $plateform): static
    {
        if (!$this->plateform->contains($plateform)) {
            $this->plateform->add($plateform);
        }

        return $this;
    }

    public function removePlateform(Platform $plateform): static
    {
        $this->plateform->removeElement($plateform);

        return $this;
    }

   

  

    
   

   
   
}