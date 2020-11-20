<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FantasyUserRanking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FantasyUserRankingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FantasyUserRanking::class);
    }
}
