<?php

namespace App\Entity;

use App\Enum\PeriodicalFrequency;
use App\Repository\PeriodicalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeriodicalRepository::class)]
class Periodical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: PeriodicalFrequency::class, options: ['default' => PeriodicalFrequency::Undefined->value])]
    private ?PeriodicalFrequency $frequency = PeriodicalFrequency::Undefined;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrequency(): ?PeriodicalFrequency
    {
        return $this->frequency;
    }

    public function setFrequency(PeriodicalFrequency $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }
}
