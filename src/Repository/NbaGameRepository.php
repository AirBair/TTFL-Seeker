<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NbaGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<NbaGame>
 */
class NbaGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NbaGame::class);
    }
}
