<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FantasyTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['fantasyTeam:write']],
    normalizationContext: ['groups' => ['fantasyTeam:read']]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'id', 'name', 'isExoticTeam', 'fantasyPoints', 'fantasyRank',
])]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'isExoticTeam',
])]
#[ApiFilter(RangeFilter::class, properties: [
    'fantasyPoints', 'fantasyRank',
])]
#[ORM\Entity(repositoryClass: FantasyTeamRepository::class)]
class FantasyTeam
{
    #[Groups(['fantasyTeam:read', 'fantasyUser:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['fantasyTeam:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $name = null;

    #[Groups(['fantasyTeam:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isExoticTeam = false;

    #[Groups(['fantasyTeam:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyPoints = null;

    #[Groups(['fantasyTeam:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyRank = null;

    #[ORM\OneToMany(mappedBy: 'fantasyTeam', targetEntity: FantasyTeamRanking::class, orphanRemoval: true)]
    #[ORM\OrderBy(value: ['rankingAt' => 'ASC'])]
    private Collection $fantasyTeamRankings;

    #[ORM\OneToMany(mappedBy: 'fantasyTeam', targetEntity: FantasyUser::class)]
    private Collection $fantasyUsers;

    public function __construct()
    {
        $this->fantasyTeamRankings = new ArrayCollection();
        $this->fantasyUsers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->name;
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

    public function getIsExoticTeam(): bool
    {
        return $this->isExoticTeam;
    }

    public function setIsExoticTeam(bool $isExoticTeam): self
    {
        $this->isExoticTeam = $isExoticTeam;

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

    public function getFantasyUsers(): Collection
    {
        return $this->fantasyUsers;
    }

    public function addFantasyUser(FantasyUser $fantasyUser): self
    {
        if (!$this->fantasyUsers->contains($fantasyUser)) {
            $this->fantasyUsers[] = $fantasyUser;
            $fantasyUser->setFantasyTeam($this);
        }

        return $this;
    }

    public function removeFantasyUser(FantasyUser $fantasyUser): self
    {
        if ($this->fantasyUsers->removeElement($fantasyUser)) {
            // set the owning side to null (unless already changed)
            if ($fantasyUser->getFantasyTeam() === $this) {
                $fantasyUser->setFantasyTeam(null);
            }
        }

        return $this;
    }
}
