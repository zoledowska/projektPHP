<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 */
#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $upload_date = null;

    #[ORM\ManyToOne(targetEntity: Albums::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Albums $album = null;

    /**
     * Author.
     *
     * @var Users|null
     */
    #[ORM\ManyToOne(targetEntity: Users::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $author;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
//    #[ORM\JoinColumn(nullable: false)]
    private ?PhotoFile $photoFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->upload_date;
    }
    public function setUploadDate(\DateTimeInterface $upload_date): static
    {
        $this->upload_date = $upload_date;

        return $this;
    }

    public function getAlbum(): ?Albums
    {
        return $this->album;
    }

    public function setAlbum(?Albums $album): static
    {
        $this->album = $album;

        return $this;
    }

    public function getAuthor(): ?users
    {
        return $this->author;
    }

    public function setAuthor(?users $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPhotoFile(): ?photoFile
    {
        return $this->photoFile;
    }

    public function setPhotoFile(photoFile $photoFile): static
    {
        $this->photoFile = $photoFile;

        return $this;
    }
}
