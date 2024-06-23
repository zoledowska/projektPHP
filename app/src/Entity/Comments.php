<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use App\Entity\Photos;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 45)]
    private ?string $nick = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    public ?\DateTimeInterface $post_date = null;

    #[ORM\ManyToOne(targetEntity: Photos::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    public ?photos $photo = null;

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

    public function getPhoto(): ?photos
    {
        return $this->photo;
    }

    public function setPhoto(?photos $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
}
