<?php

namespace App\Entity;

use App\Enum\VideoFormat;
use App\Repository\VideoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video extends Document
{
    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $duration = null;

    #[Assert\NotBlank()]
    #[ORM\Column(enumType: VideoFormat::class, options: ['default' => VideoFormat::Undefined->value])]
    private ?VideoFormat $format = VideoFormat::Undefined;

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getFormat(): ?VideoFormat
    {
        return $this->format;
    }

    public function setFormat(VideoFormat $format): static
    {
        $this->format = $format;

        return $this;
    }
}
