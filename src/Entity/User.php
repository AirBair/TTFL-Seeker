<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pick", mappedBy="user", orphanRemoval=true)
     */
    private $picks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RankingEvolution", mappedBy="user", orphanRemoval=true)
     */
    private $rankingEvolutions;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeredAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastLoginAt;

    public function __construct()
    {
        $this->picks = new ArrayCollection();
        $this->rankingEvolutions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Pick[]
     */
    public function getPicks(): Collection
    {
        return $this->picks;
    }

    public function addPick(Pick $pick): self
    {
        if (!$this->picks->contains($pick)) {
            $this->picks[] = $pick;
            $pick->setUser($this);
        }

        return $this;
    }

    public function removePick(Pick $pick): self
    {
        if ($this->picks->contains($pick)) {
            $this->picks->removeElement($pick);
            if ($pick->getUser() === $this) {
                $pick->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RankingEvolution[]
     */
    public function getRankingEvolutions(): Collection
    {
        return $this->rankingEvolutions;
    }

    public function addRankingEvolution(RankingEvolution $rankingEvolution): self
    {
        if (!$this->rankingEvolutions->contains($rankingEvolution)) {
            $this->rankingEvolutions[] = $rankingEvolution;
            $rankingEvolution->setUser($this);
        }

        return $this;
    }

    public function removeRankingEvolution(RankingEvolution $rankingEvolution): self
    {
        if ($this->rankingEvolutions->contains($rankingEvolution)) {
            $this->rankingEvolutions->removeElement($rankingEvolution);
            if ($rankingEvolution->getUser() === $this) {
                $rankingEvolution->setUser(null);
            }
        }

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function getSalt(): void
    {
        // Not needed when using the "bcrypt" algorithm.
    }

    public function eraseCredentials(): void
    {
        // If any sensitive data on the user is stored temporary, clear it here.
    }
}
