<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NbaPlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups": {"nbaPlayer:read"}},
 *     denormalizationContext={"groups": {"nbaPlayer:write"}},
 *     collectionOperations={"get"},
 *     itemOperations={"get"}
 * )
 *
 * @ORM\Entity(repositoryClass=NbaPlayerRepository::class)
 */
class NbaPlayer
{
    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $jersey;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="boolean")
     */
    private $isInjured;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\ManyToOne(targetEntity=NbaTeam::class, inversedBy="nbaPlayers")
     */
    private $nbaTeam;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $averageFantasyPoints;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $pastYearFantasyPoints;

    /**
     * @Groups({"nbaPlayer:read"})
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __toString(): string
    {
        return (string) ($this->lastName.' '.$this->firstName);
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
