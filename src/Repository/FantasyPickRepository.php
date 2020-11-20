<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FantasyPick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FantasyPickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FantasyPick::class);
    }
}
