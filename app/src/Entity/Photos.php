<?php

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title cannot be blank.')]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 255, minMessage: 'Title must be at least {{ limit }} characters long.', maxMessage: 'Title cannot be longer than {{ limit }} characters.')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Description cannot be blank.')]
    #[Assert\Type('string')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    #[Assert\NotNull(message: 'Upload date cannot be null.')]
    #[Assert\Type(\DateTimeInterface::class)]
    private ?\DateTimeInterface $upload_date = null;

    #[ORM\ManyToOne(targetEntity: Albums::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Album cannot be null.')]
    private ?Albums $album = null;

    #[ORM\ManyToOne(targetEntity: Users::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Author cannot be null.')]
    private ?Users $author = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Assert\Valid]
    private ?PhotoFile $photoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255, maxMessage: 'File name cannot be longer than {{ limit }} characters.')]
    private ?string $photo_file_name = null;

    #[ORM\OneToMany(mappedBy: 'photo', targetEntity: Comments::class, fetch: 'EAGER', orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

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

    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    public function setAuthor(?Users $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPhotoFile(): ?PhotoFile
    {
        return $this->photoFile;
    }

    public function setPhotoFile(?PhotoFile $photoFile): static
    {
        $this->photoFile = $photoFile;

        return $this;
    }

    public function getPhotoFileName(): ?string
    {
        return $this->photo_file_name;
    }

    public function setPhotoFileName(?string $photo_file_name): static
    {
        $this->photo_file_name = $photo_file_name;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPhoto($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPhoto() === $this) {
                $comment->setPhoto(null);
            }
        }

        return $this;
    }
}
