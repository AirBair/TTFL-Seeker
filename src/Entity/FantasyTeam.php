<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FantasyTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"fantasyTeam:read"}},
 *     denormalizationContext={"groups": {"fantasyTeam:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=FantasyTeamRepository::class)
 */
class FantasyTeam
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"fantasyTeam:read", "fantasyUser:read"})
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="partial")
     *
     * @Groups({"fantasyTeam:read", "fantasyUser:read"})
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=FantasyTeamRanking::class, mappedBy="fantasyTeam", orphanRemoval=true)
     */
    private $fantasyTeamRankings;

    /**
     * @ORM\OneToMany(targetEntity=FantasyUser::class, mappedBy="fantasyTeam")
     */
    private $fantasyUsers;

    public function __construct()
    {
        $this->fantasyTeamRankings = new ArrayCollection();
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

    /**
     * @return Collection|FantasyUser[]
     */
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
