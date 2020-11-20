<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NbaPlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 *
 * @ORM\Entity(repositoryClass=NbaPlayerRepository::class)
 */
class NbaPlayer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jersey;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isInjured;

    /**
     * @ORM\ManyToOne(targetEntity=NbaTeam::class, inversedBy="nbaPlayers")
     */
    private $nbaTeam;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $averageFantasyPoints;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pastYearFantasyPoints;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

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
