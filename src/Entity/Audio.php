<?php

namespace App\Entity;

use App\Enum\AudioFormat;
use App\Repository\AudioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AudioRepository::class)]
class Audio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $duration = null;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: AudioFormat::class, options: ['default' => AudioFormat::Undefined->value])]
    private ?AudioFormat $format = AudioFormat::Undefined;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getFormat(): ?AudioFormat
    {
        return $this->format;
    }

    public function setFormat(AudioFormat $format): static
    {
        $this->format = $format;

        return $this;
    }
}
