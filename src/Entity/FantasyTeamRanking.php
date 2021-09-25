<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FantasyTeamRankingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"fantasyTeamRanking:read"}},
 *     denormalizationContext={"groups": {"fantasyTeamRanking:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=FantasyTeamRankingRepository::class)
 */
class FantasyTeamRanking
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyTeamRanking:read", "fantasyTeam:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private int $season;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private bool $isPlayoffs;

    /**
     * @ApiFilter(OrderFilter::class, properties={"fantasyTeam.name"})
     * @ApiFilter(SearchFilter::class, properties={
     *     "fantasyTeam": "exact",
     *     "fantasyTeam.name": "partial"
     * })
     *
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\ManyToOne(targetEntity=FantasyTeam::class, inversedBy="fantasyTeamRankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FantasyTeam $fantasyTeam = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyTeamRanking:read", "fantasyTeam:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $fantasyPoints = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyTeamRanking:read", "fantasyTeam:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $fantasyRank = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(DateFilter::class)
     *
     * @Groups({"fantasyTeamRanking:read", "fantasyTeam:read"})
     *
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $rankingAt = null;

    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->season = (int) $_ENV['NBA_YEAR'];
        $this->isPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    public function __toString(): string
    {
        return $this->fantasyTeam.' - '.$this->rankingAt->format('d/m/Y').' - '.$this->fantasyPoints.'pts - '.$this->fantasyRank.'th';
    }

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

    public function getIsPlayoffs(): bool
    {
        return $this->isPlayoffs;
    }

    public function setIsPlayoffs(bool $isPlayoffs): self
    {
        $this->isPlayoffs = $isPlayoffs;

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

    public function getFantasyRank(): ?int
    {
        return $this->fantasyRank;
    }

    public function setFantasyRank(int $fantasyRank): self
    {
        $this->fantasyRank = $fantasyRank;

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
