<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\NbaStatsLog;

class FantasyPointsCalculator
{
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
}
