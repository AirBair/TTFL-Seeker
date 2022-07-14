<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TimestampableSubscriber implements EventSubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        $this->generateTimestampableAttributes($entity, [
            'setRegisteredAt',
            'setUpdatedAt',
        ]);
    }

    public function preUpdate(LifecycleEventArgs $eventArgs): void
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
