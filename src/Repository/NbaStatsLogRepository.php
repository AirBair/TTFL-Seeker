<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NbaStatsLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class NbaStatsLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbaStatsLog::class);
    }
}
