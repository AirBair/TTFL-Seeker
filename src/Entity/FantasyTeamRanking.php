<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isPlayoffs;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\ManyToOne(targetEntity=FantasyTeam::class, inversedBy="fantasyTeamRankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fantasyTeam;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyRank;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $rankingAt;

    /**
     * @Groups({"fantasyTeamRanking:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->season = (int) $_ENV['NBA_YEAR'];
        $this->isPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    public function __toString(): string
    {
        return (string) ($this->fantasyTeam.' - '.$this->rankingAt->format('d/m/Y').' - '.$this->fantasyPoints.'pts - '.$this->fantasyRank.'th');
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
