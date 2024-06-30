<?php
/**
 * Photos entity.
 */

namespace App\Entity;

use App\Repository\PhotosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a Photo entity.
 *
 * This entity manages photos, including their metadata, associations, and related operations.
 */
#[ORM\Entity(repositoryClass: PhotosRepository::class)]
class Photos
{
    /**
     * The unique identifier for the Photo entity.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The title of the photo.
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title cannot be blank.')]
    #[Assert\Type('string')]
    #[Assert\Length(
        min: 1,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters long.',
        maxMessage: 'Title cannot be longer than {{ limit }} characters.'
    )]
    private ?string $title = null;

    /**
     * The description of the photo.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Description cannot be blank.')]
    #[Assert\Type('string')]
    private ?string $description = null;

    /**
     * The date when the photo was uploaded.
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    #[Assert\NotNull(message: 'Upload date cannot be null.')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $uploadDate = null;

    /**
     * The album to which the photo belongs.
     */
    #[ORM\ManyToOne(targetEntity: Albums::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Album cannot be null.')]
    private ?Albums $album = null;

    /**
     * The author who uploaded the photo.
     */
    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'Author cannot be null.')]
    private ?Users $author = null;

    /**
     * The file name of the photo.
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'File name cannot be longer than {{ limit }} characters.'
    )]
    private ?string $photoFileName = null;


    /**
     * Initializes a new instance of the Photo entity.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Gets the ID of the Photo.
     *
     * @return int|null the ID of the Photo entity
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the title of the photo.
     *
     * @return string|null the title of the photo
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the photo.
     *
     * @param string $title the title to set
     *
     * @return static the current instance for chaining
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the description of the photo.
     *
     * @return string|null the description of the photo
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the photo.
     *
     * @param string $description the description to set
     *
     * @return static the current instance for chaining
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the upload date of the photo.
     *
     * @return \DateTimeImmutable|null the upload date of the photo
     */
    public function getUploadDate(): ?\DateTimeImmutable
    {
        return $this->uploadDate;
    }

    /**
     * Sets the upload date of the photo.
     *
     * @param \DateTimeImmutable $uploadDate the upload date to set
     *
     * @return static the current instance for chaining
     */
    public function setUploadDate(\DateTimeImmutable $uploadDate): static
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    /**
     * Gets the album to which the photo belongs.
     *
     * @return Albums|null the album of the photo
     */
    public function getAlbum(): ?Albums
    {
        return $this->album;
    }

    /**
     * Sets the album to which the photo belongs.
     *
     * @param Albums|null $album the album to set
     *
     * @return static the current instance for chaining
     */
    public function setAlbum(?Albums $album): static
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Gets the author who uploaded the photo.
     *
     * @return Users|null the author of the photo
     */
    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    /**
     * Sets the author who uploaded the photo.
     *
     * @param Users|null $author the author to set
     *
     * @return static the current instance for chaining
     */
    public function setAuthor(?Users $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Gets the file name of the photo.
     *
     * @return string|null the file name of the photo
     */
    public function getPhotoFileName(): ?string
    {
        return $this->photoFileName;
    }

    /**
     * Sets the file name of the photo.
     *
     * @param string|null $photoFileName the file name to set
     *
     * @return static the current instance for chaining
     */
    public function setPhotoFileName(?string $photoFileName): static
    {
        $this->photoFileName = $photoFileName;

        return $this;
    }

    /**
     * Gets the collection of comments associated with the photo.
     *
     * @return Collection<int, Comments> the comments collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Adds a comment to the photo.
     *
     * @param Comments $comment the comment to add
     *
     * @return static the current instance for chaining
     */
    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPhoto($this);
        }

        return $this;
    }

    /**
     * Removes a comment from the photo.
     *
     * @param Comments $comment the comment to remove
     *
     * @return static the current instance for chaining
     */
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
