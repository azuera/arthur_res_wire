<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProductKeyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductKeyRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['productkey:read']],
            security: "is_granted('ROLE_USER')",
        ),
        new Get(
            normalizationContext: ['groups' => ['productkey:read']],

            security: "is_granted('ROLE_USER') and object.getInvoice().getUser() == user",
        ),
    ],
)]
class ProductKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['productkey:read', 'invoice:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['productkey:read', 'invoice:read'])]
    private ?string $number = null;

    #[ORM\Column]
    #[Groups(['productkey:read', 'invoice:read'])]
    private ?\DateTime $datetime = null;

    #[ORM\ManyToOne(inversedBy: 'productKeys')]
    #[Groups(['productkey:read'])]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'productKeys')]
    private ?Invoice $invoice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTime $datetime): static
    {
        $this->datetime = $datetime;
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

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;
        return $this;
    }

    public function __toString(): string
    {
        $gameName = $this->product ? $this->product->getTitle() : 'Sans produit';
        return $gameName . ' — ' . ($this->number ?? '');
    }
}
