<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\PlatformRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlatformRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['platform:read']]),
        new Get(normalizationContext: ['groups' => ['platform:read']]),
        new Post(security: "is_granted('ROLE_USER')", denormalizationContext: ['groups' => ['platform:write']]),
        new Put(security: "is_granted('ROLE_USER')", denormalizationContext: ['groups' => ['platform:write']]),
        new Delete(security: "is_granted('ROLE_USER')"),
    ],
)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['platform:read', 'product:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['platform:read', 'platform:write', 'product:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['platform:read', 'platform:write', 'product:read'])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['platform:read', 'platform:write', 'product:read'])]
    private ?string $url = null;

    #[ORM\Column]
    #[Groups(['platform:read', 'platform:write', 'product:read'])]
    private array $systems = [];

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'plateform')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
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

    public function getSystems(): array
    {
        return $this->systems;
    }

    public function setSystems(array $systems): static
    {
        $this->systems = $systems;
        return $this;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addPlateform($this);
        }
        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);
        $product->removePlateform($this);
        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? 'Plateforme sans nom';
    }
}
