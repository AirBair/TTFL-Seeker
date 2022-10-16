<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NbaPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<NbaPlayer>
 */
class NbaPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbaPlayer::class);
    }

    public function setInactivePlayersAsFreeAgent(\DateTimeInterface $notSyncedSince): int
    {
        $results = $this->getEntityManager()->createQueryBuilder()
            ->update(NbaPlayer::class, 'np')
            ->set('np.nbaTeam', 'NULL')
            ->where('np.updatedAt < :updatedAt AND np.nbaTeam IS NOT NULL')
            ->setParameter('updatedAt', $notSyncedSince->format('Y-m-d H:i:s'))
            ->getQuery()
            ->execute();

        return is_numeric($results) ? (int) $results : 0;
    }
}
