<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass="App\Repository\NbaTeamRepository")
 */
class NbaTeam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tricode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $conference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $division;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NbaPlayer", mappedBy="nbaTeam")
     */
    private $nbaPlayers;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->nbaPlayers = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getTricode(): ?string
    {
        return $this->tricode;
    }

    public function setTricode(string $tricode): self
    {
        $this->tricode = $tricode;

        return $this;
    }

    public function getConference(): ?string
    {
        return $this->conference;
    }

    public function setConference(string $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(string $division): self
    {
        $this->division = $division;

        return $this;
    }

    /**
     * @return Collection|NbaPlayer[]
     */
    public function getNbaPlayers(): Collection
    {
        return $this->nbaPlayers;
    }

    public function addNbaPlayer(NbaPlayer $nbaPlayer): self
    {
        if (!$this->nbaPlayers->contains($nbaPlayer)) {
            $this->nbaPlayers[] = $nbaPlayer;
            $nbaPlayer->setNbaTeam($this);
        }

        return $this;
    }

    public function removeNbaPlayer(NbaPlayer $nbaPlayer): self
    {
        if ($this->nbaPlayers->contains($nbaPlayer)) {
            $this->nbaPlayers->removeElement($nbaPlayer);
            // set the owning side to null (unless already changed)
            if ($nbaPlayer->getNbaTeam() === $this) {
                $nbaPlayer->setNbaTeam(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
