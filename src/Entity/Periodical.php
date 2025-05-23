<?php

namespace App\Entity;

use App\Enum\PeriodicalFrequency;
use App\Repository\PeriodicalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeriodicalRepository::class)]
class Periodical extends Document
{
    #[Assert\NotBlank()]
    #[ORM\Column(enumType: PeriodicalFrequency::class, options: ['default' => PeriodicalFrequency::Undefined->value])]
    private ?PeriodicalFrequency $frequency = PeriodicalFrequency::Undefined;

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
