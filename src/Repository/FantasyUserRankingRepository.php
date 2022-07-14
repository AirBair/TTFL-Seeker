<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FantasyUser;
use App\Entity\FantasyUserRanking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<FantasyUserRanking>
 */
class FantasyUserRankingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FantasyUserRanking::class);
    }

    public function findUniqueByDate(int $nbaSeasonYear, bool $isPlayoffs, FantasyUser $fantasyUser, \DateTime $rankingAt): ?FantasyUserRanking
    {
        $result = $this->createQueryBuilder('fur')
            ->select('fur')
            ->where('fur.season = :season')
            ->andWhere('fur.isPlayoffs = :isPlayoffs')
            ->andWhere('fur.fantasyUser = :fantasyUser')
            ->andWhere('fur.rankingAt = :rankingAt')
            ->setParameter('season', $nbaSeasonYear)
            ->setParameter('isPlayoffs', $isPlayoffs)
            ->setParameter('fantasyUser', $fantasyUser)
            ->setParameter('rankingAt', $rankingAt->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();

        return ($result instanceof FantasyUserRanking) ? $result : null;
    }
}
