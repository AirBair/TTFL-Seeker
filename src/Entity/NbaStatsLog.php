<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass="App\Repository\NbaStatsLogRepository")
 */
class NbaStatsLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NbaGame", inversedBy="nbaStatsLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nbaGame;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\NbaPlayer", inversedBy="nbaStatsLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nbaPlayer;

    /**
     * @ORM\Column(type="integer")
     */
    private $points;

    /**
     * @ORM\Column(type="integer")
     */
    private $assists;

    /**
     * @ORM\Column(type="integer")
     */
    private $rebounds;

    /**
     * @ORM\Column(type="integer")
     */
    private $steals;

    /**
     * @ORM\Column(type="integer")
     */
    private $blocks;

    /**
     * @ORM\Column(type="integer")
     */
    private $turnovers;

    /**
     * @ORM\Column(type="integer")
     */
    private $fieldGoals;

    /**
     * @ORM\Column(type="integer")
     */
    private $fieldGoalsAttempts;

    /**
     * @ORM\Column(type="integer")
     */
    private $threePointsFieldGoals;

    /**
     * @ORM\Column(type="integer")
     */
    private $threePointsFieldGoalsAttempts;

    /**
     * @ORM\Column(type="integer")
     */
    private $freeThrows;

    /**
     * @ORM\Column(type="integer")
     */
    private $freeThrowsAttempts;

    /**
     * @ORM\Column(type="integer")
     */
    private $minutesPlayed;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasWon;

    /**
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBestPick;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbaGame(): ?NbaGame
    {
        return $this->nbaGame;
    }

    public function setNbaGame(?NbaGame $nbaGame): self
    {
        $this->nbaGame = $nbaGame;

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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getAssists(): ?int
    {
        return $this->assists;
    }

    public function setAssists(int $assists): self
    {
        $this->assists = $assists;

        return $this;
    }

    public function getRebounds(): ?int
    {
        return $this->rebounds;
    }

    public function setRebounds(int $rebounds): self
    {
        $this->rebounds = $rebounds;

        return $this;
    }

    public function getSteals(): ?int
    {
        return $this->steals;
    }

    public function setSteals(int $steals): self
    {
        $this->steals = $steals;

        return $this;
    }

    public function getBlocks(): ?int
    {
        return $this->blocks;
    }

    public function setBlocks(int $blocks): self
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getTurnovers(): ?int
    {
        return $this->turnovers;
    }

    public function setTurnovers(int $turnovers): self
    {
        $this->turnovers = $turnovers;

        return $this;
    }

    public function getFieldGoals(): ?int
    {
        return $this->fieldGoals;
    }

    public function setFieldGoals(int $fieldGoals): self
    {
        $this->fieldGoals = $fieldGoals;

        return $this;
    }

    public function getFieldGoalsAttempts(): ?int
    {
        return $this->fieldGoalsAttempts;
    }

    public function setFieldGoalsAttempts(int $fieldGoalsAttempts): self
    {
        $this->fieldGoalsAttempts = $fieldGoalsAttempts;

        return $this;
    }

    public function getThreePointsFieldGoals(): ?int
    {
        return $this->threePointsFieldGoals;
    }

    public function setThreePointsFieldGoals(int $threePointsFieldGoals): self
    {
        $this->threePointsFieldGoals = $threePointsFieldGoals;

        return $this;
    }

    public function getThreePointsFieldGoalsAttempts(): ?int
    {
        return $this->threePointsFieldGoalsAttempts;
    }

    public function setThreePointsFieldGoalsAttempts(int $threePointsFieldGoalsAttempts): self
    {
        $this->threePointsFieldGoalsAttempts = $threePointsFieldGoalsAttempts;

        return $this;
    }

    public function getFreeThrows(): ?int
    {
        return $this->freeThrows;
    }

    public function setFreeThrows(int $freeThrows): self
    {
        $this->freeThrows = $freeThrows;

        return $this;
    }

    public function getFreeThrowsAttempts(): ?int
    {
        return $this->freeThrowsAttempts;
    }

    public function setFreeThrowsAttempts(int $freeThrowsAttempts): self
    {
        $this->freeThrowsAttempts = $freeThrowsAttempts;

        return $this;
    }

    public function getMinutesPlayed(): ?int
    {
        return $this->minutesPlayed;
    }

    public function setMinutesPlayed(int $minutesPlayed): self
    {
        $this->minutesPlayed = $minutesPlayed;

        return $this;
    }

    public function getHasWon(): ?bool
    {
        return $this->hasWon;
    }

    public function setHasWon(bool $hasWon): self
    {
        $this->hasWon = $hasWon;

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

    public function getIsBestPick(): ?bool
    {
        return $this->isBestPick;
    }

    public function setIsBestPick(bool $isBestPick): self
    {
        $this->isBestPick = $isBestPick;

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
