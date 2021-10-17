<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\FantasyUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['fantasyUser:write']],
    normalizationContext: ['groups' => ['fantasyUser:read']]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'id', 'username', 'ttflId', 'fantasyTeam.name', 'isExoticUser', 'fantasyPoints', 'fantasyRank',
])]
#[ApiFilter(SearchFilter::class, properties: [
    'username' => 'partial',
    'ttflId' => 'exact',
    'fantasyTeam' => 'exact',
    'fantasyTeam.name' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'isExoticUser', 'isSynchronizationActive',
])]
#[ApiFilter(RangeFilter::class, properties: [
    'fantasyPoints', 'fantasyRank',
])]
#[ORM\Entity(repositoryClass: FantasyUserRepository::class)]
class FantasyUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['fantasyUser:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['fantasyUser:read'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $username = null;

    #[Groups(['fantasyUser:read'])]
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $ttflId = null;

    #[Groups(['fantasyUser:read'])]
    #[ORM\ManyToOne(targetEntity: FantasyTeam::class, inversedBy: 'fantasyUsers')]
    private ?FantasyTeam $fantasyTeam = null;

    #[Groups(['fantasyUser:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isExoticUser = false;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    /**
     * Plain password used before encryption. Not persisted in database.
     */
    private ?string $plainPassword = null;

    #[Groups(['fantasyUser:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isSynchronizationActive = true;

    #[Groups(['fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyPoints = null;

    #[Groups(['fantasyUser:read'])]
    #[ORM\Column(type: 'integer')]
    private ?int $fantasyRank = null;

    #[ORM\OneToMany(mappedBy: 'fantasyUser', targetEntity: FantasyPick::class, orphanRemoval: true)]
    #[ORM\OrderBy(value: ['pickedAt' => 'ASC'])]
    private Collection $fantasyPicks;

    #[ORM\OneToMany(mappedBy: 'fantasyUser', targetEntity: FantasyUserRanking::class, orphanRemoval: true)]
    #[ORM\OrderBy(value: ['rankingAt' => 'ASC'])]
    private Collection $fantasyUserRankings;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $registeredAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastLoginAt = null;

    public function __construct()
    {
        $this->fantasyPicks = new ArrayCollection();
        $this->fantasyUserRankings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getTtflId(): ?int
    {
        return $this->ttflId;
    }

    public function setTtflId(int $ttflId): self
    {
        $this->ttflId = $ttflId;

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

    public function getIsExoticUser(): bool
    {
        return $this->isExoticUser;
    }

    public function setIsExoticUser(bool $isExoticUser): self
    {
        $this->isExoticUser = $isExoticUser;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return (string) $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getIsSynchronizationActive(): bool
    {
        return $this->isSynchronizationActive;
    }

    public function setIsSynchronizationActive(bool $isSynchronizationActive): self
    {
        $this->isSynchronizationActive = $isSynchronizationActive;

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

    public function getFantasyPicks(): Collection
    {
        return $this->fantasyPicks;
    }

    public function addFantasyPick(FantasyPick $fantasyPick): self
    {
        if (!$this->fantasyPicks->contains($fantasyPick)) {
            $this->fantasyPicks[] = $fantasyPick;
            $fantasyPick->setFantasyUser($this);
        }

        return $this;
    }

    public function removeFantasyPick(FantasyPick $fantasyPick): self
    {
        if ($this->fantasyPicks->contains($fantasyPick)) {
            $this->fantasyPicks->removeElement($fantasyPick);
            if ($fantasyPick->getFantasyUser() === $this) {
                $fantasyPick->setFantasyUser(null);
            }
        }

        return $this;
    }

    public function getFantasyUserRankings(): Collection
    {
        return $this->fantasyUserRankings;
    }

    public function addFantasyUserRanking(FantasyUserRanking $fantasyUserRanking): self
    {
        if (!$this->fantasyUserRankings->contains($fantasyUserRanking)) {
            $this->fantasyUserRankings[] = $fantasyUserRanking;
            $fantasyUserRanking->setFantasyUser($this);
        }

        return $this;
    }

    public function removeFantasyUserRanking(FantasyUserRanking $fantasyUserRanking): self
    {
        if ($this->fantasyUserRankings->contains($fantasyUserRanking)) {
            $this->fantasyUserRankings->removeElement($fantasyUserRanking);
            if ($fantasyUserRanking->getFantasyUser() === $this) {
                $fantasyUserRanking->setFantasyUser(null);
            }
        }

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeInterface $lastLoginAt): self
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @Groups({"fantasyUser:read"})
     */
    public function getLastFantasyPick(): ?FantasyPick
    {
        return ($this->fantasyPicks->count()) ? $this->fantasyPicks->last() : null;
    }
}
