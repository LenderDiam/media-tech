<?php

namespace App\Entity;

use App\Enum\CopyPhysicalCondition;
use App\Enum\CopyState;
use App\Repository\CopyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CopyRepository::class)]
class Copy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: CopyState::class, options: ['default' => CopyState::Available->value])]
    private ?CopyState $state = CopyState::Available;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: CopyPhysicalCondition::class, options: ['default' => CopyPhysicalCondition::New->value])]
    private ?CopyPhysicalCondition $physicalCondition = CopyPhysicalCondition::New;

    #[ORM\ManyToOne(inversedBy: 'copies')]
    private ?Document $document = null;

    /**
     * @var Collection<int, Basket>
     */
    #[ORM\ManyToMany(targetEntity: Basket::class, inversedBy: 'copies')]
    private Collection $baskets;

    /**
     * @var Collection<int, Loan>
     */
    #[ORM\ManyToMany(targetEntity: Loan::class, inversedBy: 'copies')]
    private Collection $loans;

    public function __construct()
    {
        $this->baskets = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?CopyState
    {
        return $this->state;
    }

    public function setState(CopyState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPhysicalCondition(): ?CopyPhysicalCondition
    {
        return $this->physicalCondition;
    }

    public function setPhysicalCondition(CopyPhysicalCondition $physicalCondition): static
    {
        $this->physicalCondition = $physicalCondition;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }

    /**
     * @return Collection<int, Basket>
     */
    public function getBaskets(): Collection
    {
        return $this->baskets;
    }

    public function addBasket(Basket $basket): static
    {
        if (!$this->baskets->contains($basket)) {
            $this->baskets->add($basket);
        }

        return $this;
    }

    public function removeBasket(Basket $basket): static
    {
        $this->baskets->removeElement($basket);

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loan $loan): static
    {
        if (!$this->loans->contains($loan)) {
            $this->loans->add($loan);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): static
    {
        $this->loans->removeElement($loan);

        return $this;
    }
}
