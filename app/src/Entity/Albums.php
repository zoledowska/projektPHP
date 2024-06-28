<?php
/**
 * Albums entity.
 */

namespace App\Entity;

use App\Repository\AlbumsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents an Album entity.
 *
 * This entity is used to manage photo albums and related operations.
 */
#[ORM\Entity(repositoryClass: AlbumsRepository::class)]
#[ORM\Table(name: 'albums')]
#[UniqueEntity(fields: ['title'], message: 'This title is already in use.')]
class Albums
{
    /**
     * The unique identifier for the Album entity.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * The title of the album.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank(message: 'Title cannot be blank.')]
    #[Assert\Length(
        min: 1,
        max: 64,
        minMessage: 'Title must be at least {{ limit }} characters long.',
        maxMessage: 'Title cannot be longer than {{ limit }} characters.'
    )]
    private ?string $title = null;

    /**
     * The description of the album.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Description cannot be blank.')]
    #[Assert\Type('string')]
    private ?string $description = null;

    /**
     * The date when the album was created.
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotNull(message: 'Creation date cannot be null.')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Initializes a new instance of the Album entity.
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Gets the ID of the Album.
     *
     * @return int|null the ID of the Album entity
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the title of the album.
     *
     * @return string|null the title of the album
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the album.
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
     * Gets the description of the album.
     *
     * @return string|null the description of the album
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the album.
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
     * Gets the creation date of the album.
     *
     * @return \DateTimeImmutable|null the creation date of the album
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Sets the creation date of the album.
     *
     * @param \DateTimeImmutable $createdAt the creation date to set
     *
     * @return static the current instance for chaining
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
