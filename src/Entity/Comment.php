<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?LoginCredentials $idLoginCredentials = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trick $idTrick = null;

    #[ORM\Column(length: 255)]
    private ?string $fullname = null;

    #[ORM\ManyToOne]
    private ?avatar $avatar = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIdLoginCredentials(): ?LoginCredentials
    {
        return $this->idLoginCredentials;
    }

    public function setIdLoginCredentials(LoginCredentials $idLoginCredentials): self
    {
        $this->idLoginCredentials = $idLoginCredentials;

        return $this;
    }

    public function getIdTrick(): ?Trick
    {
        return $this->idTrick;
    }

    public function setIdTrick(?Trick $idTrick): self
    {
        $this->idTrick = $idTrick;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getAvatar(): ?avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
