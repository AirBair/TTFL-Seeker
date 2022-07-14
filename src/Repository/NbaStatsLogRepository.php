<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<NbaStatsLog>
 */
class NbaStatsLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbaStatsLog::class);
    }

    public function getBestFantasyScore(\DateTime $day): int
    {
        $result = $this->createQueryBuilder('nsl')
            ->select('MAX(nsl.fantasyPoints)')
            ->leftJoin('nsl.nbaGame', 'ng')
            ->where('ng.gameDay = :gameDay')
            ->setParameter('gameDay', $day)
            ->getQuery()
            ->getSingleScalarResult();

        return (is_numeric($result)) ? (int) $result : 0;
    }

    /**
     * @return array<NbaStatsLog>
     */
    public function findByGameDayAndFantasyPoints(\DateTime $day, int $fantasyPoints): array
    {
        $result = $this->createQueryBuilder('nsl')
            ->select('nsl')
            ->leftJoin('nsl.nbaGame', 'ng')
            ->where('ng.gameDay = :gameDay')
            ->andWhere('nsl.fantasyPoints = :fantasyPoints')
            ->setParameter('gameDay', $day)
            ->setParameter('fantasyPoints', $fantasyPoints)
            ->getQuery()
            ->getResult();

        return (\is_array($result)) ? $result : [];
    }

    public function getAvgFantasyPointsOfNbaPlayerOnSeason(NbaPlayer $nbaPlayer, int $season): float
    {
        $result = $this->getEntityManager()
            ->createQuery('
                SELECT AVG(nsl.fantasyPoints)
                FROM '.NbaStatsLog::class.' nsl
                JOIN nsl.nbaGame ng
                WHERE
                    nsl.nbaPlayer = :nbaPlayer AND
                    ng.season = :season AND
                    ng.isPlayoffs = 0 AND
                    nsl.minutesPlayed > 0
            ')
            ->setParameter('nbaPlayer', $nbaPlayer)
            ->setParameter('season', $season)
            ->getSingleScalarResult();

        return (is_numeric($result)) ? (float) $result : 0.0;
    }
}
