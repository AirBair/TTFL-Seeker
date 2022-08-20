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
use App\Repository\FantasyUserRankingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['fantasyUserRanking:write']],
    normalizationContext: ['groups' => ['fantasyUserRanking:read']]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'id', 'season', 'isPlayoffs', 'fantasyUser.username', 'fantasyPoints', 'fantasyRank', 'rankingAt', 'updatedAt',
])]
#[ApiFilter(SearchFilter::class, properties: [
    'season' => 'exact',
    'fantasyUser' => 'exact',
    'fantasyUser.name' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'isPlayoffs',
])]
#[ApiFilter(RangeFilter::class, properties: [
    'fantasyPoints', 'fantasyRank',
])]
#[ApiFilter(DateFilter::class, properties: [
    'rankingAt',
])]
#[ORM\Entity(repositoryClass: FantasyUserRankingRepository::class)]
class FantasyUserRanking
{
    #[Groups(['fantasyUserRanking:read', 'fantasyUser:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['fantasyUserRanking:read'])]
    #[ORM\ManyToOne(targetEntity: FantasyUser::class, inversedBy: 'fantasyUserRankings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FantasyUser $fantasyUser = null;

    #[Groups(['fantasyUserRanking:read'])]
    #[ORM\Column(type: 'integer')]
    private int $season;

    #[Groups(['fantasyUserRanking:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isPlayoffs;

    #[Groups(['fantasyUserRanking:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyPoints = null;

    #[Groups(['fantasyUserRanking:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyRank = null;

    #[Groups(['fantasyUserRanking:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $rankingAt = null;

    #[Groups(['fantasyUserRanking:read'])]
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->season = (int) $_ENV['NBA_YEAR'];
        $this->isPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    public function __toString(): string
    {
        return $this->fantasyUser.' - '.$this->rankingAt?->format('d/m/Y').' - '.$this->fantasyPoints.'pts - '.$this->fantasyRank.'th';
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

    public function getIsPlayoffs(): bool
    {
        return $this->isPlayoffs;
    }

    public function setIsPlayoffs(bool $isPlayoffs): self
    {
        $this->isPlayoffs = $isPlayoffs;

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
