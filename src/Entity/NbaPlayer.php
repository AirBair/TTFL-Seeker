<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\NbaPlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['nbaPlayer:read']],
    denormalizationContext: ['groups' => ['nbaPlayer:write']]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'id', 'firstName', 'lastName', 'fullName', 'position', 'jersey', 'isInjured', 'nbaTeam.fullName', 'averageFantasyPoints', 'pastYearFantasyPoints', 'isAllowedInExoticLeague', 'updatedAt',
])]
#[ApiFilter(SearchFilter::class, properties: [
    'firstName' => 'partial',
    'lastName' => 'partial',
    'fullName' => 'partial',
    'position' => 'exact',
    'jersey' => 'exact',
    'nbaTeam' => 'exact',
    'nbaTeam.fullName' => 'partial',
])]
#[ApiFilter(BooleanFilter::class, properties: [
    'isInjured', 'isAllowedInExoticLeague',
])]
#[ApiFilter(RangeFilter::class, properties: [
    'averageFantasyPoints', 'pastYearFantasyPoints',
])]
#[ORM\Entity(repositoryClass: NbaPlayerRepository::class)]
class NbaPlayer implements \Stringable
{
    #[Groups(['nbaPlayer:read', 'fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $id = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $firstName = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $lastName = null;

    #[Groups(['nbaPlayer:read', 'fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $fullName = null;

    #[Groups(['nbaPlayer:read', 'fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $fullNameInTtfl = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $position = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $jersey = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isInjured = false;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\ManyToOne(targetEntity: NbaTeam::class, inversedBy: 'nbaPlayers')]
    private ?NbaTeam $nbaTeam = null;

    #[Groups(['nbaPlayer:read', 'fantasyPick:read', 'fantasyUser:read'])]
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $averageFantasyPoints = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $pastYearFantasyPoints = null;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'boolean')]
    private bool $isAllowedInExoticLeague = true;

    #[Groups(['nbaPlayer:read'])]
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    #[\Override]
    public function __toString(): string
    {
        return $this->lastName.' '.$this->firstName;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getFullNameInTtfl(): ?string
    {
        return $this->fullNameInTtfl;
    }

    public function setFullNameInTtfl(string $fullNameInTtfl): self
    {
        $this->fullNameInTtfl = $fullNameInTtfl;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getJersey(): ?string
    {
        return $this->jersey;
    }

    public function setJersey(string $jersey): self
    {
        $this->jersey = $jersey;

        return $this;
    }

    public function getIsInjured(): ?bool
    {
        return $this->isInjured;
    }

    public function setIsInjured(bool $isInjured): self
    {
        $this->isInjured = $isInjured;

        return $this;
    }

    public function getNbaTeam(): ?NbaTeam
    {
        return $this->nbaTeam;
    }

    public function setNbaTeam(?NbaTeam $nbaTeam): self
    {
        $this->nbaTeam = $nbaTeam;

        return $this;
    }

    public function getAverageFantasyPoints(): ?float
    {
        return $this->averageFantasyPoints;
    }

    public function setAverageFantasyPoints(?float $averageFantasyPoints): self
    {
        $this->averageFantasyPoints = $averageFantasyPoints;

        return $this;
    }

    public function getPastYearFantasyPoints(): ?float
    {
        return $this->pastYearFantasyPoints;
    }

    public function setPastYearFantasyPoints(?float $pastYearFantasyPoints): self
    {
        $this->pastYearFantasyPoints = $pastYearFantasyPoints;

        return $this;
    }

    public function getIsAllowedInExoticLeague(): bool
    {
        return $this->isAllowedInExoticLeague;
    }

    public function setIsAllowedInExoticLeague(bool $isAllowedInExoticLeague): self
    {
        $this->isAllowedInExoticLeague = $isAllowedInExoticLeague;

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
