<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\NbaStatsLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"nbaStatsLog:read"}},
 *     denormalizationContext={"groups": {"nbaStatsLog:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=NbaStatsLogRepository::class)
 */
class NbaStatsLog
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ApiFilter(OrderFilter::class, properties={"nbaGame.gameDay"})
     * @ApiFilter(DateFilter::class, properties={"nbaGame.gameDay"})
     * @ApiFilter(SearchFilter::class, properties={"nbaGame.season": "exact"})
     * @ApiFilter(BooleanFilter::class, properties={"nbaGame.isPlayoffs": "exact"})
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaGame::class, inversedBy="nbaStatsLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?NbaGame $nbaGame = null;

    /**
     * @ApiFilter(OrderFilter::class, properties={
     *     "nbaPlayer.lastName",
     *     "nbaPlayer.fullName",
     *     "nbaPlayer.averageFantasyPoints"
     * })
     * @ApiFilter(SearchFilter::class, properties={
     *     "nbaPlayer": "exact",
     *     "nbaPlayer.fullName": "partial",
     *     "nbaPlayer.nbaTeam": "exact",
     *     "nbaPlayer.nbaTeam.fullName": "partial"
     * })
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaPlayer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?NbaPlayer $nbaPlayer = null;

    /**
     * @ApiFilter(OrderFilter::class, properties={"nbaPlayer.fullName"})
     * @ApiFilter(SearchFilter::class, properties={
     *     "nbaTeam": "exact",
     *     "nbaTeam.fullName": "partial"
     * })
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaTeam::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?NbaTeam $nbaTeam = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $points = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $assists = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $rebounds = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $steals = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $blocks = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $turnovers = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $fieldGoals = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $fieldGoalsAttempts = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $threePointsFieldGoals = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $threePointsFieldGoalsAttempts = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $freeThrows = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $freeThrowsAttempts = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $minutesPlayed = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(BooleanFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private bool $hasWon = false;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $fantasyPoints = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(BooleanFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private bool $isBestPick = false;

    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"nbaStatsLog:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $updatedAt = null;

    public function __toString(): string
    {
        return $this->nbaPlayer.' - '.$this->fantasyPoints.'pts';
    }

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

    public function getNbaTeam(): ?NbaTeam
    {
        return $this->nbaTeam;
    }

    public function setNbaTeam(?NbaTeam $nbaTeam): self
    {
        $this->nbaTeam = $nbaTeam;

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
