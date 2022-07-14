<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\FantasyUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template-extends ServiceEntityRepository<FantasyUser>
 */
class FantasyUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FantasyUser::class);
    }
}
