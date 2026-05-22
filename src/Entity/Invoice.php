<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ApiResource]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?float $totalAmount = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    private ?User $user = null;

    /**
     * @var Collection<int, ProductKey>
     */
    #[ORM\OneToMany(targetEntity: ProductKey::class, mappedBy: 'invoice')]  // ← CORRIGÉ : targetEntity ProductKey au lieu de Product
    private Collection $productKeys;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
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

    /**
     * @return Collection<int, ProductKey>
     */
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
        if ($this->productKeys->removeElement($productKey)) {
            if ($productKey->getInvoice() === $this) {
                $productKey->setInvoice(null);
            }
        }
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
