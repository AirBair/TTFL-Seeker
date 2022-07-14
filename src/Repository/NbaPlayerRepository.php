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
}
