<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FantasyUserRankingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass=FantasyUserRankingRepository::class)
 */
class FantasyUserRanking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=FantasyUser::class, inversedBy="fantasyUserRankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fantasyUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @ORM\Column(type="integer")
     */
    private $ranking;

    /**
     * @ORM\Column(type="date")
     */
    private $rankingAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

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

    public function getFantasyUser(): ?FantasyUser
    {
        return $this->fantasyUser;
    }

    public function setFantasyUser(?FantasyUser $fantasyUser): self
    {
        $this->fantasyUser = $fantasyUser;

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

    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(int $ranking): self
    {
        $this->ranking = $ranking;

        return $this;
    }

    public function getRankingAt(): ?\DateTimeInterface
    {
        return $this->rankingAt;
    }

    public function setRankingAt(\DateTimeInterface $rankingAt): self
    {
        $this->rankingAt = $rankingAt;

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
