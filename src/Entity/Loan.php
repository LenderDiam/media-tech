<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $withdrawalAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $backAt = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    private ?User $user = null;

    /**
     * @var Collection<int, Copy>
     */
    #[ORM\ManyToMany(targetEntity: Copy::class, mappedBy: 'loans')]
    private Collection $copies;

    public function __construct()
    {
        $this->copies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getWithdrawalAt(): ?\DateTimeImmutable
    {
        return $this->withdrawalAt;
    }

    public function setWithdrawalAt(?\DateTimeImmutable $withdrawalAt): static
    {
        $this->withdrawalAt = $withdrawalAt;

        return $this;
    }

    public function getBackAt(): ?\DateTimeImmutable
    {
        return $this->backAt;
    }

    public function setBackAt(?\DateTimeImmutable $backAt): static
    {
        $this->backAt = $backAt;

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
            $copy->addLoan($this);
        }

        return $this;
    }

    public function removeCopy(Copy $copy): static
    {
        if ($this->copies->removeElement($copy)) {
            $copy->removeLoan($this);
        }

        return $this;
    }
}
