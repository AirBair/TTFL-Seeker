<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FantasyTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass=FantasyTeamRepository::class)
 */
class FantasyTeam
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=FantasyTeamRanking::class, mappedBy="fantasyTeam", orphanRemoval=true)
     */
    private $fantasyTeamRankings;

    public function __construct()
    {
        $this->fantasyTeamRankings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|FantasyTeamRanking[]
     */
    public function getFantasyTeamRankings(): Collection
    {
        return $this->fantasyTeamRankings;
    }

    public function addFantasyTeamRanking(FantasyTeamRanking $fantasyTeamRanking): self
    {
        if (!$this->fantasyTeamRankings->contains($fantasyTeamRanking)) {
            $this->fantasyTeamRankings[] = $fantasyTeamRanking;
            $fantasyTeamRanking->setFantasyTeam($this);
        }

        return $this;
    }

    public function removeFantasyTeamRanking(FantasyTeamRanking $fantasyTeamRanking): self
    {
        if ($this->fantasyTeamRankings->removeElement($fantasyTeamRanking)) {
            // set the owning side to null (unless already changed)
            if ($fantasyTeamRanking->getFantasyTeam() === $this) {
                $fantasyTeamRanking->setFantasyTeam(null);
            }
        }

        return $this;
    }
}
