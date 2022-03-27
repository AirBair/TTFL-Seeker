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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
    denormalizationContext: ['groups' => ['nbaTeam:write']],
    normalizationContext: ['groups' => ['nbaTeam:read']]
)]
#[ApiFilter(OrderFilter::class, properties: [
    'id', 'city', 'nickname', 'fullName', 'tricode', 'conference', 'division', 'updatedAt',
])]
#[ApiFilter(SearchFilter::class, properties: [
    'city' => 'partial',
    'nickname' => 'partial',
    'fullName' => 'partial',
    'tricode' => 'exact',
    'conference' => 'exact',
    'division' => 'exact',
])]
#[ORM\Entity(repositoryClass: NbaTeamRepository::class)]
class NbaTeam
{
    #[Groups(['nbaTeam:read', 'nbaPlayer:read', 'nbaGame:read', 'nbaStatsLog:read'])]
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $id = null;

    #[Groups(['nbaTeam:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $city = null;

    #[Groups(['nbaTeam:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nickname = null;

    #[Groups(['nbaTeam:read', 'nbaPlayer:read', 'nbaGame:read', 'nbaStatsLog:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $fullName = null;

    #[Groups(['nbaTeam:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $tricode = null;

    #[Groups(['nbaTeam:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $conference = null;

    #[Groups(['nbaTeam:read'])]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $division = null;

    /**
     * @Vich\UploadableField(mapping="nba_teams_logos", fileNameProperty="logoFileName")
     */
    private ?File $logoFile = null;
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $logoFileName = null;

    #[Groups(['nbaTeam:read', 'nbaPlayer:read', 'nbaGame:read', 'nbaStatsLog:read'])]
    private ?string $logoFilePath = null;

    #[Groups(['nbaTeam:read', 'nbaPlayer:read', 'nbaGame:read', 'nbaStatsLog:read'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $primaryColor = null;

    /**
     * @var Collection<int, NbaPlayer>
     */
    #[Groups(['nbaTeam:read'])]
    #[ORM\OneToMany(mappedBy: 'nbaTeam', targetEntity: NbaPlayer::class)]
    private Collection $nbaPlayers;

    #[Groups(['nbaTeam:read'])]
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->nbaPlayers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getCity().' '.$this->getNickname();
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

    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine, otherwise the event listeners won't be called and the file is lost.
            $this->setUpdatedAt(new \DateTimeImmutable());
        }
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoFileName(?string $logoFileName): self
    {
        $this->logoFileName = $logoFileName;

        return $this;
    }

    public function getLogoFileName(): ?string
    {
        return $this->logoFileName;
    }

    public function setLogoFilePath(?string $logoFilePath): self
    {
        $this->logoFilePath = $logoFilePath;

        return $this;
    }

    public function getLogoFilePath(): ?string
    {
        return $this->logoFilePath;
    }

    public function setPrimaryColor(?string $primaryColor): self
    {
        $this->primaryColor = $primaryColor;

        return $this;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primaryColor;
    }

    /**
     * @return Collection<int, NbaPlayer>
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
