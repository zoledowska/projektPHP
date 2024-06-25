<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 1, max: 45)]
    #[ORM\Column(length: 45)]
    private ?string $nick = null;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Assert\NotNull]
    #[Assert\Type(\DateTimeInterface::class)]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    public ?\DateTimeInterface $post_date = null;

    #[Assert\NotNull]
    #[ORM\ManyToOne(targetEntity: Photos::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    public ?Photos $photo = null;

    // Getter and Setter methods ...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNick(): ?string
    {
        return $this->nick;
    }

    public function setNick(string $nick): static
    {
        $this->nick = $nick;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->post_date;
    }

    public function setPostDate(\DateTimeInterface $post_date): static
    {
        $this->post_date = $post_date;

        return $this;
    }

    public function getPhoto(): ?Photos
    {
        return $this->photo;
    }

    public function setPhoto(?Photos $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
