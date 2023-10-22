<?php

declare(strict_types=1);

namespace App\EventListener\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::prePersist)]
#[AsDoctrineListener(event: Events::preUpdate)]
class TimestampableListener
{
    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        $this->generateTimestampableAttributes($entity, [
            'setRegisteredAt',
            'setUpdatedAt',
        ]);
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        $this->generateTimestampableAttributes($entity, [
            'setUpdatedAt',
        ]);
    }

    /**
     * @param array<string> $methods
     */
    public function generateTimestampableAttributes(object $entity, array $methods): object
    {
        foreach ($methods as $method) {
            if (method_exists($entity, $method)) {
                $entity->{$method}(new \DateTimeImmutable());
            }
        }

        return $entity;
    }
}
