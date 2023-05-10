<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lastName = null;

    #[ORM\OneToOne(mappedBy: 'idMember', cascade: ['persist', 'remove'])]
    private ?Avatar $idAvatar = null;

    #[ORM\OneToOne(mappedBy: 'idMember', cascade: ['persist', 'remove'])]
    private ?LoginCredentials $idLoginCredentials = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIdAvatar(): ?Avatar
    {
        return $this->idAvatar;
    }

    public function setIdAvatar(Avatar $idAvatar): self
    {
        // set the owning side of the relation if necessary
        if ($idAvatar->getIdMember() !== $this) {
            $idAvatar->setIdMember($this);
        }

        $this->idAvatar = $idAvatar;

        return $this;
    }

    public function getIdLoginCredentials(): ?LoginCredentials
    {
        return $this->idLoginCredentials;
    }

    public function setIdLoginCredentials(LoginCredentials $idLoginCredentials): self
    {
        // set the owning side of the relation if necessary
        if ($idLoginCredentials->getIdMember() !== $this) {
            $idLoginCredentials->setIdMember($this);
        }

        $this->idLoginCredentials = $idLoginCredentials;

        return $this;
    }
}
