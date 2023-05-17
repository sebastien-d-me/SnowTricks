<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\OneToOne(inversedBy: 'idAvatar', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?LoginCredentials $idLoginCredentials = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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
}
