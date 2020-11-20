<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FantasyTeamRankingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass=FantasyTeamRankingRepository::class)
 */
class FantasyTeamRanking
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
     * @ORM\ManyToOne(targetEntity=FantasyTeam::class, inversedBy="fantasyTeamRankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fantasyTeam;

    /**
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @ORM\Column(type="integer")
     */
    private $rank;

    /**
     * @ORM\Column(type="datetime")
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

    public function getSeason(): ?int
    {
        return $this->season;
    }

    public function setSeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getFantasyTeam(): ?FantasyTeam
    {
        return $this->fantasyTeam;
    }

    public function setFantasyTeam(?FantasyTeam $fantasyTeam): self
    {
        $this->fantasyTeam = $fantasyTeam;

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

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

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
