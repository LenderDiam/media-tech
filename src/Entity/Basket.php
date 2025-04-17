<?php

namespace App\Entity;

use App\Enum\BasketState;
use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: BasketState::class, options: ['default' => BasketState::Pending->value])]
    private ?BasketState $state = BasketState::Pending;

    #[ORM\ManyToOne(inversedBy: 'baskets')]
    private ?User $user = null;

    /**
     * @var Collection<int, Copy>
     */
    #[ORM\ManyToMany(targetEntity: Copy::class, mappedBy: 'baskets')]
    private Collection $copies;

    public function __construct()
    {
        $this->copies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getState(): ?BasketState
    {
        return $this->state;
    }

    public function setState(BasketState $state): static
    {
        $this->state = $state;

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
     * @return Collection<int, Copy>
     */
    public function getCopies(): Collection
    {
        return $this->copies;
    }

    public function addCopy(Copy $copy): static
    {
        if (!$this->copies->contains($copy)) {
            $this->copies->add($copy);
            $copy->addBasket($this);
        }

        return $this;
    }

    public function removeCopy(Copy $copy): static
    {
        if ($this->copies->removeElement($copy)) {
            $copy->removeBasket($this);
        }

        return $this;
    }
}
