<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FantasyUserRankingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"fantasyUserRanking:read"}},
 *     denormalizationContext={"groups": {"fantasyUserRanking:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=FantasyUserRankingRepository::class)
 */
class FantasyUserRanking
{
    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\ManyToOne(targetEntity=FantasyUser::class, inversedBy="fantasyUserRankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fantasyUser;

    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyPoints;

    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $fantasyRank;

    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="date")
     */
    private $rankingAt;

    /**
     * @Groups({"fantasyUserRanking:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __toString(): string
    {
        return (string) ($this->fantasyUser.' - '.$this->rankingAt->format('d/m/Y').' - '.$this->fantasyPoints.'pts - '.$this->fantasyRank.'th');
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
