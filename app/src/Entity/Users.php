<?php
/**
 * Users entity.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a User entity.
 *
 * This entity handles user information including email, roles, password, and nickname.
 */
#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * The unique identifier for the User entity.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * The email address of the user.
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Email cannot be blank.')]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    private ?string $email = null;

    /**
     * The roles assigned to the user.
     */
    #[ORM\Column(type: 'json')]
    #[Assert\NotNull(message: 'Roles cannot be null.')]
    #[Assert\All([
        new Assert\NotBlank(message: 'Role cannot be blank.'),
        new Assert\Type('string', message: 'Each role must be a string.'),
    ])]
    private array $roles = [];

    /**
     * The hashed password of the user.
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Password cannot be blank.')]
    #[Assert\Length(min: 8, minMessage: 'Password must be at least {{ limit }} characters long.')]
    private ?string $password = null;

    /**
     * The nickname of the user.
     */
    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Nickname cannot be blank.')]
    #[Assert\Length(
        min: 1,
        max: 50,
        minMessage: 'Nickname must be at least {{ limit }} characters long.',
        maxMessage: 'Nickname cannot be longer than {{ limit }} characters.'
    )]
    private ?string $nick = null;

    /**
     * Gets the ID of the User.
     *
     * @return int|null the ID of the User entity
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the email address of the user.
     *
     * @return string|null the email address of the user
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets the email address of the user.
     *
     * @param string $email the email address to set
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Gets the user identifier.
     *
     * @return string the user identifier (email)
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Gets the username (deprecated).
     *
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     *
     * @return string the username (email)
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Gets the roles assigned to the user.
     *
     * @return array the roles of the user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Ensures every user has at least the default ROLE_USER.
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Sets the roles assigned to the user.
     *
     * @param array $roles the roles to set
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Gets the hashed password of the user.
     *
     * @return string|null the hashed password of the user
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the hashed password of the user.
     *
     * @param string $password the hashed password to set
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Gets the salt used for hashing passwords (not needed when using modern hashing algorithms).
     *
     * @return string|null always returns null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Erases sensitive data from the user.
     */
    public function eraseCredentials(): void
    {
        // Clear sensitive data here if any
    }

    /**
     * Gets the nickname of the user.
     *
     * @return string|null the nickname of the user
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * Sets the nickname of the user.
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
}
