<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NbaStatsLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NbaStatsLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbaStatsLog::class);
    }

    public function getBestFantasyScore(\DateTime $day): int
    {
        return (int) $this->createQueryBuilder('nsl')
            ->select('MAX(nsl.fantasyPoints)')
            ->leftJoin('nsl.nbaGame', 'ng')
            ->where('ng.gameDay = :gameDay')
            ->setParameter('gameDay', $day)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByGameDayAndFantasyPoints(\DateTime $day, int $fantasyPoints): array
    {
        return $this->createQueryBuilder('nsl')
            ->select('nsl')
            ->leftJoin('nsl.nbaGame', 'ng')
            ->where('ng.gameDay = :gameDay')
            ->andWhere('nsl.fantasyPoints = :fantasyPoints')
            ->setParameter('gameDay', $day)
            ->setParameter('fantasyPoints', $fantasyPoints)
            ->getQuery()
            ->getResult();
    }
}
