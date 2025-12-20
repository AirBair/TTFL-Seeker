<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FantasyPickRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['fantasyPick:read']],
    denormalizationContext: ['groups' => ['fantasyPick:write']]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'id', 'season', 'isPlayoffs', 'pickedAt', 'fantasy.username', 'nbaPlayer.fullName', 'isNoPick', 'fantasyPoints', 'updatedAt',
])]
#[ApiFilter(SearchFilter::class, properties: [
    'season' => 'exact',
    'fantasyUser' => 'exact',
    'fantasyUser.fantasyTeam' => 'exact',
    'fantasyUser.username' => 'partial',
    'nbaPlayer' => 'exact',
    'nbaPlayer.fullName' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'isPlayoffs', 'isNoPick',
])]
#[ApiFilter(DateFilter::class, properties: ['pickedAt'])]
#[ApiFilter(RangeFilter::class, properties: ['fantasyPoints'])]
#[ORM\Entity(repositoryClass: FantasyPickRepository::class)]
class FantasyPick implements \Stringable
{
    #[Groups(['fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['fantasyPick:read'])]
    #[ORM\Column(type: 'integer')]
    private int $season;

    #[Groups(['fantasyPick:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isPlayoffs;

    #[Groups(['fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $pickedAt = null;

    #[Groups(['fantasyPick:read'])]
    #[ORM\ManyToOne(targetEntity: FantasyUser::class, inversedBy: 'fantasyPicks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FantasyUser $fantasyUser = null;

    #[Groups(['fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\ManyToOne(targetEntity: NbaPlayer::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?NbaPlayer $nbaPlayer = null;

    #[Groups(['fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isNoPick = false;

    #[Groups(['fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyPoints = null;

    #[Groups(['fantasyPick:read'])]
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->season = is_numeric($_ENV['NBA_YEAR']) ? (int) $_ENV['NBA_YEAR'] : throw new \InvalidArgumentException('NBA_YEAR must be a number');
        $this->isPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->fantasyUser.' - '.$this->pickedAt?->format('d/m/Y').' - '.$this->nbaPlayer.' - '.$this->fantasyPoints.'pts';
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

    public function getIsNoPick(): bool
    {
        return $this->isNoPick;
    }

    public function setIsNoPick(bool $isNoPick): self
    {
        $this->isNoPick = $isNoPick;

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
