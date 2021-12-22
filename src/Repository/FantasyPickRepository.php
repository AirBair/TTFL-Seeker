<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FantasyPick;
use App\Entity\FantasyUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FantasyPickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FantasyPick::class);
    }

    public function findUniqueByDate(int $nbaSeasonYear, bool $isPlayoffs, FantasyUser $fantasyUser, \DateTime $rankingAt): ?FantasyPick
    {
        $result = $this->createQueryBuilder('fp')
            ->select('fp')
            ->where('fp.season = :season')
            ->andWhere('fp.isPlayoffs = :isPlayoffs')
            ->andWhere('fp.fantasyUser = :fantasyUser')
            ->andWhere('fp.pickedAt = :pickedAt')
            ->setParameter('season', $nbaSeasonYear)
            ->setParameter('isPlayoffs', $isPlayoffs)
            ->setParameter('fantasyUser', $fantasyUser)
            ->setParameter('pickedAt', $rankingAt->format('Y-m-d'))
            ->getQuery()
            ->getOneOrNullResult();

        return ($result instanceof FantasyPick) ? $result : null;
    }
}
