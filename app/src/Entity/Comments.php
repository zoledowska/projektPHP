<?php
/**
 * Comments entity.
 */

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a Comment entity.
 *
 * This entity is used to handle user comments and related operations.
 */
#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    /**
     * The unique identifier for the Comment entity.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The email address of the commenter.
     */
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * The nickname of the commenter.
     */
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 45)]
    #[ORM\Column(length: 45)]
    private ?string $nick = null;

    /**
     * The content of the comment.
     */
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * The date when the comment was posted.
     */
    #[Assert\NotNull]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $postDate = null;

    /**
     * The associated photo entity.
     */
    #[Assert\NotNull]
    #[ORM\ManyToOne]
    private ?Photos $photo = null;

    /**
     * Gets the ID of the Comment.
     *
     * @return int|null the ID of the Comment entity
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the email address of the commenter.
     *
     * @return string|null the email address of the commenter
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email address of the commenter.
     *
     * @param string $email the email address to set
     *
     * @return static the current instance for chaining
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets the nickname of the commenter.
     *
     * @return string|null the nickname of the commenter
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Sets the nickname of the commenter.
     *
     * @param string $nick the nickname to set
     *
     * @return static the current instance for chaining
     */
    public function setNick(string $nick): static
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Gets the content of the comment.
     *
     * @return string|null the content of the comment
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Sets the content of the comment.
     *
     * @param string $content the content to set
     *
     * @return static the current instance for chaining
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Gets the date when the comment was posted.
     *
     * @return \DateTimeImmutable|null the post date of the comment
     */
    public function getPostDate(): ?\DateTimeImmutable
    {
        return $this->postDate;
    }

    /**
     * Sets the date when the comment was posted.
     *
     * @param \DateTimeImmutable $postDate the post date to set
     *
     * @return static the current instance for chaining
     */
    public function setPostDate(\DateTimeImmutable $postDate): static
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Gets the associated photo entity.
     *
     * @return Photos|null the associated photo entity
     */
    public function getPhoto(): ?Photos
    {
        return $this->photo;
    }

    /**
     * Sets the associated photo entity.
     *
     * @param Photos|null $photo the photo entity to set
     *
     * @return static the current instance for chaining
     */
    public function setPhoto(?Photos $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
