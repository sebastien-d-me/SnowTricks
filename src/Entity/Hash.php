<?php

namespace App\Entity;

use App\Repository\HashRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HashRepository::class)]
class Hash
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $hash = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?LoginCredentials $idLoginCredentials = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
