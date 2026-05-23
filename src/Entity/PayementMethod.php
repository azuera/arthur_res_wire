<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use App\Repository\PayementMethodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PayementMethodRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['paymentmethod:read']],
            security: "is_granted('ROLE_USER')",
        ),
        new Get(
            normalizationContext: ['groups' => ['paymentmethod:read']],
            security: "is_granted('ROLE_USER') and object.getUser() == user",
        ),
        new Post(
            security: "is_granted('ROLE_USER')",
            denormalizationContext: ['groups' => ['paymentmethod:write']],
        ),
        new Patch(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
            denormalizationContext: ['groups' => ['paymentmethod:write']],
        ),
        new Delete(
            security: "is_granted('ROLE_USER') and object.getUser() == user",
        ),
    ],
)]
class PayementMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['paymentmethod:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['paymentmethod:read', 'paymentmethod:write', 'user:read'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups(['paymentmethod:read', 'paymentmethod:write', 'user:read'])]
    private ?string $lastDigits = null;

    #[ORM\ManyToOne(inversedBy: 'paymentMethods')]
    #[Groups(['paymentmethod:read'])]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'paymentMethod')]
    #[Groups(['paymentmethod:read'])]
    private Collection $invoices;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastDigits(): ?string
    {
        return $this->lastDigits;
    }

    public function setLastDigits(string $lastDigits): static
    {
        $this->lastDigits = $lastDigits;
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

    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setPaymentMethod($this);
        }
        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice) && $invoice->getPaymentMethod() === $this) $invoice->setPaymentMethod(null);
        return $this;
    }

    public function __toString(): string
    {
        return $this->type . ' (**** ' . $this->lastDigits . ')';
    }
}
