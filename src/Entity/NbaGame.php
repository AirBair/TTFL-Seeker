<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NbaGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"nbaGame:read"}},
 *     denormalizationContext={"groups": {"nbaGame:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=NbaGameRepository::class)
 */
class NbaGame
{
    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isPlayoffs;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="date")
     */
    private $gameDay;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $scheduledAt;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaTeam::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $localNbaTeam;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaTeam::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $visitorNbaTeam;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $localScore;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visitorScore;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\OneToMany(targetEntity=NbaStatsLog::class, mappedBy="nbaGame", orphanRemoval=true)
     */
    private $nbaStatsLogs;

    /**
     * @Groups({"nbaGame:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->season = (int) $_ENV['NBA_YEAR'];
        $this->isPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
        $this->nbaStatsLogs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) ($this->gameDay->format('d/m/Y').' - '.$this->localNbaTeam->getTricode().' vs '.$this->visitorNbaTeam->getTricode());
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getGameDay(): ?\DateTimeInterface
    {
        return $this->gameDay;
    }

    public function setGameDay(\DateTimeInterface $gameDay): self
    {
        $this->gameDay = $gameDay;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTimeInterface $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    public function getLocalNbaTeam(): ?NbaTeam
    {
        return $this->localNbaTeam;
    }

    public function setLocalNbaTeam(?NbaTeam $localNbaTeam): self
    {
        $this->localNbaTeam = $localNbaTeam;

        return $this;
    }

    public function getVisitorNbaTeam(): ?NbaTeam
    {
        return $this->visitorNbaTeam;
    }

    public function setVisitorNbaTeam(?NbaTeam $visitorNbaTeam): self
    {
        $this->visitorNbaTeam = $visitorNbaTeam;

        return $this;
    }

    public function getLocalScore(): ?int
    {
        return $this->localScore;
    }

    public function setLocalScore(?int $localScore): self
    {
        $this->localScore = $localScore;

        return $this;
    }

    public function getVisitorScore(): ?int
    {
        return $this->visitorScore;
    }

    public function setVisitorScore(?int $visitorScore): self
    {
        $this->visitorScore = $visitorScore;

        return $this;
    }

    /**
     * @return Collection|NbaStatsLog[]
     */
    public function getNbaStatsLogs(): Collection
    {
        return $this->nbaStatsLogs;
    }

    public function addNbaStatsLog(NbaStatsLog $nbaStatsLog): self
    {
        if (!$this->nbaStatsLogs->contains($nbaStatsLog)) {
            $this->nbaStatsLogs[] = $nbaStatsLog;
            $nbaStatsLog->setNbaGame($this);
        }

        return $this;
    }

    public function removeNbaStatsLog(NbaStatsLog $nbaStatsLog): self
    {
        if ($this->nbaStatsLogs->contains($nbaStatsLog)) {
            $this->nbaStatsLogs->removeElement($nbaStatsLog);
            if ($nbaStatsLog->getNbaGame() === $this) {
                $nbaStatsLog->setNbaGame(null);
            }
        }

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
