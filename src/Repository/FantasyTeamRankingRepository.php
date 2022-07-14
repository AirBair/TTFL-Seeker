<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FantasyTeam;
use App\Entity\FantasyTeamRanking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<FantasyTeamRanking>
 */
class FantasyTeamRankingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FantasyTeamRanking::class);
    }

    public function findUniqueByDate(int $nbaSeasonYear, bool $isPlayoffs, FantasyTeam $fantasyTeam, \DateTime $rankingAt): ?FantasyTeamRanking
    {
        $result = $this->createQueryBuilder('ftr')
            ->select('ftr')
            ->where('ftr.season = :season')
            ->andWhere('ftr.isPlayoffs = :isPlayoffs')
            ->andWhere('ftr.fantasyTeam = :fantasyTeam')
            ->andWhere('ftr.rankingAt = :rankingAt')
            ->setParameter('season', $nbaSeasonYear)
            ->setParameter('isPlayoffs', $isPlayoffs)
            ->setParameter('fantasyTeam', $fantasyTeam)
            ->setParameter('rankingAt', $rankingAt->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();

        return ($result instanceof FantasyTeamRanking) ? $result : null;
    }
}
