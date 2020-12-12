<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\NbaTeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"nbaTeam:read"}},
 *     denormalizationContext={"groups": {"nbaTeam:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=NbaTeamRepository::class)
 */
class NbaTeam
{
    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"nbaTeam:read", "nbaPlayer:read", "nbaGame:read", "nbaStatsLog:read"})
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="partial")
     *
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="partial")
     *
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="partial")
     *
     * @Groups({"nbaTeam:read", "nbaPlayer:read", "nbaGame:read", "nbaStatsLog:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tricode;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $conference;

    /**
     * @ApiFilter(OrderFilter::class)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     *
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $division;

    /**
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\OneToMany(targetEntity=NbaPlayer::class, mappedBy="nbaTeam")
     */
    private $nbaPlayers;

    /**
     * @ApiFilter(OrderFilter::class)
     *
     * @Groups({"nbaTeam:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->nbaPlayers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) ($this->getCity().' '.$this->getNickname());
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getTricode(): ?string
    {
        return $this->tricode;
    }

    public function setTricode(string $tricode): self
    {
        $this->tricode = $tricode;

        return $this;
    }

    public function getConference(): ?string
    {
        return $this->conference;
    }

    public function setConference(string $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    public function getDivision(): ?string
    {
        return $this->division;
    }

    public function setDivision(string $division): self
    {
        $this->division = $division;

        return $this;
    }

    /**
     * @return Collection|NbaPlayer[]
     */
    public function getNbaPlayers(): Collection
    {
        return $this->nbaPlayers;
    }

    public function addNbaPlayer(NbaPlayer $nbaPlayer): self
    {
        if (!$this->nbaPlayers->contains($nbaPlayer)) {
            $this->nbaPlayers[] = $nbaPlayer;
            $nbaPlayer->setNbaTeam($this);
        }

        return $this;
    }

    public function removeNbaPlayer(NbaPlayer $nbaPlayer): self
    {
        if ($this->nbaPlayers->contains($nbaPlayer)) {
            $this->nbaPlayers->removeElement($nbaPlayer);
            // set the owning side to null (unless already changed)
            if ($nbaPlayer->getNbaTeam() === $this) {
                $nbaPlayer->setNbaTeam(null);
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
