<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['invoice:read']],
            security: "is_granted('ROLE_USER')",
        ),
        new Get(
            normalizationContext: ['groups' => ['invoice:read']],
            // Seul le propriétaire peut voir SA facture
            security: "is_granted('ROLE_USER') and object.getUser() == user",
        ),
        new Post(
            security: "is_granted('ROLE_USER')",
            denormalizationContext: ['groups' => ['invoice:write']],
        ),
        new Patch(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
            denormalizationContext: ['groups' => ['invoice:write']],
        ),
        new Delete(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
        ),
    ],
)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['invoice:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['invoice:read', 'invoice:write', 'user:read'])]
    private ?string $number = null;

    #[ORM\Column]
    #[Groups(['invoice:read', 'invoice:write', 'user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['invoice:read', 'invoice:write', 'user:read'])]
    private ?float $totalAmount = null;

    #[ORM\Column(length: 255)]
    #[Groups(['invoice:read', 'invoice:write', 'user:read'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[Groups(['invoice:read'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: ProductKey::class, mappedBy: 'invoice')]
    #[Groups(['invoice:read'])]
    private Collection $productKeys;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[Groups(['invoice:read'])]
    private ?PayementMethod $paymentMethod = null;

    public function __construct()
    {
        $this->productKeys = new ArrayCollection();
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
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
            $productKey->setInvoice($this);
        }
        return $this;
    }

    public function removeProductKey(ProductKey $productKey): static
    {
        if ($this->productKeys->removeElement($productKey) && $productKey->getInvoice() === $this) $productKey->setInvoice(null);
        return $this;
    }

    public function getPaymentMethod(): ?PayementMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PayementMethod $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function __toString(): string
    {
        return $this->number ?? 'Facture #' . $this->id;
    }
}
