<?php
/**
 * PhotoFile entity.
 */

namespace App\Entity;

use App\Repository\PhotoFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a PhotoFile entity.
 *
 * This entity is used to handle photo file related operations
 * and persistence.
 */
#[ORM\Entity(repositoryClass: PhotoFileRepository::class)]
class PhotoFile
{
    /**
     * @var int|null the unique identifier for the PhotoFile entity
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var photos|null the associated photo entity
     */
    #[ORM\OneToOne(inversedBy: 'photoFile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?photos $photo = null;

    /**
     * @var string|null the file name of the photo
     */
    #[ORM\Column(length: 191)]
    private ?string $fileName = null;

    /**
     * Gets the ID of the PhotoFile.
     *
     * @return int|null the ID of the PhotoFile entity
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the associated photo entity.
     *
     * @return photos|null the associated photo entity
     */
    public function getPhoto(): ?photos
    {
        return $this->photo;
    }

    /**
     * Sets the associated photo entity.
     *
     * @param photos $photo the associated photo entity to set
     *
     * @return static the current instance for chaining
     */
    public function setPhoto(photos $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Gets the file name of the photo.
     *
     * @return string|null the file name of the photo
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Sets the file name of the photo.
     *
     * @param string $fileName the file name to set
     *
     * @return static the current instance for chaining
     */
    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
