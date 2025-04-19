<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Repository\NbaStatsLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class FantasyPointsCalculator
{
    private EntityManagerInterface $entityManager;

    #[Required]
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function calculatePlayerGameFantasyPoints(NbaStatsLog $nbaStatsLog): int
    {
        $plus
            = $nbaStatsLog->getPoints()
            + $nbaStatsLog->getAssists()
            + $nbaStatsLog->getRebounds()
            + $nbaStatsLog->getSteals()
            + $nbaStatsLog->getBlocks()
            + $nbaStatsLog->getFieldGoals()
            + $nbaStatsLog->getThreePointsFieldGoals()
            + $nbaStatsLog->getFreeThrows();

        $minus
            = $nbaStatsLog->getTurnovers()
            + $nbaStatsLog->getFieldGoalsAttempts() - $nbaStatsLog->getFieldGoals()
            + $nbaStatsLog->getThreePointsFieldGoalsAttempts() - $nbaStatsLog->getThreePointsFieldGoals()
            + $nbaStatsLog->getFreeThrowsAttempts() - $nbaStatsLog->getFreeThrows();

        return $plus - $minus;
    }

    public function calculateNbaPlayersAverageFantasyPoints(int $season, bool $isForPastYear = false): int
    {
        $players = $this->entityManager->getRepository(NbaPlayer::class)->findAll();

        /** @var NbaStatsLogRepository $nbaStatsLogRepository */
        $nbaStatsLogRepository = $this->entityManager->getRepository(NbaStatsLog::class);

        /** @var NbaPlayer $player */
        foreach ($players as $player) {
            $avgFantasyPoints = $nbaStatsLogRepository->getAvgFantasyPointsOfNbaPlayerOnSeason($player, $season);

            if ($isForPastYear) {
                $player->setPastYearFantasyPoints($avgFantasyPoints);
            } else {
                $player->setAverageFantasyPoints($avgFantasyPoints);
            }
        }

        $this->entityManager->flush();

        return \count($players);
    }
}
