<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?User $user = null;

    /**
     * @var Collection<int, Subscription>
     */
    #[ORM\ManyToMany(targetEntity: Subscription::class, inversedBy: 'transactions')]
    private Collection $suscriptions;

    public function __construct()
    {
        $this->suscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

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
     * @return Collection<int, Subscription>
     */
    public function getSuscriptions(): Collection
    {
        return $this->suscriptions;
    }

    public function addSuscription(Subscription $suscription): static
    {
        if (!$this->suscriptions->contains($suscription)) {
            $this->suscriptions->add($suscription);
        }

        return $this;
    }

    public function removeSuscription(Subscription $suscription): static
    {
        $this->suscriptions->removeElement($suscription);

        return $this;
    }
}
