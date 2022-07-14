<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NbaTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<NbaTeam>
 */
class NbaTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbaTeam::class);
    }
}
