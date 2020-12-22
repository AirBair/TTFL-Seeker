<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolveFilePathSubscriber implements EventSubscriberInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || (
                !is_a($attributes['resource_class'], NbaTeam::class, true) &&
                !is_a($attributes['resource_class'], NbaPlayer::class, true) &&
                !is_a($attributes['resource_class'], NbaGame::class, true) &&
                !is_a($attributes['resource_class'], NbaStatsLog::class, true)
            )) {
            return;
        }

        $entities = $controllerResult;

        if (!is_iterable($entities)) {
            $entities = [$entities];
        }

        foreach ($entities as $entity) {
            if ($entity instanceof NbaTeam) {
                $entity->setLogoFilePath($this->storage->resolveUri($entity, 'logoFile'));
            }
            if ($entity instanceof NbaPlayer && null !== $entity->getNbaTeam()) {
                $entity->getNbaTeam()->setLogoFilePath($this->storage->resolveUri($entity->getNbaTeam(), 'logoFile'));
            }
            if ($entity instanceof NbaStatsLog) {
                $entity->getNbaTeam()->setLogoFilePath($this->storage->resolveUri($entity->getNbaTeam(), 'logoFile'));
                $entity->getNbaGame()->getLocalNbaTeam()->setLogoFilePath($this->storage->resolveUri($entity->getNbaGame()->getLocalNbaTeam(), 'logoFile'));
                $entity->getNbaGame()->getVisitorNbaTeam()->setLogoFilePath($this->storage->resolveUri($entity->getNbaGame()->getVisitorNbaTeam(), 'logoFile'));
            }
            if ($entity instanceof NbaGame) {
                $entity->getLocalNbaTeam()->setLogoFilePath($this->storage->resolveUri($entity->getLocalNbaTeam(), 'logoFile'));
                $entity->getVisitorNbaTeam()->setLogoFilePath($this->storage->resolveUri($entity->getVisitorNbaTeam(), 'logoFile'));
            }
        }
    }
}
