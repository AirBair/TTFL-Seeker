<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use Doctrine\ORM\EntityManagerInterface;

class FantasyPointsCalculator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @required */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    public function calculatePlayerGameFantasyPoints(NbaStatsLog $nbaStatsLog): int
    {
        $plus =
            $nbaStatsLog->getPoints() +
            $nbaStatsLog->getAssists() +
            $nbaStatsLog->getRebounds() +
            $nbaStatsLog->getSteals() +
            $nbaStatsLog->getBlocks() +
            $nbaStatsLog->getFieldGoals() +
            $nbaStatsLog->getThreePointsFieldGoals() +
            $nbaStatsLog->getFreeThrows();

        $minus =
            $nbaStatsLog->getTurnovers() +
            $nbaStatsLog->getFieldGoalsAttempts() - $nbaStatsLog->getFieldGoals() +
            $nbaStatsLog->getThreePointsFieldGoalsAttempts() - $nbaStatsLog->getThreePointsFieldGoals() +
            $nbaStatsLog->getFreeThrowsAttempts() - $nbaStatsLog->getFreeThrows();

        return $plus - $minus;
    }

    public function calculateNbaPlayersAverageFantasyPoints(int $season, bool $isForPastYear = false): int
    {
        $players = $this->entityManager->getRepository(NbaPlayer::class)->findAll();

        $nbaStatsLogRepository = $this->entityManager->getRepository(NbaStatsLog::class);
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
