<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FantasyTeam;
use App\Entity\FantasyTeamRanking;
use App\Entity\FantasyUser;
use App\Entity\FantasyUserRanking;
use Doctrine\ORM\EntityManagerInterface;

class CsvImporter
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var int
     */
    private $nbaSeasonYear;

    /**
     * @var bool
     */
    private $isNbaPlayoffs;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->nbaSeasonYear = (int) $_ENV['NBA_YEAR'];
        $this->isNbaPlayoffs = (bool) $_ENV['NBA_PLAYOFFS'];
    }

    public function importPoints(string $filePath): int
    {
        $lines = 0;

        $headers = [
            0 => 'date',
            1 => 'AirBair',
            2 => 'Baptnal',
            3 => 'bendhi',
            4 => 'Boboxy',
            5 => 'HYPERION',
            6 => 'Piix77',
            7 => 'Tibulle',
            8 => 'Tomigo',
            9 => 'Raptor1977',
            10 => 'Scred',
            11 => 'StarSeeker',
        ];

        if (false !== ($handle = fopen($filePath, 'r'))) {
            while (false !== ($line = fgetcsv($handle, 1000, ','))) {
                $rankingAt = new \DateTime($line[0]);

                for ($column = 1; $column < 11; ++$column) {
                    $this->csvFantasyPointsColumnToFantasyUserRanking($headers[$column], (int) $line[$column], $rankingAt);
                }

                $this->csvFantasyPointsColumnToFantasyTeamRanking($headers[11], (int) $line[11], $rankingAt);

                $this->entityManager->flush();

                ++$lines;
            }
            fclose($handle);
        }

        return $lines;
    }

    public function csvFantasyPointsColumnToFantasyUserRanking(string $username, int $fantasyPoints, \DateTime $rankingAt): FantasyUserRanking
    {
        $fantasyUser = $this->entityManager->getRepository(FantasyUser::class)->findOneBy(['username' => $username]);

        $fantasyUserRanking = $this->entityManager->getRepository(FantasyUserRanking::class)->findOneBy([
            'fantasyUser' => $fantasyUser,
            'rankingAt' => $rankingAt,
        ]);

        if (null === $fantasyUserRanking) {
            $fantasyUserRanking = (new FantasyUserRanking())->setFantasyRank(0);
        }

        $fantasyUserRanking
            ->setFantasyUser($fantasyUser)
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyPoints($fantasyPoints)
            ->setRankingAt($rankingAt);

        if (null === $fantasyUserRanking->getId()) {
            $this->entityManager->persist($fantasyUserRanking);
        }

        return $fantasyUserRanking;
    }

    public function csvFantasyPointsColumnToFantasyTeamRanking(string $teamName, int $fantasyPoints, \DateTime $rankingAt): FantasyTeamRanking
    {
        $fantasyTeam = $this->entityManager->getRepository(FantasyTeam::class)->findOneBy(['name' => $teamName]);

        $fantasyTeamRanking = $this->entityManager->getRepository(FantasyTeamRanking::class)->findOneBy([
            'fantasyTeam' => $fantasyTeam,
            'rankingAt' => $rankingAt,
        ]);

        if (null === $fantasyTeamRanking) {
            $fantasyTeamRanking = (new FantasyTeamRanking())->setFantasyRank(0);
        }

        $fantasyTeamRanking
            ->setFantasyTeam($fantasyTeam)
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyPoints($fantasyPoints)
            ->setRankingAt($rankingAt);

        if (null === $fantasyTeamRanking->getId()) {
            $this->entityManager->persist($fantasyTeamRanking);
        }

        return $fantasyTeamRanking;
    }

    public function importRanks(string $filePath): int
    {
        $lines = 0;

        $headers = [
            0 => 'date',
            1 => 'AirBair',
            2 => 'Baptnal',
            3 => 'bendhi',
            4 => 'Boboxy',
            5 => 'HYPERION',
            6 => 'Piix77',
            7 => 'Tibulle',
            8 => 'Tomigo',
            9 => 'Raptor1977',
            10 => 'Scred',
            11 => 'StarSeeker',
        ];

        if (false !== ($handle = fopen($filePath, 'r'))) {
            while (false !== ($line = fgetcsv($handle, 1000, ','))) {
                $rankingAt = new \DateTime($line[0]);

                for ($column = 1; $column < 11; ++$column) {
                    $this->csvFantasyRankColumnToFantasyUserRanking($headers[$column], (int) $line[$column], $rankingAt);
                }

                $this->csvFantasyRankColumnToFantasyTeamRanking($headers[11], (int) $line[11], $rankingAt);

                $this->entityManager->flush();

                ++$lines;
            }
            fclose($handle);
        }

        return $lines;
    }

    public function csvFantasyRankColumnToFantasyUserRanking(string $username, int $fantasyRank, \DateTime $rankingAt): FantasyUserRanking
    {
        $fantasyUser = $this->entityManager->getRepository(FantasyUser::class)->findOneBy(['username' => $username]);

        $fantasyUserRanking = $this->entityManager->getRepository(FantasyUserRanking::class)->findOneBy([
            'fantasyUser' => $fantasyUser,
            'rankingAt' => $rankingAt,
        ]);

        if (null === $fantasyUserRanking) {
            $fantasyUserRanking = (new FantasyUserRanking())->setFantasyPoints(0);
        }

        $fantasyUserRanking
            ->setFantasyUser($fantasyUser)
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyRank($fantasyRank)
            ->setRankingAt($rankingAt);

        if (null === $fantasyUserRanking->getId()) {
            $this->entityManager->persist($fantasyUserRanking);
        }

        return $fantasyUserRanking;
    }

    public function csvFantasyRankColumnToFantasyTeamRanking(string $teamName, int $fantasyRank, \DateTime $rankingAt): FantasyTeamRanking
    {
        $fantasyTeam = $this->entityManager->getRepository(FantasyTeam::class)->findOneBy(['name' => $teamName]);

        $fantasyTeamRanking = $this->entityManager->getRepository(FantasyTeamRanking::class)->findOneBy([
            'fantasyTeam' => $fantasyTeam,
            'rankingAt' => $rankingAt,
        ]);

        if (null === $fantasyTeamRanking) {
            $fantasyTeamRanking = (new FantasyTeamRanking())->setFantasyPoints(0);
        }

        $fantasyTeamRanking
            ->setFantasyTeam($fantasyTeam)
            ->setSeason($this->nbaSeasonYear)
            ->setIsPlayoffs($this->isNbaPlayoffs)
            ->setFantasyRank($fantasyRank)
            ->setRankingAt($rankingAt);

        if (null === $fantasyTeamRanking->getId()) {
            $this->entityManager->persist($fantasyTeamRanking);
        }

        return $fantasyTeamRanking;
    }
}
