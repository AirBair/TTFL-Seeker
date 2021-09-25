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
use App\Repository\FantasyPickRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"fantasyPick:read"}},
 *     denormalizationContext={"groups": {"fantasyPick:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=FantasyPickRepository::class)
 */
class FantasyPick
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyPick:read", "fantasyUser:read"})
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
     * @Groups({"fantasyPick:read"})
     *
     * @ORM\Column(type="integer")
     */
    private int $season;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(BooleanFilter::class)
     *
     * @Groups({"fantasyPick:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private bool $isPlayoffs;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(DateFilter::class)
     *
     * @Groups({"fantasyPick:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $pickedAt = null;

    /**
     * @ApiFilter(OrderFilter::class, properties={"fantasyUser.username"})
     * @ApiFilter(SearchFilter::class, properties={
     *     "fantasyUser": "exact",
     *     "fantasyUser.fantasyTeam": "exact",
     *     "fantasyUser.username": "partial",
     * })
     *
     * @Groups({"fantasyPick:read"})
     *
     * @ORM\ManyToOne(targetEntity=FantasyUser::class, inversedBy="fantasyPicks")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?FantasyUser $fantasyUser = null;

    /**
     * @ApiFilter(OrderFilter::class, properties={"nbaPlayer.fullName"})
     * @ApiFilter(SearchFilter::class, properties={
     *     "nbaPlayer": "exact",
     *     "nbaPlayer.fullName": "partial",
     * })
     *
     * @Groups({"fantasyPick:read", "fantasyUser:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaPlayer::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?NbaPlayer $nbaPlayer = null;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"fantasyPick:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private bool $isNoPick = false;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(RangeFilter::class)
     *
     * @Groups({"fantasyPick:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="integer")
     */
    private ?int $fantasyPoints = null;

    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyPick:read"})
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
        return $this->fantasyUser.' - '.$this->pickedAt->format('d/m/Y').' - '.$this->nbaPlayer.' - '.$this->fantasyPoints.'pts';
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
