<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NbaGameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass=NbaGameRepository::class)
 */
class NbaGame
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $season;

    /**
     * @ORM\Column(type="date")
     */
    private $gameDay;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $scheduledAt;

    /**
     * @ORM\ManyToOne(targetEntity=NbaTeam::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $localNbaTeam;

    /**
     * @ORM\ManyToOne(targetEntity=NbaTeam::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $visitorNbaTeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $localScore;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $visitorScore;

    /**
     * @ORM\OneToMany(targetEntity=NbaStatsLog::class, mappedBy="nbaGame", orphanRemoval=true)
     */
    private $nbaStatsLogs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->nbaStatsLogs = new ArrayCollection();
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
