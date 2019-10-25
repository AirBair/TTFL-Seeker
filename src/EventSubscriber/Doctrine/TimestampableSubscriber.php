<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class TimestampableSubscriber implements EventSubscriber
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

    public function generateTimestampableAttributes($entity, array $methods)
    {
        foreach ($methods as $method) {
            if (method_exists($entity, $method)) {
                $entity->$method(new \DateTimeImmutable());
            }
        }

        return $entity;
    }
}
