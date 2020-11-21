<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FantasyPickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass=FantasyPickRepository::class)
 */
class FantasyPick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @ORM\Column(type="date")
     */
    private $pickedAt;

    /**
     * @ORM\ManyToOne(targetEntity=FantasyUser::class, inversedBy="fantasyPicks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fantasyUser;

    /**
     * @ORM\ManyToOne(targetEntity=NbaPlayer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $nbaPlayer;

    /**
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __toString(): string
    {
        return (string) ($this->fantasyUser.' - '.$this->pickedAt->format('d/m/Y').' - '.$this->nbaPlayer.' - '.$this->fantasyPoints.'pts');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): int
    {
        return $this->season;
    }

    public function setSeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getPickedAt(): ?\DateTimeInterface
    {
        return $this->pickedAt;
    }

    public function setPickedAt(\DateTimeInterface $pickedAt): self
    {
        $this->pickedAt = $pickedAt;

        return $this;
    }

    public function getFantasyUser(): ?FantasyUser
    {
        return $this->fantasyUser;
    }

    public function setFantasyUser(?FantasyUser $fantasyUser): self
    {
        $this->fantasyUser = $fantasyUser;

        return $this;
    }

    public function getNbaPlayer(): ?NbaPlayer
    {
        return $this->nbaPlayer;
    }

    public function setNbaPlayer(?NbaPlayer $nbaPlayer): self
    {
        $this->nbaPlayer = $nbaPlayer;

        return $this;
    }

    public function getFantasyPoints(): ?int
    {
        return $this->fantasyPoints;
    }

    public function setFantasyPoints(int $fantasyPoints): self
    {
        $this->fantasyPoints = $fantasyPoints;

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
