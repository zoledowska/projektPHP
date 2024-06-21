<?php

namespace App\Entity;

use App\Repository\PhotoFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoFileRepository::class)]
class PhotoFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'photoFile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?photos $photo = null;

    #[ORM\Column(length: 191)]
    private ?string $fileName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?photos
    {
        return $this->photo;
    }

    public function setPhoto(photos $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
